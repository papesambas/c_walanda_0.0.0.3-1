<?php

namespace App\Controller;

use App\Entity\Regions;
use App\Form\RegionsForm;
use App\Repository\RegionsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/regions')]
final class RegionsController extends AbstractController
{
    #[Route(name: 'app_regions_index', methods: ['GET'])]
    public function index(RegionsRepository $regionsRepository): Response
    {
        return $this->render('regions/index.html.twig', [
            'regions' => $regionsRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_regions_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $region = new Regions();
        $form = $this->createForm(RegionsForm::class, $region);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($region);
            $entityManager->flush();

            return $this->redirectToRoute('app_regions_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('regions/new.html.twig', [
            'region' => $region,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_regions_show', methods: ['GET'])]
    public function show(Regions $region): Response
    {
        return $this->render('regions/show.html.twig', [
            'region' => $region,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_regions_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Regions $region, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(RegionsForm::class, $region);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_regions_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('regions/edit.html.twig', [
            'region' => $region,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_regions_delete', methods: ['POST'])]
    public function delete(Request $request, Regions $region, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$region->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($region);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_regions_index', [], Response::HTTP_SEE_OTHER);
    }
}
