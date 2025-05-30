<?php

namespace App\Entity;

use App\Entity\Trait\CreatedAtTrait;
use App\Entity\Trait\EntityTrackingTrait;
use App\Entity\Trait\SlugTrait;
use App\Repository\RegionsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: RegionsRepository::class)]
class Regions
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
    #[Assert\NotNull(message: 'La désignation ne peut pas être nulle')]
    #[Assert\Length(
        min: 2,
        max: 60,
        minMessage: 'La désignation doit comporter au moins {{ limit }} caractères.',
        maxMessage: 'La désignation ne peut pas dépasser {{ limit }} caractères.'
    )]
    #[Assert\Regex(
        pattern: "/^\p{L}+(?:[ \-']\p{L}+)*$/u",
        message: 'La désignation ne doit contenir que des lettres, des espaces, des apostrophes ou des tirets.'
    )]
    private ?string $designation = null;

    /**
     * @var Collection<int, Cercles>
     */
    #[ORM\OneToMany(targetEntity: Cercles::class, mappedBy: 'region')]
    private Collection $cercles;

    public function __construct()
    {
        $this->cercles = new ArrayCollection();
    }

    public function __toString()
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

    /**
     * @return Collection<int, Cercles>
     */
    public function getCercles(): Collection
    {
        return $this->cercles;
    }

    public function addCercle(Cercles $cercle): static
    {
        if (!$this->cercles->contains($cercle)) {
            $this->cercles->add($cercle);
            $cercle->setRegion($this);
        }

        return $this;
    }

    public function removeCercle(Cercles $cercle): static
    {
        if ($this->cercles->removeElement($cercle)) {
            // set the owning side to null (unless already changed)
            if ($cercle->getRegion() === $this) {
                $cercle->setRegion(null);
            }
        }

        return $this;
    }
}
