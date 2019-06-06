<?php
/**
 * Created by PhpStorm.
 * User: polyanin
 * Date: 10.09.2018
 * Time: 16:44
 */

namespace App\Entity\Bind;

use App\Component\ModuleMetadata as ModuleMetadata;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Container
 * @package App\Entity\Bind
 * @ORM\Entity(repositoryClass="App\Repository\BindContainerRepository")
 * @ORM\Table(name="bind_container")
 * @ModuleMetadata\Module(
 *     title="Container",
 *     description="Container entity",
 *     tabOrder={
 *          "General": 1000,
 *          "Text": 2000,
 *          "Additional": 10000418
 *     })
 */
class Container
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
     * @return Container
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
     * @return Container
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @ORM\OneToMany(targetEntity="\App\Entity\Bind\Contained", mappedBy="container")
     */
    private $contained;

    /**
     * Container constructor.
     */
    public function __construct()
    {
        $this->contained = new ArrayCollection();
    }

    /**
     * @return Collection|Contained[]
     */
    public function getContained(): Collection
    {
        return $this->contained;
    }

    /**
     * @param Contained $contained
     * @return Container
     */
    public function addContained(Contained $contained): self
    {
        if (!$this->contained->contains($contained)) {
            $this->contained[] = $contained;
            $contained->setContainer($this);
        }
        return $this;
    }

    /**
     * @param Contained $contained
     * @return Container
     */
    public function removeContained(Contained $contained): self
    {
        if ($this->contained->contains($contained)) {
            $this->contained->removeElement($contained);
            // set the owning side to null (unless already changed)
            if ($contained->getContainer() === $this) {
                $contained->setContainer(null);
            }
        }
        return $this;
    }
}
