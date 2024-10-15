<?php
require '../../auth/sessions.php';
require '../../database/Database.php';
require '../../repository/admin/Admin.php';
require '../../auth/Auth.php';
require '../../helpers/FlashMessage.php';
require '../../helpers/FileHelper.php';

use Database\Database;
use Helpers\FlashMessage;
use Repository\admin\Admin;
use Auth\Auth;
use Helpers\FileHelper;

$db = new Database;
$pdo = $db->getConnection();
$admin = new Admin($pdo);
$auth = new Auth($pdo);
$auth->requireAdmin();

error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['json_file'])) {
    try {
        if ($_FILES['json_file']['error'] !== UPLOAD_ERR_OK) {
            throw new Exception("File upload error: " . $_FILES['json_file']['error']);
        }

        if ($_FILES['json_file']['type'] !== 'application/json') {
            throw new Exception("Please upload a valid JSON file.");
        }

        $uploadDir = '../../database/savedJson/';
        $fileData = FileHelper::saveAndCompressJson($_FILES['json_file']['tmp_name'], $uploadDir);

        $admin->saveJsonFile($fileData['fileName'], $fileData['filePath'], $fileData['fileSize']);

        $jsonData = file_get_contents($_FILES['json_file']['tmp_name']);
        $bookings = json_decode($jsonData, true);

        if (empty($bookings)) {
            throw new Exception("Invalid or empty JSON format.");
        }

        $admin->uploadBookings($bookings);

        FlashMessage::set('Bookings uploaded successfully and JSON file saved.');
        header('Location: admin.php');
        exit();
    } catch (Exception $e) {
        FlashMessage::set('Error: ' . htmlspecialchars($e->getMessage()));
        header('Location: admin.php');
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Upload Bookings</title>
</head>
<body>
<h1>Upload Bookings from JSON</h1>
<form method="POST" enctype="multipart/form-data">
    <input type="file" name="json_file" accept=".json" required>
    <button type="submit">Upload</button>
</form>
<form action="../../auth/logout.php" method="POST">
    <button type="submit">Logout</button>
</form>
</body>
</html>
