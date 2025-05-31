<?php

namespace App\EventListener;

use LogicException;
use App\Entity\Users;
use App\Entity\StatutEleves;
use Symfony\Bundle\SecurityBundle\Security;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\String\Slugger\SluggerInterface;

class UsersEntityListener
{
    private $Securty;
    private $Slugger;

    public function __construct(Security $security, SluggerInterface $Slugger)
    {
        $this->Securty = $security;
        $this->Slugger = $Slugger;
    }

    public function prePersist(Users $users, LifecycleEventArgs $arg): void
    {
        $user = $this->Securty->getUser();
        if ($user === null) {
            $users
            ->setCreatedAt(new \DateTimeImmutable('now'))
            ->setSlug($this->getClassesSlug($users));
        }else{
            $users
            ->setCreatedAt(new \DateTimeImmutable('now'))
            ->setSlug($this->getClassesSlug($users));
        }
    }

    public function preUpdate(Users $users, LifecycleEventArgs $arg): void
    {
        $user = $this->Securty->getUser();
        if ($user === null) {
            $users
            ->setUpdatedAt(new \DateTimeImmutable('now'))
            ->setSlug($this->getClassesSlug($users));
        }else{
            $users
            ->setUpdatedAt(new \DateTimeImmutable('now'))
            ->setSlug($this->getClassesSlug($users));
        }
    }


    private function getClassesSlug(Users $users): string
    {
        $slug = mb_strtolower($users->getUsername() . '-' . $users->getId() . '-' . time(), 'UTF-8');
        return $this->Slugger->slug($slug);
    }
}
