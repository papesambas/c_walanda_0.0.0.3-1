<?php

namespace App\DataFixtures;

use App\Entity\Classes;
use App\Entity\Etablissements;
use App\Entity\Niveaux;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class ClassesFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        $niveaux = [
            'Préscolaire' => ['Maternelle', 'Petite Section', 'Moyenne Section', 'Grande Section'],
            '1er Cycle' => ['1ère Année', '2ème Année', '3ème Année', '4ème Année', '5ème Année', '6ème Année'],
            '2ème Cycle' => ['7ème Année', '8ème Année', '9ème Année'],
            'Cycle Secondaire' => ['10ème Année', '11ème Année', '12ème Année'],
            'Technique' => ['1ère Année Technique', '2ème Année Technique', '3ème Année Technique', '4ème Année Technique'],
        ];

        $etablissements = ['MAMADOU TRAORE ANNEXE CLASSIQUE 1', 'MAMADOU TRAORE ANNEXE CLASSIQUE 2'];

        foreach ($etablissements as $etabName) {
            $etablissement = $this->getReference('etablissement_' . strtoupper($etabName), Etablissements::class);

            foreach ($niveaux as $cycle => $niveauNames) {
                foreach ($niveauNames as $niveauName) {
                    $niveau = $this->getReference('niveau_' . $niveauName, Niveaux::class);
                    
                    // Création des classes A et B pour chaque niveau dans chaque établissement
                    foreach (['A', 'B'] as $classeLetter) {
                        $designation = $niveauName . ' ' . $classeLetter;
                        $capacite = $faker->numberBetween(35, 40);

                        $classe = new Classes();
                        $classe->setDesignation($designation);
                        $classe->setCapacite($capacite);
                        $classe->setNiveau($niveau);
                        $classe->setEtablissement($etablissement);

                        $manager->persist($classe);
                        $this->addReference('classe_' . $designation . '_' . $etabName, $classe);
                    }
                }
            }
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            NiveauxFixtures::class,
            EtablissementsFixtures::class,
        ];
    }
}