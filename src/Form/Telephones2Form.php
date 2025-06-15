<?php

namespace App\Form;

use App\Entity\Meres;
use App\Entity\Peres;
use App\Entity\Users;
use App\Entity\Telephones2;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class Telephones2Form extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('numero', TextType::class, [
                'label' => 'Numéro',
                'attr' => [
                    'placeholder' => 'Entrez le numéro',
                    'class' => 'w-full px-4 py-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500',
                    'minlength' => 8,
                    'maxlength' => 23,
                ],
                'label_attr' => ['class' => 'block mb-2 text-sm font-medium text-gray-700'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'le numéro ne peut pas être vide.',
                    ]),
                    new NotNull([
                        'message' => 'le numéro ne peut pas être nul.',
                    ]),
                    new Regex(
                        [
                        'pattern' => "/^(?:(?:\+223|00223)[2567]\d{7}|(?:(?:\+(?!223)|00(?!223))\d{1,3}\d{6,12}))$/",
                        'message' => 'le numéro de téléphone est invalide.',

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
            'data_class' => Telephones2::class,
        ]);
    }
}
