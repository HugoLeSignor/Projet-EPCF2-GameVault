<?php

namespace App\Controller;

use App\Entity\Game;
use App\Entity\Review;
use App\Form\ReviewType;
use App\Repository\ReviewRepository;
use App\Service\NotificationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/review')]
#[IsGranted('ROLE_USER')]
class ReviewController extends AbstractController
{
    #[Route('/new/{id}', name: 'app_review_new', methods: ['GET', 'POST'])]
    public function new(
        Game $game,
        Request $request,
        EntityManagerInterface $em,
        ReviewRepository $reviewRepo,
        NotificationService $notifService,
    ): Response {
        $existing = $reviewRepo->findOneBy([
            'user' => $this->getUser(),
            'game' => $game,
        ]);
        if ($existing) {
            $this->addFlash('warning', 'Vous avez déjà laissé un avis pour ce jeu.');
            return $this->redirectToGame($game);
        }

        $review = new Review();
        $review->setUser($this->getUser());
        $review->setGame($game);

        $form = $this->createForm(ReviewType::class, $review);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($review);
            $em->flush();

            $notifService->notifyFollowers(
                $this->getUser(),
                'review_new',
                $game,
                'a laissé un avis sur'
            );

            $this->addFlash('success', 'Votre avis a été soumis et sera visible après modération.');
            return $this->redirectToGame($game);
        }

        return $this->render('review/new.html.twig', [
            'game' => $game,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_review_edit', methods: ['GET', 'POST'])]
    public function edit(
        Review $review,
        Request $request,
        EntityManagerInterface $em,
    ): Response {
        if ($review->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException();
        }

        $form = $this->createForm(ReviewType::class, $review);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $review->setUpdatedAt(new \DateTimeImmutable());
            $review->setIsApproved(false);
            $em->flush();

            $this->addFlash('success', 'Votre avis a été modifié et sera re-vérifié.');
            return $this->redirectToGame($review->getGame());
        }

        return $this->render('review/edit.html.twig', [
            'review' => $review,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/delete', name: 'app_review_delete', methods: ['POST'])]
    public function delete(
        Review $review,
        Request $request,
        EntityManagerInterface $em,
    ): Response {
        if ($review->getUser() !== $this->getUser() && !$this->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException();
        }

        if ($this->isCsrfTokenValid('delete' . $review->getId(), $request->getPayload()->getString('_token'))) {
            $game = $review->getGame();
            $em->remove($review);
            $em->flush();
            $this->addFlash('success', 'Avis supprimé.');
            return $this->redirectToGame($game);
        }

        return $this->redirectToGame($review->getGame());
    }

    private function redirectToGame(Game $game): Response
    {
        if ($game->getIgdbId()) {
            return $this->redirectToRoute('app_game_show', ['igdbId' => $game->getIgdbId()]);
        }
        return $this->redirectToRoute('app_game_show_local', ['id' => $game->getId()]);
    }
}
