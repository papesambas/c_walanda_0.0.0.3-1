<?php

namespace App\DataFixtures;

use App\Entity\Statuts;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class StatutsFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $statuts = [
            'Passant',
            'Redoublant',
            '1ère Inscription',
            'Transfert arrivé',
            'Transfert départ',
            'Exclu',
            'Abandon',
            'Passe au C.E.P.',
            'Passe au C.E.F.',
            'Passe au BAC',
            'En attente',
        ];

        foreach ($statuts as $statutName) {
            $statut = new Statuts();
            $statut->setDesignation($statutName);
            $manager->persist($statut);

            // Ajouter une référence pour les cercles
            $this->addReference('statut_' . $statutName, $statut);
        }

        $manager->flush();
    }

        public function getDependencies(): array
    {
        return [
            EnseignementsFixtures::class,
        ];
    }

}
