<?php

namespace App\Controller;

use App\Entity\Users;
use App\Entity\Niveaux;
use App\Entity\Scolarites1;
use App\Entity\Scolarites2;
use Psr\Log\LoggerInterface;
use App\Form\Scolarites2Form;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\Scolarites2Repository;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/scolarites2')]
#[IsGranted('IS_AUTHENTICATED_FULLY')]
final class Scolarites2Controller extends AbstractController
{
    public function __construct(private Security $security, private LoggerInterface $logger)
    {
        $this->security = $security;
    }

    #[Route(name: 'app_scolarites2_index', methods: ['GET'])]
    public function index(Scolarites2Repository $scolarites2Repository): Response
    {
        $user = $user = $this->security->getUser();
        if ($user instanceof Users) {
            $etablissement = $user->getEtablissement();
        } else {
            $etablissement = null; // ou gérer le cas où l'utilisateur n'est pas connecté
        }

        return $this->render('scolarites2/index.html.twig', [
            'scolarites2s' => $scolarites2Repository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_scolarites2_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $scolarites2 = new Scolarites2();
        $form = $this->createForm(Scolarites2Form::class, $scolarites2);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($scolarites2);
            $entityManager->flush();

            return $this->redirectToRoute('app_scolarites2_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('scolarites2/new.html.twig', [
            'scolarites2' => $scolarites2,
            'form' => $form,
        ]);
    }

    #[Route('/create', name: 'app_scolarites2_create', methods: ['POST'])]
    public function ajoutScolarite2(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $user = $this->security->getUser();
        if ($user instanceof Users) {
            $etablissement = $user->getEtablissement();
        } else {
            $etablissement = null; // ou gérer le cas où l'utilisateur n'est pas connecté
        }

        $data = json_decode($request->getContent(), true);
        $scolarite = trim(strip_tags($data['scolarite'] ?? ''));
        $niveauId = $data['niveau_id'] ?? null;
        $scolarite1Id = $data['scolarite1_id'] ?? null;

        // Validation des données
        if (!$scolarite || !$niveauId || !$scolarite1Id) {
            return new JsonResponse(['error' => 'Données manquantes'], 400);
        }

        $niveau = $entityManager->getRepository(Niveaux::class)->find($niveauId);
        $scolarite1 = $entityManager->getRepository(Scolarites1::class)->find($scolarite1Id);

        if (!$niveau || !$scolarite1) {
            return new JsonResponse(['error' => 'Entités parentes introuvables'], 404);
        }

        $scolarite2 = new Scolarites2();
        $scolarite2->setScolarite($scolarite);
        $scolarite2->setNiveau($niveau);
        $scolarite2->setScolarite1($scolarite1);

        $entityManager->persist($scolarite2);
        $entityManager->flush();

        return new JsonResponse(['id' => $scolarite2->getId(), 'text' => $scolarite]);
    }

    #[Route('/search', name: 'app_scolarites2_search', methods: ['GET'])]
    public function searchScolarite2(Request $request, Scolarites2Repository $scolarites2Repository): JsonResponse
    {
        $user = $user = $this->security->getUser();
        if ($user instanceof Users) {
            $etablissement = $user->getEtablissement();
        } else {
            $etablissement = null; // ou gérer le cas où l'utilisateur n'est pas connecté
        }

        $term = $request->query->get('term', '');
        $niveauId = $request->query->get('niveau_id');
        $scolarite1Id = $request->query->get('scolarite1_id');

        // Vérification des dépendances
        if (!$niveauId || !$scolarite1Id) {
            return new JsonResponse([]);
        }

        $scolarites2 = $scolarites2Repository->findByNiveauAndScolarite($niveauId, $scolarite1Id, $term);
        $results = array_map(function ($scolarite2) {
            return [
                'id' => $scolarite2->getId(),
                'text' => $scolarite2->getScolarite()
            ];
        }, $scolarites2);

        return new JsonResponse($results);
    }


    #[Route('/{id}', name: 'app_scolarites2_show', methods: ['GET'])]
    public function show(Scolarites2 $scolarites2): Response
    {
        return $this->render('scolarites2/show.html.twig', [
            'scolarites2' => $scolarites2,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_scolarites2_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Scolarites2 $scolarites2, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(Scolarites2Form::class, $scolarites2);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_scolarites2_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('scolarites2/edit.html.twig', [
            'scolarites2' => $scolarites2,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_scolarites2_delete', methods: ['POST'])]
    public function delete(Request $request, Scolarites2 $scolarites2, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $scolarites2->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($scolarites2);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_scolarites2_index', [], Response::HTTP_SEE_OTHER);
    }
}
