<?php

namespace App\Controller;

use App\Repository\ProduitRepository;
use App\Repository\CmdInternRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AppController extends AbstractController
{
    #[Route('/', name: 'app_dashboard')]
    public function index(ProduitRepository $productRepo, CmdInternRepository $orderRepo): Response
    {
        $lowStockProducts = $productRepo->findByLowStock(5); 

        return $this->render('dashboard.html.twig', [
            'totalProducts' => $productRepo->count([]),
            'lowStockItems' => count($lowStockProducts),
            'lowStockAlerts' => $lowStockProducts, 
            'totalValue' => 0,
            //'totalValue' => $productRepo->getTotalInventoryValue(),
            'pendingOrders' => $orderRepo->count(['statut' => 'En attente']),
            'recentOrders'  => $orderRepo->findBy([], ['idCmdInt' => 'DESC'], 5),
        ]);
    }
}