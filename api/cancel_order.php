<?php
// api/cancel_order.php
include __DIR__ . '/../includes/db.php';

header('Content-Type: application/json');

// Check if user is logged in
if (!isLoggedIn()) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'You must be logged in to cancel an order']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

try {
    $orderId = $_POST['order_id'] ?? null;
    $userId = $_SESSION['user_id'];

    if (!$orderId) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Order ID is required']);
        exit;
    }

    // Get the order and verify it belongs to the user
    $stmt = $pdo->prepare("SELECT * FROM orders WHERE id = ? AND userId = ?");
    $stmt->execute([$orderId, $userId]);
    $order = $stmt->fetch();

    if (!$order) {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Order not found']);
        exit;
    }

    // Check if order status allows cancellation
    if (!in_array($order['status'], ['PENDING', 'PROCESSING'])) {
        http_response_code(400);
        echo json_encode([
            'success' => false, 
            'message' => 'This order cannot be cancelled. Only pending or processing orders can be cancelled.'
        ]);
        exit;
    }

    // Check if order is within 24 hours
    $orderTime = strtotime($order['createdAt']);
    $currentTime = time();
    $hoursSinceOrder = ($currentTime - $orderTime) / 3600;

    if ($hoursSinceOrder >= 24) {
        http_response_code(400);
        echo json_encode([
            'success' => false, 
            'message' => 'Cancellation period has expired. Orders can only be cancelled within 24 hours of placement.'
        ]);
        exit;
    }

    // Update order status to CANCELLED
    $stmt = $pdo->prepare("UPDATE orders SET status = 'CANCELLED', updatedAt = NOW() WHERE id = ?");
    $stmt->execute([$orderId]);

    echo json_encode([
        'success' => true,
        'message' => 'Order cancelled successfully. A refund will be processed within 5-7 business days.'
    ]);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'An error occurred while cancelling the order']);
}
?>
