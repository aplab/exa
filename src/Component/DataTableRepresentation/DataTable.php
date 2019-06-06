<?php
/**
 * Created by PhpStorm.
 * User: polyanin
 * Date: 20.08.2018
 * Time: 15:57
 */

namespace Aplab\AplabAdminBundle\Component\DataTableRepresentation;


use Aplab\AplabAdminBundle\Component\DataTableRepresentation\CellType\CellTypeFactory;
use Aplab\AplabAdminBundle\Component\DataTableRepresentation\Pager\Pager;
use Aplab\AplabAdminBundle\Component\ModuleMetadata\ModuleMetadata;
use Aplab\AplabAdminBundle\Util\CssWidthDefinition;
use Doctrine\ORM\EntityManagerInterface;

class DataTable
{
    /**
     * @var string
     */
    protected $entityClassName;

    /**
     * @var \ReflectionClass
     */
    protected $entityReflectionClass;

    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * @var ModuleMetadata
     */
    protected $moduleMetadata;

    /**
     * @var DataTableCell[];
     */
    protected $cell;

    /**
     * @var CssWidthDefinition
     */
    protected $cssWidthDefinition;

    /**
     * @var \Aplab\AplabAdminBundle\Component\SystemState\SystemState
     */
    protected $systemState;

    /**
     * READ-ONLY: The field names of all fields that are part of the identifier/primary key
     * of the mapped entity class.
     *
     * @var array
     */
    protected $identifier;

    /**
     * @return array
     */
    public function getIdentifier(): array
    {
        return $this->identifier;
    }

    /**
     * @param $item
     * @return false|string
     */
    public function helperIdentifierJson($item)
    {
        $data = [];
        foreach ($this->identifier as $i) {
            $getter = 'get' . ucfirst($i);
            $data[$i] = $item->$getter();
        }
        return json_encode($data);
    }

    /**
     * DataTable constructor.
     * @param \ReflectionClass $entity_reflection_class
     * @param DataTableRepresentation $data_table_representation
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @throws \ReflectionException
     */
    public function __construct(\ReflectionClass $entity_reflection_class,
                                DataTableRepresentation $data_table_representation)
    {
        $this->entityReflectionClass = $entity_reflection_class;
        $this->entityClassName = $this->entityReflectionClass->getName();
        $this->entityManager = $data_table_representation->getEntityManager();
        $this->moduleMetadata = $data_table_representation->getModuleMetadataRepository()
            ->getMetadata($this->entityClassName);
        $this->cssWidthDefinition = $data_table_representation->getCssWidthDefinition();
        $this->systemState = $data_table_representation->getSystemStateManager()
            ->get()->get($this->entityClassName . __CLASS__);
        $this->identifier = $this->entityManager->getClassMetadata($this->entityClassName)->identifier;
        $this->initCell();
    }

    /**
     * @return DataTableCell[]
     */
    public function getCell()
    {
        if (is_null($this->cell)) {
            $this->initCell();
        }
        return $this->cell;
    }

    /**
     * Data table cell initialization
     */
    protected function initCell(): void
    {
        $factory = new CellTypeFactory;
        $this->cell = [];
        $properties = $this->moduleMetadata->getProperties();
        foreach ($properties as $property_name => $property_metadata) {
            $cell_metadata_list = $property_metadata->getCell();
            $property = $this->entityReflectionClass->getProperty($property_name);
            foreach ($cell_metadata_list as $cell_metadata) {
                $this->cssWidthDefinition->add($cell_metadata->getWidth());
                $this->cell[] = new DataTableCell($property, $property_metadata, $cell_metadata, $factory);
            }
        }
        usort($this->cell, function (DataTableCell $a, DataTableCell $b) {
            return $a->getOrder() <=> $b->getOrder();
        });
    }

    /**
     * Temporary stub
     * @return object[]
     */
    public function getItems()
    {
        $pager = $this->getPager();
        return $this->entityManager->getRepository($this->entityClassName)->findBy(
            [],
            ['id' => 'DESC'],
            $pager->getItemsPerPage(),
            $pager->getOffset()
        );
    }

    /**
     * @var int
     */
    protected $count;

    /**
     * @param void
     * @return int
     */
    public function getCount():int
    {
        if (is_null($this->count)) {
            $this->count = $this->entityManager->getRepository($this->entityClassName)->count([]);
        }
        return $this->count;
    }

    /**
     * @var Pager
     */
    protected $pager;

    /**
     * @return Pager
     */
    public function getPager()
    {
        if (is_null($this->pager)) {
            $pager = $this->systemState->get('pager');
            if ($pager instanceof Pager) {
                $this->pager = $pager;
                $pager->setCount($this->getCount());
            } else {
                $this->pager = new Pager($this->getCount());
                $this->systemState->pager = $this->pager;
            }
        }
        return $this->pager;
    }

    /**
     * @return string
     */
    public function getEntityClassName(): string
    {
        return $this->entityClassName;
    }

    /**
     * @return \ReflectionClass
     */
    public function getEntityReflectionClass(): \ReflectionClass
    {
        return $this->entityReflectionClass;
    }

    /**
     * @return EntityManagerInterface
     */
    public function getEntityManager(): EntityManagerInterface
    {
        return $this->entityManager;
    }

    /**
     * @return ModuleMetadata
     */
    public function getModuleMetadata(): ModuleMetadata
    {
        return $this->moduleMetadata;
    }

    /**
     * @return CssWidthDefinition
     */
    public function getCssWidthDefinition(): CssWidthDefinition
    {
        return $this->cssWidthDefinition;
    }

    /**
     * @return \Aplab\AplabAdminBundle\Component\SystemState\SystemState
     */
    public function getSystemState(): \Aplab\AplabAdminBundle\Component\SystemState\SystemState
    {
        return $this->systemState;
    }
}