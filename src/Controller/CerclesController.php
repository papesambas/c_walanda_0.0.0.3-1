<?php

namespace App\Controller;

use App\Entity\Cercles;
use App\Entity\Regions;
use App\Form\CerclesForm;
use App\Repository\CerclesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

// SUPPRIMEZ LE PRÉFIXE DE ROUTE ICI
#[Route('/cercles')]
final class CerclesController extends AbstractController
{
    #[Route('', name: 'app_cercles_index', methods: ['GET'])]
    public function index(CerclesRepository $cerclesRepository): Response
    {
        return $this->render('cercles/index.html.twig', [
            'cercles' => $cerclesRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_cercles_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $cercle = new Cercles();
        $form = $this->createForm(CerclesForm::class, $cercle);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($cercle);
            $entityManager->flush();

            return $this->redirectToRoute('app_cercles_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('cercles/new.html.twig', [
            'cercle' => $cercle,
            'form' => $form,
        ]);
    }

    // AUTRES MÉTHODES...

    #[Route('/create', name: 'app_cercles_create', methods: ['POST'])]
    public function ajoutAjax(Request $request, EntityManagerInterface $entityManager): Response
    {
        $data = json_decode($request->getContent(), true);
        $label = $data['label'] ?? '';
        $regionId = $data['region_id'] ?? null;

        // Nettoyage et validation
        $label = trim(strip_tags($label));

        if (empty($label)) {
            return new JsonResponse(['error' => 'Le label est requis'], Response::HTTP_BAD_REQUEST);
        }

        if (empty($regionId)) {
            return new JsonResponse(['error' => 'La région est requise'], Response::HTTP_BAD_REQUEST);
        }

        // Récupération de la région
        $region = $entityManager->getRepository(Regions::class)->find($regionId);

        if (!$region) {
            return new JsonResponse(['error' => 'Région introuvable'], Response::HTTP_NOT_FOUND);
        }

        // SUPPRIMEZ CE BLOC QUI CAUSAIT UNE ERREUR
        // if ($region) {
        //     return new JsonResponse(['error' => $region], Response::HTTP_NOT_FOUND);
        // }

        // Création du cercle
        $cercle = new Cercles();
        $cercle->setDesignation($label);
        $cercle->setRegion($region);

        $entityManager->persist($cercle);
        $entityManager->flush();

        return new JsonResponse([
            'id' => $cercle->getId(),
            'text' => $label
        ]);
    }

    #[Route('/search', name: 'app_cercles_search', methods: ['GET'])]
    public function search(Request $request, CerclesRepository $repository): Response
    {
        $term = $request->query->get('term', '');
        $regionId = $request->query->get('region_id');

        $results = $repository->search($term, $regionId);

        return $this->json($results);
    }
}