<?php

namespace App\Form;

use App\Entity\Noms;
use App\Entity\Users;
use App\Entity\Cycles;
use App\Entity\Eleves;
use App\Entity\Cercles;
use App\Entity\Classes;
use App\Entity\Niveaux;
use App\Entity\Prenoms;
use App\Entity\Regions;
use App\Entity\Statuts;
use App\Entity\Communes;
use App\Form\SantesForm;
use App\Form\DepartsForm;
use App\Entity\Scolarites1;
use App\Entity\Scolarites2;
use App\Entity\Enseignements;
use App\Entity\Etablissements;
use App\Entity\LieuNaissances;
use App\Entity\Redoublements1;
use App\Entity\Redoublements2;
use App\Entity\Redoublements3;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\All;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\LessThan;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Validator\Constraints\LessThanOrEqual;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class ElevesForm extends AbstractType
{
    public function __construct(private Security $security) {}

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $user = $this->security->getUser();
        $etablissement = ($user instanceof Users) ? $user->getEtablissement() : null;
        $enseignement = $etablissement?->getEnseignement();

        $this->addMediaFields($builder);
        $this->addPersonalInfoFields($builder);
        $this->addBirthInfoFields($builder);
        $this->addAcademicFields($builder, $etablissement, $enseignement);
        $this->addHealthDepartureFields($builder);

        // Ajoutez l'écouteur POST_SET_DATA
        $builder->addEventListener(FormEvents::POST_SET_DATA, function (FormEvent $event) {
            $form = $event->getForm();
            $eleve = $event->getData();

            if (!$eleve) {
                return;
            }

            // Pré-remplir les champs région/cercle/commune
            $this->preFillLocationFields($eleve, $form);

            // Pré-remplir les champs académiques
            $this->preFillAcademicFields($eleve, $form);
        });
    }

    private function preFillLocationFields(Eleves $eleve, $form): void
    {
        $lieuNaissance = $eleve->getLieuNaissance();

        if ($lieuNaissance) {
            $commune = $lieuNaissance->getCommune();
            $cercle = $commune ? $commune->getCercle() : null;
            $region = $cercle ? $cercle->getRegion() : null;

            $form->get('region')->setData($region);
            $form->get('cercle')->setData($cercle);
            $form->get('commune')->setData($commune);
        }
    }

    private function preFillAcademicFields(Eleves $eleve, $form): void
    {
        $classe = $eleve->getClasse();

        // Vérifier si la classe existe avant d'accéder à ses propriétés
        if ($classe) {
            $niveau = $classe->getNiveau();

            // Vérifier si le niveau existe
            if ($niveau) {
                $form->get('cycle')->setData($niveau->getCycle());
                $form->get('niveau')->setData($niveau);
            }
        }
    }

    private function addMediaFields(FormBuilderInterface $builder): void
    {
        $builder
            ->add('imageFile', VichImageType::class, [
                'label' => "Photo d'identité",
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '5M',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/jpg',
                            'image/gif',
                            'image/png',
                            'image/webp',
                        ]
                    ])
                ],
                'allow_delete' => true,
                'delete_label' => 'supprimer',
                'download_uri' => true,
                'download_label' => 'Télécharger',
                'image_uri' => false,
                'asset_helper' => true,
            ])
            ->add('document', FileType::class, [
                'label' => 'Télécharger Documents (Fichier PDF/Word)',
                'mapped' => false,
                'required' => false,
                'multiple' => true,
                'constraints' => [
                    new All([
                        'constraints' => [
                            new File([
                                'maxSize' => '2048k',
                                'mimeTypes' => [
                                    'application/pdf',
                                    'application/x-pdf',
                                    'application/msword',
                                    'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                                ],
                                'mimeTypesMessage' => 'Format valid valid PDF ou word',
                            ])
                        ]
                    ]),
                ]
            ]);
    }

    private function addPersonalInfoFields(FormBuilderInterface $builder): void
    {
        $builder
            ->add('nom', EntityType::class, $this->getNomConfig())
            ->add('prenom', EntityType::class, $this->getPrenomConfig())
            ->add('sexe', ChoiceType::class, $this->getSexeConfig())
            ->add('dateNaissance', DateType::class, $this->getDateNaissanceConfig())
            ->add('email', EmailType::class, $this->getEmailConfig())
            ->add('isAdmis', CheckboxType::class, ['label' => 'Admis', 'required' => false])
            ->add('isActif', CheckboxType::class, ['label' => 'Actif', 'required' => false])
            ->add('isAllowed', CheckboxType::class, ['label' => 'Autorisé', 'required' => false])
            ->add('isHandicap', CheckboxType::class, ['label' => 'Handicapé(e)', 'required' => false])
            ->add('natureHandicap', TextType::class, [
                'attr' => ['placeholder' => "Nature handicape"],
                'required' => false,
            ])
            ->add('statutFinance', ChoiceType::class, $this->getStatutFinanceConfig());
    }

    private function addBirthInfoFields(FormBuilderInterface $builder): void
    {
        $builder
            ->add('region', EntityType::class, $this->getRegionConfig())
            ->add('cercle', EntityType::class, $this->getCercleConfig())
            ->add('commune', EntityType::class, $this->getCommuneConfig())
            ->add('lieuNaissance', EntityType::class, $this->getLieuNaissanceConfig())
            ->add('dateActe', null, $this->getDateActeConfig())
            ->add('numeroActe', TextType::class, $this->getNumeroActeConfig());
    }

    private function addAcademicFields(
        FormBuilderInterface $builder,
        ?Etablissements $etablissement,
        ?Enseignements $enseignement
    ): void {
        $builder
            ->add('enseignement', EntityType::class, $this->getEnseignementConfig($etablissement, $enseignement))
            ->add('cycle', EntityType::class, $this->getCycleConfig())
            ->add('niveau', EntityType::class, $this->getNiveauConfig())
            ->add('statut', EntityType::class, $this->getStatutConfig($etablissement))
            ->add('classe', EntityType::class, $this->getClasseConfig())
            ->add('dateInscription', DateType::class, $this->getDateInscriptionConfig())
            ->add('dateRecrutement', DateType::class, $this->getDateRecrutementConfig())
            ->add('scolarite1', EntityType::class, $this->getScolarite1Config())
            ->add('scolarite2', EntityType::class, $this->getScolarite2Config())
            ->add('redoublement1', EntityType::class, $this->getRedoublement1Config())
            ->add('redoublement2', EntityType::class, $this->getRedoublement2Config())
            ->add('redoublement3', EntityType::class, $this->getRedoublement3Config());
    }

    private function addHealthDepartureFields(FormBuilderInterface $builder): void
    {
        $builder
            ->add('santes', CollectionType::class, [
                'entry_type' => SantesForm::class,
                'label' => false,
                'entry_options' => ['label' => false],
                'by_reference' => false,
                'allow_add' => true,
                'allow_delete' => true,
                'error_bubbling' => false,
            ])
            ->add('departs', CollectionType::class, [
                'entry_type' => DepartsForm::class,
                'label' => false,
                'entry_options' => ['label' => false],
                'by_reference' => false,
                'allow_add' => true,
                'allow_delete' => true,
                'error_bubbling' => false,
            ]);
    }

    // Configuration methods for each field
    private function getNomConfig(): array
    {
        return [
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
                'data-search-url' => '/noms/search',
                'data-create-url' => '/noms/create',
                'tabindex' => '1',
            ],
            'constraints' => [
                new NotBlank(['message' => 'Le nom ne peut pas être vide.']),
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
        ];
    }

    private function getPrenomConfig(): array
    {
        return [
            'class' => Prenoms::class,
            'placeholder' => 'Sélectionnez ou Choisir un Prénom',
            'choice_label' => 'designation',
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('p')
                    ->orderBy('p.designation', 'ASC');
            },
            'attr' => [
                'class' => 'form-control tomselect-prenom',
                'data-search-url' => '/prenoms/search',
                'data-create-url' => '/prenoms/create',
                'tabindex' => '2',
            ],
            'constraints' => [
                new NotBlank(['message' => 'Le prénom ne peut pas être vide.']),
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
        ];
    }

    private function getSexeConfig(): array
    {
        return [
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
        ];
    }

    private function getDateNaissanceConfig(): array
    {
        return [
            'widget' => 'single_text',
            'attr' => [
                'class' => 'w-full px-4 py-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500',
                'placeholder' => 'JJ/MM/AAAA',
                'max' => (new \DateTime('-3 years'))->format('Y-m-d'),
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
        ];
    }

    private function getRegionConfig(): array
    {
        return [
            'class' => Regions::class,
            'choice_label' => 'designation',
            'placeholder' => 'Sélectionnez ou Choisir une Région',
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('r')
                    ->orderBy('r.designation', 'ASC');
            },
            'attr' => [
                'class' => 'form-control tomselect-region',
                'data-search-url' => '/regions/search',
                'data-create-url' => '/regions/create',
                'tabindex' => '3',
            ],
            'required' => false,
            'error_bubbling' => false,
            'mapped' => false,
        ];
    }

    private function getCercleConfig(): array
    {
        return [
            'class' => Cercles::class,
            'choice_label' => 'designation',
            'placeholder' => 'Sélectionnez ou Choisir un Cercle',
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('c')
                    ->orderBy('c.designation', 'ASC');
            },
            'attr' => [
                'class' => 'form-control tomselect-cercle',
                'data-search-url' => '/cercles/search',
                'data-create-url' => '/cercles/create',
                'tabindex' => '4',
            ],
            'required' => false,
            'error_bubbling' => false,
            'mapped' => false,
        ];
    }

    private function getCommuneConfig(): array
    {
        return [
            'class' => Communes::class,
            'choice_label' => 'designation',
            'placeholder' => 'Sélectionnez ou Choisir une commune',
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('c')
                    ->orderBy('c.designation', 'ASC');
            },
            'attr' => [
                'class' => 'form-control tomselect-commune',
                'data-search-url' => '/communes/search',
                'data-create-url' => '/communes/create',
                'tabindex' => '5',
            ],
            'required' => false,
            'error_bubbling' => false,
            'mapped' => false,
        ];
    }

    private function getLieuNaissanceConfig(): array
    {
        return [
            'class' => LieuNaissances::class,
            'placeholder' => 'Sélectionnez ou Choisir un Lieu de naissance',
            'choice_label' => 'designation',
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('l')
                    ->orderBy('l.designation', 'ASC');
            },
            'attr' => [
                'class' => 'form-control tomselect-lieu-naissance',
                'data-search-url' => '/lieu/naissances/search',
                'data-create-url' => '/lieu/naissances/create',
                'tabindex' => '6',
            ],
            'constraints' => [
                new NotBlank(['message' => 'Le lieu de naissance ne peut pas être vide.']),
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
        ];
    }

    private function getDateActeConfig(): array
    {
        return [
            'widget' => 'single_text',
            'label' => 'Date de l\'acte',
            'attr' => [
                'class' => 'w-full px-4 py-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500',
                'placeholder' => 'JJ/MM/AAAA',
                'max' => (new \DateTime())->format('Y-m-d'),
            ],
            'label_attr' => ['class' => 'block mb-2 text-sm font-medium text-gray-700'],
            'constraints' => [
                new NotBlank(['message' => 'La date de l\'acte est obligatoire.']),
                new LessThanOrEqual([
                    'value' => 'today',
                    'message' => 'La date de l\'acte ne peut pas être postérieure à aujourd\'hui.'
                ]),
                new Callback(function ($dateActe, ExecutionContextInterface $context) {
                    $form = $context->getRoot();
                    $dateNaissance = $form->get('dateNaissance')?->getData();

                    if ($dateActe instanceof \DateTimeInterface && $dateNaissance instanceof \DateTimeInterface) {
                        if ($dateActe < $dateNaissance) {
                            $context->buildViolation("La date de l'acte ne peut pas être antérieure à la date de naissance.")
                                ->atPath('dateActe')
                                ->addViolation();
                        }
                    }
                }),
            ],
        ];
    }


    private function getNumeroActeConfig(): array
    {
        return [
            'label' => "Numéro de l'acte",
            'attr' => [
                'placeholder' => "Entrez le Numéro de l'acte",
                'class' => 'w-full px-4 py-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500',
                'minlength' => 2,
                'maxlength' => 255,
            ],
            'label_attr' => ['class' => 'block mb-2 text-sm font-medium text-gray-700'],
            'constraints' => [
                new NotBlank(['message' => 'Ce numéro ne peut pas être vide.']),
                new NotNull(['message' => 'Ce numéro ne peut pas être nul.']),
                new Regex([
                    'pattern' => "/^[\p{L}0-9]+(?:[ \-\.'\/][\p{L}0-9]+)*$/u",
                    'message' => 'Le numéro fiscal ne doit contenir que des lettres, des chiffres, des espaces et des tirets.',
                ]),
            ],
            'required' => true,
            'error_bubbling' => true,
        ];
    }

    private function getEmailConfig(): array
    {
        return [
            'label' => 'Email',
            'required' => false,
            'constraints' => [
                new Length([
                    'max' => 180,
                    'maxMessage' => 'L\'email ne peut pas dépasser {{ limit }} caractères.',
                ]),
                new Regex([
                    'pattern' => '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/',
                    'message' => 'L\'email doit être valide.',
                ]),
            ],
            'attr' => [
                'class' => 'form-control',
                'placeholder' => 'Entrez votre email',
                'tabindex' => '7',
            ],
        ];
    }

    private function getStatutFinanceConfig(): array
    {
        return [
            'label' => 'Statut Financier',
            'expanded' => true,
            'multiple' => false,
            'choices' => [
                'privé' => 'Privé',
                'boursier' => 'Boursier',
                'exonoré' => 'Exonoré'
            ],
            'label_attr' => [
                'class' => 'radio-inline'
            ]
        ];
    }

    private function getEnseignementConfig(
        ?Etablissements $etablissement,
        ?Enseignements $enseignement
    ): array {
        return [
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
                'data-search-url' => '/enseignements/search',
                'data-create-url' => '/enseignements/create',
                'tabindex' => '8',
            ],
            'constraints' => [
                new NotBlank(['message' => 'Enseignement ne peut pas être vide.']),
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
        ];
    }

    private function getCycleConfig(): array
    {
        return [
            'class' => Cycles::class,
            'placeholder' => 'Sélectionnez ou Choisir un cycle',
            'choice_label' => 'designation',
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('c')
                    ->orderBy('c.id', 'ASC');
            },
            'attr' => [
                'class' => 'form-control tomselect-cycle',
                'data-search-url' => '/cycles/search',
                'data-create-url' => '/cycles/create',
                'tabindex' => '9',
            ],
            'constraints' => [
                new NotBlank(['message' => 'Le cycle ne peut pas être vide.']),
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
        ];
    }

    private function getNiveauConfig(): array
    {
        return [
            'class' => Niveaux::class,
            'placeholder' => 'Sélectionnez ou Choisir un Niveau',
            'choice_label' => 'designation',
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('n')
                    ->orderBy('n.id', 'ASC');
            },
            'attr' => [
                'class' => 'form-control tomselect-niveau',
                'data-search-url' => '/niveaux/search',
                'data-create-url' => '/niveaux/create',
                'tabindex' => '10',
            ],
            'constraints' => [
                new NotBlank(['message' => 'Le niveau ne peut pas être vide.']),
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
            'mapped' => false,
        ];
    }

    private function getStatutConfig(?Etablissements $etablissement): array
    {
        return [
            'class' => Statuts::class,
            'placeholder' => 'Sélectionnez ou Choisir un Statut',
            'choice_label' => 'designation',
            'query_builder' => function (EntityRepository $er) use ($etablissement) {
                $qb = $er->createQueryBuilder('s')
                    ->orderBy('s.designation', 'ASC');

                if ($etablissement && $etablissement->getEnseignement()) {
                    $enseignementId = $etablissement->getEnseignement()->getId();
                    $qb->innerJoin('s.enseignement', 'e')
                        ->andWhere('e.id = :enseignementId')
                        ->setParameter('enseignementId', $enseignementId);
                }

                return $qb;
            },
            'attr' => [
                'class' => 'form-control tomselect-statut',
                'data-search-url' => '/statuts/search',
                'data-create-url' => '/statuts/create',
                'tabindex' => '10',
            ],
            'constraints' => [
                new NotBlank(['message' => 'Le statut ne peut pas être vide.']),
                new Length([
                    'min' => 2,
                    'max' => 60,
                    'minMessage' => 'Le niveau doit comporter au moins {{ limit }} caractères.',
                    'maxMessage' => 'Le niveau ne peut pas dépasser {{ limit }} caractères.',
                ]),
                new Regex([
                    'pattern' => "/^[\p{L}0-9]+(?:[ \-'\/][\p{L}0-9]+)*$/u",
                    'message' => 'Le niveau doit contenir uniquement des chiffres, lettres, des espaces, des apostrophes ou des tirets.',
                ]),
            ],
            'required' => false,
            'error_bubbling' => false,
        ];
    }

    private function getClasseConfig(): array
    {
        return [
            'class' => Classes::class,
            'placeholder' => 'Sélectionnez ou Choisir une classe',
            'choice_label' => 'designation',
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('c')
                    ->orderBy('c.designation', 'ASC');
            },
            'attr' => [
                'class' => 'form-control tomselect-classe',
                'data-search-url' => '/classes/search',
                'data-create-url' => '/classes/create',
                'tabindex' => '11',
            ],
            'constraints' => [
                new NotBlank(['message' => 'La classe ne peut pas être vide.']),
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
        ];
    }

    private function getDateInscriptionConfig(): array
    {
        return [
            'widget' => 'single_text',
            'attr' => [
                'class' => 'w-full px-4 py-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500',
                'placeholder' => 'JJ/MM/AAAA',
                'max' => (new \DateTime())->format('Y-m-d'), // Aujourd'hui maximum
            ],
            'label_attr' => ['class' => 'block mb-2 text-sm font-medium text-gray-700'],
            'constraints' => [
                new NotBlank(['message' => 'La date d\'inscription est obligatoire.']),
                new LessThanOrEqual([
                    'value' => 'today',
                    'message' => 'La date d\'inscription ne peut pas être future.'
                ]),
            ],
        ];
    }

    private function getDateRecrutementConfig(): array
    {
        $today = new \DateTime();
        $minDate = $today->modify('-11 years')->format('Y-m-d'); // Valeur par défaut pour le plus ancien

        return [
            'widget' => 'single_text',
            'attr' => [
                'class' => 'w-full px-4 py-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500',
                'placeholder' => 'JJ/MM/AAAA',
            ],
            'label_attr' => ['class' => 'block mb-2 text-sm font-medium text-gray-700'],
            'constraints' => [
                new NotBlank(['message' => 'La date de recrutement est obligatoire.']),
                new LessThanOrEqual([
                    'value' => 'today',
                    'message' => 'La date de recrutement ne peut pas être future.'
                ]),
                new Callback(function ($dateRecrutement, ExecutionContextInterface $context) {
                    $form = $context->getRoot();
                    $dateNaissance = $form->get('dateNaissance')->getData();
                    $niveau = $form->get('niveau')->getData();

                    // Validation des données requises
                    if (!$dateNaissance instanceof \DateTimeInterface) {
                        $context->buildViolation('La date de naissance est requise pour la validation')
                            ->atPath('dateNaissance')
                            ->addViolation();
                        return;
                    }

                    if (!$niveau instanceof Niveaux) {
                        $context->buildViolation('Le niveau est requis pour la validation')
                            ->atPath('niveau')
                            ->addViolation();
                        return;
                    }

                    // Calcul de l'âge à la date de recrutement
                    $today = new \DateTimeImmutable();
                    $age = $dateNaissance->diff($today)->y;
                    $agemin = $niveau->getAgemin();
                    $agemax = $niveau->getAgemax();

                    // Validation de l'âge
                    if ($age < $agemin) {
                        $context->buildViolation("L'âge de l'élève ({$age} ans) est inférieur à l'âge minimum requis ({$agemin} ans) pour ce niveau")
                            ->atPath('dateRecrutement')
                            ->addViolation();
                    }

                    if ($age > $agemax) {
                        $context->buildViolation("L'âge de l'élève ({$age} ans) dépasse l'âge maximum autorisé ({$agemax} ans) pour ce niveau")
                            ->atPath('dateRecrutement')
                            ->addViolation();
                    }

                    // Configuration des contraintes de durée
                    $designation = $niveau->getDesignation();
                    $duree = $dateRecrutement->diff(new \DateTime())->y;

                    $dureeConstraints = [
                        'Maternelle' => ['max' => 1],
                        'Petite Section' => ['max' => 1],
                        'Moyenne Section' => ['max' => 1],
                        'Grande Section' => ['max' => 1],
                        '1ère Année' => ['min' => 1, 'max' => 2],
                        '2ème Année' => ['min' => 2, 'max' => 4],
                        '3ème Année' => ['min' => 3, 'max' => 5],
                        '4ème Année' => ['min' => 4, 'max' => 6],
                        '5ème Année' => ['min' => 5, 'max' => 7],
                        '6ème Année' => ['min' => 6, 'max' => 8],
                        '7ème Année' => ['min' => 7, 'max' => 9],
                        '8ème Année' => ['min' => 8, 'max' => 10],
                        '9ème Année' => ['min' => 9, 'max' => 11],
                    ];

                    if (isset($dureeConstraints[$designation])) {
                        $constraint = $dureeConstraints[$designation];

                        if (isset($constraint['max']) && $duree > $constraint['max']) {
                            $context->buildViolation("La durée de recrutement ({$duree} ans) dépasse la durée maximale autorisée ({$constraint['max']} ans) pour '{$designation}'")
                                ->atPath('dateRecrutement')
                                ->addViolation();
                        }

                        if (isset($constraint['min']) && $duree < $constraint['min']) {
                            $context->buildViolation("La durée de recrutement ({$duree} ans) est inférieure à la durée minimale requise ({$constraint['min']} ans) pour '{$designation}'")
                                ->atPath('dateRecrutement')
                                ->addViolation();
                        }
                    }

                    // Cohérence avec la date d'inscription
                    $dateInscription = $form->get('dateInscription')->getData();
                    if ($dateInscription instanceof \DateTimeInterface && $dateRecrutement > $dateInscription) {
                        $context->buildViolation("La date de recrutement doit être antérieure ou égale à la date d'inscription")
                            ->atPath('dateRecrutement')
                            ->addViolation();
                    }
                }),
            ],
        ];
    }

    private function getScolarite1Config(): array
    {
        return [
            'class' => Scolarites1::class,
            'placeholder' => 'Sélectionnez ou Choisir une scolarité',
            'choice_label' => 'scolarite',
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('s')
                    ->orderBy('s.scolarite', 'ASC');
            },
            'attr' => [
                'class' => 'form-control tomselect-scolarite1',
                'data-search-url' => '/scolarites1/search',
                'data-create-url' => '/scolarites1/create',
                'tabindex' => '12',
            ],
            'constraints' => [
                new NotBlank(['message' => 'La scolarité ne peut pas être vide.']),
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
        ];
    }

    private function getScolarite2Config(): array
    {
        return [
            'class' => Scolarites2::class,
            'placeholder' => 'Sélectionnez ou Choisir une scolarité',
            'choice_label' => 'scolarite',
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('s')
                    ->orderBy('s.scolarite', 'ASC');
            },
            'attr' => [
                'class' => 'form-control tomselect-scolarite2',
                'data-search-url' => '/scolarites2/search',
                'data-create-url' => '/scolarites2/create',
                'tabindex' => '13',
                // Ajout des attributs pour le script
                'data-duration-field' => 'eleves_form_dateRecrutement',
                'data-scolarite1-field' => 'eleves_form_scolarite1',
            ],
            'constraints' => [
                new NotBlank(['message' => 'La scolarité ne peut pas être vide.']),
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
                new Callback([
                    'callback' => function ($scolarite2Entity, ExecutionContextInterface $context) {
                        $form = $context->getRoot();
                        $scolarite1Entity = $form->get('scolarite1')->getData();
                        $dateRecrutement = $form->get('dateRecrutement')->getData();

                        if (
                            !$scolarite1Entity instanceof Scolarites1 ||
                            !$scolarite2Entity instanceof Scolarites2 ||
                            !$dateRecrutement instanceof \DateTimeInterface
                        ) {
                            return;
                        }

                        $scolarite1Value = $scolarite1Entity->getScolarite();
                        $scolarite2Value = $scolarite2Entity->getScolarite();
                        $totalScolarite = $scolarite1Value + $scolarite2Value;

                        $now = new \DateTime();
                        $interval = $dateRecrutement->diff($now);
                        $dureePrecise = $interval->y + ($interval->m / 12) + ($interval->d / 365);

                        $tolerance = 0.5;
                        $difference = abs($totalScolarite - $dureePrecise);

                        if ($difference > $tolerance) {
                            $context->buildViolation('La somme des scolarités ({{ total }}) doit correspondre à la durée de service ({{ duree }} ans) - écart maximum: 6 mois')
                                ->setParameter('{{ total }}', $totalScolarite)
                                ->setParameter('{{ duree }}', number_format($dureePrecise, 1, ',', ' '))
                                ->addViolation();
                        }
                    },
                ]),
            ],
            'required' => false,
            'error_bubbling' => false,
        ];
    }

    private function getRedoublement1Config(): array
    {
        return [
            'class' => Redoublements1::class,
            'placeholder' => 'Sélectionnez le redoublement',
            'choice_label' => 'designation',
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('r1')
                    ->orderBy('r1.designation', 'ASC');
            },
            'attr' => [
                'class' => 'form-control tomselect-redoublement1',
                'data-search-url' => '/redoublements1/search',
                'data-create-url' => '/redoublements1/create',
                'tabindex' => '14',
            ],
            'constraints' => [
                new NotBlank(['message' => 'Le redoublement ne peut pas être vide.']),
            ],
            'required' => true,
            'error_bubbling' => false,
        ];
    }

    private function getRedoublement2Config(): array
    {
        return [
            'class' => Redoublements2::class,
            'placeholder' => 'Sélectionnez le redoublement',
            'choice_label' => 'designation',
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('r2')
                    ->orderBy('r2.designation', 'ASC');
            },
            'attr' => [
                'class' => 'form-control tomselect-redoublement2',
                'data-search-url' => '/redoublements2/search',
                'data-create-url' => '/redoublements2/create',
                'tabindex' => '15',
            ],
            'constraints' => [
                new NotBlank(['message' => 'Le redoublement ne peut pas être vide.']),
            ],
            'required' => true,
            'error_bubbling' => false,
        ];
    }

    private function getRedoublement3Config(): array
    {
        return [
            'class' => Redoublements3::class,
            'placeholder' => 'Sélectionnez le redoublement',
            'choice_label' => 'designation',
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('r3')
                    ->orderBy('r3.designation', 'ASC');
            },
            'attr' => [
                'class' => 'form-control tomselect-redoublement3',
                'data-search-url' => '/redoublements3/search',
                'data-create-url' => '/redoublements3/create',
                'tabindex' => '16',
            ],
            'constraints' => [
                new NotBlank(['message' => 'Le redoublement ne peut pas être vide.']),
            ],
            'required' => true,
            'error_bubbling' => false,
        ];
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Eleves::class,
        ]);
    }
}
