<?php

namespace App\Controller;

use App\Entity\Professions;
use App\Form\ProfessionsForm;
use App\Repository\ProfessionsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/professions')]
final class ProfessionsController extends AbstractController
{
    #[Route(name: 'app_professions_index', methods: ['GET'])]
    public function index(ProfessionsRepository $professionsRepository): Response
    {
        return $this->render('professions/index.html.twig', [
            'professions' => $professionsRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_professions_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $profession = new Professions();
        $form = $this->createForm(ProfessionsForm::class, $profession);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($profession);
            $entityManager->flush();

            return $this->redirectToRoute('app_professions_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('professions/new.html.twig', [
            'profession' => $profession,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_professions_show', methods: ['GET'])]
    public function show(Professions $profession): Response
    {
        return $this->render('professions/show.html.twig', [
            'profession' => $profession,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_professions_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Professions $profession, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ProfessionsForm::class, $profession);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_professions_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('professions/edit.html.twig', [
            'profession' => $profession,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_professions_delete', methods: ['POST'])]
    public function delete(Request $request, Professions $profession, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$profession->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($profession);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_professions_index', [], Response::HTTP_SEE_OTHER);
    }
}
