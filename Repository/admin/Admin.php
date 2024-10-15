<?php
namespace Repository\admin;

use PDO;

class Admin
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function createUser($username, $password)
    {
        if (empty($username) || empty($password)) {
            throw new \InvalidArgumentException("Username and password cannot be empty.");
        }

        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM users WHERE username = :username");
        $stmt->execute([':username' => $username]);
        $count = $stmt->fetchColumn();

        if ($count > 0) {
            throw new \Exception("Username already exists.");
        }

        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $this->pdo->prepare(
            "INSERT INTO users (username, password, role) VALUES (:username, :password, 'employee')"
        );
        $stmt->execute([':username' => $username, ':password' => $hashedPassword]);
    }

    public function getUsers()
    {
        $stmt = $this->pdo->query("SELECT * FROM users");
        return $stmt->fetchAll();
    }

    public function deleteBooking($participationId)
    {
        $stmt = $this->pdo->prepare("DELETE FROM bookings WHERE participation_id = :participation_id");
        $stmt->bindParam(':participation_id', $participationId, PDO::PARAM_INT);
        if (!$stmt->execute()) {
            throw new Exception("Failed to delete booking.");
        }
    }

    public function updateBooking($participationId, $eventName, $eventDate, $participationFee)
    {
        $stmt = $this->pdo->prepare(
            "UPDATE bookings SET event_name = :event_name, event_date = :event_date, participation_fee = :participation_fee WHERE participation_id = :participation_id"
        );
        return $stmt->execute([
            ':event_name' => $eventName,
            ':event_date' => $eventDate,
            ':participation_fee' => $participationFee,
            ':participation_id' => $participationId,
        ]);
    }

    public function getBookings()
    {
        $stmt = $this->pdo->query("SELECT * FROM bookings");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getEventTotals()
    {
        $stmt = $this->pdo->prepare(
            "
            SELECT event_name, 
                   SUM(participation_fee) AS total_fee, 
                   COUNT(participation_id) AS total_participations
            FROM bookings
            GROUP BY event_name
        "
        );

        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function saveJsonFile($fileName, $filePath, $fileSize)
    {
        $stmt = $this->pdo->prepare(
            "INSERT INTO savedJsonFiles (file_name, file_path, file_size, created_at)
            VALUES (:file_name, :file_path, :file_size, NOW())"
        );
        $stmt->execute([
            ':file_name' => $fileName,
            ':file_path' => $filePath,
            ':file_size' => $fileSize,
        ]);
    }

    public function uploadBookings($bookings)
    {
        $stmt = $this->pdo->prepare(
            "INSERT INTO bookings 
            (participation_id, employee_name, employee_mail, event_id, event_name, participation_fee, event_date)
            VALUES (:participation_id, :employee_name, :employee_mail, :event_id, :event_name, :participation_fee, :event_date)
            ON DUPLICATE KEY UPDATE 
            employee_name = VALUES(employee_name), 
            employee_mail = VALUES(employee_mail), 
            event_name = VALUES(event_name), 
            participation_fee = VALUES(participation_fee), 
            event_date = VALUES(event_date)"
        );

        foreach ($bookings as $booking) {
            $stmt->execute([
                ':participation_id' => (int)$booking['participation_id'],
                ':employee_name' => $booking['employee_name'],
                ':employee_mail' => $booking['employee_mail'],
                ':event_id' => (int)$booking['event_id'],
                ':event_name' => $booking['event_name'],
                ':participation_fee' => floatval($booking['participation_fee']),
                ':event_date' => $booking['event_date'],
            ]);
        }
    }
}