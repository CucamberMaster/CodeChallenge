<?php
namespace Repository\employee;

use PDO;
use PDOException;

class Employee {
    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function getBookings(string $employeeName): array {
        error_log("Fetching bookings for employee: $employeeName");

        try {
            $stmt = $this->pdo->prepare("SELECT * FROM bookings WHERE employee_name = :employee_name");
            $stmt->execute([':employee_name' => $employeeName]);
            $bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (empty($bookings)) {
                error_log("No bookings found for employee: $employeeName");
            }

            return $bookings;

        } catch (PDOException $e) {
            error_log("Database error: " . $e->getMessage());
            return [];
        }
    }
}
