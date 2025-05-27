<?php

namespace App\Controller;

use App\Entity\Telephones1;
use App\Form\Telephones1Form;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\Telephones1Repository;
use App\Repository\Telephones2Repository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/telephones1')]
final class Telephones1Controller extends AbstractController
{
    #[Route(name: 'app_telephones1_index', methods: ['GET'])]
    public function index(Telephones1Repository $telephones1Repository): Response
    {
        return $this->render('telephones1/index.html.twig', [
            'telephones1s' => $telephones1Repository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_telephones1_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $telephones1 = new Telephones1();
        $form = $this->createForm(Telephones1Form::class, $telephones1);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($telephones1);
            $entityManager->flush();

            return $this->redirectToRoute('app_telephones1_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('telephones1/new.html.twig', [
            'telephones1' => $telephones1,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_telephones1_show', methods: ['GET'])]
    public function show(Telephones1 $telephones1): Response
    {
        return $this->render('telephones1/show.html.twig', [
            'telephones1' => $telephones1,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_telephones1_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Telephones1 $telephones1, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(Telephones1Form::class, $telephones1);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_telephones1_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('telephones1/edit.html.twig', [
            'telephones1' => $telephones1,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_telephones1_delete', methods: ['POST'])]
    public function delete(Request $request, Telephones1 $telephones1, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $telephones1->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($telephones1);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_telephones1_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/create/{label}', name: 'app_telephone1s_create', methods: ['POST'])]
    public function ajoutAjax(
        string $label,
        Request $request,
        EntityManagerInterface $entityManager,
        Telephones2Repository $telephones2Repository
    ): Response {
        $numero = $label; // Décoder l'URL
        //$telephone1 = $telephones1Repository->findOneBy(['numero' => $numero]);
        $telephone2 = $telephones2Repository->findOneByNumero($numero);

        if ($telephone2) {
            return new JsonResponse(['error' => 'Le numéro existe déjà'], Response::HTTP_CONFLICT);
        }
        // Création

        $telephone1 = new Telephones1();
        $telephone1->setNumero($numero);

        $entityManager->persist($telephone1);
        $entityManager->flush();

        return new JsonResponse(['id' => $telephone1->getId()]);
    }
}
