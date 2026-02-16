<?php

namespace App\Controller;

use App\Entity\Game;
use App\Repository\GameRepository;
use App\Repository\UserGameCollectionRepository;
use App\Service\IgdbService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/search')]
#[IsGranted('ROLE_USER')]
class IgdbSearchController extends AbstractController
{
    #[Route('', name: 'app_igdb_search', methods: ['GET'])]
    public function search(
        Request $request,
        IgdbService $igdbService,
        GameRepository $gameRepository,
        UserGameCollectionRepository $collectionRepo,
    ): Response {
        $query = $request->query->getString('q');
        $results = [];
        $error = null;
        $alreadyInCollection = [];

        if ($query) {
            try {
                $results = $igdbService->searchGames($query);

                // Check which games are already in the database
                $igdbIds = array_map(fn(array $r) => $r['igdbId'], $results);
                $existingGames = $gameRepository->findBy(['igdbId' => $igdbIds]);
                $importedMap = [];
                foreach ($existingGames as $game) {
                    $importedMap[$game->getIgdbId()] = $game;
                }

                // Check which games are already in the user's collection
                foreach ($importedMap as $igdbId => $game) {
                    $inCollection = $collectionRepo->findOneBy([
                        'user' => $this->getUser(),
                        'game' => $game,
                    ]);
                    if ($inCollection) {
                        $alreadyInCollection[$igdbId] = true;
                    }
                }
            } catch (\Exception $e) {
                $error = 'Erreur lors de la recherche : ' . $e->getMessage();
            }
        }

        return $this->render('search/index.html.twig', [
            'query' => $query,
            'results' => $results,
            'error' => $error,
            'alreadyInCollection' => $alreadyInCollection,
        ]);
    }

    #[Route('/add/{igdbId}', name: 'app_igdb_add', methods: ['POST'])]
    public function add(
        int $igdbId,
        IgdbService $igdbService,
        GameRepository $gameRepository,
        EntityManagerInterface $em,
        Request $request,
    ): Response {
        if (!$this->isCsrfTokenValid('igdb_add_' . $igdbId, $request->request->get('_token'))) {
            $this->addFlash('danger', 'Token CSRF invalide.');
            return $this->redirectToRoute('app_igdb_search');
        }

        // Check if game already exists in database
        $game = $gameRepository->findOneBy(['igdbId' => $igdbId]);

        if (!$game) {
            // Auto-import from IGDB
            try {
                $data = $igdbService->getGameById($igdbId);
                if (!$data) {
                    $this->addFlash('danger', 'Jeu introuvable sur IGDB.');
                    return $this->redirectToRoute('app_igdb_search');
                }

                $game = new Game();
                $game->setTitre($data['name']);
                $game->setDescription($data['summary']);
                $game->setGenre($data['genre']);
                $game->setPlateforme($data['platform']);
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
            } catch (\Exception $e) {
                $this->addFlash('danger', 'Erreur lors de l\'import : ' . $e->getMessage());
                return $this->redirectToRoute('app_igdb_search');
            }
        }

        // Redirect to collection add form
        return $this->redirectToRoute('app_collection_add', ['id' => $game->getId()]);
    }
}
