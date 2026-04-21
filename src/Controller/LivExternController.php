<?php

namespace App\Controller;

use App\Entity\LivExtern;
use App\Form\LivExternType;
use App\Repository\LivExternRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/liv/extern')]
final class LivExternController extends AbstractController
{
    #[Route(name: 'app_liv_extern_index', methods: ['GET'])]
    public function index(LivExternRepository $livExternRepository): Response
    {
        return $this->render('liv_extern/index.html.twig', [
            'liv_externs' => $livExternRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_liv_extern_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $livExtern = new LivExtern();
        $form = $this->createForm(LivExternType::class, $livExtern);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($livExtern);
            $entityManager->flush();

            return $this->redirectToRoute('app_liv_extern_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('liv_extern/new.html.twig', [
            'liv_extern' => $livExtern,
            'form' => $form,
        ]);
    }

    #[Route('/{idLivExt}', name: 'app_liv_extern_show', methods: ['GET'])]
    public function show(LivExtern $livExtern): Response
    {
        return $this->render('liv_extern/show.html.twig', [
            'liv_extern' => $livExtern,
        ]);
    }

    #[Route('/{idLivExt}/edit', name: 'app_liv_extern_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, LivExtern $livExtern, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(LivExternType::class, $livExtern);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_liv_extern_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('liv_extern/edit.html.twig', [
            'liv_extern' => $livExtern,
            'form' => $form,
        ]);
    }

    #[Route('/{idLivExt}', name: 'app_liv_extern_delete', methods: ['POST'])]
    public function delete(Request $request, LivExtern $livExtern, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$livExtern->getIdLivExt(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($livExtern);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_liv_extern_index', [], Response::HTTP_SEE_OTHER);
    }
}
