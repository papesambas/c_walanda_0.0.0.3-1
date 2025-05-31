<?php

namespace App\Entity;

use App\Repository\Telephones2Repository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use App\Entity\Trait\CreatedAtTrait;
use App\Entity\Trait\EntityTrackingTrait;
use App\Entity\Trait\SlugTrait;
use Doctrine\ORM\Mapping\UniqueConstraint;

#[ORM\Entity(repositoryClass: Telephones2Repository::class)]
#[ORM\Table(name: 'telephone2')]
#[ORM\Index(name: 'IDX_TELEPHONES_2_NUMERO', columns: ['numero'])]
#[ORM\UniqueConstraint(name: 'UNIQ_TELEPHONES_2_NUMERO', columns: ['numero'])]
class Telephones2
{
    use CreatedAtTrait;
    use SlugTrait;
    use EntityTrackingTrait;
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 23, unique: true)]
    #[Assert\NotBlank(message: 'le numéro ne peut pas être vide.')]
    #[Assert\NotNull(message: 'le numéro ne peut pas être nul')]
    #[Assert\Length(
        min: 8,
        max: 23,
        minMessage: 'le numéro doit avoir au moins {{ limit }} caractères',
        maxMessage: 'le numéro doit avoir au plus {{ limit }} caractères'
    )]
    #[Assert\Regex(
        pattern: "/^(?:(?:\+223|00223)[2567]\d{8}|(?:\+(?!223)\d{1,3}|00(?!223)\d{1,3})\d{6,12})$/",
        message: 'le numéro de téléphone est invalide'
    )]
    private ?string $numero = null;

    /**
     * @var Collection<int, Peres>
     */
    #[ORM\OneToMany(targetEntity: Peres::class, mappedBy: 'telephone2')]
    private Collection $peres;

    /**
     * @var Collection<int, Meres>
     */
    #[ORM\OneToMany(targetEntity: Meres::class, mappedBy: 'telephone2')]
    private Collection $meres;

    public function __construct()
    {
        $this->peres = new ArrayCollection();
        $this->meres = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->numero ?? '';
    }
    
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumero(): ?string
    {
        return $this->numero;
    }

    public function setNumero(string $numero): static
    {
        $this->numero = $numero;

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
            $pere->setTelephone2($this);
        }

        return $this;
    }

    public function removePere(Peres $pere): static
    {
        if ($this->peres->removeElement($pere)) {
            // set the owning side to null (unless already changed)
            if ($pere->getTelephone2() === $this) {
                $pere->setTelephone2(null);
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
            $mere->setTelephone2($this);
        }

        return $this;
    }

    public function removeMere(Meres $mere): static
    {
        if ($this->meres->removeElement($mere)) {
            // set the owning side to null (unless already changed)
            if ($mere->getTelephone2() === $this) {
                $mere->setTelephone2(null);
            }
        }

        return $this;
    }

}
