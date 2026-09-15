<?php

namespace App\DataFixtures;

use App\Factory\GenreFactory;
use App\Factory\ArtistFactory;
use App\Factory\UserFactory;
use App\Entity\Enum\AlbumType;
use App\Factory\AlbumFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);
        GenreFactory::createMany(10);

        UserFactory::createMany(10);

        ArtistFactory::createOne(['biography' => null]);
        ArtistFactory::createMany(10);

        AlbumFactory::createOne(['type' => AlbumType::ALBUM]);
        AlbumFactory::createOne(['type' => AlbumType::EP]);
        AlbumFactory::createOne(['type' => AlbumType::SINGLE]);
        AlbumFactory::createMany(10);

        $manager->flush();
    }
}
