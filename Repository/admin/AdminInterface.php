<?php
namespace Repository\admin;

interface AdminInterface
{
    public function getBookings(): array;
    public function getEventTotals(): array;
    public function deleteBooking(int $participationId): void;
    public function updateBooking(int $participationId, string $eventName, string $eventDate, float $participationFee): bool;
}
