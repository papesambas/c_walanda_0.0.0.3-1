<?php

namespace App\Controller;

use App\Entity\Users;
use Psr\Log\LoggerInterface;
use App\Entity\Redoublements1;
use App\Form\Redoublements1Form;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use App\Repository\Redoublements1Repository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/redoublements1')]
final class Redoublements1Controller extends AbstractController
{
        public function __construct(private Security $security, private LoggerInterface $logger)
    {
        $this->security = $security;
        $this->logger = $logger;
    }

    #[Route(name: 'app_redoublements1_index', methods: ['GET'])]
    public function index(Redoublements1Repository $redoublements1Repository): Response
    {
        return $this->render('redoublements1/index.html.twig', [
            'redoublements1s' => $redoublements1Repository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_redoublements1_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $redoublements1 = new Redoublements1();
        $form = $this->createForm(Redoublements1Form::class, $redoublements1);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($redoublements1);
            $entityManager->flush();

            return $this->redirectToRoute('app_redoublements1_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('redoublements1/new.html.twig', [
            'redoublements1' => $redoublements1,
            'form' => $form,
        ]);
    }

    #[Route('/search', name: 'api_redoublements1_search', methods: ['GET'])]
    public function searchRedoublement1(Request $request, Redoublements1Repository $redoublements1Repository): JsonResponse
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

        if (!$niveauId) {
            return new JsonResponse([]);
        }

        $redoublement1 = $redoublements1Repository->findByNiveauAndScolarite1AndScolarite2($niveauId, $scolarite1Id, $scolarite2Id);

        $results = array_map(function ($redoublement1) {
            return [
                'id' => $redoublement1->getId(),
                'text' => $redoublement1->getDesignation()
            ];
        }, $redoublement1);

        return new JsonResponse($results);
    }

    #[Route('/{id}', name: 'app_redoublements1_show', methods: ['GET'])]
    public function show(Redoublements1 $redoublements1): Response
    {
        return $this->render('redoublements1/show.html.twig', [
            'redoublements1' => $redoublements1,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_redoublements1_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Redoublements1 $redoublements1, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(Redoublements1Form::class, $redoublements1);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_redoublements1_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('redoublements1/edit.html.twig', [
            'redoublements1' => $redoublements1,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_redoublements1_delete', methods: ['POST'])]
    public function delete(Request $request, Redoublements1 $redoublements1, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$redoublements1->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($redoublements1);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_redoublements1_index', [], Response::HTTP_SEE_OTHER);
    }
}
