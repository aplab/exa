<?php
/**
 * Created by PhpStorm.
 * User: polyanin
 * Date: 02.08.2018
 * Time: 10:42
 */

namespace App\Component\DataTableRepresentation;


use App\Component\ModuleMetadata\ModuleMetadataRepository;
use App\Component\SystemState\SystemStateManager;
use App\Util\CssWidthDefinition;
use Doctrine\ORM\EntityManagerInterface;
use Psr\SimpleCache\InvalidArgumentException;
use ReflectionClass;
use ReflectionException;

class DataTableRepresentation
{
    /**
     * @var ModuleMetadataRepository
     */
    protected $moduleMetadataRepository;

    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * @var ReflectionClass[]
     */
    protected $entityReflectionClass = [];

    /**
     * @var DataTable[]
     */
    protected $dataTable = [];

    /**
     * @var CssWidthDefinition
     */
    protected $cssWidthDefinition;

    /**
     * @var SystemStateManager
     */
    protected $systemStateManager;

    /**
     * DataTable constructor.
     * @param ModuleMetadataRepository $module_metadata_repository
     * @param EntityManagerInterface $entity_manager_interface
     * @param CssWidthDefinition $css_width_definition
     * @param SystemStateManager $system_state_manager
     */
    public function __construct(
        ModuleMetadataRepository $module_metadata_repository,
        EntityManagerInterface $entity_manager_interface,
        CssWidthDefinition $css_width_definition,
        SystemStateManager $system_state_manager
    )
    {
        $this->moduleMetadataRepository = $module_metadata_repository;
        $this->entityManager = $entity_manager_interface;
        $this->cssWidthDefinition = $css_width_definition;
        $this->systemStateManager = $system_state_manager;
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
    public function getEntityManager(): EntityManagerInterface
    {
        return $this->entityManager;
    }

    /**
     * @param string $entity_class_name
     * @param null|string $type
     * @return DataTable
     * @throws InvalidArgumentException
     * @throws ReflectionException
     */
    public function getDataTable(string $entity_class_name, ?string $type = null):DataTable
    {
        $entity_reflection_class = new ReflectionClass($entity_class_name);
        $entity_class_name = $entity_reflection_class->getName();
        if (!isset($this->dataTable[$entity_class_name])) {
            $this->entityReflectionClass[$entity_class_name] = $entity_reflection_class;
            if (is_null($type)) {
                $this->dataTable[$entity_class_name] = new DataTable($entity_reflection_class, $this);
            } else {
                $this->dataTable[$entity_class_name] = new $type($entity_reflection_class, $this);
            }
        }
        return $this->dataTable[$entity_class_name];
    }

    /**
     * @return CssWidthDefinition
     */
    public function getCssWidthDefinition(): CssWidthDefinition
    {
        return $this->cssWidthDefinition;
    }

    /**
     * @return ReflectionClass[]
     */
    public function getEntityReflectionClass(): array
    {
        return $this->entityReflectionClass;
    }

    /**
     * @return SystemStateManager
     */
    public function getSystemStateManager(): SystemStateManager
    {
        return $this->systemStateManager;
    }
}
