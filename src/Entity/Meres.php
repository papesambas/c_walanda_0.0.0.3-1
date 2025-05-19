<?php

namespace App\Entity;

use App\Repository\MeresRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Trait\SlugTrait;
use App\Entity\Trait\CreatedAtTrait;
use App\Entity\Trait\EntityTrackingTrait;

#[ORM\Entity(repositoryClass: MeresRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[ORM\Table(name: 'meres')]
#[ORM\Index(name: 'IDX_MERES_FULLNAME', columns: ['fullname'])]
class Meres
{
    use CreatedAtTrait;
    use SlugTrait;
    use EntityTrackingTrait;
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'meres')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Noms $nom = null;

    #[ORM\ManyToOne(inversedBy: 'meres')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Prenoms $prenom = null;

    #[ORM\ManyToOne(inversedBy: 'meres')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Professions $profession = null;

    #[ORM\OneToOne(inversedBy: 'meres', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Telephones1 $telephone1 = null;

    #[ORM\OneToOne(inversedBy: 'meres', cascade: ['persist', 'remove'])]
    private ?telephones2 $telephone2 = null;

    #[ORM\OneToOne(inversedBy: 'meres', cascade: ['persist', 'remove'])]
    private ?Ninas $nina = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $email = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $fullname = null;

    /**
     * @var Collection<int, Parents>
     */
    #[ORM\OneToMany(targetEntity: Parents::class, mappedBy: 'mere')]
    private Collection $parents;

    public function __construct()
    {
        $this->parents = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?Noms
    {
        return $this->nom;
    }

    public function setNom(?Noms $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?Prenoms
    {
        return $this->prenom;
    }

    public function setPrenom(?Prenoms $prenom): static
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getProfession(): ?Professions
    {
        return $this->profession;
    }

    public function setProfession(?Professions $profession): static
    {
        $this->profession = $profession;

        return $this;
    }

    public function getTelephone1(): ?Telephones1
    {
        return $this->telephone1;
    }

    public function setTelephone1(Telephones1 $telephone1): static
    {
        $this->telephone1 = $telephone1;

        return $this;
    }

    public function getTelephone2(): ?telephones2
    {
        return $this->telephone2;
    }

    public function setTelephone2(?telephones2 $telephone2): static
    {
        $this->telephone2 = $telephone2;

        return $this;
    }

    public function getNina(): ?Ninas
    {
        return $this->nina;
    }

    public function setNina(?Ninas $nina): static
    {
        $this->nina = $nina;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getFullname(): ?string
    {
        return $this->fullname;
    }

    public function setFullname(?string $fullname): static
    {
        // Empêche la modification manuelle si prénom et nom existent
        if ($this->prenom && $this->nom) {
            $this->fullname = $this->prenom->getDesignation() . ' ' . $this->nom->getDesignation();
        } else {
            $this->fullname = $fullname;
        }
        return $this;
    }

    /**
     * @return Collection<int, Parents>
     */
    public function getParents(): Collection
    {
        return $this->parents;
    }

    public function addParent(Parents $parent): static
    {
        if (!$this->parents->contains($parent)) {
            $this->parents->add($parent);
            $parent->setMere($this);
        }

        return $this;
    }

    public function removeParent(Parents $parent): static
    {
        if ($this->parents->removeElement($parent)) {
            // set the owning side to null (unless already changed)
            if ($parent->getMere() === $this) {
                $parent->setMere(null);
            }
        }

        return $this;
    }
}
