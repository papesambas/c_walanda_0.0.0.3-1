<?php

namespace App\Entity;

use App\Repository\Telephones2Repository;
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

    #[ORM\OneToOne(mappedBy: 'telephone2', cascade: ['persist', 'remove'])]
    private ?Peres $peres = null;

    #[ORM\OneToOne(mappedBy: 'telephone2', cascade: ['persist', 'remove'])]
    private ?Meres $meres = null;

        public function __toString()
    {
        return $this->numero ?? 'pas de numéro de téléphone';
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

    public function getPeres(): ?Peres
    {
        return $this->peres;
    }

    public function setPeres(?Peres $peres): static
    {
        // unset the owning side of the relation if necessary
        if ($peres === null && $this->peres !== null) {
            $this->peres->setTelephone2(null);
        }

        // set the owning side of the relation if necessary
        if ($peres !== null && $peres->getTelephone2() !== $this) {
            $peres->setTelephone2($this);
        }

        $this->peres = $peres;

        return $this;
    }

    public function getMeres(): ?Meres
    {
        return $this->meres;
    }

    public function setMeres(?Meres $meres): static
    {
        // unset the owning side of the relation if necessary
        if ($meres === null && $this->meres !== null) {
            $this->meres->setTelephone2(null);
        }

        // set the owning side of the relation if necessary
        if ($meres !== null && $meres->getTelephone2() !== $this) {
            $meres->setTelephone2($this);
        }

        $this->meres = $meres;

        return $this;
    }
}
