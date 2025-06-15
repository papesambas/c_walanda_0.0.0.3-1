<?php

namespace App\Controller;

use App\Entity\Ninas;
use App\Form\NinasForm;
use App\Repository\NinasRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/ninas')]
#[IsGranted('IS_AUTHENTICATED_FULLY')]
final class NinasController extends AbstractController
{
    #[Route(name: 'app_ninas_index', methods: ['GET'])]
    public function index(NinasRepository $ninasRepository): Response
    {
        return $this->render('ninas/index.html.twig', [
            'ninas' => $ninasRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_ninas_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $nina = new Ninas();
        $form = $this->createForm(NinasForm::class, $nina);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($nina);
            $entityManager->flush();

            return $this->redirectToRoute('app_ninas_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('ninas/new.html.twig', [
            'nina' => $nina,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_ninas_show', methods: ['GET'])]
    public function show(Ninas $nina): Response
    {
        return $this->render('ninas/show.html.twig', [
            'nina' => $nina,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_ninas_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Ninas $nina, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(NinasForm::class, $nina);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_ninas_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('ninas/edit.html.twig', [
            'nina' => $nina,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_ninas_delete', methods: ['POST'])]
    public function delete(Request $request, Ninas $nina, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$nina->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($nina);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_ninas_index', [], Response::HTTP_SEE_OTHER);
    }

        #[Route('/create/{label}', name: 'app_ninas_create', methods: ['POST'])]
    public function ajoutAjax(string $label, Request $request, EntityManagerInterface $entityManager,
    NinasRepository $ninasRepository): Response
    {
        //on vérifie si le label existe déjà
        $nina = $ninasRepository->findOneBy(['numero' => trim(strip_tags($label))]);
        if ($nina) {
            //si le label existe déjà, on retourne l'Id de l'objet Ninas
            $id = $nina->getId();
            return new JsonResponse(['id' => $id]);
        }
        //si le label n'existe pas, on crée un nouvel objet Ninas
        $nina = new Ninas();
        $nina->setNumero(trim(strip_tags($label)));
        $entityManager->persist($nina);
        $entityManager->flush();
        //on récupère l'Id qui a été créé
        $id = $nina->getId();

        return new JsonResponse(['id' => $id]);
    }
}
