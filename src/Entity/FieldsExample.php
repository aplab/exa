<?php
/**
 * Created by PhpStorm.
 * User: polyanin
 * Date: 19.08.2018
 * Time: 16:01
 */

namespace Aplab\AplabAdminBundle\Entity;

use Aplab\AplabAdminBundle\Component\ModuleMetadata as ModuleMetadata;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class FieldsExample
 * @package Aplab\AplabAdminBundle\Entity
 * @ORM\Entity(repositoryClass="Aplab\AplabAdminBundle\Repository\FieldsExampleRepository")
 * @ORM\Table(name="fields_example")
 * @ModuleMetadata\Module(
 *     title="Fields example",
 *     description="Fields example entity",
 *     tabOrder={
 *          "General": 1000,
 *          "Text": 2000,
 *          "Additional": 10000418
 *     })
 */
class FieldsExample
{
    /**
     * FieldsExample constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        $this->createdAt = new \DateTime;
    }

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="bigint")
     * @ModuleMetadata\Property(title="ID", readonly=true,
     *     cell={@ModuleMetadata\Cell(order=1000, width=80, type="EditId")},
     *     widget={@ModuleMetadata\Widget(order=1000, tab="Additional", type="Label")})
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank(message="Name should be not blank")
     * @ModuleMetadata\Property(title="Name",
     *     cell={@ModuleMetadata\Cell(order=2000, width=320, type="Label")},
     *     widget={@ModuleMetadata\Widget(order=2000, tab="General", type="Text")})
     */
    private $name;

    /**
     * @ORM\Column(type="boolean")
     * @ModuleMetadata\Property(title="Flag",
     *     cell={@ModuleMetadata\Cell(order=2000, width=48, type="Active")},
     *     widget={@ModuleMetadata\Widget(order=2000, tab="General", type="Flag")})
     */
    private $flag;

    /**
     * @return mixed
     */
    public function getFlag()
    {
        return $this->flag;
    }

    /**
     * @param mixed $flag
     * @return FieldsExample
     */
    public function setFlag($flag)
    {
        $this->flag = !!$flag;
        return $this;
    }

    /**
     * @ORM\Column(type="string")
     * @ModuleMetadata\Property(title="Textarea",
     *     cell={@ModuleMetadata\Cell(order=2000, width=320, type="Label")},
     *     widget={@ModuleMetadata\Widget(order=2000, tab="General", type="Textarea")})
     */
    private $textarea;

    /**
     * @ORM\Column(type="string")
     * @ModuleMetadata\Property(title="Image",
     *     cell={@ModuleMetadata\Cell(order=2000, width=48, type="Image")},
     *     widget={@ModuleMetadata\Widget(order=2000, tab="General", type="Image")})
     */
    private $image;

    /**
     * @ORM\Column(type="string")
     * @ModuleMetadata\Property(title="Image 2",
     *     cell={@ModuleMetadata\Cell(order=2000, width=48, type="Image")},
     *     widget={@ModuleMetadata\Widget(order=2000, tab="General", type="Image")})
     */
    private $image2;

    /**
     * @return mixed
     */
    public function getImage2()
    {
        return $this->image2;
    }

    /**
     * @param mixed $image2
     * @return FieldsExample
     */
    public function setImage2($image2)
    {
        $this->image2 = $image2;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param mixed $image
     * @return FieldsExample
     */
    public function setImage($image)
    {
        $this->image = $image;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTextarea()
    {
        return $this->textarea;
    }

    /**
     * @param mixed $textarea
     * @return FieldsExample
     */
    public function setTextarea($textarea)
    {
        $this->textarea = $textarea;
        return $this;
    }

    /**
     * @ORM\Column(type="text")
     * @ModuleMetadata\Property(title="Name",
     *     cell={},
     *     widget={@ModuleMetadata\Widget(order=2000, tab="Text", type="Ckeditor")})
     */
    private $text;

    /**
     * @ORM\Column(
     *     type="datetime",
     *     nullable=true,
     *     columnDefinition="DATETIME NULL DEFAULT CURRENT_TIMESTAMP",
     *     options={"default"="CURRENT_TIMESTAMP"}
     * )
     * @ModuleMetadata\Property(title="Created at", readonly=true,
     *     cell={@ModuleMetadata\Cell(order=2000, width=156, type="Datetime")},
     *     widget={@ModuleMetadata\Widget(order=2000, tab="General", type="DateTime")})
     */
    private $createdAt;

    /**
     * @ORM\Column(
     *     type="datetime",
     *     nullable=true,
     *     columnDefinition="DATETIME NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP",
     *     options={"default"="CURRENT_TIMESTAMP"}
     * )
     * @ModuleMetadata\Property(title="Last modified", readonly=true,
     *     cell={@ModuleMetadata\Cell(order=2000, width=156, type="Datetime")},
     *     widget={@ModuleMetadata\Widget(order=2000, tab="General", type="DateTime",
     *     options=@ModuleMetadata\Options(test={1,{"test"=4},3,"test2"=7}))}
     * )
     */
    private $lastModified;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return FieldsExample
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     * @return FieldsExample
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @return mixed
     */
    public function getLastModified()
    {
        return $this->lastModified;
    }

    /**
     * @return mixed
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param mixed $text
     * @return FieldsExample
     */
    public function setText($text)
    {
        $this->text = $text;
        return $this;
    }
}
