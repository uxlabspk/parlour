<?php
// php_web/checkout.php
include __DIR__ . '/includes/header.php';
requireLogin();

$cart = $_SESSION['cart'] ?? [];
if (empty($cart)) {
    header("Location: http://localhost:8080/cart.php");
    exit;
}

$subtotal = 0;
foreach ($cart as $item) {
    $subtotal += $item['price'] * $item['quantity'];
}
$shipping = 15.00;
$tax = $subtotal * 0.08;
$total = $subtotal + $shipping + $tax;

// Fetch user's default address or most recent address
$userId = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM addresses WHERE userId = ? ORDER BY isDefault DESC, createdAt DESC LIMIT 1");
$stmt->execute([$userId]);
$savedAddress = $stmt->fetch();

// Parse shipping info
$shippingName = $savedAddress ? $savedAddress['fullName'] : ($_SESSION['name'] ?? '');
$shippingPhone = $savedAddress ? $savedAddress['phone'] : '';
$shippingAddress = $savedAddress ? $savedAddress['addressLine1'] : '';
$shippingAddress2 = $savedAddress ? $savedAddress['addressLine2'] : '';
$shippingCity = $savedAddress ? $savedAddress['city'] : '';
$shippingState = $savedAddress ? $savedAddress['state'] : '';
$shippingZip = $savedAddress ? $savedAddress['postalCode'] : '';
$shippingCountry = $savedAddress ? $savedAddress['country'] : 'US';
?>

<!-- Checkout Page Styles -->
<style>
    .form-input {
        transition: all 0.2s ease;
    }
    .form-input:focus {
        border-color: #111;
        box-shadow: 0 0 0 3px rgba(17, 17, 17, 0.1);
    }
    .payment-option {
        transition: all 0.2s ease;
    }
    .payment-option:has(input:checked) {
        border-color: #111;
        background-color: rgba(17, 17, 17, 0.02);
    }
</style>

<div class="max-w-7xl mx-auto px-6 lg:px-8 pt-28 lg:pt-36 pb-16 lg:pb-24">
    <!-- Page Header -->
    <div class="mb-12">
        <a href="http://localhost:8080/cart.php" class="inline-flex items-center gap-2 text-gray-500 hover:text-gray-900 transition mb-4">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            <span class="text-sm font-medium">Back to Bag</span>
        </a>
        <h1 class="text-3xl lg:text-4xl font-light text-gray-900 tracking-tight">
            <span class="font-semibold">Checkout</span>
        </h1>
    </div>

    <form action="http://localhost:8080/api/place_order.php" method="POST" class="grid lg:grid-cols-3 gap-8 lg:gap-12">
        <!-- Shipping Information -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Shipping Section -->
            <div class="bg-white rounded-3xl p-6 lg:p-8 border border-gray-100">
                <div class="flex justify-between items-center mb-6">
                    <div class="flex items-center gap-3">
                        <span class="w-8 h-8 bg-gray-900 text-white rounded-full flex items-center justify-center text-sm font-semibold">1</span>
                        <h2 class="text-xl font-semibold tracking-tight">Shipping</h2>
                    </div>
                    <?php if ($savedAddress): ?>
                        <a href="http://localhost:8080/profile.php?tab=addresses" class="text-sm text-gray-500 hover:text-gray-900 font-medium">
                            Change Address
                        </a>
                    <?php endif; ?>
                </div>
                
                <?php if ($savedAddress): ?>
                    <div class="mb-6 p-4 bg-emerald-50/50 border border-emerald-100 rounded-2xl">
                        <div class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-emerald-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <div>
                                <p class="text-sm font-semibold text-emerald-900">Using saved address</p>
                                <p class="text-xs text-emerald-700 mt-1">You can manage addresses in your <a href="http://localhost:8080/profile.php?tab=addresses" class="underline font-medium">profile settings</a></p>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
                
                <div class="grid md:grid-cols-2 gap-5">
                    <div class="md:col-span-2">
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Full Name</label>
                        <input type="text" name="shipping_name" required value="<?php echo htmlspecialchars($shippingName); ?>" class="form-input w-full px-4 py-3.5 rounded-xl border border-gray-200 focus:outline-none text-sm">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Phone Number</label>
                        <input type="tel" name="shipping_phone" required value="<?php echo htmlspecialchars($shippingPhone); ?>" class="form-input w-full px-4 py-3.5 rounded-xl border border-gray-200 focus:outline-none text-sm">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Street Address</label>
                        <input type="text" name="shipping_address" required value="<?php echo htmlspecialchars($shippingAddress); ?>" class="form-input w-full px-4 py-3.5 rounded-xl border border-gray-200 focus:outline-none text-sm" placeholder="House number and street name">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Apartment, Suite, etc. <span class="text-gray-400 font-normal">(Optional)</span></label>
                        <input type="text" name="shipping_address2" value="<?php echo htmlspecialchars($shippingAddress2); ?>" class="form-input w-full px-4 py-3.5 rounded-xl border border-gray-200 focus:outline-none text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">City</label>
                        <input type="text" name="shipping_city" required value="<?php echo htmlspecialchars($shippingCity); ?>" class="form-input w-full px-4 py-3.5 rounded-xl border border-gray-200 focus:outline-none text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">State / Province</label>
                        <input type="text" name="shipping_state" required value="<?php echo htmlspecialchars($shippingState); ?>" class="form-input w-full px-4 py-3.5 rounded-xl border border-gray-200 focus:outline-none text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Postal Code</label>
                        <input type="text" name="shipping_zip" required value="<?php echo htmlspecialchars($shippingZip); ?>" class="form-input w-full px-4 py-3.5 rounded-xl border border-gray-200 focus:outline-none text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Country</label>
                        <select name="shipping_country" required class="form-input w-full px-4 py-3.5 rounded-xl border border-gray-200 focus:outline-none text-sm appearance-none bg-white cursor-pointer">
                            <!-- <option value="US" <?php echo $shippingCountry === 'US' ? 'selected' : ''; ?>>United States</option>
                            <option value="CA" <?php echo $shippingCountry === 'CA' ? 'selected' : ''; ?>>Canada</option>
                            <option value="UK" <?php echo $shippingCountry === 'UK' ? 'selected' : ''; ?>>United Kingdom</option>
                            <option value="DE" <?php echo $shippingCountry === 'DE' ? 'selected' : ''; ?>>Germany</option>
                            <option value="FR" <?php echo $shippingCountry === 'FR' ? 'selected' : ''; ?>>France</option> -->
                            <option value="PK" <?php echo $shippingCountry === 'PK' ? 'selected' : ''; ?>>Pakistan</option>
                        </select>
                    </div>
                    <?php if (!$savedAddress): ?>
                        <div class="md:col-span-2 pt-2">
                            <label class="flex items-center gap-3 cursor-pointer group">
                                <input type="checkbox" name="save_address" value="1" checked class="w-5 h-5 rounded border-gray-300 text-gray-900 focus:ring-gray-900">
                                <span class="text-sm text-gray-600 group-hover:text-gray-900 transition">Save this address for future orders</span>
                            </label>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Payment Section -->
            <div class="bg-white rounded-3xl p-6 lg:p-8 border border-gray-100">
                <div class="flex items-center gap-3 mb-6">
                    <span class="w-8 h-8 bg-gray-900 text-white rounded-full flex items-center justify-center text-sm font-semibold">2</span>
                    <h2 class="text-xl font-semibold tracking-tight">Payment</h2>
                </div>
                <div class="space-y-3">
                    <!-- <label class="payment-option flex items-center gap-4 p-4 border-2 border-gray-200 rounded-2xl cursor-pointer">
                        <input type="radio" name="payment_method" value="hbl_konnect" checked class="w-5 h-5 text-gray-900 focus:ring-gray-900">
                        <div class="flex-1">
                            <p class="font-semibold text-gray-900">HBL Konnect (Raast)</p>
                            <p class="text-sm text-gray-500">Instant payment via Raast - Recommended</p>
                        </div>
                        <div class="flex gap-2">
                            <div class="w-12 h-6 bg-green-100 rounded flex items-center justify-center text-[10px] font-bold text-green-700">HBL</div>
                            <div class="w-12 h-6 bg-blue-100 rounded flex items-center justify-center text-[10px] font-bold text-blue-700">Raast</div>
                        </div>
                    </label> -->
                    <!-- <label class="payment-option flex items-center gap-4 p-4 border-2 border-gray-200 rounded-2xl cursor-pointer">
                        <input type="radio" name="payment_method" value="credit_card" class="w-5 h-5 text-gray-900 focus:ring-gray-900">
                        <div class="flex-1">
                            <p class="font-semibold text-gray-900">Credit / Debit Card</p>
                            <p class="text-sm text-gray-500">Secure payment via Stripe</p>
                        </div>
                        <div class="flex gap-2">
                            <div class="w-10 h-6 bg-gray-100 rounded flex items-center justify-center text-[10px] font-bold text-gray-500">VISA</div>
                            <div class="w-10 h-6 bg-gray-100 rounded flex items-center justify-center text-[10px] font-bold text-gray-500">MC</div>
                        </div>
                    </label> -->
                    <!-- <label class="payment-option flex items-center gap-4 p-4 border-2 border-gray-200 rounded-2xl cursor-pointer">
                        <input type="radio" name="payment_method" value="paypal" class="w-5 h-5 text-gray-900 focus:ring-gray-900">
                        <div class="flex-1">
                            <p class="font-semibold text-gray-900">PayPal</p>
                            <p class="text-sm text-gray-500">Pay with your PayPal account</p>
                        </div>
                        <div class="w-16 h-6 bg-blue-50 rounded flex items-center justify-center text-[10px] font-bold text-blue-600">PayPal</div>
                    </label> -->
                    <label class="payment-option flex items-center gap-4 p-4 border-2 border-gray-200 rounded-2xl cursor-pointer">
                        <input type="radio" name="payment_method" value="cod" class="w-5 h-5 text-gray-900 focus:ring-gray-900">
                        <div class="flex-1">
                            <p class="font-semibold text-gray-900">Cash on Delivery</p>
                            <p class="text-sm text-gray-500">Pay when you receive your order</p>
                        </div>
                        <div class="w-10 h-6 bg-emerald-50 rounded flex items-center justify-center">
                            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        </div>
                    </label>
                </div>
            </div>
        </div>

        <!-- Order Summary -->
        <div class="lg:col-span-1">
            <div class="bg-gray-50 rounded-3xl p-6 lg:p-8 sticky top-32 border border-gray-100/50">
                <h2 class="text-xl font-semibold mb-6 tracking-tight">Order Summary</h2>
                
                <!-- Cart Items -->
                <div class="space-y-4 mb-6 max-h-64 overflow-y-auto">
                    <?php foreach ($cart as $item): ?>
                        <div class="flex gap-3 items-start">
                            <div class="w-14 h-16 bg-gray-200 rounded-xl overflow-hidden flex-shrink-0">
                                <img src="<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>" class="w-full h-full object-cover">
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 truncate"><?php echo htmlspecialchars($item['name']); ?></p>
                                <p class="text-xs text-gray-500">Qty: <?php echo $item['quantity']; ?></p>
                            </div>
                            <p class="text-sm font-semibold text-gray-900">PKR <?php echo number_format($item['price'] * $item['quantity'], 0); ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
                
                <!-- Totals -->
                <div class="space-y-3 border-t border-gray-200 pt-4 mb-6">
                    <div class="flex justify-between text-sm text-gray-600">
                        <span>Subtotal</span>
                        <span class="font-medium text-gray-900">PKR <?php echo number_format($subtotal, 0); ?></span>
                    </div>
                    <div class="flex justify-between text-sm text-gray-600">
                        <span>Shipping</span>
                        <span class="font-medium text-gray-900">PKR <?php echo number_format($shipping, 0); ?></span>
                    </div>
                    <div class="flex justify-between text-sm text-gray-600">
                        <span>Tax (8%)</span>
                        <span class="font-medium text-gray-900">PKR <?php echo number_format($tax, 0); ?></span>
                    </div>
                </div>
                
                <div class="flex justify-between text-lg font-semibold text-gray-900 border-t border-gray-200 pt-4 mb-6">
                    <span>Total</span>
                    <span>PKR <?php echo number_format($total, 0); ?></span>
                </div>

                <button type="submit" class="w-full bg-gray-900 text-white py-4 rounded-full font-semibold hover:bg-gray-800 transition flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    <span>Place Order</span>
                </button>
                
                <p class="text-center text-xs text-gray-500 mt-4 leading-relaxed">
                    Your transaction is encrypted and secure
                </p>
                
                <!-- Trust Badges -->
                <div class="flex items-center justify-center gap-4 mt-4 pt-4 border-t border-gray-200">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                </div>
            </div>
        </div>
    </form>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
