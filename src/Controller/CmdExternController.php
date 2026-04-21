<?php

namespace App\Controller;

use App\Entity\CmdExtern;
use App\Entity\LigneCmdExtern;
use App\Repository\CmdExternRepository;
use App\Repository\FournisseurRepository;
use App\Repository\LigneCmdExternRepository;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/commande/externe')]
final class CmdExternController extends AbstractController
{
    #[Route('', name: 'app_cmd_extern_index', methods: ['GET'])]
    public function index(CmdExternRepository $repo): Response
    {
        return $this->render('cmd_extern/index.html.twig', [
            'commandes' => $repo->findBy([], ['numero' => 'DESC']),
        ]);
    }

    #[Route('/new', name: 'app_cmd_extern_new', methods: ['GET', 'POST'])]
    public function new(
        Request                $request,
        EntityManagerInterface $em,
        CmdExternRepository    $repo,
        ProduitRepository      $produitRepo,
        FournisseurRepository  $fournisseurRepo,
    ): Response {
         if (!$this->isGranted('ROLE_WAREHOUSE')) {
            $this->addFlash('error', 'You do not have permission to create external orders. Only warehouse staff can place supplier orders.');
            return $this->redirectToRoute('app_cmd_extern_index');
        }
        if ($request->isMethod('POST')) {
            $cmd = new CmdExtern();
            $cmd->setDateCE(new \DateTime());
            $cmd->setStatut('Draft');

            $lastNumero = $repo->findMaxNumero();
            $cmd->setNumero($lastNumero + 1);
            $fournisseurId = $request->request->get('fournisseur');
            if ($fournisseurId) {
                $fournisseur = $fournisseurRepo->find((int) $fournisseurId);
                $cmd->setFournisseur($fournisseur);
            }

            $em->persist($cmd);

            $lignes   = $request->request->all('produits');
            $hasLines = false;

            foreach ($lignes as $row) {
                $produitId = (int) ($row['id'] ?? 0);
                $quantite  = (int) ($row['quantite'] ?? 0);

                if ($produitId <= 0 || $quantite <= 0) continue;

                $produit = $produitRepo->find($produitId);
                if (!$produit) continue;

                $ligne = new LigneCmdExtern();
                $ligne->setCmdExtern($cmd);
                $ligne->setProduit($produit);
                $ligne->setQuantite($quantite);
                $em->persist($ligne);
                $hasLines = true;
            }

            if (!$hasLines) {
                if ($request->headers->get('X-Requested-With') === 'XMLHttpRequest') {
                    return new JsonResponse(['success' => false, 'error' => 'At least one product line is required.']);
                }
                $this->addFlash('error', 'At least one product line is required.');
                return $this->redirectToRoute('app_cmd_extern_new');
            }

            $em->flush();

            if ($request->headers->get('X-Requested-With') === 'XMLHttpRequest') {
                return new JsonResponse(['success' => true, 'id' => $cmd->getId()]);
            }

            $this->addFlash('success', 'External order created successfully.');
            return $this->redirectToRoute('app_cmd_extern_index');
        }

        return $this->render('cmd_extern/new.html.twig', [
            'fournisseurs' => $fournisseurRepo->findBy([], ['denominateur' => 'ASC']),
            'produits'     => $produitRepo->findBy([], ['designation' => 'ASC']),
            'preselectedId'   => $request->query->get('produit'),
        ]);
    }

    #[Route('/{id}', name: 'app_cmd_extern_show', methods: ['GET'])]
    public function show(
        int                      $id,
        CmdExternRepository      $repo,
        LigneCmdExternRepository $ligneRepo,
    ): Response {
        $cmd = $repo->find($id);
        if (!$cmd) {
            throw $this->createNotFoundException('Order not found.');
        }

        return $this->render('cmd_extern/show.html.twig', [
            'commande' => $cmd,
            'lignes'   => $ligneRepo->findBy(['cmdExtern' => $cmd]),
        ]);
    }

    // ✅ Stock is updated here when status becomes "Réceptionnée"
    #[Route('/{id}/status', name: 'app_cmd_extern_status', methods: ['POST'])]
    public function updateStatus(
        int                      $id,
        Request                  $request,
        CmdExternRepository      $repo,
        LigneCmdExternRepository $ligneRepo,
        EntityManagerInterface   $em,
    ): JsonResponse {
        $cmd = $repo->find($id);
        if (!$cmd) {
            return new JsonResponse(['success' => false, 'error' => 'Not found'], 404);
        }

        $allowed = ['Draft', 'Ordered', 'Shipped', 'Partially Received', 'Received', 'Cancelled'];
        $newStatus = $request->request->get('statut');

        if (!in_array($newStatus, $allowed, true)) {
            return new JsonResponse(['success' => false, 'error' => 'Invalid status']);
        }

        // ✅ When fully received → add quantities to each product's stock
        if ($newStatus === 'Received') {
            $lignes = $ligneRepo->findBy(['cmdExtern' => $cmd]);
            foreach ($lignes as $ligne) {
                $produit = $ligne->getProduit();
                $produit->setQteStock($produit->getQteStock() + $ligne->getQuantite());
            }
        }

        $cmd->setStatut($newStatus);
        $em->flush();

        return new JsonResponse(['success' => true, 'statut' => $newStatus]);
    }

    #[Route('/{id}/delete', name: 'app_cmd_extern_delete', methods: ['POST'])]
public function delete(
    int                      $id,
    Request                  $request,
    CmdExternRepository      $repo,
    LigneCmdExternRepository $ligneRepo,
    EntityManagerInterface   $em,
): Response {
    $cmd = $repo->find($id);
    if (!$cmd) {
        throw $this->createNotFoundException();
    }

    if ($this->isCsrfTokenValid('delete' . $id, $request->request->get('_token'))) {
        // Delete lines first
        $lignes = $ligneRepo->findBy(['cmdExtern' => $cmd]);
        foreach ($lignes as $ligne) {
            $em->remove($ligne);
        }

        // ✅ Reindex all orders that come after this one
        $allOrders = $repo->findAll();
        foreach ($allOrders as $order) {
            if ($order->getNumero() > $cmd->getNumero()) {
                $order->setNumero($order->getNumero() - 1);
            }
        }

        $em->remove($cmd);
        $em->flush();
    }

    return $this->redirectToRoute('app_cmd_extern_index');
}
}