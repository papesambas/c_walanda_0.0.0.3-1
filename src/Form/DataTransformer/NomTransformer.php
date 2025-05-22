<?php

// src/Form/DataTransformer/NomTransformer.php

namespace App\Form\DataTransformer;

use App\Entity\Noms;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class NomTransformer implements DataTransformerInterface
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    // Du modèle (Noms) vers la vue (id pour TomSelect)
    public function transform($nomEntity): string
    {
        if (null === $nomEntity) {
            return '';
        }
        return (string) $nomEntity->getId();
    }

    // De la vue (id ou texte) vers le modèle (Noms)
    public function reverseTransform($value): ?Noms
    {
        $repo = $this->em->getRepository(Noms::class);

        if (is_numeric($value)) {
            // Cas d’un choix existant
            $nom = $repo->find((int) $value);
            if (null === $nom) {
                throw new TransformationFailedException(sprintf(
                    'Le nom d’ID "%s" n’existe pas', $value
                ));
            }
            return $nom;
        }

        if ('' !== trim($value)) {
            // Création d’un nouveau nom
            $nom = new Noms();
            $nom->setDesignation($value);
            $this->em->persist($nom);
            // on ne flushera que dans le contrôleur
            return $nom;
        }

        return null;
    }
}
