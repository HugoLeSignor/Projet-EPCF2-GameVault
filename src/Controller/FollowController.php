<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\UserFollow;
use App\Repository\UserFollowRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
class FollowController extends AbstractController
{
    #[Route('/users/{id}/follow', name: 'app_user_follow', methods: ['POST'])]
    public function toggle(
        User $targetUser,
        Request $request,
        UserFollowRepository $followRepo,
        EntityManagerInterface $em,
    ): Response {
        if (!$this->isCsrfTokenValid('follow' . $targetUser->getId(), $request->getPayload()->getString('_token'))) {
            throw $this->createAccessDeniedException();
        }

        $currentUser = $this->getUser();

        if ($currentUser === $targetUser) {
            $this->addFlash('warning', 'Vous ne pouvez pas vous suivre vous-meme.');
            return $this->redirectToRoute('app_user_profile', ['id' => $targetUser->getId()]);
        }

        $existing = $followRepo->findOneBy([
            'follower' => $currentUser,
            'following' => $targetUser,
        ]);

        if ($existing) {
            $em->remove($existing);
            $em->flush();
            $this->addFlash('success', 'Vous ne suivez plus ' . $targetUser->getPseudo() . '.');
        } else {
            $follow = new UserFollow();
            $follow->setFollower($currentUser);
            $follow->setFollowing($targetUser);
            $em->persist($follow);
            $em->flush();
            $this->addFlash('success', 'Vous suivez maintenant ' . $targetUser->getPseudo() . '.');
        }

        return $this->redirectToRoute('app_user_profile', ['id' => $targetUser->getId()]);
    }
}
