<?php

namespace App\Controller;

use App\Entity\Users;
use Psr\Log\LoggerInterface;
use App\Entity\Redoublements2;
use App\Form\Redoublements2Form;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use App\Repository\Redoublements2Repository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/redoublements2')]
final class Redoublements2Controller extends AbstractController
{
    public function __construct(private Security $security, private LoggerInterface $logger)
    {
        $this->security = $security;
        $this->logger = $logger;
    }

    #[Route(name: 'app_redoublements2_index', methods: ['GET'])]
    public function index(Redoublements2Repository $redoublements2Repository): Response
    {
        return $this->render('redoublements2/index.html.twig', [
            'redoublements2s' => $redoublements2Repository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_redoublements2_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $redoublements2 = new Redoublements2();
        $form = $this->createForm(Redoublements2Form::class, $redoublements2);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($redoublements2);
            $entityManager->flush();

            return $this->redirectToRoute('app_redoublements2_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('redoublements2/new.html.twig', [
            'redoublements2' => $redoublements2,
            'form' => $form,
        ]);
    }

    #[Route('/search', name: 'api_redoublements2_search', methods: ['GET'])]
    public function searchRedoublement2(Request $request, Redoublements2Repository $redoublements2Repository): JsonResponse
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
        $redoublement1Id = $request->query->get('redoublement1_id');

        if (!$niveauId) {
            return new JsonResponse([]);
        }

        $redoublement2 = $redoublements2Repository->findByNiveauAndScolarite1AndScolarite2AndRedoublement1($niveauId, $scolarite1Id, $scolarite2Id, $redoublement1Id);

        $results = array_map(function ($redoublement2) {
            return [
                'id' => $redoublement2->getId(),
                'text' => $redoublement2->getDesignation()
            ];
        }, $redoublement2);

        return new JsonResponse($results);
    }


    #[Route('/{id}', name: 'app_redoublements2_show', methods: ['GET'])]
    public function show(Redoublements2 $redoublements2): Response
    {
        return $this->render('redoublements2/show.html.twig', [
            'redoublements2' => $redoublements2,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_redoublements2_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Redoublements2 $redoublements2, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(Redoublements2Form::class, $redoublements2);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_redoublements2_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('redoublements2/edit.html.twig', [
            'redoublements2' => $redoublements2,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_redoublements2_delete', methods: ['POST'])]
    public function delete(Request $request, Redoublements2 $redoublements2, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $redoublements2->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($redoublements2);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_redoublements2_index', [], Response::HTTP_SEE_OTHER);
    }
}
