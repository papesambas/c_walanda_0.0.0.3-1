<?php

namespace App\Controller;

use App\Entity\LieuNaissances;
use App\Form\LieuNaissancesForm;
use App\Repository\LieuNaissancesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/lieu/naissances')]
final class LieuNaissancesController extends AbstractController
{
    #[Route(name: 'app_lieu_naissances_index', methods: ['GET'])]
    public function index(LieuNaissancesRepository $lieuNaissancesRepository): Response
    {
        return $this->render('lieu_naissances/index.html.twig', [
            'lieu_naissances' => $lieuNaissancesRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_lieu_naissances_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $lieuNaissance = new LieuNaissances();
        $form = $this->createForm(LieuNaissancesForm::class, $lieuNaissance);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($lieuNaissance);
            $entityManager->flush();

            return $this->redirectToRoute('app_lieu_naissances_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('lieu_naissances/new.html.twig', [
            'lieu_naissance' => $lieuNaissance,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_lieu_naissances_show', methods: ['GET'])]
    public function show(LieuNaissances $lieuNaissance): Response
    {
        return $this->render('lieu_naissances/show.html.twig', [
            'lieu_naissance' => $lieuNaissance,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_lieu_naissances_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, LieuNaissances $lieuNaissance, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(LieuNaissancesForm::class, $lieuNaissance);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_lieu_naissances_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('lieu_naissances/edit.html.twig', [
            'lieu_naissance' => $lieuNaissance,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_lieu_naissances_delete', methods: ['POST'])]
    public function delete(Request $request, LieuNaissances $lieuNaissance, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$lieuNaissance->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($lieuNaissance);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_lieu_naissances_index', [], Response::HTTP_SEE_OTHER);
    }
}
