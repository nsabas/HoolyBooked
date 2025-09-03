<?php

namespace App\Domain\Model;

use App\Domain\Model\Common\IdentifierTrait;

class Unavailability
{
    use IdentifierTrait;

    protected string $type;

    protected string $value;

    protected Slot $slot;

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): void
    {
        $this->type = $type;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function setValue(string $value): void
    {
        $this->value = $value;
    }

    public function getSlot(): Slot
    {
        return $this->slot;
    }

    public function setSlot(Slot $slot): void
    {
        $this->slot = $slot;
    }
}
