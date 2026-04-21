<?php

namespace App\Controller;

use App\Entity\CmdIntern;
use App\Form\CmdInternType;
use App\Repository\CmdInternRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/cmd/intern')]
final class CmdInternController extends AbstractController
{
    #[Route(name: 'app_cmd_intern_index', methods: ['GET'])]
    public function index(CmdInternRepository $cmdInternRepository): Response
    {
        return $this->render('cmd_intern/index.html.twig', [
            'cmd_interns' => $cmdInternRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_cmd_intern_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $cmdIntern = new CmdIntern();
        $form = $this->createForm(CmdInternType::class, $cmdIntern);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($cmdIntern);
            $entityManager->flush();

            return $this->redirectToRoute('app_cmd_intern_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('cmd_intern/new.html.twig', [
            'cmd_intern' => $cmdIntern,
            'form' => $form,
        ]);
    }

    #[Route('/{idCmdInt}', name: 'app_cmd_intern_show', methods: ['GET'])]
    public function show(CmdIntern $cmdIntern): Response
    {
        return $this->render('cmd_intern/show.html.twig', [
            'cmd_intern' => $cmdIntern,
        ]);
    }

    #[Route('/{idCmdInt}/edit', name: 'app_cmd_intern_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, CmdIntern $cmdIntern, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CmdInternType::class, $cmdIntern);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_cmd_intern_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('cmd_intern/edit.html.twig', [
            'cmd_intern' => $cmdIntern,
            'form' => $form,
        ]);
    }

    #[Route('/{idCmdInt}', name: 'app_cmd_intern_delete', methods: ['POST'])]
    public function delete(Request $request, CmdIntern $cmdIntern, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$cmdIntern->getIdCmdInt(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($cmdIntern);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_cmd_intern_index', [], Response::HTTP_SEE_OTHER);
    }
}
