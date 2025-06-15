<?php

namespace App\Controller;

use App\Entity\Users;
use Psr\Log\LoggerInterface;
use App\Entity\Etablissements;
use App\Form\EtablissementsForm;
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
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/etablissements')]
#[IsGranted('IS_AUTHENTICATED_FULLY')]
final class EtablissementsController extends AbstractController
{
    public function __construct(private Security $security, private LoggerInterface $logger)
    {
        $this->security = $security;
    }

    #[Route('/', name: 'app_etablissements_index', methods: ['GET'])]
    public function index(EtablissementsRepository $etablissementsRepository): Response
    {
        $user = $user = $this->security->getUser();
        if ($user instanceof Users) {
            $etablissement = $user->getEtablissement();
        } else {
            $etablissement = null; // ou gérer le cas où l'utilisateur n'est pas connecté
        }

        return $this->render('etablissements/index.html.twig', [
            'etablissements' => $etablissementsRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_etablissements_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $etablissement = new Etablissements();
        $form = $this->createForm(EtablissementsForm::class, $etablissement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($etablissement);
            $entityManager->flush();

            return $this->redirectToRoute('app_etablissements_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('etablissements/new.html.twig', [
            'etablissement' => $etablissement,
            'form' => $form,
        ]);
    }

    #[Route('/create/{label}', name: 'app_etablissements_create', methods: ['POST'])]
    public function ajoutAjax(string $label, Request $request, EntityManagerInterface $entityManager): Response
    {
        $etablissement = new Etablissements();
        $etablissement->setDesignation(trim(strip_tags($label)));
        $entityManager->persist($etablissement);
        $entityManager->flush();
        //on récupère l'Id qui a été créé
        $id = $etablissement->getId();

        return new JsonResponse(['id' => $id]);
    }

    #[Route('/search', name: 'api_etablissements_search', methods: ['GET'])]
    public function searchRegions(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $term = $request->query->get('term');
        $results = $em->getRepository(Etablissements::class)
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

    #[Route('/{id}', name: 'app_etablissements_show', methods: ['GET'])]
    public function show(
        Etablissements $etablissement,
        Request $request,
        ClassesRepository $classesRepository,
        EtablissementsRepository $etablissementsRepository,
        NiveauxRepository $niveauxRepository
    ): Response {
        $designation = $request->query->get('designation');
        $etablissementId = $request->query->get('etablissement');
        $niveauId = $request->query->get('niveau');
        $taux = $request->query->get('taux');

        $classes = $classesRepository->findByFiltersAndEtablissement($designation, $etablissement, $niveauId, $taux);
        // Récupération des listes pour les filtres
        $etablissements = $etablissementsRepository->findOneBy(['id' => $etablissement->getId()]);
        $niveaux = $niveauxRepository->findAll();

        return $this->render('etablissements/show.html.twig', [
            'classes' => $classes,
            'etablissements' => $etablissements,
            'etablis_Designe' => $etablissement,
            'niveaux' => $niveaux,
        ]);
    }


    #[Route('/{id}/edit', name: 'app_etablissements_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Etablissements $etablissement, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(EtablissementsForm::class, $etablissement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_etablissements_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('etablissements/edit.html.twig', [
            'etablissement' => $etablissement,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_etablissements_delete', methods: ['POST'])]
    public function delete(Request $request, Etablissements $etablissement, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $etablissement->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($etablissement);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_etablissements_index', [], Response::HTTP_SEE_OTHER);
    }
}
