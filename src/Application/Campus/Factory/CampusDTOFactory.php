<?php

namespace App\Application\Campus\Factory;

use App\Application\Campus\DTO\CampusOutput;
use App\Application\Slot\Factory\SlotDTOFactory;
use App\Domain\Model\Campus;

class CampusDTOFactory
{
    public static function createFromCampus(Campus $campus): CampusOutput
    {
        return new CampusOutput(
            name: $campus->getName(),
            slots: SlotDTOFactory::createSlotsOutputFromSlots($campus->getSlots())
        );
    }
}
