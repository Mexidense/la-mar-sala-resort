<?php

declare(strict_types=1);

namespace Tests;

use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

final class ResortTest extends TestCase
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

    public function testEncapsulatedArrays(): void
    {
        /** @var Room[] $rooms */
        $rooms = $this->resort->rooms();
        $this->assertEquals(5, $this->resort->numberOfRooms());
        $this->assertTrue($rooms[0]->equals($this->residentOne));

        $this->assertEquals(
            $this->resort->findRoomByNumber($this->roomOne->number())->number(),
            $this->roomOne->number()
        );
        $rooms[0] = new Room('808');
        $this->assertTrue(
            ($this->resort->findRoomByNumber($this->roomOne->number()))->equals($this->roomOne)
        );
    }

    public function testResortNotNullRooms(): void
    {
        /** @var Room[] $rooms */
        $rooms = [
            $this->roomOne,
            null,
            $this->roomThree,
            $this->roomFour,
            $this->roomFive,
        ];

        $newResort = new Resort('Los Olmos', $rooms);
        $this->assertEquals(4, $newResort->numberOfRooms());
    }

    public function testEqualRooms(): void
    {
        $roomA = new Room('701');
        $this->assertEquals(5, $this->resort->numberOfRooms());
        $this->resort->addRoom($roomA);
        $this->assertEquals(6, $this->resort->numberOfRooms());

        $roomB = new Room('701');
        $this->resort->addRoom($roomB);
        $this->assertEquals(6, $this->resort->numberOfRooms());
    }

    public function testEqualsResidents(): void
    {
        $this->resort->checkIn(
            $this->residentOne,
            $this->roomOne,
            DateTimeImmutable::createFromFormat($this->dateFormat, '01-01-2007'),
            DateTimeImmutable::createFromFormat($this->dateFormat, '15-01-2007')
        );
        $this->resort->checkIn(
            $this->residentTwo,
            $this->roomTwo,
            DateTimeImmutable::createFromFormat($this->dateFormat, '01-02-2007'),
            DateTimeImmutable::createFromFormat($this->dateFormat, '15-02-2007')
        );
        $this->assertEquals(2, $this->resort->numberOfResidents());

        $this->resort->checkIn(
            $this->residentTwo,
            $this->roomThree,
            DateTimeImmutable::createFromFormat($this->dateFormat, '01-02-2007'),
            DateTimeImmutable::createFromFormat($this->dateFormat, '15-02-2007')
        );
        $this->assertEquals(2, $this->resort->numberOfResidents());
    }

    public function testAutoIncrementBookings(): void
    {
        $this->resort->checkIn(
            $this->residentOne,
            $this->roomOne,
            DateTimeImmutable::createFromFormat($this->dateFormat, '12-01-2007'),
            DateTimeImmutable::createFromFormat($this->dateFormat, '12-01-2008')
        );
        $this->resort->checkIn(
            $this->residentTwo,
            $this->roomTwo,
            DateTimeImmutable::createFromFormat($this->dateFormat, '12-02-2007'),
            DateTimeImmutable::createFromFormat($this->dateFormat, '12-01-2008')
        );
        $this->resort->checkIn(
            $this->residentThree,
            $this->roomThree,
            DateTimeImmutable::createFromFormat($this->dateFormat, '12-02-2007'),
            DateTimeImmutable::createFromFormat($this->dateFormat, '12-01-2008')
        );
        $this->assertEquals(3, $this->resort->numberOfBookings());
        $this->assertEquals(3, $this->resort->numberOfResidents());

        /** Booking[] $bookings */
        $bookings = $this->resort->bookings();
        $lastIdentifiedBookingNumber = Booking::lastIdentity();

        for ($i = 0; $i < $this->resort->numberOfBooking(); $i++) {
            $this->assertTrue(
                $bookings[$i]->number() === $lastIdentifiedBookingNumber - (2 - $i)
            );
        }

        $this->resort->checkOut(
            $this->residentOne,
            DateTimeImmutable::createFromFormat($this->dateFormat, '12-05-2007')
        );
        $this->assertEquals(3, $this->resort->numberOfBookings());
        $this->assertEquals(2, $this->resort->numberOfResidents());

        for ($i = 0; $i < $this->resort->numberOfBooking(); $i++) {
            $this->assertTrue(
                $bookings[$i]->number() === $lastIdentifiedBookingNumber - (2 - $i)
            );
        }
    }

    public function testBookingDateControl(): void
    {
        $this->resort->checkIn(
            $this->residentOne,
            $this->roomOne,
            DateTimeImmutable::createFromFormat($this->dateFormat, '01-01-2007'),
            DateTimeImmutable::createFromFormat($this->dateFormat, '15-01-2008')
        );
        $this->resort->checkIn(
            $this->residentTwo,
            $this->roomTwo,
            DateTimeImmutable::createFromFormat($this->dateFormat, '01-02-2007'),
            DateTimeImmutable::createFromFormat($this->dateFormat, '15-02-2008')
        );
        $this->assertEquals(2, $this->resort->numberOfBookings());
        $this->assertEquals(2, $this->resort->numberOfResidents());

        $this->resort->checkIn(
            $this->residentThree,
            $this->roomThree,
            DateTimeImmutable::createFromFormat($this->dateFormat, '01-03-2007'),
            DateTimeImmutable::createFromFormat($this->dateFormat, '15-02-2008')
        );
        $this->assertEquals(2, $this->resort->numberOfBookings());
        $this->assertEquals(2, $this->resort->numberOfResidents());

        $this->resort->checkIn(
            $this->residentThree,
            $this->roomThree,
            DateTimeImmutable::createFromFormat($this->dateFormat, '01-03-2007'),
            DateTimeImmutable::createFromFormat($this->dateFormat, '01-03-2007')
        );
        $this->assertEquals(3, $this->resort->numberOfBookings());
        $this->assertEquals(3, $this->resort->numberOfResidents());
    }

    public function testBusyRoomControl(): void
    {
        $this->resort->checkIn(
            $this->residentOne,
            $this->roomOne,
            DateTimeImmutable::createFromFormat($this->dateFormat, '01-01-2007'),
            DateTimeImmutable::createFromFormat($this->dateFormat, '15-01-2007')
        );
        $this->resort->checkIn(
            $this->residentTwo,
            $this->roomTwo,
            DateTimeImmutable::createFromFormat($this->dateFormat, '01-02-2007'),
            DateTimeImmutable::createFromFormat($this->dateFormat, '15-02-2007')
        );
        $this->assertEquals(2, $this->resort->numberOfBookings());
        $this->assertEquals(2, $this->resort->numberOfResidents());

        $date = DateTimeImmutable::createFromFormat($this->dateFormat, '01-01-2007');
        $this->assertTrue($this->resort->isBusyRoom($this->roomOne, $date));
        $date = DateTimeImmutable::createFromFormat($this->dateFormat, '15-01-2007');
        $this->assertTrue($this->resort->isBusyRoom($this->roomOne, $date));

        $this->resort->checkIn(
            $this->residentThree,
            $this->roomTwo,
            DateTimeImmutable::createFromFormat($this->dateFormat, '08-02-2007'),
            DateTimeImmutable::createFromFormat($this->dateFormat, '15-02-2007')
        );
        $this->resort->checkIn(
            $this->residentThree,
            $this->roomTwo,
            DateTimeImmutable::createFromFormat($this->dateFormat, '01-02-2007'),
            DateTimeImmutable::createFromFormat($this->dateFormat, '15-02-2007')
        );
        $this->resort->checkIn(
            $this->residentThree,
            $this->roomTwo,
            DateTimeImmutable::createFromFormat($this->dateFormat, '15-02-2007'),
            DateTimeImmutable::createFromFormat($this->dateFormat, '15-02-2007')
        );
        $this->assertEquals(2, $this->resort->numberOfBookings());
        $this->assertEquals(2, $this->resort->numberOfResidents());
    }

    public function testAvailableRoomsList(): void
    {
        $this->resort->checkIn(
            $this->residentOne,
            $this->roomOne,
            DateTimeImmutable::createFromFormat($this->dateFormat, '01-01-2007'),
            DateTimeImmutable::createFromFormat($this->dateFormat, '15-01-2007')
        );
        $this->resort->checkIn(
            $this->residentTwo,
            $this->roomTwo,
            DateTimeImmutable::createFromFormat($this->dateFormat, '01-02-2007'),
            DateTimeImmutable::createFromFormat($this->dateFormat, '15-02-2007')
        );
        $this->resort->checkIn(
            $this->residentThree,
            $this->roomThree,
            DateTimeImmutable::createFromFormat($this->dateFormat, '01-03-2007'),
            DateTimeImmutable::createFromFormat($this->dateFormat, '15-03-2007')
        );
        $this->assertEquals(3, $this->resort->numberOfBookings());
        $this->assertEquals(3, $this->resort->numberOfResidents());

        $date = DateTimeImmutable::createFromFormat($this->dateFormat, '01-01-2007');
        $availableRooms = $this->resort->availableRoomsList($date);
        $this->assertContains('102', $availableRooms);
        $this->assertContains('103', $availableRooms);

        $date = DateTimeImmutable::createFromFormat($this->dateFormat, '01-03-2007');
        $availableRooms = $this->resort->availableRoomsList($date);
        $this->assertContains('101', $availableRooms);
        $this->assertContains('102', $availableRooms);

        $date = DateTimeImmutable::createFromFormat($this->dateFormat, '15-03-2007');
        $availableRooms = $this->resort->availableRoomsList($date);
        $this->assertContains('101', $availableRooms);
        $this->assertContains('102', $availableRooms);

        $date = DateTimeImmutable::createFromFormat($this->dateFormat, '01-05-2007');
        $availableRooms = $this->resort->availableRoomsList($date);
        $this->assertContains('101', $availableRooms);
        $this->assertContains('102', $availableRooms);
        $this->assertContains('103', $availableRooms);
    }

    public function testResidentsInRoomsList(): void
    {
        $this->resort->checkIn(
            $this->residentOne,
            $this->roomOne,
            DateTimeImmutable::createFromFormat($this->dateFormat, '01-01-2007'),
            DateTimeImmutable::createFromFormat($this->dateFormat, '15-01-2007')
        );
        $this->resort->checkIn(
            $this->residentTwo,
            $this->roomTwo,
            DateTimeImmutable::createFromFormat($this->dateFormat, '01-02-2007'),
            DateTimeImmutable::createFromFormat($this->dateFormat, '15-02-2007')
        );
        $this->resort->checkIn(
            $this->residentThree,
            $this->roomThree,
            DateTimeImmutable::createFromFormat($this->dateFormat, '01-03-2007'),
            DateTimeImmutable::createFromFormat($this->dateFormat, '15-03-2007')
        );
        $this->assertEquals(3, $this->resort->numberOfBookings());
        $this->assertEquals(3, $this->resort->numberOfResidents());

        $date = DateTimeImmutable::createFromFormat($this->dateFormat, '02-01-2007');
        $residentsInRooms = $this->resort->residentsInRoomsList($date);
        $this->assertContains('Martinez Gomez, Adrian', $residentsInRooms);

        $date = DateTimeImmutable::createFromFormat($this->dateFormat, '01-03-2007');
        $residentsInRooms = $this->resort->residentsInRoomsList($date);
        $this->assertContains('Roquero Sanchez, Luis', $residentsInRooms);

        $date = DateTimeImmutable::createFromFormat($this->dateFormat, '01-05-2007');
        $residentsInRooms = $this->resort->residentsInRoomsList($date);
        $this->assertContains('', $residentsInRooms);

        $this->resort->checkOut(
            $this->residentOne,
            DateTimeImmutable::createFromFormat($this->dateFormat, '02-01-2007')
        );
        $this->resort->checkOut(
            $this->residentTwo,
            DateTimeImmutable::createFromFormat($this->dateFormat, '02-02-2007')
        );
        $this->resort->checkOut(
            $this->residentThree,
            DateTimeImmutable::createFromFormat($this->dateFormat, '02-03-2007')
        );
        $date = DateTimeImmutable::createFromFormat($this->dateFormat, '03-05-2007');
        $residentsInRooms = $this->resort->residentsInRoomsList($date);
        $this->assertContains('', $residentsInRooms);

        $this->resort->checkIn(
            $this->residentOne,
            $this->roomOne,
            DateTimeImmutable::createFromFormat($this->dateFormat, '15-05-2007'),
            DateTimeImmutable::createFromFormat($this->dateFormat, '15-05-2007')
        );
        $this->resort->checkIn(
            $this->residentTwo,
            $this->roomTwo,
            DateTimeImmutable::createFromFormat($this->dateFormat, '15-05-2007'),
            DateTimeImmutable::createFromFormat($this->dateFormat, '15-05-2007')
        );
        $this->resort->checkIn(
            $this->residentThree,
            $this->roomThree,
            DateTimeImmutable::createFromFormat($this->dateFormat, '10-05-2007'),
            DateTimeImmutable::createFromFormat($this->dateFormat, '15-05-2007')
        );
        $this->resort->checkIn(
            $this->residentFour,
            $this->roomFour,
            DateTimeImmutable::createFromFormat($this->dateFormat, '10-05-2007'),
            DateTimeImmutable::createFromFormat($this->dateFormat, '15-05-2007')
        );
        $date = DateTimeImmutable::createFromFormat($this->dateFormat, '12-05-2007');
        $residentsInRooms = $this->resort->residentsInRoomsList($date);
        $position = [
            0 => 'Del Aguila Imperial, Ana Maria',
            1 => 'Lopez Lopez, Luisa',
            2 => 'Martinez Gomez, Adrian',
            3 => 'Roquero Sanchez, Luis',
        ];

        for ($i = 0; $i < sizeof($position); $i++) {
            $this->assertContains($position[$i], $residentsInRooms);
        }
    }

    public function testAgeAverageByGender(): void
    {
        $date = DateTimeImmutable::createFromFormat($this->dateFormat, '12-03-2007');
        $this->assertContains('0.0', $this->resort->ageAverageByGender($date));

        $this->resort->checkIn(
            $this->residentOne,
            $this->roomOne,
            DateTimeImmutable::createFromFormat($this->dateFormat, '01-01-2007'),
            DateTimeImmutable::createFromFormat($this->dateFormat, '15-01-2007')
        );
        $this->assertContains('0.0', $this->resort->ageAverageByGender($date));
        $this->assertContains('67.0', $this->resort->ageAverageByGender($date));

        $this->resort->checkIn(
            $this->residentTwo,
            $this->roomTwo,
            DateTimeImmutable::createFromFormat($this->dateFormat, '01-02-2007'),
            DateTimeImmutable::createFromFormat($this->dateFormat, '15-02-2007')
        );
        $this->resort->checkIn(
            $this->residentThree,
            $this->roomThree,
            DateTimeImmutable::createFromFormat($this->dateFormat, '01-03-2007'),
            DateTimeImmutable::createFromFormat($this->dateFormat, '15-03-2007')
        );
        $this->resort->checkIn(
            $this->residentFour,
            $this->roomFour,
            DateTimeImmutable::createFromFormat($this->dateFormat, '01-03-2007'),
            DateTimeImmutable::createFromFormat($this->dateFormat, '15-03-2007')
        );
        $this->assertContains('66.5', $this->resort->ageAverageByGender($date));
        $this->assertContains('62.0', $this->resort->ageAverageByGender($date));
    }
}