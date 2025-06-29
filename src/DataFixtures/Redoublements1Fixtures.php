<?php

namespace App\DataFixtures;

use App\Entity\Cycles;
use Faker\Factory;
use App\Entity\Niveaux;
use App\Entity\Redoublements1;
use App\Entity\Scolarites1;
use App\Entity\Scolarites2;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class Redoublements1Fixtures extends Fixture implements DependentFixtureInterface, FixtureGroupInterface
{
    public static function getGroups(): array
    {
        return ['scolarites1'];
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        $data = [
            'Préscolaire'     => [
                'Maternelle'       => [
                    '0' => ['0'=> ['0 Redoub']]
                ],
                'Petite Section'   => [
                    '0' => ['0'=> ['0 Redoub']]
                ],
                'Moyenne Section'  => [
                    '0' => ['0'=> ['0 Redoub']]
                ],
                'Grande Section'   => [
                    '0' => ['0'=> ['0 Redoub']]
                ],
            ],
            '1er Cycle'       => [
                '1ère Année'       => [
                    '1' => ['0'=> ['0 Redoub']],
                    '2' => ['0'=> ['1ère Année']],
                    '3' => ['0'=> ['1ère Année']]
                ],
                '2ème Année'       => [
                    '2' => ['0'=> ['0 Redoub']],
                    '3' => ['0'=> ['1ère Année', '2ème Année']],
                    '4' => ['0'=> ['1ère Année', '2ème Année']],
                    '5' => ['0'=> ['1ère Année', '2ème Année']]
                ],
                '3ème Année'       => [
                    '3' => ['0'=> ['0 Redoub']],
                    '4' => ['0'=> ['1ère Année', '2ème Année', '3ème Année']],
                    '5' => ['0'=> ['1ère Année', '2ème Année', '3ème Année']],
                    '6' => ['0'=> ['1ère Année', '2ème Année', '3ème Année']]
                ],
                '4ème Année'       => [
                    '4' => ['0'=> ['0 Redoub']],
                    '5' => ['0'=> ['1ère Année', '2ème Année', '3ème Année', '4ème Année']],
                    '6' => ['0'=> ['1ère Année', '2ème Année', '3ème Année', '4ème Année']],
                    '7' => ['0'=> ['1ère Année', '2ème Année', '3ème Année', '4ème Année']]
                ],
                '5ème Année'       => [
                    '5' => ['0'=> ['0 Redoub']],
                    '6' => ['0'=> ['1ère Année', '2ème Année', '3ème Année', '4ème Année','5ème Année']],
                    '7' => ['0'=> ['1ère Année', '2ème Année', '3ème Année', '4ème Année','5ème Année']],
                    '8' => ['0'=> ['1ère Année', '2ème Année', '3ème Année', '4ème Année','5ème Année']]
                ],
                '6ème Année'       => [
                    '6' => ['0'=> ['0 Redoub']],
                    '7' => ['0'=> ['1ère Année', '2ème Année', '3ème Année', '4ème Année','5ème Année', '6ème Année']],
                    '8' => ['0'=> ['1ère Année', '2ème Année', '3ème Année', '4ème Année','5ème Année', '6ème Année']],
                    '9' => ['0'=> ['1ère Année', '2ème Année', '3ème Année', '4ème Année','5ème Année', '6ème Année']]
                ],
            ],
            '2ème Cycle'      => [
                '7ème Année'       => [
                    '6' => [
                        ['1'=> ['0 Redoub']],
                        ['2'=> ['7ème Année']],
                        ['3'=> ['7ème Année']]
                    ],
                    '7' => [
                        ['1'=> ['1ère Année', '2ème Année', '3ème Année', '4ème Année','5ème Année', '6ème Année']],
                        ['2'=> ['1ère Année', '2ème Année', '3ème Année', '4ème Année','5ème Année', '6ème Année']],
                        ['3'=> ['1ère Année', '2ème Année', '3ème Année', '4ème Année','5ème Année', '6ème Année']]
                    ],
                    '8' => [
                        ['1'=> ['1ère Année', '2ème Année', '3ème Année', '4ème Année','5ème Année', '6ème Année']],
                        ['2'=> ['1ère Année', '2ème Année', '3ème Année', '4ème Année','5ème Année', '6ème Année']]
                    ]
                ],
                '8ème Année'       => [
                    '6' => [
                        ['2'=> ['0 Redoub']],
                        ['3'=> ['7ème Année', '8ème Année']],
                        ['4'=> ['7ème Année', '8ème Année']],
                        ['5'=> ['7ème Année', '8ème Année']]
                    ],
                    '7' => [
                        ['2'=> ['1ère Année', '2ème Année', '3ème Année', '4ème Année','5ème Année', '6ème Année']],
                        ['3'=> ['1ère Année', '2ème Année', '3ème Année', '4ème Année','5ème Année', '6ème Année']],
                        ['4'=> ['1ère Année', '2ème Année', '3ème Année', '4ème Année','5ème Année', '6ème Année']]
                    ],
                    '8' => [
                        ['2'=> ['1ère Année', '2ème Année', '3ème Année', '4ème Année','5ème Année', '6ème Année']],
                        ['3'=> ['1ère Année', '2ème Année', '3ème Année', '4ème Année','5ème Année', '6ème Année']]
                    ]
                ],
                '9ème Année'       => [
                    '6' => [
                        ['3'=> ['0 Redoub']],
                        ['4'=> ['7ème Année', '8ème Année', '9ème Année']],
                        ['5'=> ['7ème Année', '8ème Année', '9ème Année']],
                        ['6'=> ['7ème Année', '8ème Année', '9ème Année']]
                    ],
                    '7' => [
                        ['3'=> ['1ère Année', '2ème Année', '3ème Année', '4ème Année','5ème Année', '6ème Année']],
                        ['4'=> ['1ère Année', '2ème Année', '3ème Année', '4ème Année','5ème Année', '6ème Année']],
                        ['5'=> ['1ère Année', '2ème Année', '3ème Année', '4ème Année','5ème Année', '6ème Année']]
                    ],
                    '8' => [
                        ['3'=> ['1ère Année', '2ème Année', '3ème Année', '4ème Année','5ème Année', '6ème Année']],
                        ['4'=> ['1ère Année', '2ème Année', '3ème Année', '4ème Année','5ème Année', '6ème Année']]
                    ]
                ],
            ]
        ];

        foreach ($data as $cycleName => $niveaux) {
            $cycle = $this->getReference('cycle_' . $cycleName, Cycles::class);
            foreach ($niveaux as $niveauName => $scolarite1s) {
                $niveau = $this->getReference('niveau_' . $niveauName, Niveaux::class);

                foreach ($scolarite1s as $scolarite1name => $scolarite2s) {
                    $scolarite1 = $this->getReference('scolarite1_' . $niveauName . '_' . $scolarite1name, Scolarites1::class);

                    $firstValue = reset($scolarite2s);
                    if (is_array($firstValue) && count($firstValue) > 0 && is_string($firstValue[0] ?? null)) {
                        // Traitement pour le 1er cycle
                        foreach ($scolarite2s as $scolarite2name => $redoublement1s) {
                            $scolarite2 = $this->getReference('scolarite2_' . $niveauName . '_' . $scolarite1name . '_' . $scolarite2name, Scolarites2::class);
                            
                            foreach ($redoublement1s as $redoublement1) {
                                $redoub1 = new Redoublements1();
                                $redoub1
                                    ->setCycle($cycle)
                                    ->setNiveau($niveau)
                                    ->setScolarite1($scolarite1)
                                    ->setScolarite2($scolarite2)
                                    ->setDesignation($redoublement1)
                                ;
                                $manager->persist($redoub1);
                                $this->addReference('redoublement1_' . $niveauName . '_' . $scolarite1name . '_' . $scolarite2name . '_' . $redoublement1,  $redoub1);

                            }
                        }
                    } else {
                        // Traitement pour le 2ème cycle
                        foreach ($scolarite2s as $item) {
                            foreach ($item as $scolarite2name => $redoublement1s) {
                                $scolarite2 = $this->getReference('scolarite2_' . $niveauName . '_' . $scolarite1name . '_' . $scolarite2name, Scolarites2::class);
                                
                                foreach ($redoublement1s as $redoublement1) {
                                $redoub1 = new Redoublements1();
                                $redoub1
                                    ->setCycle($cycle)
                                    ->setNiveau($niveau)
                                    ->setScolarite1($scolarite1)
                                    ->setScolarite2($scolarite2)
                                    ->setDesignation($redoublement1)
                                ;
                                $manager->persist($redoub1);
                                $this->addReference('redoublement1_' . $niveauName . '_' . $scolarite1name . '_' . $scolarite2name . '_' . $redoublement1,  $redoub1);

                                }
                            }
                        }
                    }
                }
            }
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            Scolarites2Fixtures::class,
        ];
    }
}




