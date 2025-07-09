<?php
// src/Controller/SkillTreeController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SkillTreeController extends AbstractController
{
    #[Route('/skilltree', name: 'skilltree')]
    public function index(): Response
    {
        // You can render a Twig template when you flesh this out.
        // For now, just return a placeholder.
        return $this->render('skilltree/index.html.twig', [
            // pass data as needed
        ]);
    }
}
