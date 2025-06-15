<?php

namespace App\Form;

use App\Entity\Users;
use App\Entity\Cercles;
use App\Entity\Regions;
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

class CerclesForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('designation', TextType::class, [
                'label' => 'Cercle',
                'attr' => [
                    'placeholder' => 'Ex: Cercle de Kolokani',
                    'class' => 'w-full px-4 py-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500',
                    'minlength' => 2,
                    'maxlength' => 255,
                ],
                'label_attr' => ['class' => 'block mb-2 text-sm font-medium text-gray-700'],
                'constraints' => [
                    new NotBlank(['message' => 'La designation est obligatoire.']),
                    new Length([
                        'min' => 2,
                        'max' => 75,
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
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Cercles::class,
        ]);
    }
}
