<?php

namespace App\Repository;

use App\Entity\Album;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Enum\AlbumType;

/**
 * @extends ServiceEntityRepository<Album>
 */
class AlbumRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Album::class);
    }

    public function findWithRelations(AlbumType $type, ?int $limit = null): array
    {
        $qb = $this->createQueryBuilder('a')
            ->addSelect('artist', 'tracks')
            ->leftJoin('a.artist', 'artist')
            ->leftJoin('a.tracks', 'tracks')
            ->where('a.type = :type')
            ->setParameter('type', $type)
            ->orderBy('a.createdAt', 'DESC');

            if ($limit !== null) {
                $qb->setMaxResults($limit)
                    ->groupBy('a.id');
            }

            return $qb->getQuery()
                    ->getResult();
    }

    public function getAlbumsPost2020s(): array
    {
        return $this->createQueryBuilder('a')
            ->where('a.createdAt > :date')
            ->setParameter('date', new \DateTime('2020-01-01'))
            ->getQuery()->getResult();
    }

    public function getAlbumsPre2020s(): array
    {
        return $this->createQueryBuilder('a')
                ->where('a.createdAt < :date')
                ->setParameter('date', new \DateTime('2020-01-01'))
                ->getQuery()
                ->getResult();
    }

//    /**
//     * @return Album[] Returns an array of Album objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Album
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
