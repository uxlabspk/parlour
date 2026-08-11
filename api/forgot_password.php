<?php
// api/forgot_password.php
require_once __DIR__ . '/../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../auth/forgot-password");
    exit;
}

$email = $_POST['email'] ?? '';

if (empty($email)) {
    header("Location: ../auth/forgot-password?error=" . urlencode('Email is required'));
    exit;
}

// Check if user exists
$stmt = $pdo->prepare("SELECT id, email, name FROM users WHERE email = ?");
$stmt->execute([$email]);
$user = $stmt->fetch();

if (!$user) {
    // Don't reveal if email exists or not for security reasons
    // Still show success message
    header("Location: ../auth/forgot-password.php?success=1");
    exit;
}

// Generate reset token
$resetToken = bin2hex(random_bytes(32));
$resetTokenExpiry = date('Y-m-d H:i:s', strtotime('+1 hour'));

// Update user with reset token
$stmt = $pdo->prepare("UPDATE users SET resetToken = ?, resetTokenExpiry = ? WHERE id = ?");
$stmt->execute([$resetToken, $resetTokenExpiry, $user['id']]);

// Generate reset link
$resetLink = "http://" . $_SERVER['HTTP_HOST'] . "/auth/reset-password?token=" . $resetToken;

// Send email (you'll need to configure this based on your email service)
// For now, we'll just log it or you can integrate with PHPMailer, SendGrid, etc.
$emailSent = sendPasswordResetEmail($user['email'], $user['name'], $resetLink);

// For development: Log the reset link
error_log("Password reset link for {$user['email']}: $resetLink");

// In production, you should actually send the email
// For now, redirect with success message
header("Location: ../auth/forgot-password?success=1");
exit;

/**
 * Send password reset email
 * You can implement this with PHPMailer, SendGrid, or your preferred email service
 */
function sendPasswordResetEmail($to, $name, $resetLink) {
    // Example email content
    $subject = "Password Reset Request";
    $message = "
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .button { background-color: #111827; color: white; padding: 12px 24px; text-decoration: none; border-radius: 8px; display: inline-block; }
            </style>
        </head>
        <body>
            <div class='container'>
                <h2>Password Reset Request</h2>
                <p>Hello " . htmlspecialchars($name) . ",</p>
                <p>We received a request to reset your password. Click the button below to reset it:</p>
                <p><a href='" . htmlspecialchars($resetLink) . "' class='button'>Reset Password</a></p>
                <p>Or copy and paste this link into your browser:</p>
                <p>" . htmlspecialchars($resetLink) . "</p>
                <p>This link will expire in 1 hour.</p>
                <p>If you didn't request a password reset, please ignore this email.</p>
                <p>Best regards,<br>MA Essentials Team</p>
            </div>
        </body>
        </html>
    ";
    
    // Set headers
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= "From: noreply@parlour.com" . "\r\n";
    
    // Send email using PHP's mail() function
    // In production, use a proper email service like SendGrid, Mailgun, or PHPMailer
    // return mail($to, $subject, $message, $headers);
    
    // For development: Just return true and log the link
    error_log("Email would be sent to: $to with link: $resetLink");
    return true;
}
