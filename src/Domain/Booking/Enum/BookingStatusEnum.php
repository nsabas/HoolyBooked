<?php

namespace App\Domain\Booking\Enum;

enum BookingStatusEnum
{
    case confirmed;

    case cancelled;

    case finished;

    /**
     * @return array<string>
     */
    public static function all(): array
    {
        return [
            self::confirmed->name,
            self::cancelled->name,
            self::finished->name,
        ];
    }
}
