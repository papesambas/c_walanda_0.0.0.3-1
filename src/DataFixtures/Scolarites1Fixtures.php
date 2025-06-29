<?php
// src/DataFixtures/Scolarites1Fixtures.php

namespace App\DataFixtures;

use App\Entity\Scolarites1;
use App\Entity\Niveaux;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Faker\Factory;

class Scolarites1Fixtures extends Fixture implements DependentFixtureInterface, FixtureGroupInterface
{
        public static function getGroups(): array
    {
        return ['scolarites1'];
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        // Votre tableau initial (cycle => [ niveau => [ codes scolarite ] ])
        $data = [
            'Préscolaire'     => [
                'Maternelle'       => ['0'],
                'Petite Section'   => ['0'],
                'Moyenne Section'  => ['0'],
                'Grande Section'   => ['0'],
            ],
            '1er Cycle'       => [
                '1ère Année'       => ['1', '2', '3'],
                '2ème Année'       => ['2', '3', '4', '5'],
                '3ème Année'       => ['3', '4', '5', '6'],
                '4ème Année'       => ['4', '5', '6', '7'],
                '5ème Année'       => ['5', '6', '7', '8'],
                '6ème Année'       => ['6', '7', '8', '9'],
            ],
            '2ème Cycle'      => [
                '7ème Année'       => ['6', '7', '8'],
                '8ème Année'       => ['6', '7', '8'],
                '9ème Année'       => ['6', '7', '8'],
            ]
        ];

        foreach ($data as $cycleName => $niveaux) {
            foreach ($niveaux as $niveauName => $codes) {
                // Récupération de l'entité Niveaux
                /** @var Niveaux $niveau */
                $niveau = $this->getReference('niveau_' . $niveauName, Niveaux::class);

                // Pour chaque code, on crée une entrée Scolarites1
                foreach ($codes as $code) {
                    $sco1 = new Scolarites1();
                    $sco1
                        ->setNiveau($niveau)
                        ->setScolarite((int) $code)
                    ;
                    $manager->persist($sco1);
                    $this->addReference('scolarite1_' . $niveauName . '_' . $code, $sco1);
                }
            }
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            NiveauxFixtures::class,
        ];
    }
}
