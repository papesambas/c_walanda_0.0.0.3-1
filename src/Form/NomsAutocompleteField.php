<?php

namespace App\Form;

use App\Entity\Noms;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\UX\Autocomplete\Form\AsEntityAutocompleteField;
use Symfony\UX\Autocomplete\Form\BaseEntityAutocompleteType;

#[AsEntityAutocompleteField]
class NomsAutocompleteField extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'class' => Noms::class,
            'placeholder' => 'Sélectionnez ou Entrez un Nom',
            'label' => 'Nom',
            'choice_label' => 'designation',
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('n')
                    ->orderBy('n.designation', 'ASC');
            },
            'attr' => [
                'class' => 'form-control tomselect-nom',
                'data-autocomplete-initialize' => 'false', // Désactive l'auto-init
                'data-search-url' => '/noms/search',
                'data-create-url' => '/noms/create',
            ],
            'required' => false,
            'error_bubbling' => false,
            'constraints' => [
                new \Symfony\Component\Validator\Constraints\NotBlank([
                    'message' => 'Le nom ne peut pas être vide.',
                ]),
                new \Symfony\Component\Validator\Constraints\Length([
                    'min' => 2,
                    'max' => 60,
                    'minMessage' => 'Le nom doit comporter au moins {{ limit }} caractères.',
                    'maxMessage' => 'Le nom ne peut pas dépasser {{ limit }} caractères.',
                ]),
                new \Symfony\Component\Validator\Constraints\Regex([
                    'pattern' => "/^\p{L}+(?:[ \-']\p{L}+)*$/u",
                    'message' => 'Le nom doit contenir uniquement des lettres, des espaces, des apostrophes ou des tirets.',
                ]),
            ],
            // 'searchable_fields' => ['name'],
            // 'security' => 'ROLE_SOMETHING',
        ]);
    }

    public function getParent(): string
    {
        return BaseEntityAutocompleteType::class;
    }
}
