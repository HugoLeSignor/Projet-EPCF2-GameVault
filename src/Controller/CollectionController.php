<?php

namespace App\Controller;

use App\Entity\Game;
use App\Entity\UserGameCollection;
use App\Form\GameFilterType;
use App\Form\UserGameCollectionType;
use App\Repository\UserGameCollectionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/collection')]
#[IsGranted('ROLE_USER')]
class CollectionController extends AbstractController
{
    #[Route('', name: 'app_collection_index', methods: ['GET'])]
    public function index(
        UserGameCollectionRepository $collectionRepo,
        PaginatorInterface $paginator,
        Request $request,
    ): Response {
        $filterForm = $this->createForm(GameFilterType::class, null, [
            'show_status_filter' => true,
        ]);
        $filterForm->handleRequest($request);

        $data = $filterForm->getData() ?? [];

        $qb = $collectionRepo->findByUserWithFilters(
            $this->getUser(),
            $data['search'] ?? null,
            $data['plateforme'] ?? null,
            $data['genre'] ?? null,
            $data['statut'] ?? null,
        );

        $pagination = $paginator->paginate(
            $qb,
            $request->query->getInt('page', 1),
            20,
        );

        return $this->render('collection/index.html.twig', [
            'pagination' => $pagination,
            'filterForm' => $filterForm,
        ]);
    }

    #[Route('/add/{id}', name: 'app_collection_add', methods: ['GET', 'POST'])]
    public function add(
        Game $game,
        Request $request,
        EntityManagerInterface $em,
        UserGameCollectionRepository $collectionRepo,
    ): Response {
        $existing = $collectionRepo->findOneBy([
            'user' => $this->getUser(),
            'game' => $game,
        ]);
        if ($existing) {
            $this->addFlash('warning', 'Ce jeu est déjà dans votre collection.');
            return $this->redirectToRoute('app_collection_index');
        }

        $entry = new UserGameCollection();
        $entry->setUser($this->getUser());
        $entry->setGame($game);

        $form = $this->createForm(UserGameCollectionType::class, $entry);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($entry);
            $em->flush();

            $this->addFlash('success', sprintf('"%s" ajouté à votre collection !', $game->getTitre()));
            return $this->redirectToRoute('app_collection_index');
        }

        return $this->render('collection/add.html.twig', [
            'game' => $game,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_collection_edit', methods: ['GET', 'POST'])]
    public function edit(
        UserGameCollection $entry,
        Request $request,
        EntityManagerInterface $em,
    ): Response {
        if ($entry->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException();
        }

        $form = $this->createForm(UserGameCollectionType::class, $entry);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entry->setUpdatedAt(new \DateTimeImmutable());
            $em->flush();

            $this->addFlash('success', 'Collection mise à jour.');
            return $this->redirectToRoute('app_collection_index');
        }

        return $this->render('collection/edit.html.twig', [
            'entry' => $entry,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/delete', name: 'app_collection_delete', methods: ['POST'])]
    public function delete(
        UserGameCollection $entry,
        Request $request,
        EntityManagerInterface $em,
    ): Response {
        if ($entry->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException();
        }

        if ($this->isCsrfTokenValid('delete' . $entry->getId(), $request->getPayload()->getString('_token'))) {
            $em->remove($entry);
            $em->flush();
            $this->addFlash('success', 'Jeu retiré de votre collection.');
        }

        return $this->redirectToRoute('app_collection_index');
    }
}
