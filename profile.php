<?php
// php_web/profile.php
include __DIR__ . '/includes/header.php';
requireLogin();

$userId = $_SESSION['user_id'];

// Get user data
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$userId]);
$user = $stmt->fetch();

// Set default tab based on user role
$currentTab = $_GET['tab'] ?? ($user['role'] === 'ADMIN' ? 'settings' : 'orders');

// Get orders
$stmt = $pdo->prepare("SELECT * FROM orders WHERE userId = ? ORDER BY createdAt DESC");
$stmt->execute([$userId]);
$orders = $stmt->fetchAll();

// Get addresses
$stmt = $pdo->prepare("SELECT * FROM addresses WHERE userId = ? ORDER BY isDefault DESC, createdAt DESC");
$stmt->execute([$userId]);
$addresses = $stmt->fetchAll();

$successMessage = isset($_GET['order_success']) ? 'Order placed successfully! Thank you for shopping with Parlour.' : '';
$successMessage = isset($_GET['success']) ? $_GET['message'] ?? 'Operation completed successfully!' : $successMessage;
?>

<!-- Header Section -->
<section class="pt-32 pb-8 lg:pt-40 lg:pb-12 bg-rose-50">
    <div class="max-w-7xl mx-auto px-6 lg:px-8">
        <div class="flex items-center gap-6">
            <div class="w-20 h-20 bg-gray-900 text-white rounded-2xl flex items-center justify-center font-semibold text-2xl overflow-hidden shadow-lg">
                <?php if ($user['profileImage']): ?>
                    <img src="<?php echo htmlspecialchars($user['profileImage']); ?>?t=<?php echo time(); ?>" class="w-full h-full object-cover">
                <?php else: ?>
                    <?php echo strtoupper(substr($user['name'] ?? $user['email'], 0, 1)); ?>
                <?php endif; ?>
            </div>
            <div>
                <h1 class="text-3xl lg:text-4xl font-light text-gray-900 tracking-tight">
                    Welcome, <span class="font-semibold"><?php echo htmlspecialchars($_SESSION['name'] ?? 'User'); ?></span>
                </h1>
                <p class="text-gray-500 mt-1"><?php echo htmlspecialchars($user['email']); ?></p>
            </div>
        </div>
    </div>
</section>

<?php if ($successMessage): ?>
<div class="max-w-7xl mx-auto px-6 lg:px-8 py-6">
    <div class="bg-emerald-50 text-emerald-700 p-5 rounded-2xl border border-emerald-100 flex items-center gap-4">
        <div class="w-10 h-10 bg-emerald-100 rounded-xl flex items-center justify-center flex-shrink-0">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
        </div>
        <p class="font-medium"><?php echo $successMessage; ?></p>
    </div>
</div>
<?php endif; ?>

<!-- Main Content -->
<section class="py-8 lg:py-12 bg-rose-50">
    <div class="max-w-7xl mx-auto px-6 lg:px-8">
        <div class="grid lg:grid-cols-4 gap-8">
            <!-- Sidebar -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-3xl border border-gray-100 p-4 sticky top-32">
                    <nav class="space-y-1">
                        <?php if ($user['role'] === 'ADMIN'): ?>
                            <a href="http://localhost:8080/profile.php?tab=settings" class="flex items-center gap-3 px-4 py-3 rounded-xl <?php echo $currentTab === 'settings' ? 'bg-gray-900 text-white' : 'text-gray-600 hover:bg-gray-50'; ?> transition-all duration-200 font-medium">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                Account Settings
                            </a>
                            <a href="http://localhost:8080/admin/index.php" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-600 hover:bg-gray-50 transition-all duration-200 font-medium">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                </svg>
                                Admin Panel
                            </a>
                        <?php else: ?>
                            <a href="http://localhost:8080/profile.php?tab=orders" class="flex items-center gap-3 px-4 py-3 rounded-xl <?php echo $currentTab === 'orders' ? 'bg-gray-900 text-white' : 'text-gray-600 hover:bg-gray-50'; ?> transition-all duration-200 font-medium">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                                </svg>
                                Orders
                            </a>
                            <a href="http://localhost:8080/profile.php?tab=addresses" class="flex items-center gap-3 px-4 py-3 rounded-xl <?php echo $currentTab === 'addresses' ? 'bg-gray-900 text-white' : 'text-gray-600 hover:bg-gray-50'; ?> transition-all duration-200 font-medium">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                Addresses
                            </a>
                            <a href="http://localhost:8080/profile.php?tab=settings" class="flex items-center gap-3 px-4 py-3 rounded-xl <?php echo $currentTab === 'settings' ? 'bg-gray-900 text-white' : 'text-gray-600 hover:bg-gray-50'; ?> transition-all duration-200 font-medium">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                Settings
                            </a>
                        <?php endif; ?>
                        
                        <div class="pt-2 mt-2 border-t border-gray-100">
                            <a href="http://localhost:8080/auth/logout.php" class="flex items-center gap-3 px-4 py-3 rounded-xl text-red-600 hover:bg-red-50 transition-all duration-200 font-medium">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                </svg>
                                Logout
                            </a>
                        </div>
                    </nav>
                </div>
            </div>

            <!-- Main Content -->
            <div class="lg:col-span-3">
                
                <?php if ($currentTab === 'orders' && $user['role'] !== 'ADMIN'): ?>
                    <!-- ORDERS TAB -->
                    <div class="flex items-center justify-between mb-8">
                        <h2 class="text-2xl font-light text-gray-900">
                            Order <span class="font-semibold">History</span>
                        </h2>
                        <span class="text-sm text-gray-500"><?php echo count($orders); ?> orders</span>
                    </div>

                    <?php if (empty($orders)): ?>
                        <div class="text-center py-20 bg-white rounded-3xl border border-gray-100">
                            <div class="w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-6">
                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                                </svg>
                            </div>
                            <p class="text-gray-500 mb-4">You haven't placed any orders yet.</p>
                            <a href="http://localhost:8080/shop.php" class="inline-flex items-center gap-2 text-gray-900 font-semibold hover:gap-3 transition-all">
                                Start Shopping
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                                </svg>
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="space-y-6">
                            <?php foreach ($orders as $order): ?>
                                <div class="bg-white border border-gray-100 rounded-3xl overflow-hidden hover:shadow-lg transition-shadow duration-300">
                                    <!-- Order Header -->
                                    <div class="px-6 lg:px-8 py-6 border-b border-gray-50 bg-gray-50/50">
                                        <div class="flex flex-wrap gap-6 justify-between items-center">
                                            <div>
                                                <p class="text-xs text-gray-400 uppercase tracking-wider mb-1">Order ID</p>
                                                <p class="font-mono text-sm text-gray-900"><?php echo $order['id']; ?></p>
                                            </div>
                                            <div>
                                                <p class="text-xs text-gray-400 uppercase tracking-wider mb-1">Date</p>
                                                <p class="text-gray-900 text-sm"><?php echo date('M d, Y', strtotime($order['createdAt'])); ?></p>
                                            </div>
                                            <div>
                                                <p class="text-xs text-gray-400 uppercase tracking-wider mb-1">Status</p>
                                                <?php
                                                $statusColors = [
                                                    'PENDING' => 'bg-amber-50 text-amber-600 border-amber-100',
                                                    'PROCESSING' => 'bg-blue-50 text-blue-600 border-blue-100',
                                                    'SHIPPED' => 'bg-purple-50 text-purple-600 border-purple-100',
                                                    'DELIVERED' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
                                                    'CANCELLED' => 'bg-red-50 text-red-600 border-red-100'
                                                ];
                                                $statusClass = $statusColors[$order['status']] ?? 'bg-gray-50 text-gray-600 border-gray-100';
                                                ?>
                                                <span class="inline-block px-3 py-1 rounded-lg text-xs font-semibold border <?php echo $statusClass; ?>">
                                                    <?php echo $order['status']; ?>
                                                </span>
                                            </div>
                                            <div class="text-right">
                                                <p class="text-xs text-gray-400 uppercase tracking-wider mb-1">Total</p>
                                                <p class="text-xl font-semibold text-gray-900">PKR <?php echo number_format($order['total'], 2); ?></p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="px-6 lg:px-8 py-6">
                                        <?php
                                        $orderTime = strtotime($order['createdAt']);
                                        $currentTime = time();
                                        $hoursSinceOrder = ($currentTime - $orderTime) / 3600;
                                        $canCancel = $hoursSinceOrder < 24 && in_array($order['status'], ['PENDING', 'PROCESSING']);
                                        ?>

                                        <?php if ($canCancel): ?>
                                            <div class="mb-6 flex flex-wrap gap-4 justify-between items-center p-4 bg-amber-50 border border-amber-100 rounded-2xl">
                                                <div class="flex items-center gap-3">
                                                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                    <span class="text-sm text-amber-800">
                                                        <strong><?php echo number_format(24 - $hoursSinceOrder, 1); ?> hours</strong> left to cancel
                                                    </span>
                                                </div>
                                                <button onclick="cancelOrder('<?php echo $order['id']; ?>')" class="bg-red-600 hover:bg-red-700 text-white px-5 py-2 rounded-full font-semibold text-sm transition-colors">
                                                    Cancel Order
                                                </button>
                                            </div>
                                        <?php endif; ?>

                                        <!-- Order Items -->
                                        <?php
                                        $stmtItems = $pdo->prepare("SELECT oi.*, p.name, p.image FROM orderItems oi JOIN products p ON oi.productId = p.id WHERE oi.orderId = ?");
                                        $stmtItems->execute([$order['id']]);
                                        $items = $stmtItems->fetchAll();
                                        ?>
                                        <div class="flex flex-wrap gap-3">
                                            <?php foreach ($items as $item): ?>
                                                <div class="flex items-center gap-4 bg-gray-50 pr-5 rounded-2xl overflow-hidden">
                                                    <img src="<?php echo htmlspecialchars($item['image']); ?>" class="w-16 h-20 object-cover">
                                                    <div>
                                                        <p class="text-sm font-semibold text-gray-900"><?php echo htmlspecialchars($item['name']); ?></p>
                                                        <p class="text-xs text-gray-500 mt-0.5">Size: <?php echo $item['size']; ?> · Qty: <?php echo $item['quantity']; ?></p>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>

                                        <!-- Payment Details -->
                                        <div class="mt-6 pt-6 border-t border-gray-100 grid md:grid-cols-2 gap-6">
                                            <div>
                                                <p class="text-xs text-gray-400 uppercase tracking-wider mb-2">Payment Method</p>
                                                <p class="text-gray-900 font-medium"><?php echo htmlspecialchars($order['paymentMethod']); ?></p>
                                            </div>
                                            <div class="text-sm text-gray-600 space-y-1">
                                                <div class="flex justify-between"><span>Subtotal</span><span class="text-gray-900">PKR <?php echo number_format($order['subtotal'], 2); ?></span></div>
                                                <div class="flex justify-between"><span>Shipping</span><span class="text-gray-900">PKR <?php echo number_format($order['shipping'], 2); ?></span></div>
                                                <div class="flex justify-between"><span>Tax</span><span class="text-gray-900">PKR <?php echo number_format($order['tax'], 2); ?></span></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                <?php elseif ($currentTab === 'addresses' && $user['role'] !== 'ADMIN'): ?>
                    <!-- ADDRESSES TAB -->
                    <div class="flex items-center justify-between mb-8">
                        <h2 class="text-2xl font-light text-gray-900">
                            My <span class="font-semibold">Addresses</span>
                        </h2>
                        <button onclick="openAddressModal()" class="bg-gray-900 text-white px-6 py-3 rounded-full font-semibold hover:bg-gray-800 transition-colors flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Add Address
                        </button>
                    </div>

                    <?php if (empty($addresses)): ?>
                        <div class="text-center py-20 bg-white rounded-3xl border border-gray-100">
                            <div class="w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-6">
                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                </svg>
                            </div>
                            <p class="text-gray-500 mb-4">You haven't added any addresses yet.</p>
                            <button onclick="openAddressModal()" class="inline-flex items-center gap-2 text-gray-900 font-semibold hover:gap-3 transition-all">
                                Add Your First Address
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                                </svg>
                            </button>
                        </div>
                    <?php else: ?>
                        <div class="grid md:grid-cols-2 gap-6">
                            <?php foreach ($addresses as $address): ?>
                                <div class="bg-white border rounded-3xl p-6 relative <?php echo $address['isDefault'] ? 'border-gray-900' : 'border-gray-100'; ?> hover:shadow-lg transition-shadow duration-300">
                                    <?php if ($address['isDefault']): ?>
                                        <span class="absolute top-4 right-4 bg-gray-900 text-white text-xs px-3 py-1 rounded-full font-semibold">DEFAULT</span>
                                    <?php endif; ?>
                                    <div class="mb-4">
                                        <span class="inline-block text-xs text-gray-400 uppercase tracking-wider font-semibold mb-1"><?php echo $address['type']; ?></span>
                                        <h3 class="text-lg font-semibold text-gray-900"><?php echo htmlspecialchars($address['fullName']); ?></h3>
                                    </div>
                                    <div class="text-gray-600 text-sm space-y-1 mb-6">
                                        <p><?php echo htmlspecialchars($address['addressLine1']); ?></p>
                                        <?php if ($address['addressLine2']): ?>
                                            <p><?php echo htmlspecialchars($address['addressLine2']); ?></p>
                                        <?php endif; ?>
                                        <p><?php echo htmlspecialchars($address['city']) . ', ' . htmlspecialchars($address['state']) . ' ' . htmlspecialchars($address['postalCode']); ?></p>
                                        <p><?php echo htmlspecialchars($address['country']); ?></p>
                                        <p class="pt-2 text-gray-900 font-medium"><?php echo htmlspecialchars($address['phone']); ?></p>
                                    </div>
                                    <div class="flex gap-2">
                                        <button onclick='editAddress(<?php echo json_encode($address); ?>)' class="flex-1 px-4 py-2.5 bg-gray-100 hover:bg-gray-200 rounded-xl text-sm font-semibold transition-colors">Edit</button>
                                        <?php if (!$address['isDefault']): ?>
                                            <button onclick="setDefaultAddress('<?php echo $address['id']; ?>')" class="flex-1 px-4 py-2.5 bg-gray-100 hover:bg-gray-200 rounded-xl text-sm font-semibold transition-colors">Set Default</button>
                                        <?php endif; ?>
                                        <button onclick="deleteAddress('<?php echo $address['id']; ?>')" class="px-4 py-2.5 bg-red-50 hover:bg-red-100 text-red-600 rounded-xl text-sm font-semibold transition-colors">Delete</button>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                <?php elseif ($currentTab === 'settings'): ?>
                    <!-- ACCOUNT SETTINGS TAB -->
                    <h2 class="text-2xl font-light text-gray-900 mb-8">
                        Account <span class="font-semibold">Settings</span>
                    </h2>

                    <div class="space-y-6">
                        <!-- Profile Information -->
                        <div class="bg-white border border-gray-100 rounded-3xl p-6 lg:p-8">
                            <h3 class="text-lg font-semibold text-gray-900 mb-6">Profile Information</h3>
                            <form id="profileForm" enctype="multipart/form-data" class="space-y-6">
                                <div class="flex items-center gap-6 mb-8">
                                    <div class="relative">
                                        <div class="w-24 h-24 bg-gray-900 text-white rounded-2xl flex items-center justify-center font-semibold text-3xl overflow-hidden shadow-lg" id="profileImagePreview">
                                            <?php if ($user['profileImage']): ?>
                                                <img src="<?php echo htmlspecialchars($user['profileImage']); ?>?t=<?php echo time(); ?>" class="w-full h-full object-cover">
                                            <?php else: ?>
                                                <?php echo strtoupper(substr($user['name'] ?? $user['email'], 0, 1)); ?>
                                            <?php endif; ?>
                                        </div>
                                        <label for="profileImageInput" class="absolute -bottom-1 -right-1 bg-gray-900 text-white p-2.5 rounded-xl cursor-pointer hover:bg-gray-800 transition-colors shadow-lg">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                        </label>
                                        <input type="file" id="profileImageInput" name="profileImage" accept="image/*" class="hidden" onchange="previewImage(event)">
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-900"><?php echo htmlspecialchars($user['name'] ?? 'User'); ?></p>
                                        <p class="text-sm text-gray-500">Click icon to change photo</p>
                                    </div>
                                </div>
                                <div class="grid md:grid-cols-2 gap-6">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                                        <input type="text" name="name" value="<?php echo htmlspecialchars($user['name'] ?? ''); ?>" class="w-full px-5 py-4 rounded-xl border border-gray-200 focus:border-gray-400 focus:ring-2 focus:ring-gray-100 outline-none transition-all">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                                        <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" class="w-full px-5 py-4 rounded-xl border border-gray-200 focus:border-gray-400 focus:ring-2 focus:ring-gray-100 outline-none transition-all">
                                    </div>
                                </div>
                                <button type="submit" class="bg-gray-900 text-white px-8 py-4 rounded-full font-semibold hover:bg-gray-800 transition-colors">
                                    Save Changes
                                </button>
                            </form>
                        </div>

                        <!-- Change Password -->
                        <div class="bg-white border border-gray-100 rounded-3xl p-6 lg:p-8">
                            <h3 class="text-lg font-semibold text-gray-900 mb-6">Change Password</h3>
                            <form id="passwordForm" class="space-y-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Current Password</label>
                                    <input type="password" name="current_password" class="w-full px-5 py-4 rounded-xl border border-gray-200 focus:border-gray-400 focus:ring-2 focus:ring-gray-100 outline-none transition-all" required>
                                </div>
                                <div class="grid md:grid-cols-2 gap-6">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">New Password</label>
                                        <input type="password" name="new_password" class="w-full px-5 py-4 rounded-xl border border-gray-200 focus:border-gray-400 focus:ring-2 focus:ring-gray-100 outline-none transition-all" required>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Confirm New Password</label>
                                        <input type="password" name="confirm_password" class="w-full px-5 py-4 rounded-xl border border-gray-200 focus:border-gray-400 focus:ring-2 focus:ring-gray-100 outline-none transition-all" required>
                                    </div>
                                </div>
                                <button type="submit" class="bg-gray-900 text-white px-8 py-4 rounded-full font-semibold hover:bg-gray-800 transition-colors">
                                    Update Password
                                </button>
                            </form>
                        </div>

                        <!-- Delete Account -->
                        <div class="bg-red-50 border border-red-100 rounded-3xl p-6 lg:p-8">
                            <div class="flex items-start gap-4">
                                <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center flex-shrink-0">
                                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-red-600 mb-2">Delete Account</h3>
                                    <p class="text-sm text-red-600/80 mb-4">Once you delete your account, there is no going back. All your data will be permanently removed.</p>
                                    <button onclick="openDeleteModal()" class="bg-red-600 text-white px-6 py-3 rounded-full font-semibold hover:bg-red-700 transition-colors">
                                        Delete My Account
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                <?php endif; ?>

            </div>
        </div>
    </div>
</section>

<!-- Address Modal -->
<div id="addressModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden items-center justify-center p-6">
    <div class="bg-white rounded-3xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <div class="sticky top-0 bg-white px-8 py-6 border-b border-gray-100 flex justify-between items-center">
            <h3 class="text-xl font-semibold text-gray-900" id="addressModalTitle">Add New Address</h3>
            <button onclick="closeAddressModal()" class="w-10 h-10 rounded-xl bg-gray-100 flex items-center justify-center text-gray-400 hover:text-gray-600 hover:bg-gray-200 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <form id="addressForm" class="p-8 space-y-5">
            <input type="hidden" name="address_id" id="addressId">
            <input type="hidden" name="action" id="addressAction" value="add">
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Address Type</label>
                <select name="type" id="addressType" class="w-full px-5 py-4 rounded-xl border border-gray-200 focus:border-gray-400 focus:ring-2 focus:ring-gray-100 outline-none transition-all bg-white">
                    <option value="SHIPPING">Shipping</option>
                    <option value="BILLING">Billing</option>
                    <option value="BOTH">Both</option>
                </select>
            </div>
            
            <div class="grid md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                    <input type="text" name="full_name" id="addressFullName" required class="w-full px-5 py-4 rounded-xl border border-gray-200 focus:border-gray-400 focus:ring-2 focus:ring-gray-100 outline-none transition-all">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                    <input type="tel" name="phone" id="addressPhone" required class="w-full px-5 py-4 rounded-xl border border-gray-200 focus:border-gray-400 focus:ring-2 focus:ring-gray-100 outline-none transition-all">
                </div>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Address Line 1</label>
                <input type="text" name="address_line1" id="addressLine1" required class="w-full px-5 py-4 rounded-xl border border-gray-200 focus:border-gray-400 focus:ring-2 focus:ring-gray-100 outline-none transition-all">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Address Line 2 <span class="text-gray-400">(Optional)</span></label>
                <input type="text" name="address_line2" id="addressLine2" class="w-full px-5 py-4 rounded-xl border border-gray-200 focus:border-gray-400 focus:ring-2 focus:ring-gray-100 outline-none transition-all">
            </div>
            
            <div class="grid md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">City</label>
                    <input type="text" name="city" id="addressCity" required class="w-full px-5 py-4 rounded-xl border border-gray-200 focus:border-gray-400 focus:ring-2 focus:ring-gray-100 outline-none transition-all">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">State/Province</label>
                    <input type="text" name="state" id="addressState" required class="w-full px-5 py-4 rounded-xl border border-gray-200 focus:border-gray-400 focus:ring-2 focus:ring-gray-100 outline-none transition-all">
                </div>
            </div>
            
            <div class="grid md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Postal Code</label>
                    <input type="text" name="postal_code" id="addressPostalCode" required class="w-full px-5 py-4 rounded-xl border border-gray-200 focus:border-gray-400 focus:ring-2 focus:ring-gray-100 outline-none transition-all">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Country</label>
                    <input type="text" name="country" id="addressCountry" required class="w-full px-5 py-4 rounded-xl border border-gray-200 focus:border-gray-400 focus:ring-2 focus:ring-gray-100 outline-none transition-all">
                </div>
            </div>
            
            <div class="flex items-center gap-3 py-2">
                <input type="checkbox" name="is_default" id="addressIsDefault" value="1" class="w-5 h-5 rounded-lg border-gray-300 text-gray-900 focus:ring-gray-900">
                <label for="addressIsDefault" class="text-sm font-medium text-gray-700">Set as default address</label>
            </div>
            
            <div class="flex gap-3 pt-4">
                <button type="submit" class="flex-1 bg-gray-900 text-white px-6 py-4 rounded-full font-semibold hover:bg-gray-800 transition-colors">
                    Save Address
                </button>
                <button type="button" onclick="closeAddressModal()" class="px-6 py-4 bg-gray-100 hover:bg-gray-200 rounded-full font-semibold transition-colors">
                    Cancel
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Delete Account Modal -->
<div id="deleteModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden items-center justify-center p-6">
    <div class="bg-white rounded-3xl max-w-md w-full p-8">
        <div class="text-center mb-8">
            <div class="w-16 h-16 bg-red-100 rounded-2xl flex items-center justify-center mx-auto mb-6">
                <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
            <h3 class="text-2xl font-semibold text-gray-900 mb-2">Delete Account</h3>
            <p class="text-gray-600">This action cannot be undone. All your data will be permanently deleted.</p>
        </div>
        
        <form id="deleteAccountForm" class="space-y-5">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Enter your password</label>
                <input type="password" name="password" required class="w-full px-5 py-4 rounded-xl border border-gray-200 focus:border-red-400 focus:ring-2 focus:ring-red-100 outline-none transition-all">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Type <strong>DELETE</strong> to confirm</label>
                <input type="text" name="confirmation" required class="w-full px-5 py-4 rounded-xl border border-gray-200 focus:border-red-400 focus:ring-2 focus:ring-red-100 outline-none transition-all">
            </div>
            
            <div class="flex gap-3 pt-4">
                <button type="submit" class="flex-1 bg-red-600 text-white px-6 py-4 rounded-full font-semibold hover:bg-red-700 transition-colors">
                    Delete Account
                </button>
                <button type="button" onclick="closeDeleteModal()" class="px-6 py-4 bg-gray-100 hover:bg-gray-200 rounded-full font-semibold transition-colors">
                    Cancel
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Profile Image Preview
function previewImage(event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('profileImagePreview').innerHTML = `<img src="${e.target.result}" class="w-full h-full object-cover">`;
        };
        reader.readAsDataURL(file);
    }
}

// Profile Form
document.getElementById('profileForm')?.addEventListener('submit', async (e) => {
    e.preventDefault();
    const formData = new FormData(e.target);
    
    try {
        const response = await fetch('/api/update_profile.php', {
            method: 'POST',
            body: formData
        });
        const data = await response.json();
        
        if (data.success) {
            alert(data.message);
            window.location.href = window.location.pathname + '?tab=settings&_=' + Date.now();
        } else {
            alert(data.message);
        }
    } catch (error) {
        alert('An error occurred. Please try again.');
    }
});

// Password Form
document.getElementById('passwordForm')?.addEventListener('submit', async (e) => {
    e.preventDefault();
    const formData = new FormData(e.target);
    
    try {
        const response = await fetch('/api/update_password.php', {
            method: 'POST',
            body: formData
        });
        const data = await response.json();
        
        if (data.success) {
            alert(data.message);
            e.target.reset();
        } else {
            alert(data.message);
        }
    } catch (error) {
        alert('An error occurred. Please try again.');
    }
});

// Address Modal Functions
function openAddressModal() {
    document.getElementById('addressModal').classList.remove('hidden');
    document.getElementById('addressModal').classList.add('flex');
    document.getElementById('addressForm').reset();
    document.getElementById('addressModalTitle').textContent = 'Add New Address';
    document.getElementById('addressAction').value = 'add';
    document.getElementById('addressId').value = '';
}

function closeAddressModal() {
    document.getElementById('addressModal').classList.add('hidden');
    document.getElementById('addressModal').classList.remove('flex');
}

function editAddress(address) {
    document.getElementById('addressModal').classList.remove('hidden');
    document.getElementById('addressModal').classList.add('flex');
    document.getElementById('addressModalTitle').textContent = 'Edit Address';
    document.getElementById('addressAction').value = 'update';
    document.getElementById('addressId').value = address.id;
    document.getElementById('addressType').value = address.type;
    document.getElementById('addressFullName').value = address.fullName;
    document.getElementById('addressPhone').value = address.phone;
    document.getElementById('addressLine1').value = address.addressLine1;
    document.getElementById('addressLine2').value = address.addressLine2 || '';
    document.getElementById('addressCity').value = address.city;
    document.getElementById('addressState').value = address.state;
    document.getElementById('addressPostalCode').value = address.postalCode;
    document.getElementById('addressCountry').value = address.country;
    document.getElementById('addressIsDefault').checked = address.isDefault == 1;
}

// Address Form
document.getElementById('addressForm')?.addEventListener('submit', async (e) => {
    e.preventDefault();
    const formData = new FormData(e.target);
    
    try {
        const response = await fetch('/api/manage_address.php', {
            method: 'POST',
            body: formData
        });
        const data = await response.json();
        
        if (data.success) {
            alert(data.message);
            location.reload();
        } else {
            alert(data.message);
        }
    } catch (error) {
        alert('An error occurred. Please try again.');
    }
});

async function deleteAddress(addressId) {
    if (!confirm('Are you sure you want to delete this address?')) return;
    
    const formData = new FormData();
    formData.append('action', 'delete');
    formData.append('address_id', addressId);
    
    try {
        const response = await fetch('/api/manage_address.php', {
            method: 'POST',
            body: formData
        });
        const data = await response.json();
        
        if (data.success) {
            alert(data.message);
            location.reload();
        } else {
            alert(data.message);
        }
    } catch (error) {
        alert('An error occurred. Please try again.');
    }
}

async function setDefaultAddress(addressId) {
    const formData = new FormData();
    formData.append('action', 'set_default');
    formData.append('address_id', addressId);
    
    try {
        const response = await fetch('/api/manage_address.php', {
            method: 'POST',
            body: formData
        });
        const data = await response.json();
        
        if (data.success) {
            location.reload();
        } else {
            alert(data.message);
        }
    } catch (error) {
        alert('An error occurred. Please try again.');
    }
}

// Cancel Order Function
async function cancelOrder(orderId) {
    if (!confirm('Are you sure you want to cancel this order? This action cannot be undone.')) return;
    
    const formData = new FormData();
    formData.append('order_id', orderId);
    
    try {
        const response = await fetch('/api/cancel_order.php', {
            method: 'POST',
            body: formData
        });
        const data = await response.json();
        
        if (data.success) {
            alert(data.message);
            location.reload();
        } else {
            alert(data.message);
        }
    } catch (error) {
        alert('An error occurred. Please try again.');
    }
}

// Delete Account Modal
function openDeleteModal() {
    document.getElementById('deleteModal').classList.remove('hidden');
    document.getElementById('deleteModal').classList.add('flex');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
    document.getElementById('deleteModal').classList.remove('flex');
}

document.getElementById('deleteAccountForm')?.addEventListener('submit', async (e) => {
    e.preventDefault();
    const formData = new FormData(e.target);
    
    if (!confirm('This is your final warning. Are you absolutely sure you want to delete your account?')) return;
    
    try {
        const response = await fetch('/api/delete_account.php', {
            method: 'POST',
            body: formData
        });
        const data = await response.json();
        
        if (data.success) {
            alert(data.message);
            window.location.href = 'http://localhost:8080/';
        } else {
            alert(data.message);
        }
    } catch (error) {
        alert('An error occurred. Please try again.');
    }
});
</script>

<?php include __DIR__ . '/includes/footer.php'; ?>
