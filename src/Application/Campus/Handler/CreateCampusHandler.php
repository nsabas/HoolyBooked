<?php

namespace App\Application\Campus\Handler;

use App\Application\Campus\Command\CreateCampusCommand;
use App\Application\Campus\DTO\CampusOutput;
use App\Application\Campus\Factory\CampusDTOFactory;
use App\Application\Campus\Port\Database\CampusDatabasePort;
use App\Application\Slot\Factory\SlotFactory;
use App\Domain\Model\Campus;
use Symfony\Component\Uid\Uuid;

class CreateCampusHandler
{
    public function __construct(
        private CampusDatabasePort $campusDatabasePort
    ) {}

    public function handle(CreateCampusCommand $command): CampusOutput
    {
        $campus = (new Campus)
            ->setUid(Uuid::v4()->toRfc4122())
            ->setName($command->name)
        ;

        foreach ($command->slots as $slot) {
            $campus->addSlot(SlotFactory::createFromInput($slot));
        }

        $this->campusDatabasePort->save($campus, true);

        return CampusDTOFactory::createFromCampus($campus);
    }
}
