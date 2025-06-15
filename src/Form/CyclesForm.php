<?php

namespace App\Form;

use App\Entity\Users;
use App\Entity\Cycles;
use App\Entity\Enseignements;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class CyclesForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('designation', TextType::class, [
                'label' => 'Cycle',
                'attr' => [
                    'placeholder' => 'Ex: 1er Cycle, 2nd Cycle, Cycle Secondaire...',
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
            ->add('effectif')
            ->add('createdAt', null, [
                'widget' => 'single_text',
            ])
            ->add('updatedAt', null, [
                'widget' => 'single_text',
            ])
            ->add('slug')
            ->add('enseignement', EntityType::class, [
                'class' => Enseignements::class,
                'choice_label' => 'id',
                'multiple' => true,
            ])
            ->add('createdBy', EntityType::class, [
                'class' => Users::class,
                'choice_label' => 'id',
            ])
            ->add('updatedBy', EntityType::class, [
                'class' => Users::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Cycles::class,
        ]);
    }
}
