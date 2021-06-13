<?php

declare(strict_types=1);

namespace Domain\ValueObjects;

use InvalidArgumentException;

final class InvalidRoomNumber extends InvalidArgumentException
{
    public function __construct()
    {
        parent::__construct('Invalid Room number');
    }
}
