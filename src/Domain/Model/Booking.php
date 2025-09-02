<?php

namespace App\Domain\Model;

use App\Domain\Model\Common\IdentifierTrait;
use DateTimeImmutable;

class Booking
{
    use IdentifierTrait;

    protected string $name;

    protected string $status;

    protected DateTimeImmutable $startAt;

    protected DateTimeImmutable $endAt;

    protected FoodTruck $foodTruck;

    protected Slot $slot;

    protected string $email;

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getStartAt(): DateTimeImmutable
    {
        return $this->startAt;
    }

    public function setStartAt(DateTimeImmutable $startAt): self
    {
        $this->startAt = $startAt;

        return $this;
    }

    public function getEndAt(): DateTimeImmutable
    {
        return $this->endAt;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function setEndAt(DateTimeImmutable $endAt): self
    {
        $this->endAt = $endAt;

        return $this;
    }

    public function getFoodTruck(): FoodTruck
    {
        return $this->foodTruck;
    }

    public function setFoodTruck(FoodTruck $foodTruck): self
    {
        $this->foodTruck = $foodTruck;

        return $this;
    }

    public function getSlot(): Slot
    {
        return $this->slot;
    }

    public function setSlot(Slot $slot): self
    {
        $this->slot = $slot;

        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }
}
