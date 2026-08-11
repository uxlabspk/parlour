<?php
// php_web/api/place_order.php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/hbl_payment.php';
requireLogin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../cart");
    exit;
}

$cart = $_SESSION['cart'] ?? [];
if (empty($cart)) {
    header("Location: ../cart");
    exit;
}

$userId = $_SESSION['user_id'];
$userEmail = $_SESSION['email'] ?? '';
$userName = $_SESSION['name'] ?? '';

$shippingAddress = [
    'name' => $_POST['shipping_name'],
    'phone' => $_POST['shipping_phone'],
    'address' => $_POST['shipping_address'],
    'address2' => $_POST['shipping_address2'] ?? '',
    'city' => $_POST['shipping_city'],
    'state' => $_POST['shipping_state'],
    'zip' => $_POST['shipping_zip'],
    'country' => $_POST['shipping_country']
];
$billingAddress = $shippingAddress; // Simplification
$paymentMethod = $_POST['payment_method'];
$saveAddress = isset($_POST['save_address']) && $_POST['save_address'] === '1';

$subtotal = 0;
$shipping = 0;
foreach ($cart as $item) {
    $subtotal += $item['price'] * $item['quantity'];
    // Add individual product shipping cost if set
    if (isset($item['shippingPricing']) && $item['shippingPricing'] > 0) {
        $shipping += $item['shippingPricing'] * $item['quantity'];
    }
}
// If no products have shipping pricing, use default flat rate
if ($shipping == 0) {
    $shipping = 15.00;
}
$tax = $subtotal * 0.08;
$total = $subtotal + $shipping + $tax;

try {
    $pdo->beginTransaction();

    $orderId = generateUUID();
    $receiptNumber = 'RCP' . date('Ymd') . rand(10000, 99999);
    $paymentStatus = 'PENDING';
    $transactionId = null;
    $transactionData = null;
    $paymentResponse = null;
    
    // Process payment based on method
    if ($paymentMethod === 'hbl_konnect') {
        // Process HBL Konnect / Raast payment
        $paymentResponse = processHBLPayment([
            'orderId' => $orderId,
            'total' => $total,
            'customerName' => $shippingAddress['name'],
            'customerPhone' => $shippingAddress['phone'],
            'customerEmail' => $userEmail,
            'returnUrl' => 'https://yourdomain.com/order-success'
        ]);
        
        if ($paymentResponse['success']) {
            $paymentStatus = 'COMPLETED';
            $transactionId = $paymentResponse['transactionId'];
            $receiptNumber = $paymentResponse['receiptNumber'];
            $transactionData = json_encode($paymentResponse);
        } else {
            $paymentStatus = 'FAILED';
            $transactionData = json_encode($paymentResponse);
            // Don't create order if payment fails
            $pdo->rollBack();
            $_SESSION['payment_error'] = $paymentResponse['error'] ?? 'Payment failed';
            header("Location: ../checkout?payment_failed=1");
            exit;
        }
    } elseif ($paymentMethod === 'credit_card') {
        // Placeholder for credit card processing (Stripe, etc.)
        $paymentStatus = 'PENDING';
        $transactionId = 'CARD_' . strtoupper(uniqid());
        $receiptNumber = 'RCP' . date('Ymd') . rand(10000, 99999);
        // TODO: Integrate with actual payment gateway
    } elseif ($paymentMethod === 'cod') {
        // Cash on Delivery - payment pending until delivery
        $paymentStatus = 'PENDING';
        $receiptNumber = 'RCP' . date('Ymd') . rand(10000, 99999);
    } else {
        $paymentStatus = 'PENDING';
        $receiptNumber = 'RCP' . date('Ymd') . rand(10000, 99999);
    }
    
    // Create order
    $stmt = $pdo->prepare("INSERT INTO orders (id, userId, subtotal, shipping, tax, total, shippingAddress, billingAddress, paymentMethod, paymentStatus, transactionId, transactionData, receiptNumber) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([
        $orderId,
        $userId,
        $subtotal,
        $shipping,
        $tax,
        $total,
        json_encode($shippingAddress),
        json_encode($billingAddress),
        $paymentMethod,
        $paymentStatus,
        $transactionId,
        $transactionData,
        $receiptNumber
    ]);

    // Create order items
    $stmtItem = $pdo->prepare("INSERT INTO orderItems (id, orderId, productId, quantity, price, size, color) VALUES (?, ?, ?, ?, ?, ?, ?)");
    foreach ($cart as $item) {
        $stmtItem->execute([
            generateUUID(),
            $orderId,
            $item['id'],
            $item['quantity'],
            $item['price'],
            $item['size'],
            $item['color']
        ]);
    }

    // Create payment record if HBL Konnect payment was successful
    if ($paymentMethod === 'hbl_konnect' && $paymentResponse && $paymentResponse['success']) {
        $paymentId = generateUUID();
        $stmtPayment = $pdo->prepare("INSERT INTO payments (id, orderId, userId, paymentMethod, amount, transactionId, raastId, status, gatewayResponse, receiptData, createdAt) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");
        $receiptData = [
            'receiptNumber' => $receiptNumber,
            'orderDate' => date('Y-m-d H:i:s'),
            'customerName' => $shippingAddress['name'],
            'paymentMethod' => 'HBL Konnect (Raast)',
            'transactionId' => $transactionId
        ];
        $stmtPayment->execute([
            $paymentId,
            $orderId,
            $userId,
            'hbl_konnect',
            $total,
            $transactionId,
            $paymentResponse['raastId'] ?? null,
            'COMPLETED',
            json_encode($paymentResponse['gatewayResponse'] ?? []),
            json_encode($receiptData)
        ]);
    }

    // Save address if requested and user doesn't have this exact address
    if ($saveAddress) {
        $checkStmt = $pdo->prepare("SELECT id FROM addresses WHERE userId = ? AND addressLine1 = ? AND city = ? AND postalCode = ? LIMIT 1");
        $checkStmt->execute([$userId, $shippingAddress['address'], $shippingAddress['city'], $shippingAddress['zip']]);
        
        if (!$checkStmt->fetch()) {
            // Check if user has any addresses
            $countStmt = $pdo->prepare("SELECT COUNT(*) FROM addresses WHERE userId = ?");
            $countStmt->execute([$userId]);
            $hasAddresses = $countStmt->fetchColumn() > 0;
            
            // If this is their first address, make it default
            $isDefault = !$hasAddresses;
            
            $addressId = generateUUID();
            $addressStmt = $pdo->prepare("INSERT INTO addresses (id, userId, type, fullName, phone, addressLine1, addressLine2, city, state, postalCode, country, isDefault) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $addressStmt->execute([
                $addressId,
                $userId,
                'SHIPPING',
                $shippingAddress['name'],
                $shippingAddress['phone'],
                $shippingAddress['address'],
                $shippingAddress['address2'],
                $shippingAddress['city'],
                $shippingAddress['state'],
                $shippingAddress['zip'],
                $shippingAddress['country'],
                $isDefault
            ]);
        }
    }

    $pdo->commit();
    
    // Clear cart
    unset($_SESSION['cart']);
    
    // Redirect to receipt page if payment was successful
    if ($paymentStatus === 'COMPLETED') {
        header("Location: ../receipt?order_id=" . $orderId);
    } else {
        header("Location: ../profile?order_success=1&order_id=" . $orderId);
    }
    exit;
} catch (Exception $e) {
    $pdo->rollBack();
    die("Error placing order: " . $e->getMessage());
}
?>
