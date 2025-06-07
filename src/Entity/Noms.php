<?php

namespace App\Entity;

use App\Entity\Trait\CreatedAtTrait;
use App\Entity\Trait\DesignationTrait;
use App\Entity\Trait\EntityTrackingTrait;
use App\Entity\Trait\SlugTrait;
use App\Repository\NomsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\UniqueConstraint;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: NomsRepository::class)]
#[ORM\Table(name: 'noms')]
#[ORM\UniqueConstraint(name: 'UNIQ_NOMS_DESIGNATION', columns: ['designation'])]
#[ORM\Index(name: 'IDX_NOMS_DESIGNATION', columns: ['designation'])]

class Noms
{
    use CreatedAtTrait;
    use SlugTrait;
    use EntityTrackingTrait;
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 60, unique: true)]
    #[Assert\NotBlank(message: 'designation cannot be blank.')]
    #[Assert\NotNull(message: 'la désignation ne peut pas être nulle')]
    #[Assert\Length(
        min: 2,
        max: 60,
        minMessage: 'designation must be at least {{ limit }} characters long.',
        maxMessage: 'designation cannot be longer than {{ limit }} characters.'
    )]
    #[Assert\Regex(
        pattern: "/^\p{L}+(?:[ \-']\p{L}+)*$/u",
        message: 'The designation must contain only letters, spaces, apostrophes or hyphens.'
    )]
    private ?string $designation = null;

    /**
     * @var Collection<int, Peres>
     */
    #[ORM\OneToMany(targetEntity: Peres::class, mappedBy: 'nom')]
    private Collection $peres;

    /**
     * @var Collection<int, Meres>
     */
    #[ORM\OneToMany(targetEntity: Meres::class, mappedBy: 'nom')]
    private Collection $meres;

    /**
     * @var Collection<int, Eleves>
     */
    #[ORM\OneToMany(targetEntity: Eleves::class, mappedBy: 'nom')]
    private Collection $eleves;

    public function __construct()
    {
        $this->peres = new ArrayCollection();
        $this->meres = new ArrayCollection();
        $this->eleves = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function __toString()
    {
        return $this->designation ?? '';
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
     * @return Collection<int, Peres>
     */
    public function getPeres(): Collection
    {
        return $this->peres;
    }

    public function addPere(Peres $pere): static
    {
        if (!$this->peres->contains($pere)) {
            $this->peres->add($pere);
            $pere->setNom($this);
        }

        return $this;
    }

    public function removePere(Peres $pere): static
    {
        if ($this->peres->removeElement($pere)) {
            // set the owning side to null (unless already changed)
            if ($pere->getNom() === $this) {
                $pere->setNom(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Meres>
     */
    public function getMeres(): Collection
    {
        return $this->meres;
    }

    public function addMere(Meres $mere): static
    {
        if (!$this->meres->contains($mere)) {
            $this->meres->add($mere);
            $mere->setNom($this);
        }

        return $this;
    }

    public function removeMere(Meres $mere): static
    {
        if ($this->meres->removeElement($mere)) {
            // set the owning side to null (unless already changed)
            if ($mere->getNom() === $this) {
                $mere->setNom(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Eleves>
     */
    public function getEleves(): Collection
    {
        return $this->eleves;
    }

    public function addElefe(Eleves $elefe): static
    {
        if (!$this->eleves->contains($elefe)) {
            $this->eleves->add($elefe);
            $elefe->setNom($this);
        }

        return $this;
    }

    public function removeElefe(Eleves $elefe): static
    {
        if ($this->eleves->removeElement($elefe)) {
            // set the owning side to null (unless already changed)
            if ($elefe->getNom() === $this) {
                $elefe->setNom(null);
            }
        }

        return $this;
    }
}
