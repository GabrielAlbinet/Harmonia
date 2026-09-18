<?php

namespace App\Controller;

use App\Entity\Artist;
use App\Form\ArtistType;
use App\Form\DeleteArtistType;
use App\Repository\ArtistRepository;
use App\Repository\GenreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ArtistController extends AbstractController
{
    #[Route('/artist-add', name: 'app_artist_add')]
    public function create(EntityManagerInterface $entityManager, Request $request, GenreRepository $genreRepository): Response
    {
        $artist = new Artist();
        $form = $this->createForm(ArtistType::class, $artist);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($artist);
            $entityManager->flush();

            return $this->redirectToRoute('app_artist_list');
        }

        return $this->render('artist/add.html.twig', [
            'form' => $form->createView(),
            'genres' => $genreRepository->getAllGenres(),
        ]);
    }

    #[Route('/artist', name: 'app_artist_list')]
    public function list(ArtistRepository $artistRepository, GenreRepository $genreRepository): Response
    {
        return $this->render('artist/index.html.twig', [
            'artists' => $artistRepository->findAll(),
            'genres' => $genreRepository->getAllGenres(),
        ]);
    }
    #[Route('/artist-edit/{id}', name: 'app_artist_edit')]
    public function edit(Artist $artist, EntityManagerInterface $entityManager, Request $request, GenreRepository $genreRepository): Response
    {
        $form = $this->createForm(ArtistType::class, $artist);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_artist_list');
        }

        return $this->render('artist/edit.html.twig', [
            'form' => $form->createView(),
            'genres' => $genreRepository->getAllGenres(),
        ]);
    }

    #[Route('/artist-delete', name: 'app_artist_delete')]
    public function delete(EntityManagerInterface $entityManager, Request $request, GenreRepository $genreRepository): Response
    {
        $form = $this->createForm(DeleteArtistType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $artist = $form->get('artist')->getData();
            $entityManager->remove($artist);
            $entityManager->flush();

            $this->addFlash('success', 'Artiste supprimé avec succès (ainsi que ses albums et morceaux).');

            return $this->redirectToRoute('app_artist_list');
        }

        return $this->render('artist/delete.html.twig', [
            'form' => $form->createView(),
            'genres' => $genreRepository->getAllGenres(),
        ]);
    }
}