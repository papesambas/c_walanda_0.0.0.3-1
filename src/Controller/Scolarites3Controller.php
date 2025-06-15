<?php

namespace App\Controller;

use App\Entity\Users;
use App\Entity\Scolarites3;
use Psr\Log\LoggerInterface;
use App\Form\Scolarites3Form;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\Scolarites3Repository;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/scolarites3')]
#[IsGranted('IS_AUTHENTICATED_FULLY')]
final class Scolarites3Controller extends AbstractController
{
    public function __construct(private Security $security, private LoggerInterface $logger)
    {
        $this->security = $security;
    }

    #[Route(name: 'app_scolarites3_index', methods: ['GET'])]
    public function index(Scolarites3Repository $scolarites3Repository): Response
    {
        $user = $user = $this->security->getUser();
        if ($user instanceof Users) {
            $etablissement = $user->getEtablissement();
        } else {
            $etablissement = null; // ou gérer le cas où l'utilisateur n'est pas connecté
        }

        return $this->render('scolarites3/index.html.twig', [
            'scolarites3s' => $scolarites3Repository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_scolarites3_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $scolarites3 = new Scolarites3();
        $form = $this->createForm(Scolarites3Form::class, $scolarites3);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($scolarites3);
            $entityManager->flush();

            return $this->redirectToRoute('app_scolarites3_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('scolarites3/new.html.twig', [
            'scolarites3' => $scolarites3,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_scolarites3_show', methods: ['GET'])]
    public function show(Scolarites3 $scolarites3): Response
    {
        return $this->render('scolarites3/show.html.twig', [
            'scolarites3' => $scolarites3,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_scolarites3_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Scolarites3 $scolarites3, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(Scolarites3Form::class, $scolarites3);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_scolarites3_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('scolarites3/edit.html.twig', [
            'scolarites3' => $scolarites3,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_scolarites3_delete', methods: ['POST'])]
    public function delete(Request $request, Scolarites3 $scolarites3, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $scolarites3->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($scolarites3);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_scolarites3_index', [], Response::HTTP_SEE_OTHER);
    }
}
