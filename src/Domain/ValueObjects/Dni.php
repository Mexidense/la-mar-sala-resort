<?php

declare(strict_types=1);

namespace Domain\ValueObjects;

final class Dni
{
    private string $dni;

    private function __construct(string $dni)
    {
        $this->dni = $dni;
    }

    public static function create(?string $dni): self
    {
        if (empty($dni)) {
            throw new InvalidDniException();
        }

        return new self($dni);
    }

    public function value(): string
    {
        return $this->dni;
    }
}
