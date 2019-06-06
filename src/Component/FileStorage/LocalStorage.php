<?php
/**
 * Created by PhpStorm.
 * User: polyanin
 * Date: 04.09.2018
 * Time: 12:07
 */

namespace App\Component\FileStorage;


use App\Util\Path;
use Symfony\Component\Filesystem\Filesystem;

class LocalStorage
{
    /**
     * @var Path
     */
    private $fileStoragePath;

    /**
     * @var Path
     */
    private $publicDir;

    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * FileStorage constructor.
     * @param string $file_storage_path
     * @param string $public_dir
     */
    public function __construct($file_storage_path, $public_dir)
    {
        $this->fileStoragePath = new Path($file_storage_path);
        $this->publicDir = $public_dir;
        $this->filesystem = new Filesystem();
        if (!$this->filesystem->exists($this->fileStoragePath)) {
            $this->filesystem->mkdir($this->fileStoragePath);
        }
    }

    /**
     * @param $source_absolute_path
     * @param null $extension
     * @return array
     * @throws Exception
     */
    public function addFile($source_absolute_path, $extension = null)
    {
        $path = new Path($source_absolute_path);
        if (!$path->fileExists()) {
            $msg = 'Source file not exists: ' . $source_absolute_path;
            throw new Exception($msg);
        }
        // calculate file param
        $hash = md5_file($path->toString());
        $file_relative_dir = '/' . join('/', array_slice(str_split($hash, 3), 0, 3));
        $file_absolute_dir = new Path($this->fileStoragePath, $file_relative_dir);
        // check file dir
        if (!$file_absolute_dir->isDir()) {
            $this->filesystem->mkdir($file_absolute_dir, 0755);
        }
        if (!$file_absolute_dir->isDir()) {
            $msg = 'Unable to create directory: ' . $file_absolute_dir;
            throw new Exception($msg);
        }
        // handle extension
        if (is_null($extension)) {
            $extension = $path->extension();
        } else {
            settype($extension, 'string');
        }
        if (strlen($extension)) {
            $extension = '.' . strtolower($extension);
        }
        $file_relative_path = new Path($file_relative_dir, $hash . $extension);
        $file_absolute_path = new Path($this->fileStoragePath, $file_relative_path);
        $file_exists = false;
        if ($file_absolute_path->fileExists()) {
            $this->filesystem->chmod($file_absolute_path, 0644);
            $file_exists = true;
        } else {
            // trying to copy file
            $this->filesystem->copy($path, $file_absolute_path);
            if (!$file_absolute_path->fileExists()) {
                $msg = 'Unable to copy file';
                throw new Exception($msg);
            }
            // trying to change file mode
            $this->filesystem->chmod($file_absolute_path->toString(), 0644);
        }
        return array(
            'pathname' => $file_absolute_path->toString(),
            'exists' => $file_exists,
            'url' => '/' . $file_absolute_path->substract($this->publicDir)->toString()
        );
    }
}
