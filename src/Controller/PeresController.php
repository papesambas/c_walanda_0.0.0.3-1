<?php

namespace App\Controller;

use App\Entity\Peres;
use App\Form\PeresForm;
use App\Repository\PeresRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/peres')]
final class PeresController extends AbstractController
{
    #[Route(name: 'app_peres_index', methods: ['GET'])]
    public function index(PeresRepository $peresRepository): Response
    {
        return $this->render('peres/index.html.twig', [
            'peres' => $peresRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_peres_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $pere = new Peres();
        $form = $this->createForm(PeresForm::class, $pere);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($pere);
            $entityManager->flush();

            return $this->redirectToRoute('app_peres_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('peres/new.html.twig', [
            'pere' => $pere,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_peres_show', methods: ['GET'])]
    public function show(Peres $pere): Response
    {
        return $this->render('peres/show.html.twig', [
            'pere' => $pere,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_peres_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Peres $pere, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PeresForm::class, $pere);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_peres_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('peres/edit.html.twig', [
            'pere' => $pere,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_peres_delete', methods: ['POST'])]
    public function delete(Request $request, Peres $pere, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$pere->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($pere);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_peres_index', [], Response::HTTP_SEE_OTHER);
    }
}
