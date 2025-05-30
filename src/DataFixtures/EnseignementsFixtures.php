<?php

namespace App\DataFixtures;

use App\Entity\Enseignements;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class EnseignementsFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $enseignements = [
            'Classique',
            'Catholique',
            'Arabe',
            'Franco-arabe',
        ];

        foreach ($enseignements as $enseignementName) {
            $enseignement = new Enseignements();
            $enseignement->setDesignation($enseignementName);
            $manager->persist($enseignement);

            // Ajouter une référence pour les cercles
            $this->addReference('enseignement_' . $enseignementName, $enseignement);
        }

        $manager->flush();
    }

}
