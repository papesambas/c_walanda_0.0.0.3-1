<?php

namespace App\Form;

use App\Entity\Users;
use App\Entity\Prenoms;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class PrenomsForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('designation', TextType::class, [
                'label' => 'Prénom',
                'attr' => [
                    'placeholder' => 'Entrez le prénom',
                    'class' => 'w-full px-4 py-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500',
                    'minlength' => 2,
                    'maxlength' => 255,
                ],
                'label_attr' => ['class' => 'block mb-2 text-sm font-medium text-gray-700'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Le prénom ne peut pas être vide.',
                    ]),
                    new NotNull([
                        'message' => 'Le prénom ne peut pas être nul.',
                    ]),
                    new Regex(
                        [
                            'pattern' => "/^\p{L}+(?:[ \-']\p{L}+)*$/u",
                            'message' => 'Le prénom doit contenir uniquement des lettres, des espaces, des apostrophes ou des tirets.',
                        ]
                    ),
                ],
                'required' => true,
                'error_bubbling' => true,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Prenoms::class,
        ]);
    }
}
