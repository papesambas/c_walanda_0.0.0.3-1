<?php

namespace App\Controller;

use App\Entity\Cercles;
use App\Form\CerclesForm;
use App\Repository\CerclesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/cercles')]
final class CerclesController extends AbstractController
{
    #[Route(name: 'app_cercles_index', methods: ['GET'])]
    public function index(CerclesRepository $cerclesRepository): Response
    {
        return $this->render('cercles/index.html.twig', [
            'cercles' => $cerclesRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_cercles_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $cercle = new Cercles();
        $form = $this->createForm(CerclesForm::class, $cercle);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($cercle);
            $entityManager->flush();

            return $this->redirectToRoute('app_cercles_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('cercles/new.html.twig', [
            'cercle' => $cercle,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_cercles_show', methods: ['GET'])]
    public function show(Cercles $cercle): Response
    {
        return $this->render('cercles/show.html.twig', [
            'cercle' => $cercle,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_cercles_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Cercles $cercle, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CerclesForm::class, $cercle);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_cercles_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('cercles/edit.html.twig', [
            'cercle' => $cercle,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_cercles_delete', methods: ['POST'])]
    public function delete(Request $request, Cercles $cercle, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$cercle->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($cercle);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_cercles_index', [], Response::HTTP_SEE_OTHER);
    }
}
