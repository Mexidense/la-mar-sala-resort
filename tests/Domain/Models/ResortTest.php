<?php

declare(strict_types=1);

namespace Tests\Domain\Models;

use DateTimeImmutable;
use Domain\Models\Resident;
use Domain\Models\Resort;
use Domain\Models\Room;
use PHPUnit\Framework\TestCase;

final class ResortTest extends TestCase
{
    private Resort $resort;
    private Room $roomOne;
    private Room $roomTwo;
    private Room $roomThree;
    private Room $roomFour;
    private Room $roomFive;

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

        $this->dateFormat = 'd-m-Y';

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
        $this->assertEquals('27272727', $this->residentOne->dni()->value());
        $this->assertEquals('M', $this->residentOne->gender()->value());
        $this->assertEquals(
            DateTimeImmutable::createFromFormat($this->dateFormat, '12-02-1940'),
            $this->residentOne->birthdate()
        );
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
            DateTimeImmutable::createFromFormat($this->dateFormat, '12-01-2007'),
            DateTimeImmutable::createFromFormat($this->dateFormat, '12-06-2007'),
            $this->residentOne
        );
        $this->resort->checkIn(
            DateTimeImmutable::createFromFormat($this->dateFormat, '12-02-2007'),
            DateTimeImmutable::createFromFormat($this->dateFormat, '12-06-2007'),
            $this->residentTwo
        );
        $this->assertEquals(2, $this->resort->numberOfBookings());
        $this->assertEquals(2, $this->resort->numberOfResidents());

        $this->resort->checkIn(
            DateTimeImmutable::createFromFormat($this->dateFormat, '12-02-2007'),
            DateTimeImmutable::createFromFormat($this->dateFormat, '12-03-2007'),
            $this->residentThree
        );
        $this->assertEquals(3, $this->resort->numberOfBookings());
        $this->assertEquals(3, $this->resort->numberOfResidents());

        $this->resort->checkOut(
            DateTimeImmutable::createFromFormat($this->dateFormat, '12-05-2007'),
            $this->residentOne
        );
        $this->assertEquals(3, $this->resort->numberOfBookings());
        $this->assertEquals(2, $this->resort->numberOfResidents());
    }

    public function testOneHundredResidents(): void
    {
        $this->assertEquals(0, $this->resort->numberOfBookings());
        for ($i = 0; $i < 100; $i++) {
            $dynamicResident = new Resident(
                'fullName' . $i,
                '272727' . $i,
                'M',
                DateTimeImmutable::createFromFormat($this->dateFormat, '12-02-1950')
            );
            $dynamicRoom = new Room('100' . $i);
            $this->resort->checkIn(
                DateTimeImmutable::createFromFormat($this->dateFormat, '12-02-2007'),
                DateTimeImmutable::createFromFormat($this->dateFormat, '12-02-2008'),
                $dynamicResident,
                $dynamicRoom
            );
        }

        $this->assertEquals(100, $this->resort->numberOfBookings());
    }

    public function testChangeRoom(): void
    {
        $this->resort->checkIn(
            DateTimeImmutable::createFromFormat($this->dateFormat, '12-01-2007'),
            DateTimeImmutable::createFromFormat($this->dateFormat, '12-03-2007'),
            $this->residentOne,
            $this->roomOne
        );
        $this->resort->checkIn(
            DateTimeImmutable::createFromFormat($this->dateFormat, '12-02-2007'),
            DateTimeImmutable::createFromFormat($this->dateFormat, '12-03-2007'),
            $this->residentTwo,
            $this->roomTwo
        );
        $this->assertEquals(2, $this->resort->numberOfBookings());
        $this->assertEquals(2, $this->resort->numberOfResidents());

        $this->resort->checkIn(
            DateTimeImmutable::createFromFormat($this->dateFormat, '12-02-2007'),
            DateTimeImmutable::createFromFormat($this->dateFormat, '12-03-2007'),
            $this->residentThree,
            $this->roomThree
        );
        $this->assertEquals(3, $this->resort->numberOfBookings());
        $this->assertEquals(3, $this->resort->numberOfResidents());

        $this->resort->changeRoom(
            DateTimeImmutable::createFromFormat($this->dateFormat, '12-02-2007'),
            DateTimeImmutable::createFromFormat($this->dateFormat, '12-03-2007'),
            $this->residentOne,
            $this->roomFour
        );
        $this->assertEquals(4, $this->resort->numberOfBookings());
        $this->assertEquals(3, $this->resort->numberOfResidents());
    }

    public function testEncapsulatedArrays(): void
    {
        /** @var Room[] $rooms */
        $rooms = $this->resort->rooms();
        $this->assertEquals(5, $this->resort->numberOfRooms());
        $this->assertTrue($rooms[0]->equals($this->roomOne));

        $this->assertEquals(
            $this->resort->findRoomByNumber($this->roomOne->roomNumber()->value())->roomNumber(),
            $this->roomOne->roomNumber()
        );
        $rooms[0] = new Room('808');
        $this->assertTrue(
            ($this->resort->findRoomByNumber($this->roomOne->roomNumber()->value()))->equals($this->roomOne)
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
            DateTimeImmutable::createFromFormat($this->dateFormat, '01-01-2007'),
            DateTimeImmutable::createFromFormat($this->dateFormat, '15-01-2007'),
            $this->residentOne,
            $this->roomOne
        );
        $this->resort->checkIn(
            DateTimeImmutable::createFromFormat($this->dateFormat, '01-02-2007'),
            DateTimeImmutable::createFromFormat($this->dateFormat, '15-02-2007'),
            $this->residentTwo,
            $this->roomTwo
        );
        $this->assertEquals(2, $this->resort->numberOfResidents());

        $this->resort->checkIn(
            DateTimeImmutable::createFromFormat($this->dateFormat, '01-02-2007'),
            DateTimeImmutable::createFromFormat($this->dateFormat, '15-02-2007'),
            $this->residentTwo,
            $this->roomThree
        );
        $this->assertEquals(2, $this->resort->numberOfResidents());
    }

 
    public function testAutoIncrementBookings(): void
    {
        $this->resort->checkIn(
            DateTimeImmutable::createFromFormat($this->dateFormat, '12-01-2007'),
            DateTimeImmutable::createFromFormat($this->dateFormat, '12-01-2008'),
            $this->residentOne,
            $this->roomOne
        );
        $this->resort->checkIn(
            DateTimeImmutable::createFromFormat($this->dateFormat, '12-02-2007'),
            DateTimeImmutable::createFromFormat($this->dateFormat, '12-01-2008'),
            $this->residentTwo,
            $this->roomTwo
        );
        $this->resort->checkIn(
            DateTimeImmutable::createFromFormat($this->dateFormat, '12-02-2007'),
            DateTimeImmutable::createFromFormat($this->dateFormat, '12-01-2008'),
            $this->residentThree,
            $this->roomThree
        );
        $this->assertEquals(3, $this->resort->numberOfBookings());
        $this->assertEquals(3, $this->resort->numberOfResidents());

        /** Booking[] $bookings */
        $bookings = $this->resort->bookings();
        $lastIdentifiedBookingNumber = $this->resort->lastIdentity();

        for ($i = 0; $i < $this->resort->numberOfBookings(); $i++) {
            $this->assertTrue(
                $bookings[$i]->number() === $lastIdentifiedBookingNumber - (2 - $i)
            );
        }

        $this->resort->checkOut(
            DateTimeImmutable::createFromFormat($this->dateFormat, '12-05-2007'),
            $this->residentOne
        );
        $this->assertEquals(3, $this->resort->numberOfBookings());
        $this->assertEquals(2, $this->resort->numberOfResidents());

        for ($i = 0; $i < $this->resort->numberOfBookings(); $i++) {
            $this->assertTrue(
                $bookings[$i]->number() === $lastIdentifiedBookingNumber - (2 - $i)
            );
        }
    }

    public function testBookingDateControl(): void
    {
        $this->resort->checkIn(
            DateTimeImmutable::createFromFormat($this->dateFormat, '01-01-2007'),
            DateTimeImmutable::createFromFormat($this->dateFormat, '15-01-2007'),
            $this->residentOne,
            $this->roomOne
        );
        $this->resort->checkIn(
            DateTimeImmutable::createFromFormat($this->dateFormat, '01-02-2007'),
            DateTimeImmutable::createFromFormat($this->dateFormat, '15-02-2007'),
            $this->residentTwo,
            $this->roomTwo
        );
        $this->assertEquals(2, $this->resort->numberOfBookings());
        $this->assertEquals(2, $this->resort->numberOfResidents());

        $this->resort->checkIn(
            DateTimeImmutable::createFromFormat($this->dateFormat, '01-03-2007'),
            DateTimeImmutable::createFromFormat($this->dateFormat, '15-02-2007'),
            $this->residentThree,
            $this->roomThree
        );
        $this->assertEquals(2, $this->resort->numberOfBookings());
        $this->assertEquals(2, $this->resort->numberOfResidents());

        $this->resort->checkIn(
            DateTimeImmutable::createFromFormat($this->dateFormat, '01-03-2007'),
            DateTimeImmutable::createFromFormat($this->dateFormat, '01-03-2007'),
            $this->residentThree,
            $this->roomThree
        );
        $this->assertEquals(3, $this->resort->numberOfBookings());
        $this->assertEquals(3, $this->resort->numberOfResidents());
    }

    public function testBusyRoomControl(): void
    {
        $this->resort->checkIn(
            DateTimeImmutable::createFromFormat($this->dateFormat, '01-01-2007'),
            DateTimeImmutable::createFromFormat($this->dateFormat, '15-01-2007'),
            $this->residentOne,
            $this->roomOne
        );
        $this->resort->checkIn(
            DateTimeImmutable::createFromFormat($this->dateFormat, '01-02-2007'),
            DateTimeImmutable::createFromFormat($this->dateFormat, '15-02-2007'),
            $this->residentTwo,
            $this->roomTwo
        );
        $this->assertEquals(2, $this->resort->numberOfBookings());
        $this->assertEquals(2, $this->resort->numberOfResidents());

        $date = DateTimeImmutable::createFromFormat($this->dateFormat, '01-01-2007');
        $this->assertTrue($this->resort->isBusyRoom($this->roomOne, $date));
        $date = DateTimeImmutable::createFromFormat($this->dateFormat, '15-01-2007');
        $this->assertTrue($this->resort->isBusyRoom($this->roomOne, $date));

        $this->resort->checkIn(
            DateTimeImmutable::createFromFormat($this->dateFormat, '08-02-2007'),
            DateTimeImmutable::createFromFormat($this->dateFormat, '15-02-2007'),
            $this->residentThree,
            $this->roomTwo
        );
        $this->resort->checkIn(
            DateTimeImmutable::createFromFormat($this->dateFormat, '01-02-2007'),
            DateTimeImmutable::createFromFormat($this->dateFormat, '15-02-2007'),
            $this->residentThree,
            $this->roomTwo
        );
        $this->resort->checkIn(
            DateTimeImmutable::createFromFormat($this->dateFormat, '15-02-2007'),
            DateTimeImmutable::createFromFormat($this->dateFormat, '15-02-2007'),
            $this->residentThree,
            $this->roomTwo,
        );
        $this->assertEquals(2, $this->resort->numberOfBookings());
        $this->assertEquals(2, $this->resort->numberOfResidents());
    }

    public function testAvailableRoomsList(): void
    {
        $this->resort->checkIn(
            DateTimeImmutable::createFromFormat($this->dateFormat, '01-01-2007'),
            DateTimeImmutable::createFromFormat($this->dateFormat, '15-01-2007'),
            $this->residentOne,
            $this->roomOne
        );
        $this->resort->checkIn(
            DateTimeImmutable::createFromFormat($this->dateFormat, '01-02-2007'),
            DateTimeImmutable::createFromFormat($this->dateFormat, '15-02-2007'),
            $this->residentTwo,
            $this->roomTwo
        );
        $this->resort->checkIn(
            DateTimeImmutable::createFromFormat($this->dateFormat, '01-03-2007'),
            DateTimeImmutable::createFromFormat($this->dateFormat, '15-03-2007'),
            $this->residentThree,
            $this->roomThree
        );
        $this->assertEquals(3, $this->resort->numberOfBookings());
        $this->assertEquals(3, $this->resort->numberOfResidents());

        $date = DateTimeImmutable::createFromFormat($this->dateFormat, '01-01-2007');
        $availableRooms = $this->resort->availableRoomsList($date);
        $this->assertStringContainsString('102', $availableRooms);
        $this->assertStringContainsString('103', $availableRooms);

        $date = DateTimeImmutable::createFromFormat($this->dateFormat, '01-03-2007');
        $availableRooms = $this->resort->availableRoomsList($date);
        $this->assertStringContainsString('101', $availableRooms);
        $this->assertStringContainsString('102', $availableRooms);

        $date = DateTimeImmutable::createFromFormat($this->dateFormat, '15-03-2007');
        $availableRooms = $this->resort->availableRoomsList($date);
        $this->assertStringContainsString('101', $availableRooms);
        $this->assertStringContainsString('102', $availableRooms);

        $date = DateTimeImmutable::createFromFormat($this->dateFormat, '01-05-2007');
        $availableRooms = $this->resort->availableRoomsList($date);
        $this->assertStringContainsString('101', $availableRooms);
        $this->assertStringContainsString('102', $availableRooms);
        $this->assertStringContainsString('103', $availableRooms);
    }

    public function testResidentsInRoomsList(): void
    {
        $this->resort->checkIn(
            DateTimeImmutable::createFromFormat($this->dateFormat, '01-01-2007'),
            DateTimeImmutable::createFromFormat($this->dateFormat, '15-01-2007'),
            $this->residentOne,
            $this->roomOne
        );
        $this->resort->checkIn(
            DateTimeImmutable::createFromFormat($this->dateFormat, '01-02-2007'),
            DateTimeImmutable::createFromFormat($this->dateFormat, '15-02-2007'),
            $this->residentTwo,
            $this->roomTwo,
        );
        $this->resort->checkIn(
            DateTimeImmutable::createFromFormat($this->dateFormat, '01-03-2007'),
            DateTimeImmutable::createFromFormat($this->dateFormat, '15-03-2007'),
            $this->residentThree,
            $this->roomThree
        );
        $this->assertEquals(3, $this->resort->numberOfBookings());
        $this->assertEquals(3, $this->resort->numberOfResidents());

        $date = DateTimeImmutable::createFromFormat($this->dateFormat, '02-01-2007');
        $residentsInRooms = $this->resort->residentsInRoomsList($date);
        $this->assertStringContainsString('Martinez Gomez, Adrian', $residentsInRooms);

        $date = DateTimeImmutable::createFromFormat($this->dateFormat, '01-03-2007');
        $residentsInRooms = $this->resort->residentsInRoomsList($date);
        $this->assertStringContainsString('Roquero Sanchez, Luis', $residentsInRooms);

        $date = DateTimeImmutable::createFromFormat($this->dateFormat, '01-05-2007');
        $residentsInRooms = $this->resort->residentsInRoomsList($date);
        $this->assertStringContainsString('', $residentsInRooms);

        $this->resort->checkOut(
            DateTimeImmutable::createFromFormat($this->dateFormat, '02-01-2007'),
            $this->residentOne
        );
        $this->resort->checkOut(
            DateTimeImmutable::createFromFormat($this->dateFormat, '02-02-2007'),
            $this->residentTwo
        );
        $this->resort->checkOut(
            DateTimeImmutable::createFromFormat($this->dateFormat, '02-03-2007'),
            $this->residentThree
        );
        $date = DateTimeImmutable::createFromFormat($this->dateFormat, '03-05-2007');
        $residentsInRooms = $this->resort->residentsInRoomsList($date);
        $this->assertStringContainsString('', $residentsInRooms);

        $this->resort->checkIn(
            DateTimeImmutable::createFromFormat($this->dateFormat, '10-05-2007'),
            DateTimeImmutable::createFromFormat($this->dateFormat, '15-05-2007'),
            $this->residentOne,
            $this->roomOne
        );
        $this->resort->checkIn(
            DateTimeImmutable::createFromFormat($this->dateFormat, '10-05-2007'),
            DateTimeImmutable::createFromFormat($this->dateFormat, '15-05-2007'),
            $this->residentTwo,
            $this->roomTwo
        );
        $this->resort->checkIn(
            DateTimeImmutable::createFromFormat($this->dateFormat, '10-05-2007'),
            DateTimeImmutable::createFromFormat($this->dateFormat, '15-05-2007'),
            $this->residentThree,
            $this->roomThree
        );
        $this->resort->checkIn(
            DateTimeImmutable::createFromFormat($this->dateFormat, '10-05-2007'),
            DateTimeImmutable::createFromFormat($this->dateFormat, '15-05-2007'),
            $this->residentFour,
            $this->roomFour
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
            $this->assertStringContainsString($position[$i], $residentsInRooms);
        }
    }

    public function testAgeAverageByGender(): void
    {
        $date = DateTimeImmutable::createFromFormat($this->dateFormat, '12-03-2007');
        $this->assertStringContainsString('0.0', $this->resort->ageAverageByGender($date));

        $this->resort->checkIn(
            DateTimeImmutable::createFromFormat($this->dateFormat, '01-01-2007'),
            DateTimeImmutable::createFromFormat($this->dateFormat, '15-01-2007'),
            $this->residentOne,
            $this->roomOne
        );
        $this->assertStringContainsString('0.0', $this->resort->ageAverageByGender($date));
        $this->assertStringContainsString('67.0', $this->resort->ageAverageByGender($date));

        $this->resort->checkIn(
            DateTimeImmutable::createFromFormat($this->dateFormat, '01-02-2007'),
            DateTimeImmutable::createFromFormat($this->dateFormat, '15-02-2007'),
            $this->residentTwo,
            $this->roomTwo
        );
        $this->resort->checkIn(
            DateTimeImmutable::createFromFormat($this->dateFormat, '01-03-2007'),
            DateTimeImmutable::createFromFormat($this->dateFormat, '15-03-2007'),
            $this->residentThree,
            $this->roomThree
        );
        $this->resort->checkIn(
            DateTimeImmutable::createFromFormat($this->dateFormat, '01-03-2007'),
            DateTimeImmutable::createFromFormat($this->dateFormat, '15-03-2007'),
            $this->residentFour,
            $this->roomFour
        );
        $this->assertStringContainsString('66.5', $this->resort->ageAverageByGender($date));
        $this->assertStringContainsString('62.0', $this->resort->ageAverageByGender($date));
    }
}