<?php

namespace App\Form;

use App\Entity\Noms;
use App\Entity\Meres;
use App\Entity\Ninas;
use App\Entity\Prenoms;
use App\Entity\Professions;
use App\Entity\Telephones1;
use App\Entity\Telephones2;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;

class MeresForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', EntityType::class, [
                'label' => 'Nom',
                'class' => Noms::class,
                'placeholder' => 'Sélectionnez ou Choisir un Nom',
                'choice_label' => 'designation',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('n')
                        ->orderBy('n.designation', 'ASC');
                },
                'attr' => [
                    'class' => 'form-control tomselect-nom',
                    //'data-search-url'  => $this->urlGenerator->generate('app_noms_search'),
                    //'data-create-url'  => $this->urlGenerator->generate('app_noms_create'),
                    'data-search-url' => '/noms/search',
                    'data-create-url' => '/noms/create',
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Le nom ne peut pas être vide.',
                    ]),
                    new Length([
                        'min' => 2,
                        'max' => 60,
                        'minMessage' => 'Le nom doit comporter au moins {{ limit }} caractères.',
                        'maxMessage' => 'Le nom ne peut pas dépasser {{ limit }} caractères.',
                    ]),
                    new Regex([
                        'pattern' => "/^\p{L}+(?:[ \-']\p{L}+)*$/u",
                        'message' => 'Le nom doit contenir uniquement des lettres, des espaces, des apostrophes ou des tirets.',
                    ]),
                ],
                'required' => false,
                'error_bubbling' => false,
            ])
            ->add('prenom', EntityType::class, [
                'class' => Prenoms::class,
                'placeholder' => 'Sélectionnez ou Choisir un Prénom',
                'choice_label' => 'designation',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('p')
                        ->orderBy('p.designation', 'ASC');
                },
                'attr' => [
                    'class' => 'form-control tomselect-prenom',
                    //'data-search-url'  => $this->urlGenerator->generate('app_noms_search'),
                    //'data-create-url'  => $this->urlGenerator->generate('app_noms_create'),
                    'data-search-url' => '/prenoms/search',
                    'data-create-url' => '/prenoms/create',
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Le prénom ne peut pas être vide.',
                    ]),
                    new Length([
                        'min' => 2,
                        'max' => 60,
                        'minMessage' => 'Le prénom doit comporter au moins {{ limit }} caractères.',
                        'maxMessage' => 'Le prénom ne peut pas dépasser {{ limit }} caractères.',
                    ]),
                    new Regex([
                        'pattern' => "/^\p{L}+(?:[ \-']\p{L}+)*$/u",
                        'message' => 'Le prénom doit contenir uniquement des lettres, des espaces, des apostrophes ou des tirets.',
                    ]),
                ],
                'required' => false,
                'error_bubbling' => false,
            ])
            ->add('profession', EntityType::class, [
                'class' => Professions::class,
                'placeholder' => 'Sélectionnez ou Choisir une Profession',
                'choice_label' => 'designation',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('p')
                        ->orderBy('p.designation', 'ASC');
                },
                'attr' => [
                    'class' => 'form-control tomselect-profession',
                    //'data-search-url'  => $this->urlGenerator->generate('app_noms_search'),
                    //'data-create-url'  => $this->urlGenerator->generate('app_noms_create'),
                    'data-search-url' => '/professions/search',
                    'data-create-url' => '/professions/create',
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'La profession ne peut pas être vide.',
                    ]),
                    new Length([
                        'min' => 2,
                        'max' => 60,
                        'minMessage' => 'La profession doit comporter au moins {{ limit }} caractères.',
                        'maxMessage' => 'La profession ne peut pas dépasser {{ limit }} caractères.',
                    ]),
                    new Regex([
                        'pattern' => "/^\p{L}+(?:[ \-']\p{L}+)*$/u",
                        'message' => 'La profession doit contenir uniquement des lettres, des espaces, des apostrophes ou des tirets.',
                    ]),
                ],
                'required' => false,
                'error_bubbling' => false,
            ])
            ->add('telephone1', EntityType::class, [
                'class' => Telephones1::class,
                'placeholder' => 'Sélectionnez ou Choisir un numéro de téléphone',
                'choice_label' => 'numero',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('t')
                        ->orderBy('t.numero', 'ASC');
                },
                'attr' => [
                    'class' => 'form-control tomselect-telephone1',
                    //'data-search-url'  => $this->urlGenerator->generate('app_noms_search'),
                    //'data-create-url'  => $this->urlGenerator->generate('app_noms_create'),
                    'data-search-url' => '/telephones1/search',
                    'data-create-url' => '/telephones1/create',
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'le # telephone ne peut pas être vide.',
                    ]),
                    new Length([
                        'min' => 8,
                        'max' => 23,
                        'minMessage' => 'le # de telephone doit comporter au moins {{ limit }} chiffres.',
                        'maxMessage' => 'le # de telephone ne peut pas dépasser {{ limit }} caractères.',
                    ]),
                    new Regex([
                        'pattern' => "/^(?:(?:\+223|00223)[2567]\d{7}|(?:(?:\+(?!223)|00(?!223))\d{1,3}\d{6,12}))$/",
                        'message' => 'le numéro de téléphone est invalide.',
                    ]),
                ],
                'required' => false,
                'error_bubbling' => false,
            ])
            ->add('telephone2', EntityType::class, [
                'class' => Telephones2::class,
                'placeholder' => 'Sélectionnez ou Choisir un numéro de téléphone',
                'choice_label' => 'numero',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('t')
                        ->orderBy('t.numero', 'ASC');
                },
                'attr' => [
                    'class' => 'form-control tomselect-telephone2',
                    //'data-search-url'  => $this->urlGenerator->generate('app_noms_search'),
                    //'data-create-url'  => $this->urlGenerator->generate('app_noms_create'),
                    'data-search-url' => '/telephones2/search',
                    'data-create-url' => '/telephones2/create',
                ],
                'required' => false,
                'error_bubbling' => false,
            ])
            ->add('nina', EntityType::class, [
                'class' => Ninas::class,
                'placeholder' => 'Sélectionnez ou Choisir un numéro Nina',
                'choice_label' => 'numero',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('n')
                        ->orderBy('n.numero', 'ASC');
                },
                'attr' => [
                    'class' => 'form-control tomselect-nina',
                    //'data-search-url'  => $this->urlGenerator->generate('app_noms_search'),
                    //'data-create-url'  => $this->urlGenerator->generate('app_noms_create'),
                    'data-search-url' => '/ninas/search',
                    'data-create-url' => '/ninas/create',
                ],
                'required' => false,
                'error_bubbling' => false,
                'help' => 'la saisie en majuscule est obligatoire.',
            ])

            ->add('email',EmailType::class, [
                'label' => 'Email',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Entrez votre email',
                ],
                'constraints' => [
                    new Length([
                        'max' => 180,
                        'maxMessage' => 'L\'email ne peut pas dépasser {{ limit }} caractères.',
                    ]),
                    new Regex([
                        'pattern' => '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/',
                        'message' => 'L\'email doit être valide.',
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Meres::class,
        ]);
    }
}
