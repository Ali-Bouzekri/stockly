<?php

namespace App\Controller;

use App\Entity\LigneLivIntern;
use App\Form\LigneLivInternType;
use App\Repository\LigneLivInternRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/ligne/liv/intern')]
final class LigneLivInternController extends AbstractController
{
    #[Route(name: 'app_ligne_liv_intern_index', methods: ['GET'])]
    public function index(LigneLivInternRepository $ligneLivInternRepository): Response
    {
        return $this->render('ligne_liv_intern/index.html.twig', [
            'ligne_liv_interns' => $ligneLivInternRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_ligne_liv_intern_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $ligneLivIntern = new LigneLivIntern();
        $form = $this->createForm(LigneLivInternType::class, $ligneLivIntern);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($ligneLivIntern);
            $entityManager->flush();

            return $this->redirectToRoute('app_ligne_liv_intern_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('ligne_liv_intern/new.html.twig', [
            'ligne_liv_intern' => $ligneLivIntern,
            'form' => $form,
        ]);
    }

    #[Route('/{numL_LI}', name: 'app_ligne_liv_intern_show', methods: ['GET'])]
    public function show(LigneLivIntern $ligneLivIntern): Response
    {
        return $this->render('ligne_liv_intern/show.html.twig', [
            'ligne_liv_intern' => $ligneLivIntern,
        ]);
    }

    #[Route('/{numL_LI}/edit', name: 'app_ligne_liv_intern_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, LigneLivIntern $ligneLivIntern, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(LigneLivInternType::class, $ligneLivIntern);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_ligne_liv_intern_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('ligne_liv_intern/edit.html.twig', [
            'ligne_liv_intern' => $ligneLivIntern,
            'form' => $form,
        ]);
    }

    #[Route('/{numL_LI}', name: 'app_ligne_liv_intern_delete', methods: ['POST'])]
    public function delete(Request $request, LigneLivIntern $ligneLivIntern, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$ligneLivIntern->getNumLLI(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($ligneLivIntern);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_ligne_liv_intern_index', [], Response::HTTP_SEE_OTHER);
    }
}
