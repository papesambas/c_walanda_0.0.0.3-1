<?php

namespace App\EventListener;

use LogicException;
use App\Entity\Meres;
use Symfony\Bundle\SecurityBundle\Security;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class MeresEntityListener
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

    public function prePersist(Meres $mere, LifecycleEventArgs $arg): void
    {
        $token = $this->tokenStorage->getToken();
        $user = $token ? $token->getUser() : null;

        $mere->setCreatedAt(new \DateTimeImmutable('now'))
            ->setFullname($this->getMereFullname($mere))
            ->setSlug($this->getMereSlug($mere));

        if ($user) {
            $mere->setCreatedBy($user);
        }
    }

    public function preUpdate(Meres $mere, LifecycleEventArgs $arg): void
    {
        $token = $this->tokenStorage->getToken();
        $user = $token ? $token->getUser() : null;

        $mere->setUpdatedAt(new \DateTimeImmutable('now'))
            ->setFullname($this->getMereFullname($mere))
            ->setSlug($this->getMereSlug($mere));

        if ($user) {
            $mere->setUpdatedBy($user);
        }
    }

        public function postPersist(Meres $mere, LifecycleEventArgs $args): void
    {
        // Exécuté après l'enregistrement
    }

    public function postUpdate(Meres $mere, LifecycleEventArgs $args): void
    {
        // Exécuté après la mise à jour
    }
    public function postRemove(Meres $mere, LifecycleEventArgs $args): void
    {
        // Exécuté après la suppression
    }
    public function preRemove(Meres $mere, LifecycleEventArgs $args): void
    {
        // Exécuté avant la suppression
    }
    

    private function getMereSlug(Meres $mere): string
    {
        $slug = mb_strtolower($mere->getNom() . '' . $mere->getPrenom() , 'UTF-8');
        return $this->slugger->slug($slug)->toString();
    }

    private function getMereFullname(Meres $mere): string
    {
        $fullname = mb_strtolower($mere->getNom() . ' ' . $mere->getPrenom(), 'UTF-8');
        return $fullname;
    }
}
