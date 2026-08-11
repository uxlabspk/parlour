<?php
// php_web/includes/db.php

$host = '193.203.168.147';
$db   = 'u872295631_testing';
$user = 'u872295631_123';
$pass = 'Sh12zil@567';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
     $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
     // For production, you might want to log this and show a generic message
     // throw new \PDOException($e->getMessage(), (int)$e->getCode());
     die("Database connection failed. Please check your configuration.");
}

/**
 * Generate a UUID v4
 */
function generateUUID() {
    return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        mt_rand(0, 0xffff), mt_rand(0, 0xffff),
        mt_rand(0, 0xffff),
        mt_rand(0, 0x0fff) | 0x4000,
        mt_rand(0, 0x3fff) | 0x8000,
        mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
    );
}

/**
 * Auth Helpers
 */
session_start();

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function isAdmin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'ADMIN';
}

function isManager() {
    return isset($_SESSION['role']) && ($_SESSION['role'] === 'MANAGER' || $_SESSION['role'] === 'ADMIN');
}

function requireLogin() {
    if (!isLoggedIn()) {
        // Get current page for redirect after login
        $currentPage = basename($_SERVER['PHP_SELF'], '.php');
        header("Location: /auth/login?redirect=" . urlencode($currentPage));
        exit;
    }
}

function requireAdmin() {
    if (!isAdmin()) {
        header("Location: /");
        exit;
    }
}
?>
