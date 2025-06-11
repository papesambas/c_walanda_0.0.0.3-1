<?php

namespace App\Controller;

use App\Entity\Niveaux;
use App\Form\NiveauxForm;
use Psr\Log\LoggerInterface;
use App\Repository\NiveauxRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/niveaux')]
final class NiveauxController extends AbstractController
{
    #[Route(name: 'app_niveaux_index', methods: ['GET'])]
    public function index(NiveauxRepository $niveauxRepository): Response
    {
        return $this->render('niveaux/index.html.twig', [
            'niveauxes' => $niveauxRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_niveaux_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $niveau = new Niveaux();
        $form = $this->createForm(NiveauxForm::class, $niveau);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($niveau);
            $entityManager->flush();

            return $this->redirectToRoute('app_niveaux_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('niveaux/new.html.twig', [
            'niveau' => $niveau,
            'form' => $form,
        ]);
    }

            #[Route('/create/{label}', name: 'app_niveaux_create', methods: ['POST'])]
    public function ajoutAjax(string $label, Request $request, EntityManagerInterface $entityManager): Response
    {
        $niveau = new Niveaux();
        $niveau->setDesignation(trim(strip_tags($label)));
        $entityManager->persist($niveau);
        $entityManager->flush();
        //on récupère l'Id qui a été créé
        $id = $niveau->getId();

        return new JsonResponse(['id' => $id]);
    }

    #[Route('/search', name: 'api_niveaux_search', methods: ['GET'])]
    public function searchNiveaux(Request $request,NiveauxRepository $niveauxRepository): JsonResponse {
        $term = $request->query->get('term', '');
        $cycleId = $request->query->get('cycle_id');

        if (!$cycleId) {
            return new JsonResponse([]);
        }

        $niveaux = $niveauxRepository->findByCycleAndDesignation($cycleId, $term);

        $results = array_map(function ($niveau) {
            return [
                'id' => $niveau->getId(),
                'text' => $niveau->getDesignation()
            ];
        }, $niveaux);

        return new JsonResponse($results);
    }

    #[Route('/{id}', name: 'app_niveaux_show', methods: ['GET'])]
    public function show(Niveaux $niveau): Response
    {
        return $this->render('niveaux/show.html.twig', [
            'niveau' => $niveau,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_niveaux_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Niveaux $niveau, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(NiveauxForm::class, $niveau);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_niveaux_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('niveaux/edit.html.twig', [
            'niveau' => $niveau,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_niveaux_delete', methods: ['POST'])]
    public function delete(Request $request, Niveaux $niveau, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$niveau->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($niveau);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_niveaux_index', [], Response::HTTP_SEE_OTHER);
    }
}
