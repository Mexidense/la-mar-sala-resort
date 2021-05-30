<?php

declare(strict_types=1);

namespace Tests;

use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

final class ResortTestA extends TestCase
{
    private Resort $resort;
    private Room $roomOne;
    private Room $roomTwo;
    private Room $roomThree;
    private Room $roomFour;
    private Room $roomFive;
    private Room $roomSix;
    private Room $roomSeven;
    private Room $roomEight;
    private Room $roomNine;

    private Resident $residentOne;
    private Resident $residentTwo;
    private Resident $residentThree;
    private Resident $residentFour;

    private string $dateFormat;

    public function setUp(): void
    {
        $this->roomOne = new Room('101');
        $this->roomTwo = new Room('102');
        $this->roomThree = new Room('103');
        $this->roomFour = new Room('201');
        $this->roomFive = new Room('202');
        $this->roomSix = new Room('203');
        $this->roomSeven = new Room('301');
        $this->roomEight = new Room('302');
        $this->roomNine = new Room('303');

        $this->dateFormat = 'dd-MM-yyyy';

        $this->residentOne = new Resident(
            'Martinez Gomez, Adrian',
            '27272727',
            'M',
            DateTimeImmutable::createFromFormat($this->dateFormat, '12-02-1940')
        );
        $this->residentTwo = new Resident(
            'Lopez Lopez, Luisa',
            '27272728',
            'F',
            DateTimeImmutable::createFromFormat($this->dateFormat, '12-03-1940')
        );
        $this->residentThree = new Resident(
            'Roquero Sanchez, Luis',
            '27272729',
            'M',
            DateTimeImmutable::createFromFormat($this->dateFormat, '12-04-1940')
        );
        $this->residentFour = new Resident(
            'Del Aguila Imperial, Ana Maria',
            '27272730',
            'F',
            DateTimeImmutable::createFromFormat($this->dateFormat, '12-02-1950')
        );

        /** @var Room[] $rooms */
        $rooms = [
            $this->roomOne,
            $this->roomTwo,
            $this->roomThree,
            $this->roomFour,
            $this->roomFive,
        ];

        $this->resort = new Resort('La Mar Salá', $rooms);
    }

    public function testGetters(): void
    {
        $this->assertEquals('Martinez Gomez, Adrian', $this->residentOne->fullname());
        $this->assertEquals('27272727', $this->residentOne->dni());
        $this->assertEquals('M', $this->residentOne->gender());
        $this->assertEquals(
            DateTimeImmutable::createFromFormat($this->dateFormat, '12-02-1940'),
            $this->residentOne->birthdate()
        );

        $this->assertEquals('101', $this->roomOne->number());
    }

    public function testAddRoom(): void
    {
        $room = new Room('501');
        $this->assertEquals(5, $this->resort->numberOfRooms());
        $this->resort->addRoom($room);
        $this->assertEquals(6, $this->resort->numberOfRooms());

        for ($i = 0; $i < 100; $i++) {
            $dynamicRoom = new Room('80' . $i);
            $this->resort->addRoom($dynamicRoom);
        }
        $this->assertEquals(106, $this->resort->numberOfRooms());
    }

    public function testFindRoomByNumber(): void
    {
        $this->assertEquals(5, $this->resort->numberOfRooms());
        $room = $this->resort->findRoomByNumber('101');
        $this->assertTrue($room->equals($this->roomOne));
        $this->assertFalse($room->equals($this->roomTwo));

        $roomA = $this->resort->findRoomByNumber('701');
        $this->assertNull($roomA);
        $this->assertEquals(5, $this->resort->numberOfRooms());
    }

    public function testRemoveRoom(): void
    {
        $this->assertEquals(5, $this->resort->numberOfRooms());
        $this->resort->removeRoom($this->roomOne);
        $this->assertEquals(4, $this->resort->numberOfRooms());
    }

    public function testResidentAge(): void
    {
        $date = DateTimeImmutable::createFromFormat($this->dateFormat, '12-03-2007');
        $this->assertEquals(67, $this->residentOne->age($date));
        $this->assertEquals(67, $this->residentTwo->age($date));
        $this->assertEquals(66, $this->residentThree->age($date));
        $this->assertEquals(57, $this->residentFour->age($date));
    }

    public function testCheckInResort(): void
    {
        $this->resort->checkIn(
            $this->residentOne,
            DateTimeImmutable::createFromFormat($this->dateFormat, '12-01-2007'),
            DateTimeImmutable::createFromFormat($this->dateFormat, '12-06-2007')
        );
        $this->resort->checkIn(
            $this->residentTwo,
            DateTimeImmutable::createFromFormat($this->dateFormat, '12-02-2007'),
            DateTimeImmutable::createFromFormat($this->dateFormat, '12-06-2007')
        );
        $this->assertEquals(2, $this->resort->numberOfBookings());
        $this->assertEquals(2, $this->resort->numberOfResidents());

        $this->resort->checkIn(
            $this->residentThree,
            DateTimeImmutable::createFromFormat($this->dateFormat, '12-02-2007'),
            DateTimeImmutable::createFromFormat($this->dateFormat, '12-03-2007')
        );
        $this->assertEquals(3, $this->resort->numberOfBookings());
        $this->assertEquals(3, $this->resort->numberOfResidents());

        $this->resort->checkOut(
            $this->residentOne,
            DateTimeImmutable::createFromFormat($this->dateFormat, '12-05-2007')
        );
        $this->assertEquals(3, $this->resort->numberOfBookings());
        $this->assertEquals(2, $this->resort->numberOfResidents());
    }

    public function testOneHundredResidents(): void
    {
        $this->assertEquals(0, $this->resort->numberOfBookings());
        for ($i = 0; $i < 100; $i++) {
            $dynamicResident = new Resident(
                'fullname' . $i,
                'M',
                DateTimeImmutable::createFromFormat($this->dateFormat, '12-02-1950')
            );
            $dynamicRoom = new Room('100' . $i);
            $this->resort->checkOut(
                $dynamicResident,
                $dynamicRoom,
                DateTimeImmutable::createFromFormat($this->dateFormat, '12-02-2007'),
                DateTimeImmutable::createFromFormat($this->dateFormat, '12-02-2008')
            );

            $this->assertEquals(100, $this->resort->getNumberOfBookings());
        }
    }

    public function testChangeRoom(): void
    {
        $this->resort->checkIn(
            $this->residentOne,
            $this->roomOne,
            DateTimeImmutable::createFromFormat($this->dateFormat, '12-01-2007'),
            DateTimeImmutable::createFromFormat($this->dateFormat, '12-03-2007')
        );
        $this->resort->checkIn(
            $this->residentTwo,
            $this->roomTwo,
            DateTimeImmutable::createFromFormat($this->dateFormat, '12-02-2007'),
            DateTimeImmutable::createFromFormat($this->dateFormat, '12-03-2007')
        );
        $this->assertEquals(2, $this->resort->numberOfBookings());
        $this->assertEquals(2, $this->resort->numberOfResidents());

        $this->resort->checkIn(
            $this->residentThree,
            $this->roomThree,
            DateTimeImmutable::createFromFormat($this->dateFormat, '12-02-2007'),
            DateTimeImmutable::createFromFormat($this->dateFormat, '12-03-2007')
        );
        $this->assertEquals(3, $this->resort->numberOfBookings());
        $this->assertEquals(3, $this->resort->numberOfResidents());

        $this->resort->changeRoom(
            $this->residentOne,
            $this->roomFour,
            DateTimeImmutable::createFromFormat($this->dateFormat, '12-02-2007'),
            DateTimeImmutable::createFromFormat($this->dateFormat, '12-03-2007')
        );
        $this->assertEquals(4, $this->resort->numberOfBookings());
        $this->assertEquals(3, $this->resort->numberOfResidents());
    }
}