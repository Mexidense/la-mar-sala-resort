<?php

declare(strict_types=1);

namespace Domain\Entities;

use DateTimeImmutable;
use Domain\Models\Resident;
use Domain\Models\Room;

final class Booking
{
    private int $number;
    private Room $room;
    private DateTimeImmutable $checkIn;
    private DateTimeImmutable $checkOut;
    private ?Resident $resident;
    private bool $earlyCheckOut;

    public function __construct(
        int $number,
        DateTimeImmutable $checkIn,
        DateTimeImmutable $checkOut,
        Room $room,
        ?Resident $resident,
        $earlyCheckOut = false
    ) {
        $this->number = $number;
        $this->room = $room;
        $this->checkIn = $checkIn;
        $this->checkOut = $checkOut;
        $this->resident = $resident;
        $this->earlyCheckOut = $earlyCheckOut;
    }

    public function number(): int
    {
        return $this->number;
    }

    public function room(): Room
    {
        return $this->room;
    }

    public function checkIn(): DateTimeImmutable
    {
        return $this->checkIn;
    }

    public function checkOut(): DateTimeImmutable
    {
        return $this->checkOut;
    }

    public function resident(): ?Resident
    {
        return $this->resident;
    }

    public function earlyCheckOut(): bool
    {
        return $this->earlyCheckOut;
    }

    public function changeCheckOutDate(DateTimeImmutable $newCheckOut): self
    {
        return new self(
            $this->number,
            $this->checkIn,
            $newCheckOut,
            $this->room,
            $this->resident,
            true
        );
    }

    public function __toString(): string
    {
        return sprintf(
            'Number: %, Room: %s, Check-in date: %s, Check-out date: %s, Resident: %s',
            $this->number,
            $this->room->number(),
            $this->checkIn->format('d-m-Y'),
            $this->checkOut->format('d-m-Y'),
            $this->resident->fullName()
        );
    }
}