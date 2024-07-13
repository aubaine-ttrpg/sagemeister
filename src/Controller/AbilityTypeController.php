<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/ability-type', name: 'ability_type_')]
class AbilityTypeController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(): Response
    {
        return $this->render('ability_type/index.html.twig', [
            'controller_name' => 'AbilityTypeController',
        ]);
    }

}
