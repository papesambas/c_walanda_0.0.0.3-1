<?php

namespace App\EventListener;

use LogicException;
use App\Entity\Niveaux;
use Symfony\Bundle\SecurityBundle\Security;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class NiveauxEntityListener
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

    public function prePersist(Niveaux $niveau, LifecycleEventArgs $arg): void
    {
        $token = $this->tokenStorage->getToken();
        $user = $token ? $token->getUser() : null;

        $niveau->setCreatedAt(new \DateTimeImmutable('now'))
            ->setSlug($this->getNiveauSlug($niveau));

        if ($user) {
            $niveau->setCreatedBy($user);
        }
    }

    public function preUpdate(Niveaux $niveau, LifecycleEventArgs $arg): void
    {
        $token = $this->tokenStorage->getToken();
        $user = $token ? $token->getUser() : null;

        $niveau->setUpdatedAt(new \DateTimeImmutable('now'))
            ->setSlug($this->getNiveauSlug($niveau));

        if ($user) {
            $niveau->setUpdatedBy($user);
        }
    }

        public function postPersist(Niveaux $cycle, LifecycleEventArgs $args): void
    {
        // Exécuté après l'enregistrement
    }

    public function postUpdate(Niveaux $niveau, LifecycleEventArgs $args): void
    {
        // Exécuté après la mise à jour
    }
    public function postRemove(Niveaux $niveau, LifecycleEventArgs $args): void
    {
        // Exécuté après la suppression
    }
    public function preRemove(Niveaux $cycle, LifecycleEventArgs $args): void
    {
        // Exécuté avant la suppression
    }
    

    private function getNiveauSlug(Niveaux $niveau): string
    {
        $slug = mb_strtolower($niveau->getDesignation() . '' . $niveau->getId() , 'UTF-8');
        return $this->slugger->slug($slug)->toString();
    }

}
