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
        // Single SQL query for all aggregated stats
        $agg = $this->collectionRepo->getAggregatedStats($user);

        $totalPlayTime = (int) ($agg['total_play_time'] ?? 0);
        $hours = intdiv($totalPlayTime, 60);
        $minutes = $totalPlayTime % 60;
        $daysPlayed = round($totalPlayTime / 1440, 1);

        // Platform & genre distributions via GROUP BY queries
        $platformRows = $this->collectionRepo->getPlatformDistribution($user);
        $platformCounts = [];
        foreach ($platformRows as $row) {
            $platformCounts[$row['platform']] = (int) $row['cnt'];
        }

        $genreRows = $this->collectionRepo->getGenreDistribution($user);
        $genreCounts = [];
        foreach ($genreRows as $row) {
            $genreCounts[$row['genre']] = (int) $row['cnt'];
        }

        // Status distribution from aggregated stats
        $statusCounts = [];
        foreach (GameStatus::cases() as $status) {
            $statusCounts[$status->value] = 0;
        }
        $statusCounts[GameStatus::EnCours->value] = (int) ($agg['en_cours'] ?? 0);
        $statusCounts[GameStatus::Termine->value] = (int) ($agg['termine'] ?? 0);
        $statusCounts[GameStatus::EnPause->value] = (int) ($agg['en_pause'] ?? 0);
        $statusCounts[GameStatus::Backlog->value] = (int) ($agg['backlog'] ?? 0);
        $statusCounts[GameStatus::Abandonne->value] = (int) ($agg['abandonne'] ?? 0);

        // Lazy-loaded queries only for the entities we actually need
        $currentlyPlaying = $this->collectionRepo->findInProgressForUser($user, 6);
        $favorites = $this->collectionRepo->findFavoritesForUser($user);

        $avgRating = $agg['avg_rating'] !== null ? round((float) $agg['avg_rating'], 1) : null;

        return [
            'totalGames' => (int) ($agg['total_games'] ?? 0),
            'totalPlayTimeMinutes' => $totalPlayTime,
            'totalPlayTimeFormatted' => sprintf('%dh %02dmin', $hours, $minutes),
            'daysPlayed' => $daysPlayed,
            'finishedGames' => $statusCounts[GameStatus::Termine->value],
            'inProgressGames' => $statusCounts[GameStatus::EnCours->value],
            'backlogGames' => $statusCounts[GameStatus::Backlog->value],
            'abandonedGames' => $statusCounts[GameStatus::Abandonne->value],
            'pausedGames' => $statusCounts[GameStatus::EnPause->value],
            'averageRating' => $avgRating,
            'reviewCount' => $this->reviewRepo->count(['user' => $user]),
            'statusDistribution' => $statusCounts,
            'platformDistribution' => $platformCounts,
            'genreDistribution' => $genreCounts,
            'currentlyPlaying' => $currentlyPlaying,
            'favorites' => $favorites,
        ];
    }
}
