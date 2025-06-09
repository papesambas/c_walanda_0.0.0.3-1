<?php

namespace App\Form\DataForm;

use App\Entity\Users;
use App\Entity\Classes;
use App\Entity\Statuts;
use App\Data\SearchData;
use App\Entity\Professions;
use Psr\Log\LoggerInterface;
use App\Data\SearchElevesData;
use App\Entity\EcoleProvenances;
use Doctrine\ORM\EntityRepository;
use App\Repository\ClassesRepository;
use App\Repository\StatutsRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Bundle\SecurityBundle\Security;
use App\Repository\EcoleProvenancesRepository;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class SearchElevesDataType extends AbstractType
{
    public function __construct(private Security $security, private LoggerInterface $logger)
    {
        $this->security = $security;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $user = $user = $this->security->getUser();
        if ($user instanceof Users) {
            $etablissement = $user->getEtablissement();
        }
        $builder
            // Champ de recherche générale (q)
            ->add('q', TextType::class, [
                'label' => false,
                'required' => false,
                'attr' => [
                    'placeholder' => 'Nom, prénom...',
                ],
                'constraints' => [
                    new Regex([
                        'pattern' => '/^[a-zA-Z\s]+$/',
                        'message' => 'Ce champ ne doit contenir que des lettres et des espaces.',
                    ]),
                ],
            ])
            ->add('age1', IntegerType::class, [
                'label' => false,
                'required' => false,
                'attr' => [
                    'placeholder' => 'Âge miniimum...',
                ],
            ])
            ->add('age2', IntegerType::class, [
                'label' => false,
                'required' => false,
                'attr' => [
                    'placeholder' => 'Âge maximum...',
                ],
            ])
            // Liste des professions (professions)
            // src/Form/YourFormType.php
            ->add('statut', EntityType::class, [
                'label' => false,
                'class' => Statuts::class,
                'choice_label' => 'designation',
                'query_builder' => fn(EntityRepository $er) => $er->createQueryBuilder('s')
                    ->select('DISTINCT s')  // Ajout du DISTINCT pour garantir l'unicité
                    ->innerJoin('s.eleves', 'e')
                    ->where('s.designation IS NOT NULL')
                    ->andWhere('s.designation != :empty')
                    ->andWhere('e.etablissement = :etablissement')
                    ->setParameter('empty', '')
                    ->setParameter('etablissement', $etablissement)
                    ->groupBy('s.designation')
                    ->orderBy('s.designation', 'ASC'),
                'multiple' => true,
                'expanded' => false,
                'required' => false,
                'attr' => [
                    'class' => 'select-statut',
                    'data-placeholder' => 'Sélectionnez un ou plusieurs statuts...',
                ],
            ])
            // Champ pour le numéro de téléphone (telephone)
            ->add('classe', EntityType::class, [
                'label' => false,
                'class' => Classes::class,
                'choice_label' => 'designation',
                'query_builder' => fn(EntityRepository $er) => $er->createQueryBuilder('c')
                    ->select('DISTINCT c') // Garantit l'unicité des résultat
                    ->innerJoin('c.niveau', 'n')
                    ->innerJoin('n.cycle', 'cy')
                    ->andWhere('cy.etablissement = :etablissement')
                    ->setParameter('etablissement', $etablissement)
                    ->orderBy('c.id', 'ASC'),
                'multiple' => true,
                'expanded' => false,
                'required' => false,
                'attr' => [
                    'class' => 'select-classe',
                    'data-placeholder' => 'Sélectionnez une ou plusieurs classes...',
                ],
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SearchElevesData::class, // Associe ce formulaire à la classe SearchData
            'method' => 'GET', // Utilise la méthode GET pour permettre de partager l'URL des résultats
            'csrf_protection' => false, // Désactive la protection CSRF pour les formulaires de recherche
        ]);
    }

    public function getBlockPrefix()
    {
        return ''; // Retourne une chaîne vide pour éviter de préfixer les noms des champs
    }
}
