<?php
require_once __DIR__ . '/../includes/db.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

if (!isLoggedIn()) {
    echo json_encode(['success' => false, 'message' => 'Please login first']);
    exit;
}

$userId = $_SESSION['user_id'];
$password = $_POST['password'] ?? '';
$confirmation = $_POST['confirmation'] ?? '';

if (empty($password)) {
    echo json_encode(['success' => false, 'message' => 'Password is required']);
    exit;
}

if ($confirmation !== 'DELETE') {
    echo json_encode(['success' => false, 'message' => 'Please type DELETE to confirm']);
    exit;
}

try {
    // Verify password
    $stmt = $pdo->prepare("SELECT password, profileImage FROM users WHERE id = ?");
    $stmt->execute([$userId]);
    $user = $stmt->fetch();

    if (!$user || !password_verify($password, $user['password'])) {
        echo json_encode(['success' => false, 'message' => 'Incorrect password']);
        exit;
    }

    // Delete profile image if exists
    if ($user['profileImage'] && file_exists(__DIR__ . '/..' . $user['profileImage'])) {
        unlink(__DIR__ . '/..' . $user['profileImage']);
    }

    // Delete user (cascade will handle related records)
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute([$userId]);

    // Clear session
    session_destroy();

    echo json_encode(['success' => true, 'message' => 'Account deleted successfully']);
} catch (Exception $e) {
    error_log("Delete account error: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Failed to delete account']);
}
