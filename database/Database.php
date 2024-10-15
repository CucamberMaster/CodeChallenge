<?php
namespace Database;

use PDO;
use PDOException;

class Database {
    private $host = 'localhost';
    private $db = 'event_system';
    private $user = 'root';
    private $pass = '';
    private $charset = 'utf8mb4';
    private $pdo;

    public function __construct() {
        $this->createDatabase();
        $this->connect();
    }

    private function createDatabase() {
        $dsn = "mysql:host={$this->host};charset={$this->charset}";
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];

        try {
            $this->pdo = new PDO($dsn, $this->user, $this->pass, $options);
            $this->pdo->exec("CREATE DATABASE IF NOT EXISTS {$this->db}");
        } catch (PDOException $e) {
            throw new PDOException($e->getMessage(), (int)$e->getCode());
        }
    }

    private function connect() {
        $dsn = "mysql:host={$this->host};dbname={$this->db};charset={$this->charset}";
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];

        try {
            $this->pdo = new PDO($dsn, $this->user, $this->pass, $options);
            $this->setupTables();
        } catch (PDOException $e) {
            throw new PDOException($e->getMessage(), (int)$e->getCode());
        }
    }

    public function getConnection() {
        return $this->pdo;
    }

    public function setupTables() {
        $this->pdo->exec(
            "CREATE TABLE IF NOT EXISTS users (
                id INT AUTO_INCREMENT PRIMARY KEY,
                username VARCHAR(255) UNIQUE NOT NULL,
                password VARCHAR(255) NOT NULL,
                role ENUM('admin', 'employee') NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            );"
        );

        $this->pdo->exec(
            "CREATE TABLE IF NOT EXISTS bookings (
                participation_id INT AUTO_INCREMENT PRIMARY KEY,
                employee_name VARCHAR(255),
                employee_mail VARCHAR(255),
                event_id INT,
                event_name VARCHAR(255),
                participation_fee DECIMAL(10, 2),
                event_date DATETIME
            );"
        );
    }

    public function createAdminUser() {
        $username = 'admin';
        $password = password_hash('admin_password', PASSWORD_BCRYPT);

        try {
            $stmt = $this->pdo->prepare(
                "INSERT INTO users (username, password, role) VALUES (:username, :password, 'admin')"
            );
            $stmt->execute([':username' => $username, ':password' => $password]);
        } catch (PDOException $e) {
            echo "Error creating admin user: " . $e->getMessage();
        }
    }
}
