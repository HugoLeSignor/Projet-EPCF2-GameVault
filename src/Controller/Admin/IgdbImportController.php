<?php

namespace App\Controller\Admin;

use App\Entity\Game;
use App\Repository\GameRepository;
use App\Service\IgdbService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin/igdb')]
#[IsGranted('ROLE_ADMIN')]
class IgdbImportController extends AbstractController
{
    #[Route('', name: 'admin_igdb_search', methods: ['GET'])]
    public function search(Request $request, IgdbService $igdbService, GameRepository $gameRepository): Response
    {
        $query = $request->query->getString('q');
        $results = [];
        $error = null;
        $alreadyImported = [];

        if ($query) {
            try {
                $results = $igdbService->searchGames($query);

                $igdbIds = array_map(fn(array $r) => $r['igdbId'], $results);
                $existing = $gameRepository->findBy(['igdbId' => $igdbIds]);
                foreach ($existing as $game) {
                    $alreadyImported[$game->getIgdbId()] = true;
                }
            } catch (\Exception $e) {
                $error = 'Erreur lors de la recherche IGDB : ' . $e->getMessage();
            }
        }

        return $this->render('admin/igdb/search.html.twig', [
            'query' => $query,
            'results' => $results,
            'error' => $error,
            'alreadyImported' => $alreadyImported,
        ]);
    }

    #[Route('/import/{igdbId}', name: 'admin_igdb_import', methods: ['POST'])]
    public function import(
        int $igdbId,
        IgdbService $igdbService,
        GameRepository $gameRepository,
        EntityManagerInterface $em,
        Request $request,
    ): Response {
        if (!$this->isCsrfTokenValid('igdb_import_' . $igdbId, $request->request->get('_token'))) {
            $this->addFlash('danger', 'Token CSRF invalide.');
            return $this->redirectToRoute('admin_igdb_search');
        }

        $existing = $gameRepository->findOneBy(['igdbId' => $igdbId]);
        if ($existing) {
            $this->addFlash('warning', sprintf('Le jeu "%s" est déjà dans le catalogue.', $existing->getTitre()));
            return $this->redirectToRoute('admin_igdb_search');
        }

        try {
            $data = $igdbService->getGameById($igdbId);
            if (!$data) {
                $this->addFlash('danger', 'Jeu introuvable sur IGDB.');
                return $this->redirectToRoute('admin_igdb_search');
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

            $this->addFlash('success', sprintf('Le jeu "%s" a été importé avec succès !', $game->getTitre()));
        } catch (\Exception $e) {
            $this->addFlash('danger', 'Erreur lors de l\'import : ' . $e->getMessage());
        }

        return $this->redirectToRoute('admin_igdb_search');
    }
}
