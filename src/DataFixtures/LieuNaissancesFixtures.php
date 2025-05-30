<?php

namespace App\DataFixtures;

use App\Entity\Communes;
use App\Entity\LieuNaissances;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker;

class LieuNaissancesFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create('fr_FR');
        for ($i = 1; $i <= 300; $i++) {
            $lieu = new LieuNaissances();
            $commune = $this->getReference('commune_' . $faker->numberBetween(1, 175), Communes::class);
            $lieu->setCommune($commune);
            $lieu->setDesignation($faker->unique()->city());
            $manager->persist($lieu);
            $this->addReference('lieu_' . $i, $lieu);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            CommunesFixtures::class,
        ];
    }
}