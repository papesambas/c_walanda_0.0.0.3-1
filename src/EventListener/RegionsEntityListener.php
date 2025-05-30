<?php

namespace App\EventListener;

use LogicException;
use App\Entity\Regions;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\String\Slugger\SluggerInterface;

class RegionsEntityListener
{
    private $Securty;
    private $Slugger;

    public function __construct(Security $security, SluggerInterface $Slugger)
    {
        $this->Securty = $security;
        $this->Slugger = $Slugger;
    }

    public function prePersist(Regions $region, LifecycleEventArgs $arg): void
    {
        $user = $this->Securty->getUser();
        if ($user === null) {
            $region
            ->setCreatedAt(new \DateTimeImmutable('now'))
            ->setSlug($this->getClassesSlug($region));
        }else{
            $region
            ->setCreatedAt(new \DateTimeImmutable('now'))
            ->setCreatedBy($user)
            ->setSlug($this->getClassesSlug($region));
        }
    }

    public function preUpdate(Regions $region, LifecycleEventArgs $arg): void
    {
        $user = $this->Securty->getUser();
        if ($user === null) {
            $region
            ->setUpdatedAt(new \DateTimeImmutable('now'))
            ->setSlug($this->getClassesSlug($region));
        }else{
            $region
            ->setUpdatedAt(new \DateTimeImmutable('now'))
            ->setUpdatedBy($user)
            ->setSlug($this->getClassesSlug($region));
        }
    }
    public function postPersist(Regions $region, LifecycleEventArgs $args): void
    {
        if ($region->getId() === null) {
            throw new LogicException('Regions ID is null after persist.');
        }
    }
    public function postUpdate(Regions $region, LifecycleEventArgs $args): void
    {
        if ($region->getId() === null) {
            throw new LogicException('Regions ID is null after update.');
        }
    }
    public function postRemove(Regions $region, LifecycleEventArgs $args): void
    {
        if ($region->getId() === null) {
            throw new LogicException('Regions ID is null after remove.');
        }
    }


    private function getClassesSlug(Regions $region): string
    {
        $slug = mb_strtolower($region->getDesignation() . '-' . $region->getId() . '' . time(), 'UTF-8');
        return $this->Slugger->slug($slug);
    }
}
