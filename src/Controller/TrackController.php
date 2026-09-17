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
}