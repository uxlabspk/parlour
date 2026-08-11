<?php
// php_web/api/cart.php
require_once __DIR__ . '/../includes/db.php';

$action = $_POST['action'] ?? $_GET['action'] ?? '';
$isAjax = !empty($_POST['ajax']) || (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest');

if ($action === 'add') {
    $product_id = $_POST['product_id'] ?? '';
    $size = $_POST['size'] ?? 'M';
    $color = $_POST['color'] ?? '';
    $quantity = (int)($_POST['quantity'] ?? 1);

    if ($product_id) {
        // Fetch product info to store in session
        $stmt = $pdo->prepare("SELECT id, name, price, discountedPrice, image, shippingPricing FROM products WHERE id = ?");
        $stmt->execute([$product_id]);
        $product = $stmt->fetch();

        if ($product) {
            $cart_id = $product_id . '_' . $size . '_' . $color;
            $displayPrice = $product['discountedPrice'] ?? $product['price'];
            $shippingCost = $product['shippingPricing'] ?? 0;
            
            if (isset($_SESSION['cart'][$cart_id])) {
                $_SESSION['cart'][$cart_id]['quantity'] += $quantity;
            } else {
                $_SESSION['cart'][$cart_id] = [
                    'id' => $product['id'],
                    'name' => $product['name'],
                    'price' => $displayPrice,
                    'originalPrice' => $product['price'],
                    'image' => $product['image'],
                    'size' => $size,
                    'color' => $color,
                    'quantity' => $quantity,
                    'shippingPricing' => $shippingCost
                ];
            }
            
            // Calculate cart count
            $cartCount = 0;
            foreach ($_SESSION['cart'] as $item) {
                $cartCount += $item['quantity'];
            }
            
            // Return JSON for AJAX requests
            if ($isAjax) {
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => true,
                    'message' => 'Product added to cart!',
                    'cartCount' => $cartCount,
                    'productName' => $product['name']
                ]);
                exit;
            }
        } else {
            if ($isAjax) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'Product not found']);
                exit;
            }
        }
    }
    header("Location: ../cart");
    exit;
}

if ($action === 'update_quantity') {
    $cart_id = $_POST['cart_id'] ?? '';
    $quantity = (int)($_POST['quantity'] ?? 1);
    
    if ($cart_id && isset($_SESSION['cart'][$cart_id]) && $quantity > 0) {
        $_SESSION['cart'][$cart_id]['quantity'] = $quantity;
        
        // Calculate totals for AJAX response
        $subtotal = 0;
        $shipping = 0;
        foreach ($_SESSION['cart'] as $item) {
            $subtotal += $item['price'] * $item['quantity'];
            if (isset($item['shippingPricing']) && $item['shippingPricing'] > 0) {
                $shipping += $item['shippingPricing'] * $item['quantity'];
            }
        }
        if ($subtotal > 0 && $shipping == 0) {
            $shipping = 15.00;
        }
        $tax = $subtotal * 0.08;
        $total = $subtotal + $shipping + $tax;
        
        if ($isAjax) {
            header('Content-Type: application/json');
            echo json_encode([
                'success' => true,
                'itemTotal' => number_format($_SESSION['cart'][$cart_id]['price'] * $quantity, 2),
                'subtotal' => number_format($subtotal, 2),
                'shipping' => number_format($shipping, 2),
                'tax' => number_format($tax, 2),
                'total' => number_format($total, 2)
            ]);
            exit;
        }
    }
    header("Location: ../cart");
    exit;
}

if ($action === 'remove') {
    $cart_id = $_GET['cart_id'] ?? '';
    if ($cart_id && isset($_SESSION['cart'][$cart_id])) {
        unset($_SESSION['cart'][$cart_id]);
    }
    header("Location: ../cart");
    exit;
}

if ($action === 'clear') {
    unset($_SESSION['cart']);
    header("Location: ../cart");
    exit;
}

header("Location: ../");
?>
