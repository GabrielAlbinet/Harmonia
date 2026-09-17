<?php

namespace App\Controller;

use App\Entity\Playlist;
use App\Form\PlaylistType;
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
}