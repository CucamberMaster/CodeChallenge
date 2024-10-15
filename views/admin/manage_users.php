<?php
require_once '../../auth/sessions.php';
require_once '../../Repository/admin/Admin.php';
require_once '../../database/Database.php';
require_once '../../auth/Auth.php';

use Database\Database;
use Auth\Auth;
use Repository\admin\Admin;

$db = new Database();
$pdo = $db->getConnection();
$admin = new Admin($pdo);
$auth = new Auth($pdo);
$auth->requireAdmin();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_user'])) {
    $newUsername = trim($_POST['new_username']);
    $newPassword = trim($_POST['new_password']);

    if (!empty($newUsername) && !empty($newPassword)) {
        try {
            $admin->createUser($newUsername, $newPassword);
            $message = "New employee created!";
        } catch (\Exception $e) {
            $message = $e->getMessage();
        }
    } else {
        $message = "Please fill in all fields.";
    }
}

$users = $admin->getUsers();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Users</title>
</head>
<body>
<h1>Manage Users</h1>

<?php if (isset($message)): ?>
    <div>
        <?= htmlspecialchars($message) ?>
    </div>
<?php endif; ?>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Username</th>
            <th>Role</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($users as $user): ?>
            <tr>
                <td><?= htmlspecialchars($user['id']) ?></td>
                <td><?= htmlspecialchars($user['username']) ?></td>
                <td><?= htmlspecialchars($user['role']) ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<h2>Create New Employee</h2>
<form action="manage_users.php" method="POST">
    <label for="new_username">Username:</label>
    <input type="text" name="new_username" required>
    <label for="new_password">Password:</label>
    <input type="password" name="new_password" required>
    <button type="submit" name="create_user">Create User</button>
</form>

<form action="../../auth/logout.php" method="POST">
    <button type="submit">Logout</button>
</form>
<form action="admin.php" method="POST">
    <button type="submit">Back to Main Admin Page</button>
</form>
</body>
</html>
