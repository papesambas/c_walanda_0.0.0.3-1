<?php

namespace App\Controller;

use App\Entity\Users;
use App\Entity\Regions;
use App\Form\RegionsForm;
use Psr\Log\LoggerInterface;
use App\Repository\ElevesRepository;
use App\Repository\ClassesRepository;
use App\Repository\NiveauxRepository;
use App\Repository\RegionsRepository;
use App\Repository\StatutsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/regions')]
#[IsGranted('IS_AUTHENTICATED_FULLY')]
final class RegionsController extends AbstractController
{
    public function __construct(private Security $security, private LoggerInterface $logger)
    {
        $this->security = $security;
    }

    #[Route(name: 'app_regions_index', methods: ['GET'])]
    public function index(RegionsRepository $regionsRepository): Response
    {
        return $this->render('regions/index.html.twig', [
            'regions' => $regionsRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_regions_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $region = new Regions();
        $form = $this->createForm(RegionsForm::class, $region);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($region);
            $entityManager->flush();

            return $this->redirectToRoute('app_regions_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('regions/new.html.twig', [
            'region' => $region,
            'form' => $form,
        ]);
    }

    #[Route('/create/{label}', name: 'app_regions_create', methods: ['POST'])]
    public function ajoutAjax(string $label, Request $request, EntityManagerInterface $entityManager): Response
    {
        $region = new Regions();
        $region->setDesignation(trim(strip_tags($label)));
        $entityManager->persist($region);
        $entityManager->flush();
        //on récupère l'Id qui a été créé
        $id = $region->getId();

        return new JsonResponse(['id' => $id]);
    }

    #[Route('/search', name: 'api_regions_search', methods: ['GET'])]
    public function searchRegions(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $term = $request->query->get('term');
        $results = $em->getRepository(Regions::class)
            ->createQueryBuilder('n')
            ->where('n.designation LIKE :term')
            ->setParameter('term', '%' . $term . '%')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();

        return $this->json(array_map(fn($n) => [
            'id' => $n->getId(),
            'text' => $n->getDesignation()
        ], $results));
    }

    #[Route('/{id}', name: 'app_regions_show', methods: ['GET'])]
    public function show(
        Regions $region,
        Request $request,
        ElevesRepository $elevesRepository,
        NiveauxRepository $niveauxRepository,
        StatutsRepository $statutsRepository,
        ClassesRepository $classesRepository,
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
            return $this->redirectToRoute('app_cercles_index', ['id' => $region->getId()]);
        }

        // Récupérer les paramètres de filtre
        $fullname = $request->query->get('fullname');
        $classeId = $request->query->get('classe');
        $classeId = is_numeric($classeId) ? (int) $classeId : null;
        $statutId = $request->query->get('statut');
        $statutId = is_numeric($statutId) ? (int) $statutId : null;

        // Appliquer les filtres
        $eleves = $elevesRepository->findByFiltersAndRegion($fullname,  $region, $etablissements, $classeId, $statutId,);
        $eleveIds = array_map(fn($eleve) => $eleve->getId(), $eleves);

        return $this->render('regions/show.html.twig', [
            'region' => $region,
            'eleves' => $eleves,
            'classes'=> $classesRepository->findByEtablissement($etablissements),
            'statuts' => $statutsRepository->findAll(),
        ]);

    }

    #[Route('/{id}/edit', name: 'app_regions_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Regions $region, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(RegionsForm::class, $region);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_regions_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('regions/edit.html.twig', [
            'region' => $region,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_regions_delete', methods: ['POST'])]
    public function delete(Request $request, Regions $region, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $region->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($region);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_regions_index', [], Response::HTTP_SEE_OTHER);
    }
}
