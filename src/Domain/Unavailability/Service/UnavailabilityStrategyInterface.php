<?php

namespace App\Domain\Unavailability\Service;

use App\Domain\Model\Unavailability;

interface UnavailabilityStrategyInterface
{
    public function supports(Unavailability $unavailability): bool;

    public function isUnavailable(\DateTimeInterface $date, Unavailability $unavailability): bool;
}
