<?php

namespace App\Controller;

use App\Entity\Statuts;
use App\Form\StatutsForm;
use App\Repository\StatutsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/statuts')]
final class StatutsController extends AbstractController
{
    #[Route(name: 'app_statuts_index', methods: ['GET'])]
    public function index(StatutsRepository $statutsRepository): Response
    {
        return $this->render('statuts/index.html.twig', [
            'statuts' => $statutsRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_statuts_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $statut = new Statuts();
        $form = $this->createForm(StatutsForm::class, $statut);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($statut);
            $entityManager->flush();

            return $this->redirectToRoute('app_statuts_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('statuts/new.html.twig', [
            'statut' => $statut,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_statuts_show', methods: ['GET'])]
    public function show(Statuts $statut): Response
    {
        return $this->render('statuts/show.html.twig', [
            'statut' => $statut,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_statuts_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Statuts $statut, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(StatutsForm::class, $statut);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_statuts_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('statuts/edit.html.twig', [
            'statut' => $statut,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_statuts_delete', methods: ['POST'])]
    public function delete(Request $request, Statuts $statut, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$statut->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($statut);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_statuts_index', [], Response::HTTP_SEE_OTHER);
    }
}
