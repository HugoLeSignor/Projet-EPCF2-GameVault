<?php

namespace App\Repository;

use App\Entity\Game;
use App\Entity\User;
use App\Entity\UserGameCollection;
use App\Enum\GameStatus;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UserGameCollection>
 */
class UserGameCollectionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserGameCollection::class);
    }

    public function findByUserWithFilters(
        User $user,
        ?string $search,
        ?string $plateforme,
        ?string $genre,
        ?string $statut,
    ): QueryBuilder {
        $qb = $this->createQueryBuilder('c')
            ->join('c.game', 'g')
            ->addSelect('g')
            ->where('c.user = :user')
            ->setParameter('user', $user);

        if ($search) {
            $qb->andWhere('g.titre LIKE :search')
               ->setParameter('search', '%' . $search . '%');
        }

        if ($plateforme) {
            $qb->andWhere('g.plateforme = :plateforme')
               ->setParameter('plateforme', $plateforme);
        }

        if ($genre) {
            $qb->andWhere('g.genre = :genre')
               ->setParameter('genre', $genre);
        }

        if ($statut) {
            $qb->andWhere('c.statut = :statut')
               ->setParameter('statut', $statut);
        }

        return $qb->orderBy('c.addedAt', 'DESC');
    }

    /** @return UserGameCollection[] */
    public function findInProgressForUser(User $user, int $limit = 6): array
    {
        return $this->createQueryBuilder('c')
            ->join('c.game', 'g')
            ->addSelect('g')
            ->where('c.user = :user')
            ->andWhere('c.statut = :statut')
            ->setParameter('user', $user)
            ->setParameter('statut', GameStatus::EnCours)
            ->orderBy('c.updatedAt', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    /** @return UserGameCollection[] */
    public function findAllForUserWithGame(User $user): array
    {
        return $this->createQueryBuilder('c')
            ->join('c.game', 'g')
            ->addSelect('g')
            ->where('c.user = :user')
            ->setParameter('user', $user)
            ->orderBy('c.addedAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @param Game[] $games
     * @return array<int, true> Keyed by game ID
     */
    public function findGameIdsInUserCollection(User $user, array $games): array
    {
        if (empty($games)) {
            return [];
        }

        $results = $this->createQueryBuilder('c')
            ->select('IDENTITY(c.game) as gameId')
            ->where('c.user = :user')
            ->andWhere('c.game IN (:games)')
            ->setParameter('user', $user)
            ->setParameter('games', $games)
            ->getQuery()
            ->getScalarResult();

        $map = [];
        foreach ($results as $row) {
            $map[(int) $row['gameId']] = true;
        }
        return $map;
    }

    /** @return UserGameCollection[] */
    public function findFavoritesForUser(User $user): array
    {
        return $this->createQueryBuilder('c')
            ->join('c.game', 'g')
            ->addSelect('g')
            ->where('c.user = :user')
            ->andWhere('c.isFavori = true')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult();
    }

    public function getAggregatedStats(User $user): array
    {
        $conn = $this->getEntityManager()->getConnection();

        return $conn->fetchAssociative('
            SELECT
                COUNT(*) as total_games,
                COALESCE(SUM(temps_de_jeu), 0) as total_play_time,
                AVG(CASE WHEN note IS NOT NULL THEN note END) as avg_rating,
                SUM(CASE WHEN statut = :en_cours THEN 1 ELSE 0 END) as en_cours,
                SUM(CASE WHEN statut = :termine THEN 1 ELSE 0 END) as termine,
                SUM(CASE WHEN statut = :en_pause THEN 1 ELSE 0 END) as en_pause,
                SUM(CASE WHEN statut = :backlog THEN 1 ELSE 0 END) as backlog,
                SUM(CASE WHEN statut = :abandonne THEN 1 ELSE 0 END) as abandonne
            FROM user_game_collection
            WHERE user_id = :user_id
        ', [
            'user_id' => $user->getId(),
            'en_cours' => 'en_cours',
            'termine' => 'termine',
            'en_pause' => 'en_pause',
            'backlog' => 'backlog',
            'abandonne' => 'abandonne',
        ]);
    }

    /** @return array<array{platform: string, cnt: int}> */
    public function getPlatformDistribution(User $user): array
    {
        return $this->createQueryBuilder('c')
            ->select('COALESCE(c.plateforme, g.plateforme) as platform, COUNT(c.id) as cnt')
            ->join('c.game', 'g')
            ->where('c.user = :user')
            ->setParameter('user', $user)
            ->groupBy('platform')
            ->orderBy('cnt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /** @return array<array{genre: string, cnt: int}> */
    public function getGenreDistribution(User $user): array
    {
        return $this->createQueryBuilder('c')
            ->select('g.genre as genre, COUNT(c.id) as cnt')
            ->join('c.game', 'g')
            ->where('c.user = :user')
            ->setParameter('user', $user)
            ->groupBy('genre')
            ->orderBy('cnt', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
