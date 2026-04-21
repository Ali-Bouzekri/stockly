<?php

namespace App\Controller;

use App\Entity\Unit;
use App\Form\UnitType;
use App\Repository\UnitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/Unit')]
final class UnitController extends AbstractController
{
    #[Route(name: 'app_unit_index', methods: ['GET'])]
    public function index(UnitRepository $unitRepository): Response
    {
        return $this->render('Unit/index.html.twig', [
            'units' => $unitRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_unit_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $unit = new Unit();
        $form = $this->createForm(UnitType::class, $unit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($unit);
            $entityManager->flush();

            return $this->redirectToRoute('app_unit_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('Unit/new.html.twig', [
            'Unit' => $unit,
            'form' => $form,
        ]);
    }

    #[Route('/{idUnite}', name: 'app_unit_show', methods: ['GET'])]
    public function show(Unit $unit): Response
    {
        return $this->render('Unit/show.html.twig', [
            'Unit' => $unit,
        ]);
    }

    #[Route('/{idUnite}/edit', name: 'app_unit_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Unit $unit, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(UnitType::class, $unit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_unit_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('Unit/edit.html.twig', [
            'Unit' => $unit,
            'form' => $form,
        ]);
    }

    #[Route('/{idUnite}', name: 'app_unit_delete', methods: ['POST'])]
    public function delete(Request $request, Unit $unit, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$unit->getIdUnite(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($unit);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_unit_index', [], Response::HTTP_SEE_OTHER);
    }
}
