<?php

namespace App\EventListener;

use App\Entity\Users;
use LogicException;
use App\Entity\Peres;
use Symfony\Bundle\SecurityBundle\Security;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\ORM\Event\PostFlushEventArgs;

class PeresEntityListener
{
    private array $newUsers = [];
    private $security;
    private $slugger;
    private $tokenStorage;
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(Security $security, SluggerInterface $slugger, TokenStorageInterface $tokenStorage, UserPasswordHasherInterface $passwordHasher)
    {
        $this->security = $security;
        $this->slugger = $slugger;
        $this->tokenStorage = $tokenStorage;
        $this->passwordHasher = $passwordHasher;
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
        // Création d’un utilisateur en mémoire (non encore persisté)
        $user = new Users();
        $user->setPere($pere)
            ->setNom($pere->getNom()->getDesignation())
            ->setPrenom($pere->getPrenom()->getDesignation())
            ->setUsername($pere->getFullname() . '_' . $pere->getId())
            ->setRoles(['ROLE_PARENT'])
            ->setEmail($pere->getEmail() )
            ->setCreatedAt(new \DateTimeImmutable())
            ->setUpdatedAt(new \DateTimeImmutable())
            ->setCreatedBy($pere->getCreatedBy())
            ->setUpdatedBy($pere->getUpdatedBy());

        // Hash du mot de passe
        $hashedPassword = $this->passwordHasher->hashPassword($user, 'password'); // remplace 'password' par logique réelle
        $user->setPassword($hashedPassword);

        // On stocke l’utilisateur pour le postFlush
        $this->newUsers[] = $user;
    }

    /**
     * Après le flush principal : on peut persister les Users en toute sécurité
     */
    public function postFlush(PostFlushEventArgs $args): void
    {
        if (empty($this->newUsers)) {
            return;
        }

        $em = $args->getObjectManager();

        foreach ($this->newUsers as $user) {
            $em->persist($user);
        }

        $this->newUsers = []; // on vide pour éviter les doublons

        $em->flush(); // ✅ On peut faire le flush ici sans effet de bord
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
