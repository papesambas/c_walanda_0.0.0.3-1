<?php

namespace App\Form;

use App\Entity\Noms;
use App\Entity\Users;
use App\Entity\Cycles;
use App\Entity\Eleves;
use App\Entity\Cercles;
use App\Entity\Classes;
use App\Entity\Niveaux;
use App\Entity\Parents;
use App\Entity\Prenoms;
use App\Entity\Regions;
use App\Entity\Communes;
use App\Entity\Scolarites1;
use App\Entity\Scolarites2;
use App\Entity\Enseignements;
use App\Entity\Etablissements;
use App\Entity\LieuNaissances;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\LessThan;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Validator\Constraints\LessThanOrEqual;

class ElevesForm extends AbstractType
{
    public function __construct(private Security $security) {}
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $user = $this->security->getUser();
        if ($user instanceof Users) {
            $etablissement = $user->getEtablissement();
            $enseignement = $etablissement->getEnseignement();
        } else {
            $etablissements = null;
        }

        $builder
            ->add('nom', EntityType::class, [
                'label' => 'Nom',
                'class' => Noms::class,
                'placeholder' => 'Sélectionnez ou Choisir un Nom',
                'choice_label' => 'designation',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('n')
                        ->orderBy('n.designation', 'ASC');
                },
                'attr' => [
                    'class' => 'form-control tomselect-nom',
                    //'data-search-url'  => $this->urlGenerator->generate('app_noms_search'),
                    //'data-create-url'  => $this->urlGenerator->generate('app_noms_create'),
                    'data-search-url' => '/noms/search',
                    'data-create-url' => '/noms/create',
                    'tabindex' => '1',    // 1er champ focus
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Le nom ne peut pas être vide.',
                    ]),
                    new Length([
                        'min' => 2,
                        'max' => 60,
                        'minMessage' => 'Le nom doit comporter au moins {{ limit }} caractères.',
                        'maxMessage' => 'Le nom ne peut pas dépasser {{ limit }} caractères.',
                    ]),
                    new Regex([
                        'pattern' => "/^\p{L}+(?:[ \-']\p{L}+)*$/u",
                        'message' => 'Le nom doit contenir uniquement des lettres, des espaces, des apostrophes ou des tirets.',
                    ]),
                ],
                'required' => false,
                'error_bubbling' => false,
            ])
            ->add('prenom', EntityType::class, [
                'class' => Prenoms::class,
                'placeholder' => 'Sélectionnez ou Choisir un Prénom',
                'choice_label' => 'designation',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('p')
                        ->orderBy('p.designation', 'ASC');
                },
                'attr' => [
                    'class' => 'form-control tomselect-prenom',
                    //'data-search-url'  => $this->urlGenerator->generate('app_noms_search'),
                    //'data-create-url'  => $this->urlGenerator->generate('app_noms_create'),
                    'data-search-url' => '/prenoms/search',
                    'data-create-url' => '/prenoms/create',
                    'tabindex' => '2',    // 2ème champ focus
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Le prénom ne peut pas être vide.',
                    ]),
                    new Length([
                        'min' => 2,
                        'max' => 60,
                        'minMessage' => 'Le prénom doit comporter au moins {{ limit }} caractères.',
                        'maxMessage' => 'Le prénom ne peut pas dépasser {{ limit }} caractères.',
                    ]),
                    new Regex([
                        'pattern' => "/^\p{L}+(?:[ \-']\p{L}+)*$/u",
                        'message' => 'Le prénom doit contenir uniquement des lettres, des espaces, des apostrophes ou des tirets.',
                    ]),
                ],
                'required' => false,
                'error_bubbling' => false,
            ])
            ->add('sexe', ChoiceType::class, [
                'expanded' => true,
                'multiple' => false,
                'choices' => [
                    'Masculin' => 'M',
                    'Féminin' => 'F'
                ],
                'label_attr' => [
                    'class' => 'radio-inline'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez sélectionner votre genre.',
                    ]),
                ],
            ])
            ->add('dateNaissance', DateType::class, [
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'w-full px-4 py-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500',
                    'placeholder' => 'JJ/MM/AAAA',
                    'max' => (new \DateTime('-3 years'))->format('Y-m-d'), // Format attendu par HTML
                ],
                'label_attr' => ['class' => 'block mb-2 text-sm font-medium text-gray-700'],
                'constraints' => [
                    new NotBlank(['message' => 'La date de naissance est obligatoire.']),
                    new LessThanOrEqual([
                        'value' => 'today',
                        'message' => 'La date de naissance ne peut pas être future.'
                    ]),
                    new LessThan([
                        'value' => '-3 years',
                        'message' => 'L\'élève doit avoir au moins 3 ans.'
                    ]),
                ],
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
                'mapped' => false,
            ])
            ->add('cercle', EntityType::class, [
                'class' => Cercles::class,
                'choice_label' => 'designation',
                'placeholder' => 'Sélectionnez ou Choisir un Cercle',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->orderBy('c.designation', 'ASC');
                },
                'attr' => [
                    'class' => 'form-control tomselect-cercle',
                    //'data-search-url'  => $this->urlGenerator->generate('app_regions_search'),
                    //'data-create-url'  => $this->urlGenerator->generate('app_regions_create'),
                    'data-search-url' => '/cercles/search',
                    'data-create-url' => '/cercles/create',
                    'tabindex' => '1',    // 1er champ focus
                ],
                'required' => false,
                'error_bubbling' => false,
                'mapped' => false,
            ])
            ->add('commune', EntityType::class, [
                'class' => Communes::class,
                'choice_label' => 'designation',
                'placeholder' => 'Sélectionnez ou Choisir une commune',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->orderBy('c.designation', 'ASC');
                },
                'attr' => [
                    'class' => 'form-control tomselect-commune',
                    //'data-search-url'  => $this->urlGenerator->generate('app_regions_search'),
                    //'data-create-url'  => $this->urlGenerator->generate('app_regions_create'),
                    'data-search-url' => '/communes/search',
                    'data-create-url' => '/communes/create',
                    'tabindex' => '1',    // 1er champ focus
                ],
                'required' => false,
                'error_bubbling' => false,
                'mapped' => false,
            ])
            ->add('lieuNaissance', EntityType::class, [
                'class' => LieuNaissances::class,
                'placeholder' => 'Sélectionnez ou Choisir un Lieu de naissance',
                'choice_label' => 'designation',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('l')
                        ->orderBy('l.designation', 'ASC');
                },
                'attr' => [
                    'class' => 'form-control tomselect-lieu-naissance',
                    //'data-search-url'  => $this->urlGenerator->generate('app_noms_search'),
                    //'data-create-url'  => $this->urlGenerator->generate('app_noms_create'),
                    'data-search-url' => '/lieu/naissances/search',
                    'data-create-url' => '/lieu/naissances/create',
                    'tabindex' => '3',    // 2ème champ focus
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Le lieu de naissance ne peut pas être vide.',
                    ]),
                    new Length([
                        'min' => 2,
                        'max' => 60,
                        'minMessage' => 'Le lieu de naissance doit comporter au moins {{ limit }} caractères.',
                        'maxMessage' => 'Le lieu de naissance ne peut pas dépasser {{ limit }} caractères.',
                    ]),
                    new Regex([
                        'pattern' => "/^[\p{L}0-9]+(?:[ \-'\/][\p{L}0-9]+)*$/u",
                        'message' => 'Le lieu de naissance doit contenir uniquement des lettres, des espaces, des apostrophes ou des tirets.',
                    ]),
                ],
                'required' => false,
                'error_bubbling' => false,
            ])
            ->add('dateActe', null, [
                'widget' => 'single_text',
                'label' => 'Date de l\'acte',
                'attr' => [
                    'class' => 'w-full px-4 py-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500',
                    'placeholder' => 'JJ/MM/AAAA',
                    'max' => (new \DateTime())->format('Y-m-d'), // Format attendu par HTML
                ],
                'label_attr' => ['class' => 'block mb-2 text-sm font-medium text-gray-700'],
                'constraints' => [
                    new NotBlank(['message' => 'La date de l\'acte est obligatoire.']),
                    new LessThanOrEqual([
                        'value' => 'today',
                        'message' => 'La date de l\'acte ne peut pas être postérieure à aujourd\'hui.'
                    ]),
                ],
            ])
            ->add('numeroActe', TextType::class, [
                'label' => "Numéro de l'acte",
                'attr' => [
                    'placeholder' => "Entrez le Numéro de l'acte",
                    'class' => 'w-full px-4 py-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500',
                    'minlength' => 2,
                    'maxlength' => 255,
                ],
                'label_attr' => ['class' => 'block mb-2 text-sm font-medium text-gray-700'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Ce numéro ne peut pas être vide.',
                    ]),
                    new NotNull([
                        'message' => 'Ce numéro ne peut pas être nul.',
                    ]),
                    new Regex(
                        [
                            'pattern' => "/^[\p{L}0-9]+(?:[ \-'\/][\p{L}0-9]+)*$/u",
                            'message' => 'Le numéro fiscal ne doit contenir que des lettres, des chiffres, des espaces et des tirets.',
                        ]
                    ),
                ],
                'required' => true,
                'error_bubbling' => true,
            ])
            ->add('email', EmailType::class, [
                'label'       => 'Email',
                'required'    => false,
                'constraints' => [
                    new Length([
                        'max'        => 180,
                        'maxMessage' => 'L\'email ne peut pas dépasser {{ limit }} caractères.',
                    ]),
                    new Regex([
                        'pattern' => '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/',
                        'message' => 'L\'email doit être valide.',
                    ]),
                ],
                'attr' => [
                    'class'       => 'form-control',
                    'placeholder' => 'Entrez votre email',
                    'tabindex'    => '7',    // Positionne ce champ en 7ᵉ au focus
                ],
            ])
            ->add('enseignement', EntityType::class, [
                'class' => Enseignements::class,
                'placeholder' => 'Sélectionnez ou Choisir un enseignement',
                'choice_label' => 'designation',
                'query_builder' => function (EntityRepository $er) use ($etablissement) {
                    $qb = $er->createQueryBuilder('e')
                        ->orderBy('e.designation', 'ASC');
                    if ($etablissement && $etablissement->getEnseignement()) {
                        $qb->andWhere('e.id = :enseignementId')
                            ->setParameter('enseignementId', $etablissement->getEnseignement()->getId());
                    }
                    return $qb;
                },
                'data' => $enseignement,
                'attr' => [
                    'class' => 'form-control tomselect-enseignement',
                    //'data-search-url'  => $this->urlGenerator->generate('app_noms_search'),
                    //'data-create-url'  => $this->urlGenerator->generate('app_noms_create'),
                    'data-search-url' => '/enseignements/search',
                    'data-create-url' => '/enseignements/create',
                    'tabindex' => '3',    // 2ème champ focus
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Enseignement ne peut pas être vide.',
                    ]),
                    new Length([
                        'min' => 2,
                        'max' => 60,
                        'minMessage' => 'Enseignement doit comporter au moins {{ limit }} caractères.',
                        'maxMessage' => 'Enseignement ne peut pas dépasser {{ limit }} caractères.',
                    ]),
                    new Regex([
                        'pattern' => "/^[\p{L}0-9]+(?:[ \-'\/][\p{L}0-9]+)*$/u",
                        'message' => 'Le niveau doit contenir uniquement des lettres, des espaces, des apostrophes ou des tirets.',
                    ]),
                ],
                'required' => false,
                'error_bubbling' => false,
                'mapped' => false,
            ])
            ->add('cycle', EntityType::class, [
                'class' => Cycles::class,
                'placeholder' => 'Sélectionnez ou Choisir un cycle',
                'choice_label' => 'designation',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->orderBy('c.id', 'ASC');
                },
                'attr' => [
                    'class' => 'form-control tomselect-cycle',
                    //'data-search-url'  => $this->urlGenerator->generate('app_noms_search'),
                    //'data-create-url'  => $this->urlGenerator->generate('app_noms_create'),
                    'data-search-url' => '/cycles/search',
                    'data-create-url' => '/cycles/create',
                    'tabindex' => '3',    // 2ème champ focus
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Le cycle ne peut pas être vide.',
                    ]),
                    new Length([
                        'min' => 2,
                        'max' => 60,
                        'minMessage' => 'Le cycle doit comporter au moins {{ limit }} caractères.',
                        'maxMessage' => 'Le cycle ne peut pas dépasser {{ limit }} caractères.',
                    ]),
                    new Regex([
                        'pattern' => "/^[\p{L}0-9]+(?:[ \-'\/][\p{L}0-9]+)*$/u",
                        'message' => 'Le niveau doit contenir uniquement des lettres, des espaces, des apostrophes ou des tirets.',
                    ]),
                ],
                'required' => false,
                'error_bubbling' => false,
                'mapped' => false,
            ])
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
                        'pattern' => "/^[\p{L}0-9]+(?:[ \-'\/][\p{L}0-9]+)*$/u",
                        'message' => 'Le niveau doit contenir uniquement des lettres, des espaces, des apostrophes ou des tirets.',
                    ]),
                ],
                'required' => false,
                'error_bubbling' => false,
            ])
            ->add('classe', EntityType::class, [
                'class' => Classes::class,
                'placeholder' => 'Sélectionnez ou Choisir une classe',
                'choice_label' => 'designation',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->orderBy('c.designation', 'ASC');
                },
                'attr' => [
                    'class' => 'form-control tomselect-classe',
                    //'data-search-url'  => $this->urlGenerator->generate('app_noms_search'),
                    //'data-create-url'  => $this->urlGenerator->generate('app_noms_create'),
                    'data-search-url' => '/classes/search',
                    'data-create-url' => '/classes/create',
                    'tabindex' => '3',    // 2ème champ focus
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'La classe ne peut pas être vide.',
                    ]),
                    new Length([
                        'min' => 2,
                        'max' => 60,
                        'minMessage' => 'La classe doit comporter au moins {{ limit }} caractères.',
                        'maxMessage' => 'La classe ne peut pas dépasser {{ limit }} caractères.',
                    ]),
                    new Regex([
                        'pattern' => "/^[\p{L}0-9]+(?:[ \-'\/][\p{L}0-9]+)*$/u",
                        'message' => 'La classe doit contenir uniquement des lettres, des espaces, des apostrophes ou des tirets.',
                    ]),
                ],
                'required' => false,
                'error_bubbling' => false,
            ])
            ->add('scolarite1', EntityType::class, [
                'class' => Scolarites1::class,
                'placeholder' => 'Sélectionnez ou Choisir une scolarité',
                'choice_label' => 'scolarite',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('s')
                        ->orderBy('s.scolarite', 'ASC');
                },
                'attr' => [
                    'class' => 'form-control tomselect-scolarite1',
                    //'data-search-url'  => $this->urlGenerator->generate('app_noms_search'),
                    //'data-create-url'  => $this->urlGenerator->generate('app_noms_create'),
                    'data-search-url' => '/scolarites1/search',
                    'data-create-url' => '/scolarites1/create',
                    'tabindex' => '3',    // 2ème champ focus
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'La scolarité ne peut pas être vide.',
                    ]),
                    new Length([
                        'min' => 1,
                        'max' => 2,
                        'minMessage' => 'La scolarité doit comporter au moins {{ limit }} caractères.',
                        'maxMessage' => 'La scolarité ne peut pas dépasser {{ limit }} caractères.',
                    ]),
                    new Regex([
                        'pattern' => "/^\d+$/",
                        'message' => 'La scolarité doit contenir uniquement des chiffres.',
                    ]),
                ],
                'required' => false,
                'error_bubbling' => false,
            ])
            ->add('scolarite2', EntityType::class, [
                'class' => Scolarites2::class,
                'placeholder' => 'Sélectionnez ou Choisir une scolarité',
                'choice_label' => 'scolarite',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('s')
                        ->orderBy('s.scolarite', 'ASC');
                },
                'attr' => [
                    'class' => 'form-control tomselect-scolarite2',
                    //'data-search-url'  => $this->urlGenerator->generate('app_noms_search'),
                    //'data-create-url'  => $this->urlGenerator->generate('app_noms_create'),
                    'data-search-url' => '/scolarites2/search',
                    'data-create-url' => '/scolarites2/create',
                    'tabindex' => '3',    // 2ème champ focus
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'La scolarité ne peut pas être vide.',
                    ]),
                    new Length([
                        'min' => 1,
                        'max' => 2,
                        'minMessage' => 'La scolarité doit comporter au moins {{ limit }} caractères.',
                        'maxMessage' => 'La scolarité ne peut pas dépasser {{ limit }} caractères.',
                    ]),
                    new Regex([
                        'pattern' => "/^\d+$/",
                        'message' => 'La scolarité doit contenir uniquement des chiffres.',
                    ]),
                ],
                'required' => false,
                'error_bubbling' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Eleves::class,
        ]);
    }
}
