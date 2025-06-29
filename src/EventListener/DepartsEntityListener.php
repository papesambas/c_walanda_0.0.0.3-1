<?php

namespace App\EventListener;

use LogicException;
use App\Entity\Departs;
use Symfony\Bundle\SecurityBundle\Security;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class DepartsEntityListener
{
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
        // Exécuté après l'enregistrement
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
        // Exécuté avant la suppression
    }
    

    private function getDepartSlug(Departs $depart): string
    {
        $slug = mb_strtolower($depart->getMotif() . '' . $depart->getId() , 'UTF-8');
        return $this->slugger->slug($slug)->toString();
    }

}
