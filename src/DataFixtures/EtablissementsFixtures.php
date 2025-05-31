<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Enseignements;
use App\Entity\Etablissements;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class EtablissementsFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        $formeJuridiques = ['SARL', 'SA', 'Association', 'Coopérative'];

        $enseignements = $manager->getRepository(Enseignements::class)->findAll();

        foreach ($enseignements as $enseignement) {
            $this->createEtablissements($enseignement, $formeJuridiques, $faker, $manager);
        }

        $manager->flush();
    }

    private function createEtablissements(
        Enseignements $enseignement,
        array $formeJuridiques,
        \Faker\Generator $faker,
        ObjectManager $manager
    ): void {
        $designationEnseignement ='Mamadou TRAORE Annexe'.' '. $enseignement->getDesignation();
        $isClassique = ($designationEnseignement === 'Classique');

        if ($isClassique) {
            $this->createEtablissement(
                $enseignement,
                'Ecole privée Mamadou TRAORE',
                $this->getRandomFormeJuridique($formeJuridiques, $faker),
                $faker,
                $manager
            );
        }

        for ($i = 1; $i <= 2; $i++) {
            $designation = $designationEnseignement . ' ' . $i;
            $this->createEtablissement(
                $enseignement,
                $designation,
                $this->getRandomFormeJuridique($formeJuridiques, $faker),
                $faker,
                $manager
            );
        }
    }

    private function createEtablissement(
        Enseignements $enseignement,
        string $designation,
        string $formeJuridique,
        \Faker\Generator $faker,
        ObjectManager $manager
    ): void {
        $capacite = $faker->numberBetween(30, 50);
        $effectif = $faker->numberBetween(20, $capacite);

        $dateOuverture = \DateTimeImmutable::createFromMutable(
            $faker->dateTimeBetween('-5 years', 'now')
        );

        // Génération des numéros de décision avec vérification
        $decisionCreation = $this->generateDecisionNumber();
        //dump($decisionCreation); // Pour débogage, à supprimer en production
        $decisionOuverture = $this->generateDecisionNumber();
        //dump($decisionOuverture); // Pour débogage, à supprimer en production

        $etablissement = new Etablissements();
        $etablissement
            ->setDesignation($designation)
            ->setFormeJuridique($formeJuridique) // Utilisation de la variable
            ->setDecisionCreation($decisionCreation)
            ->setDecisionOuverture($decisionOuverture)
            ->setDateOuverture($dateOuverture)
            ->setAdresse($faker->address())
            ->setTelephone('(223) ' . $faker->numerify('## ### ## ##'))
            ->setEmail($faker->companyEmail)
            ->setEnseignement($enseignement)
            ->setCapacite($capacite)
            ->setEffectif($effectif);

        $manager->persist($etablissement);
    }

    private function getRandomFormeJuridique(array $formeJuridiques, \Faker\Generator $faker): string
    {
        if (empty($formeJuridiques)) {
            return 'Personnelle'; // Valeur par défaut si le tableau est vide
        }
        
        return $faker->randomElement($formeJuridiques);
    }

private function generateDecisionNumber(): string
{
    try {
        $code = strtoupper(bin2hex(random_bytes(2)));
    } catch (\Exception $e) {
        $code = 'XXXX';
    }

    return sprintf(
        'MLI-%d-%s-%s',
        rand(1000, 9999),
        $code,
        date('Y')
    );
}
    public function getDependencies(): array
    {
        return [EnseignementsFixtures::class];
    }
}