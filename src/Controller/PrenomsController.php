<?php

namespace App\Controller;

use App\Entity\Prenoms;
use App\Form\PrenomsForm;
use App\Repository\PrenomsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/prenoms')]
#[IsGranted('IS_AUTHENTICATED_FULLY')]
final class PrenomsController extends AbstractController
{
    #[Route(name: 'app_prenoms_index', methods: ['GET'])]
    public function index(PrenomsRepository $prenomsRepository): Response
    {
        return $this->render('prenoms/index.html.twig', [
            'prenoms' => $prenomsRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_prenoms_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $prenom = new Prenoms();
        $form = $this->createForm(PrenomsForm::class, $prenom);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($prenom);
            $entityManager->flush();

            return $this->redirectToRoute('app_prenoms_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('prenoms/new.html.twig', [
            'prenom' => $prenom,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_prenoms_show', methods: ['GET'])]
    public function show(Prenoms $prenom): Response
    {
        return $this->render('prenoms/show.html.twig', [
            'prenom' => $prenom,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_prenoms_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Prenoms $prenom, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PrenomsForm::class, $prenom);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_prenoms_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('prenoms/edit.html.twig', [
            'prenom' => $prenom,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_prenoms_delete', methods: ['POST'])]
    public function delete(Request $request, Prenoms $prenom, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $prenom->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($prenom);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_prenoms_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/create/{label}', name: 'app_prenoms_create', methods: ['POST'])]
    public function ajoutAjax(string $label, Request $request, EntityManagerInterface $entityManager): Response
    {
        $prenom = new Prenoms();
        $prenom->setDesignation(trim(strip_tags($label)));
        $entityManager->persist($prenom);
        $entityManager->flush();
        //on récupère l'Id qui a été créé
        $id = $prenom->getId();

        return new JsonResponse(['id' => $id]);
    }
}
