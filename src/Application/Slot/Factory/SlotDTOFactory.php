<?php

namespace App\Application\Slot\Factory;

use App\Application\Slot\DTO\SlotOutput;
use App\Domain\Model\Slot;
use Doctrine\Common\Collections\Collection;

class SlotDTOFactory
{
    public static function createSlotOutputFromSlot(Slot $slot): SlotOutput
    {
        return new SlotOutput(
            name: $slot->getName(),
            fullAddress: $slot->getFullAddress(),
            street: $slot->getStreet(),
            city: $slot->getCity(),
            state: $slot->getState(),
            zipCode: $slot->getZipCode(),
            country: $slot->getCountry(),
        );
    }

    public static function createSlotsOutputFromSlots(Collection $slots): array
    {
        $outputs = [];

        foreach ($slots as $slot) {
            $outputs[] = self::createSlotOutputFromSlot($slot);
        }

        return $outputs;
    }
}
