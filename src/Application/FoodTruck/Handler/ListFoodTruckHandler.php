<?php

namespace App\Application\FoodTruck\Handler;

use App\Application\FoodTruck\Factory\FoodTruckDTOFactory;
use App\Application\FoodTruck\Port\Database\FoodTruckDatabasePort;
use App\Application\FoodTruck\Query\ListFoodTruckQuery;
use App\Domain\Model\FoodTruck;

class ListFoodTruckHandler
{
    public function __construct(
        private FoodTruckDatabasePort $databasePort
    ) {}

    /**
     * @param ListFoodTruckQuery $foodTruckQuery
     * @return FoodTruck[]
     */
    public function handle(ListFoodTruckQuery $foodTruckQuery): array
    {
        return FoodTruckDTOFactory::createFromArray(
            $this->databasePort->findByType($foodTruckQuery->getQueryParams())
        );
    }
}
