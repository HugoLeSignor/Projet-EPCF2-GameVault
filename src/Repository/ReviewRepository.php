<?php

namespace App\Repository;

use App\Entity\Game;
use App\Entity\Review;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Review>
 */
class ReviewRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Review::class);
    }

    /**
     * @return Review[]
     */
    public function findLatestApproved(int $limit = 20): array
    {
        return $this->createQueryBuilder('r')
            ->join('r.user', 'u')
            ->addSelect('u')
            ->join('r.game', 'g')
            ->addSelect('g')
            ->where('r.isApproved = true')
            ->orderBy('r.createdAt', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    public function getAverageRatingForGame(Game $game): ?float
    {
        $result = $this->createQueryBuilder('r')
            ->select('AVG(r.note) as avg')
            ->where('r.game = :game')
            ->andWhere('r.isApproved = true')
            ->setParameter('game', $game)
            ->getQuery()
            ->getSingleScalarResult();

        return $result !== null ? round((float) $result, 1) : null;
    }

    /**
     * @return Review[]
     */
    public function findApprovedByGame(Game $game): array
    {
        return $this->createQueryBuilder('r')
            ->join('r.user', 'u')
            ->addSelect('u')
            ->where('r.game = :game')
            ->andWhere('r.isApproved = true')
            ->setParameter('game', $game)
            ->orderBy('r.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
