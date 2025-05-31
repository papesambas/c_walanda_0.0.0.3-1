<?php

namespace App\Entity;

use App\Entity\Trait\CreatedAtTrait;
use App\Entity\Trait\EntityTrackingTrait;
use App\Entity\Trait\SlugTrait;
use App\Repository\NiveauxRepository;
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
}
