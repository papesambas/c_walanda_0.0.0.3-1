<?php

namespace App\EventListener;

use LogicException;
use App\Entity\Prenoms;
use Symfony\Bundle\SecurityBundle\Security;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class PrenomsEntityListener
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

    public function prePersist(Prenoms $prenom, LifecycleEventArgs $arg): void
    {
        $token = $this->tokenStorage->getToken();
        $user = $token ? $token->getUser() : null;

        $prenom->setCreatedAt(new \DateTimeImmutable('now'))
            ->setSlug($this->getPrenomSlug($prenom));

        if ($user) {
            $prenom->setCreatedBy($user);
        }
    }

    public function preUpdate(Prenoms $prenom, LifecycleEventArgs $arg): void
    {
        $token = $this->tokenStorage->getToken();
        $user = $token ? $token->getUser() : null;

        $prenom->setUpdatedAt(new \DateTimeImmutable('now'))
            ->setSlug($this->getPrenomSlug($prenom));

        if ($user) {
            $prenom->setUpdatedBy($user);
        }
    }

        public function postPersist(Prenoms $prenom, LifecycleEventArgs $args): void
    {
        // Exécuté après l'enregistrement
    }

    public function postUpdate(Prenoms $prenom, LifecycleEventArgs $args): void
    {
        // Exécuté après la mise à jour
    }
    public function postRemove(Prenoms $prenom, LifecycleEventArgs $args): void
    {
        // Exécuté après la suppression
    }
    public function preRemove(Prenoms $prenom, LifecycleEventArgs $args): void
    {
        // Exécuté avant la suppression
    }
    

    private function getPrenomSlug(Prenoms $prenom): string
    {
        $slug = mb_strtolower($prenom->getDesignation() . '' . $prenom->getId() , 'UTF-8');
        return $this->slugger->slug($slug)->toString();
    }
}
