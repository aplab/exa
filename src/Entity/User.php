<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use InvalidArgumentException;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Component\ModuleMetadata as ModuleMetadata;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity(fields={"email"}, message="It looks like your already have an account!")
 * @ModuleMetadata\Module(
 *     title="User",
 *     description="User entity",
 *     tabOrder={
 *          "General": 1000,
 *          "Additional": 10000418
 *     })
 */
class User implements UserInterface
{
    /**
     * @var int
     */
    const MIN_PASSWORD_LENGTH = 8;

    /**
     * @var UserPasswordEncoderInterface
     */
    private static $passwordEncoder;

    /**
     * @param UserPasswordEncoderInterface $password_encoder
     */
    public static function setPasswordEncoder(UserPasswordEncoderInterface $password_encoder)
    {
        static::$passwordEncoder = $password_encoder;
    }

    /**
     * @var ValidatorInterface
     */
    private static $validator;

    /**
     * @param ValidatorInterface $validator
     */
    public static function setValidator(ValidatorInterface $validator)
    {
        static::$validator = $validator;
    }

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
     * @Assert\Email(
     *     message = "The email '{{ value }}' is not a valid email.",
     *     checkMX = true
     * )
     * @ModuleMetadata\Property(title="Email",
     *     cell={@ModuleMetadata\Cell(order=2000, width=320, type="Label")},
     *     widget={@ModuleMetadata\Widget(order=2000, tab="General", type="Text")})
     */
    private $email;

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
    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $l = mb_strlen($email);
        if (!$l) {
            throw new InvalidArgumentException('username cannot be empty');
        }
        $this->email = $email;
        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

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

    /**
     * @param string $password
     * @return User
     */
    public function setPassword(string $password): self
    {
        $l = mb_strlen($password);
        if (!$l) {
            return $this;
        }
        if ($l < static::MIN_PASSWORD_LENGTH) {
            throw new InvalidArgumentException('password too short, 8 characters needed');
        }
        $constraint = new Assert\NotCompromisedPassword();
        $validator = static::$validator;
        $errors = $validator->validate($password, $constraint);
        if (sizeof($errors)) {
            throw new InvalidArgumentException((string)$errors);
        }
        $encoded = static::$passwordEncoder->encodePassword(
            $this, $password);
        $this->password = $encoded;
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
