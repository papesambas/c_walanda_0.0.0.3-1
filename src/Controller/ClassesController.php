<?php

namespace App\Controller;

use App\Entity\Users;
use App\Entity\Classes;
use App\Entity\Niveaux;
use App\Form\ClassesForm;
use Psr\Log\LoggerInterface;
use App\Entity\Etablissements;
use App\Repository\ElevesRepository;
use App\Repository\ClassesRepository;
use App\Repository\NiveauxRepository;
use App\Repository\StatutsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use App\Repository\EtablissementsRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/classes')]
final class ClassesController extends AbstractController
{

    public function __construct(private Security $security, private LoggerInterface $logger)
    {
        $this->security = $security;
    }

    #[Route(name: 'app_classes_index', methods: ['GET'])]
    public function index(
        Request $request,
        ClassesRepository $classesRepository,
        EtablissementsRepository $etablissementsRepository,
        NiveauxRepository $niveauxRepository
    ): Response {
        $user = $user = $this->security->getUser();
        if ($user instanceof Users) {
            $etablissement = $user->getEtablissement();
        } else {
            $etablissement = null; // ou gérer le cas où l'utilisateur n'est pas connecté
        }

        $designation = $request->query->get('designation');
        $etablissementId = $request->query->get('etablissement');
        $niveauId = $request->query->get('niveau');
        $taux = $request->query->get('taux');

        $classes = $classesRepository->findByFilters($designation, $etablissementId, $niveauId, $taux);
        // Récupération des listes pour les filtres
        $etablissements = $etablissementsRepository->findAll();
        $niveaux = $niveauxRepository->findAll();

        return $this->render('classes/index.html.twig', [
            'classes' => $classes,
            'etablissements' => $etablissements,
            'niveaux' => $niveaux,
        ]);
    }

    #[Route('/new', name: 'app_classes_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $user = $this->security->getUser();
        if ($user instanceof Users) {
            $etablissement = $user->getEtablissement();
        } else {
            $etablissement = null; // ou gérer le cas où l'utilisateur n'est pas connecté
        }

        $class = new Classes();
        $form = $this->createForm(ClassesForm::class, $class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($class);
            $entityManager->flush();

            return $this->redirectToRoute('app_classes_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('classes/new.html.twig', [
            'class' => $class,
            'form' => $form,
        ]);
    }

    #[Route('/create/{label}', name: 'app_classes_create', methods: ['POST'])]
    public function ajoutClasse(string $label, Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $user = $this->security->getUser();
        if ($user instanceof Users) {
            $etablissement = $user->getEtablissement();
        } else {
            $etablissement = null; // ou gérer le cas où l'utilisateur n'est pas connecté
        }

        $classe = new Classes();
        $classe->setDesignation(trim(strip_tags($label)));
        $entityManager->persist($classe);
        $entityManager->flush();
        //on récupère l'Id qui a été créé
        $id = $classe->getId();

        return new JsonResponse(['id' => $id]);
    }

    #[Route('/search', name: 'api_classes_search', methods: ['GET'])]
    public function searchClasses(Request $request, ClassesRepository $classesRepository): JsonResponse
    {
        $user = $user = $this->security->getUser();
        if ($user instanceof Users) {
            $etablissement = $user->getEtablissement();
        } else {
            $etablissement = null; // ou gérer le cas où l'utilisateur n'est pas connecté
        }

        $term = $request->query->get('term', '');
        $niveauId = $request->query->get('niveau_id');

        if (!$niveauId) {
            return new JsonResponse([]);
        }

        $classes = $classesRepository->findByNiveauAndDesignation($niveauId, $term, $etablissement);

        $results = array_map(function ($classe) {
            return [
                'id' => $classe->getId(),
                'text' => $classe->getDesignation()
            ];
        }, $classes);

        return new JsonResponse($results);
    }

    #[Route('/{id}', name: 'app_classes_show', methods: ['GET'])]
    public function show(
        Classes $classe,
        Request $request,
        ElevesRepository $elevesRepository,
        NiveauxRepository $niveauxRepository,
        StatutsRepository $statutsRepository
    ): Response {

        $user = $user = $this->security->getUser();
        if ($user instanceof Users) {
            $etablissement = $user->getEtablissement();
        } else {
            $etablissement = null; // ou gérer le cas où l'utilisateur n'est pas connecté
        }

        // Récupérer les paramètres de filtre
        $etablissements = null;
        $fullname = $request->query->get('fullname');
        $classeId = $request->query->get('classe');
        $classeId = is_numeric($classeId) ? (int) $classeId : null;
        $niveauId = $classe->getNiveau()->getId();
        $statutId = $request->query->get('statut');
        $statutId = is_numeric($statutId) ? (int) $statutId : null;

        // Appliquer les filtres
        $eleves = $elevesRepository->findByFiltersAndClasse($fullname, $classe,  $etablissements, $niveauId, $statutId,);
        return $this->render('classes/show.html.twig', [
            'classe' => $classe,
            'eleves' => $eleves,
            'statuts' => $statutsRepository->findAll(),
        ]);
    }

    #[Route('/{id}/edit', name: 'app_classes_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Classes $class, EntityManagerInterface $entityManager): Response
    {
        $user = $user = $this->security->getUser();
        if ($user instanceof Users) {
            $etablissement = $user->getEtablissement();
        } else {
            $etablissement = null; // ou gérer le cas où l'utilisateur n'est pas connecté
        }

        $form = $this->createForm(ClassesForm::class, $class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_classes_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('classes/edit.html.twig', [
            'class' => $class,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_classes_delete', methods: ['POST'])]
    public function delete(Request $request, Classes $class, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $class->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($class);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_classes_index', [], Response::HTTP_SEE_OTHER);
    }
}
