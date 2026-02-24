<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class IgdbService
{
    private ?string $accessToken = null;
    private ?int $tokenExpiresAt = null;

    private const GENRE_MAP = [
        'Fighting' => 'Action',
        'Shooter' => 'FPS',
        'Music' => 'Indie',
        'Platform' => 'Plateforme',
        'Puzzle' => 'Puzzle',
        'Racing' => 'Course',
        'Real Time Strategy (RTS)' => 'Stratégie',
        'Role-playing (RPG)' => 'RPG',
        'Simulator' => 'Simulation',
        'Sport' => 'Sport',
        'Strategy' => 'Stratégie',
        'Turn-based strategy (TBS)' => 'Stratégie',
        'Tactical' => 'Stratégie',
        'Hack and slash/Beat \'em up' => 'Action',
        'Quiz/Trivia' => 'Puzzle',
        'Pinball' => 'Indie',
        'Adventure' => 'Aventure',
        'Indie' => 'Indie',
        'Arcade' => 'Action',
        'Visual Novel' => 'Aventure',
        'Card & Board Game' => 'Stratégie',
        'MOBA' => 'Stratégie',
        'Point-and-click' => 'Aventure',
    ];

    // Our local genre → IGDB genre IDs
    public const GENRE_IDS = [
        'Action' => [4, 25, 33],      // Fighting, Hack and slash, Arcade
        'Aventure' => [31, 2, 34],     // Adventure, Point-and-click, Visual Novel
        'RPG' => [12],                 // Role-playing
        'FPS' => [5],                  // Shooter
        'Stratégie' => [11, 15, 16, 24, 35, 36], // RTS, Strategy, TBS, Tactical, Card, MOBA
        'Sport' => [14],               // Sport
        'Course' => [10],              // Racing
        'Simulation' => [13],          // Simulator
        'Puzzle' => [9, 26],           // Puzzle, Quiz/Trivia
        'Plateforme' => [8],           // Platform
        'Horreur' => [31],             // Adventure (theme-based, closest match)
        'Indie' => [32],               // Indie
    ];

    // Our local platform → IGDB platform IDs
    public const PLATFORM_IDS = [
        'PC' => [6, 14, 3],           // PC Windows, Mac, Linux
        'PS5' => [167],                // PlayStation 5
        'PS4' => [48],                 // PlayStation 4
        'Xbox Series' => [169],        // Xbox Series X|S
        'Xbox One' => [49],            // Xbox One
        'Switch' => [130],             // Nintendo Switch
        'Switch 2' => [612],           // Nintendo Switch 2
    ];

    // IGDB theme IDs to exclude (adult/NSFW content)
    private const EXCLUDED_THEMES = [42]; // 42 = Erotic

    private const PLATFORM_MAP = [
        'PC (Microsoft Windows)' => 'PC',
        'Mac' => 'PC',
        'Linux' => 'PC',
        'PlayStation 5' => 'PS5',
        'PlayStation 4' => 'PS4',
        'PlayStation 3' => 'PS3',
        'PlayStation 2' => 'PS2',
        'PlayStation' => 'PS1',
        'PlayStation Vita' => 'PS Vita',
        'PlayStation Portable' => 'PSP',
        'Xbox Series X|S' => 'Xbox Series',
        'Xbox One' => 'Xbox One',
        'Xbox 360' => 'Xbox 360',
        'Xbox' => 'Xbox',
        'Nintendo Switch' => 'Switch',
        'Nintendo Switch 2' => 'Switch 2',
        'Wii U' => 'Wii U',
        'Wii' => 'Wii',
        'Nintendo 3DS' => '3DS',
        'Nintendo DS' => 'DS',
        'Nintendo 64' => 'N64',
        'Game Boy Advance' => 'GBA',
        'Nintendo GameCube' => 'GameCube',
        'Android' => 'Mobile',
        'iOS' => 'Mobile',
    ];

    public function __construct(
        private readonly HttpClientInterface $httpClient,
        private readonly string $igdbClientId,
        private readonly string $igdbClientSecret,
    ) {}

    private function getAccessToken(): string
    {
        if ($this->accessToken && $this->tokenExpiresAt && time() < $this->tokenExpiresAt) {
            return $this->accessToken;
        }

        $response = $this->httpClient->request('POST', 'https://id.twitch.tv/oauth2/token', [
            'body' => [
                'client_id' => $this->igdbClientId,
                'client_secret' => $this->igdbClientSecret,
                'grant_type' => 'client_credentials',
            ],
        ]);

        $data = $response->toArray();
        $this->accessToken = $data['access_token'];
        $this->tokenExpiresAt = time() + ($data['expires_in'] ?? 5000) - 60;

        return $this->accessToken;
    }

    private function apiRequest(string $endpoint, string $body): array
    {
        $response = $this->httpClient->request('POST', 'https://api.igdb.com/v4/' . $endpoint, [
            'headers' => [
                'Client-ID' => $this->igdbClientId,
                'Authorization' => 'Bearer ' . $this->getAccessToken(),
            ],
            'body' => $body,
        ]);

        return $response->toArray();
    }

    public function searchGames(string $query, ?string $genre = null, ?string $platform = null, int $limit = 20): array
    {
        $conditions = [$this->getNsfwFilter()];
        $genrePlatformWhere = $this->buildWhereClause($genre, $platform);
        if ($genrePlatformWhere) {
            $conditions[] = $genrePlatformWhere;
        }
        $where = implode(' & ', $conditions);

        $body = sprintf(
            'search "%s"; fields name,summary,cover.image_id,genres.name,platforms.name,first_release_date,involved_companies.company.name,involved_companies.developer,involved_companies.publisher; where %s; limit %d;',
            addslashes($query),
            $where,
            $limit
        );

        $results = $this->apiRequest('games', $body);

        return array_map(fn(array $game) => $this->formatGameResult($game), $results);
    }

    public function getPopularGames(?string $genre = null, ?string $platform = null, int $limit = 24): array
    {
        $conditions = ['total_rating_count > 50', 'cover != null', $this->getNsfwFilter()];
        $genrePlatformWhere = $this->buildWhereClause($genre, $platform);
        if ($genrePlatformWhere) {
            $conditions[] = $genrePlatformWhere;
        }

        $body = sprintf(
            'fields name,summary,cover.image_id,genres.name,platforms.name,first_release_date,involved_companies.company.name,involved_companies.developer,involved_companies.publisher; sort total_rating_count desc; where %s; limit %d;',
            implode(' & ', $conditions),
            $limit
        );

        $results = $this->apiRequest('games', $body);

        return array_map(fn(array $game) => $this->formatGameResult($game), $results);
    }

    public function getRecentReleases(int $limit = 8): array
    {
        $threeMonthsAgo = time() - (90 * 24 * 60 * 60);
        $now = time();

        $body = sprintf(
            'fields name,summary,cover.image_id,genres.name,platforms.name,first_release_date,involved_companies.company.name,involved_companies.developer,involved_companies.publisher; sort first_release_date desc; where first_release_date >= %d & first_release_date <= %d & cover != null & total_rating_count > 5 & %s; limit %d;',
            $threeMonthsAgo,
            $now,
            $this->getNsfwFilter(),
            $limit
        );

        $results = $this->apiRequest('games', $body);

        return array_map(fn(array $game) => $this->formatGameResult($game), $results);
    }

    private function buildWhereClause(?string $genre, ?string $platform): string
    {
        $parts = [];

        if ($genre && isset(self::GENRE_IDS[$genre])) {
            $ids = implode(',', self::GENRE_IDS[$genre]);
            $parts[] = 'genres = (' . $ids . ')';
        }

        if ($platform && isset(self::PLATFORM_IDS[$platform])) {
            $ids = implode(',', self::PLATFORM_IDS[$platform]);
            $parts[] = 'platforms = (' . $ids . ')';
        }

        return implode(' & ', $parts);
    }

    public function getGameById(int $igdbId): ?array
    {
        $body = sprintf(
            'where id = %d; fields name,summary,cover.image_id,genres.name,platforms.name,first_release_date,involved_companies.company.name,involved_companies.developer,involved_companies.publisher; limit 1;',
            $igdbId
        );

        $results = $this->apiRequest('games', $body);

        if (empty($results)) {
            return null;
        }

        return $this->formatGameResult($results[0]);
    }

    private function formatGameResult(array $game): array
    {
        $coverUrl = null;
        if (isset($game['cover']['image_id'])) {
            $coverUrl = $this->getCoverUrl($game['cover']['image_id']);
        }

        $developer = null;
        $publisher = null;
        if (isset($game['involved_companies'])) {
            foreach ($game['involved_companies'] as $ic) {
                if (!empty($ic['developer']) && !$developer) {
                    $developer = $ic['company']['name'] ?? null;
                }
                if (!empty($ic['publisher']) && !$publisher) {
                    $publisher = $ic['company']['name'] ?? null;
                }
            }
        }

        $genre = null;
        if (isset($game['genres'])) {
            foreach ($game['genres'] as $g) {
                $genre = self::GENRE_MAP[$g['name']] ?? null;
                if ($genre) break;
            }
            if (!$genre) {
                $genre = 'Action';
            }
        }

        $platforms = [];
        if (isset($game['platforms'])) {
            foreach ($game['platforms'] as $p) {
                $mapped = self::PLATFORM_MAP[$p['name']] ?? null;
                if ($mapped && !in_array($mapped, $platforms, true)) {
                    $platforms[] = $mapped;
                }
            }
        }
        if (empty($platforms)) {
            $platforms = ['PC'];
        }

        $releaseDate = null;
        if (isset($game['first_release_date'])) {
            $releaseDate = (new \DateTimeImmutable())->setTimestamp($game['first_release_date'])->format('Y-m-d');
        }

        return [
            'igdbId' => $game['id'],
            'name' => $game['name'] ?? 'Unknown',
            'summary' => $game['summary'] ?? null,
            'coverUrl' => $coverUrl,
            'genre' => $genre ?? 'Action',
            'platform' => $platforms[0],
            'platforms' => $platforms,
            'developer' => $developer,
            'publisher' => $publisher,
            'releaseDate' => $releaseDate,
        ];
    }

    private function getNsfwFilter(): string
    {
        return 'themes != (' . implode(',', self::EXCLUDED_THEMES) . ')';
    }

    public function getCoverUrl(string $imageId, string $size = 'cover_big'): string
    {
        return sprintf('https://images.igdb.com/igdb/image/upload/t_%s/%s.jpg', $size, $imageId);
    }
}
