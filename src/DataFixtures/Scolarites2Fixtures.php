<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Niveaux;
use App\Entity\Scolarites1;
use App\Entity\Scolarites2;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class Scolarites2Fixtures extends Fixture implements DependentFixtureInterface, FixtureGroupInterface
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
                'Maternelle'       => ['0'=> ['0']],
                'Petite Section'   => ['0'=> ['0']],
                'Moyenne Section'  => ['0'=> ['0']],
                'Grande Section'   => ['0'=> ['0']],
            ],
            '1er Cycle'       => [
                '1ère Année'       => ['1'=> ['0'], '2'=> ['0'], '3'=> ['0']],
                '2ème Année'       => ['2'=> ['0'], '3'=> ['0'], '4'=> ['0'], '5'=> ['0']],
                '3ème Année'       => ['3'=> ['0'], '4'=> ['0'], '5'=> ['0'], '6'=> ['0']],
                '4ème Année'       => ['4'=> ['0'], '5'=> ['0'], '6'=> ['0'], '7'=> ['0']],
                '5ème Année'       => ['5'=> ['0'], '6'=> ['0'], '7'=> ['0'], '8'=> ['0']],
                '6ème Année'       => ['6'=> ['0'], '7'=> ['0'], '8'=> ['0'], '9'=> ['0']],
            ],
            '2ème Cycle'      => [
                '7ème Année'       => ['6'=> ['1','2','3'], '7'=> ['1','2','3'], '8'=> ['1','2']],
                '8ème Année'       => ['6'=> ['2','3','4','5'], '7'=> ['2','3','4'], '8'=> ['2','3']],
                '9ème Année'       => ['6'=> ['3','4','5','6'], '7'=> ['3','4','5'], '8'=> ['3','4']],
            ]
        ];

        foreach ($data as $cycleName => $niveaux) {
            foreach ($niveaux as $niveauName => $scolarite1s) {
                // Récupération de l'entité Niveaux
                /** @var Niveaux $niveau */
                $niveau = $this->getReference('niveau_' . $niveauName, Niveaux::class);

                // Pour chaque code, on crée une entrée Scolarites1
                /** @var Scolarites1 $colarite1 */
                foreach ($scolarite1s as $scolarite1name => $scolarite2s) {
                    $scolarite1 = $this->getReference('scolarite1_' . $niveauName . '_' . $scolarite1name, Scolarites1::class);

                    foreach ($scolarite2s as $scolarite2) {
                    $sco2 = new Scolarites2();
                    $sco2
                        ->setNiveau($niveau)
                        ->setScolarite1( $scolarite1)
                        ->setScolarite((int) $scolarite2)
                    ;
                    $manager->persist($sco2);
                    $this->addReference('scolarite2_' . $niveauName. '_' . $scolarite1name . '_' . $scolarite2, $sco2);
                    }
                }
            }
        }

        $manager->flush();

    }

    public function getDependencies(): array
    {
        return [
            Scolarites1Fixtures::class,
        ];
    }
}
