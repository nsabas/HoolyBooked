<?php

namespace App\Domain\Unavailability\Service;

use App\Domain\Model\Unavailability;

class UnavailabilityChecker
{
    /**
     * @param UnavailabilityStrategyInterface[] $strategies
     */
    public function __construct(
        private iterable $strategies
    ) {}

    public function isUnavailable(\DateTimeInterface $date, Unavailability $unavailability): bool
    {
        foreach ($this->strategies as $strategy) {
            if ($strategy->supports($unavailability)) {
                return $strategy->isUnavailable($date, $unavailability);
            }
        }

        throw new \LogicException('No strategy found for unavailability type ' . $unavailability->getType());
    }
}
