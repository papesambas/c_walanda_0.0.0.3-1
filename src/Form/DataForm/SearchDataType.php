<?php

namespace App\Form\DataForm;

use App\Data\SearchData;
use App\Entity\Professions;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class SearchDataType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            // Champ de recherche générale (q)
            ->add('q', TextType::class, [
                'label' => false,
                'required' => false,
                'attr' => [
                    'placeholder' => 'Nom, prénom...',
                ],
                'constraints' => [
                    new Regex([
                        'pattern' => '/^[a-zA-Z\s]+$/',
                        'message' => 'Ce champ ne doit contenir que des lettres et des espaces.',
                    ]),
                ],
            ])
            // Liste des professions (professions)
            // src/Form/YourFormType.php
            ->add('professions', EntityType::class, [
                'label' => false,
                'class' => Professions::class,
                'choice_label' => 'designation',
                'query_builder' => fn(EntityRepository $er) => $er->createQueryBuilder('p')
                    ->where('p.designation IS NOT NULL')
                    ->andWhere('p.designation != :empty')
                    ->setParameter('empty', '')
                    ->orderBy('p.designation', 'ASC'),
                'multiple' => true,
                'expanded' => false,
                'required' => false,
                'attr' => [
                    'class' => 'select-profession',
                    'data-placeholder' => 'Sélectionnez une ou plusieurs professions...',
                ],
            ])
            // Champ pour le numéro de téléphone (telephone)
            ->add('telephone', TextType::class, [
                'label' => false,
                'required' => false,
                'attr' => [
                    'placeholder' => 'Numéro de téléphone...',
                ],
                'constraints' => [
                    new Regex([
                        'pattern' => '/^[0-9+\s]+$/',
                        'message' => 'Le numéro de téléphone ne doit contenir que des chiffres, des espaces et le signe +.',
                    ]),
                ],
            ])

            // Champ pour le NINA (nina)
            ->add('nina', TextType::class, [
                'label' => false,
                'required' => false,
                'attr' => [
                    'placeholder' => 'Numéro d\'identification nationale...',
                ],
                'constraints' => [
                    new Regex([
                        'pattern' => "/^(?=(?:\D*\d){9,13})(?=(?:\d*[A-Za-z]){1,3})[A-Za-z0-9 ]{15}$/",
                        'message' => 'Le numéro Nina est incorrect.',
                    ]),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SearchData::class, // Associe ce formulaire à la classe SearchData
            'method' => 'GET', // Utilise la méthode GET pour permettre de partager l'URL des résultats
            'csrf_protection' => false, // Désactive la protection CSRF pour les formulaires de recherche
        ]);
    }

    public function getBlockPrefix()
    {
        return ''; // Retourne une chaîne vide pour éviter de préfixer les noms des champs
    }
}
