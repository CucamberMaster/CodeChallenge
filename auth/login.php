<?php
require '../database/Database.php';
require '../auth/Auth.php';

use Database\Database;
use Auth\Auth;

$db = new Database;
$pdo = $db->getConnection();
$auth = new Auth($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    $stmt = $pdo->query("SELECT COUNT(*) FROM users");
    $userCount = $stmt->fetchColumn();

    if ($userCount == 0) {
        header('Location: ../views/employee/employee.php');
        exit();
    }

    if ($auth->login($username, $password)) {
        $redirectPath = $auth->isAdmin(
        ) ? '../views/admin/admin.php' : '../views/employee/employee.php';
        header('Location: '.$redirectPath);
        exit();
    } else {
        $error = "Invalid username or password.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
</head>
<body>
<h1>Login</h1>
<form method="POST">
    <input type="text" name="username" placeholder="Username" required>
    <input type="password" name="password" placeholder="Password" required>
    <button type="submit">Login</button>
</form>
<?php if (isset($error)): ?>
    <p style="color:red;"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>
</body>
</html>
