<?php

declare(strict_types = 1);

namespace Domain;

use DateTimeImmutable;

final class Resident
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
}