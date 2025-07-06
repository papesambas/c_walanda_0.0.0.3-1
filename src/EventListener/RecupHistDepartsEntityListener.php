<?php

namespace App\EventListener;

use App\Entity\RecupHistRecupHistDeparts;
use LogicException;
use App\Entity\RecupHistDeparts;
use Symfony\Bundle\SecurityBundle\Security;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class RecupHistDepartsEntityListener
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

    public function prePersist(RecupHistDeparts $hist, LifecycleEventArgs $arg): void
    {
        $token = $this->tokenStorage->getToken();
        $user = $token ? $token->getUser() : null;

        $hist->setCreatedAt(new \DateTimeImmutable('now'))
            ->setDateDepart(new \DateTimeImmutable('now'));

        if ($user) {
            $hist->setCreatedBy($user);
        }
    }

    public function preUpdate(RecupHistDeparts $hist, LifecycleEventArgs $arg): void
    {
        $token = $this->tokenStorage->getToken();
        $user = $token ? $token->getUser() : null;

        $hist->setUpdatedAt(new \DateTimeImmutable('now'));

        if ($user) {
            $hist->setUpdatedBy($user);
        }
    }

    public function postPersist(RecupHistDeparts $hist, LifecycleEventArgs $args): void
    {
        // Exécuté après l'enregistrement
    }

    public function postUpdate(RecupHistDeparts $hist, LifecycleEventArgs $args): void
    {
        // Exécuté après la mise à jour
    }
    public function postRemove(RecupHistDeparts $hist, LifecycleEventArgs $args): void
    {
        // Exécuté après la suppression
    }
    public function preRemove(RecupHistDeparts $hist, LifecycleEventArgs $args): void
    {

    }

    private function getDepartSlug(RecupHistDeparts $hist): string
    {
        $slug = mb_strtolower($hist->getMotif() . '' . $hist->getId(), 'UTF-8');
        return $this->slugger->slug($slug)->toString();
    }
}
