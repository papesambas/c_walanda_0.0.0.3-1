<?php

namespace App\Controller;

use App\Entity\Departs;
use App\Form\DepartsForm;
use App\Repository\DepartsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/departs')]
final class DepartsController extends AbstractController
{
    #[Route(name: 'app_departs_index', methods: ['GET'])]
    public function index(DepartsRepository $departsRepository): Response
    {
        return $this->render('departs/index.html.twig', [
            'departs' => $departsRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_departs_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $depart = new Departs();
        $form = $this->createForm(DepartsForm::class, $depart);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($depart);
            $entityManager->flush();

            return $this->redirectToRoute('app_departs_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('departs/new.html.twig', [
            'depart' => $depart,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_departs_show', methods: ['GET'])]
    public function show(Departs $depart): Response
    {
        return $this->render('departs/show.html.twig', [
            'depart' => $depart,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_departs_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Departs $depart, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(DepartsForm::class, $depart);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_departs_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('departs/edit.html.twig', [
            'depart' => $depart,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_departs_delete', methods: ['POST'])]
    public function delete(Request $request, Departs $depart, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$depart->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($depart);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_departs_index', [], Response::HTTP_SEE_OTHER);
    }
}
