<?php

namespace App\EventListener;

use LogicException;
use App\Entity\Santes;
use Symfony\Bundle\SecurityBundle\Security;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class SantesEntityListener
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

    public function prePersist(Santes $sante, LifecycleEventArgs $arg): void
    {
        $token = $this->tokenStorage->getToken();
        $user = $token ? $token->getUser() : null;

        $sante->setCreatedAt(new \DateTimeImmutable('now'))
            ->setSlug($this->getSanteSlug($sante));

        if ($user) {
            $sante->setCreatedBy($user);
        }
    }

    public function preUpdate(Santes $sante, LifecycleEventArgs $arg): void
    {
        $token = $this->tokenStorage->getToken();
        $user = $token ? $token->getUser() : null;

        $sante->setUpdatedAt(new \DateTimeImmutable('now'))
            ->setSlug($this->getSanteSlug($sante));

        if ($user) {
            $sante->setUpdatedBy($user);
        }
    }

        public function postPersist(Santes $sante, LifecycleEventArgs $args): void
    {
        // Exécuté après l'enregistrement
    }

    public function postUpdate(Santes $sante, LifecycleEventArgs $args): void
    {
        // Exécuté après la mise à jour
    }
    public function postRemove(Santes $sante, LifecycleEventArgs $args): void
    {
        // Exécuté après la suppression
    }
    public function preRemove(Santes $sante, LifecycleEventArgs $args): void
    {
        // Exécuté avant la suppression
    }
    

    private function getSanteSlug(Santes $sante): string
    {
        $slug = mb_strtolower($sante->getMaladie() . '' . $sante->getId() , 'UTF-8');
        return $this->slugger->slug($slug)->toString();
    }

}
