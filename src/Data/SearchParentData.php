<?php

namespace App\Data;

use App\Validator\AtLeastOneField;
use Symfony\Component\Validator\Constraints as Assert;

//#[AtLeastOneField]
class SearchParentData
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
    public $qPere = '';

    /**
     * Summary of telephone
     * @var string
     */
    public $telephonePere = '';

    /**
     * Summary of nina
     * @var string
     */
    public $ninaPere = '';

        /**
     * Summary of q
     * @var string
     */
    public $qMere = '';

    /**
     * Summary of telephone
     * @var string
     */
    public $telephoneMere = '';

    /**
     * Summary of nina
     * @var string
     */
    public $ninaMere = '';

}
