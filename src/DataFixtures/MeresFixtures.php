<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Noms;
use App\Entity\Ninas;
use App\Entity\Meres;
use App\Entity\Prenoms;
use App\Entity\Professions;
use App\Entity\Telephones1;
use App\Entity\Telephones2;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class MeresFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        // Suivi des références utilisées pour respecter les relations OneToOne
        $usedTelephones1 = [];
        $usedTelephones2 = [];
        $usedNinas = [];

        for ($i = 1; $i <= 60; $i++) {
            $mere = new Meres();

            // Prénom masculin (références prenom_m_0 à prenom_m_24)
            $prenomRef = $this->getReference('prenom_f_' . random_int(0, 24), Prenoms::class);
            $mere->setPrenom($prenomRef);

            // Nom aléatoire (références nom_1 à nom_50)
            $nomRef = $this->getReference('nom_' . random_int(1, 50), Noms::class);
            $mere->setNom($nomRef);

            // Profession aléatoire (références profession_1 à profession_75)
            $professionRef = $this->getReference('profession_' . random_int(1, 75), Professions::class);
            $mere->setProfession($professionRef);
            $mere->setEmail($faker->email);

            // Téléphone 1 obligatoire (unique)
            do {
                $tel1Index = random_int(101, 250);
            } while (in_array($tel1Index, $usedTelephones1));
            $usedTelephones1[] = $tel1Index;
            $telephone1Ref = $this->getReference('telephone_' . $tel1Index, Telephones1::class);
            $mere->setTelephone1($telephone1Ref);

            // Téléphone 2 optionnel (50% de chance et unique)
            if ($faker->boolean(50)) {
                // Limite à 50 références dans Telephones2
                if (count($usedTelephones2) < 50) {
                    do {
                        $tel2Index = random_int(51, 100);
                    } while (in_array($tel2Index, $usedTelephones2));
                    $usedTelephones2[] = $tel2Index;
                    $telephone2Ref = $this->getReference('telephone_' . $tel2Index, Telephones2::class);
                    $mere->setTelephone2($telephone2Ref);
                }
            }

            // Nina optionnel (30% de chance et unique)
            if ($faker->boolean(30)) {
                // Limite à 60 références dans Ninas
                if (count($usedNinas) < 60) {
                    do {
                        $ninaIndex = random_int(61, 200);
                    } while (in_array($ninaIndex, $usedNinas));
                    $usedNinas[] = $ninaIndex;
                    $ninaRef = $this->getReference('nina_' . $ninaIndex, Ninas::class);
                    $mere->setNina($ninaRef);
                }
            }

            // Email optionnel (60% de chance)
            if ($faker->boolean(60)) {
                $mere->setEmail($faker->email);
            }

            $manager->persist($mere);
            $this->addReference('mere_' . $i, $mere);
        }


        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            NomsFixtures::class,
            PrenomsFixtures::class,
            ProfessionsFixtures::class,
            Telephones1Fixtures::class,
            Telephones2Fixtures::class,
            NinasFixtures::class,
            PeresFixtures::class
        ];
    }
}
