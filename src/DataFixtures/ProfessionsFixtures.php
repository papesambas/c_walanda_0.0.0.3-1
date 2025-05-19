<?php

namespace App\DataFixtures;

use App\Entity\Professions;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class ProfessionsFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $designations = $this->generateUniqueProfessions(75);

        $i = 1;
        foreach ($designations as $designation) {
            $profession = new Professions();
            $profession->setDesignation($designation);
            $manager->persist($profession);
            $this->addReference('profession_' . $i, $profession);
            $i++;
        }

        $manager->flush();
    }

    private function generateUniqueProfessions(int $count): array
    {
        $baseDesignations = [
            'Médecin', 'Ingénieur', 'Enseignant', 'Agriculteur', 'Comptable', 'Chauffeur', 'Policier', 'Gendarme',
            'Pharmacien', 'Boucher', 'Pâtissier', 'Cuisinier', 'Menuisier', 'Électricien', 'Plombier', 'Coiffeur',
            'Vétérinaire', 'Mécanicien', 'Technicien', 'Facteur', 'Employé', 'Informaticien', 'Commerçant',
            'Vendeur', 'Journaliste', 'Écrivain', 'Professeur', 'Instituteur', 'Secrétaire', 'Directeur', 'Architecte',
            'Maçon', 'Peintre', 'Photographe', 'Artisan', 'Tailleur', 'Musicien', 'Chanteur', 'Danseur', 'Avocat',
            'Juge', 'Notaire', 'Banquier', 'Caissier', 'Agent immobilier', 'Infirmier', 'Sage-femme', 'Dentiste',
            'Cordonnier', 'Zoologiste', 'Scientifique', 'Chercheur', 'Sociologue', 'Psychologue', 'Traducteur',
            'Conseiller', 'Boulanger', 'Éleveur', 'Fermier', 'Charpentier', 'Gardien', 'Douanier', 'Manœuvre',
            'Livreur', 'Ébéniste', 'Bijoutier', 'Orfèvre', 'Tapissier', 'Horloger', 'Esthéticienne', 'Téléconseiller',
            'Ambassadeur', 'Bibliothécaire', 'Archiviste', 'Diplomate'
        ];

        shuffle($baseDesignations);
        return array_slice($baseDesignations, 0, $count);
    }

    public function getDependencies(): array
    {
        return [
            NomsFixtures::class,
            PrenomsFixtures::class
        ];
    }
}
