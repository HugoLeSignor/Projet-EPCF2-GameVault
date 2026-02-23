<?php

namespace App\EventSubscriber;

use App\Repository\NotificationRepository;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Twig\Environment;

class NotificationCountSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly NotificationRepository $notifRepo,
        private readonly Security $security,
        private readonly Environment $twig,
    ) {}

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::CONTROLLER => 'onController',
        ];
    }

    public function onController(ControllerEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return;
        }

        $user = $this->security->getUser();
        if (!$user) {
            return;
        }

        $count = $this->notifRepo->countUnread($user);
        $this->twig->addGlobal('unreadNotifCount', $count);
    }
}
