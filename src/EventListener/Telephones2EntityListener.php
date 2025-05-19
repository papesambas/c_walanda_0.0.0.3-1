<?php

namespace App\EventListener;

use LogicException;
use App\Entity\Telephones2;
use Symfony\Bundle\SecurityBundle\Security;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class Telephones2EntityListener
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

    public function prePersist(Telephones2 $telephone2, LifecycleEventArgs $arg): void
    {
        $token = $this->tokenStorage->getToken();
        $user = $token ? $token->getUser() : null;

        $telephone2->setCreatedAt(new \DateTimeImmutable('now'))
            ->setSlug($this->getTelephoneSlug($telephone2));

        if ($user) {
            $telephone2->setCreatedBy($user);
        }
    }

    public function preUpdate(Telephones2 $telephone2, LifecycleEventArgs $arg): void
    {
        $token = $this->tokenStorage->getToken();
        $user = $token ? $token->getUser() : null;

        $telephone2->setUpdatedAt(new \DateTimeImmutable('now'))
            ->setSlug($this->getTelephoneSlug($telephone2));

        if ($user) {
            $telephone2->setUpdatedBy($user);
        }
    }

        public function postPersist(Telephones2 $telephone2, LifecycleEventArgs $args): void
    {
        // Exécuté après l'enregistrement
    }

    public function postUpdate(Telephones2 $telephone2, LifecycleEventArgs $args): void
    {
        // Exécuté après la mise à jour
    }
    public function postRemove(Telephones2 $telephone2, LifecycleEventArgs $args): void
    {
        // Exécuté après la suppression
    }
    public function preRemove(Telephones2 $telephone2, LifecycleEventArgs $args): void
    {
        // Exécuté avant la suppression
    }
    

    private function getTelephoneSlug(Telephones2 $telephone2): string
    {
        $slug = mb_strtolower($telephone2->getNumero() . '' . $telephone2->getId() , 'UTF-8');
        return $this->slugger->slug($slug)->toString();
    }
}
