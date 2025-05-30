<?php

namespace App\Form;

use App\Entity\Users;
use App\Entity\Regions;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class RegionsForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('designation', TextType::class, [
                'label' => 'Région',
                'attr' => [
                    'placeholder' => 'Entrez la designation de la région',
                    'class' => 'w-full px-4 py-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500 tomselect-region',
                    'minlength' => 2,
                    'maxlength' => 255,
                ],
                'label_attr' => ['class' => 'block mb-2 text-sm font-medium text-gray-700'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'La designation ne peut pas être vide.',
                    ]),
                    new NotNull([
                        'message' => 'La designation ne peut pas être nul.',
                    ]),
                    new Regex(
                        [
                            'pattern' => "/^\p{L}+(?:[ \-']\p{L}+)*$/u",
                            'message' => 'La designation doit contenir uniquement des lettres, des espaces, des apostrophes ou des tirets.',
                        ]
                    ),
                ],
                'required' => true,
                'error_bubbling' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Regions::class,
        ]);
    }
}
