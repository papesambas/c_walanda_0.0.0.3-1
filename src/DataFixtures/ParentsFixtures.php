<?php

namespace App\DataFixtures;

use App\Entity\Meres;
use App\Entity\Peres;
use App\Entity\Parents;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class ParentsFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = \Faker\Factory::create('fr_FR');

        for ($i=0; $i <100 ; $i++) { 
            $parent = new Parents();
            $parent->setPere($this->getReference('pere_' . random_int(1, 60),Peres::class));
            $parent->setMere($this->getReference('mere_' . random_int(1, 60),Meres::class));
            $manager->persist($parent);
            $this->addReference('parent_' . $i, $parent);
        }

        // $product = new Product();
        // $manager->persist($product);

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
            PeresFixtures::class,
            MeresFixtures::class
        ];
    }
}
