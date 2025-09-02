<?php

namespace App\Application\Slot\Port\Database;

use App\Domain\Model\Slot;

interface SlotDatabasePort
{
    public function save(Slot $slot, bool $flush = false): void;

    public function hasBookingScheduled(Slot $slot, \DateTimeInterface $date): bool;

    public function getSlotByUid(string $uid): Slot;
}
