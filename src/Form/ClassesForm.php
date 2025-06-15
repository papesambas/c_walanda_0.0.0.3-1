<?php

namespace App\Form;

use App\Entity\Classes;
use App\Entity\Niveaux;
use App\Entity\Etablissements;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Range;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ClassesForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('designation', TextType::class, [
                'label' => 'Classe',
                'attr' => [
                    'placeholder' => 'Ex: 6ème A, CM2, Terminale S',
                    'class' => 'w-full px-4 py-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500',
                    'minlength' => 2,
                    'maxlength' => 255,
                ],
                'label_attr' => ['class' => 'block mb-2 text-sm font-medium text-gray-700'],
                'constraints' => [
                    new NotBlank(['message' => 'La designation est obligatoire.']),
                    new Length([
                        'min' => 2,
                        'max' => 255,
                        'minMessage' => 'La designation doit contenir au moins {{ limit }} caractères.',
                        'maxMessage' => 'La designation ne peut pas dépasser {{ limit }} caractères.',
                    ]),
                    new Regex([
                        'pattern' => "/^[\p{L}\d\s\-']+$/u",
                        'message' => 'Caractères autorisés : lettres, chiffres, espaces, tirets et apostrophes.',
                    ]),
                ],
                'error_bubbling' => false,
            ])
            ->add('capacite', IntegerType::class, [
                'label' => 'Capacité',
                'attr' => [
                    'placeholder' => 'Nombre d\'élèves maximum',
                    'class' => 'w-full px-4 py-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500',
                    'min' => 1,
                    'max' => 100,
                ],
                'label_attr' => ['class' => 'block mb-2 text-sm font-medium text-gray-700'],
                'constraints' => [
                    new NotBlank(['message' => 'La capacité est obligatoire.']),
                    new Range([
                        'min' => 1,
                        'max' => 100,
                        'minMessage' => 'La capacité minimale est {{ limit }} élève.',
                        'maxMessage' => 'La capacité maximale est {{ limit }} élèves.',
                    ]),
                ],
            ])
            ->add('etablissement', EntityType::class, [
                'class' => Etablissements::class,
                'choice_label' => 'designation',
                'placeholder' => 'Sélectionnez un établissement',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('e')
                        ->orderBy('e.designation', 'ASC');
                },
                'label' => 'Établissement',
                'attr' => [
                    'class' => 'w-full px-4 py-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500 tomselect-etablissement',
                    'data-search-url' => '/etablissements/search',
                    'data-create-url' => '/etablissements/create',
                ],
                'label_attr' => ['class' => 'block mb-2 text-sm font-medium text-gray-700'],
                'constraints' => [
                    new NotNull(['message' => 'La sélection d\'un établissement est obligatoire.']),
                ],
                'required' => true,
            ])
            ->add('niveau', EntityType::class, [
                'class' => Niveaux::class,
                'choice_label' => 'designation',
                'placeholder' => 'Sélectionnez un niveau',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('n')
                        ->orderBy('n.designation', 'ASC');
                },
                'label' => 'Niveau scolaire',
                'attr' => [
                    'class' => 'w-full px-4 py-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500 tomselect-niveau',
                    'data-search-url' => '/niveaux/search',
                    'data-create-url' => '/niveaux/create',
                ],
                'label_attr' => ['class' => 'block mb-2 text-sm font-medium text-gray-700'],
                'constraints' => [
                    new NotNull(['message' => 'La sélection d\'un niveau est obligatoire.']),
                ],
                'required' => true,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Classes::class,
        ]);
    }
}