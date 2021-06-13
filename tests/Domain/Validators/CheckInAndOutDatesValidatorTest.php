<?php

declare(strict_types=1);

namespace Tests\Domain\Validators;

use DateTimeImmutable;
use Domain\Validators\CheckInAndOutDatesValidator;
use PHPUnit\Framework\TestCase;

final class CheckInAndOutDatesValidatorTest extends TestCase
{
    /** @dataProvider checkInAndOutDatesAreValidDataProvider */
    public function testCheckInAndOutDateAreValid(
        string $checkInDate,
        string $checkOutDate,
        bool $isValid
    ): void {
        $dateFormat = 'd-m-Y';
        $checkInDate = DateTimeImmutable::createFromFormat($dateFormat, $checkInDate);
        $checkOutDate = DateTimeImmutable::createFromFormat($dateFormat, $checkOutDate);

        $this->assertEquals(
            $isValid,
            CheckInAndOutDatesValidator::validate(
                $checkInDate,
                $checkOutDate
            )
        );
    }

    public function checkInAndOutDatesAreValidDataProvider(): array
    {
        return [
            [
                '01-01-2021',
                '01-02-2021',
                true,
            ],
            [
                '01-01-2021',
                '01-01-2021',
                true,
            ],
            [
                '01-02-2021',
                '01-01-2021',
                false,
            ],
        ];
    }
}
