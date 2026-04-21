<?php

namespace App\Controller;

use App\Entity\Comite;
use App\Form\ComiteType;
use App\Repository\ComiteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/comite')]
final class ComiteController extends AbstractController
{
    #[Route(name: 'app_comite_index', methods: ['GET'])]
    public function index(ComiteRepository $comiteRepository): Response
    {
        return $this->render('comite/index.html.twig', [
            'comites' => $comiteRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_comite_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $comite = new Comite();
        $form = $this->createForm(ComiteType::class, $comite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($comite);
            $entityManager->flush();

            return $this->redirectToRoute('app_comite_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('comite/new.html.twig', [
            'comite' => $comite,
            'form' => $form,
        ]);
    }

    #[Route('/{idComit}', name: 'app_comite_show', methods: ['GET'])]
    public function show(Comite $comite): Response
    {
        return $this->render('comite/show.html.twig', [
            'comite' => $comite,
        ]);
    }

    #[Route('/{idComit}/edit', name: 'app_comite_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Comite $comite, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ComiteType::class, $comite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_comite_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('comite/edit.html.twig', [
            'comite' => $comite,
            'form' => $form,
        ]);
    }

    #[Route('/{idComit}', name: 'app_comite_delete', methods: ['POST'])]
    public function delete(Request $request, Comite $comite, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$comite->getIdComit(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($comite);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_comite_index', [], Response::HTTP_SEE_OTHER);
    }
}
