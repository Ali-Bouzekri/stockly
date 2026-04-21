<?php

namespace App\Controller;

use App\Repository\CmdExternRepository;
use App\Repository\CmdInternRepository;
use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/rapports')]
final class ReportController extends AbstractController
{
    #[Route('', name: 'app_report_index', methods: ['GET'])]
    public function index(
        ProduitRepository    $produitRepo,
        CmdInternRepository  $internRepo,
        CmdExternRepository  $externRepo,
    ): Response {
        // ── Stock summary ───────────────────────────────────────────────────
        $allProduits = $produitRepo->findAll();
        $totalProduits = count($allProduits);

        $inStock  = 0;
        $lowStock = 0;
        $outStock = 0;

        foreach ($allProduits as $p) {
            if ($p->getQteStock() === 0) {
                $outStock++;
            } elseif ($p->getQteStock() <= $p->getSeuilAlert()) {
                $lowStock++;
            } else {
                $inStock++;
            }
        }

        // ── Order counts by status ──────────────────────────────────────────
        $internOrders = $internRepo->findAll();
        $externOrders = $externRepo->findAll();

        $internByStatus = [];
        foreach ($internOrders as $o) {
            $s = $o->getStatut();
            $internByStatus[$s] = ($internByStatus[$s] ?? 0) + 1;
        }

        $externByStatus = [];
        foreach ($externOrders as $o) {
            $s = $o->getStatut();
            $externByStatus[$s] = ($externByStatus[$s] ?? 0) + 1;
        }

        // ── Low stock products (for table) ──────────────────────────────────
        $lowStockProducts = array_filter(
            $allProduits,
            fn($p) => $p->getQteStock() <= $p->getSeuilAlert()
        );

        // ── Recent orders (last 10 of each) ────────────────────────────────
        $recentIntern = $internRepo->findBy([], ['dateCI' => 'DESC'], 10);
        $recentExtern = $externRepo->findBy([], ['dateCE' => 'DESC'], 10);

        return $this->render('report/index.html.twig', [
            'totalProduits'    => $totalProduits,
            'inStock'          => $inStock,
            'lowStock'         => $lowStock,
            'outStock'         => $outStock,
            'internByStatus'   => $internByStatus,
            'externByStatus'   => $externByStatus,
            'lowStockProducts' => $lowStockProducts,
            'recentIntern'     => $recentIntern,
            'recentExtern'     => $recentExtern,
            'totalIntern'      => count($internOrders),
            'totalExtern'      => count($externOrders),
        ]);
    }
}