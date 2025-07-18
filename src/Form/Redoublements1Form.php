<?php

namespace App\Form;

use App\Entity\Cycles;
use App\Entity\Niveaux;
use App\Entity\Redoublements1;
use App\Entity\Scolarites1;
use App\Entity\Scolarites2;
use App\Entity\Users;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Redoublements1Form extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('designation')
            ->add('createdAt', null, [
                'widget' => 'single_text',
            ])
            ->add('updatedAt', null, [
                'widget' => 'single_text',
            ])
            ->add('slug')
            ->add('cycle', EntityType::class, [
                'class' => Cycles::class,
                'choice_label' => 'id',
            ])
            ->add('niveau', EntityType::class, [
                'class' => Niveaux::class,
                'choice_label' => 'id',
            ])
            ->add('scolarite1', EntityType::class, [
                'class' => Scolarites1::class,
                'choice_label' => 'id',
            ])
            ->add('scolarite2', EntityType::class, [
                'class' => Scolarites2::class,
                'choice_label' => 'id',
            ])
            ->add('createdBy', EntityType::class, [
                'class' => Users::class,
                'choice_label' => 'id',
            ])
            ->add('updatedBy', EntityType::class, [
                'class' => Users::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Redoublements1::class,
        ]);
    }
}
