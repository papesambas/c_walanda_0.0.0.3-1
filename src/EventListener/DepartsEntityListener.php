<?php

namespace App\EventListener;

use LogicException;
use App\Entity\Departs;
use App\Entity\RecupHistDeparts;
use Doctrine\ORM\Event\PostFlushEventArgs;
use Symfony\Bundle\SecurityBundle\Security;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class DepartsEntityListener
{
    private array $newHist = [];
    private $security;
    private $slugger;
    private $tokenStorage;

    public function __construct(Security $security, SluggerInterface $slugger, TokenStorageInterface $tokenStorage)
    {
        $this->security = $security;
        $this->slugger = $slugger;
        $this->tokenStorage = $tokenStorage;
    }

    public function prePersist(Departs $depart, LifecycleEventArgs $arg): void
    {
        $token = $this->tokenStorage->getToken();
        $user = $token ? $token->getUser() : null;

        $depart->setCreatedAt(new \DateTimeImmutable('now'))
            ->setDateDepart(new \DateTimeImmutable('now'))
            ->setSlug($this->getDepartSlug($depart));

        if ($user) {
            $depart->setCreatedBy($user);
        }
    }

    public function preUpdate(Departs $depart, LifecycleEventArgs $arg): void
    {
        $token = $this->tokenStorage->getToken();
        $user = $token ? $token->getUser() : null;

        $depart->setUpdatedAt(new \DateTimeImmutable('now'))
            ->setSlug($this->getDepartSlug($depart));

        if ($user) {
            $depart->setUpdatedBy($user);
        }
    }

    public function postPersist(Departs $depart, LifecycleEventArgs $args): void
    {
        $entityManager = $args->getObjectManager(); // Récupération de l'EntityManager

        $dateDepart = $depart->getDateDepart();
        $motif = $depart->getMotif();
        $ecole = $depart->getEcoleDestinataire();
        $eleve = $depart->getEleve();
        $hist = new RecupHistDeparts();
        $hist->setDateDepart($dateDepart);
        $hist->setMotif($motif);
        $hist->setEcoleDestinataire($ecole);

        // Important: clonez l'élève pour éviter les problèmes de référence
        $hist->setEleve($eleve);

        $this->newHist[] = $hist;
        // Exécuté après l'enregistrement
    }

        public function postFlush(PostFlushEventArgs $args): void
    {
        if (empty($this->newHist)) {
            return;
        }

        $em = $args->getObjectManager();

        foreach ($this->newHist as $user) {
            $em->persist($user);
        }

        $this->newHist = []; // on vide pour éviter les doublons

        $em->flush(); // ✅ On peut faire le flush ici sans effet de bord
    }


    public function postUpdate(Departs $depart, LifecycleEventArgs $args): void
    {
        // Exécuté après la mise à jour
    }
    public function postRemove(Departs $depart, LifecycleEventArgs $args): void
    {
        // Exécuté après la suppression
    }
    public function preRemove(Departs $depart, LifecycleEventArgs $args): void
    {
    }

    private function getDepartSlug(Departs $depart): string
    {
        $slug = mb_strtolower($depart->getMotif() . '' . $depart->getId(), 'UTF-8');
        return $this->slugger->slug($slug)->toString();
    }
}
