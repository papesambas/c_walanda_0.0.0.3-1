<?php

namespace App\DataFixtures;

use App\Entity\Cycles;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class CyclesFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $cycles = [
            'Préscolaire',
            '1er Cycle',
            '2ème Cycle',
        ];

        foreach ($cycles as $cycleName) {
            $cycle = new Cycles();
            $cycle->setDesignation($cycleName);
            $manager->persist($cycle);

            // Ajouter une référence pour les cercles
            $this->addReference('cycle_' . $cycleName, $cycle);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            EnseignementsFixtures::class,
            // Ajouter ici les fixtures dont cette fixture dépend, si nécessaire
        ];
    }

}
