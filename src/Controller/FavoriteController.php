<?php

namespace App\Controller;

use App\Entity\Favorite;
use App\Entity\Track;
use App\Repository\FavoriteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class FavoriteController extends AbstractController
{
    #[Route('/favorite/toggle/{id}', name: 'app_favorite_toggle', methods: ['POST'])]
    public function toggle(Track $track, EntityManagerInterface $entityManager, FavoriteRepository $favoriteRepository): JsonResponse
    {
        $user = $this->getUser();

        if ($user === null) {
            return $this->json(['error' => 'Vous devez être connecté.'], Response::HTTP_UNAUTHORIZED);
        }

        $existing = $favoriteRepository->findOneBy([
            'user' => $user,
            'track' => $track,
        ]);

        if ($existing !== null) {
            $entityManager->remove($existing);
            $isFavorited = false;
        } else {
            $favorite = new Favorite();
            $favorite->setUser($user);
            $favorite->setTrack($track);
            $favorite->setAddedAt(new \DateTimeImmutable());
            $entityManager->persist($favorite);
            $isFavorited = true;
        }

        $entityManager->flush();

        return $this->json([
            'favorited' => $isFavorited
        ]);
    }
}