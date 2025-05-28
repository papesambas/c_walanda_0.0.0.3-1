<?php

namespace App\Entity;

use App\Repository\NinasRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\UniqueConstraint;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\NotNull;
use App\Entity\Trait\CreatedAtTrait;
use App\Entity\Trait\EntityTrackingTrait;
use App\Entity\Trait\SlugTrait;

#[ORM\Entity(repositoryClass: NinasRepository::class)]
#[ORM\Table(name: 'ninas')]
#[ORM\UniqueConstraint(name: 'UNIQ_NINAS_NUMERO', columns: ['numero'])]
#[ORM\Index(name: 'IDX_NINAS_NUMERO', columns: ['numero'])]
class Ninas
{
    use CreatedAtTrait;
    use SlugTrait;
    use EntityTrackingTrait;
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 15, unique: true)]
    #[Assert\NotBlank(message: 'le numéro ne peut pas être vide.')]
    #[Assert\NotNull(message: 'le numéro ne peut pas être nul')]
    #[Assert\Length(
        min: 15,
        max: 15,
        minMessage: 'le numéro doit avoir au moins {{ limit }} caractères',
        maxMessage: 'le numéro doit avoir au plus {{ limit }} caractères'
    )]
    #[Assert\Regex(
        pattern: "/^(?=[A-Z0-9]{13} [A-Z]$)(?!(?:[^A-Z]*[A-Z]){6,})[A-Z0-9]{13} [A-Z]$/",
        message: 'le numéro nina doit avoir 13 caractères majuscule et une lettre majuscule à la fin'
    )]
    private ?string $numero = null;

    #[ORM\OneToOne(mappedBy: 'nina', targetEntity: Peres::class, cascade: ['persist', 'remove'])]
    private ?Peres $peres = null;

    #[ORM\OneToOne(mappedBy: 'nina', targetEntity: Meres::class, cascade: ['persist', 'remove'])]
    private ?Meres $meres = null;

    public function getId(): ?int
    {
        return $this->id;
    }

        public function __toString()
    {
        return $this->numero ?? '';
    }


    public function getNumero(): ?string
    {
        return $this->numero;
    }

    public function setNumero(string $numero): static
    {
        $this->numero = trim($numero);

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
            $this->peres->setNina(null);
        }

        // set the owning side of the relation if necessary
        if ($peres !== null && $peres->getNina() !== $this) {
            $peres->setNina($this);
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
            $this->meres->setNina(null);
        }

        // set the owning side of the relation if necessary
        if ($meres !== null && $meres->getNina() !== $this) {
            $meres->setNina($this);
        }

        $this->meres = $meres;

        return $this;
    }
}
