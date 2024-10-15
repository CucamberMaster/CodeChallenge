<?php
namespace Helpers;


class FlashMessage {
    public static function set(string $message): void {
        $_SESSION['flash_message'] = $message;
    }

    public static function get(): ?string {
        if (isset($_SESSION['flash_message'])) {
            $message = $_SESSION['flash_message'];
            unset($_SESSION['flash_message']);
            return $message;
        }
        return null;
    }
}
