<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use App\Entity\Trait\SlugTrait;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Trait\CreatedAtTrait;
use App\Repository\ElevesRepository;
use App\Entity\Trait\EntityTrackingTrait;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity(repositoryClass: ElevesRepository::class)]
#[Vich\Uploadable]
class Eleves
{
    use CreatedAtTrait;
    use SlugTrait;
    use EntityTrackingTrait;
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    // NOTE: This is not a mapped field of entity metadata, just a simple property.
    #[Vich\UploadableField(mapping: 'eleves_images', fileNameProperty: 'imageName')]
    private ?File $imageFile = null;

    #[ORM\Column(nullable: true)]
    private ?string $imageName = null;

    #[ORM\ManyToOne(inversedBy: 'eleves')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Noms $nom = null;

    #[ORM\ManyToOne(inversedBy: 'eleves')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Prenoms $prenom = null;

    #[ORM\Column(length: 1)]
    private ?string $sexe = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    private ?\DateTimeImmutable $dateNaissance = null;

    #[ORM\ManyToOne(inversedBy: 'eleves')]
    #[ORM\JoinColumn(nullable: false)]
    private ?LieuNaissances $lieuNaissance = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    private ?\DateTimeImmutable $dateActe = null;

    #[ORM\Column(length: 30)]
    private ?string $numeroActe = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $email = null;

    #[ORM\ManyToOne(inversedBy: 'eleves')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Parents $parent = null;

    #[ORM\ManyToOne(inversedBy: 'eleves')]
    private ?Etablissements $etablissement = null;

    #[ORM\ManyToOne(inversedBy: 'eleves')]
    private ?Classes $classe = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $niveau = null;

    #[ORM\Column]
    private ?bool $isActif = true;

    #[ORM\Column]
    private ?bool $isAdmis = false;

    #[ORM\Column]
    private ?bool $isAllowed = false;

    #[ORM\OneToOne(inversedBy: 'eleves', cascade: ['persist', 'remove'])]
    private ?Users $user = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $fullname = null;

    #[ORM\ManyToOne(inversedBy: 'eleves')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Statuts $statut = null;

    #[ORM\ManyToOne(inversedBy: 'eleves')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Scolarites1 $scolarite1 = null;

    #[ORM\ManyToOne(inversedBy: 'eleves')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Scolarites2 $scolarite2 = null;

    #[ORM\ManyToOne(inversedBy: 'eleves')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Redoublements1 $redoublement1 = null;

    #[ORM\ManyToOne(inversedBy: 'eleves')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Redoublements2 $redoublement2 = null;

    #[ORM\ManyToOne(inversedBy: 'eleves')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Redoublements3 $redoublement3 = null;

    /**
     * @var Collection<int, DossierEleves>
     */
    #[ORM\OneToMany(targetEntity: DossierEleves::class, mappedBy: 'eleve', orphanRemoval: true)]
    private Collection $dossierEleves;

    /**
     * @var Collection<int, Departs>
     */
    #[ORM\OneToMany(targetEntity: Departs::class, mappedBy: 'eleve', orphanRemoval: true)]
    private Collection $departs;

    /**
     * @var Collection<int, Santes>
     */
    #[ORM\OneToMany(targetEntity: Santes::class, mappedBy: 'eleve', orphanRemoval: true)]
    private Collection $santes;

    #[ORM\Column]
    private ?bool $isHandicap = false;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $natureHandicap = null;

    #[ORM\Column(length: 8)]
    private ?string $statutFinance = "Privé";

    #[ORM\Column(type: Types::DATE_IMMUTABLE, nullable: true)]
    private ?\DateTimeImmutable $dateInscription = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    private ?\DateTimeImmutable $dateRecrutement = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $matricule = null;

    public function __construct()
    {
        $this->dossierEleves = new ArrayCollection();
        $this->departs = new ArrayCollection();
        $this->santes = new ArrayCollection();
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

    public function getSexe(): ?string
    {
        return $this->sexe;
    }

    public function setSexe(string $sexe): static
    {
        $this->sexe = $sexe;

        return $this;
    }

    public function getDateNaissance(): ?\DateTimeImmutable
    {
        return $this->dateNaissance;
    }

    public function setDateNaissance(\DateTimeImmutable $dateNaissance): static
    {
        $this->dateNaissance = $dateNaissance;

        return $this;
    }

    public function getLieuNaissance(): ?LieuNaissances
    {
        return $this->lieuNaissance;
    }

    public function setLieuNaissance(?LieuNaissances $lieuNaissance): static
    {
        $this->lieuNaissance = $lieuNaissance;

        return $this;
    }

    public function getDateActe(): ?\DateTimeImmutable
    {
        return $this->dateActe;
    }

    public function setDateActe(\DateTimeImmutable $dateActe): static
    {
        $this->dateActe = $dateActe;

        return $this;
    }

    public function getNumeroActe(): ?string
    {
        return $this->numeroActe;
    }

    public function setNumeroActe(string $numeroActe): static
    {
        $this->numeroActe = $numeroActe;

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

    public function getParent(): ?Parents
    {
        return $this->parent;
    }

    public function setParent(?Parents $parent): static
    {
        $this->parent = $parent;

        return $this;
    }

    public function getEtablissement(): ?Etablissements
    {
        return $this->etablissement;
    }

    public function setEtablissement(?Etablissements $etablissement): static
    {
        $this->etablissement = $etablissement;

        return $this;
    }

    public function getClasse(): ?Classes
    {
        return $this->classe;
    }

    public function setClasse(?Classes $classe): static
    {
        if ($this->classe === $classe) {
            return $this;
        }

        $oldClasse = $this->classe;
        $this->classe = $classe;

        // Mettre à jour l'ancienne classe
        if ($oldClasse !== null) {
            $oldClasse->removeEleve($this);
        }

        // Mettre à jour la nouvelle classe
        if ($classe !== null) {
            $classe->addEleve($this);
        }

        return $this;
    }

    public function getNiveau(): ?string
    {
        return $this->niveau;
    }

    public function setNiveau(?string $niveau): static
    {
        $this->niveau = $niveau;

        return $this;
    }

    public function isActif(): ?bool
    {
        return $this->isActif;
    }

    public function setIsActif(bool $isActif): static
    {
        $this->isActif = $isActif;

        return $this;
    }

    public function isAdmis(): ?bool
    {
        return $this->isAdmis;
    }

    public function setIsAdmis(bool $isAdmis): static
    {
        $this->isAdmis = $isAdmis;

        return $this;
    }

    public function isAllowed(): ?bool
    {
        return $this->isAllowed;
    }

    public function setIsAllowed(bool $isAllowed): static
    {
        $this->isAllowed = $isAllowed;

        return $this;
    }

    public function getUser(): ?Users
    {
        return $this->user;
    }

    public function setUser(?Users $user): static
    {
        $this->user = $user;

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

    public function getStatut(): ?Statuts
    {
        return $this->statut;
    }

    public function setStatut(?Statuts $statut): static
    {
        $this->statut = $statut;

        return $this;
    }

    public function getScolarite1(): ?Scolarites1
    {
        return $this->scolarite1;
    }

    public function setScolarite1(?Scolarites1 $scolarite1): static
    {
        $this->scolarite1 = $scolarite1;

        return $this;
    }

    public function getScolarite2(): ?Scolarites2
    {
        return $this->scolarite2;
    }

    public function setScolarite2(?Scolarites2 $scolarite2): static
    {
        $this->scolarite2 = $scolarite2;

        return $this;
    }

    public function getRedoublement1(): ?Redoublements1
    {
        return $this->redoublement1;
    }

    public function setRedoublement1(?Redoublements1 $redoublement1): static
    {
        $this->redoublement1 = $redoublement1;

        return $this;
    }

    public function getRedoublement2(): ?Redoublements2
    {
        return $this->redoublement2;
    }

    public function setRedoublement2(?Redoublements2 $redoublement2): static
    {
        $this->redoublement2 = $redoublement2;

        return $this;
    }

    public function getRedoublement3(): ?Redoublements3
    {
        return $this->redoublement3;
    }

    public function setRedoublement3(?Redoublements3 $redoublement3): static
    {
        $this->redoublement3 = $redoublement3;

        return $this;
    }

    /**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile|null $imageFile
     */
    public function setImageFile(?File $imageFile = null): void
    {
        $this->imageFile = $imageFile;

        if (null !== $imageFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    public function setImageName(?string $imageName): void
    {
        $this->imageName = $imageName;
    }

    public function getImageName(): ?string
    {
        return $this->imageName;
    }

    /**
     * @return Collection<int, DossierEleves>
     */
    public function getDossierEleves(): Collection
    {
        return $this->dossierEleves;
    }

    public function addDossierElefe(DossierEleves $dossierElefe): static
    {
        if (!$this->dossierEleves->contains($dossierElefe)) {
            $this->dossierEleves->add($dossierElefe);
            $dossierElefe->setEleve($this);
        }

        return $this;
    }

    public function removeDossierElefe(DossierEleves $dossierElefe): static
    {
        if ($this->dossierEleves->removeElement($dossierElefe)) {
            // set the owning side to null (unless already changed)
            if ($dossierElefe->getEleve() === $this) {
                $dossierElefe->setEleve(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Departs>
     */
    public function getDeparts(): Collection
    {
        return $this->departs;
    }

    public function addDepart(Departs $depart): static
    {
        if (!$this->departs->contains($depart)) {
            $this->departs->add($depart);
            $depart->setEleve($this);
        }

        return $this;
    }

    public function removeDepart(Departs $depart): static
    {
        if ($this->departs->removeElement($depart)) {
            // set the owning side to null (unless already changed)
            if ($depart->getEleve() === $this) {
                $depart->setEleve(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Santes>
     */
    public function getSantes(): Collection
    {
        return $this->santes;
    }

    public function addSante(Santes $sante): static
    {
        if (!$this->santes->contains($sante)) {
            $this->santes->add($sante);
            $sante->setEleve($this);
        }

        return $this;
    }

    public function removeSante(Santes $sante): static
    {
        if ($this->santes->removeElement($sante)) {
            // set the owning side to null (unless already changed)
            if ($sante->getEleve() === $this) {
                $sante->setEleve(null);
            }
        }

        return $this;
    }

    public function isHandicap(): ?bool
    {
        return $this->isHandicap;
    }

    public function setIsHandicap(bool $isHandicap): static
    {
        $this->isHandicap = $isHandicap;

        return $this;
    }

    public function getNatureHandicap(): ?string
    {
        return $this->natureHandicap;
    }

    public function setNatureHandicap(?string $natureHandicap): static
    {
        $this->natureHandicap = $natureHandicap;

        return $this;
    }

    public function getStatutFinance(): ?string
    {
        return $this->statutFinance;
    }

    public function setStatutFinance(string $statutFinance): static
    {
        $this->statutFinance = $statutFinance;

        return $this;
    }

    public function getDateInscription(): ?\DateTimeImmutable
    {
        return $this->dateInscription;
    }

    public function setDateInscription(?\DateTimeImmutable $dateInscription): static
    {
        $this->dateInscription = $dateInscription;

        return $this;
    }

    public function getDateRecrutement(): ?\DateTimeImmutable
    {
        return $this->dateRecrutement;
    }

    public function setDateRecrutement(\DateTimeImmutable $dateRecrutement): static
    {
        $this->dateRecrutement = $dateRecrutement;

        return $this;
    }

    public function getMatricule(): ?string
    {
        return $this->matricule;
    }

    public function setMatricule(?string $matricule): static
    {
        $this->matricule = $matricule;

        return $this;
    }
}
