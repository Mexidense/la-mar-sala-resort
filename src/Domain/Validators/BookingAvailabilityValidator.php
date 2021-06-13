<?php

declare(strict_types = 1);

namespace Domain\Validators;

use DateTimeImmutable;

final class BookingAvailabilityValidator
{
    public static function isAvailable(
        DateTimeImmutable $checkIn,
        DateTimeImmutable $checkOut,
        DateTimeImmutable $dateToCheck
    ): bool {
        if ($dateToCheck >= $checkIn && $dateToCheck <= $checkOut) {
            return false;
        }

        return true;
    }
}