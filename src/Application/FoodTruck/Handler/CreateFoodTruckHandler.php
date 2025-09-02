<?php

namespace App\Application\FoodTruck\Handler;

use App\Application\FoodTruck\Command\CreateFoodTruckCommand;
use App\Application\FoodTruck\Port\Database\FoodTruckDatabasePort;
use App\Domain\FoodTruck\Enum\FoodTruckStatusEnum;
use App\Domain\Model\FoodTruck;
use Symfony\Component\Uid\Uuid;

class CreateFoodTruckHandler
{
    public function __construct(
        private FoodTruckDatabasePort $databasePort
    ) {}

    public function handle(CreateFoodTruckCommand $command): FoodTruck
    {
        $foodTruck = (new FoodTruck)
            ->setName($command->name)
            ->setUid(Uuid::v4()->toRfc4122())
            ->setStatus(FoodTruckStatusEnum::active->name)
            ->setDescription($command->description)
            ->setType($command->type)
        ;

        $this->databasePort->save($foodTruck, true);

        return $foodTruck;
    }
}
