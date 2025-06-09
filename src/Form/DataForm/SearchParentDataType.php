<?php

namespace App\Form\DataForm;

use App\Data\SearchParentData;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class SearchParentDataType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // Champ de recherche générale (q)
            ->add('qPere', TextType::class, [
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
            // Champ de recherche générale (q)
            ->add('qMere', TextType::class, [
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
            // Champ pour le numéro de téléphone (telephone)
            ->add('telephonePere', TextType::class, [
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
            ->add('ninaPere', TextType::class, [
                'label' => false,
                'required' => false,
                'attr' => [
                    'placeholder' => 'Numéro d\'identification nationale...',
                ],
                'constraints' => [
                    new Regex([
                        'pattern' => "/^(?=(?:\D*\d){9,13})(?=(?:\d*[A-Za-z]){1,3})[A-Za-z0-9 ]{15}$/",
                        'message' => 'Le numéro Nina est incorrect',
                    ]),
                ],
            ])
            ->add('telephoneMere', TextType::class, [
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
            ->add('ninaMere', TextType::class, [
                'label' => false,
                'required' => false,
                'attr' => [
                    'placeholder' => 'Numéro d\'identification nationale...',
                ],
                'constraints' => [
                    new Regex([
                        'pattern' => "/^(?=(?:\D*\d){9,13})(?=(?:\d*[A-Za-z]){1,3})[A-Za-z0-9 ]{15}$/",
                        'message' => 'Le numéro Nina est incorrect',
                    ]),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SearchParentData::class,
            'method' => 'GET', // Utilise la méthode GET pour permettre de partager l'URL des résultats
            'csrf_protection' => false, // Désactive la protection CSRF pour les formulaires de recherche

        ]);
    }
}
