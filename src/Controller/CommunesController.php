<?php

namespace App\Controller;

use App\Entity\Users;
use App\Entity\Cercles;
use App\Entity\Communes;
use App\Form\CommunesForm;
use Psr\Log\LoggerInterface;
use App\Repository\ElevesRepository;
use App\Repository\CerclesRepository;
use App\Repository\ClassesRepository;
use App\Repository\StatutsRepository;
use App\Repository\CommunesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/communes')]
#[IsGranted('IS_AUTHENTICATED_FULLY')]
final class CommunesController extends AbstractController
{
    public function __construct(private Security $security, private LoggerInterface $logger)
    {
        $this->security = $security;
    }

    #[Route('', name: 'app_communes_index', methods: ['GET'])]
    public function index(CommunesRepository $communesRepository): Response
    {
        return $this->render('communes/index.html.twig', [
            'communes' => $communesRepository->findAll(),
        ]);
    }

    #[Route('/cercle/{id}', name: 'app_communes_cercle_index', methods: ['GET'])]
    public function indexCommuneByCercle(Cercles $cercle, CommunesRepository $communesRepository): Response
    {
        return $this->render('communes/index.html.twig', [
            'communes' => $communesRepository->findBy(['cercle' => $cercle], ['designation' => 'ASC']),
        ]);
    }

    #[Route('/new', name: 'app_communes_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $commune = new Communes();
        $form = $this->createForm(CommunesForm::class, $commune);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($commune);
            $entityManager->flush();

            return $this->redirectToRoute('app_communes_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('communes/new.html.twig', [
            'commune' => $commune,
            'form' => $form,
        ]);
    }

    #[Route('/create', name: 'app_communes_create', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $em, CerclesRepository $cerclesRepository): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!$data || empty($data['designation']) || empty($data['cercle_id'])) {
            return new JsonResponse(['error' => 'Invalid data'], 400);
        }

        $cercle = $cerclesRepository->find($data['cercle_id']);
        if (!$cercle) {
            return new JsonResponse(['error' => 'Region not found'], 404);
        }

        $commune = new Communes();
        $commune->setDesignation($data['designation']);
        $commune->setCercle($cercle);

        $em->persist($commune);
        $em->flush();

        return new JsonResponse([
            'id' => $commune->getId(),
            'text' => $commune->getDesignation()
        ], 201);
    }

    #[Route('/search', name: 'app_communes_search', methods: ['GET'])]
    public function searchCercles(
        Request $request,
        CommunesRepository $communesRepository
    ): JsonResponse {
        $term = $request->query->get('term', '');
        $cercleId = $request->query->get('cercle_id');

        if (!$cercleId) {
            return new JsonResponse([]);
        }

        $communes = $communesRepository->findByCercleAndDesignation($cercleId, $term);

        $results = array_map(function ($commune) {
            return [
                'id' => $commune->getId(),
                'text' => $commune->getDesignation()
            ];
        }, $communes);

        return new JsonResponse($results);
    }

    #[Route('/{id}', name: 'app_communes_show', methods: ['GET'])]
    public function show(
        Communes $commune,
        Request $request,
        ElevesRepository $elevesRepository,
        ClassesRepository $classesRepository,
        StatutsRepository $statutsRepository,
        Security $security
    ): Response {

        // Récupération correcte de l'utilisateur
        $user = $security->getUser();

        if ($user instanceof Users) {
            $etablissements = $user->getEtablissement();
        } else {
            $etablissements = null;
        }

        // Redirection si aucun établissement n'est associé
        if ($etablissements === null) {
            return $this->redirectToRoute('app_lieu_naissances_communes_index', ['id' => $commune->getId()]);
        }

        // Récupérer les paramètres de filtre
        $fullname = $request->query->get('fullname');
        $classeId = $request->query->get('classe');
        $classeId = is_numeric($classeId) ? (int) $classeId : null;
        $statutId = $request->query->get('statut');
        $statutId = is_numeric($statutId) ? (int) $statutId : null;

        // Appliquer les filtres (virgule en trop supprimée)
        $eleves = $elevesRepository->findByFiltersAndCommune($fullname, $commune, $etablissements, $classeId, $statutId);
        $eleveIds = array_map(fn($eleve) => $eleve->getId(), $eleves);

        return $this->render('communes/show.html.twig', [
            'commune' => $commune,
            'eleves' => $eleves,
            'classes'=> $classesRepository->findByEtablissement($etablissements),
            'statuts' => $statutsRepository->findAll(),
        ]);
    }

    #[Route('/{id}/edit', name: 'app_communes_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Communes $commune, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CommunesForm::class, $commune);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_communes_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('communes/edit.html.twig', [
            'commune' => $commune,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_communes_delete', methods: ['POST'])]
    public function delete(Request $request, Communes $commune, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $commune->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($commune);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_communes_index', [], Response::HTTP_SEE_OTHER);
    }
}
