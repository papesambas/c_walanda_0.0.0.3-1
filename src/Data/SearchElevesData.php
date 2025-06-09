<?php

namespace App\Data;

use App\Entity\Statuts;
use App\Entity\Classes;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class SearchElevesData
{
    /**
     * Numéro de la page pour la pagination.
     * @var int
     */
    public int $page = 1;

    /**
     * Terme de recherche (nom, prénom, etc.).
     * @var string|null
     */
    public ?string $q = null;

    /**
     * Âge minimum de l'élève.
     * @var int|null
     */
    public ?int $age1 = null;

    /**
     * Âge maximum de l'élève.
     * @var int|null
     */
    public ?int $age2 = null;

    /**
     * Liste des statuts sélectionnés (ex: inscrit, en attente, etc.).
     * @var Collection<int, Statuts>
     */
    public Collection $statut;

    /**
     * Liste des classes sélectionnées.
     * @var Collection<int, Classes>
     */
    public Collection $classe;

    /**
     * Constructeur : Initialise les collections pour éviter les erreurs.
     */
    public function __construct()
    {
        $this->statut = new ArrayCollection();
        $this->classe = new ArrayCollection();
    }
}
