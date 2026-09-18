<?php

namespace App\Controller;

use App\Entity\Playlist;
use App\Form\PlaylistType;
use App\Form\DeletePlaylistType;
use App\Repository\GenreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class PlaylistController extends AbstractController
{
    #[Route('/playlist-add', name: 'app_playlist_add')]
    public function create(EntityManagerInterface $entityManager, Request $request, GenreRepository $genreRepository): Response
    {
        $playlist = new Playlist();
        $playlist->setUser($this->getUser());
        $playlist->setCreatedAt(new \DateTimeImmutable());

        $form = $this->createForm(PlaylistType::class, $playlist);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($playlist);
            $entityManager->flush();

            return $this->redirectToRoute('app_profil');
        }

        return $this->render('playlist/add.html.twig', [
            'form' => $form->createView(),
            'genres' => $genreRepository->getAllGenres(),
        ]);
    }

    #[Route('/playlist-delete', name: 'app_playlist_delete')]
    public function delete(EntityManagerInterface $entityManager, Request $request, GenreRepository $genreRepository): Response
    {
        $form = $this->createForm(DeletePlaylistType::class, null, [
            'user' => $this->getUser(),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $playlist = $form->get('playlist')->getData();
            $entityManager->remove($playlist);
            $entityManager->flush();

            return $this->redirectToRoute('app_profil');
        }

        return $this->render('playlist/delete.html.twig', [
            'form' => $form->createView(),
            'genres' => $genreRepository->getAllGenres(),
        ]);
    }

    #[Route('/playlist-edit/{id}', name: 'app_playlist_edit')]
    public function edit(Playlist $playlist, EntityManagerInterface $entityManager, Request $request, GenreRepository $genreRepository): Response
    {
        if ($playlist->getUser() !== $this->getUser()) {
            return $this->redirectToRoute('app_profil');
        }

        $form = $this->createForm(PlaylistType::class, $playlist);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_profil');
        }

        return $this->render('playlist/edit.html.twig', [
            'form' => $form->createView(),
            'genres' => $genreRepository->getAllGenres(),
        ]);
    }
}