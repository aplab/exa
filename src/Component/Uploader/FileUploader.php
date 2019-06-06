<?php
/**
 * This file is part of the Capsule package.
 *
 * (c) Alexander Polyanin 2006 <polyanin@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Date: 26.01.2017
 * Time: 0:10
 */

namespace Aplab\AplabAdminBundle\Component\Uploader;


use Aplab\AplabAdminBundle\Component\FileStorage\LocalStorage;
use Aplab\AplabAdminBundle\Entity\UserFiles\File;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class ImageUploader
 * @package App\Ajax\Controller
 */
class FileUploader
{
    /**
     * @var LocalStorage
     */
    private $storage;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * ImageUploader constructor.
     * @param LocalStorage $storage
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(LocalStorage $storage, EntityManagerInterface $entityManager)
    {
        $this->storage = $storage;
        $this->entityManager = $entityManager;
    }

    /**
     *
     */
    const FILES_VAR_NAME = 'file';



    /**
     * @return string
     * @throws Exception
     */
    public function receive()
    {
        if (!isset($_FILES[static::FILES_VAR_NAME])) {
            throw new Exception('the variable is not passed');
        }
        $file = $_FILES[static::FILES_VAR_NAME];
        $keys = array('name', 'type', 'size', 'tmp_name', 'error');
        foreach ($keys as $key) {
            if (!isset($file[$key])) {
                throw new Exception('the variable is not passed');
            }
            if (!is_scalar($file[$key])) {
                throw new Exception('wrong type of variable');
            }
        }

        $name = $file['name'];
        $type = $file['type'];
        $size = $file['size'];
        $path = $file['tmp_name'];
        $error = $file['error'];

        if ($error) {
            throw new Exception('upload error ' . Msg::msg($error));
        }

        if (!is_uploaded_file($path)) {
            throw new Exception('file was not uploaded');
        }

        $extension = strtolower(substr(strrchr($name, "."), 1));
        try {
            $result = $this->storage->addFile($path, $extension);
        } catch (\Throwable $e) {
            throw new Exception('Unable to store file: ' . $e->getMessage() . '[' . $e->getLine() . ']');
        }


        $filename = pathinfo($result['pathname'], PATHINFO_BASENAME);

        $o_file = new File();
        $o_file->setName($name);
        $o_file->setFilename($filename);
        $o_file->setContentType($type);

        $this->entityManager->persist($o_file);
        $this->entityManager->flush();

        return $o_file->getFilename();
    }
}
