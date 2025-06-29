<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Cycles;
use App\Entity\Niveaux;
use App\Entity\Scolarites1;
use App\Entity\Scolarites2;
use App\Entity\Redoublements1;
use App\Entity\Redoublements2;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class Redoublements2Fixtures extends Fixture implements DependentFixtureInterface, FixtureGroupInterface
{
    public static function getGroups(): array
    {
        return ['scolarites1'];
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        $data = $this->getStructureData();

        foreach ($data as $cycleName => $niveaux) {
            $cycle = $this->getReference('cycle_' . $cycleName, Cycles::class);

            foreach ($niveaux as $niveauName => $scolarite1s) {
                $niveau = $this->getReference('niveau_' . $niveauName, Niveaux::class);

                foreach ($scolarite1s as $scolarite1name => $scolarite2s) {
                    $scolarite1 = $this->getReference('scolarite1_' . $niveauName . '_' . $scolarite1name, Scolarites1::class);

                    foreach ($scolarite2s as $scolarite2name => $redoublement1s) {
                        $scolarite2 = $this->getReference('scolarite2_' . $niveauName . '_' . $scolarite1name . '_' . $scolarite2name, Scolarites2::class);

                        foreach ($redoublement1s as $redoublement1name => $redoublement2s) {
                            $redoublement1 = $this->getReference('redoublement1_' . $niveauName . '_' . $scolarite1name . '_' . $scolarite2name . '_' . $redoublement1name, Redoublements1::class);

                            foreach ($redoublement2s as $redoublement2) {
                                $this->createRedoublement2(
                                    $manager,
                                    $cycle,
                                    $niveau,
                                    $scolarite1,
                                    $scolarite2,
                                    $redoublement1,
                                    $redoublement2
                                );
                            }
                        }
                    }
                }
            }
        }

        $manager->flush();
    }

    private function createRedoublement2(
        ObjectManager $manager,
        Cycles $cycle,
        Niveaux $niveau,
        Scolarites1 $scolarite1,
        Scolarites2 $scolarite2,
        Redoublements1 $redoublement1,
        string $designation
    ): void {
        $entity = new Redoublements2();
        $entity
            ->setCycle($cycle)
            ->setNiveau($niveau)
            ->setScolarite1($scolarite1)
            ->setScolarite2($scolarite2)
            ->setRedoublement1($redoublement1)
            ->setDesignation($designation);

        $manager->persist($entity);

        $refName = sprintf(
            'redoublement2_%s_%s_%s_%s_%s',
            $niveau->getDesignation(),
            $scolarite1->getScolarite(),
            $scolarite2->getScolarite(),
            $redoublement1->getDesignation(),
            $designation
        );
        $this->addReference($refName, $entity);
    }

    private function getStructureData(): array
    {
        return [
            'Préscolaire' => [
                'Maternelle' => [
                    '0' => [
                        '0' => [
                            '0 Redoub' => ['0 Redoub']
                        ]
                    ]
                ],
                'Petite Section' => [
                    '0' => [
                        '0' => [
                            '0 Redoub' => ['0 Redoub']
                        ]
                    ]
                ],
                'Moyenne Section' => [
                    '0' => [
                        '0' => [
                            '0 Redoub' => ['0 Redoub']
                        ]
                    ]
                ],
                'Grande Section' => [
                    '0' => [
                        '0' => [
                            '0 Redoub' => ['0 Redoub']
                        ]
                    ]
                ],
            ],
            '1er Cycle' => [
                '1ère Année' => [
                    '1' => [
                        '0' => [
                            '0 Redoub' => ['0 Redoub']
                        ]
                    ],
                    '2' => [
                        '0' => [
                            '1ère Année' => ['0 Redoub']
                        ]
                    ],
                    '3' => [
                        '0' => [
                            '1ère Année' => ['1ère Année']
                        ]
                    ]
                ],
                '2ème Année' => [
                    '2' => [
                        '0' => [
                            '0 Redoub' => ['0 Redoub']
                        ]
                    ],
                    '3' => [
                        '0' => [
                            '1ère Année' => ['0 Redoub'],
                            '2ème Année' => ['0 Redoub']
                        ]
                    ],
                    '4' => [
                        '0' => [
                            '1ère Année' => ['2ème Année'],
                            '2ème Année' => ['2ème Année']
                        ]
                    ],
                    '5' => [
                        '0' => [
                            '1ère Année' => ['2ème Année'],
                            '2ème Année' => ['2ème Année']
                        ]
                    ]
                ],
                '3ème Année' => [
                    '3' => [
                        '0' => [
                            '0 Redoub' => ['0 Redoub']
                        ]
                    ],
                    '4' => [
                        '0' => [
                            '1ère Année' => ['0 Redoub'],
                            '2ème Année' => ['0 Redoub'],
                            '3ème Année' => ['0 Redoub']
                        ]
                    ],
                    '5' => [
                        '0' => [
                            '1ère Année' => ['2ème Année', '3ème Année'],
                            '2ème Année' => ['3ème Année'],
                            '3ème Année' => ['3ème Année']
                        ]
                    ],
                    '6' => [
                        '0' => [
                            '1ère Année' => ['2ème Année', '3ème Année'],
                            '2ème Année' => ['3ème Année'],
                            '3ème Année' => ['3ème Année']
                        ]
                    ]
                ],
                '4ème Année' => [
                    '4' => [
                        '0' => [
                            '0 Redoub' => ['0 Redoub']
                        ]
                    ],
                    '5' => [
                        '0' => [
                            '1ère Année' => ['0 Redoub'],
                            '2ème Année' => ['0 Redoub'],
                            '3ème Année' => ['0 Redoub'],
                            '4ème Année' => ['0 Redoub']
                        ]
                    ],
                    '6' => [
                        '0' => [
                            '1ère Année' => ['2ème Année', '3ème Année', '4ème Année'],
                            '2ème Année' => ['3ème Année', '4ème Année'],
                            '3ème Année' => ['4ème Année'],
                            '4ème Année' => ['4ème Année']
                        ]
                    ],
                    '7' => [
                        '0' => [
                            '1ère Année' => ['2ème Année', '3ème Année', '4ème Année'],
                            '2ème Année' => ['3ème Année', '4ème Année'],
                            '3ème Année' => ['4ème Année'],
                            '4ème Année' => ['4ème Année']
                        ]
                    ]
                ],
                '5ème Année' => [
                    '5' => [
                        '0' => [
                            '0 Redoub' => ['0 Redoub']
                        ]
                    ],
                    '6' => [
                        '0' => [
                            '1ère Année' => ['0 Redoub'],
                            '2ème Année' => ['0 Redoub'],
                            '3ème Année' => ['0 Redoub'],
                            '4ème Année' => ['0 Redoub'],
                            '5ème Année' => ['0 Redoub']
                        ]
                    ],
                    '7' => [
                        '0' => [
                            '1ère Année' => ['2ème Année', '3ème Année', '4ème Année', '5ème Année'],
                            '2ème Année' => ['3ème Année', '4ème Année', '5ème Année'],
                            '3ème Année' => ['4ème Année', '5ème Année'],
                            '4ème Année' => ['5ème Année'],
                            '5ème Année' => ['5ème Année']
                        ]
                    ],
                    '8' => [
                        '0' => [
                            '1ère Année' => ['2ème Année', '3ème Année', '4ème Année', '5ème Année'],
                            '2ème Année' => ['3ème Année', '4ème Année', '5ème Année'],
                            '3ème Année' => ['4ème Année', '5ème Année'],
                            '4ème Année' => ['5ème Année'],
                            '5ème Année' => ['5ème Année']
                        ]
                    ]
                ],
                '6ème Année' => [
                    '6' => [
                        '0' => [
                            '0 Redoub' => ['0 Redoub']
                        ]
                    ],
                    '7' => [
                        '0' => [
                            '1ère Année' => ['0 Redoub'],
                            '2ème Année' => ['0 Redoub'],
                            '3ème Année' => ['0 Redoub'],
                            '4ème Année' => ['0 Redoub'],
                            '5ème Année' => ['0 Redoub'],
                            '6ème Année' => ['0 Redoub']
                        ]
                    ],
                    '8' => [
                        '0' => [
                            '1ère Année' => ['2ème Année', '3ème Année', '4ème Année', '5ème Année', '6ème Année'],
                            '2ème Année' => ['3ème Année', '4ème Année', '5ème Année', '6ème Année'],
                            '3ème Année' => ['4ème Année', '5ème Année', '6ème Année'],
                            '4ème Année' => ['5ème Année', '6ème Année'],
                            '5ème Année' => ['6ème Année'],
                            '6ème Année' => ['6ème Année']
                        ]
                    ],
                    '9' => [
                        '0' => [
                            '1ère Année' => ['2ème Année', '3ème Année', '4ème Année', '5ème Année', '6ème Année'],
                            '2ème Année' => ['3ème Année', '4ème Année', '5ème Année', '6ème Année'],
                            '3ème Année' => ['4ème Année', '5ème Année', '6ème Année'],
                            '4ème Année' => ['5ème Année', '6ème Année'],
                            '5ème Année' => ['6ème Année'],
                            '6ème Année' => ['6ème Année']
                        ]
                    ]
                ],
            ],
            '2ème Cycle' => [
                '7ème Année' => [
                    '6' => [
                        '1' => [
                            '0 Redoub' => ['0 Redoub']
                        ],
                        '2' => [
                            '7ème Année' => ['0 Redoub']
                        ],
                        '3' => [
                            '7ème Année' => ['7ème Année']
                        ]
                    ],
                    '7' => [
                        '1' => [
                            '1ère Année' => ['0 Redoub'],
                            '2ème Année' => ['0 Redoub'],
                            '3ème Année' => ['0 Redoub'],
                            '4ème Année' => ['0 Redoub'],
                            '5ème Année' => ['0 Redoub'],
                            '6ème Année' => ['0 Redoub']
                        ],
                        '2' => [
                            '1ère Année' => ['7ème Année'],
                            '2ème Année' => ['7ème Année'],
                            '3ème Année' => ['7ème Année'],
                            '4ème Année' => ['7ème Année'],
                            '5ème Année' => ['7ème Année'],
                            '6ème Année' => ['7ème Année']
                        ],
                        '3' => [
                            '1ère Année' => ['7ème Année'],
                            '2ème Année' => ['7ème Année'],
                            '3ème Année' => ['7ème Année'],
                            '4ème Année' => ['7ème Année'],
                            '5ème Année' => ['7ème Année'],
                            '6ème Année' => ['7ème Année']
                        ]
                    ],
                    '8' => [
                        '1' => [
                            '1ère Année' => ['2ème Année', '3ème Année', '4ème Année', '5ème Année', '6ème Année'],
                            '2ème Année' => ['3ème Année', '4ème Année', '5ème Année', '6ème Année'],
                            '3ème Année' => ['4ème Année', '5ème Année', '6ème Année'],
                            '4ème Année' => ['5ème Année', '6ème Année'],
                            '5ème Année' => ['6ème Année'],
                            '6ème Année' => ['6ème Année']
                        ],
                        '2' => [
                            '1ère Année' => ['2ème Année', '3ème Année', '4ème Année', '5ème Année', '6ème Année'],
                            '2ème Année' => ['3ème Année', '4ème Année', '5ème Année', '6ème Année'],
                            '3ème Année' => ['4ème Année', '5ème Année', '6ème Année'],
                            '4ème Année' => ['5ème Année', '6ème Année'],
                            '5ème Année' => ['6ème Année'],
                            '6ème Année' => ['6ème Année']
                        ]
                    ]
                ],
                '8ème Année' => [
                    '6' => [
                        '2' => [
                            '0 Redoub' => ['0 Redoub']
                        ],
                        '3' => [
                            '7ème Année' => ['0 Redoub'],
                            '8ème Année' => ['0 Redoub']
                        ],
                        '4' => [
                            '7ème Année' => ['8ème Année'],
                            '8ème Année' => ['8ème Année']
                        ],
                        '5' => [
                            '7ème Année' => ['8ème Année'],
                            '8ème Année' => ['8ème Année']
                        ]
                    ],
                    '7' => [
                        '2' => [
                            '1ère Année' => ['0 Redoub'],
                            '2ème Année' => ['0 Redoub'],
                            '3ème Année' => ['0 Redoub'],
                            '4ème Année' => ['0 Redoub'],
                            '5ème Année' => ['0 Redoub'],
                            '6ème Année' => ['0 Redoub']
                        ],
                        '3' => [
                            '1ère Année' => ['7ème Année', '8ème Année'],
                            '2ème Année' => ['7ème Année', '8ème Année'],
                            '3ème Année' => ['7ème Année', '8ème Année'],
                            '4ème Année' => ['7ème Année', '8ème Année'],
                            '5ème Année' => ['7ème Année', '8ème Année'],
                            '6ème Année' => ['7ème Année', '8ème Année']
                        ],
                        '4' => [
                            '1ère Année' => ['7ème Année', '8ème Année'],
                            '2ème Année' => ['7ème Année', '8ème Année'],
                            '3ème Année' => ['7ème Année', '8ème Année'],
                            '4ème Année' => ['7ème Année', '8ème Année'],
                            '5ème Année' => ['7ème Année', '8ème Année'],
                            '6ème Année' => ['7ème Année', '8ème Année']
                        ]
                    ],
                    '8' => [
                        '2' => [
                            '1ère Année' => ['2ème Année', '3ème Année', '4ème Année', '5ème Année', '6ème Année'],
                            '2ème Année' => ['3ème Année', '4ème Année', '5ème Année', '6ème Année'],
                            '3ème Année' => ['4ème Année', '5ème Année', '6ème Année'],
                            '4ème Année' => ['5ème Année', '6ème Année'],
                            '5ème Année' => ['6ème Année'],
                            '6ème Année' => ['6ème Année']
                        ],
                        '3' => [
                            '1ère Année' => ['2ème Année', '3ème Année', '4ème Année', '5ème Année', '6ème Année'],
                            '2ème Année' => ['3ème Année', '4ème Année', '5ème Année', '6ème Année'],
                            '3ème Année' => ['4ème Année', '5ème Année', '6ème Année'],
                            '4ème Année' => ['5ème Année', '6ème Année'],
                            '5ème Année' => ['6ème Année'],
                            '6ème Année' => ['6ème Année']
                        ]
                    ]
                ],
                '9ème Année' => [
                    '6' => [
                        '3' => [
                            '0 Redoub' => ['0 Redoub']
                        ],
                        '4' => [
                            '7ème Année' => ['0 Redoub'],
                            '8ème Année' => ['0 Redoub'],
                            '9ème Année' => ['0 Redoub']
                        ],
                        '5' => [
                            '7ème Année' => ['8ème Année', '9ème Année'],
                            '8ème Année' => ['9ème Année'],
                            '9ème Année' => ['9ème Année']
                        ],
                        '6' => [
                            '7ème Année' => ['8ème Année', '9ème Année'],
                            '8ème Année' => ['9ème Année'],
                            '9ème Année' => ['9ème Année']
                        ]
                    ],
                    '7' => [
                        '3' => [
                            '1ère Année' => ['0 Redoub'],
                            '2ème Année' => ['0 Redoub'],
                            '3ème Année' => ['0 Redoub'],
                            '4ème Année' => ['0 Redoub'],
                            '5ème Année' => ['0 Redoub'],
                            '6ème Année' => ['0 Redoub']
                        ],
                        '4' => [
                            '1ère Année' => ['7ème Année', '8ème Année', '9ème Année'],
                            '2ème Année' => ['7ème Année', '8ème Année', '9ème Année'],
                            '3ème Année' => ['7ème Année', '8ème Année', '9ème Année'],
                            '4ème Année' => ['7ème Année', '8ème Année', '9ème Année'],
                            '5ème Année' => ['7ème Année', '8ème Année', '9ème Année'],
                            '6ème Année' => ['7ème Année', '8ème Année', '9ème Année']
                        ],
                        '5' => [
                            '1ère Année' => ['7ème Année', '8ème Année', '9ème Année'],
                            '2ème Année' => ['7ème Année', '8ème Année', '9ème Année'],
                            '3ème Année' => ['7ème Année', '8ème Année', '9ème Année'],
                            '4ème Année' => ['7ème Année', '8ème Année', '9ème Année'],
                            '5ème Année' => ['7ème Année', '8ème Année', '9ème Année'],
                            '6ème Année' => ['7ème Année', '8ème Année', '9ème Année']
                        ]
                    ],
                    '8' => [
                        '3' => [
                            '1ère Année' => ['2ème Année', '3ème Année', '4ème Année', '5ème Année', '6ème Année'],
                            '2ème Année' => ['3ème Année', '4ème Année', '5ème Année', '6ème Année'],
                            '3ème Année' => ['4ème Année', '5ème Année', '6ème Année'],
                            '4ème Année' => ['5ème Année', '6ème Année'],
                            '5ème Année' => ['6ème Année'],
                            '6ème Année' => ['6ème Année']
                        ],
                        '4' => [
                            '1ère Année' => ['2ème Année', '3ème Année', '4ème Année', '5ème Année', '6ème Année'],
                            '2ème Année' => ['3ème Année', '4ème Année', '5ème Année', '6ème Année'],
                            '3ème Année' => ['4ème Année', '5ème Année', '6ème Année'],
                            '4ème Année' => ['5ème Année', '6ème Année'],
                            '5ème Année' => ['6ème Année'],
                            '6ème Année' => ['6ème Année']
                        ]
                    ]
                ],
            ]
        ];
    }

    public function getDependencies(): array
    {
        return [
            Redoublements1Fixtures::class,
        ];
    }
}
