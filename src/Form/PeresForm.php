<?php

namespace App\Form;

use App\Entity\Ninas;
use App\Entity\Noms;
use App\Entity\Peres;
use App\Entity\Prenoms;
use App\Entity\Professions;
use App\Entity\Telephones1;
use App\Entity\Telephones2;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PeresForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email')
            ->add('nom', EntityType::class, [
                'class' => Noms::class,
                'choice_label' => 'id',
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
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Peres::class,
        ]);
    }
}
