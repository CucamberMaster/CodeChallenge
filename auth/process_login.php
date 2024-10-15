<?php
require '../database/Database.php';
require './auth/sessions.php';
require '../auth/Auth.php';

use Database\Database;
use Auth\Auth;

$db = new Database();
$pdo = $db->getConnection();
$auth = new Auth($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if ($auth->login($username, $password)) {
        header('Location: ../views/admin/admin.php');
        exit();
    } else {
        echo "Invalid login credentials!";
    }
}
?>
