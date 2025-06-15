<?php

namespace App\Controller;

use App\Entity\Meres;
use App\Form\MeresForm;
use App\Repository\MeresRepository;
use App\Service\MeresCacheService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/meres')]
#[IsGranted('IS_AUTHENTICATED_FULLY')]
final class MeresController extends AbstractController
{
    #[Route(name: 'app_meres_index', methods: ['GET'])]
    public function index(MeresCacheService $meresCacheService): Response
    {
        return $this->render('meres/index.html.twig', [
            'meres' => $meresCacheService->getMeresList(),
        ]);
    }

    #[Route('/new', name: 'app_meres_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $mere = new Meres();
        $form = $this->createForm(MeresForm::class, $mere);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($mere);
            $entityManager->flush();

            return $this->redirectToRoute('app_meres_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('meres/new.html.twig', [
            'mere' => $mere,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_meres_show', methods: ['GET'])]
    public function show(Meres $mere): Response
    {
        return $this->render('meres/show.html.twig', [
            'mere' => $mere,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_meres_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Meres $mere, EntityManagerInterface $entityManager, MeresCacheService $meresCacheService): Response
    {
        $form = $this->createForm(MeresForm::class, $mere);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $meresCacheService->invalidateCache();

            return $this->redirectToRoute('app_meres_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('meres/edit.html.twig', [
            'mere' => $mere,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_meres_delete', methods: ['POST'])]
    public function delete(Request $request, Meres $mere, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $mere->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($mere);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_meres_index', [], Response::HTTP_SEE_OTHER);
    }
}
