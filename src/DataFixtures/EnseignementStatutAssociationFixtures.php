<?php

// src/DataFixtures/EnseignementStatutAssociationFixtures.php

namespace App\DataFixtures;

use App\Entity\Enseignements;
use App\Entity\Statuts;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class EnseignementStatutAssociationFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        // Récupérer tous les enseignements
        $enseignements = [];
        $enseignementsNames = [
            'Classique',
            'Catholique',
            'Arabe',
            'Franco-arabe',
        ];
        
        foreach ($enseignementsNames as $name) {
            $enseignements[] = $this->getReference('enseignement_' . $name, Enseignements::class);
        }

        // Récupérer tous les statuts
        $statuts = [];
        $statutsNames = [
            'Passant', 'Redoublant', '1ère Inscription', 'Transfert arrivé', 
            'Transfert départ', 'Exclu', 'Abandon', 'Passe au C.E.P.', 
            'Passe au C.E.F.', 'Passe au BAC', 'En attente'
        ];
        
        foreach ($statutsNames as $name) {
            $statuts[] = $this->getReference('statut_' . $name, Statuts::class);
        }

        // Associer chaque enseignement à tous les statuts
        foreach ($enseignements as $enseignement) {
            foreach ($statuts as $statut) {
                $enseignement->addStatut($statut);
            }
            $manager->persist($enseignement);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            EnseignementsFixtures::class,
            StatutsFixtures::class,
        ];
    }
}