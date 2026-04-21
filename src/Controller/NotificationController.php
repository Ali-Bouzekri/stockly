<?php

namespace App\Controller;

use App\Repository\NotificationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/notifications')]
class NotificationController extends AbstractController
{
    #[Route('', name: 'app_notifications')]
    public function index(NotificationRepository $notificationRepo): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        
        $user = $this->getUser();
        if (!$user instanceof \App\Entity\Fonctionnaire) {
            throw new \LogicException('User must be a Fonctionnaire');
        }
        
        $notifications = $notificationRepo->findBy(
            ['recipient' => $user],
            ['createdAt' => 'DESC']
        );
        
        return $this->render('notification/index.html.twig', [
            'notifications' => $notifications,
        ]);
    }

    #[Route('/mark-read/{id}', name: 'app_notification_mark_read', methods: ['POST'])]
    public function markRead(int $id, NotificationRepository $repo, EntityManagerInterface $em): Response
    {
        $notification = $repo->find($id);
        if ($notification && $notification->getRecipient() === $this->getUser()) {
            $notification->setIsRead(true);
            $em->flush();
        }
        return $this->redirectToRoute('app_notifications');
    }

    #[Route('/mark-all-read', name: 'app_notification_mark_all_read', methods: ['POST'])]
    public function markAllRead(NotificationRepository $repo, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        if ($user instanceof \App\Entity\Fonctionnaire) {
            $unread = $repo->findUnreadByUser($user);
            foreach ($unread as $notif) {
                $notif->setIsRead(true);
            }
            $em->flush();
        }
        return $this->redirectToRoute('app_notifications');
    }
}