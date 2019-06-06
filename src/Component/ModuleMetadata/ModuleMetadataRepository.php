<?php
/**
 * Created by PhpStorm.
 * User: polyanin
 * Date: 02.08.2018
 * Time: 17:18
 */

namespace Aplab\AplabAdminBundle\Component\ModuleMetadata;


use Doctrine\Common\Annotations\Reader;
use Psr\SimpleCache\CacheInterface;

class ModuleMetadataRepository
{
    /**
     * @var string
     */
    const CACHE_KEY_DELIMITER = '.';

    /**
     * @var string
     */
    private $cacheKeyPrefix;

    /**
     * @var CacheInterface
     */
    private $cache;

    /**
     * @var Reader
     */
    private $reader;

    /**
     * ModuleMetadataRepository constructor.
     * @param CacheInterface $cache
     * @param Reader $reader
     */
    public function __construct(CacheInterface $cache, Reader $reader)
    {
        $class = get_class($this);
        $this->cacheKeyPrefix = md5($class);
        $this->cache = $cache;
        $this->reader = $reader;
    }

    /**
     * @param $object
     * @return ModuleMetadata
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @throws \ReflectionException
     */
    public function getMetadata($object)
    {
        $reflection_class = new \ReflectionClass($object);
        $class_name = $reflection_class->getName();
        $cache_key_suffix = md5($class_name);
        $cache_key = join(static::CACHE_KEY_DELIMITER, [
            $this->cacheKeyPrefix, $cache_key_suffix
        ]);
        $metadata = $this->cache->get($cache_key);
        if ($metadata instanceof ModuleMetadata) {
            return $metadata;
        }
        $metadata = new ModuleMetadata($reflection_class, $this->getReader());
        $env = $_SERVER['APP_ENV'] ?? 'dev';
        $this->cache->set($cache_key, $metadata);
        return $metadata;
    }

    /**
     * @return string
     */
    public function getCacheKeyPrefix(): string
    {
        return $this->cacheKeyPrefix;
    }

    /**
     * @return CacheInterface
     */
    public function getCache(): CacheInterface
    {
        return $this->cache;
    }

    /**
     * @return Reader
     */
    public function getReader(): Reader
    {
        return $this->reader;
    }
}