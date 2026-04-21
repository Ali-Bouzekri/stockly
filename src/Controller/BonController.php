<?php

namespace App\Controller;

use App\Repository\CmdInternRepository;
use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;
use Knp\Snappy\Pdf;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class BonController extends AbstractController
{
    #[Route('/commande/interne/{id}/bon', name: 'app_cmd_intern_bon')]
    public function generateBon(int $id, CmdInternRepository $repo, Pdf $pdf): Response
    {
        $order = $repo->find($id);
        if (!$order) throw $this->createNotFoundException();
        if (!$order->getReceivedAt()) {
            throw $this->createAccessDeniedException('Bon not available until receipt confirmed.');
        }

        $html = $this->renderView('bon/cmd_intern.html.twig', [
            'commande' => $order,
        ]);

        return new PdfResponse(
            $pdf->getOutputFromHtml($html),
            sprintf('bon-commande-%d.pdf', $order->getNumero())
        );
    }
}