<?php

namespace App\EventListener;

use App\Entity\Users;
use LogicException;
use App\Entity\Eleves;
use Symfony\Bundle\SecurityBundle\Security;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\ORM\Event\PostFlushEventArgs;

class ElevesEntityListener
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

    public function prePersist(Eleves $eleve, LifecycleEventArgs $arg): void
    {
        $token = $this->tokenStorage->getToken();
        $user = $token ? $token->getUser() : null;

        $eleve->setCreatedAt(new \DateTimeImmutable('now'))
            ->setFullname($this->getEleveFullname($eleve))
            ->setSlug($this->getEleveSlug($eleve));

        if ($user) {
            $eleve->setCreatedBy($user);
        }
    }

    public function preUpdate(Eleves $eleve, LifecycleEventArgs $arg): void
    {
        $token = $this->tokenStorage->getToken();
        $user = $token ? $token->getUser() : null;

        $eleve->setUpdatedAt(new \DateTimeImmutable('now'))
            ->setFullname($this->getEleveFullname($eleve))
            ->setSlug($this->getEleveSlug($eleve));

        if ($user) {
            $eleve->setUpdatedBy($user);
        }
    }

    public function postPersist(Eleves $eleve, LifecycleEventArgs $args): void
    {
        // Création d’un utilisateur en mémoire (non encore persisté)
        $user = new Users();
        $user->setEleves($eleve)
            ->setNom($eleve->getNom()->getDesignation())
            ->setPrenom($eleve->getPrenom()->getDesignation())
            ->setUsername($eleve->getFullname() . '_' . $eleve->getId())
            ->setRoles(['ROLE_ELEVE'])
            ->setEmail($eleve->getEmail() )
            ->setCreatedAt(new \DateTimeImmutable())
            ->setUpdatedAt(new \DateTimeImmutable())
            ->setCreatedBy($eleve->getCreatedBy())
            ->setSlug($this->slugger->slug($eleve->getFullname())->toString(). '-' . $eleve->getId())
            ->setUpdatedBy($eleve->getUpdatedBy());

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

    public function postUpdate(Eleves $eleve, LifecycleEventArgs $args): void
    {
        // Exécuté après la mise à jour
    }
    public function postRemove(Eleves $eleve, LifecycleEventArgs $args): void
    {
        // Exécuté après la suppression
    }
    public function preRemove(Eleves $eleve, LifecycleEventArgs $args): void
    {
        // Exécuté avant la suppression
    }


    private function getEleveSlug(Eleves $eleve): string
    {
        $slug = mb_strtolower($eleve->getNom() . '' . $eleve->getPrenom(), 'UTF-8');
        return $this->slugger->slug($slug)->toString();
    }

    public function getEleveFullname(Eleves $eleve): string
    {
        $fullname = $eleve->getPrenom()->getDesignation() . ' ' . $eleve->getNom()->getDesignation();
        return mb_strtoupper($fullname, 'UTF-8');
    }
}
