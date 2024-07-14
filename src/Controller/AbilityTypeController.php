<?php

namespace App\Controller;

use App\Entity\AbilityType;
use App\Form\AbilityTypeType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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

    #[Route('/list', name: 'list')]
    public function list(
        EntityManagerInterface $entityManager,
    ): Response
    {
        $abilityTypes = $entityManager->getRepository(AbilityType::class)->findAll();
        return $this->render('ability_type/list.html.twig', [
            'controller_name' => 'AbilityTypeController',
            'abilityTypes' => $abilityTypes,
        ]);
    }

    #[Route('/new', name: 'new')]
    public function new(
        Request $request,
        EntityManagerInterface $entityManager,
    ): Response {
        $abilityType = new AbilityType();
        $form = $this->createForm(AbilityTypeType::class, $abilityType);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            # Retreive data from the form
            $abilityType = $form->getData();

            # Persist and add to Database
            $entityManager->persist($abilityType);
            $entityManager->flush();

            return $this->redirectToRoute('ability_type_success');
        }

        return $this->render('ability_type/new.html.twig', [
            'controller_name' => 'AbilityTypeController',
            'form' => $form,
        ]);
    }

    #[Route('/success', name: 'success')]
    public function success(): Response
    {
        return $this->render('ability_type/success.html.twig', [
            'controller_name' => 'AbilityTypeController',
        ]);
    }
}
