<?php

namespace App\Entity;

use App\Entity\Trait\EntityTrackingTrait;
use App\Entity\Trait\SlugTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Trait\CreatedAtTrait;
use App\Repository\StatutsRepository;
use Symfony\component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: StatutsRepository::class)]
class Statuts
{
    use CreatedAtTrait;
    use SlugTrait;
    use EntityTrackingTrait;
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 60, unique: true)]
    #[Assert\NotBlank(message: 'La désignation ne peut pas être vide.')]
    #[Assert\Length(
        max: 60,
        maxMessage: 'La désignation ne peut pas dépasser {{ limit }} caractères.'
    )]
    #[Assert\Regex(
        pattern: "/^\p{L}+(?:[ \-']\p{L}+)*$/u",
        message: 'The designation must contain only letters, spaces, apostrophes or hyphens.'
    )]
    private ?string $designation = null;

    /**
     * @var Collection<int, Enseignements>
     */
    #[ORM\ManyToMany(targetEntity: Enseignements::class, mappedBy: 'statut')]
    #[Assert\Valid]
    private Collection $enseignement;

    #[ORM\Column]
    private ?bool $isActive = true;

    public function __construct()
    {
        $this->enseignement = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->designation ?? 'Statut sans désignation';
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
            $enseignement->addStatut($this);
        }

        return $this;
    }

    public function removeEnseignement(Enseignements $enseignement): static
    {
        if ($this->enseignement->removeElement($enseignement)) {
            $enseignement->removeStatut($this);
        }

        return $this;
    }

    public function isActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): static
    {
        $this->isActive = $isActive;

        return $this;
    }
}
