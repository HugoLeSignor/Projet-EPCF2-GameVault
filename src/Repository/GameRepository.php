<?php

namespace App\Repository;

use App\Entity\Game;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Game>
 */
class GameRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Game::class);
    }

    public function findByFilters(?string $search, ?string $plateforme, ?string $genre): QueryBuilder
    {
        $qb = $this->createQueryBuilder('g');

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

        return $qb->orderBy('g.titre', 'ASC');
    }
}
