<?php

namespace App\Form;

use App\Entity\Users;
use App\Entity\Cycles;
use App\Entity\Niveaux;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class NiveauxForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('designation', TextType::class, [
                'label' => 'Niveau d\'étude',
                'attr' => [
                    'placeholder' => 'Ex: 6ème Année, Maternelle, ...',
                    'class' => 'w-full px-4 py-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500',
                    'minlength' => 2,
                    'maxlength' => 75,
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
            ->add('cycle', EntityType::class, [
                'class' => Cycles::class,
                'choice_label' => 'designation',
                'label' => 'cycle',
                'placeholder' => 'Sélectionnez un cycle',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->orderBy('c.designation', 'ASC');
                },
                'attr' => [
                    'class' => 'form-control tomselect-cycle',
                    //'data-search-url'  => $this->urlGenerator->generate('app_regions_search'),
                    //'data-create-url'  => $this->urlGenerator->generate('app_regions_create'),
                    'data-search-url' => '/cycles/search',
                    'data-create-url' => '/cycles/create',
                    'tabindex' => '1',    // 1er champ focus
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez sélectionner un cycle.']),
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
            'data_class' => Niveaux::class,
        ]);
    }
}
