<?php

declare(strict_types=1);

namespace Domain\Models;

use DateTimeImmutable;
use Domain\ValueObjects\Dni;
use Domain\ValueObjects\Gender;

final class Resident implements Stringable
{
    private string $fullName;
    private Dni $dni;
    private Gender $gender;
    private DateTimeImmutable $birthdate;

    public function __construct(
        string $fullName,
        string $dni,
        string $gender,
        DateTimeImmutable $birthdate
    ) {
        $this->fullName = $fullName;
        $this->dni = Dni::create($dni);
        $this->gender = Gender::create($gender);
        $this->birthdate = $birthdate;
    }

    public function fullName(): string
    {
        return $this->fullName;
    }

    public function dni(): Dni
    {
        return $this->dni;
    }

    public function gender(): Gender
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
            $this->dni->value(),
            $this->gender->value(),
            $this->birthdate->format('d-m-Y')
        );
    }
}
