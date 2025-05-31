<?php

namespace App\EventListener;

use LogicException;
use App\Entity\Users;
use App\Entity\Etablissements;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Event\PostFlushEventArgs;
use Symfony\Bundle\SecurityBundle\Security;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class EtablissementsEntityListener
{
    private array $newUsers = [];

    private $security;
    private $slugger;
    private $tokenStorage;
    private $passwordHasher;
    private $entityManager;

    public function __construct(
        Security $security,
        SluggerInterface $slugger,
        UserPasswordHasherInterface $passwordHasher,
        TokenStorageInterface $tokenStorage,
        EntityManagerInterface $entityManager
    ) {
        $this->security = $security;
        $this->slugger = $slugger;
        $this->tokenStorage = $tokenStorage;
        $this->passwordHasher = $passwordHasher;
        $this->entityManager = $entityManager;
    }

    public function prePersist(Etablissements $etablissement, LifecycleEventArgs $arg): void
    {
        $token = $this->tokenStorage->getToken();
        $user = $token ? $token->getUser() : null;

        $etablissement
            ->setCreatedAt(new \DateTimeImmutable('now'))
            ->setSlug($this->getEtablissementSlug($etablissement));

        if ($user) {
            $etablissement->setCreatedBy($user);
        }
    }

    public function preUpdate(Etablissements $etablissement, LifecycleEventArgs $arg): void
    {
        $token = $this->tokenStorage->getToken();
        $user = $token ? $token->getUser() : null;

        $etablissement
            ->setUpdatedAt(new \DateTimeImmutable('now'))
            ->setSlug($this->getEtablissementSlug($etablissement));

        if ($user) {
            $etablissement->setUpdatedBy($user);
        }
    }

    public function postPersist(Etablissements $etablissement, LifecycleEventArgs $args): void
    {
        $rolesConfig = [
            ['superadmin', 'SIDIBE', 'Pape Samba', ['ROLE_SUPERADMIN']],
            ['admin', 'TOURE', 'Amadou', ['ROLE_ADMIN']],
            ['chef', 'CAMARA', 'Alassane', ['ROLE_DIRECTION']],
            ['secretaire', 'TRAORE', 'Aminata', ['ROLE_SECRETAIRE']]
        ];

        foreach ($rolesConfig as $config) {
            $user = new Users();
            $username = $config[0] . $etablissement->getId();

            $user->setUsername($username)
                ->setNom($config[1])
                ->setPrenom($config[2])
                ->setEmail($username . '@' . $etablissement->getSlug() . '.com')
                ->setRoles($config[3])
                ->setEtablissement($etablissement)
                ->setSlug($this->slugger->slug($username)->toString(). '-' . $etablissement->getId())
                ->setCreatedAt(new \DateTimeImmutable())
                ->setIsActif(true);

            // Hachage du mot de passe
            $hashedPassword = $this->passwordHasher->hashPassword($user, 'password'); // remplace 'password' par logique réelle
            $user->setPassword($hashedPassword);

            // On stocke l’utilisateur pour le postFlush
            $this->newUsers[] = $user;
        }
    }

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

    private function getEtablissementSlug(Etablissements $etablissement): string
    {
        $slug = mb_strtolower($etablissement->getDesignation() . '-' . $etablissement->getId(), 'UTF-8');
        return $this->slugger->slug($slug)->toString();
    }
}
