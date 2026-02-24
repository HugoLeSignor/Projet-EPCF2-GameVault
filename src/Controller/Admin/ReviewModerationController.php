<?php

namespace App\Controller\Admin;

use App\Entity\Review;
use App\Repository\ReviewRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin/reviews')]
#[IsGranted('ROLE_ADMIN')]
class ReviewModerationController extends AbstractController
{
    public function __construct(
        private readonly LoggerInterface $auditLogger,
    ) {}

    #[Route('', name: 'admin_review_index')]
    public function index(
        ReviewRepository $reviewRepo,
        PaginatorInterface $paginator,
        Request $request,
    ): Response {
        $filter = $request->query->getString('filter', 'pending');

        $qb = $reviewRepo->createQueryBuilder('r')
            ->join('r.user', 'u')
            ->addSelect('u')
            ->join('r.game', 'g')
            ->addSelect('g')
            ->orderBy('r.createdAt', 'DESC');

        if ($filter === 'pending') {
            $qb->where('r.isApproved = false');
        }

        $pagination = $paginator->paginate(
            $qb,
            $request->query->getInt('page', 1),
            20,
        );

        return $this->render('admin/review/index.html.twig', [
            'pagination' => $pagination,
            'filter' => $filter,
        ]);
    }

    #[Route('/{id}/approve', name: 'admin_review_approve', methods: ['POST'])]
    public function approve(
        Review $review,
        Request $request,
        EntityManagerInterface $em,
    ): Response {
        if ($this->isCsrfTokenValid('approve' . $review->getId(), $request->getPayload()->getString('_token'))) {
            $review->setIsApproved(true);
            $em->flush();

            $this->auditLogger->info('Review approved', [
                'admin' => $this->getUser()->getUserIdentifier(),
                'review_id' => $review->getId(),
                'review_author' => $review->getUser()->getPseudo(),
                'game' => $review->getGame()->getTitre(),
            ]);

            $this->addFlash('success', 'Avis approuvé.');
        }

        return $this->redirectToRoute('admin_review_index');
    }

    #[Route('/{id}/reject', name: 'admin_review_reject', methods: ['POST'])]
    public function reject(
        Review $review,
        Request $request,
        EntityManagerInterface $em,
    ): Response {
        if ($this->isCsrfTokenValid('reject' . $review->getId(), $request->getPayload()->getString('_token'))) {
            $reviewId = $review->getId();
            $author = $review->getUser()->getPseudo();
            $game = $review->getGame()->getTitre();

            $em->remove($review);
            $em->flush();

            $this->auditLogger->info('Review rejected', [
                'admin' => $this->getUser()->getUserIdentifier(),
                'review_id' => $reviewId,
                'review_author' => $author,
                'game' => $game,
            ]);

            $this->addFlash('success', 'Avis rejeté et supprimé.');
        }

        return $this->redirectToRoute('admin_review_index');
    }
}
