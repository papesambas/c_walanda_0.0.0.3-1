<?php

namespace App\EventListener;

use LogicException;
use App\Entity\Scolarites2;
use Symfony\Bundle\SecurityBundle\Security;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class Scolarites2EntityListener
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

    public function prePersist(Scolarites2 $scolarite2, LifecycleEventArgs $arg): void
    {
        $token = $this->tokenStorage->getToken();
        $user = $token ? $token->getUser() : null;

        $scolarite2->setCreatedAt(new \DateTimeImmutable('now'))
            ->setSlug($this->getScolarite2Slug($scolarite2));

        if ($user) {
            $scolarite2->setCreatedBy($user);
        }
    }

    public function preUpdate(Scolarites2 $scolarite2, LifecycleEventArgs $arg): void
    {
        $token = $this->tokenStorage->getToken();
        $user = $token ? $token->getUser() : null;

        $scolarite2->setUpdatedAt(new \DateTimeImmutable('now'))
            ->setSlug($this->getScolarite2Slug($scolarite2));

        if ($user) {
            $scolarite2->setUpdatedBy($user);
        }
    }

        public function postPersist(Scolarites2 $scolarite2, LifecycleEventArgs $args): void
    {
        // Exécuté après l'enregistrement
    }

    public function postUpdate(Scolarites2 $scolarite2, LifecycleEventArgs $args): void
    {
        // Exécuté après la mise à jour
    }
    public function postRemove(Scolarites2 $scolarite2, LifecycleEventArgs $args): void
    {
        // Exécuté après la suppression
    }
    public function preRemove(Scolarites2 $scolarite2, LifecycleEventArgs $args): void
    {
        // Exécuté avant la suppression
    }
    

    private function getScolarite2Slug(Scolarites2 $scolarite2): string
    {
        $slug = mb_strtolower($scolarite2->getScolarite() . '' . $scolarite2->getId() , 'UTF-8');
        return $this->slugger->slug($slug)->toString();
    }

}
