<?php

namespace App\Controller;

use App\Repository\FavoriteRepository;
use App\Repository\GenreRepository;
use App\Repository\TrackRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Album;
use App\Entity\Track;
use App\Form\TrackType;
use App\Form\DeleteTrackType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

final class TrackController extends AbstractController
{
    #[Route('/track', name: 'app_track')]
    public function index(TrackRepository $trackRepository, GenreRepository $genreRepository, FavoriteRepository $favoriteRepository): Response
    {
        $favoritedTrackIds = [];
        $user = $this->getUser();

        if ($user !== null) {
            $favorites = $favoriteRepository->findBy(['user' => $user]);
            foreach ($favorites as $favorite) {
                $favoritedTrackIds[] = $favorite->getTrack()->getId();
            }
        }

        return $this->render('track/index.html.twig', [
            'tracks' => $trackRepository->findAll(),
            'genres' => $genreRepository->getAllGenres(),
            'favoritedTrackIds' => $favoritedTrackIds,
        ]);
    }

    #[Route('/album/{id}/track-add', name: 'app_track_add')]
    public function create(Album $album, EntityManagerInterface $entityManager, Request $request, GenreRepository $genreRepository): Response
    {
        $track = new Track();
        $track->setAlbum($album);
        $track->setPlayCount(0);

        $form = $this->createForm(TrackType::class, $track);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($track);
            $entityManager->flush();

            return $this->redirectToRoute('app_album_item', ['id' => $album->getId()]);
        }

        return $this->render('track/add.html.twig', [
            'form' => $form->createView(),
            'album' => $album,
            'genres' => $genreRepository->getAllGenres(),
        ]);
    }

    #[Route('/track-edit/{id}', name: 'app_track_edit')]
    public function edit(Track $track, EntityManagerInterface $entityManager, Request $request, GenreRepository $genreRepository): Response
    {
        $form = $this->createForm(TrackType::class, $track);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_album_item', ['id' => $track->getAlbum()->getId()]);
        }

        return $this->render('track/edit.html.twig', [
            'form' => $form->createView(),
            'track' => $track,
            'genres' => $genreRepository->getAllGenres(),
        ]);
    }

    #[Route('/track-delete', name: 'app_track_delete')]
    public function delete(EntityManagerInterface $entityManager, Request $request, GenreRepository $genreRepository): Response
    {
        $form = $this->createForm(DeleteTrackType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $track = $form->get('track')->getData();
            $entityManager->remove($track);
            $entityManager->flush();

            $this->addFlash('success', 'Morceau supprimé avec succès.');

            return $this->redirectToRoute('app_track');
        }

        return $this->render('track/delete.html.twig', [
            'form' => $form->createView(),
            'genres' => $genreRepository->getAllGenres(),
        ]);
    }
}