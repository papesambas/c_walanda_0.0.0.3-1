<?php

namespace App\Form;

use App\Entity\Enseignements;
use App\Entity\Etablissements;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Validator\Constraints\LessThanOrEqual;

class EtablissementsForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('designation', TextType::class, [
                'label' => 'Désignation',
                'attr' => [
                    'placeholder' => 'Ex: Lycée Moderne de Kolokani',
                    'class' => 'w-full px-4 py-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500',
                    'minlength' => 2,
                    'maxlength' => 255,
                ],
                'label_attr' => ['class' => 'block mb-2 text-sm font-medium text-gray-700'],
                'constraints' => [
                    new NotBlank(['message' => 'La désignation est obligatoire.']),
                    new Length([
                        'min' => 2,
                        'max' => 255,
                        'minMessage' => 'La désignation doit contenir au moins {{ limit }} caractères.',
                        'maxMessage' => 'La désignation ne peut pas dépasser {{ limit }} caractères.',
                    ]),
                    new Regex([
                        'pattern' => "/^[\p{L}\d\s\-,']+$/u",
                        'message' => 'Caractères autorisés : lettres, chiffres, espaces, tirets, virgules et apostrophes.',
                    ]),
                ],
            ])
            ->add('formeJuridique', ChoiceType::class, [
                'label' => 'Forme juridique',
                'choices' => [
                    'Public' => 'public',
                    'S.A.R.L' => 'SARL',
                    'Unipersonnel' => 'Unipersonnel',
                    'S.A' => 'SA',
                    'Communautaire' => 'communautaire',
                    'Confessionnel' => 'confessionnel',
                ],
                'placeholder' => 'Sélectionnez une forme juridique',
                'attr' => [
                    'class' => 'w-full px-4 py-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500',
                ],
                'label_attr' => ['class' => 'block mb-2 text-sm font-medium text-gray-700'],
                'constraints' => [
                    new NotBlank(['message' => 'La forme juridique est obligatoire.']),
                ],
            ])
            ->add('decisionCreation', TextType::class, [
                'label' => 'Décision de création',
                'attr' => [
                    'placeholder' => 'Ex: MEN-15896PL-856/1998',
                    'class' => 'w-full px-4 py-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500',
                    'minlength' => 2,
                    'maxlength' => 50,
                ],
                'label_attr' => ['class' => 'block mb-2 text-sm font-medium text-gray-700'],
                'constraints' => [
                    new NotBlank(['message' => 'La décision de création est obligatoire.']),
                    new Length([
                        'min' => 2,
                        'max' => 50,
                        'minMessage' => 'La décision doit contenir au moins {{ limit }} caractères.',
                        'maxMessage' => 'La décision ne peut pas dépasser {{ limit }} caractères.',
                    ]),
                    new Regex([
                        'pattern' => "/^[\p{L}\d\s\-\/]+$/u",
                        'message' => 'Caractères autorisés : lettres, chiffres, espaces, tirets et barres obliques.',
                    ]),
                ],
            ])
            ->add('decisionOuverture', TextType::class, [
                'label' => 'Décision d\'ouverture',
                'attr' => [
                    'placeholder' => 'Ex: MEN-15896PL-856/1998',
                    'class' => 'w-full px-4 py-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500',
                    'minlength' => 2,
                    'maxlength' => 50,
                ],
                'label_attr' => ['class' => 'block mb-2 text-sm font-medium text-gray-700'],
                'constraints' => [
                    new NotBlank(['message' => 'La décision d\'ouverture est obligatoire.']),
                    new Length([
                        'min' => 2,
                        'max' => 50,
                        'minMessage' => 'La décision doit contenir au moins {{ limit }} caractères.',
                        'maxMessage' => 'La décision ne peut pas dépasser {{ limit }} caractères.',
                    ]),
                    new Regex([
                        'pattern' => "/^[\p{L}\d\s\-\/]+$/u",
                        'message' => 'Caractères autorisés : lettres, chiffres, espaces, tirets et barres obliques.',
                    ]),
                ],
            ])
            ->add('dateOuverture', DateType::class, [
                'label' => 'Date d\'ouverture',
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'w-full px-4 py-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500',
                    'placeholder' => 'JJ/MM/AAAA',
                    'max' => (new \DateTime())->format('Y-m-d'), // Format attendu par HTML
                ],
                'label_attr' => ['class' => 'block mb-2 text-sm font-medium text-gray-700'],
                'constraints' => [
                    new NotBlank(['message' => 'La date d\'ouverture est obligatoire.']),
                    new LessThanOrEqual([
                        'value' => 'today',
                        'message' => 'La date d\'ouverture ne peut pas être postérieure à aujourd\'hui.'
                    ]),
                ],
            ])
            ->add('numeroSocial', TextType::class, [
                'label' => 'Numéro Social',
                'attr' => [
                    'placeholder' => 'Ex: 589623458 K',
                    'class' => 'w-full px-4 py-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500',
                    'minlength' => 2,
                    'maxlength' => 30,
                ],
                'label_attr' => ['class' => 'block mb-2 text-sm font-medium text-gray-700'],
                'constraints' => [
                    new Length([
                        'min' => 2,
                        'max' => 30,
                        'minMessage' => 'Le numéro social doit contenir au moins {{ limit }} caractères.',
                        'maxMessage' => 'Le numéro social ne peut pas dépasser {{ limit }} caractères.',
                    ]),
                    new Regex([
                        'pattern' => "/^[\p{L}\d\s\-]+$/u",
                        'message' => 'Caractères autorisés : lettres, chiffres, espaces et tirets.',
                    ]),
                ],
                'required' => false,
            ])
            ->add('numeroFiscal', TextType::class, [
                'label' => 'Numéro Fiscal',
                'attr' => [
                    'placeholder' => 'Ex: 589623458 K',
                    'class' => 'w-full px-4 py-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500',
                    'minlength' => 2,
                    'maxlength' => 30,
                ],
                'label_attr' => ['class' => 'block mb-2 text-sm font-medium text-gray-700'],
                'constraints' => [
                    new Length([
                        'min' => 2,
                        'max' => 30,
                        'minMessage' => 'Le numéro fiscal doit contenir au moins {{ limit }} caractères.',
                        'maxMessage' => 'Le numéro fiscal ne peut pas dépasser {{ limit }} caractères.',
                    ]),
                    new Regex([
                        'pattern' => "/^[\p{L}\d\s\-]+$/u",
                        'message' => 'Caractères autorisés : lettres, chiffres, espaces et tirets.',
                    ]),
                ],
                'required' => false,
            ])
            ->add('compteBancaire', TextType::class, [
                'label' => 'Numéro Compte Bancaire',
                'attr' => [
                    'placeholder' => 'Ex: 001-589623458-001-K',
                    'class' => 'w-full px-4 py-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500',
                    'minlength' => 2,
                    'maxlength' => 50,
                ],
                'label_attr' => ['class' => 'block mb-2 text-sm font-medium text-gray-700'],
                'constraints' => [
                    new Length([
                        'min' => 2,
                        'max' => 50,
                        'minMessage' => 'Le numéro doit contenir au moins {{ limit }} caractères.',
                        'maxMessage' => 'Le numéro ne peut pas dépasser {{ limit }} caractères.',
                    ]),
                    new Regex([
                        'pattern' => "/^[\p{L}\d\s\-\/]+$/u",
                        'message' => 'Caractères autorisés : lettres, chiffres, espaces, tirets et barres obliques.',
                    ]),
                ],
                'required' => false,
            ])
            ->add('adresse', TextType::class, [
                'label' => 'Adresse',
                'attr' => [
                    'placeholder' => 'Ex: Baco coura rue 8965 porte 96',
                    'class' => 'w-full px-4 py-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500',
                    'minlength' => 2,
                    'maxlength' => 255,
                ],
                'label_attr' => ['class' => 'block mb-2 text-sm font-medium text-gray-700'],
                'constraints' => [
                    new Length([
                        'min' => 2,
                        'max' => 255,
                        'minMessage' => 'L\'adresse doit contenir au moins {{ limit }} caractères.',
                        'maxMessage' => 'L\'adresse ne peut pas dépasser {{ limit }} caractères.',
                    ]),
                    new Regex([
                        'pattern' => "/^[\p{L}\d\s\-\/,\.']+$/u",
                        'message' => 'Caractères autorisés : lettres, chiffres, espaces, tirets, virgules, points et apostrophes.',
                    ]),
                ],
                'required' => true,
            ])
            ->add('telephone', TextType::class, [
                'label' => 'Téléphone Fixe',
                'attr' => [
                    'placeholder' => 'Ex: +22320200000 ou 0022320200000',
                    'class' => 'w-full px-4 py-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500',
                    'minlength' => 8,
                    'maxlength' => 15,
                ],
                'label_attr' => ['class' => 'block mb-2 text-sm font-medium text-gray-700'],
                'constraints' => [
                    new NotBlank(['message' => 'Le téléphone fixe est obligatoire.']),
                    new Length([
                        'min' => 8,
                        'max' => 15,
                        'minMessage' => 'Le téléphone doit contenir au moins {{ limit }} caractères.',
                        'maxMessage' => 'Le téléphone ne peut pas dépasser {{ limit }} caractères.',
                    ]),
                    new Regex([
                        'pattern' => "/^(?:(?:\+223|00223)[2567]\d{7}|(?:(?:\+(?!223)|00(?!223))\d{1,3}\d{6,12}))$/",
                        'message' => 'le numéro de téléphone est invalide.',
                    ]),
                ],
            ])
            ->add('telephoneMobile', TextType::class, [
                'label' => 'Téléphone Mobile',
                'attr' => [
                    'placeholder' => 'Ex: +22365000000 ou 0022365000000',
                    'class' => 'w-full px-4 py-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500',
                    'minlength' => 8,
                    'maxlength' => 15,
                ],
                'label_attr' => ['class' => 'block mb-2 text-sm font-medium text-gray-700'],
                'constraints' => [
                    new Length([
                        'min' => 8,
                        'max' => 15,
                        'minMessage' => 'Le téléphone mobile doit contenir au moins {{ limit }} caractères.',
                        'maxMessage' => 'Le téléphone mobile ne peut pas dépasser {{ limit }} caractères.',
                    ]),
                    new Regex([
                        'pattern' => "/^(?:(?:\+223|00223)[2567]\d{7}|(?:(?:\+(?!223)|00(?!223))\d{1,3}\d{6,12}))$/",
                        'message' => 'le numéro de téléphone est invalide.',
                    ]),
                ],
                'required' => false,
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'attr' => [
                    'placeholder' => 'Ex: contact@etablissement.ml',
                    'class' => 'w-full px-4 py-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500',
                ],
                'label_attr' => ['class' => 'block mb-2 text-sm font-medium text-gray-700'],
                'constraints' => [
                    new Length([
                        'max' => 180,
                        'maxMessage' => 'L\'email ne peut pas dépasser {{ limit }} caractères.',
                    ]),
                    new Regex([
                        'pattern' => '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/',
                        'message' => 'Veuillez entrer une adresse email valide.',
                    ]),
                ],
                'required' => false,
            ])
            ->add('enseignement', EntityType::class, [
                'class' => Enseignements::class,
                'choice_label' => 'designation',
                'label' => 'Type d\'enseignement',
                'placeholder' => 'Sélectionnez un enseignement',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('e')
                        ->orderBy('e.designation', 'ASC');
                },
                'attr' => [
                    'class' => 'form-control tomselect-enseignement',
                    //'data-search-url'  => $this->urlGenerator->generate('app_regions_search'),
                    //'data-create-url'  => $this->urlGenerator->generate('app_regions_create'),
                    'data-search-url' => '/enseignements/search',
                    'data-create-url' => '/enseignements/create',
                    'tabindex' => '1',    // 1er champ focus
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez sélectionner un type d\'enseignement.']),
                ],
                'label_attr' => ['class' => 'block mb-2 text-sm font-medium text-gray-700'],
                'required' => false,
                'error_bubbling' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Etablissements::class,
        ]);
    }
}
