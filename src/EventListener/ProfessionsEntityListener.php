<?php

namespace App\EventListener;

use LogicException;
use App\Entity\Professions;
use Symfony\Bundle\SecurityBundle\Security;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class ProfessionsEntityListener
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

    public function prePersist(Professions $professions, LifecycleEventArgs $arg): void
    {
        $token = $this->tokenStorage->getToken();
        $user = $token ? $token->getUser() : null;

        $professions->setCreatedAt(new \DateTimeImmutable('now'))
            ->setSlug($this->getProfessionSlug($professions));

        if ($user) {
            $professions->setCreatedBy($user);
        }
    }

    public function preUpdate(Professions $professions, LifecycleEventArgs $arg): void
    {
        $token = $this->tokenStorage->getToken();
        $user = $token ? $token->getUser() : null;

        $professions->setUpdatedAt(new \DateTimeImmutable('now'))
            ->setSlug($this->getProfessionSlug($professions));

        if ($user) {
            $professions->setUpdatedBy($user);
        }
    }

        public function postPersist(Professions $professions, LifecycleEventArgs $args): void
    {
        // Exécuté après l'enregistrement
    }

    public function postUpdate(Professions $professions, LifecycleEventArgs $args): void
    {
        // Exécuté après la mise à jour
    }
    public function postRemove(Professions $professions, LifecycleEventArgs $args): void
    {
        // Exécuté après la suppression
    }
    public function preRemove(Professions $professions, LifecycleEventArgs $args): void
    {
        // Exécuté avant la suppression
    }
    

    private function getProfessionSlug(Professions $professions): string
    {
        $slug = mb_strtolower($professions->getDesignation() . '' . $professions->getId() , 'UTF-8');
        return $this->slugger->slug($slug)->toString();
    }
}
