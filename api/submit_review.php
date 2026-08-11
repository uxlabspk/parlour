<?php
// api/submit_review.php
include __DIR__ . '/../includes/db.php';

header('Content-Type: application/json');

// Check if user is logged in
if (!isLoggedIn()) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'You must be logged in to submit a review']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

try {
    $productId = $_POST['product_id'] ?? null;
    $rating = $_POST['rating'] ?? null;
    $comment = $_POST['comment'] ?? '';
    $userId = $_SESSION['user_id'];

    // Validate input
    if (!$productId || !$rating) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Product ID and rating are required']);
        exit;
    }

    // Validate rating range
    if ($rating < 1 || $rating > 5) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Rating must be between 1 and 5']);
        exit;
    }

    // Check if product exists
    $stmt = $pdo->prepare("SELECT id FROM products WHERE id = ?");
    $stmt->execute([$productId]);
    if (!$stmt->fetch()) {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Product not found']);
        exit;
    }

    // Check if user already reviewed this product
    $stmt = $pdo->prepare("SELECT id FROM reviews WHERE productId = ? AND userId = ?");
    $stmt->execute([$productId, $userId]);
    if ($stmt->fetch()) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'You have already reviewed this product']);
        exit;
    }

    // Insert review
    $reviewId = generateUUID();
    $stmt = $pdo->prepare("
        INSERT INTO reviews (id, productId, userId, rating, comment, approved, createdAt)
        VALUES (?, ?, ?, ?, ?, FALSE, NOW())
    ");
    $stmt->execute([$reviewId, $productId, $userId, $rating, $comment]);

    echo json_encode([
        'success' => true,
        'message' => 'Thank you for your review! It will be visible once approved by our team.'
    ]);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'An error occurred while submitting your review']);
}
?>
