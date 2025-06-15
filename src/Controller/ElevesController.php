<?php

namespace App\Controller;

use App\Entity\Users;
use App\Entity\Eleves;
use App\Form\ElevesForm;
use Psr\Log\LoggerInterface;
use App\Repository\ElevesRepository;
use App\Repository\ClassesRepository;
use App\Repository\NiveauxRepository;
use App\Repository\StatutsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Attribute\IsGranted;

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
            'classes'=> $classesRepository->findByEtablissement($etablissement),
            'statuts' => $statutsRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_eleves_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $user = $this->security->getUser();
        if ($user instanceof Users) {
            $etablissement = $user->getEtablissement();
        } else {
            $etablissement = null; // ou gérer le cas où l'utilisateur n'est pas connecté
        }

        $elefe = new Eleves();
        $form = $this->createForm(ElevesForm::class, $elefe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //$entityManager->persist($elefe);
            //$entityManager->flush();

            //return $this->redirectToRoute('app_eleves_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('eleves/new.html.twig', [
            'elefe' => $elefe,
            'form' => $form,
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
    public function edit(Request $request, Eleves $elefe, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ElevesForm::class, $elefe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
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
}
