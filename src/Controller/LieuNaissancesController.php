<?php

namespace App\Controller;

use App\Entity\LieuNaissances;
use App\Form\LieuNaissancesForm;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\LieuNaissancesRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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

        #[Route('/create/{label}', name: 'app_lieu_naissances_create', methods: ['POST'])]
    public function ajoutAjax(string $label, Request $request, EntityManagerInterface $entityManager): Response
    {
        $lieu = new LieuNaissances();
        $lieu->setDesignation(trim(strip_tags($label)));
        $entityManager->persist($lieu);
        $entityManager->flush();
        //on récupère l'Id qui a été créé
        $id = $lieu->getId();

        return new JsonResponse(['id' => $id]);
    }

    #[Route('/search', name: 'api_lieu_naissances_search', methods: ['GET'])]
    public function searchLieuNaissances(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $term = $request->query->get('term');
        $results = $em->getRepository(LieuNaissances::class)
            ->createQueryBuilder('n')
            ->where('n.designation LIKE :term')
            ->setParameter('term', '%'.$term.'%')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();

        return $this->json(array_map(fn($n) => [
            'id' => $n->getId(),
            'text' => $n->getDesignation()
        ], $results));
    }
}
