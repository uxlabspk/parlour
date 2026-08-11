<?php
// php_web/cart.php
include __DIR__ . '/includes/header.php';

$cart = $_SESSION['cart'] ?? [];
$subtotal = 0;
$shipping = 0;
foreach ($cart as $item) {
    $subtotal += $item['price'] * $item['quantity'];
    // Add individual product shipping cost if set
    if (isset($item['shippingPricing']) && $item['shippingPricing'] > 0) {
        $shipping += $item['shippingPricing'] * $item['quantity'];
    }
}

// If no products have shipping pricing, use default flat rate if there are items
if ($subtotal > 0 && $shipping == 0) {
    $shipping = 15.00;
}
$tax = $subtotal * 0.08;
$total = $subtotal + $shipping + $tax;
?>

<!-- Cart Page Styles -->
<style>
    .cart-item {
        transition: all 0.3s ease;
    }
    .cart-item:hover {
        background-color: rgba(249, 250, 251, 0.5);
    }
    .qty-btn {
        transition: all 0.2s ease;
    }
    .qty-btn:hover {
        background-color: #111;
        color: #fff;
    }
    .summary-card {
        transition: all 0.3s ease;
    }
</style>

<!-- Hero Section -->
 <div class="relative h-[60vh] min-h-[500px] flex items-center justify-center overflow-hidden">
    <div class="absolute inset-0 -z-10">
        <img 
            src="https://images.unsplash.com/photo-1441984904996-e0b6ba687e04?w=1920&q=85" 
            alt="Shop Collection" 
            class="w-full h-full object-cover"
        />
        <div class="absolute inset-0 bg-gradient-to-r from-black/70 via-black/50 to-transparent"></div>
    </div>
    
    <div class="relative z-10 text-center px-6">
        <span class="inline-block text-sm font-semibold text-white/70 tracking-[0.3em] uppercase mb-4">Shopping</span>
        <h1 class="text-5xl lg:text-6xl font-light text-white mb-4 tracking-tight">
            Your <span class="font-semibold">Bag</span>
        </h1>
        <p class="text-lg text-white/70 max-w-xl mx-auto">
            Explore our curated selections, each designed with a unique vision of modern elegance.
        </p>
    </div>
</div>

<div class="max-w-7xl mx-auto px-6 lg:px-8 pt-12 pb-16 lg:pb-24">
    <?php if (empty($cart)): ?>
        <div class="text-center py-20 bg-gray-50 rounded-3xl max-w-2xl mx-auto">
            <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                </svg>
            </div>
            <h2 class="text-2xl font-semibold text-gray-900 mb-3">Your bag is empty</h2>
            <p class="text-gray-500 mb-8 max-w-sm mx-auto">Looks like you haven't added anything to your bag yet. Start exploring our collection.</p>
            <a href="https://parlour.com/shop" class="inline-flex items-center gap-2 bg-gray-900 text-white px-8 py-4 rounded-full font-semibold hover:bg-gray-800 transition">
                <span>Start Shopping</span>
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </a>
        </div>
    <?php else: ?>
        <div class="grid lg:grid-cols-3 gap-8 lg:gap-12">
            <!-- Cart Items -->
            <div class="lg:col-span-2 space-y-1">
                <div class="bg-white rounded-3xl border border-gray-100 overflow-hidden">
                    <?php $index = 0; foreach ($cart as $id => $item): $index++; ?>
                        <div class="cart-item flex gap-5 p-6 <?php echo $index < count($cart) ? 'border-b border-gray-100' : ''; ?>" data-cart-item="<?php echo htmlspecialchars($id); ?>">
                            <a href="https://parlour.com/product?id=<?php echo $item['id'] ?? ''; ?>" class="w-24 h-28 lg:w-28 lg:h-32 rounded-2xl overflow-hidden bg-gray-100 flex-shrink-0">
                                <img src="<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>" class="w-full h-full object-cover hover:scale-105 transition-transform duration-500">
                            </a>
                            <div class="flex-1 flex flex-col justify-between min-w-0">
                                <div>
                                    <div class="flex justify-between items-start gap-4 mb-2">
                                        <a href="https://parlour.com/product?id=<?php echo $item['id'] ?? ''; ?>" class="text-base lg:text-lg font-semibold text-gray-900 hover:text-gray-600 transition truncate"><?php echo htmlspecialchars($item['name']); ?></a>
                                        <p id="price-<?php echo htmlspecialchars($id); ?>" class="text-base lg:text-lg font-semibold text-gray-900 flex-shrink-0" data-unit-price="<?php echo $item['price']; ?>">PKR <?php echo number_format($item['price'] * $item['quantity'], 0); ?></p>
                                    </div>
                                    <div class="flex flex-wrap gap-3 text-sm text-gray-500">
                                        <span class="bg-gray-100 px-3 py-1 rounded-full">Size: <?php echo htmlspecialchars($item['size']); ?></span>
                                        <?php if ($item['color']): ?>
                                            <span class="bg-gray-100 px-3 py-1 rounded-full">Color: <?php echo htmlspecialchars($item['color']); ?></span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="flex justify-between items-center mt-4">
                                    <div class="flex items-center bg-gray-100 rounded-full overflow-hidden">
                                        <button onclick="updateQuantity('<?php echo htmlspecialchars($id); ?>', -1)" class="qty-btn w-9 h-9 flex items-center justify-center text-gray-600 font-medium rounded-full">
                                            −
                                        </button>
                                        <span id="qty-<?php echo htmlspecialchars($id); ?>" class="px-3 text-sm font-semibold min-w-[2.5rem] text-center"><?php echo $item['quantity']; ?></span>
                                        <button onclick="updateQuantity('<?php echo htmlspecialchars($id); ?>', 1)" class="qty-btn w-9 h-9 flex items-center justify-center text-gray-600 font-medium rounded-full">
                                            +
                                        </button>
                                    </div>
                                    <a href="https://parlour.com/api/cart?action=remove&cart_id=<?php echo urlencode($id); ?>" class="text-gray-400 hover:text-rose-500 transition p-2 rounded-full hover:bg-rose-50">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                
                <!-- Continue Shopping Link -->
                <div class="pt-6">
                    <a href="https://parlour.com/shop" class="inline-flex items-center gap-2 text-gray-600 hover:text-gray-900 transition font-medium">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                        <span>Continue Shopping</span>
                    </a>
                </div>
            </div>

            <!-- Summary -->
            <div class="lg:col-span-1">
                <div class="summary-card bg-gray-50 rounded-3xl p-6 lg:p-8 sticky top-32 border border-gray-100/50">
                    <h2 class="text-xl font-semibold mb-6 tracking-tight">Order Summary</h2>
                    
                    <div class="space-y-4 mb-6">
                        <div class="flex justify-between text-gray-600">
                            <span>Subtotal</span>
                            <span id="subtotal" class="font-medium text-gray-900">PKR <?php echo number_format($subtotal, 0); ?></span>
                        </div>
                        <div class="flex justify-between text-gray-600">
                            <span>Shipping</span>
                            <span id="shipping" class="font-medium text-gray-900">PKR <?php echo number_format($shipping, 0); ?></span>
                        </div>
                        <div class="flex justify-between text-gray-600">
                            <span>Tax (8%)</span>
                            <span id="tax" class="font-medium text-gray-900">PKR <?php echo number_format($tax, 0); ?></span>
                        </div>
                    </div>
                    
                    <div class="border-t border-gray-200 pt-4 mb-6">
                        <div class="flex justify-between text-lg font-semibold text-gray-900">
                            <span>Total</span>
                            <span id="total">PKR <?php echo number_format($total, 0); ?></span>
                        </div>
                    </div>
                    
                    <?php if (isLoggedIn()): ?>
                        <a href="https://parlour.com/checkout" class="flex items-center justify-center gap-2 w-full bg-gray-900 text-white py-4 rounded-full font-semibold hover:bg-gray-800 transition">
                            <span>Checkout</span>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                        </a>
                    <?php else: ?>
                        <a href="https://parlour.com/auth/login?redirect=checkout" class="flex items-center justify-center gap-2 w-full bg-gray-900 text-white py-4 rounded-full font-semibold hover:bg-gray-800 transition">
                            <span>Sign in to Checkout</span>
                        </a>
                        <p class="text-center text-xs text-gray-500 mt-4">
                            You need to sign in to proceed with checkout
                        </p>
                    <?php endif; ?>
                    
                    <!-- Trust Badges -->
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <div class="flex items-center justify-center gap-6 text-gray-400">
                            <div class="flex flex-col items-center gap-1">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                <span class="text-[10px] font-medium">Secure</span>
                            </div>
                            <div class="flex flex-col items-center gap-1">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                                <span class="text-[10px] font-medium">Cards</span>
                            </div>
                            <div class="flex flex-col items-center gap-1">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                                <span class="text-[10px] font-medium">Protected</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<script>
function updateQuantity(cartId, change) {
    const qtyElement = document.getElementById('qty-' + cartId);
    const priceElement = document.getElementById('price-' + cartId);
    let currentQty = parseInt(qtyElement.textContent);
    let newQty = currentQty + change;
    
    if (newQty < 1) return;
    
    qtyElement.textContent = newQty;
    const unitPrice = parseFloat(priceElement.getAttribute('data-unit-price'));
    priceElement.textContent = 'PKR ' + Math.round(unitPrice * newQty).toLocaleString();
    
    fetch('https://parlour.com/api/cart.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: new URLSearchParams({
            action: 'update_quantity',
            cart_id: cartId,
            quantity: newQty,
            ajax: '1'
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById('subtotal').textContent = 'PKR ' + Math.round(parseFloat(data.subtotal)).toLocaleString();
            document.getElementById('shipping').textContent = 'PKR ' + Math.round(parseFloat(data.shipping)).toLocaleString();
            document.getElementById('tax').textContent = 'PKR ' + Math.round(parseFloat(data.tax)).toLocaleString();
            document.getElementById('total').textContent = 'PKR ' + Math.round(parseFloat(data.total)).toLocaleString();
            priceElement.textContent = 'PKR ' + Math.round(parseFloat(data.itemTotal)).toLocaleString();
        } else {
            qtyElement.textContent = currentQty;
            priceElement.textContent = 'PKR ' + Math.round(unitPrice * currentQty).toLocaleString();
        }
    })
    .catch(error => {
        console.error('Error updating quantity:', error);
        qtyElement.textContent = currentQty;
        priceElement.textContent = 'PKR ' + Math.round(unitPrice * currentQty).toLocaleString();
    });
}
</script>

<?php include __DIR__ . '/includes/footer.php'; ?>
