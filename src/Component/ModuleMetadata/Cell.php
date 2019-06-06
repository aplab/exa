<?php
/**
 * Created by PhpStorm.
 * User: polyanin
 * Date: 30.07.2018
 * Time: 20:39
 */

namespace Aplab\AplabAdminBundle\Component\ModuleMetadata;


use Doctrine\Common\Annotations\Annotation\Attribute;
use Doctrine\Common\Annotations\Annotation\Attributes;
use Doctrine\Common\Annotations\Annotation\Target;
use Doctrine\ORM\Mapping\Annotation;

/**
 * Class Cell
 * @package Aplab\AplabAdminBundle\Annotation\Module
 * @Annotation
 * @Target({"ANNOTATION"})
 * @Attributes({
 *      @Attribute("width", type="integer", required=true),
 *      @Attribute("order", type="integer", required=true),
 *      @Attribute("type", type="string", required=true),
 *      @Attribute("label", type="string", required=false),
 *      @Attribute("title", type="string", required=false),
 *      @Attribute("help", type="string", required=false),
 *      @Attribute("comment", type="string", required=false),
 *      @Attribute("options", type="Aplab\AplabAdminBundle\Component\ModuleMetadata\Options", required=false),
 *     })
 */
class Cell implements Annotation
{
    /**
     * Cell constructor.
     * @param array $values
     */
    public function __construct(array $values)
    {
        $this->width = $values['width'];
        $this->order = $values['order'];
        $this->type = $values['type'];
        $this->options = $values['options'] ?? new Options;
        $this->label = $values['label'] ?? '';
        $this->title = $values['title'] ?? '';
        $this->help = $values['help'] ?? '';
        $this->comment = $values['comment'] ?? '';
    }

    /**
     * @var Options
     */
    private $options;

    /**
     * @return Options
     */
    public function getOptions(): Options
    {
        return $this->options;
    }

    /**
     * @param Options $options
     * @return Cell
     */
    public function setOptions(Options $options): Cell
    {
        $this->options = $options;
        return $this;
    }

    /**
     * @var integer
     */
    private $width;

    /**
     * @var integer
     */
    private $order;

    /**
     * @var string
     */
    private $type;

    /**
     * @var string
     */
    private $label;

    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $help;

    /**
     * @var string
     */
    private $comment;

    /**
     * @return int
     */
    public function getWidth(): int
    {
        return $this->width;
    }

    /**
     * @param int $width
     * @return Cell
     */
    public function setWidth(int $width): Cell
    {
        $this->width = $width;
        return $this;
    }

    /**
     * @return int
     */
    public function getOrder(): int
    {
        return $this->order;
    }

    /**
     * @param int $order
     * @return Cell
     */
    public function setOrder(int $order): Cell
    {
        $this->order = $order;
        return $this;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     * @return Cell
     */
    public function setType(string $type): Cell
    {
        $this->type = $type;
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
     * @return Cell
     */
    public function setLabel(string $label): Cell
    {
        $this->label = $label;
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
     * @return Cell
     */
    public function setTitle(string $title): Cell
    {
        $this->title = $title;
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
     * @return Cell
     */
    public function setHelp(string $help): Cell
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
     * @return Cell
     */
    public function setComment(string $comment): Cell
    {
        $this->comment = $comment;
        return $this;
    }
}