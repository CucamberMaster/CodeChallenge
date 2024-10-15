<?php

class Database
{
    private $host = 'localhost';

    private $db = 'event_system';

    private $user = 'root';

    private $pass = '';

    private $charset = 'utf8mb4';

    private $pdo;

    public function __construct()
    {
        $this->connect();
    }

    private function connect()
    {
        $dsn = "mysql:host={$this->host};charset={$this->charset}";
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];

        try {
            $this->pdo = new PDO($dsn, $this->user, $this->pass, $options);
            $this->pdo->exec("CREATE DATABASE IF NOT EXISTS {$this->db}");
            echo "Database '{$this->db}' created or already exists.<br>";
            $dsn = "mysql:host={$this->host};dbname={$this->db};charset={$this->charset}";
            $this->pdo = new PDO($dsn, $this->user, $this->pass, $options);
        } catch (PDOException $e) {
            throw new PDOException($e->getMessage(), (int)$e->getCode());
        }
    }

    public function getConnection()
    {
        return $this->pdo;
    }

    public function setupTables()
    {
        $this->pdo->exec(
            "
            CREATE TABLE IF NOT EXISTS users (
                id INT AUTO_INCREMENT PRIMARY KEY,
                username VARCHAR(255) UNIQUE NOT NULL,
                password VARCHAR(255) NOT NULL,
                role ENUM('admin', 'employee') NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            );
        "
        );
        echo "'users' table created or already exists.<br>";

        $this->pdo->exec(
            "
            CREATE TABLE IF NOT EXISTS bookings (
                participation_id INT PRIMARY KEY,
                employee_name VARCHAR(255),
                employee_mail VARCHAR(255),
                event_id INT,
                event_name VARCHAR(255),
                participation_fee DECIMAL(10, 2),
                event_date DATETIME
            );
        "
        );
        echo "'bookings' table created or already exists.<br>";

        $this->pdo->exec(
            "
CREATE TABLE IF NOT EXISTS savedJsonFiles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    file_name VARCHAR(255) NOT NULL,
    file_path VARCHAR(255) NOT NULL,
    file_size INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
;
        "
        );
        echo "'savedJsonFiles ' table created or already exists.<br>";
    }

    public function createAdminUser()
    {
        $username = 'admin';
        $password = password_hash('admin_password', PASSWORD_BCRYPT); // Admin password (hashed)

        try {
            $stmt = $this->pdo->prepare(
                "INSERT INTO users (username, password, role) VALUES (:username, :password, 'admin')"
            );
            $stmt->execute([':username' => $username, ':password' => $password]);
            echo "Admin user created successfully!<br>";
        } catch (PDOException $e) {
            echo "Error creating admin user: ".$e->getMessage()."<br>";
        }
    }
}

$db = new Database;
$db->setupTables();
$db->createAdminUser();

?>
