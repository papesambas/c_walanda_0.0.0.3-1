<?php

namespace App\Controller;

use App\Entity\Users;
use App\Entity\Parents;
use App\Form\ParentsForm;
use App\Data\SearchParentData;
use App\Repository\MeresRepository;
use App\Repository\PeresRepository;
use App\Repository\ElevesRepository;
use App\Service\ParentsCacheService;
use App\Repository\ClassesRepository;
use App\Repository\NiveauxRepository;
use App\Repository\ParentsRepository;
use App\Repository\StatutsRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\DataForm\SearchParentDataType;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/parents')]
#[IsGranted('IS_AUTHENTICATED_FULLY')]
final class ParentsController extends AbstractController
{
    public function __construct(private readonly Security $security) {}

    #[Route(name: 'app_parents_index', methods: ['GET'])]
    public function index(Request $request, ParentsRepository $parentsRepository, PeresRepository $peresRepository, MeresRepository $meresRepository): Response
    {
        // Invalider la cache (au choix)
        // $cacheService->invalidateCache();
        //$cacheService->clearParentsList();
        $user = $user = $this->security->getUser();
        if ($user instanceof Users) {
            $etablissement = $user->getEtablissement();
        } else {
            $etablissement = null; // ou gérer le cas où l'utilisateur n'est pas connecté
        }

        $fullname = trim($request->query->get('fullname', ''));

        // Si 'fullname' est renseigné, on filtre les résultats
        if ($fullname) {
            $parents = $parentsRepository->findByFilters($fullname);
        } else {
            // Si aucun filtre n'est passé, récupère tous les parents
            $parents = $parentsRepository->findAll();
        }

        $data = new SearchParentData();
        $form = $this->createForm(SearchParentDataType::class, $data);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Recherche pour le père
            $peres = $peresRepository->findBySearchParentDataForInscription($data);
            // Recherche pour la mère
            $meres = $meresRepository->findBySearchParentDataForInscription($data);

            if (!empty($peres) && !empty($meres)) {
                // Rechercher un parent existant avec le père et la mère
                $parent = $parentsRepository->findOneByPereAndMere($peres, $meres);
                if ($parent) {
                    // Rediriger vers la création d'un élève avec l'ID du parent
                    //return $this->redirectToRoute('app_eleves_new', [
                    return $this->redirectToRoute('app_eleves_new', [
                        'parent_id' => $parent->getId(),
                    ], Response::HTTP_SEE_OTHER);
                } else {
                    // Aucun parent trouvé, rediriger vers la création d'un parent avec père et mère pré-remplis
                    return $this->redirectToRoute('app_parents_new', [
                        'pere_id' => $peres[0]->getId(),
                        'mere_id' => $meres[0]->getId(),
                    ], Response::HTTP_SEE_OTHER);
                }
            } elseif (!empty($peres) && empty($meres)) {
                // Rediriger vers la création d'un nouveau parent avec le père pré-rempli
                return $this->redirectToRoute('app_parents_new', [
                    'pere_id' => $peres[0]->getId(),
                ], Response::HTTP_SEE_OTHER);
            } elseif (empty($peres) && !empty($meres)) {
                // Rediriger vers la création d'un nouveau parent avec la mère pré-remplie
                return $this->redirectToRoute('app_parents_new', [
                    'mere_id' => $meres[0]->getId(),
                ], Response::HTTP_SEE_OTHER);
            } else {
                // Aucun résultat pour père et mère, rediriger vers la création d'un parent
                $this->addFlash('warning', 'Aucun parent trouvé. Veuillez d\'abord créer un parent.');
                return $this->redirectToRoute('app_parents_new', [], Response::HTTP_SEE_OTHER);
            }
        }

        return $this->render('parents/index.html.twig', [
            'parents' => $parents,
            'dernier_ajout' => $parentsRepository->findAll(),
            'form'    => $form->createView(),
        ]);
    }


    #[Route('/new', name: 'app_parents_new', methods: ['GET', 'POST'])]
    public function new(Request $request,EntityManagerInterface $entityManager,PeresRepository $peresRepository,MeresRepository $meresRepository
    ): Response {
        $parent = new Parents();

        // Pré-remplissage si un ID de père est transmis
        if ($request->query->has('pere_id')) {
            $pere = $peresRepository->find($request->query->get('pere_id'));
            if ($pere) {
                $parent->setPere($pere);
            } else {
                $this->addFlash('error', 'Le père spécifié n\'existe pas.');
                return $this->redirectToRoute('app_parents_index', [], Response::HTTP_SEE_OTHER);
            }
        }

        // Pré-remplissage si un ID de mère est transmis
        if ($request->query->has('mere_id')) {
            $mere = $meresRepository->find($request->query->get('mere_id'));
            if ($mere) {
                $parent->setMere($mere);
            } else {
                $this->addFlash('error', 'La mère spécifiée n\'existe pas.');
                return $this->redirectToRoute('app_parents_index', [], Response::HTTP_SEE_OTHER);
            }
        }

        $form = $this->createForm(ParentsForm::class, $parent);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($parent);
            $entityManager->flush();

            $this->addFlash('success', 'Le parent a été créé avec succès.');
            return $this->redirectToRoute('app_eleves_new', [
                'parent_id' => $parent->getId(),
            ], Response::HTTP_SEE_OTHER);
        }

        return $this->render('parents/new.html.twig', [
            'parent' => $parent,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_parents_show', methods: ['GET'])]
    public function show(
        Parents $parent,
        Request $request,
        ElevesRepository $elevesRepository,
        ClassesRepository $classesRepository,
        StatutsRepository $statutsRepository
    ): Response {
        // Récupérer les paramètres de filtre
        $etablissements = null;
        $fullname = $request->query->get('fullname');
        $classeId = $request->query->get('classe');
        $classeId = is_numeric($classeId) ? (int) $classeId : null;
        $niveauId = $request->query->get('niveau');
        $statutId = $request->query->get('statut');
        $statutId = is_numeric($statutId) ? (int) $statutId : null;

        // Appliquer les filtres
        $eleves = $elevesRepository->findByFiltersAndParent($fullname, $parent,  $etablissements, $classeId, $statutId,);
        $eleveIds = array_map(fn($eleve) => $eleve->getId(), $eleves);

        return $this->render('parents/show.html.twig', [
            'parent' => $parent,
            'eleves' => $eleves,
            'statuts' => $statutsRepository->findAll(),
            'classes'  => $classesRepository->findByEleveIds($eleveIds),
        ]);
    }

    #[Route('/{id}/edit', name: 'app_parents_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Parents $parent, EntityManagerInterface $entityManager, ParentsCacheService $parentsCacheService): Response
    {
        $form = $this->createForm(ParentsForm::class, $parent);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $parentsCacheService->invalidateCache();

            return $this->redirectToRoute('app_parents_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('parents/edit.html.twig', [
            'parent' => $parent,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_parents_delete', methods: ['POST'])]
    public function delete(Request $request, Parents $parent, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $parent->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($parent);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_parents_index', [], Response::HTTP_SEE_OTHER);
    }
}
