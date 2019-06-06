<?php
/**
 * Created by PhpStorm.
 * User: polyanin
 * Date: 25.08.2018
 * Time: 9:00
 */

namespace Aplab\AplabAdminBundle\Component\SystemState;


use Aplab\AplabAdminBundle\Util\Path;

class SystemState
{
    /**
     * @var int
     */
    private $id;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * SystemState constructor.
     * @param int $id
     */
    private function __construct(int $id = 0)
    {
        $this->id = $id;
        $this->data = [];
    }

    /**
     * @var array
     */
    private $data;

    /**
     * @param $name
     * @return null
     */
    public function __get($name)
    {
        return $this->get($name);
    }

    /**
     * @param $name
     * @return DataBag
     */
    public function get($name)
    {
        $key = self::k($name);
        if (!array_key_exists($key, $this->data)) {
            $this->data[$key] = new DataBag;
        }
        if (!($this->data[$key] instanceof DataBag)) {
            $this->data[$key] = new DataBag;
        }
        return $this->data[$key];
    }

    /**
     * @param $name
     * @param $value
     */
    public function __set($name, $value)
    {
        throw new \RuntimeException('direct modification not allowed');
    }

    /**
     * @param $name
     * @return string
     */
    private static function k($name)
    {
        return md5(serialize($name));
    }

    /**
     * Уничтожить переменную
     *
     * @param string $name
     */
    public function purge($name)
    {
        $key = $this->k($name);
        unset($this->data[$key]);
    }

    /**
     * @param int $id
     * @param SystemStateManager $manager
     * @return self
     */
    public static function create(int $id, SystemStateManager $manager) {
        $path = new Path($manager->getDataDir(), $id);
        $fs = $manager->getFilesystem();
        if (!$fs->exists($path)) {
            return new static($id);
        }
        $data = file_get_contents($path);
        try {
            $o = unserialize($data);
            if (!($o instanceof static)) {
                throw new \RuntimeException('wrong data');
            }
            if ($o->getId() !== $id) {
                throw new \RuntimeException('id mismatch');
            }
            return $o;
        } catch (\Throwable $exception) {
            $fs->remove($path);
            return new static($id);
        }
    }
}