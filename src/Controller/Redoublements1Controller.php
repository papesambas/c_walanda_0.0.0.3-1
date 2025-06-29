<?php

namespace App\Controller;

use App\Entity\Redoublements1;
use App\Form\Redoublements1Form;
use App\Repository\Redoublements1Repository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/redoublements1')]
final class Redoublements1Controller extends AbstractController
{
    #[Route(name: 'app_redoublements1_index', methods: ['GET'])]
    public function index(Redoublements1Repository $redoublements1Repository): Response
    {
        return $this->render('redoublements1/index.html.twig', [
            'redoublements1s' => $redoublements1Repository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_redoublements1_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $redoublements1 = new Redoublements1();
        $form = $this->createForm(Redoublements1Form::class, $redoublements1);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($redoublements1);
            $entityManager->flush();

            return $this->redirectToRoute('app_redoublements1_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('redoublements1/new.html.twig', [
            'redoublements1' => $redoublements1,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_redoublements1_show', methods: ['GET'])]
    public function show(Redoublements1 $redoublements1): Response
    {
        return $this->render('redoublements1/show.html.twig', [
            'redoublements1' => $redoublements1,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_redoublements1_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Redoublements1 $redoublements1, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(Redoublements1Form::class, $redoublements1);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_redoublements1_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('redoublements1/edit.html.twig', [
            'redoublements1' => $redoublements1,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_redoublements1_delete', methods: ['POST'])]
    public function delete(Request $request, Redoublements1 $redoublements1, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$redoublements1->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($redoublements1);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_redoublements1_index', [], Response::HTTP_SEE_OTHER);
    }
}
