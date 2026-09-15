<?php

namespace App\Factory;

use App\Entity\Album;
use App\Entity\Enum\AlbumType;
use Zenstruck\Foundry\Persistence\PersistentObjectFactory;

/**
 * @extends PersistentObjectFactory<Album>
 */
final class AlbumFactory extends PersistentObjectFactory
{
    public function __construct()
    {
    }

    #[\Override]
    public static function class(): string
    {
        return Album::class;
    }

    #[\Override]
    protected function defaults(): array|callable
    {
        $array = ["1.jpeg", "2.jpeg", "3.jpeg", "4.jpeg", "5.jpeg", "6.jpeg", "7.jpeg", "8.jpeg", "9.jpeg", "10.jpeg"];

        return [
            'label' => self::faker()->sentence(3),
            'createdAt' => \DateTimeImmutable::createFromMutable(self::faker()->dateTimeBetween('-10 years', 'now')),
            'cover' => 'uploads/' . self::faker()->randomElement($array),
            'type' => self::faker()->randomElement(AlbumType::cases()),
            'artist' => ArtistFactory::random(),
        ];
    }

    #[\Override]
    protected function initialize(): static
    {
        return $this;
    }
}