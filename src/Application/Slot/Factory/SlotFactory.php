<?php

namespace App\Application\Slot\Factory;

use App\Application\Slot\DTO\SlotInput;
use App\Domain\Model\Slot;
use Symfony\Component\Uid\Uuid;

class SlotFactory
{
    public static function createFromInput(SlotInput $input): Slot
    {
        return (new Slot)
            ->setUid(Uuid::v4())
            ->setName($input->name)
            ->setFullAddress($input->fullAddress)
            ->setStreet($input->street)
            ->setCity($input->city)
            ->setState($input->state)
            ->setZipCode($input->zipCode)
            ->setCountry($input->country)
        ;
    }

    public static function createBatchFromInput(array $inputs): array
    {
        $slots = [];

        foreach ($inputs as $input) {
            $slots[] = self::createFromInput($input);
        }

        return $slots;
    }
}
