<?php
// receipt.php - Display and print order receipt
include __DIR__ . '/includes/header.php';
requireLogin();

if (!isset($_GET['order_id'])) {
    header("Location: https://parlour.com/profile");
    exit;
}

$orderId = $_GET['order_id'];
$userId = $_SESSION['user_id'];

// Fetch order details
$stmt = $pdo->prepare("
    SELECT o.*, p.transactionId, p.raastId, p.status as paymentStatus, p.receiptData, p.gatewayResponse
    FROM orders o
    LEFT JOIN payments p ON o.id = p.orderId
    WHERE o.id = ? AND o.userId = ?
");
$stmt->execute([$orderId, $userId]);
$order = $stmt->fetch();

if (!$order) {
    header("Location: https://parlour.com/profile");
    exit;
}

// Fetch order items
$stmt = $pdo->prepare("
    SELECT oi.*, p.name, p.image
    FROM orderItems oi
    JOIN products p ON oi.productId = p.id
    WHERE oi.orderId = ?
");
$stmt->execute([$orderId]);
$orderItems = $stmt->fetchAll();

$shippingAddress = json_decode($order['shippingAddress'], true);
$receiptData = $order['receiptData'] ? json_decode($order['receiptData'], true) : [];
$gatewayResponse = $order['gatewayResponse'] ? json_decode($order['gatewayResponse'], true) : [];
?>

<!-- Print Styles -->
<style>
    @media print {
        header, footer, .no-print {
            display: none !important;
        }
        body {
            background: white !important;
        }
        .receipt-container {
            box-shadow: none !important;
            border: none !important;
            max-width: 100% !important;
        }
    }
    
    .receipt-container {
        background: white;
        box-shadow: 0 0 40px rgba(0, 0, 0, 0.1);
    }
</style>

<div class="max-w-4xl mx-auto px-6 lg:px-8 pt-28 lg:pt-36 pb-16 lg:pb-24">
    <!-- Action Buttons -->
    <div class="no-print flex justify-between items-center mb-8">
        <a href="https://parlour.com/profile?tab=orders" class="inline-flex items-center gap-2 text-gray-500 hover:text-gray-900 transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            <span class="text-sm font-medium">Back to Orders</span>
        </a>
        <button onclick="window.print()" class="inline-flex items-center gap-2 px-6 py-3 bg-gray-900 text-white rounded-full hover:bg-gray-800 transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
            <span class="font-medium">Print Receipt</span>
        </button>
    </div>

    <!-- Receipt -->
    <div class="receipt-container rounded-3xl border border-gray-200 p-8 lg:p-12">
        <!-- Header -->
        <div class="border-b border-gray-200 pb-8 mb-8">
            <div class="flex justify-between items-start mb-6">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">MA Essentials</h1>
                    <p class="text-sm text-gray-500">Your Premium E-Commerce Store</p>
                </div>
                <?php if ($order['paymentStatus'] === 'COMPLETED'): ?>
                    <div class="text-right">
                        <span class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-100 text-emerald-800 rounded-full text-sm font-semibold">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                            Payment Successful
                        </span>
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="grid md:grid-cols-2 gap-6">
                <div>
                    <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Receipt Number</h3>
                    <p class="text-lg font-bold text-gray-900"><?php echo htmlspecialchars($order['receiptNumber']); ?></p>
                </div>
                <div>
                    <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Order Date</h3>
                    <p class="text-lg font-medium text-gray-900"><?php echo date('F j, Y g:i A', strtotime($order['createdAt'])); ?></p>
                </div>
            </div>
        </div>

        <!-- Payment Information -->
        <?php if ($order['paymentMethod'] === 'hbl_konnect' && $order['transactionId']): ?>
            <div class="bg-green-50 border border-green-200 rounded-2xl p-6 mb-8">
                <h3 class="text-sm font-semibold text-green-900 uppercase tracking-wider mb-4">Payment Details</h3>
                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-xs text-green-700 mb-1">Payment Method</p>
                        <p class="text-sm font-bold text-green-900">HBL Konnect (Raast)</p>
                    </div>
                    <div>
                        <p class="text-xs text-green-700 mb-1">Transaction ID</p>
                        <p class="text-sm font-mono font-bold text-green-900"><?php echo htmlspecialchars($order['transactionId']); ?></p>
                    </div>
                    <?php if ($order['raastId']): ?>
                        <div>
                            <p class="text-xs text-green-700 mb-1">Raast ID</p>
                            <p class="text-sm font-mono font-bold text-green-900"><?php echo htmlspecialchars($order['raastId']); ?></p>
                        </div>
                    <?php endif; ?>
                    <?php if (!empty($gatewayResponse['authCode'])): ?>
                        <div>
                            <p class="text-xs text-green-700 mb-1">Authorization Code</p>
                            <p class="text-sm font-mono font-bold text-green-900"><?php echo htmlspecialchars($gatewayResponse['authCode']); ?></p>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="mt-4 pt-4 border-t border-green-200">
                    <div class="flex items-center gap-2 text-green-700 text-sm">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                        <span class="font-medium">Payment verified and processed securely via HBL Konnect</span>
                    </div>
                </div>
            </div>
        <?php elseif ($order['paymentMethod'] === 'cod'): ?>
            <div class="bg-amber-50 border border-amber-200 rounded-2xl p-6 mb-8">
                <h3 class="text-sm font-semibold text-amber-900 uppercase tracking-wider mb-2">Payment Method</h3>
                <p class="text-base font-bold text-amber-900">Cash on Delivery</p>
                <p class="text-sm text-amber-700 mt-2">Please keep PKR <?php echo number_format($order['total'], 0); ?> ready for payment upon delivery.</p>
            </div>
        <?php endif; ?>

        <!-- Shipping Address -->
        <div class="mb-8">
            <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-4">Ship To</h3>
            <div class="bg-gray-50 rounded-2xl p-6">
                <p class="font-semibold text-gray-900 mb-1"><?php echo htmlspecialchars($shippingAddress['name']); ?></p>
                <p class="text-sm text-gray-700"><?php echo htmlspecialchars($shippingAddress['phone']); ?></p>
                <p class="text-sm text-gray-700 mt-2">
                    <?php echo htmlspecialchars($shippingAddress['address']); ?>
                    <?php if (!empty($shippingAddress['address2'])): ?>, <?php echo htmlspecialchars($shippingAddress['address2']); ?><?php endif; ?>
                </p>
                <p class="text-sm text-gray-700">
                    <?php echo htmlspecialchars($shippingAddress['city']); ?>, 
                    <?php echo htmlspecialchars($shippingAddress['state']); ?> 
                    <?php echo htmlspecialchars($shippingAddress['zip']); ?>
                </p>
                <p class="text-sm text-gray-700"><?php echo htmlspecialchars($shippingAddress['country']); ?></p>
            </div>
        </div>

        <!-- Order Items -->
        <div class="mb-8">
            <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-4">Order Items</h3>
            <div class="border border-gray-200 rounded-2xl overflow-hidden">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Item</th>
                            <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Qty</th>
                            <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Price</th>
                            <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <?php foreach ($orderItems as $item): ?>
                            <tr>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-4">
                                        <img src="<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>" class="w-16 h-16 object-cover rounded-lg">
                                        <div>
                                            <p class="font-medium text-gray-900"><?php echo htmlspecialchars($item['name']); ?></p>
                                            <?php if ($item['size'] || $item['color']): ?>
                                                <p class="text-xs text-gray-500 mt-1">
                                                    <?php if ($item['size']): ?>Size: <?php echo htmlspecialchars($item['size']); ?><?php endif; ?>
                                                    <?php if ($item['size'] && $item['color']): ?> | <?php endif; ?>
                                                    <?php if ($item['color']): ?>Color: <?php echo htmlspecialchars($item['color']); ?><?php endif; ?>
                                                </p>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center text-gray-900"><?php echo $item['quantity']; ?></td>
                                <td class="px-6 py-4 text-right text-gray-900">PKR <?php echo number_format($item['price'], 0); ?></td>
                                <td class="px-6 py-4 text-right font-semibold text-gray-900">PKR <?php echo number_format($item['price'] * $item['quantity'], 0); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Order Summary -->
        <div class="border-t border-gray-200 pt-6">
            <div class="max-w-sm ml-auto space-y-3">
                <div class="flex justify-between text-sm text-gray-600">
                    <span>Subtotal</span>
                    <span class="font-medium text-gray-900">PKR <?php echo number_format($order['subtotal'], 0); ?></span>
                </div>
                <div class="flex justify-between text-sm text-gray-600">
                    <span>Shipping</span>
                    <span class="font-medium text-gray-900">PKR <?php echo number_format($order['shipping'], 0); ?></span>
                </div>
                <div class="flex justify-between text-sm text-gray-600">
                    <span>Tax (8%)</span>
                    <span class="font-medium text-gray-900">PKR <?php echo number_format($order['tax'], 0); ?></span>
                </div>
                <div class="flex justify-between text-lg font-bold text-gray-900 border-t border-gray-200 pt-3">
                    <span>Total Paid</span>
                    <span>PKR <?php echo number_format($order['total'], 0); ?></span>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="mt-12 pt-8 border-t border-gray-200 text-center">
            <p class="text-sm text-gray-600 mb-2">Thank you for shopping with MA Essentials!</p>
            <p class="text-xs text-gray-500">For any queries, contact us at support@parlour.com</p>
            <p class="text-xs text-gray-400 mt-4">This is a computer-generated receipt.</p>
        </div>
    </div>

    <!-- Additional Actions -->
    <div class="no-print mt-8 flex justify-center gap-4">
        <a href="https://parlour.com/profile?tab=orders" class="px-6 py-3 border border-gray-300 text-gray-700 rounded-full hover:bg-gray-50 transition font-medium">
            View All Orders
        </a>
        <a href="https://parlour.com/shop" class="px-6 py-3 bg-gray-900 text-white rounded-full hover:bg-gray-800 transition font-medium">
            Continue Shopping
        </a>
    </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
