<?php
require_once '../../database/Database.php';
require_once '../../auth/Auth.php';
require_once '../../repository/admin/Admin.php';
require_once '../../controller/AdminController.php';
require_once '../../helpers/FlashMessage.php';
require_once '../../helpers/DateHelper.php';

use Database\Database;
use Auth\Auth;
use Repository\admin\Admin;
use Controller\AdminController;
use Helpers\FlashMessage;
use Helpers\DateHelper;

session_start();
$db = new Database;
$pdo = $db->getConnection();
$auth = new Auth($pdo);
$auth->requireAdmin();

$admin = new Admin($pdo);
$controller = new AdminController($admin);
$controller->handleRequest();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
    $participationId = $_POST['participation_id'];

    try {
        $admin->deleteBooking($participationId);
        FlashMessage::set('Booking deleted successfully!');
    } catch (Exception $e) {
        FlashMessage::set('Error deleting booking: ' . htmlspecialchars($e->getMessage()));
    }
}

$bookings = $admin->getBookings();
$eventTotals = $admin->getEventTotals();
$uploadMessage = FlashMessage::get();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <style>
        .alert {
            padding: 20px;
            background-color: #4CAF50;
            color: white;
            margin-bottom: 15px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 8px 12px;
            border: 1px solid #ccc;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
<h1>Admin Dashboard</h1>
<form action="../../auth/logout.php" method="POST">
    <button type="submit">Logout</button>
</form>

<?php if ($uploadMessage): ?>
    <div class="alert"><?= htmlspecialchars($uploadMessage) ?></div>
    <script>
        const alertDiv = document.querySelector('.alert');
        alertDiv.style.display = 'block';
        setTimeout(() => {
            alertDiv.style.display = 'none';
        }, 5000); // 5 seconds
    </script>
<?php endif; ?>

<h2>Upload JSON Bookings</h2>
<form action="upload_json.php" method="POST" enctype="multipart/form-data">
    <input type="file" name="json_file" accept=".json" required>
    <button type="submit">Upload and Import</button>
</form>

<h2>Create New Employee</h2>
<form action="manage_users.php" method="POST">
    <label for="new_username">Username:</label>
    <input type="text" name="new_username" required>
    <label for="new_password">Password:</label>
    <input type="password" name="new_password" required>
    <button type="submit" name="create_user">Create User</button>
</form>

<h2>Manage Users</h2>
<form action="manage_users.php" method="POST">
    <button type="submit">User Manage Admin Page</button>
</form>

<h2>Manage Bookings</h2>
<?php if (empty($bookings)): ?>
    <p>No bookings available at this time.</p>
<?php else: ?>
    <table>
        <thead>
            <tr>
                <th>Participation ID</th>
                <th>Employee Name</th>
                <th>Event Name</th>
                <th>Event Date</th>
                <th>Participation Fee</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($bookings as $booking): ?>
                <tr>
                    <td><?= htmlspecialchars($booking['participation_id']) ?></td>
                    <td><?= htmlspecialchars($booking['employee_name']) ?></td>
                    <td><?= htmlspecialchars($booking['event_name']) ?></td>
                    <td><?= DateHelper::format($booking['event_date']) ?></td>
                    <td><?= htmlspecialchars($booking['participation_fee']) ?></td>
                    <td>
                        <form action="admin.php" method="POST" style="display:inline;">
                            <input type="hidden" name="participation_id" value="<?= htmlspecialchars($booking['participation_id']) ?>">
                            <button type="submit" name="delete" onclick="return confirm('Are you sure you want to delete this booking?');">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

<h2>Event Totals</h2>
<?php if (empty($eventTotals)): ?>
    <p>No event totals available at this time.</p>
<?php else: ?>
    <table>
        <thead>
            <tr>
                <th>Event Name</th>
                <th>Total Participation Fee</th>
                <th>Total Participations</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($eventTotals as $event): ?>
                <tr>
                    <td><?= htmlspecialchars($event['event_name']) ?></td>
                    <td><?= htmlspecialchars(number_format($event['total_fee'], 2)) ?></td>
                    <td><?= htmlspecialchars($event['total_participations']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>
</body>
</html>
