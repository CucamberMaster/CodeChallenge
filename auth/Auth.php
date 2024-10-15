<?php
namespace Auth;

class Auth
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function login($username, $password)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->execute([':username' => $username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            return true;
        }
        return false;
    }

    public function logout()
    {
        session_unset();
        session_destroy();
        header('Location: login.php');
        exit();
    }

    public function isLoggedIn()
    {
        return isset($_SESSION['username']);
    }

    public function isAdmin()
    {
        return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
    }

    public function requireLogin()
    {
        if (!$this->isLoggedIn()) {
            header('Location: login.php');
            exit();
        }
    }

    public function requireAdmin()
    {
        if (!$this->isAdmin()) {
            header('Location: views/employee/employee.php');
            exit();
        }
    }
}

?>
