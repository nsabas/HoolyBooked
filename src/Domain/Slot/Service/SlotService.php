<?php

namespace App\Domain\Slot\Service;

use App\Application\Slot\Port\Database\SlotDatabasePort;
use App\Domain\Model\Slot;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class SlotService
{
    public function __construct(
        private SlotDatabasePort $slotDatabasePort
    ) {}

    public function isSlotAvailable(Slot $slot, \DateTimeInterface $date): bool
    {
        return $this->slotDatabasePort->hasBookingScheduled($slot, $date);
    }
}
