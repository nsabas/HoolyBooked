<?php

namespace App\Domain\Model;


use App\Domain\Model\Common\IdentifierTrait;
use Doctrine\Common\Collections\Collection;

class FoodTruck
{
    use IdentifierTrait;

    protected string $name;

    protected string $status;

    protected string $description;

    protected  string $type;

    /**
     * @var Collection $bookings
     */
    protected Collection $bookings;

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
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

    /**
     * @return Collection
     */
    public function getBookings(): Collection
    {
        return $this->bookings;
    }

    /**
     * @param Collection $bookings
     * @return void
     */
    public function setBookings(Collection $bookings): void
    {
        $this->bookings = $bookings;
    }

    public function addBooking(Booking $booking): self
    {
        if (!$this->bookings->contains($booking)) {
            $booking->setFoodTruck($this);
            $this->bookings->add($booking);
        }

        return $this;
    }

    public function removeBooking(Booking $booking): self
    {
        if ($this->bookings->contains($booking)) {
            $this->bookings->removeElement($booking);
        }

        return $this;
    }
}
