<?php

namespace App\Entity;

use App\Entity\Trait\CreatedAtTrait;
use App\Entity\Trait\EntityTrackingTrait;
use App\Entity\Trait\SlugTrait;
use App\Repository\EtablissementsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: EtablissementsRepository::class)]
class Etablissements
{
    use CreatedAtTrait;
    use SlugTrait;
    use EntityTrackingTrait;
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100, unique: true)]
    #[Assert\NotBlank(message: 'La désignation de l\'établissement est obligatoire.')]
    #[Assert\Length(
        min: 3,
        max: 100,
        minMessage: 'La désignation doit comporter au moins {{ limit }} caractères.',
        maxMessage: 'La désignation ne doit pas dépasser {{ limit }} caractères.'
    )]
    #[Assert\Regex(
        pattern: '/^[a-zA-Z0-9\s\-]+$/',
        message: 'La désignation ne doit contenir que des lettres, des chiffres, des espaces et des tirets.'
    )]
    private ?string $designation = null;

    #[ORM\Column(length: 30)]
    #[Assert\NotBlank(message: 'La forme juridique de l\'établissement est obligatoire.')]
    #[Assert\Length(
        min: 3,
        max: 30,
        minMessage: 'La forme juridique doit comporter au moins {{ limit }} caractères.',
        maxMessage: 'La forme juridique ne doit pas dépasser {{ limit }} caractères.'
    )]
    #[Assert\Regex(
        pattern: '/^[a-zA-Z\s\-]+$/',
        message: 'La forme juridique ne doit contenir que des lettres, des espaces et des tirets.'
    )]
    private ?string $formeJuridique = null;

    #[ORM\Column(length: 30, unique: true, nullable: false)]
    #[Assert\NotBlank(message: 'La décision de création de l\'établissement est obligatoire.')]
    #[Assert\Length(
        min: 3,
        max: 30,
        minMessage: 'La décision de création doit comporter au moins {{ limit }} caractères.',
        maxMessage: 'La décision de création ne doit pas dépasser {{ limit }} caractères.'
    )]
    #[Assert\Regex(
        pattern: '/^[a-zA-Z0-9\s\-]+$/',
        message: 'La décision de création ne doit contenir que des lettres, des chiffres, des espaces et des tirets.'
    )]
    private ?string $decisionCreation = null;

    #[ORM\Column(length: 30, unique: true, nullable: true)]
    #[Assert\Length(
        min: 3,
        max: 30,
        minMessage: 'La décision d\'ouverture doit comporter au moins {{ limit }} caractères.',
        maxMessage: 'La décision d\'ouverture ne doit pas dépasser {{ limit }} caractères.'
    )]
    #[Assert\Regex(
        pattern: '/^[a-zA-Z0-9\s\-]+$/',
        message: 'La décision d\'ouverture ne doit contenir que des lettres, des chiffres, des espaces et des tirets.'
    )]
    #[Assert\NotBlank(message: 'La décision d\'ouverture de l\'établissement est obligatoire.')]
    #[Assert\NotNull(message: 'La décision d\'ouverture de l\'établissement ne peut pas être nulle.')]
    private ?string $decisionOuverture = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE, nullable: true)]
    private ?\DateTimeImmutable $dateOuverture = null;

    #[ORM\Column(length: 30, nullable: true)]
    #[Assert\Length(
        max: 30,
        maxMessage: 'Le numéro social ne doit pas dépasser {{ limit }} caractères.'
    )]
    #[Assert\Regex(
        pattern: '/^[a-zA-Z0-9\s\-]+$/',
        message: 'Le numéro INPS ne doit contenir que des lettres, des chiffres, des espaces et des tirets.'
    )]

    private ?string $numeroSocial = null;

    #[ORM\Column(length: 30, nullable: true)]
    #[Assert\Length(
        max: 30,
        maxMessage: 'Le numéro fiscal ne doit pas dépasser {{ limit }} caractères.'
    )]
    #[Assert\Regex(
        pattern: '/^[a-zA-Z0-9\s\-]+$/',
        message: 'Le numéro fiscal ne doit contenir que des lettres, des chiffres, des espaces et des tirets.'
    )]

    private ?string $numeroFiscal = null;

    #[ORM\Column(length: 60, nullable: true)]
    #[Assert\Length(
        max: 60,
        maxMessage: 'Le compte bancaire ne doit pas dépasser {{ limit }} caractères.'
    )]
    #[Assert\Regex(
        pattern: '/^[a-zA-Z0-9\s\-]+$/',
        message: 'Le compte bancaire ne doit contenir que des lettres, des chiffres, des espaces et des tirets.'
    )]
    private ?string $compteBancaire = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'L\'adresse de l\'établissement est obligatoire.')]
    #[Assert\Length(
        min: 5,
        max: 255,
        minMessage: 'L\'adresse doit comporter au moins {{ limit }} caractères.',
        maxMessage: 'L\'adresse ne doit pas dépasser {{ limit }} caractères.'
    )]
    #[Assert\Regex(
        pattern: '/^[a-zA-Z0-9\s,.-]+$/',
        message: 'L\'adresse ne doit contenir que des lettres, des chiffres, des espaces, des virgules, des points et des tirets.'
    )]
    #[Assert\NotNull(message: 'L\'adresse de l\'établissement ne peut pas être nulle.')]
    private ?string $adresse = null;

    #[ORM\Column(length: 23)]
    #[Assert\NotBlank(message: 'Le numéro de téléphone de l\'établissement est obligatoire.')]
    #[Assert\Length(
        min: 10,
        max: 23,
        minMessage: 'Le numéro de téléphone doit comporter au moins {{ limit }} caractères.',
        maxMessage: 'Le numéro de téléphone ne doit pas dépasser {{ limit }} caractères.'
    )]
    #[Assert\Regex(
        pattern: "/^(?:(?:\+223|00223)[2567]\d{7}|(?:(?:\+(?!223)|00(?!223))\d{1,3}\d{6,12}))$/",
        message: 'Le numéro de téléphone ne doit contenir que des chiffres, des espaces, des tirets et des parenthèses.'
    )]
    #[Assert\NotNull(message: 'Le numéro de téléphone de l\'établissement ne peut pas être nul.')]
    private ?string $telephone = null;

    #[ORM\Column(length: 25, nullable: true)]
    #[Assert\Length(
        max: 25,
        maxMessage: 'Le numéro de téléphone mobile ne doit pas dépasser {{ limit }} caractères.'
    )]
    #[Assert\Regex(
        pattern: "/^(?:(?:\+223|00223)[2567]\d{7}|(?:(?:\+(?!223)|00(?!223))\d{1,3}\d{6,12}))$/",
        message: 'Le numéro de téléphone mobile ne doit contenir que des chiffres, des espaces, des tirets et des parenthèses.'
    )]
    private ?string $telephoneMobile = null;

    #[ORM\Column(length: 100)]
    #[Assert\Email(
        message: 'L\'adresse email "{{ value }}" n\'est pas une adresse email valide.',
        mode: 'strict'
    )]
    #[Assert\Length(
        max: 100,
        maxMessage: 'L\'email ne doit pas dépasser {{ limit }} caractères.'
    )]
    #[Assert\NotBlank(message: 'L\'email de l\'établissement est obligatoire.')]
    #[Assert\NotNull(message: 'L\'email de l\'établissement ne peut pas être nul.')]
    #[Assert\Regex(
        pattern: '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/',
        message: 'L\'email doit être au format valide.'
    )]
    private ?string $email = null;

    #[ORM\Column(nullable: true)]
    #[Assert\PositiveOrZero(message: 'La capacité doit être un nombre positif ou zéro.')]
    #[Assert\Type(
        type: 'integer',
        message: 'La capacité doit être un nombre entier.'
    )]
    #[Assert\NotNull(message: 'La capacité de l\'établissement ne peut pas être nulle.')]
    #[Assert\NotBlank(message: 'La capacité de l\'établissement est obligatoire.')]
    #[Assert\Range(
        min: 0,
        notInRangeMessage: 'La capacité doit être supérieure ou égale à {{ min }}.',
    )]
    #[Assert\LessThanOrEqual(
        value: 10000,
        message: 'La capacité ne doit pas dépasser {{ compared_value }}.'
    )]
    #[Assert\GreaterThanOrEqual(
        value: 0,
        message: 'La capacité doit être supérieure ou égale à {{ compared_value }}.'
    )]
    #[Assert\Regex(
        pattern: '/^\d+$/',
        message: 'La capacité doit être un nombre entier positif.'
    )]
    private ?int $capacite = 0;

    #[ORM\Column(nullable: true)]
    #[Assert\PositiveOrZero(message: 'L\'effectif doit être un nombre positif ou zéro.')]
    #[Assert\Type(
        type: 'integer',
        message: 'L\'effectif doit être un nombre entier.'
    )]
    #[Assert\NotNull(message: 'L\'effectif de l\'établissement ne peut pas être nul.')]
    #[Assert\NotBlank(message: 'L\'effectif de l\'établissement est obligatoire.')]
    #[Assert\Range(
        min: 0,
        notInRangeMessage: 'L\'effectif doit être supérieur ou égal à {{ min }}.',
    )]
    #[Assert\LessThanOrEqual(
        value: 10000,
        message: 'L\'effectif ne doit pas dépasser {{ compared_value }}.'
    )]
    #[Assert\GreaterThanOrEqual(
        value: 0,
        message: 'L\'effectif doit être supérieur ou égal à {{ compared_value }}.'
    )]
    #[Assert\Regex(
        pattern: '/^\d+$/',
        message: 'L\'effectif doit être un nombre entier positif.'
    )]
    private ?int $effectif = 0;

    #[ORM\ManyToOne(inversedBy: 'etablissements')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Enseignements $enseignement = null;

    /**
     * @var Collection<int, Users>
     */
    #[ORM\OneToMany(targetEntity: Users::class, mappedBy: 'etablissement')]
    private Collection $users;

    /**
     * @var Collection<int, Users>
     */
    #[ORM\ManyToMany(targetEntity: Users::class, inversedBy: 'etablissements')]
    private Collection $superUsers;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->superUsers = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->designation ?? 'N/A';
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDesignation(): ?string
    {
        return $this->designation;
    }

    public function setDesignation(string $designation): static
    {
        $designation = trim($designation);
        $designation = preg_replace('/\s+/', ' ', $designation); // Normalise les espaces
        $this->designation = mb_strtoupper($designation, 'UTF-8');
        return $this;
    }

    public function getFormeJuridique(): ?string
    {
        return $this->formeJuridique;
    }

    public function setFormeJuridique(string $formeJuridique): static
    {
        $this->formeJuridique = $formeJuridique;
        $normalized = trim($formeJuridique);
        $normalized = preg_replace('/\s+/', ' ', $normalized); // Normalise les espaces
        $this->formeJuridique = mb_strtoupper($normalized, 'UTF-8');
        return $this;
    }

    public function getDecisionCreation(): ?string
    {
        return $this->decisionCreation;
    }

    public function setDecisionCreation(string $decisionCreation): static
    {
        $this->decisionCreation = $decisionCreation;
        $normalized = trim($decisionCreation);
        $normalized = preg_replace('/\s+/', ' ', $normalized); // Normalise les espaces
        $this->decisionCreation = mb_strtoupper($normalized, 'UTF-8');
        return $this;
    }

    public function getDecisionOuverture(): ?string
    {
        return $this->decisionOuverture;
    }

    public function setDecisionOuverture(string $decisionOuverture): static
    {
        $this->decisionOuverture = $decisionOuverture;
        $normalized = trim($decisionOuverture);
        $normalized = preg_replace('/\s+/', ' ', $normalized); // Normalise les espaces
        $this->decisionOuverture = mb_strtoupper($normalized, 'UTF-8');
        return $this;
    }

    public function getDateOuverture(): ?\DateTimeImmutable
    {
        return $this->dateOuverture;
    }

    public function setDateOuverture(?\DateTimeImmutable $dateOuverture): static
    {
        $this->dateOuverture = $dateOuverture;

        return $this;
    }

    public function getNumeroSocial(): ?string
    {
        return $this->numeroSocial;
    }

    public function setNumeroSocial(?string $numeroSocial): static
    {
        $this->numeroSocial = $numeroSocial;
        $designation = trim($numeroSocial);
        $designation = preg_replace('/\s+/', ' ', $designation); // Normalise les espaces
        $this->numeroSocial = mb_strtoupper($designation, 'UTF-8');
        return $this;
    }

    public function getNumeroFiscal(): ?string
    {
        return $this->numeroFiscal;
    }

    public function setNumeroFiscal(?string $numeroFiscal): static
    {
        $this->numeroFiscal = $numeroFiscal;
        $designation = trim($numeroFiscal);
        $designation = preg_replace('/\s+/', ' ', $designation); // Normalise les espaces
        $this->numeroFiscal = mb_strtoupper($designation, 'UTF-8');
        return $this;
    }

    public function getCompteBancaire(): ?string
    {
        return $this->compteBancaire;
    }

    public function setCompteBancaire(?string $compteBancaire): static
    {
        $this->compteBancaire = $compteBancaire;
        $designation = trim($compteBancaire);
        $designation = preg_replace('/\s+/', ' ', $designation); // Normalise les espaces
        $this->compteBancaire = mb_strtoupper($designation, 'UTF-8');
        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): static
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone): static
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getTelephoneMobile(): ?string
    {
        return $this->telephoneMobile;
    }

    public function setTelephoneMobile(?string $telephoneMobile): static
    {
        $this->telephoneMobile = $telephoneMobile;

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

    public function getCapacite(): ?int
    {
        return $this->capacite;
    }

    public function setCapacite(?int $capacite): static
    {
        if ($capacite < 0) {
            throw new \InvalidArgumentException('La capacité ne peut pas être négative.');
        }
        if (!is_int($capacite)) {
            throw new \InvalidArgumentException('La capacité doit être un nombre entier.');
        }
        if ($capacite > 10000) {
            throw new \InvalidArgumentException('La capacité ne doit pas dépasser 10 000.');
        }
        $this->capacite = $capacite;

        return $this;
    }

    public function getEffectif(): ?int
    {
        return $this->effectif;
    }

    public function setEffectif(?int $effectif): static
    {
        if ($effectif < 0) {
            throw new \InvalidArgumentException('L\'effectif ne peut pas être négatif.');
        }
        if (!is_int($effectif)) {
            throw new \InvalidArgumentException('L\'effectif doit être un nombre entier.');
        }
        if ($effectif > 10000) {
            throw new \InvalidArgumentException('L\'effectif ne doit pas dépasser 10 000.');
        }
        if ($effectif === null) {
            $effectif = 0; // Set default value if null
        }

        $this->effectif = $effectif;

        return $this;
    }

    public function getEnseignement(): ?Enseignements
    {
        return $this->enseignement;
    }

    public function setEnseignement(?Enseignements $enseignement): static
    {
        $this->enseignement = $enseignement;

        return $this;
    }

    /**
     * @return Collection<int, Users>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(Users $user): static
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
            $user->setEtablissement($this);
        }

        return $this;
    }

    public function removeUser(Users $user): static
    {
        if ($this->users->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getEtablissement() === $this) {
                $user->setEtablissement(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Users>
     */
    public function getSuperUsers(): Collection
    {
        return $this->superUsers;
    }

    public function addSuperUser(Users $superUser): static
    {
        if (!$this->superUsers->contains($superUser)) {
            $this->superUsers->add($superUser);
        }

        return $this;
    }

    public function removeSuperUser(Users $superUser): static
    {
        $this->superUsers->removeElement($superUser);

        return $this;
    }
}
