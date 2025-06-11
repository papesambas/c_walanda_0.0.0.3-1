<?php

namespace App\Entity;

use App\Entity\Trait\CreatedAtTrait;
use App\Entity\Trait\EntityTrackingTrait;
use App\Entity\Trait\SlugTrait;
use App\Repository\NiveauxRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: NiveauxRepository::class)]
class Niveaux
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
    #[Assert\NotNull(message: 'La désignation ne peut pas être nulle.')]
    private ?string $designation = null;

    #[ORM\Column]
    private ?int $effectif = 0;

    #[ORM\ManyToOne(inversedBy: 'niveauxes')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotNull(message: 'Le cycle ne peut pas être nul.')]
    private ?Cycles $cycle = null;

    /**
     * @var Collection<int, Classes>
     */
    #[ORM\OneToMany(targetEntity: Classes::class, mappedBy: 'niveau')]
    private Collection $classes;

    /**
     * @var Collection<int, Scolarites1>
     */
    #[ORM\OneToMany(targetEntity: Scolarites1::class, mappedBy: 'niveau', orphanRemoval: true)]
    private Collection $scolarites1s;

    /**
     * @var Collection<int, Scolarites2>
     */
    #[ORM\OneToMany(targetEntity: Scolarites2::class, mappedBy: 'niveau', orphanRemoval: true)]
    private Collection $scolarites2s;

    /**
     * @var Collection<int, Scolarites3>
     */
    #[ORM\OneToMany(targetEntity: Scolarites3::class, mappedBy: 'niveau', orphanRemoval: true)]
    private Collection $scolarites3s;

    public function __construct()
    {
        $this->classes = new ArrayCollection();
        $this->scolarites1s = new ArrayCollection();
        $this->scolarites2s = new ArrayCollection();
        $this->scolarites3s = new ArrayCollection();
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
        $this->designation = $designation;

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

        if (!is_int($effectif)) {
            throw new \InvalidArgumentException('L\'effectif doit être un entier.');
        }

        if ($effectif > 1000000) {
            throw new \InvalidArgumentException('L\'effectif ne peut pas dépasser 1 000 000.');
        }

        if (null === $effectif) {
            throw new \InvalidArgumentException('L\'effectif ne peut pas être nul.');
        }
        $this->effectif = $effectif;

        return $this;
    }

    public function getCycle(): ?Cycles
    {
        return $this->cycle;
    }

    public function setCycle(?Cycles $cycle): static
    {
        $this->cycle = $cycle;

        return $this;
    }

    /**
     * @return Collection<int, Classes>
     */
    public function getClasses(): Collection
    {
        return $this->classes;
    }

    public function addClass(Classes $class): static
    {
        if (!$this->classes->contains($class)) {
            $this->classes->add($class);
            $class->setNiveau($this);
        }

        return $this;
    }

    public function removeClass(Classes $class): static
    {
        if ($this->classes->removeElement($class)) {
            // set the owning side to null (unless already changed)
            if ($class->getNiveau() === $this) {
                $class->setNiveau(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Scolarites1>
     */
    public function getScolarites1s(): Collection
    {
        return $this->scolarites1s;
    }

    public function addScolarites1(Scolarites1 $scolarites1): static
    {
        if (!$this->scolarites1s->contains($scolarites1)) {
            $this->scolarites1s->add($scolarites1);
            $scolarites1->setNiveau($this);
        }

        return $this;
    }

    public function removeScolarites1(Scolarites1 $scolarites1): static
    {
        if ($this->scolarites1s->removeElement($scolarites1)) {
            // set the owning side to null (unless already changed)
            if ($scolarites1->getNiveau() === $this) {
                $scolarites1->setNiveau(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Scolarites2>
     */
    public function getScolarites2s(): Collection
    {
        return $this->scolarites2s;
    }

    public function addScolarites2(Scolarites2 $scolarites2): static
    {
        if (!$this->scolarites2s->contains($scolarites2)) {
            $this->scolarites2s->add($scolarites2);
            $scolarites2->setNiveau($this);
        }

        return $this;
    }

    public function removeScolarites2(Scolarites2 $scolarites2): static
    {
        if ($this->scolarites2s->removeElement($scolarites2)) {
            // set the owning side to null (unless already changed)
            if ($scolarites2->getNiveau() === $this) {
                $scolarites2->setNiveau(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Scolarites3>
     */
    public function getScolarites3s(): Collection
    {
        return $this->scolarites3s;
    }

    public function addScolarites3(Scolarites3 $scolarites3): static
    {
        if (!$this->scolarites3s->contains($scolarites3)) {
            $this->scolarites3s->add($scolarites3);
            $scolarites3->setNiveau($this);
        }

        return $this;
    }

    public function removeScolarites3(Scolarites3 $scolarites3): static
    {
        if ($this->scolarites3s->removeElement($scolarites3)) {
            // set the owning side to null (unless already changed)
            if ($scolarites3->getNiveau() === $this) {
                $scolarites3->setNiveau(null);
            }
        }

        return $this;
    }
}
