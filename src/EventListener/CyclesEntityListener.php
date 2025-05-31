<?php

namespace App\EventListener;

use LogicException;
use App\Entity\Cycles;
use Symfony\Bundle\SecurityBundle\Security;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class CyclesEntityListener
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

    public function prePersist(Cycles $cycle, LifecycleEventArgs $arg): void
    {
        $token = $this->tokenStorage->getToken();
        $user = $token ? $token->getUser() : null;

        $cycle->setCreatedAt(new \DateTimeImmutable('now'))
            ->setSlug($this->getCycleSlug($cycle));

        if ($user) {
            $cycle->setCreatedBy($user);
        }
    }

    public function preUpdate(Cycles $cycle, LifecycleEventArgs $arg): void
    {
        $token = $this->tokenStorage->getToken();
        $user = $token ? $token->getUser() : null;

        $cycle->setUpdatedAt(new \DateTimeImmutable('now'))
            ->setSlug($this->getCycleSlug($cycle));

        if ($user) {
            $cycle->setUpdatedBy($user);
        }
    }

        public function postPersist(Cycles $cycle, LifecycleEventArgs $args): void
    {
        // Exécuté après l'enregistrement
    }

    public function postUpdate(Cycles $cycle, LifecycleEventArgs $args): void
    {
        // Exécuté après la mise à jour
    }
    public function postRemove(Cycles $cycle, LifecycleEventArgs $args): void
    {
        // Exécuté après la suppression
    }
    public function preRemove(Cycles $cycle, LifecycleEventArgs $args): void
    {
        // Exécuté avant la suppression
    }
    

    private function getCycleSlug(Cycles $cycle): string
    {
        $slug = mb_strtolower($cycle->getDesignation() . '' . $cycle->getId() , 'UTF-8');
        return $this->slugger->slug($slug)->toString();
    }

}
