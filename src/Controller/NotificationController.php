<?php

namespace App\Controller;

use App\Entity\Notification;
use App\Repository\NotificationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/notifications')]
#[IsGranted('ROLE_USER')]
class NotificationController extends AbstractController
{
    #[Route('', name: 'app_notifications', methods: ['GET'])]
    public function index(NotificationRepository $notifRepo): Response
    {
        $notifications = $notifRepo->findByUser($this->getUser());

        return $this->render('notification/index.html.twig', [
            'notifications' => $notifications,
        ]);
    }

    #[Route('/{id}/read', name: 'app_notification_read', methods: ['POST'])]
    public function markRead(
        Notification $notification,
        EntityManagerInterface $em,
    ): Response {
        if ($notification->getRecipient() !== $this->getUser()) {
            throw $this->createAccessDeniedException();
        }

        $notification->setIsRead(true);
        $em->flush();

        return $this->redirectToRoute('app_notifications');
    }

    #[Route('/read-all', name: 'app_notification_read_all', methods: ['POST'])]
    public function markAllRead(
        Request $request,
        NotificationRepository $notifRepo,
    ): Response {
        if (!$this->isCsrfTokenValid('read_all', $request->getPayload()->getString('_token'))) {
            throw $this->createAccessDeniedException();
        }

        $notifRepo->markAllRead($this->getUser());

        $this->addFlash('success', 'Toutes les notifications marquees comme lues.');
        return $this->redirectToRoute('app_notifications');
    }
}
