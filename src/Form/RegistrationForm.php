<?php

namespace App\Form;

use Assert\Regex;
use App\Entity\Users;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Validator\Constraints\PasswordStrength;
use Symfony\Component\Validator\Constraints as Assert;

class RegistrationForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'attr' => ['placeholder' => "Nom de Famille"],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Entrez un nom S.V.P',
                    ]),
                    new Length([
                        'min' => 2,
                        'minMessage' => 'le nom doit avoir au moins {{ limit }} caractères',
                        // max length allowed by Symfony for security reasons
                        'max' => 60,
                    ]),
                    new Assert\Regex([
                        'pattern' => "/^\p{L}+(?:[ \-']\p{L}+)*$/u",
                        'message' => 'Le nom ne doit contenir que des lettres, des espaces, des apostrophes ou des traits d\'union.',
                    ])
                ],

                'error_bubbling' => false,
            ])
            ->add('prenom', TextType::class, [
                'attr' => ['placeholder' => "Prénom..."],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Entrez un prénom S.V.P',
                    ]),
                    new Length([
                        'min' => 2,
                        'minMessage' => 'le nom doit avoir au moins {{ limit }} caractères',
                        // max length allowed by Symfony for security reasons
                        'max' => 70,
                    ]),
                    new Assert\Regex([
                        'pattern' => "/^\p{L}+(?:[ \-']\p{L}+)*$/u",
                        'message' => 'Le prénom ne doit contenir que des lettres, des espaces, des apostrophes ou des traits d\'union.',
                    ])
                ],

                'error_bubbling' => false,
            ])
            ->add('username', TextType::class, [
                'attr' => ['placeholder' => "Nom d'utilisateur ..."],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Entrez un nom d\'utilisateur S.V.P',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'le nom d\'utilisateur doit avoir au moins {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 50,
                    ]),
                ],

                'error_bubbling' => false,
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'You should agree to our terms.',
                    ]),
                ],
            ])

            ->add('email', EmailType::class, [
                'label' => "E-mail",
                'required' => true,
                'attr' => [
                    'placeholder' => 'exemple@email.fr',
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Entrez une adresse e-mail S.V.P',
                    ]),
                    new Length([
                        'min' => 3,
                        'minMessage' => 'L\'adresse e-mail doit avoir au moins {{ limit }} caractères',
                        'max' => 180,
                    ]),
                    new Assert\Regex([
                        'pattern' => "/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/",
                        'message' => 'L\'adresse e-mail n\'est pas valide.',
                    ]),
                ],
                'error_bubbling' => false,
            ])

            ->add('plainPassword', RepeatedType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
                'type' => PasswordType::class,
                'options' => [
                    'attr' => [
                        'type' => 'password'
                    ]
                ],
                'first_options'  => [
                    'label' => 'Mot de passe',
                    'attr' => [
                        'autocomplete' => 'new-password',
                        'class' => 'form-control',
                    ],
                ],
                'second_options'  => [
                    'label' => 'Confirmer le mot de passe',
                    'attr' => [
                        'autocomplete' => 'new-password',
                        'class' => 'form-control',
                    ],
                ],
                'constraints' => [
                    new Length([
                        'min' => 6,
                        'minMessage' => 'le nom d\'utilisateur doit avoir au moins {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                    ]),
                    //new NotCompromisedPassword(),
                    new PasswordStrength(
                        [
                            'minScore' => PasswordStrength::STRENGTH_MEDIUM,
                            'message' => 'Your password is too easy to guess. Company\'s security policy requires to use a stronger password.'
                        ]
                    ),

                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Users::class,
        ]);
    }
}
