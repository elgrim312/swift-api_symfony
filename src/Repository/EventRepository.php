<?php

namespace App\Repository;

use App\Entity\Event;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Validator\Constraints\Date;

/**
 * @method Event|null find($id, $lockMode = null, $lockVersion = null)
 * @method Event|null findOneBy(array $criteria, array $orderBy = null)
 * @method Event[]    findAll()
 * @method Event[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Event::class);
    }

    public function getCurrentEvent()
    {
        $begin = new \DateTime('-5 minutes');
        $end = new \DateTime('+5 minutes');

        return $this->createQueryBuilder('e')
            ->where("e.start_at >= :begin ")
            ->andWhere("e.start_at <= :end")
            ->setParameter("begin", $begin)
            ->setParameter("end", $end)
            ->getQuery()->execute();
    }

    public function getEventToday()
    {
        $begin = new \DateTime('-1 day');
        $end = new \DateTime('+1 day');

        return $this->createQueryBuilder('e')
            ->where("e.start_at >= :begin ")
            ->andWhere("e.start_at <= :end")
            ->setParameter("begin", $begin)
            ->setParameter("end", $end)
            ->getQuery()->execute();
    }

    public function checkEventIsActive(string $date)
    {
        $begin = new \DateTime($date. ' -5 minutes');
        $end = new \DateTime($date. ' +5 minutes');

        return $this->createQueryBuilder('e')
            ->where("e.start_at >= :begin ")
            ->andWhere("e.start_at <= :end")
            ->setParameter("begin", $begin)
            ->setParameter("end", $end)
            ->getQuery()->execute()
        ;
    }
//    /**
//     * @return Event[] Returns an array of Event objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Event
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
