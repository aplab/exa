<?php
/**
 * Created by PhpStorm.
 * User: polyanin
 * Date: 25.08.2018
 * Time: 8:59
 */

namespace Aplab\AplabAdminBundle\Component\SystemState;


use Aplab\AplabAdminBundle\Util\Path;
use Psr\Log\LoggerInterface;
use Symfony\Component\Filesystem\Filesystem;

class SystemStateManager
{
    /**
     * @var \ReflectionClass
     */
    private $reflectionClass;

    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * @var Path
     */
    private $dataDir;

    /**
     * @var SystemState[]
     */
    private $data;

    /**
     * SystemStateManager constructor.
     * @param $aplab_admin_data_dir
     * @throws \ReflectionException
     */
    public function __construct(string $aplab_admin_data_dir)
    {
        $this->reflectionClass = new \ReflectionClass($this);
        $this->filesystem = new Filesystem();
        $this->dataDir = new Path($aplab_admin_data_dir, $this->reflectionClass->getName(), 'data');
        $this->data = [];
        $this->filesystem->mkdir($this->dataDir);
    }

    /**
     * @return \ReflectionClass
     */
    public function getReflectionClass(): \ReflectionClass
    {
        return $this->reflectionClass;
    }

    /**
     * @return Filesystem
     */
    public function getFilesystem(): Filesystem
    {
        return $this->filesystem;
    }

    /**
     * @return string
     */
    public function getDataDir(): string
    {
        return $this->dataDir;
    }

    /**
     * @param int $id
     * @return SystemState
     */
    public function get(int $id = 0): SystemState
    {
        if (!isset($this->data[$id])) {
            $this->data[$id] = SystemState::create($id, $this);
        }
        if (!($this->data[$id] instanceof SystemState)) {
            $this->data[$id] = SystemState::create($id, $this);
        }
        return $this->data[$id];
    }

    /**
     * flush data if modified
     * @param LoggerInterface $logger
     */
    public function flush(LoggerInterface $logger)
    {
        foreach ($this->data as $item) {
            $path = new Path($this->dataDir, $item->getId());
            $data = serialize($item);
            $this->filesystem->dumpFile($path, $data);
        }
    }
}