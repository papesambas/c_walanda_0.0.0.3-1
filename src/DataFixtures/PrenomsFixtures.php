<?php

namespace App\DataFixtures;

use App\Entity\Prenoms;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class PrenomsFixtures extends Fixture implements DependentFixtureInterface
{
    private const PRENOMS_MASCULINS = [
        'Jean', 'Pierre', 'Paul', 'Jacques', 'Michel', 
        'Philippe', 'André', 'Alain', 'Nicolas', 'Christophe',
        'Patrick', 'Daniel', 'Éric', 'Stéphane', 'David',
        'Thomas', 'François', 'Olivier', 'Sébastien', 'Laurent',
        'Mathieu', 'Jérôme', 'Vincent', 'Antoine', 'Guillaume'
    ];

    private const PRENOMS_FEMININS = [
        'Marie', 'Anne', 'Isabelle', 'Sophie', 'Nathalie',
        'Valérie', 'Catherine', 'Christine', 'Sandrine', 'Élodie',
        'Julie', 'Caroline', 'Stéphanie', 'Virginie', 'Aurélie',
        'Céline', 'Émilie', 'Delphine', 'Laurence', 'Chantal',
        'Béatrice', 'Patricia', 'Monique', 'Sylvie', 'Élisabeth'
    ];

    private const PARTICULES = ['-', ' ', "'"];

    public function load(ObjectManager $manager): void
    {
        $usedDesignations = [];

        // Prénoms masculins
        foreach (self::PRENOMS_MASCULINS as $key => $prenomBase) {
            do {
                $designation = $this->genererPrenom($prenomBase, self::PRENOMS_MASCULINS);
            } while (in_array($designation, $usedDesignations, true));

            $usedDesignations[] = $designation;

            $prenom = new Prenoms();
            $prenom->setDesignation($designation);
            $manager->persist($prenom);
            $this->addReference('prenom_m_' . $key, $prenom);
        }

        // Prénoms féminins
        foreach (self::PRENOMS_FEMININS as $key => $prenomBase) {
            do {
                $designation = $this->genererPrenom($prenomBase, self::PRENOMS_FEMININS);
            } while (in_array($designation, $usedDesignations, true));

            $usedDesignations[] = $designation;

            $prenom = new Prenoms();
            $prenom->setDesignation($designation);
            $manager->persist($prenom);
            $this->addReference('prenom_f_' . $key, $prenom);
        }

        $manager->flush();
    }

    private function genererPrenom(string $base, array $liste): string
    {
        // 20% de chance de générer un prénom composé
        if (mt_rand(1, 5) === 1) {
            $particule = self::PARTICULES[array_rand(self::PARTICULES)];
            $secondPrenom = $liste[array_rand($liste)];

            // Éviter les répétitions (ex: Jean-Jean)
            while ($secondPrenom === $base) {
                $secondPrenom = $liste[array_rand($liste)];
            }

            return $this->formaterPrenom($base . $particule . $secondPrenom);
        }

        return $this->formaterPrenom($base);
    }

    private function formaterPrenom(string $prenom): string
    {
        $prenom = trim($prenom);
        $prenom = mb_strtolower($prenom, 'UTF-8');

        // Gestion des majuscules après particules
        $parts = preg_split('/([-\' ])/u', $prenom, -1, PREG_SPLIT_DELIM_CAPTURE);
        $result = '';

        foreach ($parts as $part) {
            if (in_array($part, ['-', "'", ' '])) {
                $result .= $part;
            } else {
                $result .= mb_strtoupper(mb_substr($part, 0, 1), 'UTF-8') . mb_substr($part, 1);
            }
        }

        return $result;
    }

    public function getDependencies(): array
    {
        return [
            NomsFixtures::class
        ];
    }
}
