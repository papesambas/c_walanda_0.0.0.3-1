<?php

namespace App\Controller;

use App\Entity\Santes;
use App\Form\SantesForm;
use App\Repository\SantesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/santes')]
final class SantesController extends AbstractController
{
    #[Route(name: 'app_santes_index', methods: ['GET'])]
    public function index(SantesRepository $santesRepository): Response
    {
        return $this->render('santes/index.html.twig', [
            'santes' => $santesRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_santes_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $sante = new Santes();
        $form = $this->createForm(SantesForm::class, $sante);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($sante);
            $entityManager->flush();

            return $this->redirectToRoute('app_santes_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('santes/new.html.twig', [
            'sante' => $sante,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_santes_show', methods: ['GET'])]
    public function show(Santes $sante): Response
    {
        return $this->render('santes/show.html.twig', [
            'sante' => $sante,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_santes_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Santes $sante, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SantesForm::class, $sante);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_santes_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('santes/edit.html.twig', [
            'sante' => $sante,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_santes_delete', methods: ['POST'])]
    public function delete(Request $request, Santes $sante, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$sante->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($sante);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_santes_index', [], Response::HTTP_SEE_OTHER);
    }
}
