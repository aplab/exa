<?php
/**
 * Created by PhpStorm.
 * User: polyanin
 * Date: 10.09.2018
 * Time: 16:44
 */

namespace App\Entity\Bind;

use App\Component\ModuleMetadata as ModuleMetadata;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Contained
 * @package App\Entity\Bind
 * @ORM\Entity()
 * @ORM\Table(name="bind_contained")
 * @ModuleMetadata\Module(
 *     title="Contained",
 *     description="Contained entity",
 *     tabOrder={
 *          "General": 1000,
 *          "Text": 2000,
 *          "Additional": 10000418
 *     })
 */
class Contained
{
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
     * @ModuleMetadata\Property(title="Name",
     *     cell={@ModuleMetadata\Cell(order=2000, width=320, type="Label")},
     *     widget={@ModuleMetadata\Widget(order=2000, tab="General", type="Text")})
     */
    private $name;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return Contained
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
     * @return Contained
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @ORM\ManyToOne(targetEntity="\App\Entity\Bind\Container", inversedBy="contained")
     * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
     * @ModuleMetadata\Property(title="Container",
     *     cell={@ModuleMetadata\Cell(order=3000, width=320, type="Entity",
     *     options=@ModuleMetadata\Options(accessor="getName"))},
     *     widget={@ModuleMetadata\Widget(order=2000, tab="General", type="Entity",
     *     options=@ModuleMetadata\Options(data_class="\App\Entity\Bind\Container"))})
     */
    private $container;

    /**
     * @return mixed
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * @param mixed $container
     * @return Contained
     */
    public function setContainer(?Container $container)
    {
        $this->container = $container;
        return $this;
    }
}
