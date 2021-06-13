<?php

declare(strict_types=1);

namespace Domain\ValueObjects;

final class RoomNumber
{
    private string $roomNumber;

    private function __construct(string $roomNumber)
    {
        $this->roomNumber = $roomNumber;
    }

    public static function create(?string $roomNumber): self
    {
        if (empty($roomNumber)) {
            throw new InvalidRoomNumber();
        }

        return new self($roomNumber);
    }

    public function value(): string
    {
        return $this->roomNumber;
    }
}
