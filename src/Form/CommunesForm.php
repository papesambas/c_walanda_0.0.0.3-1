<?php

namespace App\Form;

use App\Entity\Users;
use App\Entity\Cercles;
use App\Entity\Regions;
use App\Entity\Communes;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class CommunesForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('designation', EntityType::class, [
                'class' => Communes::class,
                'choice_label' => 'designation',
                'placeholder' => 'Sélectionnez ou Entrez une commune',
                /*'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        //->where('c.designation IS NULL')
                        ->orderBy('c.designation', 'ASC');
                },*/
                'attr' => [
                    'class' => 'form-control tomselect-commune',
                    //'data-search-url'  => $this->urlGenerator->generate('app_regions_search'),
                    //'data-create-url'  => $this->urlGenerator->generate('app_regions_create'),
                    'data-search-url' => '/communes/search',
                    'data-create-url' => '/communes/create',
                    'data-commune-target' => 'true',
                    'tabindex' => '1',    // 1er champ focus
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'La Désignation ne peut pas être vide.',
                    ]),
                    new Length([
                        'min' => 2,
                        'max' => 60,
                        'minMessage' => 'La Désignation doit comporter au moins {{ limit }} caractères.',
                        'maxMessage' => 'La Désignation ne peut pas dépasser {{ limit }} caractères.',
                    ]),
                    new Regex([
                        'pattern' => "/^\p{L}+(?:[ \-']\p{L}+)*$/u",
                        'message' => 'La Désignation doit contenir uniquement des lettres, des espaces, des apostrophes ou des tirets.',
                    ]),
                ],
                'required' => false,
                'error_bubbling' => false,
            ])
            ->add('region', EntityType::class, [
                'class' => Regions::class,
                'choice_label' => 'designation',
                'placeholder' => 'Sélectionnez ou Choisir une Région',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('r')
                        ->orderBy('r.designation', 'ASC');
                },
                'attr' => [
                    'class' => 'form-control tomselect-region',
                    //'data-search-url'  => $this->urlGenerator->generate('app_regions_search'),
                    //'data-create-url'  => $this->urlGenerator->generate('app_regions_create'),
                    'data-search-url' => '/regions/search',
                    'data-create-url' => '/regions/create',
                    'tabindex' => '1',    // 1er champ focus
                ],
                'required' => false,
                'error_bubbling' => false,
                'mapped' => false,
            ])
        ;

        $builder->get('region')->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) {
                $form = $event->getForm();
                $data = $form->getData();
                $this->addCercleField($form->getParent(), $data);
            }
        );
        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) {
                $form = $event->getForm();
                $data = $event->getData();

                // Vérifier si l'entité Communes existe
                $cercle = $data ? $data->getCercle() : null;

                // Récupérer la région via le cercle si disponible
                $region = $cercle ? $cercle->getRegion() : null;

                // Recréer dynamiquement le champ 'region' avec la valeur initiale
                $form->add('region', EntityType::class, [
                    'class' => Regions::class,
                    'choice_label' => 'designation',
                    'mapped' => false,
                    'placeholder' => 'Sélectionnez ou Choisir une région',
                    'data' => $region, // ✅ PRÉ-REMPLISSAGE
                    'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('r')
                            ->orderBy('r.designation', 'ASC');
                    },
                    'attr' => [
                        'class' => 'form-control tomselect-region',
                        'data-search-url' => '/regions/search',
                        'data-create-url' => '/regions/create',
                        'tabindex' => '1',
                    ],
                    'constraints' => [
                        new NotBlank([
                            'message' => 'La Désignation ne peut pas être vide.',
                        ]),
                        new Length([
                            'min' => 2,
                            'max' => 60,
                            'minMessage' => 'La Désignation doit comporter au moins {{ limit }} caractères.',
                            'maxMessage' => 'La Désignation ne peut pas dépasser {{ limit }} caractères.',
                        ]),
                        new Regex([
                            'pattern' => "/^\p{L}+(?:[ \-']\p{L}+)*$/u",
                            'message' => 'La Désignation doit contenir uniquement des lettres, des espaces, des apostrophes ou des tirets.',
                        ]),
                    ],
                    'required' => false,
                    'error_bubbling' => false,
                ]);

                // Ajouter le champ cercle dépendant de la région
                $this->addCercleField($form, $region);

            }
        );
    }

    public function addCercleField(FormInterface $form, ?Regions $regions)
    {
        $builder = $form->getConfig()->getFormFactory()->createNamedBuilder(
            'cercle',
            EntityType::class,
            null,
            [
                'class' => Cercles::class,
                'choice_label' => 'designation',
                'placeholder' => 'Sélectionnez ou Choisir un Cercle',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->orderBy('c.designation', 'ASC');
                },
                'attr' => [
                    'class' => 'form-control tomselect-cercle',
                    //'data-search-url'  => $this->urlGenerator->generate('app_cercles_search'),
                    //'data-create-url'  => $this->urlGenerator->generate('app_cercles_create'),
                    'data-search-url' => '/cercles/search',
                    'data-create-url' => '/cercles/create',
                    'tabindex' => '1',    // 1er champ focus
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'La Désignation ne peut pas être vide.',
                    ]),
                    new Length([
                        'min' => 2,
                        'max' => 60,
                        'minMessage' => 'La Désignation doit comporter au moins {{ limit }} caractères.',
                        'maxMessage' => 'La Désignation ne peut pas dépasser {{ limit }} caractères.',
                    ]),
                    new Regex([
                        'pattern' => "/^\p{L}+(?:[ \-']\p{L}+)*$/u",
                        'message' => 'La Désignation doit contenir uniquement des lettres, des espaces, des apostrophes ou des tirets.',
                    ]),
                ],
                'required' => false,
                'error_bubbling' => false,
                'auto_initialize' => false,
            ]
        );
        $form->add($builder->getForm());
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Communes::class,
        ]);
    }
}
