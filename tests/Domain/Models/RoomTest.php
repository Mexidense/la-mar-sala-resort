<?php

declare(strict_types=1);

namespace Tests\Domain\Models;

use Domain\Models\Room;
use PHPUnit\Framework\TestCase;

final class RoomTest extends TestCase
{
    public function testCreateRoom(): void
    {
        $room = new Room('101');

        $this->assertInstanceOf(Room::class, $room);
        $this->assertEquals('101', $room->roomNumber()->value());
    }

    public function testEqualsRoom(): void
    {
        $roomOne = new Room('101');
        $roomTwo = new Room('102');

        $this->assertFalse($roomOne->equals($roomTwo));

        $roomThree = new Room('101');
        $this->assertTrue($roomOne->equals($roomThree));
    }
}
