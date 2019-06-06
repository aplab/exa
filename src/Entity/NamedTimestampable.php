<?php
/**
 * Created by PhpStorm.
 * User: polyanin
 * Date: 19.08.2018
 * Time: 16:01
 */

namespace App\Entity;

use App\Component\ModuleMetadata as ModuleMetadata;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class NamedTimestampable
 * @package App\Entity
 * @ORM\Entity(repositoryClass="App\Repository\NamedTimestampableRepository")
 * @ORM\Table(name="named_timestampable")
 * @ModuleMetadata\Module(
 *     title="Named timestampable",
 *     description="Named timestampable entity",
 *     tabOrder={
 *          "General": 1000,
 *          "Text": 2000,
 *          "Additional": 10000418
 *     })
 */
class NamedTimestampable
{
    /**
     * NamedTimestampable constructor.
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
     * @return NamedTimestampable
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
     * @return NamedTimestampable
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
     * @return NamedTimestampable
     */
    public function setText($text)
    {
        $this->text = $text;
        return $this;
    }
}
