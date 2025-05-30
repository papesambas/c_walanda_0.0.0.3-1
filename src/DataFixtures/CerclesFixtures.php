<?php

namespace App\DataFixtures;

use App\Entity\Cercles;
use App\Entity\Regions;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class CerclesFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $cercles = [
            'District de Bamako' => ['district'],
            'Région de Kayes' => [
                'Cercle de Kayes',
                'Cercle de Bafoulabé',
                'Cercle de Yélimané',
                'Cercle de Kéniéba',
                'Cercle de Ambidébi',
                'Cercle de Aourou',
                'Cercle de Diamou',
                'Cercle de Oussoubidiagna',
                'Cercle de Ségala',
                'Cercle de Sadiola'
            ],
            'Région de Koulikoro' => [
                'Cercle de Koulikoro',
                'Cercle de Banamba',
                'Cercle de Kangaba',
                'Cercle de Kati',
                'Cercle de Kolokani',
                'Cercle de Nyamina',
                'Cercle de Siby',
                'Cercle de Néguéla'
            ],
            'Région de Sikasso' => [
                'Cercle de Sikasso',
                'Cercle de Kadiolo',
                'Cercle de Dandérésso',
                'Cercle de Kignan',
                'Cercle de Kléla',
                'Cercle de Lobougoula',
                'Cercle de Loulouni',
                'Cercle de Niéma'
            ],
            'Région de Ségou' => [
                'Cercle de Ségou',
                'Cercle de Bla',
                'Cercle de Barouéli',
                'Cercle de Niono',
                'Cercle de Macina',
                'Cercle de Dioro',
                'Cercle de Farako',
                'Cercle de Nampala',
                'Cercle de Sokolo',
                'Cercle de Markala',
                'Cercle de Sarro'
            ],
            'Région de Mopti' => [
                'Cercle de Mopti',
                'Cercle de Djenné',
                'Cercle de Ténenkou',
                'Cercle de Youwarou',
                'Cercle de Konna',
                'Cercle de Korientzé',
                'Cercle de Sofara',
                'Cercle de Toguéré-Coumbé'
            ],
            'Région de Tombouctou' => [
                'Cercle de Tombouctou',
                'Cercle de Goundam',
                'Cercle de Diré',
                'Cercle de Niafunké',
                'Cercle de Gourma-Rharous',
                'Cercle de Bintagoungou',
                'Cercle de Saraféré',
                'Cercle de Bambara-Maoudé',
                'Cercle de Léré',
                'Cercle de Gossi',
                'Cercle de Tonka',
                'Cercle de Ber',
                'Cercle de Gargando'
            ],
            'Région de Gao' => [
                'Cercle de Gao',
                'Cercle de Bourem',
                'Cercle d\'Ansongo',
                'Cercle de Almoustrat',
                'Cercle de Bamba',
                'Cercle de Ouattagouna',
                'Cercle de Soni Aliber',
                'Cercle de Djebock',
                'Cercle de Talataye',
                'Cercle de Tessit',
                'Cercle de N\'Tillit',
                'Cercle de Gabéro',
                'Cercle de Ersane',
                'Cercle de Tabankort',
                'Cercle de Tin-Aouker',
                'Cercle de Kassambéré'
            ],
            'Région de Kidal' => [
                'Cercle de Kidal',
                'Cercle d\'Abeïbara',
                'Cercle de Tin-Essako',
                'Cercle de Tessalit',
                'Cercle de Achibogho',
                'Cercle de Anétif',
                'Cercle de Timétrine',
                'Cercle de Aguel-Hoc',
                'Cercle de Takalote'
            ],
            'Région de Taoudenni' => [
                'Cercle de Taoudenni',
                'Cercle de Araouane',
                'Cercle de Foum-Elba',
                'Cercle de Boû-Djebeha',
                'Cercle de Al-Ourche',
                'Cercle de Achouratt'
            ],
            'Région de Ménaka' => [
                'Cercle de Ménaka',
                'Cercle de Tidermène',
                'Cercle de Inékar',
                'Cercle de Andéramboukane',
                'Cercle de Anouzagrène',
                'Cercle de Inlamawane (Fanfi)'
            ],
            'Région de Nioro' => [
                'Cercle de Nioro',
                'Cercle de Diéma',
                'Cercle de Diangounté',
                'Cercle de Sandaré',
                'Cercle de Troungoumbé',
                'Cercle de Béma'
            ],
            'Région de Kita' => [
                'Cercle de Kita',
                'Cercle de Sagabari',
                'Cercle de Sébékoro',
                'Cercle de Toukoto',
                'Cercle de Séféto',
                'Cercle de Sirakoro'
            ],
            'Région de Dioïla' => [
                'Cercle de Dioïla',
                'Cercle de Banco',
                'Cercle de Béléko',
                'Cercle de Fana',
                'Cercle de Massigui',
                'Cercle de Ména'
            ],
            'Région de Nara' => [
                'Cercle de Nara',
                'Cercle de Ballé',
                'Cercle de Dilly',
                'Cercle de Mourdiah',
                'Cercle de Guiré',
                'Cercle de Fallou'
            ],
            'Région de Bougouni' => [
                'Cercle de Bougouni',
                'Cercle de Yanfolila',
                'Cercle de Kolondiéba',
                'Cercle de Garalo',
                'Cercle de Koumantou',
                'Cercle de Sélingué',
                'Cercle de Ouélessébougou',
                'Cercle de Kadiala',
                'Cercle de Fakola',
                'Cercle de Dogo'
            ],
            'Région de Koutiala' => [
                'Cercle de Koutiala',
                'Cercle de Yorosso',
                'Cercle de M\'Péssoba',
                'Cercle de Molobala',
                'Cercle de Koury',
                'Cercle de Konséguéla',
                'Cercle de Kouniana',
                'Cercle de Zangasso'
            ],
            'Région de San' => [
                'Cercle de San',
                'Cercle de Tominian',
                'Cercle de Kimparana',
                'Cercle de Yangasso',
                'Cercle de Fangasso',
                'Cercle de Mandiakuy',
                'Cercle de Sy'
            ],
            'Région de Douentza' => [
                'Cercle de Douentza',
                'Cercle de Boré',
                'Cercle de Hombori',
                'Cercle de N\'Gouma',
                'Cercle de Mondoro',
                'Cercle de Boni'
            ],
            'Région de Bandiagara' => [
                'Cercle de Bandiagara',
                'Cercle de Koro',
                'Cercle de Bankass',
                'Cercle de Kendié',
                'Cercle de Ningari',
                'Cercle de Dialassagou',
                'Cercle de Sangha',
                'Cercle de Kani',
                'Cercle de Sokoura'
            ],
            // Ajoutez les autres régions et leurs cercles ici...
        ];

        foreach ($cercles as $regionName => $cercleNames) {
            $region = $this->getReference('region_' . $regionName, Regions::class);

            foreach ($cercleNames as $cercleName) {
                $cercle = new Cercles();
                $cercle->setDesignation($cercleName);
                $cercle->setRegion($region);
                $manager->persist($cercle);

                // Ajouter une référence pour les communes
                $this->addReference('cercle_' . $cercleName, $cercle);
            }
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [RegionsFixtures::class];
    }
}
