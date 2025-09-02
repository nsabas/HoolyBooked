<?php

namespace App\Domain\Model;

use App\Domain\Model\Common\IdentifierTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class Campus
{
    use IdentifierTrait;

    protected string $name;

    protected Collection $slots;

    public function __construct()
    {
        $this->slots = new ArrayCollection();
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection
     */
    public function getSlots(): Collection
    {
        return $this->slots;
    }

    /**
     * @param Collection $slots
     * @return Campus
     */
    public function setSlots(Collection $slots): self
    {
        $this->slots = $slots;

        return $this;
    }

    public function addSlot(Slot $slot): self
    {
        if (!$this->slots->contains($slot)) {
            $slot->setCampus($this);
            $this->slots->add($slot);
        }

        return $this;
    }

    public function removeSlot(Slot $slot): self
    {
        if ($this->slots->contains($slot)) {
            $this->slots->removeElement($slot);
        }

        return $this;
    }
}
