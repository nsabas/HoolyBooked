<?php

namespace App\Domain\Unavailability\Enum;

enum UnavailabilityTypeEnum
{
    case day;

    case month;

    case year;

    case specific_date;
}
