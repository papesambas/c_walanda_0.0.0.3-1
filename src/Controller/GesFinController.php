<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class GesFinController extends AbstractController
{
    #[Route('/ges/fin', name: 'app_ges_fin')]
    public function index(): Response
    {
        return $this->render('ges_fin/index.html.twig', [
            'controller_name' => 'GesFinController',
        ]);
    }
}
