<?php

namespace App\EventListener;

use LogicException;
use App\Entity\Scolarites3;
use Symfony\Bundle\SecurityBundle\Security;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class Scolarites3EntityListener
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

    public function prePersist(Scolarites3 $scolarite3, LifecycleEventArgs $arg): void
    {
        $token = $this->tokenStorage->getToken();
        $user = $token ? $token->getUser() : null;

        $scolarite3->setCreatedAt(new \DateTimeImmutable('now'))
            ->setSlug($this->getScolarite3Slug($scolarite3));

        if ($user) {
            $scolarite3->setCreatedBy($user);
        }
    }

    public function preUpdate(Scolarites3 $scolarite3, LifecycleEventArgs $arg): void
    {
        $token = $this->tokenStorage->getToken();
        $user = $token ? $token->getUser() : null;

        $scolarite3->setUpdatedAt(new \DateTimeImmutable('now'))
            ->setSlug($this->getScolarite3Slug($scolarite3));

        if ($user) {
            $scolarite3->setUpdatedBy($user);
        }
    }

        public function postPersist(Scolarites3 $scolarite3, LifecycleEventArgs $args): void
    {
        // Exécuté après l'enregistrement
    }

    public function postUpdate(Scolarites3 $scolarite3, LifecycleEventArgs $args): void
    {
        // Exécuté après la mise à jour
    }
    public function postRemove(Scolarites3 $scolarite3, LifecycleEventArgs $args): void
    {
        // Exécuté après la suppression
    }
    public function preRemove(Scolarites3 $scolarite3, LifecycleEventArgs $args): void
    {
        // Exécuté avant la suppression
    }
    

    private function getScolarite3Slug(Scolarites3 $scolarite3): string
    {
        $slug = mb_strtolower($scolarite3->getScolarite() . '' . $scolarite3->getId() , 'UTF-8');
        return $this->slugger->slug($slug)->toString();
    }

}
