<?php

declare(strict_types = 1);

namespace Domain\Models;

final class Room implements Stringable
{
    private string $number;

    public function __construct(string $number)
    {
        $this->number = $number;
    }

    public function number(): string
    {
        return $this->number;
    }

    public function equals(Room $roomToCompare): bool
    {
        return $this->number === $roomToCompare->number();
    }

    public function __toString(): string
    {
        return sprintf('Room number: %s ', $this->number);
    }
}