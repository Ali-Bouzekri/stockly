<?php

namespace App\Controller;

use App\Entity\LigneCmdIntern;
use App\Form\LigneCmdInternType;
use App\Repository\LigneCmdInternRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/ligne/cmd/intern')]
final class LigneCmdInternController extends AbstractController
{
    #[Route(name: 'app_ligne_cmd_intern_index', methods: ['GET'])]
    public function index(LigneCmdInternRepository $ligneCmdInternRepository): Response
    {
        return $this->render('ligne_cmd_intern/index.html.twig', [
            'ligne_cmd_interns' => $ligneCmdInternRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_ligne_cmd_intern_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $ligneCmdIntern = new LigneCmdIntern();
        $form = $this->createForm(LigneCmdInternType::class, $ligneCmdIntern);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($ligneCmdIntern);
            $entityManager->flush();

            return $this->redirectToRoute('app_ligne_cmd_intern_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('ligne_cmd_intern/new.html.twig', [
            'ligne_cmd_intern' => $ligneCmdIntern,
            'form' => $form,
        ]);
    }

    #[Route('/{numL_CI}', name: 'app_ligne_cmd_intern_show', methods: ['GET'])]
    public function show(LigneCmdIntern $ligneCmdIntern): Response
    {
        return $this->render('ligne_cmd_intern/show.html.twig', [
            'ligne_cmd_intern' => $ligneCmdIntern,
        ]);
    }

    #[Route('/{numL_CI}/edit', name: 'app_ligne_cmd_intern_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, LigneCmdIntern $ligneCmdIntern, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(LigneCmdInternType::class, $ligneCmdIntern);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_ligne_cmd_intern_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('ligne_cmd_intern/edit.html.twig', [
            'ligne_cmd_intern' => $ligneCmdIntern,
            'form' => $form,
        ]);
    }

    #[Route('/{numL_CI}', name: 'app_ligne_cmd_intern_delete', methods: ['POST'])]
    public function delete(Request $request, LigneCmdIntern $ligneCmdIntern, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$ligneCmdIntern->getNumLCI(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($ligneCmdIntern);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_ligne_cmd_intern_index', [], Response::HTTP_SEE_OTHER);
    }
}
