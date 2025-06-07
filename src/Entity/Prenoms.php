<?php

namespace App\Entity;

use App\Repository\PrenomsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use App\Entity\Trait\CreatedAtTrait;
use App\Entity\Trait\EntityTrackingTrait;
use App\Entity\Trait\SlugTrait;
use Doctrine\ORM\Mapping\UniqueConstraint;


#[ORM\Entity(repositoryClass: PrenomsRepository::class)]
#[ORM\Table(name: 'prenoms')]
#[ORM\UniqueConstraint(name: 'UNIQ_PRENOMS_DESIGNATION', columns: ['designation'])]
#[ORM\Index(name: 'IDX_PRENOMS_DESIGNATION', columns: ['designation'])]
class Prenoms
{
    use CreatedAtTrait;
    use SlugTrait;
    use EntityTrackingTrait;
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 75, unique: true)]
    #[Assert\NotBlank(message: 'la designation ne peut pas être vide.')]
    #[Assert\NotNull(message: 'la désignation ne peut pas être nulle')]
    #[Assert\Length(
        min: 2,
        max: 75,
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
    #[ORM\OneToMany(targetEntity: Peres::class, mappedBy: 'prenom')]
    private Collection $peres;

    /**
     * @var Collection<int, Meres>
     */
    #[ORM\OneToMany(targetEntity: Meres::class, mappedBy: 'prenom')]
    private Collection $meres;

    /**
     * @var Collection<int, Eleves>
     */
    #[ORM\OneToMany(targetEntity: Eleves::class, mappedBy: 'prenom')]
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
        $designation = mb_strtolower($designation, 'UTF-8');
        $designation = mb_strtoupper(mb_substr($designation, 0, 1, 'UTF-8'), 'UTF-8') . mb_substr($designation, 1, null, 'UTF-8');

        $this->designation = $designation;

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
            $pere->setPrenom($this);
        }

        return $this;
    }

    public function removePere(Peres $pere): static
    {
        if ($this->peres->removeElement($pere)) {
            // set the owning side to null (unless already changed)
            if ($pere->getPrenom() === $this) {
                $pere->setPrenom(null);
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
            $mere->setPrenom($this);
        }

        return $this;
    }

    public function removeMere(Meres $mere): static
    {
        if ($this->meres->removeElement($mere)) {
            // set the owning side to null (unless already changed)
            if ($mere->getPrenom() === $this) {
                $mere->setPrenom(null);
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
            $elefe->setPrenom($this);
        }

        return $this;
    }

    public function removeElefe(Eleves $elefe): static
    {
        if ($this->eleves->removeElement($elefe)) {
            // set the owning side to null (unless already changed)
            if ($elefe->getPrenom() === $this) {
                $elefe->setPrenom(null);
            }
        }

        return $this;
    }
}
