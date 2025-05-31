<?php

// src/DataFixtures/EnseignementCycleAssociationFixtures.php

namespace App\DataFixtures;

use App\Entity\Enseignements;
use App\Entity\Cycles;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class EnseignementCycleAssociationFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        // Récupérer tous les enseignements
        $enseignements = [];
        $enseignementsNames = [
            'Classique',
            'Catholique',
            'Arabe',
            'Franco-arabe',
        ];
        
        foreach ($enseignementsNames as $name) {
            $enseignements[] = $this->getReference('enseignement_' . $name, Enseignements::class);
        }

        // Récupérer tous les cycles
        $cycles = [];
        $cyclesNames = [
            'Préscolaire',
            '1er Cycle',
            '2ème Cycle',
            'Cycle Secondaire',
            'Technique',
        ];
        
        foreach ($cyclesNames as $name) {
            $cycles[] = $this->getReference('cycle_' . $name, Cycles::class);
        }

        // Associer chaque enseignement à tous les cycles
        foreach ($enseignements as $enseignement) {
            foreach ($cycles as $cycle) {
                $enseignement->addCycle($cycle);
            }
            $manager->persist($enseignement);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            EnseignementsFixtures::class,
            CyclesFixtures::class,
        ];
    }
}