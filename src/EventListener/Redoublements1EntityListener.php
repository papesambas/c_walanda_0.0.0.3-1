<?php

namespace App\EventListener;

use LogicException;
use App\Entity\Redoublements1;
use Symfony\Bundle\SecurityBundle\Security;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class Redoublements1EntityListener
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

    public function prePersist(Redoublements1 $redoublement1, LifecycleEventArgs $arg): void
    {
        $token = $this->tokenStorage->getToken();
        $user = $token ? $token->getUser() : null;

        $redoublement1->setCreatedAt(new \DateTimeImmutable('now'))
            ->setSlug($this->getRedoublement1Slug($redoublement1));

        if ($user) {
            $redoublement1->setCreatedBy($user);
        }
    }

    public function preUpdate(Redoublements1 $redoublement1, LifecycleEventArgs $arg): void
    {
        $token = $this->tokenStorage->getToken();
        $user = $token ? $token->getUser() : null;

        $redoublement1->setUpdatedAt(new \DateTimeImmutable('now'))
            ->setSlug($this->getRedoublement1Slug($redoublement1));

        if ($user) {
            $redoublement1->setUpdatedBy($user);
        }
    }

        public function postPersist(Redoublements1 $redoublement1, LifecycleEventArgs $args): void
    {
        // Exécuté après l'enregistrement
    }

    public function postUpdate(Redoublements1 $redoublement1, LifecycleEventArgs $args): void
    {
        // Exécuté après la mise à jour
    }
    public function postRemove(Redoublements1 $redoublement1, LifecycleEventArgs $args): void
    {
        // Exécuté après la suppression
    }
    public function preRemove(Redoublements1 $redoublement1, LifecycleEventArgs $args): void
    {
        // Exécuté avant la suppression
    }
    

    private function getRedoublement1Slug(Redoublements1 $redoublement1): string
    {
        $slug = mb_strtolower($redoublement1->getDesignation() . '' . $redoublement1->getId() , 'UTF-8');
        return $this->slugger->slug($slug)->toString();
    }

}
