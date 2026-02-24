<?php

namespace App\Controller;

use App\Entity\Game;
use App\Repository\GameRepository;
use App\Repository\ReviewRepository;
use App\Repository\UserGameCollectionRepository;
use App\Service\IgdbService;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class GameController extends AbstractController
{
    public function __construct(
        private readonly LoggerInterface $logger,
    ) {}
    #[Route('/games', name: 'app_game_index', methods: ['GET'])]
    public function index(
        Request $request,
        IgdbService $igdbService,
        GameRepository $gameRepository,
        UserGameCollectionRepository $collectionRepo,
    ): Response {
        $query = mb_substr($request->query->getString('q'), 0, 100);
        $genre = $request->query->getString('genre') ?: null;
        $platform = $request->query->getString('platform') ?: null;
        $results = [];
        $error = null;
        $alreadyInCollection = [];

        try {
            if ($query) {
                $results = $igdbService->searchGames($query, $genre, $platform);
            } else {
                $results = $igdbService->getPopularGames($genre, $platform);
            }

            // Check which games are already in user's collection (batch query)
            if ($this->getUser() && !empty($results)) {
                $igdbIds = array_map(fn(array $r) => $r['igdbId'], $results);
                $existingGames = $gameRepository->findBy(['igdbId' => $igdbIds]);

                if (!empty($existingGames)) {
                    $inCollectionIds = $collectionRepo->findGameIdsInUserCollection(
                        $this->getUser(),
                        $existingGames
                    );
                    foreach ($existingGames as $game) {
                        if (isset($inCollectionIds[$game->getId()])) {
                            $alreadyInCollection[$game->getIgdbId()] = true;
                        }
                    }
                }
            }
        } catch (\Exception $e) {
            $this->logger->error('IGDB API error', ['exception' => $e]);
            $error = 'Impossible de charger les jeux. Veuillez réessayer plus tard.';
        }

        return $this->render('game/index.html.twig', [
            'query' => $query,
            'genre' => $genre,
            'platform' => $platform,
            'results' => $results,
            'error' => $error,
            'alreadyInCollection' => $alreadyInCollection,
            'genres' => array_keys(IgdbService::GENRE_IDS),
            'platforms' => array_keys(IgdbService::PLATFORM_IDS),
        ]);
    }

    #[Route('/games/{igdbId}', name: 'app_game_show', requirements: ['igdbId' => '\d+'], methods: ['GET'])]
    public function show(
        int $igdbId,
        IgdbService $igdbService,
        GameRepository $gameRepository,
        ReviewRepository $reviewRepository,
        UserGameCollectionRepository $collectionRepo,
        EntityManagerInterface $em,
    ): Response {
        // Find or auto-import from IGDB
        $game = $gameRepository->findOneBy(['igdbId' => $igdbId]);

        if (!$game) {
            $data = $igdbService->getGameById($igdbId);
            if (!$data) {
                throw $this->createNotFoundException('Jeu introuvable.');
            }

            $game = new Game();
            $game->setTitre($data['name']);
            $game->setDescription($data['summary']);
            $game->setGenre($data['genre']);
            $game->setPlateforme(implode(', ', $data['platforms']));
            $game->setDeveloppeur($data['developer']);
            $game->setEditeur($data['publisher']);
            $game->setIgdbId($igdbId);

            if ($data['coverUrl']) {
                $game->setImageUrl($data['coverUrl']);
            }
            if ($data['releaseDate']) {
                $game->setDateDeSortie(new \DateTimeImmutable($data['releaseDate']));
            }

            $em->persist($game);
            $em->flush();
        } elseif ($game->getPlatformsRefreshedAt() === null) {
            // Mettre à jour les plateformes des jeux importés avant le multi-plateforme
            $data = $igdbService->getGameById($igdbId);
            if ($data && count($data['platforms']) > 1) {
                $game->setPlateforme(implode(', ', $data['platforms']));
            }
            $game->setPlatformsRefreshedAt(new \DateTimeImmutable());
            $em->flush();
        }

        return $this->renderGameShow($game, $reviewRepository, $collectionRepo);
    }

    #[Route('/game/local/{id}', name: 'app_game_show_local', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function showLocal(
        Game $game,
        ReviewRepository $reviewRepository,
        UserGameCollectionRepository $collectionRepo,
    ): Response {
        return $this->renderGameShow($game, $reviewRepository, $collectionRepo);
    }

    private function renderGameShow(
        Game $game,
        ReviewRepository $reviewRepository,
        UserGameCollectionRepository $collectionRepo,
    ): Response {
        $reviews = $reviewRepository->findApprovedByGame($game);
        $inCollection = null;

        if ($this->getUser()) {
            $inCollection = $collectionRepo->findOneBy([
                'user' => $this->getUser(),
                'game' => $game,
            ]);
        }

        return $this->render('game/show.html.twig', [
            'game' => $game,
            'reviews' => $reviews,
            'inCollection' => $inCollection,
        ]);
    }
}
