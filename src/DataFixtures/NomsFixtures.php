<?php

namespace App\DataFixtures;

use App\Entity\Noms;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class NomsFixtures extends Fixture 
{
    private const VALID_NAMES = [
        'Martin', 'Bernard', 'Dubois', 'Thomas', 'Robert', 'Richard', 'Petit', 
        'Durand', 'Leroy', 'Moreau', 'Simon', 'Laurent', 'Lefebvre', 'Michel',
        'Garcia', 'David', 'Bertrand', 'Roux', 'Vincent', 'Fournier', 'Morel',
        'Girard', 'Andre', 'Lefevre', 'Mercier', 'Dupont', 'Lambert', 'Bonnet',
        'Francois', 'Martinez', 'Legrand', 'Garnier', 'Faure', 'Rousseau',
        'Blanc', 'Guerin', 'Muller', 'Henry', 'Roussel', 'Nicolas', 'Perrot',
        'Morin', 'Mathieu', 'Clement', 'Gauthier', 'Dumont', 'Lopez', 'Fontaine',
        'Chevalier', 'Robin', 'Masson'
    ];

    private const PARTICLES = [' ', '-', "'"];

public function load(ObjectManager $manager): void
{
    $existingDesignations = [];

    for ($i = 1; $i <= 50; $i++) {
        $nom = new Noms();

        do {
            $parts = random_int(1, 2);

            if ($parts === 1) {
                $designation = $this->getRandomName();
            } else {
                $designation = $this->getRandomName() 
                    . $this->getRandomParticle() 
                    . $this->getRandomName();
            }

            // Nettoyage et normalisation de la désignation
            $designation = trim($designation);
            $designation = mb_strtolower($designation, 'UTF-8');
            $designation = mb_strtoupper(mb_substr($designation, 0, 1, 'UTF-8'), 'UTF-8') . mb_substr($designation, 1, null, 'UTF-8');

        } while (in_array($designation, $existingDesignations, true));

        $existingDesignations[] = $designation;

        $nom->setDesignation($designation);
        $manager->persist($nom);
        $this->addReference('nom_'.$i, $nom);
    }

    $manager->flush();
}

    private function getRandomName(): string
    {
        return self::VALID_NAMES[array_rand(self::VALID_NAMES)];
    }

    private function getRandomParticle(): string
    {
        return self::PARTICLES[array_rand(self::PARTICLES)];
    }

}