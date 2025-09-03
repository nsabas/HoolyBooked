<?php

namespace App\Domain\Slot\Service;

use App\Application\Slot\Port\Database\SlotDatabasePort;
use App\Domain\Model\Slot;
use App\Domain\Unavailability\Service\UnavailabilityChecker;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class SlotService
{
    public function __construct(
        private SlotDatabasePort $slotDatabasePort,
        private UnavailabilityChecker $unavailabilityChecker
    ) {}

    public function isSlotAvailable(Slot $slot, \DateTimeInterface $date): bool
    {
        foreach ($slot->getUnavailabilities() as $unavailability) {
            if ($this->unavailabilityChecker->isUnavailable($date, $unavailability)) {
                throw new BadRequestHttpException('Slot is unavailable days off');
            }
        }

        return $this->slotDatabasePort->hasBookingScheduled($slot, $date);
    }
}
