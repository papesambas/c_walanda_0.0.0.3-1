<?php

namespace App\Controller;

use App\Entity\Noms;
use App\Form\NomsForm;
use App\Repository\NomsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/noms')]
#[IsGranted('IS_AUTHENTICATED_FULLY')]
final class NomsController extends AbstractController
{
    #[Route(name: 'app_noms_index', methods: ['GET'])]
    public function index(NomsRepository $nomsRepository): Response
    {
        return $this->render('noms/index.html.twig', [
            'noms' => $nomsRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_noms_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $nom = new Noms();
        $form = $this->createForm(NomsForm::class, $nom);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($nom);
            $entityManager->flush();

            return $this->redirectToRoute('app_noms_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('noms/new.html.twig', [
            'nom' => $nom,
            'form' => $form,
        ]);
    }

        #[Route('/create/{label}', name: 'app_noms_create', methods: ['POST'])]
    public function ajoutAjax(string $label, Request $request, EntityManagerInterface $entityManager): Response
    {
        $nom = new Noms();
        $nom->setDesignation(trim(strip_tags($label)));
        $entityManager->persist($nom);
        $entityManager->flush();
        //on récupère l'Id qui a été créé
        $id = $nom->getId();

        return new JsonResponse(['id' => $id]);
    }

    #[Route('/search', name: 'api_noms_search', methods: ['GET'])]
    public function searchNoms(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $term = $request->query->get('term');
        $results = $em->getRepository(Noms::class)
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
    
    #[Route('/{id}', name: 'app_noms_show', methods: ['GET'])]
    public function show(Noms $nom): Response
    {
        return $this->render('noms/show.html.twig', [
            'nom' => $nom,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_noms_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Noms $nom, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(NomsForm::class, $nom);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_noms_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('noms/edit.html.twig', [
            'nom' => $nom,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_noms_delete', methods: ['POST'])]
    public function delete(Request $request, Noms $nom, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $nom->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($nom);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_noms_index', [], Response::HTTP_SEE_OTHER);
    }

}
