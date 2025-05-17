<?php

namespace App\Entity\Trait;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

trait DesignationTrait
{
    #[ORM\Column(length: 130)]
    #[Assert\NotBlank(message: 'designation cannot be blank.')]
    #[Assert\Length(
        min: 2,
        max: 130,
        minMessage: 'designation must be at least {{ limit }} characters long.',
        maxMessage: 'designation cannot be longer than {{ limit }} characters.'
    )]
    private ?string $designation = null;

    public function getDesignation(): ?string
    {
        return $this->designation;
    }

    public function setDesignation(string $designation): static
    {
        // Liste des mots à ignorer
        $exceptions = ['de', 'du', 'la', 'le', 'et', 'à'];

        // Supprime les espaces multiples et les espaces en début/fin de chaîne
        $designation = trim(preg_replace('/\s+/', ' ', $designation));

        // Convertit la première lettre de chaque mot en majuscule, sauf les exceptions
        $this->designation = preg_replace_callback('/\b\w+/u', function ($matches) use ($exceptions) {
            $word = $matches[0];
            if (in_array(mb_strtolower($word, 'UTF-8'), $exceptions)) {
                return $word;
            }
            return mb_convert_case($word, MB_CASE_TITLE, 'UTF-8');
        }, $designation);

        return $this;
    }

}
