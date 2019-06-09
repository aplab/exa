<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Component\ModuleMetadata as ModuleMetadata;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SystemUserRepository")
 * @UniqueEntity(fields={"username"}, message="It looks like your already have an account!")
 * @ModuleMetadata\Module(
 *     title="System user",
 *     description="System user entity",
 *     tabOrder={
 *          "General": 1000,
 *          "Additional": 10000418
 *     })
 */
class SystemUser implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="bigint", options={"unsigned"=true})
     * @ModuleMetadata\Property(title="ID", readonly=true,
     *     cell={
     *         @ModuleMetadata\Cell(order=1000, width=80, type="EditId")
     *     },
     *     widget={
     *         @ModuleMetadata\Widget(order=1000, tab="Additional", type="Label")
     *     })
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Assert\NotBlank(message="Username should be not blank")
     * @ModuleMetadata\Property(title="Name",
     *     cell={@ModuleMetadata\Cell(order=2000, width=320, type="Label")},
     *     widget={@ModuleMetadata\Widget(order=2000, tab="General", type="Text")})
     */
    private $username;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     * @Assert\NotBlank(message="Password should be not blank")
     * @ModuleMetadata\Property(title="Password",
     *     cell={},
     *     widget={@ModuleMetadata\Widget(order=2000, tab="General", type="Password")})
     */
    private $password;

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_ADMIN';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }
}
