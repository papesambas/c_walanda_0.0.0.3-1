<?php

namespace App\Entity;

use App\Entity\Trait\SlugTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Trait\CreatedAtTrait;
use App\Repository\ParentsRepository;
use App\Entity\Trait\EntityTrackingTrait;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ParentsRepository::class)]
class Parents
{
    use CreatedAtTrait;
    use SlugTrait;
    use EntityTrackingTrait;
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'parents', cascade: ['persist'])]
    #[Assert\NotNull(message: 'La mère ne peut pas être nulle.')]
    #[Assert\Valid()]
    #[ORM\JoinColumn(nullable: false)]
    private ?Peres $pere = null;

    #[ORM\ManyToOne(inversedBy: 'parents', cascade: ['persist'])]
    #[Assert\NotNull(message: 'La mère ne peut pas être nulle.')]
    #[Assert\Valid()]
    #[ORM\JoinColumn(nullable: false)]
    private ?Meres $mere = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $fullname = null;

    /**
     * @var Collection<int, Eleves>
     */
    #[ORM\OneToMany(targetEntity: Eleves::class, mappedBy: 'parent')]
    private Collection $eleves;

    public function __construct()
    {
        $this->eleves = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function __toString(): string
    {
        return $this->fullname ?? '';
    }

    public function getPere(): ?Peres
    {
        return $this->pere;
    }

    public function setPere(?Peres $pere): static
    {
        $this->pere = $pere;

        return $this;
    }

    public function getMere(): ?Meres
    {
        return $this->mere;
    }

    public function setMere(?Meres $mere): static
    {
        $this->mere = $mere;

        return $this;
    }

    public function getFullname(): ?string
    {
        return $this->fullname;
    }

    public function setFullname(?string $fullname): static
    {
        $this->fullname = $fullname;

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
            $elefe->setParent($this);
        }

        return $this;
    }

    public function removeElefe(Eleves $elefe): static
    {
        if ($this->eleves->removeElement($elefe)) {
            // set the owning side to null (unless already changed)
            if ($elefe->getParent() === $this) {
                $elefe->setParent(null);
            }
        }

        return $this;
    }
}
