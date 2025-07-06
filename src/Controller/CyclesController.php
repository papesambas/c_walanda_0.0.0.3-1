<?php

namespace App\Controller;

use App\Entity\Users;
use App\Entity\Cycles;
use App\Form\CyclesForm;
use Psr\Log\LoggerInterface;
use App\Entity\Enseignements;
use App\Repository\CyclesRepository;
use App\Repository\ElevesRepository;
use App\Repository\NiveauxRepository;
use App\Repository\StatutsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/cycles')]
#[IsGranted('IS_AUTHENTICATED_FULLY')]
final class CyclesController extends AbstractController
{
    public function __construct(private Security $security, private LoggerInterface $logger)
    {
        $this->security = $security;
    }

    #[Route(name: 'app_cycles_index', methods: ['GET'])]
    public function index(CyclesRepository $cyclesRepository): Response
    {

        $user = $user = $this->security->getUser();
        if ($user instanceof Users) {
            $etablissement = $user->getEtablissement();
            $enseignement = $etablissement->getEnseignement();
        } else {
            $enseignement = null; // ou gérer le cas où l'utilisateur n'est pas connecté
        }

        $cycles = $cyclesRepository->findForAll($enseignement);
        return $this->render('cycles/index.html.twig', [
            'cycles' => $cycles,
        ]);
    }

    #[Route('/enseignement/{id}', name: 'app_cycles_enseignement_index', methods: ['GET'])]
    public function indexCycleByEnseignement(Enseignements $enseignement, CyclesRepository $cyclesRepository): Response
    {
        $cycles = $cyclesRepository->findByEnseignementOrdered($enseignement);
        return $this->render('cycles/index.html.twig', [
            'cycles' => $cycles,
            'enseignement' => $enseignement // Ajouté pour avoir le contexte dans le template
        ]);
    }

    #[Route('/new', name: 'app_cycles_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $user = $this->security->getUser();
        if ($user instanceof Users) {
            $etablissement = $user->getEtablissement();
        } else {
            $etablissement = null; // ou gérer le cas où l'utilisateur n'est pas connecté
        }

        $cycle = new Cycles();
        $form = $this->createForm(CyclesForm::class, $cycle);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($cycle);
            $entityManager->flush();

            return $this->redirectToRoute('app_cycles_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('cycles/new.html.twig', [
            'cycle' => $cycle,
            'form' => $form,
        ]);
    }

    #[Route('/create/{label}', name: 'app_cycles_create', methods: ['POST'])]
    public function ajoutAjax(string $label, Request $request, EntityManagerInterface $entityManager): Response
    {
        $cycle = new Cycles();
        $cycle->setDesignation(trim(strip_tags($label)));
        $entityManager->persist($cycle);
        $entityManager->flush();
        //on récupère l'Id qui a été créé
        $id = $cycle->getId();

        return new JsonResponse(['id' => $id]);
    }

    #[Route('/search', name: 'api_cycles_search', methods: ['GET'])]
    public function searchCycles(Request $request, CyclesRepository $cyclesRepository): JsonResponse
    {
        $user = $user = $this->security->getUser();
        if ($user instanceof Users) {
            $etablissement = $user->getEtablissement();
        } else {
            $etablissement = null; // ou gérer le cas où l'utilisateur n'est pas connecté
        }

        $term = $request->query->get('term', '');
        $enseignementId = $request->query->get('enseignement_id');

        if (!$enseignementId) {
            return new JsonResponse([]);
        }

        $cycles = $cyclesRepository->findByEnseignementAndDesignation($enseignementId, $term);

        $results = array_map(function ($cycle) {
            return [
                'id' => $cycle->getId(),
                'text' => $cycle->getDesignation()
            ];
        }, $cycles);

        return new JsonResponse($results);
    }

    #[Route('/{id}', name: 'app_cycles_show', methods: ['GET'])]
    public function show(
        Cycles $cycle,
        Request $request,
        ElevesRepository $elevesRepository,
        NiveauxRepository $niveauxRepository,
        StatutsRepository $statutsRepository
    ): Response {
        // Récupération correcte de l'utilisateur
        $user = $this->security->getUser();

        if ($user instanceof Users) {
            $etablissements = $user->getEtablissement();
        } else {
            $etablissements = null;
        }

        // Redirection avec message si aucun établissement n'est associé
        if ($etablissements === null) {
            $this->addFlash('error', 'Vous n\'avez pas les autorisations nécessaires pour accéder à cette page.');
            return $this->redirectToRoute('app_niveaux_cycle_index', ['id' => $cycle->getId()]);
        }

        // Récupérer les paramètres de filtre
        $fullname = $request->query->get('fullname');
        $niveauId = $request->query->get('niveau');
        $niveauId = is_numeric($niveauId) ? (int) $niveauId : null;
        $statutId = $request->query->get('statut');
        $statutId = is_numeric($statutId) ? (int) $statutId : null;

        // Appliquer les filtres
        $eleves = $elevesRepository->findByFiltersAndCycle($fullname, $cycle, $etablissements, $niveauId, $statutId);

        return $this->render('cycles/show.html.twig', [
            'cycle' => $cycle, // Ajouté pour avoir le contexte dans le template
            'eleves' => $eleves,
            'niveaux' => $niveauxRepository->findBy(['cycle'=>$cycle]),
            'statuts' => $statutsRepository->findAll(),
        ]);
    }

    #[Route('/{id}/edit', name: 'app_cycles_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Cycles $cycle, EntityManagerInterface $entityManager): Response
    {
        $user = $user = $this->security->getUser();
        if ($user instanceof Users) {
            $etablissement = $user->getEtablissement();
        } else {
            $etablissement = null; // ou gérer le cas où l'utilisateur n'est pas connecté
        }

        $form = $this->createForm(CyclesForm::class, $cycle);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_cycles_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('cycles/edit.html.twig', [
            'cycle' => $cycle,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_cycles_delete', methods: ['POST'])]
    public function delete(Request $request, Cycles $cycle, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $cycle->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($cycle);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_cycles_index', [], Response::HTTP_SEE_OTHER);
    }
}
