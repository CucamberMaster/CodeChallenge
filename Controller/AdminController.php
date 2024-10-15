<?php
namespace Controller;

use Repository\admin\Admin;
use Helpers\FileHelper;
use Helpers\FlashMessage;

class AdminController
{
    private $admin;

    public function __construct(Admin $admin)
    {
        $this->admin = $admin;
    }

    public function handleRequest(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['delete'])) {
                $this->deleteBooking();
            } elseif (isset($_POST['update'])) {
                $this->updateBooking();
            } elseif (isset($_FILES['json_file'])) {
                $this->uploadBookings();
            }
        }
    }

    private function deleteBooking(): void
    {
        try {
            $this->admin->deleteBooking((int)$_POST['participation_id']);
            FlashMessage::set('Booking deleted successfully!');
        } catch (\Exception $e) {
            FlashMessage::set('Failed to delete booking: ' . $e->getMessage());
        }
        $this->redirect('admin.php');
    }

    private function updateBooking(): void
    {
        try {
            $updated = $this->admin->updateBooking(
                (int)$_POST['participation_id'],
                $_POST['event_name'],
                $_POST['event_date'],
                (float)$_POST['participation_fee']
            );
            FlashMessage::set($updated ? 'Booking updated successfully!' : 'Failed to update booking.');
        } catch (\Exception $e) {
            FlashMessage::set('Error updating booking: ' . $e->getMessage());
        }
        $this->redirect('admin.php');
    }

    private function uploadBookings(): void
    {
        try {
            if ($_FILES['json_file']['error'] !== UPLOAD_ERR_OK) {
                throw new \Exception("File upload error: " . $_FILES['json_file']['error']);
            }

            if ($_FILES['json_file']['type'] !== 'application/json') {
                throw new \Exception("Please upload a valid JSON file.");
            }

            $uploadDir = '../../database/savedJson/';
            $fileData = FileHelper::saveAndCompressJson($_FILES['json_file']['tmp_name'], $uploadDir);


            $this->admin->saveJsonFile($fileData['fileName'], $fileData['filePath'], $fileData['fileSize']);

            $jsonData = file_get_contents($_FILES['json_file']['tmp_name']);
            $bookings = json_decode($jsonData, true);

            if ($bookings === null || empty($bookings)) {
                throw new \Exception("Invalid or empty JSON format.");
            }

            $this->admin->uploadBookings($bookings);
            FlashMessage::set('Bookings uploaded successfully and JSON file saved.');
        } catch (\Exception $e) {
            FlashMessage::set('Error uploading bookings: ' . htmlspecialchars($e->getMessage()));
        }
        $this->redirect('admin.php');
    }

    private function redirect(string $url): void
    {
        header("Location: $url");
        exit();
    }
}
