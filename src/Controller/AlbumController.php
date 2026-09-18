<?php

namespace App\Controller;

use App\Entity\Album;
use App\Form\AlbumsType;
use App\Form\DeleteAlbumType;
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
        dump($request->files->all());
        if ($form->isSubmitted()) {
            dump($form->getErrors(true, false));
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $coverFile = $form->get('coverFile')->getData();

            if ($coverFile) {
                $newFilename = uniqid() . '.' . $coverFile->guessExtension();

                $coverFile->move(
                    $this->getParameter('kernel.project_dir') . '/public/uploads',
                    $newFilename
                );

                $album->setCover('uploads/' . $newFilename);
            }

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

    #[Route('/album-edit/{id}', name: 'app_album_edit')]
    public function edit(Album $album, EntityManagerInterface $entityManager, Request $request, GenreRepository $genreRepository): Response
    {
        $form = $this->createForm(AlbumsType::class, $album, [
            'isCreation' => false,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $coverFile = $form->get('coverFile')->getData();

            if ($coverFile) {
                $newFilename = uniqid() . '.' . $coverFile->guessExtension();

                $coverFile->move(
                    $this->getParameter('kernel.project_dir') . '/public/uploads',
                    $newFilename
                );

                $album->setCover('uploads/' . $newFilename);
            }

            $entityManager->flush();

            return $this->redirectToRoute('app_album_item', ['id' => $album->getId()]);
        }

        return $this->render('album/edit.html.twig', [
            'form' => $form->createView(),
            'genres' => $genreRepository->getAllGenres(),
        ]);
    }

    #[Route('/album-delete', name: 'app_album_delete')]
    public function delete(EntityManagerInterface $entityManager, Request $request, GenreRepository $genreRepository): Response
    {
        $form = $this->createForm(DeleteAlbumType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $album = $form->get('album')->getData();
            $entityManager->remove($album);
            $entityManager->flush();

            $this->addFlash('success', 'Album supprimé avec succès (ainsi que ses morceaux).');

            return $this->redirectToRoute('app_home');
        }

        return $this->render('album/delete.html.twig', [
            'form' => $form->createView(),
            'genres' => $genreRepository->getAllGenres(),
        ]);
    }
}