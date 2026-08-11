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
$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');

if (empty($name) || empty($email)) {
    echo json_encode(['success' => false, 'message' => 'Name and email are required']);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'Invalid email format']);
    exit;
}

try {
    // Check if email already exists for another user
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
    $stmt->execute([$email, $userId]);
    if ($stmt->fetch()) {
        echo json_encode(['success' => false, 'message' => 'Email already in use']);
        exit;
    }

    // Handle profile image upload
    $profileImage = null;
    if (isset($_FILES['profileImage']) && $_FILES['profileImage']['error'] === UPLOAD_ERR_OK) {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $fileType = $_FILES['profileImage']['type'];
        
        if (!in_array($fileType, $allowedTypes)) {
            echo json_encode(['success' => false, 'message' => 'Invalid image type. Only JPG, PNG, GIF, and WEBP are allowed']);
            exit;
        }

        $maxSize = 5 * 1024 * 1024; // 5MB
        if ($_FILES['profileImage']['size'] > $maxSize) {
            echo json_encode(['success' => false, 'message' => 'Image size must be less than 5MB']);
            exit;
        }

        $uploadDir = __DIR__ . '/../assets/images/profiles/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $extension = pathinfo($_FILES['profileImage']['name'], PATHINFO_EXTENSION);
        $filename = $userId . '_' . time() . '.' . $extension;
        $uploadPath = $uploadDir . $filename;

        if (move_uploaded_file($_FILES['profileImage']['tmp_name'], $uploadPath)) {
            $profileImage = '/assets/images/profiles/' . $filename;
            
            // Delete old profile image if exists
            $stmt = $pdo->prepare("SELECT profileImage FROM users WHERE id = ?");
            $stmt->execute([$userId]);
            $oldImage = $stmt->fetchColumn();
            if ($oldImage && file_exists(__DIR__ . '/..' . $oldImage)) {
                unlink(__DIR__ . '/..' . $oldImage);
            }
        }
    }

    // Update user profile
    if ($profileImage) {
        $stmt = $pdo->prepare("UPDATE users SET name = ?, email = ?, profileImage = ?, updatedAt = NOW() WHERE id = ?");
        $stmt->execute([$name, $email, $profileImage, $userId]);
    } else {
        $stmt = $pdo->prepare("UPDATE users SET name = ?, email = ?, updatedAt = NOW() WHERE id = ?");
        $stmt->execute([$name, $email, $userId]);
    }

    $_SESSION['name'] = $name;
    $_SESSION['email'] = $email;
    if ($profileImage) {
        $_SESSION['profileImage'] = $profileImage;
    }

    echo json_encode(['success' => true, 'message' => 'Profile updated successfully', 'profileImage' => $profileImage]);
} catch (Exception $e) {
    error_log("Update profile error: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Failed to update profile']);
}
