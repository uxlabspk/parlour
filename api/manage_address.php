<?php
require_once __DIR__ . '/../includes/db.php';
header('Content-Type: application/json');

if (!isLoggedIn()) {
    echo json_encode(['success' => false, 'message' => 'Please login first']);
    exit;
}

$userId = $_SESSION['user_id'];
$action = $_POST['action'] ?? $_GET['action'] ?? '';

try {
    switch ($action) {
        case 'add':
        case 'update':
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                echo json_encode(['success' => false, 'message' => 'Invalid request method']);
                exit;
            }

            $addressId = $_POST['address_id'] ?? null;
            $type = $_POST['type'] ?? 'SHIPPING';
            $fullName = trim($_POST['full_name'] ?? '');
            $phone = trim($_POST['phone'] ?? '');
            $addressLine1 = trim($_POST['address_line1'] ?? '');
            $addressLine2 = trim($_POST['address_line2'] ?? '');
            $city = trim($_POST['city'] ?? '');
            $state = trim($_POST['state'] ?? '');
            $postalCode = trim($_POST['postal_code'] ?? '');
            $country = trim($_POST['country'] ?? '');
            $isDefault = isset($_POST['is_default']) && $_POST['is_default'] === '1';

            if (empty($fullName) || empty($phone) || empty($addressLine1) || empty($city) || empty($state) || empty($postalCode) || empty($country)) {
                echo json_encode(['success' => false, 'message' => 'All required fields must be filled']);
                exit;
            }

            // If setting as default, unset other defaults
            if ($isDefault) {
                $stmt = $pdo->prepare("UPDATE addresses SET isDefault = FALSE WHERE userId = ?");
                $stmt->execute([$userId]);
            }

            if ($action === 'add') {
                $newId = bin2hex(random_bytes(18));
                $stmt = $pdo->prepare("INSERT INTO addresses (id, userId, type, fullName, phone, addressLine1, addressLine2, city, state, postalCode, country, isDefault) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([$newId, $userId, $type, $fullName, $phone, $addressLine1, $addressLine2, $city, $state, $postalCode, $country, $isDefault]);
                echo json_encode(['success' => true, 'message' => 'Address added successfully']);
            } else {
                // Verify address belongs to user
                $stmt = $pdo->prepare("SELECT id FROM addresses WHERE id = ? AND userId = ?");
                $stmt->execute([$addressId, $userId]);
                if (!$stmt->fetch()) {
                    echo json_encode(['success' => false, 'message' => 'Address not found']);
                    exit;
                }

                $stmt = $pdo->prepare("UPDATE addresses SET type = ?, fullName = ?, phone = ?, addressLine1 = ?, addressLine2 = ?, city = ?, state = ?, postalCode = ?, country = ?, isDefault = ?, updatedAt = NOW() WHERE id = ? AND userId = ?");
                $stmt->execute([$type, $fullName, $phone, $addressLine1, $addressLine2, $city, $state, $postalCode, $country, $isDefault, $addressId, $userId]);
                echo json_encode(['success' => true, 'message' => 'Address updated successfully']);
            }
            break;

        case 'delete':
            $addressId = $_POST['address_id'] ?? '';
            if (empty($addressId)) {
                echo json_encode(['success' => false, 'message' => 'Address ID is required']);
                exit;
            }

            $stmt = $pdo->prepare("DELETE FROM addresses WHERE id = ? AND userId = ?");
            $stmt->execute([$addressId, $userId]);

            if ($stmt->rowCount() > 0) {
                echo json_encode(['success' => true, 'message' => 'Address deleted successfully']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Address not found']);
            }
            break;

        case 'set_default':
            $addressId = $_POST['address_id'] ?? '';
            if (empty($addressId)) {
                echo json_encode(['success' => false, 'message' => 'Address ID is required']);
                exit;
            }

            // Verify address belongs to user
            $stmt = $pdo->prepare("SELECT id FROM addresses WHERE id = ? AND userId = ?");
            $stmt->execute([$addressId, $userId]);
            if (!$stmt->fetch()) {
                echo json_encode(['success' => false, 'message' => 'Address not found']);
                exit;
            }

            // Unset all defaults
            $stmt = $pdo->prepare("UPDATE addresses SET isDefault = FALSE WHERE userId = ?");
            $stmt->execute([$userId]);

            // Set new default
            $stmt = $pdo->prepare("UPDATE addresses SET isDefault = TRUE WHERE id = ? AND userId = ?");
            $stmt->execute([$addressId, $userId]);

            echo json_encode(['success' => true, 'message' => 'Default address updated']);
            break;

        default:
            echo json_encode(['success' => false, 'message' => 'Invalid action']);
    }
} catch (Exception $e) {
    error_log("Manage address error: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'An error occurred']);
}
