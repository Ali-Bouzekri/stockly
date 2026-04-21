<?php

namespace App\Controller;

use App\Entity\Fonctionnaire;
use App\Form\FonctionnaireType;
use App\Repository\FonctionnaireRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/fonctionnaire')]
final class FonctionnaireController extends AbstractController
{
    #[Route(name: 'app_fonctionnaire_index', methods: ['GET'])]
    public function index(FonctionnaireRepository $fonctionnaireRepository): Response
    {
        return $this->render('fonctionnaire/index.html.twig', [
            'fonctionnaires' => $fonctionnaireRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_fonctionnaire_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $fonctionnaire = new Fonctionnaire();
        $form = $this->createForm(FonctionnaireType::class, $fonctionnaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($fonctionnaire);
            $entityManager->flush();

            return $this->redirectToRoute('app_fonctionnaire_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('fonctionnaire/new.html.twig', [
            'fonctionnaire' => $fonctionnaire,
            'form' => $form,
        ]);
    }

    #[Route('/{idFonct}', name: 'app_fonctionnaire_show', methods: ['GET'])]
    public function show(Fonctionnaire $fonctionnaire): Response
    {
        return $this->render('fonctionnaire/show.html.twig', [
            'fonctionnaire' => $fonctionnaire,
        ]);
    }

    #[Route('/{idFonct}/edit', name: 'app_fonctionnaire_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Fonctionnaire $fonctionnaire, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(FonctionnaireType::class, $fonctionnaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_fonctionnaire_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('fonctionnaire/edit.html.twig', [
            'fonctionnaire' => $fonctionnaire,
            'form' => $form,
        ]);
    }

    #[Route('/{idFonct}', name: 'app_fonctionnaire_delete', methods: ['POST'])]
    public function delete(Request $request, Fonctionnaire $fonctionnaire, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$fonctionnaire->getIdFonct(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($fonctionnaire);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_fonctionnaire_index', [], Response::HTTP_SEE_OTHER);
    }
}
