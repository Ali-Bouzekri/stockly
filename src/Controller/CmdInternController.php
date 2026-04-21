<?php

namespace App\Controller;

use App\Entity\ApprovalStep;
use App\Entity\CmdIntern;
use App\Entity\Fonctionnaire;
use App\Entity\LigneCmdIntern;
use App\Repository\ApprovalStepRepository;
use App\Repository\CmdInternRepository;
use App\Repository\FonctionnaireRepository;
use App\Repository\LigneCmdInternRepository;
use App\Repository\ProduitRepository;
use App\Service\NotificationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/commande/interne')]
final class CmdInternController extends AbstractController
{
    private $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    // ── List ────────────────────────────────────────────────────────────────
    #[Route('', name: 'app_cmd_intern_index', methods: ['GET'])]
    public function index(CmdInternRepository $repo): Response
    {
        return $this->render('cmd_intern/index.html.twig', [
            'commandes' => $repo->findBy([], ['numero' => 'DESC']),
        ]);
    }

    // ── New order (GET = form / POST = save) ────────────────────────────────
    #[Route('/new', name: 'app_cmd_intern_new', methods: ['GET', 'POST'])]
    public function new(
        Request                 $request,
        EntityManagerInterface  $em,
        ProduitRepository       $produitRepo,
        FonctionnaireRepository $fonctRepo,
        CmdInternRepository     $internRepo,
    ): Response {
        if ($this->isGranted('ROLE_WAREHOUSE')) {
        $this->addFlash('error', 'Warehouse staff cannot create internal orders. Please use External Orders for supplier purchases.');
                return $this->redirectToRoute('app_cmd_intern_index');
            }
            
            $this->denyAccessUnlessGranted('ROLE_USER');
        if ($request->isMethod('POST')) {
            $cmd = new CmdIntern();
            $cmd->setDateCI(new \DateTime());
            $cmd->setStatut('Pending');

            $lastNumero = $internRepo->findMaxNumero();
            $cmd->setNumero($lastNumero + 1);

            $fonctId = $request->request->get('fonctionnaire');
            if ($fonctId) {
                $fonct = $fonctRepo->find((int) $fonctId);
                $cmd->setFonctionnaire($fonct);
            }

            $em->persist($cmd);

            $lignes   = $request->request->all('produits');
            $hasLines = false;

            foreach ($lignes as $row) {
                $produitId = (int) ($row['id']       ?? 0);
                $quantite  = (int) ($row['quantite'] ?? 0);

                if ($produitId <= 0 || $quantite <= 0) {
                    continue;
                }

                $produit = $produitRepo->find($produitId);
                if (!$produit) {
                    continue;
                }

                $ligne = new LigneCmdIntern();
                $ligne->setCmdInt($cmd);
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
                return $this->redirectToRoute('app_cmd_intern_new');
            }

            $em->flush();
            $this->buildApprovalChain($cmd, $em);
            $em->flush(); // persist steps
            if ($request->headers->get('X-Requested-With') === 'XMLHttpRequest') {
                return new JsonResponse(['success' => true, 'id' => $cmd->getIdCmdInt()]);
            }

            $this->addFlash('success', 'Internal order created successfully.');
            return $this->redirectToRoute('app_cmd_intern_index');
        }

        // In CmdInternController::new (GET part)
    return $this->render('cmd_intern/new.html.twig', [
        'currentUser'    => $this->getUser(),  // single Fonctionnaire
        'produits'       => $produitRepo->findBy([], ['designation' => 'ASC']),
    ]);
    }

    // ── Show ────────────────────────────────────────────────────────────────
    #[Route('/{id}', name: 'app_cmd_intern_show', methods: ['GET'])]
    public function show(
        int                      $id,
        CmdInternRepository      $repo,
        LigneCmdInternRepository $ligneRepo,
    ): Response {
        $cmd = $repo->find($id);
        if (!$cmd) {
            throw $this->createNotFoundException('Order not found.');
        }

        return $this->render('cmd_intern/show.html.twig', [
            'commande' => $cmd,
            'lignes'   => $ligneRepo->findBy(['cmdInt' => $cmd]),
        ]);
    }

    // ── Update status (AJAX) ─────────────────────────────────────────────────
    #[Route('/{id}/status', name: 'app_cmd_intern_status', methods: ['POST'])]
public function updateStatus(
    int $id,
    Request $request,
    CmdInternRepository $repo,
    LigneCmdInternRepository $ligneRepo,
    EntityManagerInterface $em,
): JsonResponse {
    $cmd = $repo->find($id);
    if (!$cmd) {
        return new JsonResponse(['success' => false, 'error' => 'Not found'], 404);
    }

    $validStatuses = ['Pending', 'Approved', 'Delivered', 'Received', 'Rejected'];
    $statut = $request->request->get('statut');

    if (!in_array($statut, $validStatuses)) {
        return new JsonResponse(['success' => false, 'error' => 'Invalid status'], 422);
    }

    $cmd->setStatut($statut);
    $em->flush();

    return new JsonResponse(['success' => true]);
}
    // ── Update line quantity (AJAX) ──────────────────────────────────────────
    #[Route('/{id}/ligne/{ligneId}/quantite', name: 'app_cmd_intern_update_qty', methods: ['POST'])]
    public function updateLineQuantity(
        int                      $id,
        int                      $ligneId,
        Request                  $request,
        CmdInternRepository      $repo,
        LigneCmdInternRepository $ligneRepo,
        EntityManagerInterface   $em,
    ): JsonResponse {
        $cmd = $repo->find($id);
        if (!$cmd || $cmd->getStatut() !== 'Pending') {
            return new JsonResponse(['success' => false, 'error' => 'Not allowed'], 403);
        }

        $ligne = $ligneRepo->findOneBy([
            'numL_CI' => $ligneId,
            'cmdInt'  => $cmd,
        ]);
        if (!$ligne) {
            return new JsonResponse(['success' => false, 'error' => 'Line not found'], 404);
        }

        $quantite = (int) $request->request->get('quantite', 1);
        if ($quantite < 1) {
            return new JsonResponse(['success' => false, 'error' => 'Invalid quantity'], 422);
        }

        $ligne->setQuantite($quantite);
        $em->flush();

        return new JsonResponse(['success' => true]);
    }

    // ── Delete ──────────────────────────────────────────────────────────────
    #[Route('/{id}/delete', name: 'app_cmd_intern_delete', methods: ['POST'])]
public function delete(
    int                      $id,
    Request                  $request,
    CmdInternRepository      $repo,
    LigneCmdInternRepository $ligneRepo,
    EntityManagerInterface   $em,
): Response {
    $cmd = $repo->find($id);
    if (!$cmd) {
        throw $this->createNotFoundException();
    }

    if ($this->isCsrfTokenValid('delete' . $id, $request->request->get('_token'))) {
        // ✅ Delete lines first
        $lignes = $ligneRepo->findBy(['cmdInt' => $cmd]);
        foreach ($lignes as $ligne) {
            $em->remove($ligne);
        }

        $em->remove($cmd);
        $em->flush();
    }

    return $this->redirectToRoute('app_cmd_intern_index');
}
private function buildApprovalChain(CmdIntern $order, EntityManagerInterface $em): void
{
    $requester = $order->getFonctionnaire();

    // If no requester or no department — skip approval chain silently
    if (!$requester || !$requester->getOrganigramme()) {
        return;
    }

    $currentDept = $requester->getOrganigramme();
    $chain       = [];
    $stepOrder   = 1;
    $maxDepth    = 10; // prevent infinite loop
    $depth       = 0;

    while ($currentDept && $depth < $maxDepth) {
        $depth++;

        $chef = $em->getRepository(Fonctionnaire::class)->findOneBy([
            'organigramme' => $currentDept,
            'responsable'  => true,
        ]);

        if ($chef && $chef->getIdFonct() !== $requester->getIdFonct() && !$this->isInChain($chain, $chef)) {
            $step = new ApprovalStep();
            $step->setCommande($order);
            $step->setApprover($chef);
            $step->setStepOrder($stepOrder++);
            $step->setStatus('pending');
            $em->persist($step);
            $chain[] = $chef;

            // Stop at final approver
            if (in_array('ROLE_FINAL_APPROVER', $chef->getRoles(), true)) {
                break;
            }
        }

        $currentDept = $currentDept->getParent();
    }

    // Fallback: if chain is empty, find any ROLE_FINAL_APPROVER
    if (empty($chain)) {
        $finalApprovers = $em->getRepository(Fonctionnaire::class)->findAll();
        foreach ($finalApprovers as $f) {
            if (in_array('ROLE_FINAL_APPROVER', $f->getRoles(), true)) {
                $step = new ApprovalStep();
                $step->setCommande($order);
                $step->setApprover($f);
                $step->setStepOrder(1);
                $step->setStatus('pending');
                $em->persist($step);
                $chain[] = $f;
                break;
            }
        }
    }

    // Notify first approver
    if (!empty($chain)) {
        try {
            $this->notificationService->notifyApprover($chain[0], $order);
        } catch (\Exception $e) {
            // Don't fail the order creation if notification fails
        }
    }
}

    private function isInChain(array $chain, Fonctionnaire $chef): bool
    {
        foreach ($chain as $c) {
            if ($c->getIdFonct() === $chef->getIdFonct()) return true;
        }
        return false;
    }

    // ── Approve step ────────────────────────────────────────────────────────
    #[Route('/{id}/approve/{stepId}', name: 'app_cmd_intern_approve', methods: ['POST'])]
public function approveStep(
    int $id,
    int $stepId,
    Request $request,
    CmdInternRepository $repo,
    ApprovalStepRepository $stepRepo,
    EntityManagerInterface $em,
    NotificationService $notifService
): Response {
    $order = $repo->find($id);
    $step = $stepRepo->find($stepId);
    
    if (!$order || !$step || $step->getCommande()->getIdCmdInt() !== $order->getIdCmdInt()) {
        throw $this->createNotFoundException();
    }

    /** @var Fonctionnaire $currentUser */
    $currentUser = $this->getUser();
    
    // Security: only the assigned approver can act
    if ($step->getApprover()->getIdFonct() !== $currentUser->getIdFonct()) {
        throw $this->createAccessDeniedException('You are not authorized to approve this step.');
    }

    $action = $request->request->get('action'); // 'approve' or 'reject'
    $comments = $request->request->get('comments');

    if ($action === 'approve') {
        $step->setStatus('approved');
        $step->setApprovedAt(new \DateTime());
        $em->flush();

        // Find next pending step
        $nextStep = $stepRepo->findOneBy([
            'commande' => $order,
            'status' => 'pending',
        ], ['stepOrder' => 'ASC']);

        if ($nextStep) {
            $notifService->notifyApprover($nextStep->getApprover(), $order);
        } else {
            // All steps approved
            $order->setStatut('Approved');
            $em->flush();
            $notifService->notifyRequester($order, "Your order #{$order->getNumero()} has been fully approved.");
        }
    } elseif ($action === 'reject') {
        $step->setStatus('rejected');
        $step->setComments($comments);
        $order->setStatut('Rejected');
        $em->flush();
        $notifService->notifyRequester($order, "Your order #{$order->getNumero()} was rejected by {$step->getApprover()->getPrenom()}.");
    }

    return $this->redirectToRoute('app_cmd_intern_show', ['id' => $id]);
}
#[Route('/{id}/receive', name: 'app_cmd_intern_receive', methods: ['POST'])]
public function confirmReceipt(
    int                      $id,
    CmdInternRepository      $repo,
    LigneCmdInternRepository $ligneRepo,
    EntityManagerInterface   $em
): Response {
    $order = $repo->find($id);
    if (!$order) {
        throw $this->createNotFoundException();
    }

    /** @var Fonctionnaire $currentUser */
    $currentUser = $this->getUser();

    if ($order->getFonctionnaire()->getIdFonct() !== $currentUser->getIdFonct()) {
        throw $this->createAccessDeniedException('Only the requester can confirm receipt.');
    }

    if ($order->getStatut() !== 'Delivered') {
        $this->addFlash('error', 'Order must be delivered first.');
        return $this->redirectToRoute('app_cmd_intern_show', ['id' => $id]);
    }

    // ✅ Decrease stock
    foreach ($ligneRepo->findBy(['cmdInt' => $order]) as $ligne) {
        $produit = $ligne->getProduit();
        $newStock = $produit->getQteStock() - $ligne->getQuantite();
        $produit->setQteStock(max(0, $newStock));
    }

    // ✅ Set status to Received
    $order->setStatut('Received');
    $order->setReceivedAt(new \DateTime());
    $order->setReceivedBy($currentUser);
    $em->flush();

    $this->addFlash('success', 'Receipt confirmed. Stock updated.');
    return $this->redirectToRoute('app_cmd_intern_show', ['id' => $id]);
}

#[Route('/{id}/bon', name: 'app_cmd_intern_bon', methods: ['GET'])]
public function generateBon(
    int                      $id,
    CmdInternRepository      $repo,
    LigneCmdInternRepository $ligneRepo,
): Response {
    $order = $repo->find($id);
    if (!$order || $order->getStatut() !== 'Received') {
        throw $this->createNotFoundException('Order not found or not yet received.');
    }

    return $this->render('cmd_intern/bon.html.twig', [
        'commande' => $order,
        'lignes'   => $ligneRepo->findBy(['cmdInt' => $order]),
    ]);
}


}