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
                ['Maternelle', 2, 3],
                ['Petite Section', 3, 4],
                ['Moyenne Section', 4, 5],
                ['Grande Section', 5, 6]
            ],
            '1er Cycle' => [
                ['1ère Année', 5, 9],
                ['2ème Année', 6, 10],
                ['3ème Année', 7, 11],
                ['4ème Année', 8, 12],
                ['5ème Année', 9, 13],
                ['6ème Année', 10, 14]
            ],
            '2ème Cycle' => [
                ['7ème Année', 11, 16],
                ['8ème Année', 12, 17],
                ['9ème Année', 13, 25],
            ]

            // Ajoutez les autres régions et leurs niveaux ici...
        ];

        foreach ($niveaux as $cycleName => $niveauDatas) {
            $cycle = $this->getReference('cycle_' . $cycleName, Cycles::class);

            foreach ($niveauDatas as [$niveauName, $ageMin, $ageMax]) {
                $niveau = new Niveaux();
                $niveau->setDesignation($niveauName)
                    ->setCycle($cycle)
                    ->setAgeMin($ageMin)
                    ->setAgeMax($ageMax);

                $manager->persist($niveau);
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
