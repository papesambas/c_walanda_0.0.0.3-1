<?php

namespace App\DataFixtures;

use App\Entity\Regions;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class RegionsFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $regions = [
            'District de Bamako',
            'Région de Kayes',
            'Région de Koulikoro',
            'Région de Sikasso',
            'Région de Ségou',
            'Région de Mopti',
            'Région de Tombouctou',
            'Région de Gao',
            'Région de Kidal',
            'Région de Taoudenni',
            'Région de Ménaka',
            'Région de Nioro',
            'Région de Kita',
            'Région de Dioïla',
            'Région de Nara',
            'Région de Bougouni',
            'Région de Koutiala',
            'Région de San',
            'Région de Douentza',
            'Région de Bandiagara',
        ];

        foreach ($regions as $regionName) {
            $region = new Regions();
            $region->setDesignation($regionName);
            $manager->persist($region);

            // Ajouter une référence pour les cercles
            $this->addReference('region_' . $regionName, $region);
        }

        $manager->flush();
    }

        public function getDependencies(): array
    {
        return [Scolarites2Fixtures::class];
    }
}
