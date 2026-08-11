<?php
// api/reset_password.php
require_once __DIR__ . '/../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../auth/login");
    exit;
}

$token = $_POST['token'] ?? '';
$password = $_POST['password'] ?? '';
$confirmPassword = $_POST['confirm_password'] ?? '';

// Validate input
if (empty($token) || empty($password) || empty($confirmPassword)) {
    header("Location: ../auth/reset-password?token=" . urlencode($token) . "&error=" . urlencode('All fields are required'));
    exit;
}

if ($password !== $confirmPassword) {
    header("Location: ../auth/reset-password?token=" . urlencode($token) . "&error=" . urlencode('Passwords do not match'));
    exit;
}

if (strlen($password) < 6) {
    header("Location: ../auth/reset-password?token=" . urlencode($token) . "&error=" . urlencode('Password must be at least 6 characters'));
    exit;
}

// Verify token exists and is not expired
$stmt = $pdo->prepare("SELECT id, email FROM users WHERE resetToken = ? AND resetTokenExpiry > NOW()");
$stmt->execute([$token]);
$user = $stmt->fetch();

if (!$user) {
    header("Location: ../auth/reset-password?token=" . urlencode($token) . "&error=" . urlencode('Invalid or expired reset token'));
    exit;
}

// Hash new password
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// Update password and clear reset token
$stmt = $pdo->prepare("UPDATE users SET password = ?, resetToken = NULL, resetTokenExpiry = NULL WHERE id = ?");
$stmt->execute([$hashedPassword, $user['id']]);

// Log the user in automatically (optional)
$_SESSION['user_id'] = $user['id'];
$_SESSION['email'] = $user['email'];

// Fetch complete user info
$stmt = $pdo->prepare("SELECT name, role FROM users WHERE id = ?");
$stmt->execute([$user['id']]);
$userInfo = $stmt->fetch();

$_SESSION['name'] = $userInfo['name'];
$_SESSION['role'] = $userInfo['role'];

// Redirect to success page or home with success message
$_SESSION['success_message'] = 'Your password has been reset successfully!';
header("Location: ../");
exit;
