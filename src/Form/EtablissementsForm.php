<?php

namespace App\Form;

use App\Entity\Enseignements;
use App\Entity\Etablissements;
use App\Entity\Users;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EtablissementsForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('designation')
            ->add('formeJuridique')
            ->add('decisionCreation')
            ->add('decisionOuverture')
            ->add('dateOuverture', null, [
                'widget' => 'single_text',
            ])
            ->add('numeroSocial')
            ->add('numeroFiscal')
            ->add('compteBancaire')
            ->add('adresse')
            ->add('telephone')
            ->add('telephoneMobile')
            ->add('email')
            ->add('capacite')
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
            ])
            ->add('superUsers', EntityType::class, [
                'class' => Users::class,
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
            'data_class' => Etablissements::class,
        ]);
    }
}
