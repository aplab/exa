<?php
/**
 * Created by PhpStorm.
 * User: polyanin
 * Date: 02.08.2018
 * Time: 16:59
 */

namespace Aplab\AplabAdminBundle\Component\ModuleMetadata;


use Doctrine\Common\Annotations\Reader;

class ModuleMetadata
{
    /**
     * @var string
     */
    protected $className;

    /**
     * ModuleMetadata constructor.
     *
     * @param \ReflectionClass $reflection_class
     * @param Reader $reader
     */
    public function __construct(\ReflectionClass $reflection_class, Reader $reader)
    {
        $this->className = $reflection_class->getName();
        $module_metadata = $reader->getClassAnnotation($reflection_class, Module::class);
        if ($module_metadata instanceof Module) {
            $this->module = $module_metadata;
        }
        $properties = $reflection_class->getProperties();
        foreach ($properties as $property) {
            $property_metadata = $reader->getPropertyAnnotation($property, Property::class);
            if ($property_metadata instanceof Property) {
                $this->properties[$property->getName()] = $property_metadata;
            }
        }
    }

    /**
     * @var Module
     */
    protected $module;

    /**
     * @var Property[]
     */
    protected $properties;

    /**
     * @return string
     */
    public function getClassName(): string
    {
        return $this->className;
    }

    /**
     * @return Module
     */
    public function getModule(): Module
    {
        return $this->module;
    }

    /**
     * @param Module $module
     * @return ModuleMetadata
     */
    public function setModule(Module $module): ModuleMetadata
    {
        $this->module = $module;
        return $this;
    }

    /**
     * @return Property[]
     */
    public function getProperties(): array
    {
        return $this->properties;
    }

    /**
     * @param Property[] $properties
     * @return ModuleMetadata
     */
    public function setProperties(array $properties): ModuleMetadata
    {
        $this->properties = $properties;
        return $this;
    }
}