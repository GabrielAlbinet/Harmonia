<?php

namespace App\DataFixtures;

use App\Factory\GenreFactory;
use App\Factory\ArtistFactory;
use App\Factory\UserFactory;
use App\Entity\Enum\AlbumType;
use App\Factory\AlbumFactory;
use App\Factory\TrackFactory;
use App\Factory\PlaylistFactory;
use App\Factory\FavoriteFactory;
use App\Factory\ListeningHistoryFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

        $genres = [
            "Rock" => "red",
            "Pop" => "pink",
            "Rap" => "orange",
            "Jazz" => "gold",
            "Électro" => "cyan",
            "Classique" => "purple",
            "Metal" => "darkgray",
            "Voluntatis" => "green",
            "Dolorem" => "blue",
        ];

        foreach ($genres as $label => $color) {
            GenreFactory::createOne([
                'label' => $label,
                'color' => $color,
            ]);
        }

        UserFactory::createMany(10);

        ArtistFactory::createOne(['biography' => null]);
        ArtistFactory::createMany(10);

        AlbumFactory::createOne(['type' => AlbumType::ALBUM]);
        AlbumFactory::createOne(['type' => AlbumType::EP]);
        AlbumFactory::createOne(['type' => AlbumType::SINGLE]);
        AlbumFactory::createMany(10);

        TrackFactory::createOne(['explicit' => true]);
        TrackFactory::createMany(100);

        PlaylistFactory::createOne(['isPublic' => false]);
        PlaylistFactory::createMany(10);

        FavoriteFactory::createMany(100);
        ListeningHistoryFactory::createMany(100);

        $manager->flush();
    }
}
