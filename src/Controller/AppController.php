<?php

namespace App\Controller;

use App\Repository\ProduitRepository;
use App\Repository\CmdInternRepository;
use App\Repository\CmdExternRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AppController extends AbstractController
{
    #[Route('/', name: 'app_dashboard')]
    public function index(
        ProduitRepository   $productRepo,
        CmdInternRepository $internRepo,
        CmdExternRepository $externRepo,
    ): Response {
        $allProduits = $productRepo->findAll();

        $lowStockCount = 0;
        $outStockCount = 0;

        foreach ($allProduits as $p) {
            if ($p->getQteStock() === 0) {
                $outStockCount++;
            } elseif ($p->getQteStock() <= $p->getSeuilAlert()) {
                $lowStockCount++;
            }
        }

        $lowStockAlerts = array_filter($allProduits, fn($p) =>
            $p->getQteStock() > 0 && $p->getQteStock() <= $p->getSeuilAlert()
        );

        $internOrders = array_map(fn($o) => [
            'id'     => 'I-' . $o->getNumero(),
            'date'   => $o->getDateCI(),
            'statut' => $o->getStatut(),
            'type'   => 'Internal',
        ], $internRepo->findBy([], ['numero' => 'DESC'], 10));

        $externOrders = array_map(fn($o) => [
            'id'     => 'E-' . $o->getNumero(),
            'date'   => $o->getDateCE(),
            'statut' => $o->getStatut(),
            'type'   => 'External',
        ], $externRepo->findBy([], ['numero' => 'DESC'], 10));

        $allOrders = array_merge($internOrders, $externOrders);
        usort($allOrders, fn($a, $b) => $b['date'] <=> $a['date']);
        $recentOrders = array_slice($allOrders, 0, 5);

        return $this->render('dashboard.html.twig', [
            'totalProducts'  => count($allProduits),
            'lowStockItems'  => $lowStockCount,
            'lowStockAlerts' => $lowStockAlerts,
            'outOfStock'     => $outStockCount,
            'pendingIntern' => $internRepo->count(['statut' => 'Pending'])
                + $internRepo->count(['statut' => 'Approved']),

            'pendingExtern' => $externRepo->count(['statut' => 'Draft'])
                + $externRepo->count(['statut' => 'Ordered'])
                + $externRepo->count(['statut' => 'Shipped'])
                + $externRepo->count(['statut' => 'Partially Received']),
            'recentOrders'   => $recentOrders,
        ]);
    }
}