<?php

namespace App\Controller;

use App\Entity\Communes;
use App\Form\CommunesForm;
use App\Repository\CommunesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/communes')]
final class CommunesController extends AbstractController
{
    #[Route(name: 'app_communes_index', methods: ['GET'])]
    public function index(CommunesRepository $communesRepository): Response
    {
        return $this->render('communes/index.html.twig', [
            'communes' => $communesRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_communes_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $commune = new Communes();
        $form = $this->createForm(CommunesForm::class, $commune);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($commune);
            $entityManager->flush();

            return $this->redirectToRoute('app_communes_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('communes/new.html.twig', [
            'commune' => $commune,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_communes_show', methods: ['GET'])]
    public function show(Communes $commune): Response
    {
        return $this->render('communes/show.html.twig', [
            'commune' => $commune,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_communes_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Communes $commune, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CommunesForm::class, $commune);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_communes_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('communes/edit.html.twig', [
            'commune' => $commune,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_communes_delete', methods: ['POST'])]
    public function delete(Request $request, Communes $commune, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$commune->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($commune);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_communes_index', [], Response::HTTP_SEE_OTHER);
    }
}
