<?php

namespace App\Entity;

use App\Entity\Trait\CreatedAtTrait;
use App\Entity\Trait\EntityTrackingTrait;
use App\Entity\Trait\SlugTrait;
use App\Repository\EnseignementsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: EnseignementsRepository::class)]
class Enseignements
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
     * @var Collection<int, statuts>
     */
    #[ORM\ManyToMany(targetEntity: statuts::class, inversedBy: 'enseignement')]
    #[ORM\JoinTable(name: 'enseignements_statuts')]
    #[Assert\Valid]
    private Collection $statut;

    public function __construct()
    {
        $this->statut = new ArrayCollection();
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
     * @return Collection<int, statuts>
     */
    public function getStatut(): Collection
    {
        return $this->statut;
    }

    public function addStatut(statuts $statut): static
    {
        if (!$this->statut->contains($statut)) {
            $this->statut->add($statut);
        }

        return $this;
    }

    public function removeStatut(statuts $statut): static
    {
        $this->statut->removeElement($statut);

        return $this;
    }
}
