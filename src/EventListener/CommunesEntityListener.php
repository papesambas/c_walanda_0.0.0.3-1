<?php

namespace App\EventListener;

use App\Entity\Cercles;
use LogicException;
use App\Entity\Classes;
use App\Entity\Communes;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\String\Slugger\SluggerInterface;

class CommunesEntityListener
{
    private $Security;
    private $Slugger;

    public function __construct(Security $security, SluggerInterface $Slugger)
    {
        $this->Security = $security;
        $this->Slugger = $Slugger;
    }

    public function prePersist(Communes $commune, LifecycleEventArgs $arg): void
    {
        $user = $this->Security->getUser();
        if ($user === null) {
            $commune
            ->setCreatedAt(new \DateTimeImmutable('now'))
            ->setSlug($this->getClassesSlug($commune));
        }else{
            $commune
            ->setCreatedAt(new \DateTimeImmutable('now'))
            ->setCreatedBy($user)
            ->setSlug($this->getClassesSlug($commune));
        }
    }

    public function preUpdate(Communes $commune, LifecycleEventArgs $arg): void
    {
        $user = $this->Security->getUser();
        if ($user === null) {
            $commune
            ->setUpdatedAt(new \DateTimeImmutable('now'))
            ->setSlug($this->getClassesSlug($commune));
        }else{
            $commune
            ->setUpdatedAt(new \DateTimeImmutable('now'))
            ->setUpdatedBy($user)
            ->setSlug($this->getClassesSlug($commune));
        }
    }

    public function postPersist(Communes $commune, LifecycleEventArgs $args): void
    {
        if ($commune->getId() === null) {
            throw new LogicException('Communes ID is null after persist.');
        }
    
    }
    public function postUpdate(Communes $commune, LifecycleEventArgs $args): void
    {
        if ($commune->getId() === null) {
            throw new LogicException('Communes ID is null after update.');
        }
    }

    public function postRemove(Communes $commune, LifecycleEventArgs $args): void
    {
        if ($commune->getId() === null) {
            throw new LogicException('Communes ID is null after remove.');
        }
    }




    private function getClassesSlug(Communes $commune): string
    {
        $slug = mb_strtolower($commune->getDesignation() . '-' . $commune->getId() . '-' . time(), 'UTF-8');
        return $this->Slugger->slug($slug);
    }
}
