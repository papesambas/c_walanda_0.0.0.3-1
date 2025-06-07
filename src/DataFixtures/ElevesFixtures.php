<?php

namespace App\DataFixtures;

use App\Entity\Eleves;
use App\Entity\Classes;
use App\Entity\LieuNaissances;
use App\Entity\Noms;
use App\Entity\Parents;
use App\Entity\Prenoms;
use App\Entity\Statuts;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker\Factory;

class ElevesFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        $statutRefs = [
            'Passant',
            'Redoublant',
            '1ère Inscription',
            'Transfert arrivé',
            'Transfert départ',
            'Exclu',
            'Abandon',
        ];


        $etablissements = ['MAMADOU TRAORE ANNEXE CLASSIQUE 1', 'MAMADOU TRAORE ANNEXE CLASSIQUE 2'];
        $parentCount = 100;

        foreach ($etablissements as $etabName) {

            foreach (['A', 'B'] as $suffixe) {
                foreach ($this->getNiveaux() as $niveau) {
                    $designation = $niveau . ' ' . $suffixe;
                    $classeRef = 'classe_' . $designation . '_' . $etabName;
                    $statuts = [];
                    foreach ($statutRefs as $statutName) {
                        $statuts[] = $this->getReference('statut_' . $statutName, Statuts::class);
                    }

                    /** @var Classes $classe */
                    $classe = $this->getReference($classeRef, Classes::class);

                    // Répartition aléatoire mais équilibrée des sexes
                    $sexes = array_merge(array_fill(0, 17, 'M'), array_fill(0, 13, 'F'));
                    shuffle($sexes);

                    for ($i = 0; $i < 30; $i++) {
                        if ($i < 17) {
                            $eleve = new Eleves();
                            $idx = random_int(0, 60);
                            $key = 'nom_' . $idx;

                            $prenomRef = $this->getReference('prenom_m_' . random_int(0, 24), Prenoms::class);
                            $nomRef = $this->getReference($key, Noms::class);
                            $lieuRef = 'lieu_' . random_int(1, 50);
                            $parentRef = 'parent_' . random_int(0, $parentCount - 1);

                            /** @var string $prenom */
                            $prenom = $this->getReference('prenom_m_' . random_int(0, 24), Prenoms::class);

                            /** @var string $nom */
                            $nom = $this->getReference($key, Noms::class);

                            $sexe = $sexes[$i];
                            $fullname = $prenom . ' ' . $nom;

                            $dateNaissance = \DateTimeImmutable::createFromMutable($faker->dateTimeBetween('-15 years', '-5 years'));

                            $eleve->setPrenom($this->getReference('prenom_m_' . random_int(0, 24), Prenoms::class));
                            $eleve->setNom($this->getReference($key, Noms::class));
                            $eleve->setSexe($sexe);
                            $eleve->setEmail($faker->email);
                            $eleve->setDateNaissance($dateNaissance);
                            $eleve->setDateActe($dateNaissance->modify('+1 month'));
                            $eleve->setNumeroActe($faker->regexify('[A-Z0-9]{4}-[0-9]{3}'));
                            $eleve->setLieuNaissance($this->getReference($lieuRef, LieuNaissances::class));
                            $eleve->setClasse($classe);
                            $eleve->setEtablissement($classe->getEtablissement());
                            $eleve->setParent($this->getReference($parentRef, Parents::class));
                            $eleve->setStatut($faker->randomElement($statuts));
                            $manager->persist($eleve);
                        } else {
                            $eleve = new Eleves();
                            $idx = random_int(0, 60);
                            $key = 'nom_' . $idx;

                            $prenomRef = $this->getReference('prenom_f_' . random_int(0, 24), Prenoms::class);
                            $nomRef = $this->getReference($key, Noms::class);
                            $lieuRef = 'lieu_' . random_int(1, 50);
                            $parentRef = 'parent_' . random_int(0, $parentCount - 1);

                            /** @var string $prenom */
                            $prenom = $this->getReference('prenom_f_' . random_int(0, 24), Prenoms::class);

                            /** @var string $nom */
                            $nom = $this->getReference($key, Noms::class);

                            $sexe = $sexes[$i];
                            $fullname = $prenom . ' ' . $nom;

                            $dateNaissance = \DateTimeImmutable::createFromMutable($faker->dateTimeBetween('-15 years', '-5 years'));

                            $eleve->setPrenom($this->getReference('prenom_f_' . random_int(0, 24), Prenoms::class));
                            $eleve->setNom($this->getReference($key, Noms::class));
                            $eleve->setSexe($sexe);
                            $eleve->setEmail($faker->email);
                            $eleve->setDateNaissance($dateNaissance);
                            $eleve->setDateActe($dateNaissance->modify('+1 month'));
                            $eleve->setNumeroActe($faker->regexify('[A-Z0-9]{4}-[0-9]{3}'));
                            $eleve->setLieuNaissance($this->getReference($lieuRef, LieuNaissances::class));
                            $eleve->setClasse($classe);
                            $eleve->setEtablissement($classe->getEtablissement());
                            $eleve->setParent($this->getReference($parentRef, Parents::class));
                            $eleve->setStatut($faker->randomElement($statuts));
                            $manager->persist($eleve);
                        }
                    }
                }
            }
        }

        $manager->flush();
    }

    private function getNiveaux(): array
    {
        return [
            'Maternelle',
            'Petite Section',
            'Moyenne Section',
            'Grande Section',
            '1ère Année',
            '2ème Année',
            '3ème Année',
            '4ème Année',
            '5ème Année',
            '6ème Année',
            '7ème Année',
            '8ème Année',
            '9ème Année',
            '10ème Année',
            '11ème Année',
            '12ème Année',
            '1ère Année Technique',
            '2ème Année Technique',
            '3ème Année Technique',
            '4ème Année Technique'
        ];
    }

    public function getDependencies(): array
    {
        return [
            ClassesFixtures::class,
            ParentsFixtures::class,
            NomsFixtures::class,
            PrenomsFixtures::class,
            LieuNaissancesFixtures::class,
        ];
    }
}
