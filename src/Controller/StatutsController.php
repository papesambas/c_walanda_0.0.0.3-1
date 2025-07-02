<?php

namespace App\Controller;

use App\Entity\Statuts;
use App\Form\StatutsForm;
use App\Entity\Enseignements;
use App\Repository\StatutsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/statuts')]
#[IsGranted('IS_AUTHENTICATED_FULLY')]
final class StatutsController extends AbstractController
{
    #[Route(name: 'app_statuts_index', methods: ['GET'])]
    public function index(StatutsRepository $statutsRepository): Response
    {
        return $this->render('statuts/index.html.twig', [
            'statuts' => $statutsRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_statuts_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $statut = new Statuts();
        $form = $this->createForm(StatutsForm::class, $statut);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($statut);
            $entityManager->flush();

            return $this->redirectToRoute('app_statuts_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('statuts/new.html.twig', [
            'statut' => $statut,
            'form' => $form,
        ]);
    }

    #[Route('/search', name: 'statuts_search', methods: ['GET'])]
    public function search(Request $request, StatutsRepository $repository): JsonResponse
    {
        $term = $request->query->get('term');
        $enseignementId = $request->query->get('enseignement_id');

        $statuts = $repository->searchByEnseignement($term, $enseignementId);

        return $this->json(array_map(fn($s) => [
            'id' => $s->getId(),
            'text' => $s->getDesignation()
        ], $statuts));
    }

    #[Route('/create', name: 'statuts_create', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $statut = new Statuts();
        $statut->setDesignation($data['designation']);

        if ($enseignementId = $data['enseignement_id']) {
            $enseignement = $em->find(Enseignements::class, $enseignementId);
            $statut->addEnseignement($enseignement);
        }

        $em->persist($statut);
        $em->flush();

        return $this->json([
            'success' => true,
            'id' => $statut->getId(),
            'designation' => $statut->getDesignation()
        ]);
    }

    #[Route('/{id}', name: 'app_statuts_show', methods: ['GET'])]
    public function show(Statuts $statut): Response
    {
        return $this->render('statuts/show.html.twig', [
            'statut' => $statut,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_statuts_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Statuts $statut, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(StatutsForm::class, $statut);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_statuts_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('statuts/edit.html.twig', [
            'statut' => $statut,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_statuts_delete', methods: ['POST'])]
    public function delete(Request $request, Statuts $statut, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $statut->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($statut);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_statuts_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/create/{label}', name: 'app_statuts_create', methods: ['POST'])]
    public function ajoutAjax(string $label, Request $request, EntityManagerInterface $entityManager): Response
    {
        $statut = new Statuts();
        $statut->setDesignation(trim(strip_tags($label)));
        $entityManager->persist($statut);
        $entityManager->flush();
        //on récupère l'Id qui a été créé
        $id = $statut->getId();

        return new JsonResponse(['id' => $id]);
    }

    #[Route('/search', name: 'api_statuts_search', methods: ['GET'])]
    public function searchStatuts(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $term = $request->query->get('term');
        $results = $em->getRepository(Statuts::class)
            ->createQueryBuilder('n')
            ->where('n.designation LIKE :term')
            ->setParameter('term', '%' . $term . '%')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();

        return $this->json(array_map(fn($n) => [
            'id' => $n->getId(),
            'text' => $n->getDesignation()
        ], $results));
    }
}
