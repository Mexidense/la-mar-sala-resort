<?php

declare(strict_types=1);

namespace Domain\Models;

use Domain\ValueObjects\RoomNumber;

final class Room implements Stringable
{
    private RoomNumber $number;

    public function __construct(string $number)
    {
        $this->number = RoomNumber::create($number);
    }

    public function roomNumber(): RoomNumber
    {
        return $this->number;
    }

    public function equals(Room $roomToCompare): bool
    {
        return $this->number === $roomToCompare->roomNumber();
    }

    public function __toString(): string
    {
        return sprintf('Room number: %s ', $this->number->value());
    }
}
