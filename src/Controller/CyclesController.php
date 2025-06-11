<?php

namespace App\Controller;

use App\Entity\Cycles;
use App\Form\CyclesForm;
use App\Repository\CyclesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/cycles')]
final class CyclesController extends AbstractController
{
    #[Route(name: 'app_cycles_index', methods: ['GET'])]
    public function index(CyclesRepository $cyclesRepository): Response
    {
        return $this->render('cycles/index.html.twig', [
            'cycles' => $cyclesRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_cycles_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $cycle = new Cycles();
        $form = $this->createForm(CyclesForm::class, $cycle);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($cycle);
            $entityManager->flush();

            return $this->redirectToRoute('app_cycles_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('cycles/new.html.twig', [
            'cycle' => $cycle,
            'form' => $form,
        ]);
    }

    #[Route('/create/{label}', name: 'app_cycles_create', methods: ['POST'])]
    public function ajoutAjax(string $label, Request $request, EntityManagerInterface $entityManager): Response
    {
        $cycle = new Cycles();
        $cycle->setDesignation(trim(strip_tags($label)));
        $entityManager->persist($cycle);
        $entityManager->flush();
        //on récupère l'Id qui a été créé
        $id = $cycle->getId();

        return new JsonResponse(['id' => $id]);
    }

    #[Route('/search', name: 'api_cycles_search', methods: ['GET'])]
    public function searchCycles(Request $request,CyclesRepository $cyclesRepository): JsonResponse {
        $term = $request->query->get('term', '');
        $enseignementId = $request->query->get('enseignement_id');

        if (!$enseignementId) {
            return new JsonResponse([]);
        }

        $cycles = $cyclesRepository->findByEnseignementAndDesignation($enseignementId, $term);

        $results = array_map(function ($cycle) {
            return [
                'id' => $cycle->getId(),
                'text' => $cycle->getDesignation()
            ];
        }, $cycles);

        return new JsonResponse($results);
    }

    #[Route('/{id}', name: 'app_cycles_show', methods: ['GET'])]
    public function show(Cycles $cycle): Response
    {
        return $this->render('cycles/show.html.twig', [
            'cycle' => $cycle,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_cycles_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Cycles $cycle, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CyclesForm::class, $cycle);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_cycles_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('cycles/edit.html.twig', [
            'cycle' => $cycle,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_cycles_delete', methods: ['POST'])]
    public function delete(Request $request, Cycles $cycle, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $cycle->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($cycle);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_cycles_index', [], Response::HTTP_SEE_OTHER);
    }
}
