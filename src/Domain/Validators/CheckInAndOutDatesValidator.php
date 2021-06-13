<?php

declare(strict_types = 1);

namespace Domain\Validators;

use DateTimeImmutable;

final class CheckInAndOutDatesValidator
{
    public static function validate(DateTimeImmutable $checkIn, DateTimeImmutable $checkOut): bool
    {
        return $checkIn <= $checkOut;
    }
}