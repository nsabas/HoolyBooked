<?php

namespace App\Application\Campus\Command;

use App\Application\Slot\DTO\SlotInput;
use Symfony\Component\Validator\Constraints as Assert;

class CreateCampusCommand
{
    /**
     * @param string $name
     * @param SlotInput[] $slots
     */
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Length(min: 1, max: 180)]
        public string $name,
        public array $slots
    ) {}
}
