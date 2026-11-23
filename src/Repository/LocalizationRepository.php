<?php

namespace App\Repository;

use App\Entity\Localization;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Localization>
 */
class LocalizationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Localization::class);
    }

    // Użyłem tutaj copilota
    public function findByLocation(Localization $location): array
    {
        return $this->getEntityManager()->createQueryBuilder()
            ->select('weather')
            ->from(\App\Entity\Weather::class, 'weather')
            ->where('weather.Localization = :location')
            ->setParameter('location', $location)
            ->orderBy('weather.Date', 'ASC')
            ->getQuery()
            ->getResult();
    }


//    /**
//     * @return Localization[] Returns an array of Localization objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('l')
//            ->andWhere('l.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('l.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Localization
//    {
//        return $this->createQueryBuilder('l')
//            ->andWhere('l.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
