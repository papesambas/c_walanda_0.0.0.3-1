<?php

namespace App\Controller;

use App\Entity\Classes;
use App\Entity\Etablissements;
use App\Entity\Niveaux;
use App\Form\ClassesForm;
use App\Repository\ClassesRepository;
use App\Repository\EtablissementsRepository;
use App\Repository\NiveauxRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/classes')]
final class ClassesController extends AbstractController
{
    #[Route(name: 'app_classes_index', methods: ['GET'])]
    public function index(Request $request,ClassesRepository $classesRepository,
     EtablissementsRepository $etablissementsRepository, NiveauxRepository $niveauxRepository): Response
    {
        $designation = $request->query->get('designation');
        $etablissementId = $request->query->get('etablissement');
        $niveauId = $request->query->get('niveau');
        $taux = $request->query->get('taux');

        dump('designation',$designation,'etablissement',$etablissementId,'niveau',$niveauId);

        $classes = $classesRepository->findByFilters($designation, $etablissementId, $niveauId, $taux);
        dump($classes);
        // Récupération des listes pour les filtres
        $etablissements = $etablissementsRepository->findAll();
        $niveaux = $niveauxRepository->findAll();

        return $this->render('classes/index.html.twig', [
            'classes' => $classes,
            'etablissements' => $etablissements,
            'niveaux' => $niveaux,
        ]);
    }

    #[Route('/new', name: 'app_classes_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $class = new Classes();
        $form = $this->createForm(ClassesForm::class, $class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($class);
            $entityManager->flush();

            return $this->redirectToRoute('app_classes_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('classes/new.html.twig', [
            'class' => $class,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_classes_show', methods: ['GET'])]
    public function show(Classes $class): Response
    {
        return $this->render('classes/show.html.twig', [
            'class' => $class,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_classes_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Classes $class, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ClassesForm::class, $class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_classes_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('classes/edit.html.twig', [
            'class' => $class,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_classes_delete', methods: ['POST'])]
    public function delete(Request $request, Classes $class, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $class->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($class);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_classes_index', [], Response::HTTP_SEE_OTHER);
    }
}
