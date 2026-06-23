<?php

namespace App\Controller;

use App\Enum\GameStatus;
use App\Form\ProfileType;
use App\Repository\UserFollowRepository;
use App\Repository\UserGameCollectionRepository;
use App\Service\UserStatsService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/profile')]
#[IsGranted('ROLE_USER')]
class ProfileController extends AbstractController
{
    #[Route('', name: 'app_profile', methods: ['GET'])]
    public function index(
        UserStatsService $statsService,
        UserGameCollectionRepository $collectionRepo,
        UserFollowRepository $followRepo,
    ): Response {
        $user = $this->getUser();
        $stats = $statsService->getStatsForUser($user);
        $entries = $collectionRepo->findAllForUserWithGame($user);

        $statusOrder = [
            GameStatus::EnCours,
            GameStatus::Termine,
            GameStatus::EnPause,
            GameStatus::Backlog,
            GameStatus::Abandonne,
        ];

        $collectionByStatus = [];
        foreach ($statusOrder as $status) {
            $filtered = array_filter($entries, fn($e) => $e->getStatut() === $status);
            if (!empty($filtered)) {
                $collectionByStatus[] = [
                    'status' => $status,
                    'entries' => array_values($filtered),
                ];
            }
        }

        return $this->render('user/profile.html.twig', [
            'profileUser' => $user,
            'stats' => $stats,
            'collectionByStatus' => $collectionByStatus,
            'isFollowing' => false,
            'followersCount' => $followRepo->countFollowers($user),
            'followingCount' => $followRepo->countFollowing($user),
        ]);
    }

    #[Route('/edit', name: 'app_profile_edit', methods: ['GET', 'POST'])]
    public function edit(
        Request $request,
        EntityManagerInterface $em,
    ): Response {
        $user = $this->getUser();
        $form = $this->createForm(ProfileType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setUpdatedAt(new \DateTimeImmutable());
            $em->flush();

            $this->addFlash('success', 'Profil mis à jour.');
            return $this->redirectToRoute('app_profile');
        }

        return $this->render('profile/edit.html.twig', [
            'form' => $form,
        ]);
    }
}
