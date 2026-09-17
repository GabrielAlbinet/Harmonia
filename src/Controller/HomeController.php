<?php

namespace App\Controller;

use App\Repository\AlbumRepository;
use App\Repository\TrackRepository;
use App\Repository\GenreRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Enum\AlbumType;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(AlbumRepository $albumRepository, TrackRepository $trackRepository, GenreRepository $genreRepository): Response
    {
        $album = $albumRepository->findBy(['type' => AlbumType::ALBUM]);
        $single = $albumRepository->findBy(['type' => AlbumType::SINGLE]);
        $EP = $albumRepository->findBy(['type' => AlbumType::EP]);
        $tracks = $trackRepository->findAll();
        $albumsPost2020s = $albumRepository->getAlbumsPost2020s();
        $albumsPre2020s = $albumRepository->getAlbumsPre2020s();
        dump($albumsPost2020s);
        dump($albumsPre2020s);

        $user = $this->getUser();
        dump($user);

        return $this->render('home/index.html.twig', [
            'album' => $album,
            'single' => $single,
            'EP' => $EP,
            'albumsPost2020s' => $albumsPost2020s,
            'albumsPre2020s' => $albumsPre2020s,
            'tracks' => $tracks,
            'genres' => $genreRepository->getAllGenres()
        ]);
    }
}