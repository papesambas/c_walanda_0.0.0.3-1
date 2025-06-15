<?php

namespace App\Controller;

use App\Entity\Users;
use App\Entity\Scolarites1;
use Psr\Log\LoggerInterface;
use App\Form\Scolarites1Form;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\Scolarites1Repository;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/scolarites1')]
#[IsGranted('IS_AUTHENTICATED_FULLY')]
final class Scolarites1Controller extends AbstractController
{
    public function __construct(private Security $security, private LoggerInterface $logger)
    {
        $this->security = $security;
    }

    #[Route(name: 'app_scolarites1_index', methods: ['GET'])]
    public function index(Scolarites1Repository $scolarites1Repository): Response
    {
        $user = $user = $this->security->getUser();
        if ($user instanceof Users) {
            $etablissement = $user->getEtablissement();
        } else {
            $etablissement = null; // ou gérer le cas où l'utilisateur n'est pas connecté
        }

        return $this->render('scolarites1/index.html.twig', [
            'scolarites1s' => $scolarites1Repository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_scolarites1_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $scolarites1 = new Scolarites1();
        $form = $this->createForm(Scolarites1Form::class, $scolarites1);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($scolarites1);
            $entityManager->flush();

            return $this->redirectToRoute('app_scolarites1_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('scolarites1/new.html.twig', [
            'scolarites1' => $scolarites1,
            'form' => $form,
        ]);
    }

    #[Route('/create/{label}', name: 'app_scolarites1_create', methods: ['POST'])]
    public function ajoutScolarite1(string $label, Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $user = $this->security->getUser();
        if ($user instanceof Users) {
            $etablissement = $user->getEtablissement();
        } else {
            $etablissement = null; // ou gérer le cas où l'utilisateur n'est pas connecté
        }

        $scolarite1 = new Scolarites1();
        $scolarite1->setScolarite(trim(strip_tags($label)));
        $entityManager->persist($scolarite1);
        $entityManager->flush();
        //on récupère l'Id qui a été créé
        $id = $scolarite1->getId();

        return new JsonResponse(['id' => $id]);
    }

    #[Route('/search', name: 'api_scolarites1_search', methods: ['GET'])]
    public function searchScolarite1(Request $request, Scolarites1Repository $scolarites1Repository): JsonResponse
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

        $scolarites1 = $scolarites1Repository->findByNiveauAndScolarite($niveauId, $term);

        $results = array_map(function ($scolarite1) {
            return [
                'id' => $scolarite1->getId(),
                'text' => $scolarite1->getScolarite()
            ];
        }, $scolarites1);

        return new JsonResponse($results);
    }


    #[Route('/{id}', name: 'app_scolarites1_show', methods: ['GET'])]
    public function show(Scolarites1 $scolarites1): Response
    {
        $user = $user = $this->security->getUser();
        if ($user instanceof Users) {
            $etablissement = $user->getEtablissement();
        } else {
            $etablissement = null; // ou gérer le cas où l'utilisateur n'est pas connecté
        }

        return $this->render('scolarites1/show.html.twig', [
            'scolarites1' => $scolarites1,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_scolarites1_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Scolarites1 $scolarites1, EntityManagerInterface $entityManager): Response
    {
        $user = $user = $this->security->getUser();
        if ($user instanceof Users) {
            $etablissement = $user->getEtablissement();
        } else {
            $etablissement = null; // ou gérer le cas où l'utilisateur n'est pas connecté
        }

        $form = $this->createForm(Scolarites1Form::class, $scolarites1);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_scolarites1_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('scolarites1/edit.html.twig', [
            'scolarites1' => $scolarites1,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_scolarites1_delete', methods: ['POST'])]
    public function delete(Request $request, Scolarites1 $scolarites1, EntityManagerInterface $entityManager): Response
    {
        $user = $user = $this->security->getUser();
        if ($user instanceof Users) {
            $etablissement = $user->getEtablissement();
        } else {
            $etablissement = null; // ou gérer le cas où l'utilisateur n'est pas connecté
        }

        if ($this->isCsrfTokenValid('delete' . $scolarites1->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($scolarites1);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_scolarites1_index', [], Response::HTTP_SEE_OTHER);
    }
}
