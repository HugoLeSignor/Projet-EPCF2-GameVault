<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserGameCollectionRepository;
use App\Repository\UserRepository;
use App\Service\UserStatsService;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class UserSearchController extends AbstractController
{
    #[Route('/users', name: 'app_users', methods: ['GET'])]
    public function index(Request $request, UserRepository $userRepo): Response
    {
        $query = $request->query->getString('q');
        $users = [];

        if ($query) {
            $users = $userRepo->searchByPseudo($query);
        } else {
            $users = $userRepo->findBy([], ['createdAt' => 'DESC'], 20);
        }

        return $this->render('user/index.html.twig', [
            'query' => $query,
            'users' => $users,
        ]);
    }

    #[Route('/users/{id}', name: 'app_user_profile', methods: ['GET'])]
    public function profile(
        User $user,
        UserStatsService $statsService,
        UserGameCollectionRepository $collectionRepo,
        PaginatorInterface $paginator,
        Request $request,
    ): Response {
        $stats = $statsService->getStatsForUser($user);
        $entries = $collectionRepo->findBy(['user' => $user], ['addedAt' => 'DESC']);

        $pagination = $paginator->paginate(
            $entries,
            $request->query->getInt('page', 1),
            20,
        );

        return $this->render('user/profile.html.twig', [
            'profileUser' => $user,
            'stats' => $stats,
            'pagination' => $pagination,
        ]);
    }
}
