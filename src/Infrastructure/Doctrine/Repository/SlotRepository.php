<?php

namespace App\Infrastructure\Doctrine\Repository;

use App\Application\Slot\Port\Database\SlotDatabasePort;
use App\Domain\Booking\Enum\BookingStatusEnum;
use App\Domain\Model\Booking;
use App\Domain\Model\Slot;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class SlotRepository extends ServiceEntityRepository implements SlotDatabasePort
{
    public function __construct(private readonly ManagerRegistry $registry,)
    {
        parent::__construct($this->registry, Slot::class);
    }

    public function save(Slot $slot, bool $flush = false): void
    {
        $this->getEntityManager()->persist($slot);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function hasBookingScheduled(Slot $slot, \DateTimeInterface $date): bool
    {
        return $this->createQueryBuilder('s')
            ->join('s.bookings', 'b')
            ->select('COUNT(b.id)')
            ->where('s = :slot')
            ->andWhere('b.startAt >= :date')
            ->andWhere('b.endAt <= :date')
            ->andWhere('b.status = :status')
            ->setParameter('slot', $slot)
            ->setParameter('date', $date)
            ->setParameter('status', BookingStatusEnum::confirmed->name)
            ->getQuery()
            ->getSingleScalarResult() > 0
        ;
    }

    public function getSlotByUid(string $uid): Slot
    {
        return $this->findOneBy(['uid' => $uid]);
    }
}
