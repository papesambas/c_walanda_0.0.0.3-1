<?php

namespace App\Entity;

use App\Entity\Trait\CreatedAtTrait;
use App\Entity\Trait\EntityTrackingTrait;
use App\Entity\Trait\SlugTrait;
use App\Repository\ClassesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ClassesRepository::class)]
#[ORM\Table(
    name: 'classes',
    uniqueConstraints: [
        new ORM\UniqueConstraint(name: 'unique_class', columns: ['designation', 'niveau_id', 'etablissement_id'])
    ],
    indexes: [
        new ORM\Index(name: 'IDX_CLASSES_DESIGNATION', columns: ['designation'])
    ]
)]
class Classes
{
    use CreatedAtTrait;
    use SlugTrait;
    use EntityTrackingTrait;
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 75)]
    #[Assert\NotBlank()]
    #[Assert\NotNull()]
    #[Assert\Length(
        min: 2,
        max: 75,
        minMessage: 'designation must be at least {{ limit }} characters long.',
        maxMessage: 'designation cannot be longer than {{ limit }} characters.'
    )]
    #[Assert\Regex(
        pattern: "/^[\p{L}0-9]+(?:[ \-'][\p{L}0-9]+)*$/u",
        message: 'The designation must contain only letters, spaces, apostrophes or hyphens.'
    )]
    private ?string $designation = null;

    #[ORM\Column]
    #[Assert\PositiveOrZero()]
    #[Assert\NotNull()]
    private ?int $capacite = 0;

    #[ORM\ManyToOne(inversedBy: 'classes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Etablissements $etablissement = null;

    #[ORM\ManyToOne(inversedBy: 'classes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Niveaux $niveau = null;

    /**
     * @var Collection<int, Eleves>
     */
    #[ORM\OneToMany(targetEntity: Eleves::class, mappedBy: 'classe')]
    private Collection $eleves;

    #[ORM\Column]
    #[Assert\PositiveOrZero()]
    #[Assert\NotNull()]
    private ?int $effectif = 0;

    public function __construct()
    {
        $this->eleves = new ArrayCollection();
    }


    public function __toString()
    {
        return $this->designation ?? '';
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
        $this->designation = $designation;

        return $this;
    }

    public function getCapacite(): ?int
    {
        return $this->capacite;
    }

    public function setCapacite(int $capacite): static
    {
        $this->capacite = $capacite;

        return $this;
    }

    public function getEtablissement(): ?Etablissements
    {
        return $this->etablissement;
    }

    public function setEtablissement(?Etablissements $etablissement): static
    {
        $this->etablissement = $etablissement;

        return $this;
    }

    public function getNiveau(): ?Niveaux
    {
        return $this->niveau;
    }

    public function setNiveau(?Niveaux $niveau): static
    {
        $this->niveau = $niveau;

        return $this;
    }

    /**
     * @return Collection<int, Eleves>
     */
    public function getEleves(): Collection
    {
        return $this->eleves;
    }

    public function addEleve(Eleves $eleve): static
    {
        if (!$this->eleves->contains($eleve)) {
            // Vérifier la capacité avant d'ajouter
            if ($this->getDisponibilite() <= 0) {
                throw new \LogicException('La classe est complète : impossible d\'ajouter un élève');
            }

            $this->eleves->add($eleve);
            $eleve->setClasse($this);
            $this->effectif++; // Mise à jour de l'effectif
        }

        return $this;
    }

    public function removeEleve(Eleves $eleve): static
    {
        if ($this->eleves->removeElement($eleve)) {
            if ($eleve->getClasse() === $this) {
                $eleve->setClasse(null);
                $this->effectif--; // Mise à jour de l'effectif
            }
        }

        return $this;
    }

    public function getEffectif(): ?int
    {
        return $this->effectif;
    }

    public function setEffectif(int $effectif): static
    {
        $this->effectif = $effectif;

        return $this;
    }

    public function getDisponibilite(): int
    {
        return max(0, $this->capacite - $this->effectif);
    }

    #[ORM\PostLoad]
    public function updateEffectifFromDatabase(): void
    {
        $this->effectif = count($this->eleves);
    }
}
