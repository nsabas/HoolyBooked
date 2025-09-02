<?php

namespace App\Application\Campus\DTO;

use App\Application\Slot\DTO\SlotOutput;

class CampusOutput
{
    /**
     * @param string $name
     * @param SlotOutput[] $slots
     */
    public function __construct(
        public string $name,
        public array $slots
    ) {}
}
