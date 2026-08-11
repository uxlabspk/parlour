<?php
// php_web/admin/orders.php
include __DIR__ . '/../includes/header.php';
requireAdmin();

$action = $_GET['action'] ?? 'list';
$error = '';
$success = '';

// Handle status update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $orderId = $_POST['order_id'];
    $newStatus = $_POST['status'];
    
    $stmt = $pdo->prepare("UPDATE orders SET status = ?, updatedAt = NOW() WHERE id = ?");
    try {
        $stmt->execute([$newStatus, $orderId]);
        $success = 'Order status updated successfully!';
    } catch (Exception $e) {
        $error = 'Error updating order status: ' . $e->getMessage();
    }
}

// Handle order deletion
if ($action === 'delete') {
    $orderId = $_GET['id'];
    $stmt = $pdo->prepare("DELETE FROM orders WHERE id = ?");
    try {
        $stmt->execute([$orderId]);
        $success = 'Order deleted successfully!';
        $action = 'list';
    } catch (Exception $e) {
        $error = 'Error deleting order: ' . $e->getMessage();
        $action = 'list';
    }
}

// Fetch single order for view
$order = null;
$orderItems = [];
if ($action === 'view') {
    $orderId = $_GET['id'];
    
    // Fetch order with user details and payment info
    $stmt = $pdo->prepare("
        SELECT o.*, u.email, u.name as userName, 
               p.transactionId, p.raastId, p.status as paymentStatus, p.receiptData, p.gatewayResponse
        FROM orders o 
        JOIN users u ON o.userId = u.id 
        LEFT JOIN payments p ON o.id = p.orderId
        WHERE o.id = ?
    ");
    $stmt->execute([$orderId]);
    $order = $stmt->fetch();
    
    if ($order) {
        // Fetch order items with product details
        $stmt = $pdo->prepare("
            SELECT oi.*, p.name as productName, p.image 
            FROM orderItems oi 
            JOIN products p ON oi.productId = p.id 
            WHERE oi.orderId = ?
        ");
        $stmt->execute([$orderId]);
        $orderItems = $stmt->fetchAll();
    } else {
        $error = 'Order not found.';
        $action = 'list';
    }
}

// Fetch all orders with filters
if ($action === 'list') {
    $statusFilter = $_GET['status'] ?? '';
    $searchQuery = $_GET['search'] ?? '';
    
    $sql = "
        SELECT o.*, u.email, u.name as userName 
        FROM orders o 
        JOIN users u ON o.userId = u.id 
        WHERE 1=1
    ";
    $params = [];
    
    if ($statusFilter) {
        $sql .= " AND o.status = ?";
        $params[] = $statusFilter;
    }
    
    if ($searchQuery) {
        $sql .= " AND (o.id LIKE ? OR u.email LIKE ? OR u.name LIKE ?)";
        $searchParam = "%$searchQuery%";
        $params[] = $searchParam;
        $params[] = $searchParam;
        $params[] = $searchParam;
    }
    
    $sql .= " ORDER BY o.createdAt DESC";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $orders = $stmt->fetchAll();
}
?>

<div class="">
    <?php if ($action === 'list'): ?>
        <!-- Admin Header -->
        <div class="sm:pt-36 pt-24 bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900 text-white">
            <div class="max-w-7xl mx-auto px-6 py-12">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                    <div>
                        <a href="https://parlour.com/admin" class="inline-flex items-center gap-2 text-sm text-gray-400 hover:text-white transition-colors mb-3">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                            Back to Dashboard
                        </a>
                        <h1 class="text-3xl lg:text-4xl font-light">
                            Order <span class="font-semibold">Management</span>
                        </h1>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-6 -mt-6">
            <?php if ($success): ?>
                <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-5 py-4 rounded-xl mb-6 flex items-center gap-3">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <?php echo htmlspecialchars($success); ?>
                </div>
            <?php endif; ?>

            <?php if ($error): ?>
                <div class="bg-red-50 border border-red-200 text-red-700 px-5 py-4 rounded-xl mb-6 flex items-center gap-3">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <!-- Filters -->
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 mb-6">
                <form method="GET" class="flex flex-col md:flex-row gap-4">
                    <input type="hidden" name="action" value="list">
                    <div class="flex-1">
                        <input 
                            type="text" 
                            name="search" 
                            placeholder="Search by Order ID, Email, or Customer Name..." 
                            value="<?php echo htmlspecialchars($searchQuery); ?>"
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl outline-none focus:border-gray-400 focus:ring-2 focus:ring-gray-100 transition-all duration-200 text-sm"
                        >
                    </div>
                    <div>
                        <select 
                            name="status" 
                            class="w-full md:w-auto px-4 py-3 border border-gray-200 rounded-xl outline-none focus:border-gray-400 focus:ring-2 focus:ring-gray-100 transition-all duration-200 text-sm"
                        >
                            <option value="">All Statuses</option>
                            <option value="PENDING" <?php echo $statusFilter === 'PENDING' ? 'selected' : ''; ?>>Pending</option>
                            <option value="PROCESSING" <?php echo $statusFilter === 'PROCESSING' ? 'selected' : ''; ?>>Processing</option>
                            <option value="SHIPPED" <?php echo $statusFilter === 'SHIPPED' ? 'selected' : ''; ?>>Shipped</option>
                            <option value="DELIVERED" <?php echo $statusFilter === 'DELIVERED' ? 'selected' : ''; ?>>Delivered</option>
                            <option value="CANCELLED" <?php echo $statusFilter === 'CANCELLED' ? 'selected' : ''; ?>>Cancelled</option>
                        </select>
                    </div>
                    <button type="submit" class="bg-gray-900 text-white px-6 py-3 rounded-xl font-semibold text-sm hover:bg-gray-800 transition-colors duration-200">
                        Apply Filters
                    </button>
                    <a href="https://parlour.com/admin/orders?action=list" class="bg-gray-100 text-gray-700 px-6 py-3 rounded-xl font-semibold text-sm hover:bg-gray-200 transition-colors duration-200 text-center">
                        Clear
                    </a>
                </form>
            </div>

            <!-- Orders Table -->
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-100">
                    <h2 class="text-lg font-semibold text-gray-900">All Orders <span class="text-gray-400 font-normal">(<?php echo count($orders); ?>)</span></h2>
                </div>
                <?php if (empty($orders)): ?>
                    <div class="px-6 py-16 text-center">
                        <div class="w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                            </svg>
                        </div>
                        <p class="text-gray-500">No orders found.</p>
                    </div>
                <?php else: ?>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="bg-gray-50/50">
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Order ID</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Customer</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Date</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Total</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Payment</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                <?php foreach ($orders as $o): ?>
                                    <tr class="hover:bg-gray-50/50 transition-colors">
                                        <td class="px-6 py-4">
                                            <span class="font-mono text-sm text-gray-600">#<?php echo substr($o['id'], 0, 8); ?></span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm font-medium text-gray-900"><?php echo htmlspecialchars($o['userName'] ?? 'N/A'); ?></div>
                                            <div class="text-xs text-gray-500"><?php echo htmlspecialchars($o['email']); ?></div>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-500">
                                            <?php echo date('M d, Y', strtotime($o['createdAt'])); ?>
                                        </td>
                                        <td class="px-6 py-4 text-sm font-semibold text-gray-900">
                                            PKR <?php echo number_format($o['total'], 0); ?>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-600">
                                            <?php echo htmlspecialchars($o['paymentMethod']); ?>
                                        </td>
                                        <td class="px-6 py-4">
                                            <?php 
                                            $statusColors = [
                                                'PENDING' => 'bg-amber-50 text-amber-700 border-amber-200',
                                                'PROCESSING' => 'bg-blue-50 text-blue-700 border-blue-200',
                                                'SHIPPED' => 'bg-purple-50 text-purple-700 border-purple-200',
                                                'DELIVERED' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                                'CANCELLED' => 'bg-red-50 text-red-700 border-red-200'
                                            ];
                                            $colorClass = $statusColors[$o['status']] ?? 'bg-gray-50 text-gray-700 border-gray-200';
                                            ?>
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold border <?php echo $colorClass; ?>">
                                                <?php echo $o['status']; ?>
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-3">
                                                <a href="https://parlour.com/admin/orders?action=view&id=<?php echo $o['id']; ?>" 
                                                   class="text-gray-600 hover:text-gray-900 transition-colors">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                    </svg>
                                                </a>
                                                <a href="https://parlour.com/admin/orders?action=delete&id=<?php echo $o['id']; ?>" 
                                                   class="text-red-500 hover:text-red-700 transition-colors"
                                                   onclick="return confirm('Are you sure you want to delete this order?')">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                    </svg>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>

    <?php elseif ($action === 'view' && $order): ?>
        <!-- Order Details View -->
        <div class="bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900 text-white">
            <div class="max-w-7xl mx-auto px-6 py-12">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                    <div>
                        <a href="https://parlour.com/admin/orders?action=list" class="inline-flex items-center gap-2 text-sm text-gray-400 hover:text-white transition-colors mb-3">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                            Back to Orders
                        </a>
                        <h1 class="text-3xl lg:text-4xl font-light">
                            Order <span class="font-semibold">#<?php echo substr($order['id'], 0, 8); ?></span>
                        </h1>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-6 -mt-6">
            <?php if ($success): ?>
                <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-5 py-4 rounded-xl mb-6 flex items-center gap-3">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <?php echo htmlspecialchars($success); ?>
                </div>
            <?php endif; ?>

            <?php if ($error): ?>
                <div class="bg-red-50 border border-red-200 text-red-700 px-5 py-4 rounded-xl mb-6 flex items-center gap-3">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Order Information -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Order Items -->
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                        <div class="px-6 py-5 border-b border-gray-100">
                            <h2 class="text-lg font-semibold text-gray-900">Order Items</h2>
                        </div>
                        <div class="p-6 space-y-4">
                            <?php foreach ($orderItems as $item): ?>
                                <div class="flex gap-4 p-4 bg-gray-50 rounded-xl">
                                    <img src="<?php echo htmlspecialchars($item['image']); ?>" 
                                         alt="<?php echo htmlspecialchars($item['productName']); ?>"
                                         class="w-20 h-20 object-cover rounded-lg">
                                    <div class="flex-1 min-w-0">
                                        <h3 class="font-semibold text-gray-900 mb-1"><?php echo htmlspecialchars($item['productName']); ?></h3>
                                        <div class="text-sm text-gray-500 space-x-3">
                                            <span>Qty: <?php echo $item['quantity']; ?></span>
                                            <?php if ($item['size']): ?>
                                                <span>Size: <?php echo htmlspecialchars($item['size']); ?></span>
                                            <?php endif; ?>
                                            <?php if ($item['color']): ?>
                                                <span>Color: <?php echo htmlspecialchars($item['color']); ?></span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <div class="font-semibold text-gray-900">PKR <?php echo number_format($item['price'], 0); ?></div>
                                        <div class="text-xs text-gray-500">each</div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Addresses -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Shipping Address -->
                        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                            <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-3">
                                <div class="w-8 h-8 bg-blue-50 rounded-lg flex items-center justify-center">
                                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                </div>
                                <h2 class="font-semibold text-gray-900">Shipping Address</h2>
                            </div>
                            <div class="p-6">
                                <?php $shippingAddress = json_decode($order['shippingAddress'], true); ?>
                                <div class="text-sm space-y-1 text-gray-600">
                                    <p class="font-semibold text-gray-900"><?php echo htmlspecialchars($shippingAddress['fullName'] ?? 'N/A'); ?></p>
                                    <p><?php echo htmlspecialchars($shippingAddress['addressLine1'] ?? ''); ?></p>
                                    <?php if (!empty($shippingAddress['addressLine2'])): ?>
                                        <p><?php echo htmlspecialchars($shippingAddress['addressLine2']); ?></p>
                                    <?php endif; ?>
                                    <p><?php echo htmlspecialchars($shippingAddress['city'] ?? ''); ?>, <?php echo htmlspecialchars($shippingAddress['state'] ?? ''); ?> <?php echo htmlspecialchars($shippingAddress['postalCode'] ?? ''); ?></p>
                                    <p><?php echo htmlspecialchars($shippingAddress['country'] ?? ''); ?></p>
                                    <p class="pt-2">Phone: <?php echo htmlspecialchars($shippingAddress['phone'] ?? 'N/A'); ?></p>
                                </div>
                            </div>
                        </div>

                        <!-- Billing Address -->
                        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                            <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-3">
                                <div class="w-8 h-8 bg-purple-50 rounded-lg flex items-center justify-center">
                                    <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                    </svg>
                                </div>
                                <h2 class="font-semibold text-gray-900">Billing Address</h2>
                            </div>
                            <div class="p-6">
                                <?php $billingAddress = json_decode($order['billingAddress'], true); ?>
                                <div class="text-sm space-y-1 text-gray-600">
                                    <p class="font-semibold text-gray-900"><?php echo htmlspecialchars($billingAddress['fullName'] ?? 'N/A'); ?></p>
                                    <p><?php echo htmlspecialchars($billingAddress['addressLine1'] ?? ''); ?></p>
                                    <?php if (!empty($billingAddress['addressLine2'])): ?>
                                        <p><?php echo htmlspecialchars($billingAddress['addressLine2']); ?></p>
                                    <?php endif; ?>
                                    <p><?php echo htmlspecialchars($billingAddress['city'] ?? ''); ?>, <?php echo htmlspecialchars($billingAddress['state'] ?? ''); ?> <?php echo htmlspecialchars($billingAddress['postalCode'] ?? ''); ?></p>
                                    <p><?php echo htmlspecialchars($billingAddress['country'] ?? ''); ?></p>
                                    <p class="pt-2">Phone: <?php echo htmlspecialchars($billingAddress['phone'] ?? 'N/A'); ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Order Summary Sidebar -->
                <div class="space-y-6">
                    <!-- Status Update -->
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-100">
                            <h2 class="font-semibold text-gray-900">Update Status</h2>
                        </div>
                        <div class="p-6">
                            <form method="POST">
                                <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                                <select name="status" class="w-full px-4 py-3 border border-gray-200 rounded-xl outline-none focus:border-gray-400 focus:ring-2 focus:ring-gray-100 transition-all duration-200 text-sm mb-4">
                                    <option value="PENDING" <?php echo $order['status'] === 'PENDING' ? 'selected' : ''; ?>>Pending</option>
                                    <option value="PROCESSING" <?php echo $order['status'] === 'PROCESSING' ? 'selected' : ''; ?>>Processing</option>
                                    <option value="SHIPPED" <?php echo $order['status'] === 'SHIPPED' ? 'selected' : ''; ?>>Shipped</option>
                                    <option value="DELIVERED" <?php echo $order['status'] === 'DELIVERED' ? 'selected' : ''; ?>>Delivered</option>
                                    <option value="CANCELLED" <?php echo $order['status'] === 'CANCELLED' ? 'selected' : ''; ?>>Cancelled</option>
                                </select>
                                <button type="submit" name="update_status" class="w-full bg-gray-900 text-white px-6 py-3 rounded-xl font-semibold text-sm hover:bg-gray-800 transition-colors duration-200">
                                    Update Status
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Order Summary -->
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-100">
                            <h2 class="font-semibold text-gray-900">Order Summary</h2>
                        </div>
                        <div class="p-6 space-y-4">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500">Order ID</span>
                                <span class="font-mono text-xs text-gray-900">#<?php echo substr($order['id'], 0, 12); ?></span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500">Customer</span>
                                <span class="font-medium text-gray-900"><?php echo htmlspecialchars($order['userName'] ?? 'N/A'); ?></span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500">Email</span>
                                <span class="font-medium text-gray-900 text-xs"><?php echo htmlspecialchars($order['email']); ?></span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500">Order Date</span>
                                <span class="font-medium text-gray-900"><?php echo date('M d, Y', strtotime($order['createdAt'])); ?></span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500">Payment Method</span>
                                <span class="font-medium text-gray-900"><?php echo htmlspecialchars($order['paymentMethod']); ?></span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500">Payment Status</span>
                                <?php 
                                $paymentStatusColors = [
                                    'PENDING' => 'text-amber-700',
                                    'COMPLETED' => 'text-emerald-700',
                                    'FAILED' => 'text-red-700',
                                    'REFUNDED' => 'text-purple-700'
                                ];
                                $paymentColor = $paymentStatusColors[$order['paymentStatus']] ?? 'text-gray-700';
                                ?>
                                <span class="font-semibold <?php echo $paymentColor; ?>"><?php echo htmlspecialchars($order['paymentStatus']); ?></span>
                            </div>
                            <?php if ($order['transactionId']): ?>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500">Transaction ID</span>
                                <span class="font-mono text-xs text-gray-900"><?php echo htmlspecialchars($order['transactionId']); ?></span>
                            </div>
                            <?php endif; ?>
                            <?php if ($order['receiptNumber']): ?>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500">Receipt #</span>
                                <span class="font-mono text-xs text-gray-900"><?php echo htmlspecialchars($order['receiptNumber']); ?></span>
                            </div>
                            <?php endif; ?>
                            <div class="border-t border-gray-100 pt-4 space-y-2">
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-500">Subtotal</span>
                                    <span>PKR <?php echo number_format($order['subtotal'], 0); ?></span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-500">Shipping</span>
                                    <span>PKR <?php echo number_format($order['shipping'], 0); ?></span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-500">Tax</span>
                                    <span>PKR <?php echo number_format($order['tax'], 0); ?></span>
                                </div>
                                <div class="flex justify-between text-lg font-semibold pt-3 border-t border-gray-100">
                                    <span>Total</span>
                                    <span>PKR <?php echo number_format($order['total'], 0); ?></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Information -->
                    <?php if ($order['transactionId'] && $order['paymentMethod'] === 'hbl_konnect'): ?>
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-3">
                            <div class="w-8 h-8 bg-green-50 rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                            </div>
                            <h2 class="font-semibold text-gray-900">HBL Konnect Payment</h2>
                        </div>
                        <div class="p-6 space-y-3">
                            <?php 
                            $gatewayResponse = $order['gatewayResponse'] ? json_decode($order['gatewayResponse'], true) : [];
                            ?>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500">Transaction ID</span>
                                <span class="font-mono text-xs text-gray-900"><?php echo htmlspecialchars($order['transactionId']); ?></span>
                            </div>
                            <?php if ($order['raastId']): ?>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500">Raast ID</span>
                                <span class="font-mono text-xs text-gray-900"><?php echo htmlspecialchars($order['raastId']); ?></span>
                            </div>
                            <?php endif; ?>
                            <?php if (!empty($gatewayResponse['authCode'])): ?>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500">Auth Code</span>
                                <span class="font-mono text-xs text-gray-900"><?php echo htmlspecialchars($gatewayResponse['authCode']); ?></span>
                            </div>
                            <?php endif; ?>
                            <?php if (!empty($gatewayResponse['rrn'])): ?>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500">RRN</span>
                                <span class="font-mono text-xs text-gray-900"><?php echo htmlspecialchars($gatewayResponse['rrn']); ?></span>
                            </div>
                            <?php endif; ?>
                            <div class="pt-3 border-t border-gray-100">
                                <div class="flex items-center gap-2 text-emerald-600 text-sm">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                    <span class="font-medium">Payment Verified</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Quick Actions -->
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-100">
                            <h2 class="font-semibold text-gray-900">Quick Actions</h2>
                        </div>
                        <div class="p-6">
                            <a href="https://parlour.com/admin/orders?action=delete&id=<?php echo $order['id']; ?>" 
                               class="block w-full text-center bg-red-50 text-red-600 px-6 py-3 rounded-xl font-semibold text-sm hover:bg-red-100 transition-colors duration-200 border border-red-200"
                               onclick="return confirm('Are you sure you want to delete this order? This action cannot be undone.')">
                                Delete Order
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <br>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
