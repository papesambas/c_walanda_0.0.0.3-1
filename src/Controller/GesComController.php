<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('IS_AUTHENTICATED_FULLY')]
final class GesComController extends AbstractController
{
    #[Route('/ges/com', name: 'app_ges_com')]
    public function index(): Response
    {
        return $this->render('ges_com/index.html.twig', [
            'controller_name' => 'GesComController',
        ]);
    }
}
