<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\UserGameCollection;
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
}
