<?php

namespace App\Controller;

use App\Entity\LigneCmdExtern;
use App\Form\LigneCmdExternType;
use App\Repository\LigneCmdExternRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/ligne/cmd/extern')]
final class LigneCmdExternController extends AbstractController
{
    #[Route(name: 'app_ligne_cmd_extern_index', methods: ['GET'])]
    public function index(LigneCmdExternRepository $ligneCmdExternRepository): Response
    {
        return $this->render('ligne_cmd_extern/index.html.twig', [
            'ligne_cmd_externs' => $ligneCmdExternRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_ligne_cmd_extern_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $ligneCmdExtern = new LigneCmdExtern();
        $form = $this->createForm(LigneCmdExternType::class, $ligneCmdExtern);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($ligneCmdExtern);
            $entityManager->flush();

            return $this->redirectToRoute('app_ligne_cmd_extern_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('ligne_cmd_extern/new.html.twig', [
            'ligne_cmd_extern' => $ligneCmdExtern,
            'form' => $form,
        ]);
    }

    #[Route('/{numL_CE}', name: 'app_ligne_cmd_extern_show', methods: ['GET'])]
    public function show(LigneCmdExtern $ligneCmdExtern): Response
    {
        return $this->render('ligne_cmd_extern/show.html.twig', [
            'ligne_cmd_extern' => $ligneCmdExtern,
        ]);
    }

    #[Route('/{numL_CE}/edit', name: 'app_ligne_cmd_extern_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, LigneCmdExtern $ligneCmdExtern, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(LigneCmdExternType::class, $ligneCmdExtern);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_ligne_cmd_extern_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('ligne_cmd_extern/edit.html.twig', [
            'ligne_cmd_extern' => $ligneCmdExtern,
            'form' => $form,
        ]);
    }

    #[Route('/{numL_CE}', name: 'app_ligne_cmd_extern_delete', methods: ['POST'])]
    public function delete(Request $request, LigneCmdExtern $ligneCmdExtern, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$ligneCmdExtern->getNumL_CE(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($ligneCmdExtern);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_ligne_cmd_extern_index', [], Response::HTTP_SEE_OTHER);
    }
}
