<?php

namespace App\Controller;

use App\Entity\Enseignements;
use App\Form\EnseignementsForm;
use App\Repository\EnseignementsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/enseignements')]
final class EnseignementsController extends AbstractController
{
    #[Route(name: 'app_enseignements_index', methods: ['GET'])]
    public function index(EnseignementsRepository $enseignementsRepository): Response
    {
        return $this->render('enseignements/index.html.twig', [
            'enseignements' => $enseignementsRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_enseignements_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $enseignement = new Enseignements();
        $form = $this->createForm(EnseignementsForm::class, $enseignement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($enseignement);
            $entityManager->flush();

            return $this->redirectToRoute('app_enseignements_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('enseignements/new.html.twig', [
            'enseignement' => $enseignement,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_enseignements_show', methods: ['GET'])]
    public function show(Enseignements $enseignement): Response
    {
        return $this->render('enseignements/show.html.twig', [
            'enseignement' => $enseignement,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_enseignements_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Enseignements $enseignement, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(EnseignementsForm::class, $enseignement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_enseignements_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('enseignements/edit.html.twig', [
            'enseignement' => $enseignement,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_enseignements_delete', methods: ['POST'])]
    public function delete(Request $request, Enseignements $enseignement, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$enseignement->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($enseignement);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_enseignements_index', [], Response::HTTP_SEE_OTHER);
    }
}
