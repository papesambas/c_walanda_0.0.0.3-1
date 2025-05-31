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

    /**
     * @var Collection<int, Etablissements>
     */
    #[ORM\OneToMany(targetEntity: Etablissements::class, mappedBy: 'enseignement')]
    private Collection $etablissements;

    /**
     * @var Collection<int, Cycles>
     */
    #[ORM\ManyToMany(targetEntity: Cycles::class, mappedBy: 'enseignement')]
    private Collection $cycles;

    public function __construct()
    {
        $this->statut = new ArrayCollection();
        $this->etablissements = new ArrayCollection();
        $this->cycles = new ArrayCollection();
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

    /**
     * @return Collection<int, Etablissements>
     */
    public function getEtablissements(): Collection
    {
        return $this->etablissements;
    }

    public function addEtablissement(Etablissements $etablissement): static
    {
        if (!$this->etablissements->contains($etablissement)) {
            $this->etablissements->add($etablissement);
            $etablissement->setEnseignement($this);
        }

        return $this;
    }

    public function removeEtablissement(Etablissements $etablissement): static
    {
        if ($this->etablissements->removeElement($etablissement)) {
            // set the owning side to null (unless already changed)
            if ($etablissement->getEnseignement() === $this) {
                $etablissement->setEnseignement(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Cycles>
     */
    public function getCycles(): Collection
    {
        return $this->cycles;
    }

    public function addCycle(Cycles $cycle): static
    {
        if (!$this->cycles->contains($cycle)) {
            $this->cycles->add($cycle);
            $cycle->addEnseignement($this);
        }

        return $this;
    }

    public function removeCycle(Cycles $cycle): static
    {
        if ($this->cycles->removeElement($cycle)) {
            $cycle->removeEnseignement($this);
        }

        return $this;
    }
}
