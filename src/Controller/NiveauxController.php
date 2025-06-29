<?php

namespace App\Controller;

use App\Entity\Users;
use App\Entity\Cycles;
use App\Entity\Niveaux;
use App\Form\NiveauxForm;
use App\Repository\ClassesRepository;
use Psr\Log\LoggerInterface;
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

#[Route('/niveaux')]
#[IsGranted('IS_AUTHENTICATED_FULLY')]
final class NiveauxController extends AbstractController
{
    public function __construct(private Security $security, private LoggerInterface $logger)
    {
        $this->security = $security;
    }

    #[Route(name: 'app_niveaux_index', methods: ['GET'])]
    public function index(NiveauxRepository $niveauxRepository): Response
    {
        $user = $user = $this->security->getUser();
        if ($user instanceof Users) {
            $etablissements = $user->getEtablissement();
        } else {
            $etablissements = null; // ou gérer le cas où l'utilisateur n'est pas connecté
        }

        return $this->render('niveaux/index.html.twig', [
            'niveauxes' => $niveauxRepository->findAll(),
        ]);
    }

    #[Route('/cycle/{id}', name: 'app_niveaux_cycle_index', methods: ['GET'])]
    public function indexCycleByEnseignement(Cycles $cycle, NiveauxRepository $niveauxRepository): Response
    {
        $niveaux = $niveauxRepository->findBy(['cycle' => $cycle]);
        return $this->render('niveaux/index.html.twig', [
            'niveauxes' => $niveaux,
            'cycle' => $cycle // Ajouté pour avoir le contexte dans le template
        ]);
    }


    #[Route('/new', name: 'app_niveaux_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $niveau = new Niveaux();
        $form = $this->createForm(NiveauxForm::class, $niveau);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($niveau);
            $entityManager->flush();

            return $this->redirectToRoute('app_home');
        }

        return $this->render('niveaux/new.html.twig', [
            'niveau' => $niveau,
            'form' => $form,
        ]);
    }

    #[Route('/create/{label}', name: 'app_niveaux_create', methods: ['POST'])]
    public function ajoutAjax(string $label, Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $user = $this->security->getUser();
        if ($user instanceof Users) {
            $etablissement = $user->getEtablissement();
        } else {
            $etablissement = null; // ou gérer le cas où l'utilisateur n'est pas connecté
        }

        $niveau = new Niveaux();
        $niveau->setDesignation(trim(strip_tags($label)));
        $entityManager->persist($niveau);
        $entityManager->flush();
        //on récupère l'Id qui a été créé
        $id = $niveau->getId();

        return new JsonResponse(['id' => $id]);
    }

    #[Route('/search', name: 'api_niveaux_search', methods: ['GET'])]
    public function searchNiveaux(Request $request, NiveauxRepository $niveauxRepository): JsonResponse
    {
        $term = $request->query->get('term', '');
        $cycleId = $request->query->get('cycle_id');
        $dateNaissance = $request->query->get('date_naissance'); // Nouveau paramètre

        if (!$cycleId) {
            return new JsonResponse([]);
        }

        // Calculer l'âge si la date de naissance est fournie
        $age = null;
        if ($dateNaissance) {
            try {
                $birthDate = new \DateTime($dateNaissance);
                $today = new \DateTime();
                $interval = $today->diff($birthDate);
                $age = $interval->y;
            } catch (\Exception $e) {
                // Gérer l'erreur si nécessaire
            }
        }

        $niveaux = $niveauxRepository->findByCycleDesignationAndAge(
            $cycleId,
            $term,
            $age
        );

        $results = array_map(function ($niveau) {
            return [
                'id' => $niveau->getId(),
                'text' => $niveau->getDesignation(),
                'age_min' => $niveau->getAgeMin(), // Optionnel
                'age_max' => $niveau->getAgeMax()  // Optionnel
            ];
        }, $niveaux);

        return new JsonResponse($results);
    }

    #[Route('/{id}', name: 'app_niveaux_show', methods: ['GET'])]
    public function show(
        Niveaux $niveau,
        Request $request,
        ElevesRepository $elevesRepository,
        NiveauxRepository $niveauxRepository,
        ClassesRepository $classesRepository,
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
            return $this->redirectToRoute('app_home');
        }

        // Récupérer les paramètres de filtre
        $fullname = $request->query->get('fullname');
        $designation = $request->query->get('designation');
        $niveauId = $request->query->get('niveau');
        $niveauId = is_numeric($niveauId) ? (int) $niveauId : null;
        $classeId = $request->query->get('classe');
        $classeId = is_numeric($classeId) ? (int) $classeId : null;
        $statutId = $request->query->get('statut');
        $statutId = is_numeric($statutId) ? (int) $statutId : null;
        $taux = $request->query->get('taux');

        // Appliquer les filtres
        $classes = $classesRepository->findByFilters($designation, $etablissements, $niveau, $taux);

        return $this->render('niveaux/show.html.twig', [
            'classes' => $classes,
            'etablissements' => $etablissements,
            'niveaux' => $niveau,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_niveaux_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Niveaux $niveau, EntityManagerInterface $entityManager): Response
    {
        $user = $user = $this->security->getUser();
        if ($user instanceof Users) {
            $etablissement = $user->getEtablissement();
        } else {
            $etablissement = null; // ou gérer le cas où l'utilisateur n'est pas connecté
        }

        $form = $this->createForm(NiveauxForm::class, $niveau);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_niveaux_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('niveaux/edit.html.twig', [
            'niveau' => $niveau,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_niveaux_delete', methods: ['POST'])]
    public function delete(Request $request, Niveaux $niveau, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $niveau->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($niveau);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_niveaux_index', [], Response::HTTP_SEE_OTHER);
    }
}
