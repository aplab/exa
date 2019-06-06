<?php
/**
 * Created by PhpStorm.
 * User: polyanin
 * Date: 27.08.2018
 * Time: 20:42
 */

namespace App\Component\InstanceEditor;


use App\Component\ModuleMetadata\ModuleMetadataRepository;
use Doctrine\ORM\EntityManagerInterface;

class InstatceEditorManager
{
    /**
     * @var ModuleMetadataRepository
     */
    protected $moduleMetadataRepository;

    /**
     * @var EntityManagerInterface
     */
    protected $entityManagerInterface;

    /**
     * InstatceEditorManager constructor.
     * @param ModuleMetadataRepository $module_metadata_repository
     * @param EntityManagerInterface $entity_manager_interface
     */
    public function __construct(ModuleMetadataRepository $module_metadata_repository,
                                EntityManagerInterface $entity_manager_interface)
    {
        $this->moduleMetadataRepository = $module_metadata_repository;
        $this->entityManagerInterface = $entity_manager_interface;
    }

    /**
     * @param object $entity
     * @return InstanceEditor
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @throws \ReflectionException
     */
    public function getInstanceEditor(object $entity): InstanceEditor
    {
        return new InstanceEditor($entity, $this);
    }

    /**
     * @return ModuleMetadataRepository
     */
    public function getModuleMetadataRepository(): ModuleMetadataRepository
    {
        return $this->moduleMetadataRepository;
    }

    /**
     * @return EntityManagerInterface
     */
    public function getEntityManagerInterface(): EntityManagerInterface
    {
        return $this->entityManagerInterface;
    }


}
