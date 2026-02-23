<?php

namespace App\Service;

use App\Entity\Game;
use App\Entity\Notification;
use App\Entity\User;
use App\Repository\UserFollowRepository;
use Doctrine\ORM\EntityManagerInterface;

class NotificationService
{
    public function __construct(
        private readonly UserFollowRepository $followRepo,
        private readonly EntityManagerInterface $em,
    ) {}

    public function notifyFollowers(User $actor, string $type, Game $game, string $message): void
    {
        $follows = $this->followRepo->getFollowerUsers($actor);

        foreach ($follows as $follow) {
            $notification = new Notification();
            $notification->setRecipient($follow->getFollower());
            $notification->setActor($actor);
            $notification->setType($type);
            $notification->setGame($game);
            $notification->setMessage($message);
            $this->em->persist($notification);
        }

        if (!empty($follows)) {
            $this->em->flush();
        }
    }
}
