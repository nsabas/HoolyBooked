<?php

namespace App\Domain\Unavailability\Service;

use App\Domain\Model\Unavailability;
use App\Domain\Unavailability\Enum\UnavailabilityTypeEnum;
use App\Domain\Unavailability\Service\UnavailabilityStrategyInterface;

class DayUnavailabilityStrategy implements UnavailabilityStrategyInterface
{
    public function supports(Unavailability $unavailability): bool
    {
        return $unavailability->getType() === UnavailabilityTypeEnum::day->name;
    }

    public function isUnavailable(\DateTimeInterface $date, Unavailability $unavailability): bool
    {
        return $date->format('N') === $unavailability->getValue();
    }
}
