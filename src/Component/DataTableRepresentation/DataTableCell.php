<?php
/**
 * Created by PhpStorm.
 * User: polyanin
 * Date: 02.08.2018
 * Time: 10:46
 */

namespace Aplab\AplabAdminBundle\Component\DataTableRepresentation;


use Aplab\AplabAdminBundle\Component\DataTableRepresentation\CellType\CellTypeFactory;
use Aplab\AplabAdminBundle\Component\DataTableRepresentation\CellType\CellTypeInterface;
use Aplab\AplabAdminBundle\Component\ModuleMetadata\Cell;
use Aplab\AplabAdminBundle\Component\ModuleMetadata\Options;
use Aplab\AplabAdminBundle\Component\ModuleMetadata\Property;

class DataTableCell
{
    /**
     * @var string
     */
    private $propertyName;

    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $label;

    /**
     * @var string
     */
    private $comment;

    /**
     * @var string
     */
    private $help;

    /**
     * @var int
     */
    private $width;

    /**
     * @var int
     */
    private $order;

    /**
     * @var CellTypeInterface
     */
    private $type;

    /**
     * @var Options
     */
    private $options;

    /**
     * DataTableCell constructor.
     * @param \ReflectionProperty $property
     * @param Property $property_metadata
     * @param Cell $cell_metadata
     * @param CellTypeFactory $factory
     */
    public function __construct(\ReflectionProperty $property, Property $property_metadata,
                                Cell $cell_metadata, CellTypeFactory $factory)
    {
        $this->propertyName = $property->getName();
        $title = $cell_metadata->getTitle();
        if ($title) {
            $this->title = $title;
        } else {
            $this->title = $property_metadata->getTitle();
        }
        $this->label = $cell_metadata->getLabel();
        $this->comment = $cell_metadata->getComment();
        $this->help = $cell_metadata->getHelp();
        $this->width = $cell_metadata->getWidth();
        $this->order = $cell_metadata->getOrder();
        $this->type = $factory->create($this, $cell_metadata->getType());
        $this->options = $cell_metadata->getOptions();
    }

    /**
     * @return string
     */
    public function getPropertyName(): string
    {
        return $this->propertyName;
    }

    /**
     * @return int
     */
    public function getWidth(): int
    {
        return $this->width;
    }

    /**
     * @return int
     */
    public function getOrder(): int
    {
        return $this->order;
    }

    /**
     * @return CellTypeInterface
     */
    public function getType(): CellTypeInterface
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return Options
     */
    public function getOptions(): Options
    {
        return $this->options;
    }
}