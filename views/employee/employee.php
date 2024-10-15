
<?php
require '../../auth/sessions.php';
require '../../database/Database.php';
require '../../auth/Auth.php';
require '../../repository/employee/Employee.php';
require '../../controller/EmployeeController.php';
require '../../helpers/DateHelper.php';

use Database\Database;
use Auth\Auth;
use Repository\employee\Employee;
use Controller\EmployeeController;
use Helpers\DateHelper;

SessionAuth\requireLogin();

$db = new Database();
$pdo = $db->getConnection();
$auth = new Auth($pdo);
$employee = new Employee($pdo);
$controller = new EmployeeController($employee);
$bookings = $controller->getBookings($_SESSION['username']);
$totalParticipationFee = 0;
$username = htmlspecialchars($_SESSION['username']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= $username ?> - Bookings</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 8px;
            border: 1px solid #ccc;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
<h1><?= $username ?> - Bookings</h1>
<table>
    <thead>
        <tr>
            <th>Participation ID</th>
            <th>Event Name</th>
            <th>Event Date</th>
            <th>Participation Fee</th>
        </tr>
    </thead>
    <tbody>
        <?php if (empty($bookings)): ?>
            <tr>
                <td colspan="4">No bookings found. Or not logged in to the correct account</td>
            </tr>
        <?php else: ?>
            <?php foreach ($bookings as $booking): ?>
                <tr>
                    <td><?= htmlspecialchars($booking['participation_id']) ?></td>
                    <td><?= htmlspecialchars($booking['event_name']) ?></td>
                    <td><?= DateHelper::format($booking['event_date']) ?></td> <!-- Use DateHelper here -->
                    <td><?= htmlspecialchars(number_format($booking['participation_fee'], 2)) ?></td>
                </tr>
                <?php $totalParticipationFee += floatval($booking['participation_fee']); ?>
            <?php endforeach; ?>
            <tr>
                <td colspan="3" style="text-align: right; font-weight: bold;">Total:</td>
                <td style="font-weight: bold;"><?= htmlspecialchars(number_format($totalParticipationFee, 2)) ?></td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>
<form action="../../auth/logout.php" method="POST">
    <button type="submit">Logout</button>
</form>
</body>
</html>