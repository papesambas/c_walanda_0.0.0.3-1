<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\UsersRepository;
use App\Entity\Trait\CreatedAtTrait;
use App\Entity\Trait\EntityTrackingTrait;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\PasswordStrength;

#[ORM\Entity(repositoryClass: UsersRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_USERNAME', fields: ['username'])]
#[UniqueEntity(fields: ['username'], message: 'There is already an account with this username')]
#[ORM\Table(name: 'users')]
#[ORM\Index(name: 'IDX_USERS_USERNAME', columns: ['username'])]
class Users implements UserInterface, PasswordAuthenticatedUserInterface
{
    use CreatedAtTrait;
    use EntityTrackingTrait;
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    private ?string $username = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    #[Assert\NotBlank(
        message: 'Entrez un mot de passe S.V.P'
    )]
    #[Assert\Length(
        min: 6,
        minMessage: 'le mot de passe doit avoir au moins {{ limit }} caractères',
        max: 255,
        maxMessage: 'le mot de passe doit avoir au plus {{ limit }} caractères'
    )]

    private ?string $password = null;

    #[ORM\Column(length: 60)]
    #[Assert\Regex(
        pattern: "/^\p{L}+(?:[ \-']\p{L}+)*$/u",
        message: 'Le nom ne doit contenir que des lettres, des espaces, des apostrophes ou des traits d\'union.'
    )]
    #[Assert\Length(
        min: 2,
        minMessage: 'le nom doit avoir au moins {{ limit }} caractères',
        max: 60,
        maxMessage: 'le nom doit avoir au plus {{ limit }} caractères'
    )]
    #[Assert\NotBlank(
        message: 'Entrez un nom S.V.P'
    )]
    #[Assert\NotNull(
        message: 'le Nom ne peut pas être nul'
    )]
    private ?string $nom = null;

    #[ORM\Column(length: 75)]
    #[Assert\Regex(
        pattern: "/^\p{L}+(?:[ \-']\p{L}+)*$/u",
        message: 'Le prénom ne doit contenir que des lettres, des espaces, des apostrophes ou des traits d\'union.'
    )]
    #[Assert\Length(
        min: 2,
        minMessage: 'le prénom doit avoir au moins {{ limit }} caractères',
        max: 70,
        maxMessage: 'le prénom doit avoir au plus {{ limit }} caractères'
    )]
    #[Assert\NotBlank(
        message: 'Entrez un prénom S.V.P'
    )]
    #[Assert\NotNull(
        message: 'le Prénom ne peut pas être nul'
    )]
    private ?string $prenom = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(
        message: 'Entrez un email S.V.P'
    )]
    #[Assert\NotNull(
        message: 'l\'email ne peut pas être nul'
    )]
    #[Assert\Email(
        message: 'L\'adresse email "{{ value }}" n\'est pas une adresse email valide.',
        mode: 'strict'
    )]
    #[Assert\Length(
        max: 255,
        maxMessage: 'l\'email doit avoir au plus {{ limit }} caractères'
    )]
    #[Assert\Regex(
        pattern: "/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/",
        message: 'L\'adresse email "{{ value }}" n\'est pas une adresse email valide.'
    )]
    private ?string $email = null;

    #[ORM\Column]
    private ?bool $isActif = true;

    #[ORM\Column]
    private ?bool $isAllowed = false;

    #[ORM\Column]
    private bool $isVerified = false;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->username;
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

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function isActif(): ?bool
    {
        return $this->isActif;
    }

    public function setIsActif(bool $isActif): static
    {
        $this->isActif = $isActif;

        return $this;
    }

    public function isAllowed(): ?bool
    {
        return $this->isAllowed;
    }

    public function setIsAllowed(bool $isAllowed): static
    {
        $this->isAllowed = $isAllowed;

        return $this;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): static
    {
        $this->isVerified = $isVerified;

        return $this;
    }
}
