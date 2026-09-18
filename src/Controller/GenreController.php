<?php

namespace App\Controller;

use App\Entity\Genre;
use App\Form\GenreType;
use App\Form\DeleteGenreType;
use App\Repository\GenreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class GenreController extends AbstractController
{
    #[Route('/genre/{id}', name: 'app_genre')]
    public function show(Genre $genre, GenreRepository $genreRepository): Response
    {
        return $this->render('genre/index.html.twig', [
            'genre' => $genre,
            'genres' => $genreRepository->getAllGenres(),
        ]);
    }

    #[Route('/genre-add', name: 'app_genre_add')]
    public function create(EntityManagerInterface $entityManager, Request $request, GenreRepository $genreRepository): Response
    {
        $genre = new Genre();
        $form = $this->createForm(GenreType::class, $genre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($genre);
            $entityManager->flush();

            return $this->redirectToRoute('app_genre', ['id' => $genre->getId()]);
        }

        return $this->render('genre/add.html.twig', [
            'form' => $form->createView(),
            'genres' => $genreRepository->getAllGenres(),
        ]);
    }

    #[Route('/genre-delete', name: 'app_genre_delete')]
    public function delete(EntityManagerInterface $entityManager, Request $request, GenreRepository $genreRepository): Response
    {
        $form = $this->createForm(DeleteGenreType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $genre = $form->get('genre')->getData();
            $entityManager->remove($genre);
            $entityManager->flush();

            $this->addFlash('success', 'Genre supprimé avec succès.');

            return $this->redirectToRoute('app_home');
        }

        return $this->render('genre/delete.html.twig', [
            'form' => $form->createView(),
            'genres' => $genreRepository->getAllGenres(),
        ]);
    }

    #[Route('/genre-edit/{id}', name: 'app_genre_edit')]
    public function edit(Genre $genre, EntityManagerInterface $entityManager, Request $request, GenreRepository $genreRepository): Response
    {
        $form = $this->createForm(GenreType::class, $genre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_genre', ['id' => $genre->getId()]);
        }

        return $this->render('genre/edit.html.twig', [
            'form' => $form->createView(),
            'genres' => $genreRepository->getAllGenres(),
        ]);
    }
}