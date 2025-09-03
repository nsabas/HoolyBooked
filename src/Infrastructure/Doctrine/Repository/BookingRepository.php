<?php

namespace App\Infrastructure\Doctrine\Repository;

use App\Application\Booking\Port\Database\BookingDatabasePort;
use App\Domain\Booking\Enum\BookingStatusEnum;
use App\Domain\Model\Booking;
use App\Domain\Model\Campus;
use App\Domain\Model\FoodTruck;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class BookingRepository extends ServiceEntityRepository implements BookingDatabasePort
{
    public function __construct(private readonly ManagerRegistry $registry,)
    {
        parent::__construct($this->registry, Booking::class);
    }

    public function save(Booking $foodTruck, bool $flush = false): void
    {
        $this->getEntityManager()->persist($foodTruck);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findByUid(string $uid): ?Booking
    {
        return $this->findOneBy(['uid' => $uid]);
    }

    public function hasOverlappingBooking(FoodTruck $foodTruck, \DateTimeImmutable $start, \DateTimeImmutable $end): bool
    {
        $qb = $this->createQueryBuilder('b')
            ->select('COUNT(b.id)')
            ->where('b.foodTruck = :foodTruck')
            ->andWhere('b.startAt <= :end')
            ->andWhere('b.endAt >= :start')
            ->setParameter('foodTruck', $foodTruck)
            ->setParameter('start', $start)
            ->setParameter('end', $end)
        ;

        return (bool) $qb->getQuery()->getSingleScalarResult() > 0;
    }

    public function findBookingByDate(\DateTimeImmutable $date = null, string $campusUid = null): array
    {
        $queryBuilder = $this->createQueryBuilder('b');

        if ($date) {
            $queryBuilder
                ->andWhere('b.startAt <= :date')
                ->andWhere('b.endAt >= :date')
                ->setParameter('date', $date);
        }

        if ($campusUid) {
            $queryBuilder
                ->leftJoin('b.slot', 's')
                ->leftJoin('s.campus', 'c')
                ->andWhere('c.uid = :campusUid')
                ->setParameter('campusUid', $campusUid);
        }

        return $queryBuilder
            ->andWhere('b.status = :status')
            ->setParameter('status', BookingStatusEnum::confirmed->name)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @param \DateTimeImmutable $date
     * @return Booking[]
     */
    public function getBookingStartAt(\DateTimeImmutable $date): array
    {
        return $this->createQueryBuilder('b')
            ->where('b.startAt = :date')
            ->andWhere('b.status = :status')
            ->setParameter('date', $date)
            ->setParameter('status', BookingStatusEnum::confirmed->name)
            ->getQuery()
            ->getResult()
        ;
    }

    public function hasBookingBetweenFor(FoodTruck $foodTruck, Campus $campus, \DateTimeImmutable $start, \DateTimeImmutable $end): bool
    {
        return $this->createQueryBuilder('b')
            ->select('COUNT(b.id)')
            ->join('b.foodTruck', 'f')
            ->join('b.slot', 's')
            ->join('s.campus', 'c')
            ->andWhere('c = :campus')
            ->andWhere('b.startAt BETWEEN :start AND :end')
            ->andWhere('f = :foodTruck')
            ->andWhere('b.status = :status')
            ->setParameter('start', $start)
            ->setParameter('end', $end)
            ->setParameter('foodTruck', $foodTruck)
            ->setParameter('campus', $campus)
            ->setParameter('status', BookingStatusEnum::confirmed->name)
            ->getQuery()
            ->getSingleScalarResult() > 0
        ;
    }
}
