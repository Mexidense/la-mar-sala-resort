<?php

declare(strict_types=1);

namespace Tests\Domain\Models;

use DateTimeImmutable;
use Domain\Models\Resident;
use Domain\ValueObjects\InvalidDniException;
use Domain\ValueObjects\InvalidGenderException;
use PHPUnit\Framework\TestCase;

final class ResidentTest extends TestCase
{
    public function testCreateResident(): void
    {
        $resident = new Resident(
            'Martinez Gomez, Adrian',
            '27272727',
            'M',
            DateTimeImmutable::createFromFormat('d-m-Y', '12-02-1940')
        );

        $this->assertEquals('Martinez Gomez, Adrian', $resident->fullname());
        $this->assertEquals('27272727', $resident->dni()->value());
        $this->assertEquals('M', $resident->gender()->value());
        $this->assertEquals(
            DateTimeImmutable::createFromFormat('d-m-Y', '12-02-1940'),
            $resident->birthdate()
        );
    }

    public function testInvalidDniException(): void
    {
        $this->expectException(InvalidDniException::class);
        new Resident(
            'Martinez Gomez, Adrian',
            '',
            'M',
            DateTimeImmutable::createFromFormat('d-m-Y', '12-02-1940')
        );
    }

    public function testInvalidGenderException(): void
    {
        $this->expectException(InvalidGenderException::class);
        new Resident(
            'Martinez Gomez, Adrian',
            '27272727',
            'X',
            DateTimeImmutable::createFromFormat('d-m-Y', '12-02-1940')
        );
    }
}
