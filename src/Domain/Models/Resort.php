<?php

declare(strict_types=1);

namespace Domain\Models;

use DateTimeImmutable;
use Domain\Entities\Booking;
use Domain\Formatters\ArrayToString;
use Domain\Formatters\ResidentsAgeAverageToString;
use Domain\Validators\BookingAvailabilityValidator;
use Domain\Validators\CheckInAndOutDatesValidator;

final class Resort
{
    private string $name;
    /** @var Room[] */
    private array $rooms;
    /** @var Booking[] */
    private ?array $bookings;

    public function __construct(
        string $name,
        array $rooms
    ) {
        $this->name = $name;
        $this->rooms = array_filter($rooms, function ($room) {
            if (false === is_null($room)) {
                return $room;
            }
        });
        $this->bookings = [];
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

    public function bookings(): ?array
    {
        return $this->bookings;
    }

    public function numberOfRooms(): int
    {
        return sizeof($this->rooms);
    }

    public function numberOfBookings(): int
    {
        return sizeof($this->bookings);
    }

    public function numberOfResidents(): int
    {
        $residents = [];
        foreach ($this->bookings as $booking) {
            if (false === $booking->earlyCheckOut()) {
                $residents[$booking->resident()->dni()->value()][] = $booking->room();
            }
        }

        return sizeof($residents);
    }

    public function lastIdentity(): int
    {
        return sizeof($this->bookings);
    }

    public function nextIdentity(): int
    {
        return $this->lastIdentity() + 1;
    }

    public function addRoom(Room $room): void
    {
        $this->rooms[$room->number()] = $room;
    }

    public function findRoomByNumber(string $roomNumber): ?Room
    {
        foreach ($this->rooms as $room) {
            if ($roomNumber === $room->number()) {
                return $room;
            }
        }

        return null;
    }

    public function removeRoom(Room $roomToRemove): void
    {
        foreach ($this->rooms as $roomIndex => $room) {
            if ($room->equals($roomToRemove)) {
                unset($this->rooms[$roomIndex]);
            }
        }
    }

    private function getAvailableRoom($date): ?Room
    {
        foreach ($this->rooms as $room) {
            if (false === $this->isBusyRoom($room, $date)) {
                return $room;
            }
        }

        return null;
    }

    public function checkIn(
        DateTimeImmutable $checkInDate,
        DateTimeImmutable $checkOutDate,
        Resident $resident,
        ?Room $room = null
    ): void {
        if (false === CheckInAndOutDatesValidator::validate($checkInDate, $checkOutDate)) {
            return;
        }

        if (is_null($room)) {
            $room = $this->getAvailableRoom($checkInDate);
            if (is_null($room)) {
                return;
            }
        }

        if ($this->isBusyRoom($room, $checkInDate)) {
            return;
        }

        $this->bookings[] = new Booking(
            $this->nextIdentity(),
            $checkInDate,
            $checkOutDate,
            $room,
            $resident
        );
    }

    public function checkOut(
        DateTimeImmutable $newCheckOutDate,
        Resident $residentOut
    ): void {
        foreach ($this->bookings as $bookingIndex => $booking) {
            if ($residentOut->equals($booking->resident())) {
                $changedBooking = $booking->changeCheckOutDate($newCheckOutDate);
                $this->bookings[$bookingIndex] = $changedBooking;
                break;
            }
        }
    }

    public function changeRoom(
        DateTimeImmutable $checkInDate,
        DateTimeImmutable $checkOutDate,
        Resident $resident,
        Room $room
    ): void {
        foreach ($this->bookings as $bookingIndex => $booking) {
            if ($booking->resident()->equals($resident)) {
                $this->bookings[$bookingIndex] = new Booking(
                    $booking->number(),
                    $booking->checkIn(),
                    $checkInDate,
                    $booking->room(),
                    $resident
                );
                $this->bookings[] = new Booking(
                    $this->nextIdentity(),
                    $checkInDate,
                    $checkOutDate,
                    $room,
                    $resident
                );
            }
        }
    }

    public function isBusyRoom(Room $room, DateTimeImmutable $date): bool
    {
        foreach ($this->bookings as $booking) {
            if ($booking->room()->equals($room)) {
                return false === BookingAvailabilityValidator::isAvailable(
                    $booking->checkIn(),
                    $booking->checkOut(),
                    $date
                );
            }
        }

        return false;
    }

    public function availableRoomsList(DateTimeImmutable $date): string
    {
        $availableRooms = [];
        foreach ($this->rooms as $room) {
            if (false === $this->isBusyRoom($room, $date)) {
                $availableRooms[] = $room;
            }
        }

        return ArrayToString::format($availableRooms);
    }

    /** @return Resident[] */
    private function residentsInRooms(DateTimeImmutable $dateToCheck = null): array
    {
        $residents = [];

        foreach ($this->bookings as $booking) {
            if (is_null($dateToCheck)) {
                $residents[$booking->resident()->dni()->value()] = $booking->resident();
                continue;
            }
            if (
                false === $booking->earlyCheckOut() &&
                false === BookingAvailabilityValidator::isAvailable(
                    $booking->checkIn(),
                    $booking->checkOut(),
                    $dateToCheck
                )
            ) {
                $residents[$booking->resident()->dni()->value()] = $booking->resident();
            }
        }

        return $residents;
    }

    public function residentsInRoomsList(DateTimeImmutable $dateToCheck): string
    {
        return ArrayToString::format($this->residentsInRooms($dateToCheck));
    }

    public function ageAverageByGender(DateTimeImmutable $dateToCheck): string
    {
        $residents = $this->residentsInRooms();
        $residentsStatistics = [];

        foreach ($residents as $resident) {
            $residentsStatistics[$resident->gender()][] = $resident->age($dateToCheck);
        }

        if (false === array_key_exists('F', $residentsStatistics)) {
            $residentsStatistics['F'][] = 0.0;
        }

        if (false === array_key_exists('M', $residentsStatistics)) {
            $residentsStatistics['M'][] = 0.0;
        }

        return ResidentsAgeAverageToString::format($residentsStatistics);
    }
}
