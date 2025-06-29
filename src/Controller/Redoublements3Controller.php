<?php

namespace App\Controller;

use App\Entity\Users;
use Psr\Log\LoggerInterface;
use App\Entity\Redoublements3;
use App\Form\Redoublements3Form;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use App\Repository\Redoublements3Repository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/redoublements3')]
final class Redoublements3Controller extends AbstractController
{
    public function __construct(private Security $security, private LoggerInterface $logger)
    {
        $this->security = $security;
        $this->logger = $logger;
    }

    #[Route(name: 'app_redoublements3_index', methods: ['GET'])]
    public function index(Redoublements3Repository $redoublements3Repository): Response
    {
        return $this->render('redoublements3/index.html.twig', [
            'redoublements3s' => $redoublements3Repository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_redoublements3_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $redoublements3 = new Redoublements3();
        $form = $this->createForm(Redoublements3Form::class, $redoublements3);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($redoublements3);
            $entityManager->flush();

            return $this->redirectToRoute('app_redoublements3_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('redoublements3/new.html.twig', [
            'redoublements3' => $redoublements3,
            'form' => $form,
        ]);
    }

    #[Route('/search', name: 'api_redoublements3_search', methods: ['GET'])]
    public function searchRedoublement3(Request $request, Redoublements3Repository $redoublements3Repository): JsonResponse
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
        $scolarite2Id = $request->query->get('scolarite2_id');
        $redoublement2Id = $request->query->get('redoublement2_id');

        if (!$niveauId) {
            return new JsonResponse([]);
        }

        $redoublement3 = $redoublements3Repository->findByNiveauAndScolarite1AndScolarite2AndRedoublement2($niveauId, $scolarite1Id, $scolarite2Id, $redoublement2Id);

        $results = array_map(function ($redoublement3) {
            return [
                'id' => $redoublement3->getId(),
                'text' => $redoublement3->getDesignation()
            ];
        }, $redoublement3);

        return new JsonResponse($results);
    }


    #[Route('/{id}', name: 'app_redoublements3_show', methods: ['GET'])]
    public function show(Redoublements3 $redoublements3): Response
    {
        return $this->render('redoublements3/show.html.twig', [
            'redoublements3' => $redoublements3,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_redoublements3_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Redoublements3 $redoublements3, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(Redoublements3Form::class, $redoublements3);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_redoublements3_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('redoublements3/edit.html.twig', [
            'redoublements3' => $redoublements3,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_redoublements3_delete', methods: ['POST'])]
    public function delete(Request $request, Redoublements3 $redoublements3, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $redoublements3->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($redoublements3);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_redoublements3_index', [], Response::HTTP_SEE_OTHER);
    }
}
