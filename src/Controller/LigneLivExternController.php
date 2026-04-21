<?php

namespace App\Controller;

use App\Entity\LigneLivExtern;
use App\Form\LigneLivExternType;
use App\Repository\LigneLivExternRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/ligne/liv/extern')]
final class LigneLivExternController extends AbstractController
{
    #[Route(name: 'app_ligne_liv_extern_index', methods: ['GET'])]
    public function index(LigneLivExternRepository $ligneLivExternRepository): Response
    {
        return $this->render('ligne_liv_extern/index.html.twig', [
            'ligne_liv_externs' => $ligneLivExternRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_ligne_liv_extern_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $ligneLivExtern = new LigneLivExtern();
        $form = $this->createForm(LigneLivExternType::class, $ligneLivExtern);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($ligneLivExtern);
            $entityManager->flush();

            return $this->redirectToRoute('app_ligne_liv_extern_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('ligne_liv_extern/new.html.twig', [
            'ligne_liv_extern' => $ligneLivExtern,
            'form' => $form,
        ]);
    }

    #[Route('/{numL_LE}', name: 'app_ligne_liv_extern_show', methods: ['GET'])]
    public function show(LigneLivExtern $ligneLivExtern): Response
    {
        return $this->render('ligne_liv_extern/show.html.twig', [
            'ligne_liv_extern' => $ligneLivExtern,
        ]);
    }

    #[Route('/{numL_LE}/edit', name: 'app_ligne_liv_extern_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, LigneLivExtern $ligneLivExtern, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(LigneLivExternType::class, $ligneLivExtern);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_ligne_liv_extern_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('ligne_liv_extern/edit.html.twig', [
            'ligne_liv_extern' => $ligneLivExtern,
            'form' => $form,
        ]);
    }

    #[Route('/{numL_LE}', name: 'app_ligne_liv_extern_delete', methods: ['POST'])]
    public function delete(Request $request, LigneLivExtern $ligneLivExtern, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$ligneLivExtern->getNumLLE(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($ligneLivExtern);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_ligne_liv_extern_index', [], Response::HTTP_SEE_OTHER);
    }
}
