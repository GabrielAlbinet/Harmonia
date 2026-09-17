<?php

namespace App\Controller;

use App\Entity\Album;
use App\Form\AlbumsType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\GenreRepository;
use App\Repository\TrackRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class AlbumController extends AbstractController
{
    #[Route('/album/{id}', name: 'app_album_item')]
    public function item(Album $album, TrackRepository $trackRepository, GenreRepository $genreRepository): Response
    {
        return $this->render('album/index.html.twig', [
            'album' => $album,
            'tracks' => $trackRepository->findBy(['album' => $album]),
            'genres' => $genreRepository->getAllGenres(),
        ]);
    }

    #[Route('/album-create', name: 'app_album_create')]
    public function create(EntityManagerInterface $entityManager, Request $request, GenreRepository $genreRepository): Response
    {
        $album = new Album();
        $form = $this->createForm(AlbumsType::class, $album);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $album->setCreatedAt(new \DateTimeImmutable());
            $entityManager->persist($album);
            $entityManager->flush();

            return $this->redirectToRoute('app_home');
        }

        return $this->render('album/add.html.twig', [
            'form' => $form->createView(),
            'genres' => $genreRepository->getAllGenres(),
        ]);
    }
}