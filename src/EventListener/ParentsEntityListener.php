<?php

namespace App\EventListener;

use LogicException;
use App\Entity\Parents;
use Symfony\Bundle\SecurityBundle\Security;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class ParentsEntityListener
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

    public function prePersist(Parents $parents, LifecycleEventArgs $arg): void
    {
        $token = $this->tokenStorage->getToken();
        $user = $token ? $token->getUser() : null;

        $parents->setCreatedAt(new \DateTimeImmutable('now'))
            ->setSlug($this->getParentSlug($parents))
            ->setFullname($this->getParentFullname($parents));

        if ($user) {
            $parents->setCreatedBy($user);
        }
    }

    public function preUpdate(Parents $parents, LifecycleEventArgs $arg): void
    {
        $token = $this->tokenStorage->getToken();
        $user = $token ? $token->getUser() : null;

        $parents->setUpdatedAt(new \DateTimeImmutable('now'))
            ->setSlug($this->getParentSlug($parents))
            ->setFullname($this->getParentFullname($parents));

        if ($user) {
            $parents->setUpdatedBy($user);
        }
    }

        public function postPersist(Parents $parents, LifecycleEventArgs $args): void
    {
        // Exécuté après l'enregistrement
    }

    public function postUpdate(Parents $parents, LifecycleEventArgs $args): void
    {
        // Exécuté après la mise à jour
    }
    public function postRemove(Parents $parents, LifecycleEventArgs $args): void
    {
        // Exécuté après la suppression
    }
    public function preRemove(Parents $parents, LifecycleEventArgs $args): void
    {
        // Exécuté avant la suppression
    }


    private function getParentSlug(Parents $parents): string
    {
        $slug = mb_strtolower($parents->getPere()->getFullname() . ' et ' . $parents->getMere()->getFullname() , 'UTF-8');
        return $this->slugger->slug($slug)->toString();
    }

    private function getParentFullname(Parents $parents): string
    {
        $fullname = trim($parents->getPere()->getFullname() . ' ' . $parents->getMere()->getFullname());
        if (empty($fullname)) {
            throw new LogicException('Le nom complet du parent ne peut pas être vide.');
        }
        return $fullname;
    }
}
