<?php

namespace App\Entity;

use App\Entity\Trait\SlugTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Trait\CreatedAtTrait;
use App\Repository\CommunesRepository;
use App\Entity\Trait\EntityTrackingTrait;
use App\Entity\Cercles;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CommunesRepository::class)]
class Communes
{
    use CreatedAtTrait;
    use SlugTrait;
    use EntityTrackingTrait;
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 60)]
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

    #[ORM\ManyToOne(inversedBy: 'communes')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\Valid(
        groups: ['Default', 'communes_cercle'],
        message: 'Le cercle associé doit être valide.'
    )]
    private ?Cercles $cercle = null;

    /**
     * @var Collection<int, LieuNaissances>
     */
    #[ORM\OneToMany(targetEntity: LieuNaissances::class, mappedBy: 'commune')]
    private Collection $lieuNaissances;

    public function __construct()
    {
        $this->lieuNaissances = new ArrayCollection();
    }

    public function __toString(): string
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
        $designation = mb_strtolower($designation, 'UTF-8');
        $designation = mb_strtoupper(mb_substr($designation, 0, 1, 'UTF-8'), 'UTF-8') . mb_substr($designation, 1, null, 'UTF-8');

        $this->designation = $designation;

        return $this;
    }

    public function getCercle(): ?Cercles
    {
        return $this->cercle;
    }

    public function setCercle(?Cercles $cercle): static
    {
        $this->cercle = $cercle;

        return $this;
    }

    /**
     * @return Collection<int, LieuNaissances>
     */
    public function getLieuNaissances(): Collection
    {
        return $this->lieuNaissances;
    }

    public function addLieuNaissance(LieuNaissances $lieuNaissance): static
    {
        if (!$this->lieuNaissances->contains($lieuNaissance)) {
            $this->lieuNaissances->add($lieuNaissance);
            $lieuNaissance->setCommune($this);
        }

        return $this;
    }

    public function removeLieuNaissance(LieuNaissances $lieuNaissance): static
    {
        if ($this->lieuNaissances->removeElement($lieuNaissance)) {
            // set the owning side to null (unless already changed)
            if ($lieuNaissance->getCommune() === $this) {
                $lieuNaissance->setCommune(null);
            }
        }

        return $this;
    }
}
