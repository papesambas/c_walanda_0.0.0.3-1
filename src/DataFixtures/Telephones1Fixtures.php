<?php

namespace App\DataFixtures;

use App\Entity\Telephones1;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class Telephones1Fixtures extends Fixture implements DependentFixtureInterface
{
public function load(ObjectManager $manager): void
{
    $phoneNumbers = $this->generateValidPhoneNumbers(300);

    $i = 1; // compteur manuel
    foreach ($phoneNumbers as $number) {
        $telephone = new Telephones1();
        $telephone->setNumero($number);
        $manager->persist($telephone);
        $this->addReference('telephone_' . $i, $telephone);
        $i++;
    }

    $manager->flush();
}

    private function generateValidPhoneNumbers(int $count): array
    {
        $prefixes = ['+223', '00223'];
        $startDigits = ['2', '5', '6', '7'];
        $uniqueNumbers = [];

        while (count($uniqueNumbers) < $count) {
            $prefix = $prefixes[array_rand($prefixes)];
            $start = $startDigits[array_rand($startDigits)];
            $middle = str_pad((string)random_int(0, 9999999), 8, '0', STR_PAD_LEFT);
            $candidate = $prefix . $start . $middle;

            if ($this->isValidPhoneNumber($candidate)) {
                $uniqueNumbers[$candidate] = true;
            }
        }

        return array_keys($uniqueNumbers);
    }

    private function isValidPhoneNumber(string $number): bool
    {
        return preg_match('/^(?:(?:\+223|00223)[2567]\d{8}|(?:\+(?!223)\d{1,3}|00(?!223)\d{1,3})\d{6,12})$/', $number);
    }

    public function getDependencies(): array
    {
        return [
            NomsFixtures::class,
            PrenomsFixtures::class,
            ProfessionsFixtures::class
        ];
    }
}
