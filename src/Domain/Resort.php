<?php

declare(strict_types = 1);

namespace Domain;

final class Resort
{
    private string $name;
    /** @var Room[] */
    private array $rooms;

    public function __construct(string $name, array $rooms)
    {
        $this->name = $name;
        $this->rooms = $rooms;
    }

    public function name(): string
    {
        return $this->name;
    }

    /** @return Room[] */
    public function rooms(): array
    {
        return $this->rooms;
    }
}