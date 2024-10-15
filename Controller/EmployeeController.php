<?php
namespace Controller;

use Repository\employee\Employee;
use Auth\Auth;

class EmployeeController {
    private $employee;

    public function __construct(Employee $employee) {
        $this->employee = $employee;
    }

    public function getBookings(string $username): array {
        return $this->employee->getBookings($username);
    }
}
