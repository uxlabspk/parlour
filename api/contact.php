<?php
// api/contact.php
require_once __DIR__ . '/../includes/db.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

// Get form data
$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$message = trim($_POST['message'] ?? '');

// Validate input
$errors = [];

if (empty($name)) {
    $errors[] = 'Name is required';
}

if (empty($email)) {
    $errors[] = 'Email is required';
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Invalid email format';
}

if (empty($message)) {
    $errors[] = 'Message is required';
} elseif (strlen($message) < 10) {
    $errors[] = 'Message must be at least 10 characters';
}

if (!empty($errors)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => implode(', ', $errors)]);
    exit;
}

// Save to database (optional - create contacts table if needed)
try {
    $stmt = $pdo->prepare("
        INSERT INTO contacts (name, email, message, createdAt) 
        VALUES (?, ?, ?, NOW())
    ");
    $stmt->execute([$name, $email, $message]);
} catch (PDOException $e) {
    // If contacts table doesn't exist, just continue without saving
    error_log("Contact form submission (DB save failed): " . $e->getMessage());
}

// Send email to admin
$adminEmail = 'support@parlour.com'; // Change this to your actual admin email
$emailSent = sendContactEmail($adminEmail, $name, $email, $message);

// Send confirmation email to user
$confirmationSent = sendConfirmationEmail($email, $name);

// Log for development
error_log("Contact form submission from: $name ($email)");
error_log("Message: $message");

// Return success response
echo json_encode([
    'success' => true,
    'message' => 'Thank you for contacting us! We will get back to you soon.'
]);
exit;

/**
 * Send contact form email to admin
 */
function sendContactEmail($adminEmail, $name, $email, $message) {
    $subject = "New Contact Form Submission from " . $name;
    
    $htmlMessage = "
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; background-color: #f9fafb; }
                .content { background-color: white; padding: 30px; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
                .header { font-size: 24px; font-weight: bold; margin-bottom: 20px; color: #111827; }
                .field { margin-bottom: 15px; }
                .label { font-weight: bold; color: #374151; margin-bottom: 5px; }
                .value { color: #6b7280; }
                .message-box { background-color: #f3f4f6; padding: 15px; border-radius: 8px; margin-top: 15px; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='content'>
                    <div class='header'>New Contact Form Submission</div>
                    
                    <div class='field'>
                        <div class='label'>From:</div>
                        <div class='value'>" . htmlspecialchars($name) . "</div>
                    </div>
                    
                    <div class='field'>
                        <div class='label'>Email:</div>
                        <div class='value'>" . htmlspecialchars($email) . "</div>
                    </div>
                    
                    <div class='field'>
                        <div class='label'>Message:</div>
                        <div class='message-box'>" . nl2br(htmlspecialchars($message)) . "</div>
                    </div>
                    
                    <div style='margin-top: 20px; padding-top: 20px; border-top: 1px solid #e5e7eb; color: #9ca3af; font-size: 12px;'>
                        <p>This email was sent from the MA Essentials contact form on " . date('F j, Y \a\t g:i A') . "</p>
                    </div>
                </div>
            </div>
        </body>
        </html>
    ";
    
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= "From: " . $email . "\r\n";
    $headers .= "Reply-To: " . $email . "\r\n";
    
    // For development: Log the email
    error_log("Contact email would be sent to: $adminEmail");
    
    // In production, use a proper email service
    // return mail($adminEmail, $subject, $htmlMessage, $headers);
    
    return true;
}

/**
 * Send confirmation email to user
 */
function sendConfirmationEmail($to, $name) {
    $subject = "Thank you for contacting MA Essentials";
    
    $htmlMessage = "
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .content { background-color: #f9fafb; padding: 30px; border-radius: 12px; }
                .header { font-size: 24px; font-weight: bold; margin-bottom: 20px; color: #111827; }
                .button { background-color: #111827; color: white; padding: 12px 24px; text-decoration: none; border-radius: 8px; display: inline-block; margin-top: 20px; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='content'>
                    <div class='header'>Thank You for Reaching Out!</div>
                    <p>Hello " . htmlspecialchars($name) . ",</p>
                    <p>We've received your message and appreciate you taking the time to contact us.</p>
                    <p>Our customer support team will review your inquiry and get back to you within 24-48 hours.</p>
                    <p>In the meantime, feel free to explore our latest work:</p>
                    <a href='http://" . $_SERVER['HTTP_HOST'] . "/shop.php' class='button'>Browse Shop</a>
                    <p style='margin-top: 30px;'>Best regards,<br><strong>MA Essentials Team</strong></p>
                    <div style='margin-top: 30px; padding-top: 20px; border-top: 1px solid #d1d5db; color: #6b7280; font-size: 12px;'>
                        <p>MA Essentials | 123 Fashion Avenue, New York, NY 10001</p>
                        <p>support@parlour.com | +1 (555) 123-4567</p>
                    </div>
                </div>
            </div>
        </body>
        </html>
    ";
    
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= "From: support@parlour.com" . "\r\n";
    
    // For development: Log the email
    error_log("Confirmation email would be sent to: $to");
    
    // In production, use a proper email service
    // return mail($to, $subject, $htmlMessage, $headers);
    
    return true;
}
