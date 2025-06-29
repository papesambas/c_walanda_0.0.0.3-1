<?php

namespace App\EventListener;

use LogicException;
use App\Entity\DossierEleves;
use Symfony\Bundle\SecurityBundle\Security;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class DossierElevesEntityListener
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

    public function prePersist(DossierEleves $dossier, LifecycleEventArgs $arg): void
    {
        $token = $this->tokenStorage->getToken();
        $user = $token ? $token->getUser() : null;

        $dossier->setCreatedAt(new \DateTimeImmutable('now'))
            ->setSlug($this->getDossierEleveSlug($dossier));

        if ($user) {
            $dossier->setCreatedBy($user);
        }
    }

    public function preUpdate(DossierEleves $dossier, LifecycleEventArgs $arg): void
    {
        $token = $this->tokenStorage->getToken();
        $user = $token ? $token->getUser() : null;

        $dossier->setUpdatedAt(new \DateTimeImmutable('now'))
            ->setSlug($this->getDossierEleveSlug($dossier));

        if ($user) {
            $dossier->setUpdatedBy($user);
        }
    }

        public function postPersist(DossierEleves $dossier, LifecycleEventArgs $args): void
    {
        // Exécuté après l'enregistrement
    }

    public function postUpdate(DossierEleves $dossier, LifecycleEventArgs $args): void
    {
        // Exécuté après la mise à jour
    }
    public function postRemove(DossierEleves $dossier, LifecycleEventArgs $args): void
    {
        // Exécuté après la suppression
    }
    public function preRemove(DossierEleves $dossier, LifecycleEventArgs $args): void
    {
        // Exécuté avant la suppression
    }
    

    private function getDossierEleveSlug(DossierEleves $dossier): string
    {
        $slug = mb_strtolower($dossier->getDesignation() . '' . $dossier->getId() , 'UTF-8');
        return $this->slugger->slug($slug)->toString();
    }

}
