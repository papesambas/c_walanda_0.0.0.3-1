<?php

namespace App\Controller;

use App\Entity\Users;
use App\Entity\Communes;
use Psr\Log\LoggerInterface;
use App\Entity\LieuNaissances;
use App\Form\LieuNaissancesForm;
use App\Repository\ElevesRepository;
use App\Repository\ClassesRepository;
use App\Repository\StatutsRepository;
use App\Repository\CommunesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use App\Repository\LieuNaissancesRepository;
use App\Repository\NiveauxRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/lieu/naissances')]
#[IsGranted('IS_AUTHENTICATED_FULLY')]
final class LieuNaissancesController extends AbstractController
{
    public function __construct(private Security $security, private LoggerInterface $logger)
    {
        $this->security = $security;
    }

    #[Route(name: 'app_lieu_naissances_index', methods: ['GET'])]
    public function index(LieuNaissancesRepository $lieuNaissancesRepository): Response
    {
        return $this->render('lieu_naissances/index.html.twig', [
            'lieu_naissances' => $lieuNaissancesRepository->findAll(),
        ]);
    }

    #[Route('/commune/{id}', name: 'app_lieu_naissances_communes_index', methods: ['GET'])]
    public function indexlieuByCommune(Communes $commune, LieuNaissancesRepository $lieuNaissancesRepository): Response
    {
        return $this->render('lieu_naissances/index.html.twig', [
            'lieu_naissances' => $lieuNaissancesRepository->findByCommune($commune->getId()),
            'commune' => $commune
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

    #[Route('/create', name: 'app_lieu_naissances_create', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $em, CommunesRepository $communesRepository): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!$data || empty($data['designation']) || empty($data['commune_id'])) {
            return new JsonResponse(['error' => 'Invalid data'], 400);
        }

        $commune = $communesRepository->find($data['commune_id']);
        if (!$commune) {
            return new JsonResponse(['error' => 'Commune not found'], 404);
        }

        $lieu = new LieuNaissances();
        $lieu->setDesignation($data['designation']);
        $lieu->setCommune($commune);

        $em->persist($lieu);
        $em->flush();

        return new JsonResponse([
            'id' => $lieu->getId(),
            'text' => $lieu->getDesignation()
        ], 201);
    }

    #[Route('/search', name: 'app_lieu_naissance_search', methods: ['GET'])]
    public function searchCercles(
        Request $request,
        LieuNaissancesRepository $lieuNaissancesRepository
    ): JsonResponse {
        $term = $request->query->get('term', '');
        $communeId = $request->query->get('commune_id');

        if (!$communeId) {
            return new JsonResponse([]);
        }

        $lieux = $lieuNaissancesRepository->findByCommuneAndDesignation($communeId, $term);

        $results = array_map(function ($lieu) {
            return [
                'id' => $lieu->getId(),
                'text' => $lieu->getDesignation()
            ];
        }, $lieux);

        return new JsonResponse($results);
    }

    #[Route('/{id}', name: 'app_lieu_naissances_show', methods: ['GET'])]
    public function show(
        LieuNaissances $lieuNaissance,
        Request $request,
        ElevesRepository $elevesRepository,
        //NiveauxRepository $niveauxRepository,
        StatutsRepository $statutsRepository,
        ClassesRepository $classesRepository
    ): Response {
        // Récupération correcte de l'utilisateur
        $user = $this->security->getUser();

        if ($user instanceof Users) {
            $etablissements = $user->getEtablissement();
        } else {
            $etablissements = null;
        }

        // Redirection si aucun établissement n'est associé
        if ($etablissements === null) {
            $this->addFlash(
                'warning',
                'Votre compte utilisateur n\'est pas associé à un établissement. 
         Vous ne pouvez pas accéder aux données des élèves.'
            );
            return $this->redirectToRoute('app_home');
        }

        // Récupérer les paramètres de filtre
        $fullname = $request->query->get('fullname');
        $classeId = $request->query->get('classe');
        $classeId = is_numeric($classeId) ? (int) $classeId : null;
        $statutId = $request->query->get('statut');
        $statutId = is_numeric($statutId) ? (int) $statutId : null;

        // Appliquer les filtres (virgule en trop supprimée)
        $eleves = $elevesRepository->findByFiltersAndLieuNaissance($fullname, $lieuNaissance, $etablissements, $classeId, $statutId);
        $eleveIds = array_map(fn($eleve) => $eleve->getId(), $eleves);

        return $this->render('lieu_naissances/show.html.twig', [
            'lieu_naissance' => $lieuNaissance,
            'eleves' => $eleves,
            //'classes' => $classesRepository->findByEleveIds($eleveIds),
            'classes'=> $classesRepository->findByEtablissement($etablissements),
            'statuts' => $statutsRepository->findAll(),
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
        if ($this->isCsrfTokenValid('delete' . $lieuNaissance->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($lieuNaissance);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_lieu_naissances_index', [], Response::HTTP_SEE_OTHER);
    }
}
