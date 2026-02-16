<?php

namespace App\Controller\Admin;

use App\Repository\GameRepository;
use App\Repository\ReviewRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin')]
#[IsGranted('ROLE_ADMIN')]
class DashboardController extends AbstractController
{
    #[Route('', name: 'admin_dashboard')]
    public function index(
        UserRepository $userRepo,
        GameRepository $gameRepo,
        ReviewRepository $reviewRepo,
    ): Response {
        return $this->render('admin/dashboard.html.twig', [
            'totalUsers' => $userRepo->count([]),
            'totalGames' => $gameRepo->count([]),
            'pendingReviews' => $reviewRepo->count(['isApproved' => false]),
            'totalReviews' => $reviewRepo->count([]),
        ]);
    }
}
