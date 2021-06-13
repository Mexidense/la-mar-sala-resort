<?php

declare(strict_types = 1);

namespace Domain\Models;

use DateTimeImmutable;

final class Resident implements Stringable
{
    private string $fullName;
    private string $dni;
    private string $gender;
    private DateTimeImmutable $birthdate;

    public function __construct(
        string $fullName,
        string $dni,
        string $gender,
        DateTimeImmutable $birthdate
    ) {
        $this->fullName = $fullName;
        $this->dni = $dni;
        $this->gender = $gender;
        $this->birthdate = $birthdate;
    }

    public function fullName(): string
    {
        return $this->fullName;
    }

    public function dni(): string
    {
        return $this->dni;
    }

    public function gender(): string
    {
        return $this->gender;
    }

    public function birthdate(): DateTimeImmutable
    {
        return $this->birthdate;
    }

    public function age(DateTimeImmutable $date): float
    {
        return $this->birthdate->diff($date)->y;
    }

    public function equals(Resident $resident): bool
    {
        return $this->dni === $resident->dni();
    }

    public function __toString(): string
    {
        return sprintf(
            'Full name: %s, DNI: %s, Gender: %s, Birthdate: %s',
            $this->fullName,
            $this->dni,
            $this->gender,
            $this->birthdate->format('d-m-Y')
        );
    }
}