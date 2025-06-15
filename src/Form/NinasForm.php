<?php

namespace App\Form;

use App\Entity\Meres;
use App\Entity\Ninas;
use App\Entity\Peres;
use App\Entity\Users;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class NinasForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('numero', TextType::class, [
                'label' => 'Numéro NINA',
                'attr' => [
                    'placeholder' => 'Ex: 4528965236214 A, 589US25263252 P',
                    'class' => 'w-full px-4 py-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500',
                    'minlength' => 15,
                    'maxlength' => 15,
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
                        'pattern' => "/^(?=[A-Z0-9]{13} [A-Z]$)(?!(?:[^A-Z]*[A-Z]){6,})[A-Z0-9]{13} [A-Z]$/",
                        'message' => 'le numéro nina doit avoir 13 caractères majuscule et une lettre majuscule à la fin.',
                    ]),
                ],
                'error_bubbling' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Ninas::class,
        ]);
    }
}
