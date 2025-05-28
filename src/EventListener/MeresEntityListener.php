<?php

namespace App\EventListener;

use LogicException;
use App\Entity\Meres;
use App\Entity\Users;
use Doctrine\ORM\Event\PostFlushEventArgs;
use Symfony\Bundle\SecurityBundle\Security;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class MeresEntityListener
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

    public function prePersist(Meres $mere, LifecycleEventArgs $arg): void
    {
        $token = $this->tokenStorage->getToken();
        $user = $token ? $token->getUser() : null;

        $mere->setCreatedAt(new \DateTimeImmutable('now'))
            ->setFullname($this->getMereFullname($mere))
            ->setSlug($this->getMereSlug($mere));

        if ($user) {
            $mere->setCreatedBy($user);
        }
    }

    public function preUpdate(Meres $mere, LifecycleEventArgs $arg): void
    {
        $token = $this->tokenStorage->getToken();
        $user = $token ? $token->getUser() : null;

        $mere->setUpdatedAt(new \DateTimeImmutable('now'))
            ->setFullname($this->getMereFullname($mere))
            ->setSlug($this->getMereSlug($mere));

        if ($user) {
            $mere->setUpdatedBy($user);
        }
    }

    public function postPersist(Meres $mere, LifecycleEventArgs $args): void
    {
        // Création d’un utilisateur en mémoire (non encore persisté)
        $user = new Users();
        $user->setMere($mere)
            ->setNom($mere->getNom()->getDesignation())
            ->setPrenom($mere->getPrenom()->getDesignation())
            ->setUsername($mere->getFullname() . '_' . $mere->getId())
            ->setRoles(['ROLE_PARENT'])
            ->setEmail($mere->getEmail() )
            ->setCreatedAt(new \DateTimeImmutable())
            ->setUpdatedAt(new \DateTimeImmutable())
            ->setCreatedBy($mere->getCreatedBy())
            ->setUpdatedBy($mere->getUpdatedBy());

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

    public function postUpdate(Meres $mere, LifecycleEventArgs $args): void
    {
        // Exécuté après la mise à jour
    }
    public function postRemove(Meres $mere, LifecycleEventArgs $args): void
    {
        // Exécuté après la suppression
    }
    public function preRemove(Meres $mere, LifecycleEventArgs $args): void
    {
        // Exécuté avant la suppression
    }
    

    private function getMereSlug(Meres $mere): string
    {
        $slug = mb_strtolower($mere->getNom() . '' . $mere->getPrenom() , 'UTF-8');
        return $this->slugger->slug($slug)->toString();
    }

    private function getMereFullname(Meres $mere): string
    {
        $fullname = mb_strtolower($mere->getNom() . ' ' . $mere->getPrenom(), 'UTF-8');
        return $fullname;
    }
}
