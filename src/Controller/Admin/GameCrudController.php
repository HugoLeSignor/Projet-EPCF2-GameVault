<?php

namespace App\Controller\Admin;

use App\Entity\Game;
use App\Form\GameType;
use App\Repository\GameRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin/games')]
#[IsGranted('ROLE_ADMIN')]
class GameCrudController extends AbstractController
{
    #[Route('', name: 'admin_game_index', methods: ['GET'])]
    public function index(
        GameRepository $gameRepository,
        PaginatorInterface $paginator,
        Request $request,
    ): Response {
        $pagination = $paginator->paginate(
            $gameRepository->createQueryBuilder('g')->orderBy('g.titre', 'ASC'),
            $request->query->getInt('page', 1),
            20,
        );

        return $this->render('admin/game/index.html.twig', [
            'pagination' => $pagination,
        ]);
    }

    #[Route('/new', name: 'admin_game_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $game = new Game();
        $form = $this->createForm(GameType::class, $game);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($game);
            $em->flush();

            $this->addFlash('success', 'Jeu ajouté au catalogue.');
            return $this->redirectToRoute('admin_game_index');
        }

        return $this->render('admin/game/new.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: 'admin_game_edit', methods: ['GET', 'POST'])]
    public function edit(Game $game, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(GameType::class, $game);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $game->setUpdatedAt(new \DateTimeImmutable());
            $em->flush();

            $this->addFlash('success', 'Jeu modifié.');
            return $this->redirectToRoute('admin_game_index');
        }

        return $this->render('admin/game/edit.html.twig', [
            'game' => $game,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'admin_game_delete', methods: ['POST'])]
    public function delete(
        Game $game,
        Request $request,
        EntityManagerInterface $em,
    ): Response {
        if ($this->isCsrfTokenValid('delete' . $game->getId(), $request->getPayload()->getString('_token'))) {
            $em->remove($game);
            $em->flush();
            $this->addFlash('success', 'Jeu supprimé du catalogue.');
        }

        return $this->redirectToRoute('admin_game_index');
    }
}
