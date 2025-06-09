<?php

namespace App\Data;

use App\Entity\Professions;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\SearchDataRepository;

#[ORM\Entity(repositoryClass: SearchDataRepository::class)]
class SearchData
{

    /**
     * Summary of page
     * @var int
     */
    public $page = 1;
    /**
     * Summary of q
     * @var string
     */
    public $q = '';

    /**
     * Summary of professions
     * @var Professions[]
     */
    public $professions = [];

    /**
     * Summary of telephone
     * @var string
     */
    public $telephone = '';

    public $telephone2 = '';

    /**
     * Summary of nina
     * @var string
     */
    public $nina = '';

}
