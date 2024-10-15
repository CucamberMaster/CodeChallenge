<?php
require 'auth/sessions.php';
require 'auth/Auth.php';
require 'database/Database.php';
use SessionAuth;
use Auth\Auth;
use Database\Database;

try {
    $database = new Database();
    $pdo = $database->getConnection();
} catch (Exception $e) {
    die("Database connection failed: " . $e->getMessage());
}

$auth = new Auth($pdo);


if (!SessionAuth\isLoggedIn()) {
    header('Location: auth/login.php');
    exit();
}

if ($auth->isAdmin()) {
    header('Location: views/admin/admin.php');
} else {
    header('Location: components/views/employee/employee.php');
}
?>
