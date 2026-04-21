<?php

namespace App\Controller;

use App\Entity\Organigramme;
use App\Form\OrganigrammeType;
use App\Repository\OrganigrammeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/organigramme')]
final class OrganigrammeController extends AbstractController
{
    #[Route(name: 'app_organigramme_index', methods: ['GET'])]
    public function index(OrganigrammeRepository $organigrammeRepository): Response
    {
        return $this->render('organigramme/index.html.twig', [
            'organigrammes' => $organigrammeRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_organigramme_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $organigramme = new Organigramme();
        $form = $this->createForm(OrganigrammeType::class, $organigramme);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($organigramme);
            $entityManager->flush();

            return $this->redirectToRoute('app_organigramme_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('organigramme/new.html.twig', [
            'organigramme' => $organigramme,
            'form' => $form,
        ]);
    }

    #[Route('/{idOrg}', name: 'app_organigramme_show', methods: ['GET'])]
    public function show(Organigramme $organigramme): Response
    {
        return $this->render('organigramme/show.html.twig', [
            'organigramme' => $organigramme,
        ]);
    }

    #[Route('/{idOrg}/edit', name: 'app_organigramme_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Organigramme $organigramme, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(OrganigrammeType::class, $organigramme);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_organigramme_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('organigramme/edit.html.twig', [
            'organigramme' => $organigramme,
            'form' => $form,
        ]);
    }

    #[Route('/{idOrg}', name: 'app_organigramme_delete', methods: ['POST'])]
    public function delete(Request $request, Organigramme $organigramme, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$organigramme->getIdOrg(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($organigramme);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_organigramme_index', [], Response::HTTP_SEE_OTHER);
    }
}
