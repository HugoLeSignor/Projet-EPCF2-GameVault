<?php

namespace App\Service;

use App\Entity\User;
use App\Enum\GameStatus;
use App\Repository\ReviewRepository;
use App\Repository\UserGameCollectionRepository;

class UserStatsService
{
    public function __construct(
        private readonly UserGameCollectionRepository $collectionRepo,
        private readonly ReviewRepository $reviewRepo,
    ) {}

    public function getStatsForUser(User $user): array
    {
        $entries = $this->collectionRepo->findBy(['user' => $user]);

        $totalGames = count($entries);
        $totalPlayTime = 0;
        $statusCounts = [];
        $platformCounts = [];
        $genreCounts = [];
        $ratingSum = 0;
        $ratingCount = 0;
        $currentlyPlaying = [];
        $favorites = [];

        foreach (GameStatus::cases() as $status) {
            $statusCounts[$status->value] = 0;
        }

        foreach ($entries as $entry) {
            $totalPlayTime += $entry->getTempsDeJeu() ?? 0;
            $statusCounts[$entry->getStatut()->value]++;

            if ($entry->getNote() !== null) {
                $ratingSum += $entry->getNote();
                $ratingCount++;
                $favorites[] = $entry;
            }

            if ($entry->getStatut() === GameStatus::EnCours) {
                $currentlyPlaying[] = $entry;
            }

            $game = $entry->getGame();
            $platform = $game->getPlateforme();
            $platformCounts[$platform] = ($platformCounts[$platform] ?? 0) + 1;

            $genre = $game->getGenre();
            $genreCounts[$genre] = ($genreCounts[$genre] ?? 0) + 1;
        }

        // Sort favorites by rating desc, keep top 6
        usort($favorites, fn($a, $b) => $b->getNote() <=> $a->getNote());
        $favorites = array_slice($favorites, 0, 6);

        // Keep only last 6 currently playing
        $currentlyPlaying = array_slice($currentlyPlaying, 0, 6);

        $hours = intdiv($totalPlayTime, 60);
        $minutes = $totalPlayTime % 60;

        // Days played (for AniList-style display)
        $daysPlayed = round($totalPlayTime / 1440, 1);

        arsort($platformCounts);
        arsort($genreCounts);

        return [
            'totalGames' => $totalGames,
            'totalPlayTimeMinutes' => $totalPlayTime,
            'totalPlayTimeFormatted' => sprintf('%dh %02dmin', $hours, $minutes),
            'daysPlayed' => $daysPlayed,
            'finishedGames' => $statusCounts[GameStatus::Termine->value],
            'inProgressGames' => $statusCounts[GameStatus::EnCours->value],
            'backlogGames' => $statusCounts[GameStatus::Backlog->value],
            'abandonedGames' => $statusCounts[GameStatus::Abandonne->value],
            'pausedGames' => $statusCounts[GameStatus::EnPause->value],
            'averageRating' => $ratingCount > 0 ? round($ratingSum / $ratingCount, 1) : null,
            'reviewCount' => $this->reviewRepo->count(['user' => $user]),
            'statusDistribution' => $statusCounts,
            'platformDistribution' => $platformCounts,
            'genreDistribution' => $genreCounts,
            'currentlyPlaying' => $currentlyPlaying,
            'favorites' => $favorites,
        ];
    }
}
