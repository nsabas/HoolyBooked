<?php

namespace App\Domain\FoodTruck\Enum;

enum FoodTruckStatusEnum
{
    case active;
    case inactive;
    case maintenance;
}
