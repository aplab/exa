<?php
/**
 * Created by PhpStorm.
 * User: polyanin
 * Date: 30.07.2018
 * Time: 20:39
 */

namespace App\Component\ModuleMetadata;


use Doctrine\Common\Annotations\Annotation\Attribute;
use Doctrine\Common\Annotations\Annotation\Attributes;
use Doctrine\Common\Annotations\Annotation\Target;
use Doctrine\ORM\Mapping\Annotation;

/**
 * Class Property
 * @package App\Annotation\Module
 * @Annotation
 * @Target({"PROPERTY"})
 * @Attributes({
 *   @Attribute("title", type = "string", required=true),
 *   @Attribute("description", type = "string", required=false),
 *   @Attribute("help", type = "string", required=false),
 *   @Attribute("comment", type = "string", required=false),
 *   @Attribute("label", type = "string", required=false),
 *   @Attribute("cell", type = "array<App\Component\ModuleMetadata\Cell>", required=true),
 *   @Attribute("widget", type = "array<App\Component\ModuleMetadata\Widget>", required=true),
 *   @Attribute("readonly", type = "boolean")
 * })
 */
class Property implements Annotation
{
    /**
     * Property constructor.
     * @param array $values
     */
    public function __construct(array $values)
    {
        $this->title = $values['title'];
        $this->description = $values['description'] ?? '';
        $this->help = $values['help'] ?? '';
        $this->comment = $values['comment'] ?? '';
        $this->label = $values['label'] ?? '';
        $this->cell = $values['cell'] ?? [];
        $this->widget = $values['widget'] ?? [];
        $this->readonly = $values['readonly'] ?? false;
    }

    /**
     * @var Widget[]
     */
    private $widget;

    /**
     * @return Widget[]
     */
    public function getWidget(): array
    {
        return $this->widget;
    }

    /**
     * @param Widget[] $widget
     * @return Property
     */
    public function setWidget(array $widget): Property
    {
        $this->widget = $widget;
        return $this;
    }

    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $description;

    /**
     * @var string
     */
    private $help;

    /**
     * @var string
     */
    private $comment;

    /**
     * @var string
     */
    private $label;

    /**
     * @var Cell[]
     */
    private $cell;

    /**
     * @var boolean
     */
    private $readonly;

    /**
     * @return bool
     */
    public function isReadonly(): bool
    {
        return $this->readonly;
    }

    /**
     * @param bool $readonly
     * @return Property
     */
    public function setReadonly(bool $readonly): Property
    {
        $this->readonly = $readonly;
        return $this;
    }

    /**
     * @return array
     */
    public function getCell(): array
    {
        return $this->cell;
    }

    /**
     * @param Cell[]
     * @return Property
     */
    public function setCell(array $cell): Property
    {
        $this->cell = $cell;
        return $this;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return Property
     */
    public function setTitle(string $title): Property
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return Property
     */
    public function setDescription(string $description): Property
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return string
     */
    public function getHelp(): string
    {
        return $this->help;
    }

    /**
     * @param string $help
     * @return Property
     */
    public function setHelp(string $help): Property
    {
        $this->help = $help;
        return $this;
    }

    /**
     * @return string
     */
    public function getComment(): string
    {
        return $this->comment;
    }

    /**
     * @param string $comment
     * @return Property
     */
    public function setComment(string $comment): Property
    {
        $this->comment = $comment;
        return $this;
    }

    /**
     * @return string
     */
    public function getLabel(): string
    {
        return $this->label;
    }

    /**
     * @param string $label
     * @return Property
     */
    public function setLabel(string $label): Property
    {
        $this->label = $label;
        return $this;
    }
}
