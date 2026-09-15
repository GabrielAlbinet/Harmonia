<?php

namespace App\Factory;

use App\Entity\Track;
use App\Repository\TrackRepository;
use Doctrine\ORM\EntityRepository;
use Zenstruck\Foundry\Persistence\PersistentObjectFactory;
use Zenstruck\Foundry\Persistence\Proxy;
use Zenstruck\Foundry\Persistence\ProxyRepositoryDecorator;

/**
 * @extends PersistentObjectFactory<Track>
 */
final class TrackFactory extends PersistentObjectFactory
{
    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services
     *
     * @todo inject services if required
     */
    public function __construct()
    {
    }

    #[\Override]
    public static function class(): string
    {
        return Track::class;
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    #[\Override]
    protected function defaults(): array|callable
    {
        return [
            'album' => AlbumFactory::random(),
            'genres' => GenreFactory::randomRange(1, 3),
            'duration' => self::faker()->numberBetween(120, 360),
            'explicit' => self::faker()->boolean(15),
            'label' => self::faker()->sentence(2),
            'playCount' => self::faker()->numberBetween(0, 500000),
            'trackNumber' => self::faker()->numberBetween(1, 15),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    #[\Override]
    protected function initialize(): static
    {
        return $this
            // ->afterInstantiate(function(Track $track): void {})
        ;
    }
}
