<?php

namespace App\Form;

use App\Entity\Cycles;
use App\Entity\Enseignements;
use App\Entity\Users;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CyclesForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('designation')
            ->add('effectif')
            ->add('createdAt', null, [
                'widget' => 'single_text',
            ])
            ->add('updatedAt', null, [
                'widget' => 'single_text',
            ])
            ->add('slug')
            ->add('enseignement', EntityType::class, [
                'class' => Enseignements::class,
                'choice_label' => 'id',
                'multiple' => true,
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
            'data_class' => Cycles::class,
        ]);
    }
}
