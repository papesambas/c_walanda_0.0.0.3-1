<?php

namespace App\EventListener;

use LogicException;
use App\Entity\Enseignements;
use Symfony\Bundle\SecurityBundle\Security;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class EnseignementsEntityListener
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

    public function prePersist(Enseignements $enseignement, LifecycleEventArgs $arg): void
    {
        $token = $this->tokenStorage->getToken();
        $user = $token ? $token->getUser() : null;

        $enseignement->setCreatedAt(new \DateTimeImmutable('now'))
            ->setSlug($this->getEnseignementSlug($enseignement));

        if ($user) {
            $enseignement->setCreatedBy($user);
        }
    }

    public function preUpdate(Enseignements $enseignement, LifecycleEventArgs $arg): void
    {
        $token = $this->tokenStorage->getToken();
        $user = $token ? $token->getUser() : null;

        $enseignement->setUpdatedAt(new \DateTimeImmutable('now'))
            ->setSlug($this->getEnseignementSlug($enseignement));

        if ($user) {
            $enseignement->setUpdatedBy($user);
        }
    }

        public function postPersist(Enseignements $enseignement, LifecycleEventArgs $args): void
    {
        // Exécuté après l'enregistrement
    }

    public function postUpdate(Enseignements $enseignement, LifecycleEventArgs $args): void
    {
        // Exécuté après la mise à jour
    }
    public function postRemove(Enseignements $enseignement, LifecycleEventArgs $args): void
    {
        // Exécuté après la suppression
    }
    public function preRemove(Enseignements $enseignement, LifecycleEventArgs $args): void
    {
        // Exécuté avant la suppression
    }
    

    private function getEnseignementSlug(Enseignements $enseignement): string
    {
        $slug = mb_strtolower($enseignement->getDesignation() . '' . $enseignement->getId() , 'UTF-8');
        return $this->slugger->slug($slug)->toString();
    }

}
