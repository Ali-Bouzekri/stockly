<?php

namespace App\Controller;

use App\Entity\LivIntern;
use App\Form\LivInternType;
use App\Repository\LivInternRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/liv/intern')]
final class LivInternController extends AbstractController
{
    #[Route(name: 'app_liv_intern_index', methods: ['GET'])]
    public function index(LivInternRepository $livInternRepository): Response
    {
        return $this->render('liv_intern/index.html.twig', [
            'liv_interns' => $livInternRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_liv_intern_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $livIntern = new LivIntern();
        $form = $this->createForm(LivInternType::class, $livIntern);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($livIntern);
            $entityManager->flush();

            return $this->redirectToRoute('app_liv_intern_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('liv_intern/new.html.twig', [
            'liv_intern' => $livIntern,
            'form' => $form,
        ]);
    }

    #[Route('/{idLivInt}', name: 'app_liv_intern_show', methods: ['GET'])]
    public function show(LivIntern $livIntern): Response
    {
        return $this->render('liv_intern/show.html.twig', [
            'liv_intern' => $livIntern,
        ]);
    }

    #[Route('/{idLivInt}/edit', name: 'app_liv_intern_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, LivIntern $livIntern, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(LivInternType::class, $livIntern);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_liv_intern_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('liv_intern/edit.html.twig', [
            'liv_intern' => $livIntern,
            'form' => $form,
        ]);
    }

    #[Route('/{idLivInt}', name: 'app_liv_intern_delete', methods: ['POST'])]
    public function delete(Request $request, LivIntern $livIntern, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$livIntern->getIdLivInt(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($livIntern);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_liv_intern_index', [], Response::HTTP_SEE_OTHER);
    }
}
