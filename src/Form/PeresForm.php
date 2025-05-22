<?php

namespace App\Form;

use App\Entity\Noms;
use App\Entity\Ninas;
use App\Entity\Peres;
use App\Entity\Prenoms;
use App\Entity\Professions;
use App\Entity\Telephones1;
use App\Entity\Telephones2;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PeresForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', EntityType::class, [
                'label' => 'Nom',
                'class' => Noms::class,
                'placeholder' => 'Entrer ou Choisir un Nom',
                'choice_label' => 'designation',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('n')
                        ->orderBy('n.designation', 'ASC');
                },
                'attr' => [
                    'class' => 'select-nomfamille',
                    'allow-clear' => 'true', // Permet de vider la sélection
                ],
                'required' => false,
                'error_bubbling' => false,
            ])
            ->add('prenom', EntityType::class, [
                'class' => Prenoms::class,
                'choice_label' => 'id',
            ])
            ->add('profession', EntityType::class, [
                'class' => Professions::class,
                'choice_label' => 'id',
            ])
            ->add('telephone1', EntityType::class, [
                'class' => Telephones1::class,
                'choice_label' => 'id',
            ])
            ->add('telephone2', EntityType::class, [
                'class' => Telephones2::class,
                'choice_label' => 'id',
            ])
            ->add('nina', EntityType::class, [
                'class' => Ninas::class,
                'choice_label' => 'id',
            ])
            ->add('email')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Peres::class,
        ]);
    }
}
