<?php

namespace App\DataFixtures;

use App\Entity\Niveaux;
use App\Entity\Cycles;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class NiveauxFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $niveaux = [
            'Préscolaire' => [
                'Maternelle',
                'Petite Section',
                'Moyenne Section',
                'Grande Section'
            ],
            '1er Cycle' => [
                '1ère Année',
                '2ème Année',
                '3ème Année',
                '4ème Année',
                '5ème Année',
                '6ème Année'
            ],
            '2ème Cycle' => [
                '7ème Année',
                '8ème Année',
                '9ème Année',
            ],
            'Cycle Secondaire' => [
                '10ème Année',
                '11ème Année',
                '12ème Année',
            ],
            'Technique' => [
                '1ère Année Technique',
                '2ème Année Technique',
                '3ème Année Technique',
                '4ème Année Technique'
            ],
            
            // Ajoutez les autres régions et leurs niveaux ici...
        ];

        foreach ($niveaux as $cycleName => $niveauNames) {
            $cycle = $this->getReference('cycle_' . $cycleName, Cycles::class);

            foreach ($niveauNames as $niveauName) {
                $niveau = new Niveaux();
                $niveau->setDesignation($niveauName);
                $niveau->setCycle($cycle);
                $manager->persist($niveau);

                // Ajouter une référence pour les communes
                $this->addReference('niveau_' . $niveauName, $niveau);
            }
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [CyclesFixtures::class];
    }
}
