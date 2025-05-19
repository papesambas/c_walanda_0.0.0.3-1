<?php

namespace App\EventListener;

use LogicException;
use App\Entity\Telephones1;
use Symfony\Bundle\SecurityBundle\Security;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class Telephones1EntityListener
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

    public function prePersist(Telephones1 $telephone1, LifecycleEventArgs $arg): void
    {
        $token = $this->tokenStorage->getToken();
        $user = $token ? $token->getUser() : null;

        $telephone1->setCreatedAt(new \DateTimeImmutable('now'))
            ->setSlug($this->getTelephoneSlug($telephone1));

        if ($user) {
            $telephone1->setCreatedBy($user);
        }
    }

    public function preUpdate(Telephones1 $telephone1, LifecycleEventArgs $arg): void
    {
        $token = $this->tokenStorage->getToken();
        $user = $token ? $token->getUser() : null;

        $telephone1->setUpdatedAt(new \DateTimeImmutable('now'))
            ->setSlug($this->getTelephoneSlug($telephone1));

        if ($user) {
            $telephone1->setUpdatedBy($user);
        }
    }

        public function postPersist(Telephones1 $telephone1, LifecycleEventArgs $args): void
    {
        // Exécuté après l'enregistrement
    }

    public function postUpdate(Telephones1 $telephone1, LifecycleEventArgs $args): void
    {
        // Exécuté après la mise à jour
    }
    public function postRemove(Telephones1 $telephone1, LifecycleEventArgs $args): void
    {
        // Exécuté après la suppression
    }
    public function preRemove(Telephones1 $telephone1, LifecycleEventArgs $args): void
    {
        // Exécuté avant la suppression
    }
    

    private function getTelephoneSlug(Telephones1 $telephone1): string
    {
        $slug = mb_strtolower($telephone1->getNumero() . '' . $telephone1->getId() , 'UTF-8');
        return $this->slugger->slug($slug)->toString();
    }
}
