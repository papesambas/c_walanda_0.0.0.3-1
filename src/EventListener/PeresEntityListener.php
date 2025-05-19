<?php

namespace App\EventListener;

use LogicException;
use App\Entity\Peres;
use Symfony\Bundle\SecurityBundle\Security;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class PeresEntityListener
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

    public function prePersist(Peres $pere, LifecycleEventArgs $arg): void
    {
        $token = $this->tokenStorage->getToken();
        $user = $token ? $token->getUser() : null;

        $pere->setCreatedAt(new \DateTimeImmutable('now'))
            ->setFullname($this->getPereFullname($pere))
            ->setSlug($this->getPereSlug($pere));

        if ($user) {
            $pere->setCreatedBy($user);
        }
    }

    public function preUpdate(Peres $pere, LifecycleEventArgs $arg): void
    {
        $token = $this->tokenStorage->getToken();
        $user = $token ? $token->getUser() : null;

        $pere->setUpdatedAt(new \DateTimeImmutable('now'))
            ->setFullname($this->getPereFullname($pere))
            ->setSlug($this->getPereSlug($pere));

        if ($user) {
            $pere->setUpdatedBy($user);
        }
    }

    public function postPersist(Peres $pere, LifecycleEventArgs $args): void
    {
        // Exécuté après l'enregistrement
    }

    public function postUpdate(Peres $pere, LifecycleEventArgs $args): void
    {
        // Exécuté après la mise à jour
    }
    public function postRemove(Peres $pere, LifecycleEventArgs $args): void
    {
        // Exécuté après la suppression
    }
    public function preRemove(Peres $pere, LifecycleEventArgs $args): void
    {
        // Exécuté avant la suppression
    }


    private function getPereSlug(Peres $pere): string
    {
        $slug = mb_strtolower($pere->getNom() . '' . $pere->getPrenom(), 'UTF-8');
        return $this->slugger->slug($slug)->toString();
    }

    public function getPereFullname(Peres $pere): string
    {
        $fullname = $pere->getPrenom()->getDesignation() . ' ' . $pere->getNom()->getDesignation();
        return mb_strtoupper($fullname, 'UTF-8');
    }
}
