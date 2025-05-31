<?php

namespace App\Entity;

use App\Entity\Enseignements;
use App\Entity\Trait\SlugTrait;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Trait\CreatedAtTrait;
use App\Repository\CyclesRepository;
use App\Entity\Trait\EntityTrackingTrait;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CyclesRepository::class)]
class Cycles
{
    use CreatedAtTrait;
    use EntityTrackingTrait;
    use SlugTrait;
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 60, unique: true, nullable: false)]
    #[Assert\NotBlank(message: 'La désignation ne peut pas être vide.')]
    #[Assert\Length(
        max: 60,
        maxMessage: 'La désignation ne peut pas dépasser {{ limit }} caractères.'
    )]
    #[Assert\Regex(
        pattern: "/^\p{L}+(?:[ \-']\p{L}+)*$/u",
        message: 'La désignation doit contenir uniquement des lettres, des espaces, des apostrophes ou des tirets.'
    )]


    private ?string $designation = null;

    /**
     * @var Collection<int, Enseignements>
     */
    #[ORM\ManyToMany(targetEntity: Enseignements::class, inversedBy: 'cycles')]
    #[ORM\JoinTable(name: 'cycles_enseignements')]
    #[Assert\NotBlank(message: 'La liste des enseignements ne peut pas être vide.')]
    #[Assert\Count(
        min: 1,
        minMessage: 'La liste des enseignements doit contenir au moins {{ limit }} élément.'
    )]
    #[Assert\Valid]
    private Collection $enseignement;

    #[ORM\Column]
    #[Assert\NotBlank(message: 'L\'effectif ne peut pas être vide.')]
    #[Assert\Range(
        min: 0,
        notInRangeMessage: 'L\'effectif doit être supérieur ou égal à {{ limit }}.',
        minMessage: 'L\'effectif doit être supérieur ou égal à 0.'
    )]
    private ?int $effectif = 0;

    /**
     * @var Collection<int, Niveaux>
     */
    #[ORM\OneToMany(targetEntity: Niveaux::class, mappedBy: 'cycle')]
    private Collection $niveauxes;

    public function __construct()
    {
        $this->enseignement = new ArrayCollection();
        $this->niveauxes = new ArrayCollection();
    }

    public function __toString(): string
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
        $designation = trim($designation);
        $designation = preg_replace('/\s+/', ' ', $designation); // Normalise les espaces
        $this->designation = mb_strtoupper($designation, 'UTF-8');
        return $this;

    }

    /**
     * @return Collection<int, Enseignements>
     */
    public function getEnseignement(): Collection
    {
        return $this->enseignement;
    }

    public function addEnseignement(Enseignements $enseignement): static
    {
        if (!$this->enseignement->contains($enseignement)) {
            $this->enseignement->add($enseignement);
        }

        return $this;
    }

    public function removeEnseignement(Enseignements $enseignement): static
    {
        $this->enseignement->removeElement($enseignement);

        return $this;
    }

    public function getEffectif(): ?int
    {
        return $this->effectif;
    }

    public function setEffectif(int $effectif): static
    {
        if ($effectif < 0) {
            throw new \InvalidArgumentException('L\'effectif doit être supérieur ou égal à 0.');
        }
        $effectif = (int) $effectif; // Assure que l'effectif est un entier
        if ($effectif < 0) {
            throw new \InvalidArgumentException('L\'effectif doit être supérieur ou égal à 0.');
        }
        if ($effectif > 10000000) {
            throw new \InvalidArgumentException('L\'effectif ne peut pas dépasser 10000000.');
        }
        if (!is_int($effectif)) {
            throw new \InvalidArgumentException('L\'effectif doit être un entier.');
        }
        $this->effectif = $effectif;

        return $this;
    }

    /**
     * @return Collection<int, Niveaux>
     */
    public function getNiveauxes(): Collection
    {
        return $this->niveauxes;
    }

    public function addNiveaux(Niveaux $niveaux): static
    {
        if (!$this->niveauxes->contains($niveaux)) {
            $this->niveauxes->add($niveaux);
            $niveaux->setCycle($this);
        }

        return $this;
    }

    public function removeNiveaux(Niveaux $niveaux): static
    {
        if ($this->niveauxes->removeElement($niveaux)) {
            // set the owning side to null (unless already changed)
            if ($niveaux->getCycle() === $this) {
                $niveaux->setCycle(null);
            }
        }

        return $this;
    }
}
