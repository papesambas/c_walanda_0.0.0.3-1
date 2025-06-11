<?php

namespace App\Controller;

use App\Entity\Enseignements;
use App\Form\EnseignementsForm;
use App\Repository\ElevesRepository;
use App\Repository\NiveauxRepository;
use App\Repository\StatutsRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\EnseignementsRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/enseignements')]
final class EnseignementsController extends AbstractController
{
    #[Route(name: 'app_enseignements_index', methods: ['GET'])]
    public function index(EnseignementsRepository $enseignementsRepository): Response
    {
        return $this->render('enseignements/index.html.twig', [
            'enseignements' => $enseignementsRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_enseignements_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $enseignement = new Enseignements();
        $form = $this->createForm(EnseignementsForm::class, $enseignement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($enseignement);
            $entityManager->flush();

            return $this->redirectToRoute('app_enseignements_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('enseignements/new.html.twig', [
            'enseignement' => $enseignement,
            'form' => $form,
        ]);
    }

    #[Route('/create/{label}', name: 'app_enseignements_create', methods: ['POST'])]
    public function ajoutAjax(string $label, Request $request, EntityManagerInterface $entityManager): Response
    {
        $enseignement = new Enseignements();
        $enseignement->setDesignation(trim(strip_tags($label)));
        $entityManager->persist($enseignement);
        $entityManager->flush();
        //on récupère l'Id qui a été créé
        $id = $enseignement->getId();

        return new JsonResponse(['id' => $id]);
    }

    #[Route('/search', name: 'api_enseignements_search', methods: ['GET'])]
    public function searchRegions(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $term = $request->query->get('term');
        $results = $em->getRepository(Enseignements::class)
            ->createQueryBuilder('e')
            ->where('e.designation LIKE :term')
            ->setParameter('term', '%' . $term . '%')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();

        return $this->json(array_map(fn($n) => [
            'id' => $n->getId(),
            'text' => $n->getDesignation()
        ], $results));
    }


    #[Route('/{id}', name: 'app_enseignements_show', methods: ['GET'])]
    public function show(
        Enseignements $enseignement,
        Request $request,
        ElevesRepository $elevesRepository,
        NiveauxRepository $niveauxRepository,
        StatutsRepository $statutsRepository
    ): Response {
        // Récupérer les paramètres de filtre
        $etablissements = null;
        $fullname = $request->query->get('fullname');
        $classeId = $request->query->get('classe');
        $classeId = is_numeric($classeId) ? (int) $classeId : null;
        $niveauId = $request->query->get('niveau');
        $niveauId = is_numeric($niveauId) ? (int) $niveauId : null;

        $statutId = $request->query->get('statut');
        $statutId = is_numeric($statutId) ? (int) $statutId : null;

        // Appliquer les filtres
        $eleves = $elevesRepository->findByFiltersAndEnseignement($fullname, $enseignement, $etablissements, $niveauId, $statutId,);

        return $this->render('enseignements/show.html.twig', [
            'eleves' => $eleves,
            'niveaux' => $niveauxRepository->findAll(),
            'statuts' => $statutsRepository->findAll(),
        ]);
    }

    #[Route('/{id}/edit', name: 'app_enseignements_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Enseignements $enseignement, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(EnseignementsForm::class, $enseignement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_enseignements_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('enseignements/edit.html.twig', [
            'enseignement' => $enseignement,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_enseignements_delete', methods: ['POST'])]
    public function delete(Request $request, Enseignements $enseignement, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $enseignement->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($enseignement);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_enseignements_index', [], Response::HTTP_SEE_OTHER);
    }
}
