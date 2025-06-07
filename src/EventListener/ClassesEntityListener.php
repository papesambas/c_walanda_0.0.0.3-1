<?php

namespace App\EventListener;

use LogicException;
use App\Entity\Classes;
use Symfony\Bundle\SecurityBundle\Security;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class ClassesEntityListener
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

    public function prePersist(Classes $classe, LifecycleEventArgs $arg): void
    {
        $token = $this->tokenStorage->getToken();
        $user = $token ? $token->getUser() : null;

        $classe->setCreatedAt(new \DateTimeImmutable('now'))
            ->setSlug($this->getClasseSlug($classe));

        if ($user) {
            $classe->setCreatedBy($user);
        }
    }

    public function preUpdate(Classes $classe, LifecycleEventArgs $arg): void
    {
        $token = $this->tokenStorage->getToken();
        $user = $token ? $token->getUser() : null;

        $classe->setUpdatedAt(new \DateTimeImmutable('now'))
            ->setSlug($this->getClasseSlug($classe));

        if ($user) {
            $classe->setUpdatedBy($user);
        }
    }

        public function postPersist(Classes $classe, LifecycleEventArgs $args): void
    {
        // Exécuté après l'enregistrement
    }

    public function postUpdate(Classes $classe, LifecycleEventArgs $args): void
    {
        // Exécuté après la mise à jour
    }
    public function postRemove(Classes $classe, LifecycleEventArgs $args): void
    {
        // Exécuté après la suppression
    }
    public function preRemove(Classes $classe, LifecycleEventArgs $args): void
    {
        // Exécuté avant la suppression
    }
    

    private function getClasseSlug(Classes $classe): string
    {
        $slug = mb_strtolower($classe->getDesignation() . '' . $classe->getId() , 'UTF-8');
        return $this->slugger->slug($slug)->toString();
    }

}
