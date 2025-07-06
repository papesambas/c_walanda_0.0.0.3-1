<?php

namespace App\Controller;

use App\Entity\Users;
use App\Entity\Eleves;
use App\Form\ElevesForm;
use Psr\Log\LoggerInterface;
use App\Entity\DossierEleves;
use App\Repository\ElevesRepository;
use App\Repository\ClassesRepository;
use App\Repository\NiveauxRepository;
use App\Repository\ParentsRepository;
use App\Repository\StatutsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

#[Route('/eleves')]
#[IsGranted('IS_AUTHENTICATED_FULLY')]
final class ElevesController extends AbstractController
{
    public function __construct(private Security $security, private LoggerInterface $logger)
    {
        $this->security = $security;
    }

    #[Route('/', name: 'app_eleves_index', methods: ['GET'])]
    public function index(
        Request $request,
        ElevesRepository $elevesRepository,
        ClassesRepository $classesRepository,
        StatutsRepository $statutsRepository
    ): Response {

        $user = $user = $this->security->getUser();
        if ($user instanceof Users) {
            $etablissement = $user->getEtablissement();
        } else {
            $etablissement = null; // ou gérer le cas où l'utilisateur n'est pas connecté
        }

        // Récupérer les paramètres de filtre
        $fullname = $request->query->get('fullname');
        $classeId = $request->query->get('classe');
        $classeId = is_numeric($classeId) ? (int) $classeId : null;
        $statutId = $request->query->get('statut');
        $statutId = is_numeric($statutId) ? (int) $statutId : null;

        // Appliquer les filtres
        $eleves = $elevesRepository->findByFilters($fullname,  $etablissement, $classeId, $statutId,);

        return $this->render('eleves/index.html.twig', [
            'eleves' => $eleves,
            'classes' => $classesRepository->findByEtablissement($etablissement),
            'statuts' => $statutsRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_eleves_new', methods: ['GET', 'POST'])]
    public function new(
        Request $request,
        EntityManagerInterface $entityManager,
        ParentsRepository $parentsRepository,
        SluggerInterface $slugger
    ): Response {
        // Récupération de l'utilisateur et de son établissement
        $user = $this->security->getUser();
        $etablissement = $user instanceof Users
            ? $user->getEtablissement()
            : null;

        // Parent optionnel
        $parentId = $request->query->get('parent_id');
        $parent = $parentId
            ? $parentsRepository->find($parentId)
            : null;

        $eleve = new Eleves();
        $form = $this->createForm(ElevesForm::class, $eleve);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $documents = $form->get('document')->getData();

            try {
                foreach ($documents as $document) {
                    $originalFilename = pathinfo($document->getClientOriginalName(), PATHINFO_FILENAME);
                    $safeFilename     = $slugger->slug($originalFilename);
                    $newFilename      = $safeFilename . '-' . uniqid() . '.' . $document->guessExtension();

                    // Copier le fichier dans le dossier uploads avec le nom sécurisé
                    $document->move(
                        $this->getParameter('documents_eleves_directory'),
                        $newFilename
                    );

                    // Enregistrer en base
                    $docum = new DossierEleves();
                    $docum->setDesignation($originalFilename);
                    $docum->setSlug($newFilename);
                    $eleve->addDossierElefe($docum);
                }
            } catch (\Exception $e) {
                // Journalisation de l'erreur et message flash
                $this->logger->error('Erreur upload document élève: ' . $e->getMessage());
                $this->addFlash('error', 'Une erreur est survenue lors de l’upload des documents.');
                // Tu peux soit rediriger, soit laisser l'utilisateur corriger
            }

            // Derniers champs avant persist
            $eleve->setEtablissement($etablissement);
            $eleve->setParent($parent);

            $entityManager->persist($eleve);
            $entityManager->flush();

            return $this->redirectToRoute('app_eleves_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('eleves/new.html.twig', [
            'elefe' => $eleve,
            'form'  => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_eleves_show', methods: ['GET'])]
    public function show(Eleves $elefe): Response
    {
        return $this->render('eleves/show.html.twig', [
            'elefe' => $elefe,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_eleves_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Eleves $elefe, EntityManagerInterface $entityManager, SluggerInterface $slugger,): Response
    {
        $form = $this->createForm(ElevesForm::class, $elefe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //try {
            $documents = $form->get('document')->getData();
            //$extrait = $form->get('extrait')->getData();
            foreach ($documents as $document) {
                $originalFilename = pathinfo($document->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $document->guessExtension();
                $fichier = md5(uniqid()) . '.' . $document->guessExtension();

                //On copie le fichier dans le dossier upload
                $document->move(
                    $this->getParameter('documents_eleves_directory'),
                    $originalFilename
                );

                //On stock le nom du document dans la base de donnée
                $docum = new DossierEleves;
                $docum->setDesignation($originalFilename);
                $docum->setSlug($fichier);
                $elefe->addDossierElefe($docum);
            }
            $entityManager->flush();

            return $this->redirectToRoute('app_eleves_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('eleves/edit.html.twig', [
            'elefe' => $elefe,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_eleves_delete', methods: ['POST'])]
    public function delete(Request $request, Eleves $elefe, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $elefe->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($elefe);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_eleves_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/delete/document/{id}', name: 'app_eleve_documents_delete', methods: ['DELETE'])]
    public function deleteDocument(Request $request, DossierEleves $document, EntityManagerInterface $entityManager)
    {
        // Log pour vérifier la réception de la requête
        error_log('Requête DELETE reçue pour le document: ' . $document->getId());

        $data = json_decode($request->getContent(), true);
        //On vérifie si le token est valide
        if ($this->isCsrfTokenValid('delete' . $document->getId(), $data['_token'])) {
            //On récupère le nom du document
            $designation = $document->getDesignation();
            //On supprime de la base
            $entityManager->remove($document);
            $entityManager->flush();

            //On supprime le fichier
            unlink($this->getParameter('documents_eleves_directory') . '/' . $designation);

            // On repond en json
            return new JsonResponse(['success' => 1]);
        } else {
            return new JsonResponse(['error' => "Token Invalide"], 400);
        }
    }
}
