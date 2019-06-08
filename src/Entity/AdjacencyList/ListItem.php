<?php

namespace App\Entity\AdjacencyList;

use DateTime;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use LogicException;
use Symfony\Component\Validator\Constraints as Assert;
use App\Component\ModuleMetadata as ModuleMetadata;

use Gedmo\Mapping\Annotation as Gedmo;


/**
 * Class ListItem
 * @package App\Entity\AdjacencyList
 * @ORM\Entity(repositoryClass="App\Repository\AdjacencyListItemRepository")
 * @ORM\Table(name="adjacency_list", indexes={
 *      @ORM\Index(name="parent_id", columns={"parent_id"}),
 *      @ORM\Index(name="sort_order", columns={"sort_order"}),
 *      @ORM\Index(name="order_id", columns={"sort_order", "id"})
 *     })
 * @ModuleMetadata\Module(
 *     title="Tree",
 *     description="Tree",
 *     tabOrder={
 *          "General": 1000,
 *          "Text": 2000,
 *          "Additional": 10000418
 *     })
 */
class ListItem
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @ModuleMetadata\Property(title="ID", readonly=true,
     *     cell={@ModuleMetadata\Cell(order=1000, width=80, type="EditId")},
     *     widget={@ModuleMetadata\Widget(order=1000, tab="Additional", type="Label")})
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity="\App\Entity\AdjacencyList\ListItem", mappedBy="parent")
     * @ORM\OrderBy({"sortOrder" = "ASC", "id" = "ASC"})
     */
    private $children;

    /**
     * Many Categories have One Category.
     * @ORM\ManyToOne(targetEntity="\App\Entity\AdjacencyList\ListItem", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", onDelete="SET NULL")
     * @ModuleMetadata\Property(title="Parent",
     *     cell={@ModuleMetadata\Cell(order=3000, width=320, type="Entity",
     *     options=@ModuleMetadata\Options(accessor="getName"))},
     *     widget={@ModuleMetadata\Widget(order=2000, tab="General", type="Entity",
     *     options=@ModuleMetadata\Options(data_class="\App\Entity\AdjacencyList\ListItem"))})
     */
    private $parent;

    /**
     * @ORM\Column(type="bigint")
     * @ModuleMetadata\Property(title="Sort order",
     *     cell={@ModuleMetadata\Cell(order=4000, width=120, type="Rtext")},
     *     widget={@ModuleMetadata\Widget(order=2000, tab="General", type="Text")})
     */
    private $sortOrder;

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank(message="Name should be not blank")
     * @ModuleMetadata\Property(title="Name",
     *     cell={@ModuleMetadata\Cell(order=2000, width=320, type="Tree")},
     *     widget={@ModuleMetadata\Widget(order=2000, tab="General", type="Text")})
     */
    private $name;

    /**
     * ListItem constructor.
     */
    public function __construct()
    {
        $this->createdAt = new DateTime;
        $this->updatedAt = new DateTime;
        $this->children = new ArrayCollection();
        $this->sortOrder = 0;
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|ListItem[]
     */
    public function getChildren(): Collection
    {
        return $this->children;
    }

    /**
     * @param ListItem $child
     * @return ListItem
     */
    public function addChild(ListItem $child): self
    {
        if ($child === $this) {
            throw new LogicException('Unable to set object as child of itself.');
        }
        $parent_of_child = $child->getParent();
        while($parent_of_child) {
            if ($parent_of_child === $this) {
                throw new LogicException('Unable to add ancestor as child.');
            }
        }
        if (!$this->children->contains($child)) {
            $this->children[] = $child;
            $child->setParent($this);
        }

        return $this;
    }

    /**
     * @return mixed
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @param ListItem|null $parent
     * @return ListItem
     */
    public function setParent(?ListItem $parent): self
    {
        if ($parent === $this) {
            throw new LogicException('Unable to set object as parent of itself.');
        }
        if (!is_null($parent)) {
            $parent_of_parent = $parent->getParent();
            while($parent_of_parent) {
                if ($parent_of_parent === $this) {
                    throw new LogicException('Unable to set descendant as parent.');
                }
                $parent_of_parent = $parent_of_parent->getParent();
            }
        }
        $this->parent = $parent;
        return $this;
    }

    /**
     * @param ListItem $child
     * @return ListItem
     */
    public function removeChild(ListItem $child): self
    {
        if ($this->children->contains($child)) {
            $this->children->removeElement($child);
            // set the owning side to null (unless already changed)
            if ($child->getParent() === $this) {
                $child->setParent(null);
            }
        }

        return $this;
    }

    /**
     * @return mixed
     */
    public function getSortOrder()
    {
        return $this->sortOrder;
    }

    /**
     * @param mixed $sortOrder
     * @return ListItem
     */
    public function setSortOrder($sortOrder)
    {
        $this->sortOrder = $sortOrder;
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
     * @return ListItem
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @ORM\Column(type="datetime")
     * @Gedmo\Timestampable(on="create")
     * @ModuleMetadata\Property(title="Created at", readonly=true,
     *     cell={@ModuleMetadata\Cell(order=5000, width=156, type="Datetime")},
     *     widget={@ModuleMetadata\Widget(order=1000000, tab="Additional", type="DateTime")})
     */
    private $createdAt;
    /**
     * @ORM\Column(type="datetime")
     * @Gedmo\Timestampable(on="update")
     * @ModuleMetadata\Property(title="Last modified", readonly=true,
     *     cell={@ModuleMetadata\Cell(order=6000, width=156, type="Datetime")},
     *     widget={@ModuleMetadata\Widget(order=1000000, tab="Additional", type="DateTime")})
     */
    private $updatedAt;

    /**
     * @return DateTimeInterface|null
     */
    public function getCreatedAt(): ?DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * @param DateTimeInterface|null $createdAt
     * @return ListItem
     */
    public function setCreatedAt(?DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    /**
     * @return DateTimeInterface|null
     */
    public function getUpdatedAt(): ?DateTimeInterface
    {
        return $this->updatedAt;
    }

    /**
     * @param DateTimeInterface|null $updatedAt
     * @return ListItem
     */
    public function setUpdatedAt(?DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }
}
