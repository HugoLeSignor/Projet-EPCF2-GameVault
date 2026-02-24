<?php

namespace App\Controller\Admin;

use App\Entity\ContactMessage;
use App\Repository\ContactMessageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin/messages')]
#[IsGranted('ROLE_ADMIN')]
class ContactMessageController extends AbstractController
{
    #[Route('', name: 'admin_contact_index')]
    public function index(ContactMessageRepository $repo): Response
    {
        $messages = $repo->findBy([], ['createdAt' => 'DESC']);

        return $this->render('admin/contact/index.html.twig', [
            'messages' => $messages,
            'unreadCount' => $repo->countUnread(),
        ]);
    }

    #[Route('/{id}', name: 'admin_contact_show', methods: ['GET'])]
    public function show(ContactMessage $message, EntityManagerInterface $em): Response
    {
        if (!$message->isRead()) {
            $message->setIsRead(true);
            $em->flush();
        }

        return $this->render('admin/contact/show.html.twig', [
            'message' => $message,
        ]);
    }

    #[Route('/{id}/delete', name: 'admin_contact_delete', methods: ['POST'])]
    public function delete(
        ContactMessage $message,
        Request $request,
        EntityManagerInterface $em,
    ): Response {
        if ($this->isCsrfTokenValid('delete' . $message->getId(), $request->getPayload()->getString('_token'))) {
            $em->remove($message);
            $em->flush();
            $this->addFlash('success', 'Message supprimé.');
        }

        return $this->redirectToRoute('admin_contact_index');
    }
}
