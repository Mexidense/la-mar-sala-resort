<?php

declare(strict_types=1);

namespace Domain\ValueObjects;

use InvalidArgumentException;

final class InvalidGenderException extends InvalidArgumentException
{
    public function __construct()
    {
        parent::__construct('Invalid gender value');
    }
}
