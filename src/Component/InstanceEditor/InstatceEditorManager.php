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
use Psr\SimpleCache\InvalidArgumentException;
use ReflectionException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

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

    protected $validatorInterface;

    /**
     * InstatceEditorManager constructor.
     * @param ModuleMetadataRepository $module_metadata_repository
     * @param EntityManagerInterface $entity_manager_interface
     * @param ValidatorInterface $validator_interface
     */
    public function __construct(ModuleMetadataRepository $module_metadata_repository,
                                EntityManagerInterface $entity_manager_interface,
                                ValidatorInterface $validator_interface)
    {
        $this->moduleMetadataRepository = $module_metadata_repository;
        $this->entityManagerInterface = $entity_manager_interface;
        $this->validatorInterface = $validator_interface;
    }

    /**
     * @param object $entity
     * @return InstanceEditor
     * @throws InvalidArgumentException
     * @throws ReflectionException
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

    /**
     * @return ValidatorInterface
     */
    public function getValidatorInterface(): ValidatorInterface
    {
        return $this->validatorInterface;
    }
}
