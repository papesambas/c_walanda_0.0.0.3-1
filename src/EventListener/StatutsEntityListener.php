<?php

namespace App\EventListener;

use LogicException;
use App\Entity\Statuts;
use Symfony\Bundle\SecurityBundle\Security;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class StatutsEntityListener
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

    public function prePersist(Statuts $statut, LifecycleEventArgs $arg): void
    {
        $token = $this->tokenStorage->getToken();
        $user = $token ? $token->getUser() : null;

        $statut->setCreatedAt(new \DateTimeImmutable('now'))
            ->setSlug($this->getStatutSlug($statut));

        if ($user) {
            $statut->setCreatedBy($user);
        }
    }

    public function preUpdate(Statuts $statut, LifecycleEventArgs $arg): void
    {
        $token = $this->tokenStorage->getToken();
        $user = $token ? $token->getUser() : null;

        $statut->setUpdatedAt(new \DateTimeImmutable('now'))
            ->setSlug($this->getStatutSlug($statut));

        if ($user) {
            $statut->setUpdatedBy($user);
        }
    }

        public function postPersist(Statuts $statut, LifecycleEventArgs $args): void
    {
        // Exécuté après l'enregistrement
    }

    public function postUpdate(Statuts $statut, LifecycleEventArgs $args): void
    {
        // Exécuté après la mise à jour
    }
    public function postRemove(Statuts $statut, LifecycleEventArgs $args): void
    {
        // Exécuté après la suppression
    }
    public function preRemove(Statuts $statut, LifecycleEventArgs $args): void
    {
        // Exécuté avant la suppression
    }
    

    private function getStatutSlug(Statuts $statut): string
    {
        $slug = mb_strtolower($statut->getDesignation() . '' . $statut->getId() , 'UTF-8');
        return $this->slugger->slug($slug)->toString();
    }

}
