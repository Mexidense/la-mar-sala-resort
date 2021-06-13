<?php

declare(strict_types=1);

namespace Tests\Domain\Validators;

use DateTimeImmutable;
use Domain\Validators\BookingAvailabilityValidator;
use PHPUnit\Framework\TestCase;

final class BookingAvailabilityValidatorTest extends TestCase
{
    /** @dataProvider bookingIsAvailableDataProvider */
    public function testBookingIsAvailable(
        string $checkInDate,
        string $checkOutDate,
        string $dateToCheck,
        bool $isAvailable
    ): void {
        $dateFormat = 'd-m-Y';
        $checkInDate = DateTimeImmutable::createFromFormat($dateFormat, $checkInDate);
        $checkOutDate = DateTimeImmutable::createFromFormat($dateFormat, $checkOutDate);
        $dateToCheck = DateTimeImmutable::createFromFormat($dateFormat, $dateToCheck);

        $this->assertEquals(
            $isAvailable,
            BookingAvailabilityValidator::isAvailable(
                $checkInDate,
                $checkOutDate,
                $dateToCheck
            )
        );
    }

    public function bookingIsAvailableDataProvider(): array
    {
        return [
            [
                '01-01-2021',
                '01-02-2021',
                '01-03-2021',
                true
            ],
            [
                '01-01-2021',
                '01-02-2021',
                '01-12-2020',
                true
            ],
            [
                '01-01-2021',
                '01-02-2021',
                '01-01-2021',
                false
            ],
            [
                '01-01-2021',
                '01-02-2021',
                '01-02-2021',
                false
            ],
        ];
    }
}
