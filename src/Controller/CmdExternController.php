<?php

namespace App\Controller;

use App\Entity\CmdExtern;
use App\Form\CmdExternType;
use App\Repository\CmdExternRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/cmd/extern')]
final class CmdExternController extends AbstractController
{
    #[Route(name: 'app_cmd_extern_index', methods: ['GET'])]
    public function index(CmdExternRepository $cmdExternRepository): Response
    {
        return $this->render('cmd_extern/index.html.twig', [
            'cmd_externs' => $cmdExternRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_cmd_extern_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $cmdExtern = new CmdExtern();
        $form = $this->createForm(CmdExternType::class, $cmdExtern);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($cmdExtern);
            $entityManager->flush();

            return $this->redirectToRoute('app_cmd_extern_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('cmd_extern/new.html.twig', [
            'cmd_extern' => $cmdExtern,
            'form' => $form,
        ]);
    }

    #[Route('/{idCmdExt}', name: 'app_cmd_extern_show', methods: ['GET'])]
    public function show(CmdExtern $cmdExtern): Response
    {
        return $this->render('cmd_extern/show.html.twig', [
            'cmd_extern' => $cmdExtern,
        ]);
    }

    #[Route('/{idCmdExt}/edit', name: 'app_cmd_extern_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, CmdExtern $cmdExtern, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CmdExternType::class, $cmdExtern);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_cmd_extern_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('cmd_extern/edit.html.twig', [
            'cmd_extern' => $cmdExtern,
            'form' => $form,
        ]);
    }

    #[Route('/{idCmdExt}', name: 'app_cmd_extern_delete', methods: ['POST'])]
    public function delete(Request $request, CmdExtern $cmdExtern, EntityManagerInterface $entityManager): Response
    {
         
            if ($this->isCsrfTokenValid('delete'.$cmdExtern->getId(), $request->getPayload()->getString('_token'))){
            $entityManager->remove($cmdExtern);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_cmd_extern_index', [], Response::HTTP_SEE_OTHER);
    }
}
