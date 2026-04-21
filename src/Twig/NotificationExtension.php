<?php

namespace App\Twig;

use App\Entity\Fonctionnaire;
use App\Repository\NotificationRepository;
use Symfony\Bundle\SecurityBundle\Security;
use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;

class NotificationExtension extends AbstractExtension implements GlobalsInterface
{
    public function __construct(
        private NotificationRepository $notificationRepo,
        private Security $security
    ) {}

    public function getGlobals(): array
    {
        return [
            'notificationRepo' => $this->notificationRepo,
        ];
    }
}