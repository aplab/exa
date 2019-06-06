<?php
/**
 * Created by PhpStorm.
 * User: polyanin
 * Date: 26.07.2018
 * Time: 14:12
 */

namespace App\Component\ModuleMetadata;

use Doctrine\Common\Annotations\Annotation\Attribute;
use Doctrine\Common\Annotations\Annotation\Attributes;
use Doctrine\Common\Annotations\Annotation\Target;

/**
 * Class Module
 * @package App\Annotation\Module
 * @Annotation
 * @Target({"CLASS"})
 * @Attributes({
 *      @Attribute("title", type="string", required=true),
 *      @Attribute("name", type="string", required=false),
 *      @Attribute("description", type="string", required=false),
 *      @Attribute("help", type="string", required=false),
 *      @Attribute("comment", type="string", required=false),
 *      @Attribute("label", type="string", required=false),
 *      @Attribute("tabOrder", type="array<integer>", required=true),
 * })
 */
class Module
{
    /**
     * Module constructor.
     * @param array $values
     */
    public function __construct(array $values)
    {
        $this->title = $values['title'] ?? $this->title;
        $this->description = $values['description'] ?? $this->title;
        $this->help = $values['help'] ?? $this->title;
        $this->comment = $values['comment'] ?? $this->title;
        $this->name = $values['name'] ?? $this->title;
        $this->label = $values['label'] ?? $this->title;
        $this->tabOrder = $values['tabOrder'] ?? [];
    }

    /**
     * @var array
     */
    private $tabOrder;

    /**
     * @var integer
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
    private $name;

    /**
     * @var string
     */
    private $label;

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return Module
     */
    public function setTitle(string $title): Module
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
     * @return Module
     */
    public function setDescription(string $description): Module
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
     * @return Module
     */
    public function setHelp(string $help): Module
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
     * @return Module
     */
    public function setComment(string $comment): Module
    {
        $this->comment = $comment;
        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Module
     */
    public function setName(string $name): Module
    {
        $this->name = $name;
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
     * @return Module
     */
    public function setLabel(string $label): Module
    {
        $this->label = $label;
        return $this;
    }

    /**
     * @return array
     */
    public function getTabOrder(): array
    {
        return $this->tabOrder;
    }

    /**
     * @param array $tabOrder
     * @return Module
     */
    public function setTabOrder(array $tabOrder): Module
    {
        $this->tabOrder = $tabOrder;
        return $this;
    }
}
