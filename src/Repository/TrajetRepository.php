<?php

namespace App\Repository;

use App\Entity\Trajet;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Trajet>
 */
class TrajetRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Trajet::class);
    }
    public function findAllLessThanToday( ): array
    {
$entityManager = $this->getEntityManager();

$now = new \DateTime();

$query = $entityManager->createQuery(
    'SELECT t
    FROM App\Entity\Trajet t
    WHERE t.Date < :today
    OR (t.Date = :today AND t.Time < :time) 
    ORDER BY t.id ASC'
)->setParameter('today', $now->format('Y-m-d'))
->setParameter('time', $now->format('H:i:s'));

// returns an array of Trajet objects
return $query->getResult();
    }


    public function findAllAfterThanToday( ): array
    {
$entityManager = $this->getEntityManager();

$now = new \DateTime();

$query = $entityManager->createQuery(
    'SELECT t
    FROM App\Entity\Trajet t
    WHERE t.Date > :today
    OR (t.Date = :today AND t.Time > :time) 
    ORDER BY t.id ASC'
)->setParameter('today', $now->format('Y-m-d'))
->setParameter('time', $now->format('H:i:s'));

// returns an array of Trajet objects
return $query->getResult();
    }

    public function findAllCreated(int $owner): array
    {
        $entityManager = $this->getEntityManager();

        $now = new \DateTime();

        $query = $entityManager->createQuery(
            'SELECT t
            FROM App\Entity\Trajet t
            WHERE (t.Date < :today AND t.owner_id = :owner)
            OR (t.Date = :today AND t.Time > :time AND t.owner_id = :owner)
            ORDER BY t.id ASC'
        )->setParameter('today', $now->format('Y-m-d'))
         ->setParameter('time', $now->format('H:i:s'))
         ->setParameter('owner', $owner);

        return $query->getResult();
    }

    public function findAllJoined(int $owner): array
    {
        $entityManager = $this->getEntityManager();

        $now = new \DateTime();

        $query = $entityManager->createQuery(
            'SELECT t
            FROM App\Entity\Reservation r
            JOIN App\Entity\Trajet t
            WITH r.idtrajet = t.id
            WHERE (t.Date < :today AND r.iduser = :owner)
            OR (t.Date = :today AND t.Time > :time AND r.iduser = :owner)
            ORDER BY t.id ASC'
        )->setParameter('today', $now->format('Y-m-d'))
         ->setParameter('time', $now->format('H:i:s'))
         ->setParameter('owner', $owner);

        return $query->getResult();
    }


    public function findAllCurrentCreated(int $owner): array
    {
        $entityManager = $this->getEntityManager();

        $now = new \DateTime();

        $query = $entityManager->createQuery(
            'SELECT t
            FROM App\Entity\Trajet t
            WHERE (t.Date > :today AND t.owner_id = :owner)
            OR (t.Date = :today AND t.Time < :time AND t.owner_id = :owner)
            ORDER BY t.id ASC'
        )->setParameter('today', $now->format('Y-m-d'))
         ->setParameter('time', $now->format('H:i:s'))
         ->setParameter('owner', $owner);

        return $query->getResult();
    }

    public function findAllCurrentJoined(int $owner): array
    {
        $entityManager = $this->getEntityManager();

        $now = new \DateTime();

        $query = $entityManager->createQuery(
            'SELECT t
            FROM App\Entity\Reservation r
            JOIN App\Entity\Trajet t
            WITH r.idtrajet = t.id
            WHERE (t.Date > :today AND r.iduser = :owner)
            OR (t.Date = :today AND t.Time < :time AND r.iduser = :owner)
            ORDER BY t.id ASC'
        )->setParameter('today', $now->format('Y-m-d'))
         ->setParameter('time', $now->format('H:i:s'))
         ->setParameter('owner', $owner);

        return $query->getResult();
    }

//    /**
//     * @return Trajet[] Returns an array of Trajet objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('t.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Trajet
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
