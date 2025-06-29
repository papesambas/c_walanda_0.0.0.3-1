<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use App\Entity\Trait\SlugTrait;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Trait\CreatedAtTrait;
use App\Repository\DepartsRepository;
use App\Entity\Trait\EntityTrackingTrait;

#[ORM\Entity(repositoryClass: DepartsRepository::class)]
class Departs
{
    use CreatedAtTrait;
    use EntityTrackingTrait;
    use SlugTrait;
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    private ?\DateTimeImmutable $dateDepart = null;

    #[ORM\Column(length: 255)]
    private ?string $motif = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $ecoleDestinataire = null;

    #[ORM\ManyToOne(inversedBy: 'departs')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Eleves $eleve = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateDepart(): ?\DateTimeImmutable
    {
        return $this->dateDepart;
    }

    public function setDateDepart(\DateTimeImmutable $dateDepart): static
    {
        $this->dateDepart = $dateDepart;

        return $this;
    }

    public function getMotif(): ?string
    {
        return $this->motif;
    }

    public function setMotif(string $motif): static
    {
        $this->motif = $motif;

        return $this;
    }

    public function getEcoleDestinataire(): ?string
    {
        return $this->ecoleDestinataire;
    }

    public function setEcoleDestinataire(?string $ecoleDestinataire): static
    {
        $this->ecoleDestinataire = $ecoleDestinataire;

        return $this;
    }

    public function getEleve(): ?Eleves
    {
        return $this->eleve;
    }

    public function setEleve(?Eleves $eleve): static
    {
        $this->eleve = $eleve;

        return $this;
    }
}
