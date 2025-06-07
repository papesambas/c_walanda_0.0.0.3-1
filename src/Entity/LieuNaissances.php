<?php

namespace App\Entity;

use App\Entity\Trait\SlugTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Trait\CreatedAtTrait;
use App\Entity\Trait\EntityTrackingTrait;
use App\Repository\LieuNaissancesRepository;
use App\Entity\Communes;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: LieuNaissancesRepository::class)]
class LieuNaissances
{
    use CreatedAtTrait;
    use SlugTrait;
    use EntityTrackingTrait;
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 75)]
    #[Assert\NotBlank(message: 'La désignation ne peut pas être vide.')]
    #[Assert\NotNull(message: 'La désignation ne peut pas être nulle')]
    #[Assert\Length(
        min: 2,
        max: 75,
        minMessage: 'La désignation doit comporter au moins {{ limit }} caractères.',
        maxMessage: 'La désignation ne peut pas dépasser {{ limit }} caractères.'
    )]
    #[Assert\Regex(
        pattern: "/^\p{L}+(?:[ \-']\p{L}+)*$/u",
        message: 'La désignation ne doit contenir que des lettres, des espaces, des apostrophes ou des tirets.'
    )]
    private ?string $designation = null;

    #[ORM\ManyToOne(inversedBy: 'lieuNaissances')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\Valid(
        groups: ['Default', 'lieu_naissances_commune'],
        message: 'La commune associée doit être valide.'
    )]
    private ?Communes $commune = null;

    /**
     * @var Collection<int, Eleves>
     */
    #[ORM\OneToMany(targetEntity: Eleves::class, mappedBy: 'lieuNaissance')]
    private Collection $eleves;

    public function __construct()
    {
        $this->eleves = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->designation ?? 'Lieu de naissance sans désignation';
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
        $designation = mb_strtolower($designation, 'UTF-8');
        $designation = mb_strtoupper(mb_substr($designation, 0, 1, 'UTF-8'), 'UTF-8') . mb_substr($designation, 1, null, 'UTF-8');

        $this->designation = $designation;

        return $this;
    }

    public function getCommune(): ?Communes
    {
        return $this->commune;
    }

    public function setCommune(?Communes $commune): static
    {
        $this->commune = $commune;

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
            $elefe->setLieuNaissance($this);
        }

        return $this;
    }

    public function removeElefe(Eleves $elefe): static
    {
        if ($this->eleves->removeElement($elefe)) {
            // set the owning side to null (unless already changed)
            if ($elefe->getLieuNaissance() === $this) {
                $elefe->setLieuNaissance(null);
            }
        }

        return $this;
    }
}
