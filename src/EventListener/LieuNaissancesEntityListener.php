<?php

namespace App\EventListener;

use LogicException;
use App\Entity\LieuNaissance;
use App\Entity\LieuNaissances;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\String\Slugger\SluggerInterface;

class LieuNaissancesEntityListener
{
    private $Securty;
    private $Slugger;

    public function __construct(Security $security, SluggerInterface $Slugger)
    {
        $this->Securty = $security;
        $this->Slugger = $Slugger;
    }

    public function prePersist(LieuNaissances $lieu, LifecycleEventArgs $arg): void
    {
        /*$user = $this->Securty->getUser();
        if ($user === null) {
            throw new LogicException('User cannot be null here ...');
        }*/


        $lieu
            ->setCreatedAt(new \DateTimeImmutable('now'))
            ->setSlug($this->getClassesSlug($lieu));
    }

    public function preUpdate(LieuNaissances $lieu, LifecycleEventArgs $arg): void
    {
        /*$user = $this->Securty->getUser();
        if ($user === null) {
            throw new LogicException('User cannot be null here ...');
        }*/

        $lieu
            ->setUpdatedAt(new \DateTimeImmutable('now'));
    }
    public function postPersist(LieuNaissances $lieu, LifecycleEventArgs $args): void
    {
        if ($lieu->getId() === null) {
            throw new LogicException('LieuNaissances ID is null after persist.');
        }
    }
    public function postUpdate(LieuNaissances $lieu, LifecycleEventArgs $args): void
    {
        if ($lieu->getId() === null) {
            throw new LogicException('LieuNaissances ID is null after update.');
        }
    }

    public function postRemove(LieuNaissances $lieu, LifecycleEventArgs $args): void
    {
        if ($lieu->getId() === null) {
            throw new LogicException('LieuNaissances ID is null after remove.');
        }
    }


    private function getClassesSlug(LieuNaissances $lieu): string
    {
        $slug = mb_strtolower($lieu->getDesignation() . '-' . $lieu->getId() . '-' . time(), 'UTF-8');
        return $this->Slugger->slug($slug);
    }
}
