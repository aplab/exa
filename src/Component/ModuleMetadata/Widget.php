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
 * Class Vidget
 * @package App\Annotation\Module
 * @Annotation
 * @Target({"ANNOTATION"})
 * @Attributes({
 *      @Attribute("tab", type="string", required=true),
 *      @Attribute("order", type="integer", required=true),
 *      @Attribute("type", type="string", required=true),
 *      @Attribute("label", type="string", required=false),
 *      @Attribute("title", type="string", required=false),
 *      @Attribute("help", type="string", required=false),
 *      @Attribute("comment", type="string", required=false),
 *      @Attribute("options", type="App\Component\ModuleMetadata\Options", required=false),
 *     })
 */
class Widget implements Annotation
{
    /**
     * Vidget constructor.
     * @param array $values
     */
    public function __construct(array $values)
    {
        $this->tab = $values['tab'];
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
     * @return Widget
     */
    public function setOptions(Options $options): Widget
    {
        $this->options = $options;
        return $this;
    }

    /**
     * @var string
     */
    private $tab;

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
     * @return string
     */
    public function getTab(): string
    {
        return $this->tab;
    }

    /**
     * @param string $tab
     * @return Widget
     */
    public function setTab(string $tab): Widget
    {
        $this->tab = $tab;
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
     * @return Widget
     */
    public function setOrder(int $order): Widget
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
     * @return Widget
     */
    public function setType(string $type): Widget
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
     * @return Widget
     */
    public function setLabel(string $label): Widget
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
     * @return Widget
     */
    public function setTitle(string $title): Widget
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
     * @return Widget
     */
    public function setHelp(string $help): Widget
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
     * @return Widget
     */
    public function setComment(string $comment): Widget
    {
        $this->comment = $comment;
        return $this;
    }
}
