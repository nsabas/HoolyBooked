<?php

namespace App\Tests\Domain\Unavailability\Service;

use App\Domain\Model\Unavailability;
use App\Domain\Unavailability\Enum\UnavailabilityTypeEnum;
use App\Domain\Unavailability\Service\DayUnavailabilityStrategy;
use PHPUnit\Framework\TestCase;

class DayUnavailabilityStrategyTest extends TestCase
{
    private DayUnavailabilityStrategy $strategy;

    protected function setUp(): void
    {
        $this->strategy = new DayUnavailabilityStrategy();
    }

    public function testSupportsReturnsTrueForDayType(): void
    {
        $unavailability = $this->createMock(Unavailability::class);
        $unavailability->method('getType')->willReturn(UnavailabilityTypeEnum::day->name);

        $this->assertTrue($this->strategy->supports($unavailability));
    }

    public function testSupportsReturnsFalseForOtherType(): void
    {
        $unavailability = $this->createMock(Unavailability::class);
        $unavailability->method('getType')->willReturn(UnavailabilityTypeEnum::month->name);

        $this->assertFalse($this->strategy->supports($unavailability));
    }

    public function testIsUnavailableReturnsTrueWhenDayMatches(): void
    {
        $date = new \DateTimeImmutable('2023-06-14'); // mercredi -> 3

        $unavailability = $this->createMock(Unavailability::class);
        $unavailability->method('getValue')->willReturn('3'); // mercredi

        $this->assertTrue($this->strategy->isUnavailable($date, $unavailability));
    }

    public function testIsUnavailableReturnsFalseWhenDayDoesNotMatch(): void
    {
        $date = new \DateTimeImmutable('2023-06-14'); // mercredi -> 3

        $unavailability = $this->createMock(Unavailability::class);
        $unavailability->method('getValue')->willReturn('5'); // vendredi

        $this->assertFalse($this->strategy->isUnavailable($date, $unavailability));
    }
}
