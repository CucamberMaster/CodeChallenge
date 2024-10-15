<?php
namespace SessionAuth;

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 *
 * @return bool
 */
function isLoggedIn(): bool {
    return isset($_SESSION['username']);
}

/**
 * @return bool
 */
function isAdmin(): bool {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

/**
 *
 * @param string $redirectUrl
 */
function requireLogin(string $redirectUrl = 'login.php'): void {
    if (!isLoggedIn()) {
        header("Location: $redirectUrl");
        exit();
    }
}

/**
 *
 * @param string $redirectUrl
 */
function requireAdmin(string $redirectUrl = 'views/employee/employee.php'): void {
    if (!isAdmin()) {
        header("Location: $redirectUrl");
        exit();
    }
}
?>
