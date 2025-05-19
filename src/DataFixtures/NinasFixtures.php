<?php

namespace App\DataFixtures;

use App\Entity\Ninas;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class NinasFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $ninasExistants = [];

        for ($i = 1; $i <= 200; $i++) {
            $nina = new Ninas();

            // Génère un numéro unique et conforme
            do {
                $numero = $this->generateValidNina();
            } while (in_array($numero, $ninasExistants));

            $ninasExistants[] = $numero;
            $nina->setNumero($numero);

            $manager->persist($nina);
            $this->addReference('nina_' . $i, $nina);
        }

        $manager->flush();
    }

    private function generateValidNina(): string
    {
        do {
            // Générer la partie principale (13 caractères)
            $mainPart = '';
            $letterCount = 0;
            $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';

            for ($i = 0; $i < 13; $i++) {
                $char = $chars[random_int(0, strlen($chars) - 1)];
                $mainPart .= $char;

                // Compter les lettres
                if (ctype_alpha($char)) {
                    $letterCount++;
                }
            }

            // Vérifier qu'on a au maximum 5 lettres
        } while ($letterCount > 5);

        // Ajouter la lettre finale
        $lastLetter = chr(random_int(65, 90)); // A-Z

        return $mainPart . ' ' . $lastLetter;
    }

    public function getDependencies(): array
    {
        return [
            NomsFixtures::class,
            PrenomsFixtures::class,
            ProfessionsFixtures::class,
            Telephones1Fixtures::class,
            Telephones2Fixtures::class
        ];
    }
}
