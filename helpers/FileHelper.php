<?php
namespace Helpers;

class FileHelper {
    public static function saveAndCompressJson($fileTmpPath, $uploadDir) {
        $jsonData = file_get_contents($fileTmpPath);

        $compressedData = gzencode($jsonData);
        $fileName = time() . '_bookings.json.gz';
        $filePath = $uploadDir . $fileName;

        if (!file_put_contents($filePath, $compressedData)) {
            throw new \Exception("Failed to save the compressed JSON file.");
        }

        $fileSize = filesize($filePath);
        return ['fileName' => $fileName, 'filePath' => $filePath, 'fileSize' => $fileSize];
    }
}
