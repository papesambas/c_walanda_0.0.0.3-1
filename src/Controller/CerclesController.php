<?php

namespace App\Controller;

use App\Entity\Users;
use App\Entity\Cercles;
use App\Entity\Regions;
use App\Form\CerclesForm;
use Psr\Log\LoggerInterface;
use App\Repository\ElevesRepository;
use App\Repository\CerclesRepository;
use App\Repository\ClassesRepository;
use App\Repository\RegionsRepository;
use App\Repository\StatutsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/cercles')]
#[IsGranted('IS_AUTHENTICATED_FULLY')]
final class CerclesController extends AbstractController
{
    public function __construct(private Security $security, private LoggerInterface $logger)
    {
        $this->security = $security;
    }

    #[Route('', name: 'app_cercles_index', methods: ['GET'])]
    public function index(CerclesRepository $cerclesRepository): Response
    {
        return $this->render('cercles/index.html.twig', [
            'cercles' => $cerclesRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_cercles_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $cercle = new Cercles();
        $form = $this->createForm(CerclesForm::class, $cercle);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($cercle);
            $entityManager->flush();

            return $this->redirectToRoute('app_cercles_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('cercles/new.html.twig', [
            'cercle' => $cercle,
            'form' => $form,
        ]);
    }

    #[Route('/create', name: 'app_cercles_create', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $em, RegionsRepository $regionsRepository): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!$data || empty($data['designation']) || empty($data['region_id'])) {
            return new JsonResponse(['error' => 'Invalid data'], 400);
        }

        $region = $regionsRepository->find($data['region_id']);
        if (!$region) {
            return new JsonResponse(['error' => 'Region not found'], 404);
        }

        $cercle = new Cercles();
        $cercle->setDesignation($data['designation']);
        $cercle->setRegion($region);

        $em->persist($cercle);
        $em->flush();

        return new JsonResponse([
            'id' => $cercle->getId(),
            'text' => $cercle->getDesignation()
        ], 201);
    }

    #[Route('/search', name: 'app_cercles_search', methods: ['GET'])]
    public function searchCercles(
        Request $request,
        CerclesRepository $cerclesRepository
    ): JsonResponse {
        $term = $request->query->get('term', '');
        $regionId = $request->query->get('region_id');

        if (!$regionId) {
            return new JsonResponse([]);
        }

        $cercles = $cerclesRepository->findByRegionAndDesignation($regionId, $term);

        $results = array_map(function ($cercle) {
            return [
                'id' => $cercle->getId(),
                'text' => $cercle->getDesignation()
            ];
        }, $cercles);

        return new JsonResponse($results);
    }
    #[Route('/region/{id}', name: 'app_cercle_region_index', methods: ['GET'])]
    public function indexCommuneByCercle(Regions $region, CerclesRepository $cerclesRepository): Response
    {
        return $this->render('cercles/index.html.twig', [
            'cercles' => $cerclesRepository->findBy(['region' => $region], ['designation' => 'ASC']),
        ]);
    }

    #[Route('/{id}', name: 'app_cercles_show', methods: ['GET'])]
    public function show(
        Cercles $cercle,
        Request $request,
        ElevesRepository $elevesRepository,
        ClassesRepository $classesRepository,
        StatutsRepository $statutsRepository
    ): Response {
        // Récupérer les paramètres de filtre
        $user = $this->security->getUser();
        if ($user instanceof Users) {
            $etablissements = $user->getEtablissement();
        } else {
            $etablissements = null; // ou gérer le cas où l'utilisateur n'est pas connecté
        }

        // Redirection si aucun établissement n'est associé
        if ($etablissements === null) {
            return $this->redirectToRoute('app_communes_cercle_index', ['id' => $cercle->getId()]);
        }

        $fullname = $request->query->get('fullname');
        $classeId = $request->query->get('classe');
        $classeId = is_numeric($classeId) ? (int) $classeId : null;
        $statutId = $request->query->get('statut');
        $statutId = is_numeric($statutId) ? (int) $statutId : null;

        // Appliquer les filtres
        $eleves = $elevesRepository->findByFiltersAndCercle($fullname,  $cercle, $etablissements, $classeId, $statutId,);
        $eleveIds = array_map(fn($eleve) => $eleve->getId(), $eleves);

        return $this->render('cercles/show.html.twig', [
            'cercle' => $cercle,
            'eleves' => $eleves,
            'classes'=> $classesRepository->findByEtablissement($etablissements),
            'statuts' => $statutsRepository->findAll(),
        ]);
    }

    #[Route('/{id}/edit', name: 'app_cercles_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Cercles $cercle, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CerclesForm::class, $cercle);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_cercles_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('cercles/edit.html.twig', [
            'cercle' => $cercle,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_cercles_delete', methods: ['POST'])]
    public function delete(Request $request, Cercles $cercle, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $cercle->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($cercle);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_cercles_index', [], Response::HTTP_SEE_OTHER);
    }
}
