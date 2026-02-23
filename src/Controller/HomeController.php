<?php

namespace App\Controller;

use App\Enum\GameStatus;
use App\Repository\UserGameCollectionRepository;
use App\Service\IgdbService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(
        IgdbService $igdbService,
        UserGameCollectionRepository $collectionRepo,
    ): Response {
        $popularGames = [];
        $recentReleases = [];
        $error = null;

        try {
            $popularGames = $igdbService->getPopularGames(null, null, 8);
            $recentReleases = $igdbService->getRecentReleases(8);
        } catch (\Exception $e) {
            $error = 'Impossible de charger les jeux depuis IGDB.';
        }

        $inProgressEntries = [];
        if ($this->getUser()) {
            $inProgressEntries = $collectionRepo->findBy(
                ['user' => $this->getUser(), 'statut' => GameStatus::EnCours],
                ['updatedAt' => 'DESC'],
                6
            );
        }

        return $this->render('home/index.html.twig', [
            'popularGames' => $popularGames,
            'recentReleases' => $recentReleases,
            'inProgressEntries' => $inProgressEntries,
            'genres' => array_keys(IgdbService::GENRE_IDS),
            'error' => $error,
        ]);
    }
}
