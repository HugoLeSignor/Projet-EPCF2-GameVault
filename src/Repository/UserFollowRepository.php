<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\UserFollow;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UserFollow>
 */
class UserFollowRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserFollow::class);
    }

    public function isFollowing(User $follower, User $following): bool
    {
        return (bool) $this->findOneBy([
            'follower' => $follower,
            'following' => $following,
        ]);
    }

    /**
     * @return User[]
     */
    public function getFollowerUsers(User $user): array
    {
        return $this->createQueryBuilder('f')
            ->join('f.follower', 'u')
            ->addSelect('u')
            ->where('f.following = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult();
    }

    public function countFollowers(User $user): int
    {
        return (int) $this->createQueryBuilder('f')
            ->select('COUNT(f.id)')
            ->where('f.following = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function countFollowing(User $user): int
    {
        return (int) $this->createQueryBuilder('f')
            ->select('COUNT(f.id)')
            ->where('f.follower = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getSingleScalarResult();
    }
}
