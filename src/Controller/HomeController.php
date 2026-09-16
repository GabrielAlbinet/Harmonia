<?php

namespace App\Controller;

use App\Repository\AlbumRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Enum\AlbumType;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(AlbumRepository $albumRepository): Response
    {
        $album = $albumRepository->findBy(['type' => AlbumType::ALBUM]);
        $single = $albumRepository->findBy(['type' => AlbumType::SINGLE]);
        $EP = $albumRepository->findBy(['type' => AlbumType::EP]);

        return $this->render('home/index.html.twig', [
            'album' => $album,
            'single' => $single,
            'EP' => $EP,
        ]);
    }
}