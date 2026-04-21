<?php

namespace App\Service;

use App\Entity\Fonctionnaire;
use App\Entity\Notification;
use App\Entity\CmdIntern;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class NotificationService
{
    public function __construct(
        private EntityManagerInterface $em,
        private MailerInterface $mailer,
        private string $adminEmail
    ) {}

    public function notifyApprover(Fonctionnaire $approver, CmdIntern $order): void
    {
        // In-app notification
        $notif = new Notification();
        $notif->setRecipient($approver);
        $notif->setMessage(sprintf(
            'Order #%d from %s %s requires your approval.',
            $order->getNumero(),
            $order->getFonctionnaire()->getPrenom(),
            $order->getFonctionnaire()->getNom()
        ));
        $notif->setLink('/commande/interne/' . $order->getIdCmdInt());
        $this->em->persist($notif);

        // Email notification
        $email = (new Email())
            ->from($this->adminEmail)
            ->to($approver->getEmail())
            ->subject('Approval Required: Order #' . $order->getNumero())
            ->html($this->renderApprovalEmail($order, $approver));
        $this->mailer->send($email);

        $this->em->flush();
    }

    private function renderApprovalEmail(CmdIntern $order, Fonctionnaire $approver): string
    {
        // You can use a Twig template here; for brevity, a simple string.
        return "<p>Order #{$order->getNumero()} from {$order->getFonctionnaire()->getPrenom()} {$order->getFonctionnaire()->getNom()} needs your approval.</p>" .
               "<p><a href='http://localhost/commande/interne/{$order->getIdCmdInt()}'>View Order</a></p>";
    }

    public function notifyRequester(CmdIntern $order, string $message): void
    {
        $notif = new Notification();
        $notif->setRecipient($order->getFonctionnaire());
        $notif->setMessage($message);
        $notif->setLink('/commande/interne/' . $order->getIdCmdInt());
        $this->em->persist($notif);
        $this->em->flush();
    }
}