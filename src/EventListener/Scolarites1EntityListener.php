<?php

namespace App\EventListener;

use LogicException;
use App\Entity\Scolarites1;
use Symfony\Bundle\SecurityBundle\Security;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class Scolarites1EntityListener
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

    public function prePersist(Scolarites1 $scolarite1, LifecycleEventArgs $arg): void
    {
        $token = $this->tokenStorage->getToken();
        $user = $token ? $token->getUser() : null;

        $scolarite1->setCreatedAt(new \DateTimeImmutable('now'))
            ->setSlug($this->getScolarite1Slug($scolarite1));

        if ($user) {
            $scolarite1->setCreatedBy($user);
        }
    }

    public function preUpdate(Scolarites1 $scolarite1, LifecycleEventArgs $arg): void
    {
        $token = $this->tokenStorage->getToken();
        $user = $token ? $token->getUser() : null;

        $scolarite1->setUpdatedAt(new \DateTimeImmutable('now'))
            ->setSlug($this->getScolarite1Slug($scolarite1));

        if ($user) {
            $scolarite1->setUpdatedBy($user);
        }
    }

        public function postPersist(Scolarites1 $scolarite1, LifecycleEventArgs $args): void
    {
        // Exécuté après l'enregistrement
    }

    public function postUpdate(Scolarites1 $scolarite1, LifecycleEventArgs $args): void
    {
        // Exécuté après la mise à jour
    }
    public function postRemove(Scolarites1 $scolarite1, LifecycleEventArgs $args): void
    {
        // Exécuté après la suppression
    }
    public function preRemove(Scolarites1 $scolarite1, LifecycleEventArgs $args): void
    {
        // Exécuté avant la suppression
    }
    

    private function getScolarite1Slug(Scolarites1 $scolarite1): string
    {
        $slug = mb_strtolower($scolarite1->getScolarite() . '' . $scolarite1->getId() , 'UTF-8');
        return $this->slugger->slug($slug)->toString();
    }

}
