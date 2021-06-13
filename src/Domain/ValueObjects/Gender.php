<?php

declare(strict_types=1);

namespace Domain\ValueObjects;

final class Gender
{
    public const FEMALE = 'F';
    public const MALE = 'M';
    private const GENDERS = [
        self::FEMALE,
        self::MALE,
    ];
    private string $gender;

    private function __construct(string $gender)
    {
        $this->gender = $gender;
    }

    public static function create(string $gender): self
    {
        if (false === in_array($gender, self::GENDERS)) {
            throw new InvalidGenderException();
        }

        return new self($gender);
    }

    public function value(): string
    {
        return $this->gender;
    }
}
