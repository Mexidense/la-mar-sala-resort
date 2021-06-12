<?php

declare(strict_types = 1);

namespace Domain;

final class Room
{
    private string $number;

    public function __construct(string $number)
    {
        $this->number = $number;
    }

    public function number(): string
    {
        return $this->number;
    }
}