<?php

namespace App\Controller;

use App\Entity\Favorite;
use App\Entity\Track;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class FavoriteController extends AbstractController
{
    #[Route('/favorite/add/{id}', name: 'app_favorite_add')]
    public function add(Track $track, EntityManagerInterface $entityManager): Response
    {
        $favorite = new Favorite();
        $favorite->setUser($this->getUser());
        $favorite->setTrack($track);
        $favorite->setAddedAt(new \DateTimeImmutable());

        $entityManager->persist($favorite);
        $entityManager->flush();

        return $this->redirectToRoute('app_track');
    }
}