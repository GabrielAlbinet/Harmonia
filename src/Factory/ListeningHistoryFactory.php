<?php

namespace App\Factory;

use App\Entity\ListeningHistory;
use App\Repository\ListeningHistoryRepository;
use Doctrine\ORM\EntityRepository;
use Zenstruck\Foundry\Persistence\PersistentObjectFactory;
use Zenstruck\Foundry\Persistence\Proxy;
use Zenstruck\Foundry\Persistence\ProxyRepositoryDecorator;

/**
 * @extends PersistentObjectFactory<ListeningHistory>
 */
final class ListeningHistoryFactory extends PersistentObjectFactory
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
        return ListeningHistory::class;
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    #[\Override]
    protected function defaults(): array|callable
    {
        $track = TrackFactory::random();
        return [
            'listenedAt' => \DateTimeImmutable::createFromMutable(self::faker()->dateTimeBetween(\DateTime::createFromImmutable($track->getAlbum()->getCreatedAt()),'now')),            
            'track' => $track,
            'user' => UserFactory::random(),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    #[\Override]
    protected function initialize(): static
    {
        return $this
            // ->afterInstantiate(function(ListeningHistory $listeningHistory): void {})
        ;
    }
}
