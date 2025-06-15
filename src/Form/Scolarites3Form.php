<?php

namespace App\Form;

use App\Entity\Users;
use App\Entity\Niveaux;
use App\Entity\Scolarites3;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class Scolarites3Form extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('niveau', EntityType::class, [
                'class' => Niveaux::class,
                'placeholder' => 'Sélectionnez ou Choisir un Niveau',
                'choice_label' => 'designation',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('n')
                        ->orderBy('n.id', 'ASC');
                },
                'attr' => [
                    'class' => 'form-control tomselect-niveau',
                    //'data-search-url'  => $this->urlGenerator->generate('app_noms_search'),
                    //'data-create-url'  => $this->urlGenerator->generate('app_noms_create'),
                    'data-search-url' => '/niveaux/search',
                    'data-create-url' => '/niveaux/create',
                    'tabindex' => '3',    // 2ème champ focus
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Le niveau ne peut pas être vide.',
                    ]),
                    new Length([
                        'min' => 2,
                        'max' => 60,
                        'minMessage' => 'Le niveau doit comporter au moins {{ limit }} caractères.',
                        'maxMessage' => 'Le niveau ne peut pas dépasser {{ limit }} caractères.',
                    ]),
                    new Regex([
                        'pattern' => "/^\p{L}+(?:[ \-']\p{L}+)*$/u",
                        'message' => 'Le niveau doit contenir uniquement des lettres, des espaces, des apostrophes ou des tirets.',
                    ]),
                ],
                'required' => false,
                'error_bubbling' => false,
            ])
            ->add('scolarite', TextType::class, [
                'label' => 'Scolarité',
                'attr' => [
                    'placeholder' => 'Entrez une scolarité',
                    'class' => 'w-full px-4 py-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500',
                    'minlength' => 2,
                    'maxlength' => 255,
                ],
                'label_attr' => ['class' => 'block mb-2 text-sm font-medium text-gray-700'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'La scolarité ne peut pas être vide.',
                    ]),
                    new NotNull([
                        'message' => 'La scolarité ne peut pas être nul.',
                    ]),
                    new Regex(
                        [
                            'pattern' => "/^\d+$/",
                            'message' => 'La scolarité doit contenir uniquement des chiffres.',
                        ]
                    ),
                ],
                'required' => true,
                'error_bubbling' => true,
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Scolarites3::class,
        ]);
    }
}
