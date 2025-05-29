<?php

namespace App\Entity;

use App\Repository\Telephones1Repository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use App\Entity\Trait\CreatedAtTrait;
use App\Entity\Trait\EntityTrackingTrait;
use App\Entity\Trait\SlugTrait;
use Doctrine\ORM\Mapping\UniqueConstraint;


#[ORM\Entity(repositoryClass: Telephones1Repository::class)]
#[ORM\Table(name: 'telephone1')]
#[ORM\UniqueConstraint(name: 'UNIQ_TELEPHONES_1_NUMERO', columns: ['numero'])]
#[ORM\Index(name: 'IDX_TELEPHONES_1_NUMERO', columns: ['numero'])]
class Telephones1
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
        pattern: "/^(?:(?:\+223|00223)[2567]\d{7}|(?:(?:\+(?!223)|00(?!223))\d{1,3}\d{6,12}))$/",
        message: 'le numéro de téléphone est invalide'
    )]


    private ?string $numero = null;

    #[ORM\OneToOne(mappedBy: 'telephone1', cascade: ['persist', 'remove'])]
    private ?Peres $peres = null;

    #[ORM\OneToOne(mappedBy: 'telephone1', cascade: ['persist', 'remove'])]
    private ?Meres $meres = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function __toString()
    {
        return $this->numero ?? 'pas de numéro de téléphone';
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

    public function setPeres(Peres $peres): static
    {
        // set the owning side of the relation if necessary
        if ($peres->getTelephone1() !== $this) {
            $peres->setTelephone1($this);
        }

        $this->peres = $peres;

        return $this;
    }

    public function getMeres(): ?Meres
    {
        return $this->meres;
    }

    public function setMeres(Meres $meres): static
    {
        // set the owning side of the relation if necessary
        if ($meres->getTelephone1() !== $this) {
            $meres->setTelephone1($this);
        }

        $this->meres = $meres;

        return $this;
    }
}
