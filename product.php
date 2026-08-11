<?php
// php_web/product.php
include __DIR__ . '/includes/header.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: https://parlour.com/shop");
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$id]);
$product = $stmt->fetch();

if (!$product) {
    header("Location: https://parlour.com/shop");
    exit;
}

// Parse JSON fields
$images = json_decode($product['images'] ?? '[]', true);
$sizes = json_decode($product['sizes'] ?? '[]', true);
$colors = json_decode($product['colors'] ?? '[]', true);

// Parse single size/color fields and merge with arrays
if (!empty($product['size'])) {
    $singleSizes = array_map('trim', explode(',', $product['size']));
    $sizes = array_unique(array_merge($sizes, $singleSizes));
}
if (!empty($product['color'])) {
    $singleColors = array_map('trim', explode(',', $product['color']));
    $colors = array_unique(array_merge($colors, $singleColors));
}

// Fetch approved reviews for this product
$reviewStmt = $pdo->prepare("
    SELECT r.*, u.name as userName, u.profileImage 
    FROM reviews r 
    JOIN users u ON r.userId = u.id 
    WHERE r.productId = ? AND r.approved = TRUE 
    ORDER BY r.createdAt DESC
");
$reviewStmt->execute([$id]);
$reviews = $reviewStmt->fetchAll();

// Check if current user has already reviewed this product
$hasReviewed = false;
if (isLoggedIn()) {
    $checkStmt = $pdo->prepare("SELECT id FROM reviews WHERE productId = ? AND userId = ?");
    $checkStmt->execute([$id, $_SESSION['user_id']]);
    $hasReviewed = $checkStmt->fetch() !== false;
}
?>

<!-- Modern Product Page Styles -->
<style>
    .product-detail-card {
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .size-option:hover {
        transform: translateY(-2px);
    }
    .color-option {
        transition: all 0.3s ease;
    }
    .color-option:hover {
        transform: scale(1.1);
    }
    .thumbnail-btn {
        transition: all 0.3s ease;
    }
    .thumbnail-btn:hover {
        transform: scale(1.05);
    }
    .review-card {
        transition: all 0.3s ease;
    }
    .review-card:hover {
        transform: translateY(-4px);
    }
</style>

<div class="max-w-7xl mx-auto px-6 lg:px-8 pt-28 lg:pt-36 pb-16 lg:pb-24">
    <!-- Breadcrumb -->
    <nav class="flex items-center text-sm text-gray-500 mb-10 overflow-x-auto whitespace-nowrap pb-2">
        <a href="https://parlour.com/" class="hover:text-gray-900 transition-colors">Home</a>
        <svg class="w-4 h-4 mx-3 text-gray-300 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
        <a href="https://parlour.com/shop" class="hover:text-gray-900 transition-colors">Shop</a>
        <svg class="w-4 h-4 mx-3 text-gray-300 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
        <span class="text-gray-900 font-medium"><?php echo htmlspecialchars($product['name']); ?></span>
    </nav>

    <div class="grid lg:grid-cols-2 gap-12 xl:gap-16">
        <!-- Image Gallery with Zoom -->
        <div class="space-y-5">
            <div class="main-image-wrapper relative aspect-[4/5] rounded-3xl overflow-hidden bg-gray-100 cursor-crosshair group">
                <img id="main-image" 
                     src="<?php echo htmlspecialchars($product['image']); ?>" 
                     alt="<?php echo htmlspecialchars($product['name']); ?>" 
                     class="w-full h-full object-cover transition-transform duration-300 ease-out origin-center">
                
                <!-- Badges -->
                <div class="absolute top-5 left-5 flex flex-col gap-2">
                    <?php if ($product['sale']): ?>
                        <span class="bg-rose-500 text-white text-xs font-semibold px-4 py-1.5 rounded-full">Sale</span>
                    <?php endif; ?>
                    <?php if ($product['featured']): ?>
                        <span class="bg-amber-500 text-white text-xs font-semibold px-4 py-1.5 rounded-full">Featured</span>
                    <?php endif; ?>
                </div>
                
                <!-- Zoom Hint -->
                <div class="absolute top-5 right-5 bg-white/90 backdrop-blur-sm text-gray-700 p-2.5 rounded-full opacity-0 group-hover:opacity-100 transition-opacity shadow-sm pointer-events-none">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7" /></svg>
                </div>
            </div>

            <?php if (!empty($images) && count($images) > 0): ?>
                <div class="grid grid-cols-5 gap-3">
                    <!-- Main Image Thumbnail -->
                    <button type="button" 
                            onclick="changeMainImage('<?php echo htmlspecialchars($product['image']); ?>', this)" 
                            class="thumbnail-btn aspect-square rounded-2xl overflow-hidden ring-2 ring-gray-900 ring-offset-2 bg-gray-100">
                        <img src="<?php echo htmlspecialchars($product['image']); ?>" class="w-full h-full object-cover">
                    </button>
                    <!-- Other Images -->
                    <?php foreach ($images as $index => $img): ?>
                        <button type="button" 
                                onclick="changeMainImage('<?php echo htmlspecialchars($img); ?>', this)" 
                                class="thumbnail-btn aspect-square rounded-2xl overflow-hidden ring-1 ring-gray-200 ring-offset-2 hover:ring-gray-400 opacity-60 hover:opacity-100 bg-gray-100">
                            <img src="<?php echo htmlspecialchars($img); ?>" class="w-full h-full object-cover">
                        </button>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Product Details -->
        <div class="lg:py-4">
            <!-- Category Badge -->
            <span class="inline-block text-xs font-semibold text-gray-500 uppercase tracking-[0.2em] mb-4">
                <?php echo htmlspecialchars($product['category']); ?>
            </span>
            
            <h1 class="text-3xl lg:text-4xl xl:text-5xl font-light text-gray-900 mb-6 tracking-tight leading-tight">
                <?php echo htmlspecialchars($product['name']); ?>
            </h1>
            
            <!-- Price & Rating -->
            <div class="flex flex-wrap items-center gap-4 mb-8 pb-8 border-b border-gray-100">
                <div class="flex items-center gap-3">
                    <?php 
                    $displayPrice = $product['discountedPrice'] ?? $product['price'];
                    $hasDiscount = !empty($product['discountedPrice']) && $product['discountedPrice'] < $product['price'];
                    $discountPercent = 0;
                    
                    if ($hasDiscount) {
                        $discountPercent = round((($product['price'] - $product['discountedPrice']) / $product['price']) * 100);
                    } elseif ($product['originalPrice']) {
                        $discountPercent = round((($product['originalPrice'] - $product['price']) / $product['originalPrice']) * 100);
                    }
                    ?>
                    <span class="text-2xl lg:text-3xl font-semibold text-gray-900">PKR <?php echo number_format($displayPrice, 0); ?></span>
                    <?php if ($hasDiscount): ?>
                        <span class="text-lg text-gray-400 line-through">PKR <?php echo number_format($product['price'], 0); ?></span>
                        <span class="bg-rose-50 text-rose-600 px-3 py-1 rounded-full text-sm font-semibold">
                            -<?php echo $discountPercent; ?>%
                        </span>
                    <?php elseif ($product['originalPrice']): ?>
                        <span class="text-lg text-gray-400 line-through">PKR <?php echo number_format($product['originalPrice'], 0); ?></span>
                        <span class="bg-rose-50 text-rose-600 px-3 py-1 rounded-full text-sm font-semibold">
                            -<?php echo $discountPercent; ?>%
                        </span>
                    <?php endif; ?>
                </div>
                
                <?php if ($product['reviewCount'] > 0): ?>
                <div class="flex items-center gap-2 ml-auto">
                    <div class="flex text-amber-400">
                        <?php for ($i = 1; $i <= 5; $i++): echo $i <= round($product['rating']) ? '★' : '<span class="text-gray-200">★</span>'; endfor; ?>
                    </div>
                    <span class="text-sm text-gray-500">(<?php echo $product['reviewCount']; ?>)</span>
                </div>
                <?php endif; ?>
            </div>

            <div class="text-gray-600 leading-relaxed mb-8">
                <?php echo nl2br(htmlspecialchars($product['description'] ?? '')); ?>
            </div>

            <?php if (!empty($product['shippingPricing']) && $product['shippingPricing'] > 0): ?>
            <div class="bg-gray-50 rounded-2xl p-5 mb-8 flex items-center gap-4">
                <div class="w-11 h-11 rounded-full bg-white flex items-center justify-center text-gray-700 shadow-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
                </div>
                <div>
                    <p class="text-sm font-semibold text-gray-900">Shipping Estimate</p>
                    <p class="text-sm text-gray-500">PKR <?php echo number_format($product['shippingPricing'], 0); ?> · Calculated at checkout</p>
                </div>
            </div>
            <?php endif; ?>

            <form id="add-to-cart-form" action="https://parlour.com/api/cart.php" method="POST" class="space-y-6">
                <input type="hidden" name="action" value="add">
                <input type="hidden" name="ajax" value="1">
                <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">

                <?php if (!empty($sizes)): ?>
                    <div>
                        <div class="flex justify-between items-center mb-4">
                            <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Select Size</label>
                            <button type="button" class="text-xs text-gray-500 hover:text-gray-900 underline">Size Guide</button>
                        </div>
                        <div class="flex flex-wrap gap-2">
                            <?php foreach ($sizes as $size): ?>
                                <label class="cursor-pointer">
                                    <input type="radio" name="size" value="<?php echo htmlspecialchars($size); ?>" class="peer sr-only" required>
                                    <div class="size-option px-5 py-3 rounded-xl font-medium bg-white border border-gray-200 text-gray-600 peer-checked:bg-gray-900 peer-checked:text-white peer-checked:border-gray-900 hover:border-gray-400 transition-all text-center min-w-[3rem] text-sm">
                                        <?php echo htmlspecialchars($size); ?>
                                    </div>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php else: ?>
                    <input type="hidden" name="size" value="Standard">
                <?php endif; ?>

                <?php if (!empty($colors)): ?>
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-4">Select Color</label>
                        <div class="flex flex-wrap gap-3">
                            <?php foreach ($colors as $color): ?>
                                <label class="cursor-pointer relative group">
                                    <input type="radio" name="color" value="<?php echo htmlspecialchars($color); ?>" class="peer sr-only" required>
                                    <div class="color-option w-11 h-11 rounded-full border border-gray-200 flex items-center justify-center peer-checked:ring-2 peer-checked:ring-gray-900 peer-checked:ring-offset-2 bg-white">
                                        <span class="text-xs font-semibold text-gray-600"><?php echo substr($color, 0, 2); ?></span>
                                    </div>
                                    <span class="absolute -bottom-9 left-1/2 -translate-x-1/2 bg-gray-900 text-white text-xs px-3 py-1.5 rounded-lg opacity-0 group-hover:opacity-100 transition whitespace-nowrap pointer-events-none z-10">
                                        <?php echo htmlspecialchars($color); ?>
                                    </span>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php else: ?>
                    <input type="hidden" name="color" value="">
                <?php endif; ?>

                <div class="pt-6 space-y-4">
                    <button type="submit" 
                            class="w-full bg-gray-900 text-white py-4 rounded-full font-semibold text-base hover:bg-gray-800 transition-all disabled:bg-gray-200 disabled:text-gray-400 disabled:cursor-not-allowed flex items-center justify-center gap-3" 
                            <?php echo $product['inStock'] ? '' : 'disabled'; ?>>
                        <?php if ($product['inStock']): ?>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" /></svg>
                            <span>Add to Bag</span>
                        <?php else: ?>
                            <span>Out of Stock</span>
                        <?php endif; ?>
                    </button>
                    
                    <div id="cart-message" class="hidden p-4 rounded-2xl font-medium text-sm"></div>
                    
                    <!-- Features -->
                    <div class="grid grid-cols-3 gap-3 pt-4">
                        <div class="flex flex-col items-center gap-2 p-4 rounded-2xl bg-gray-50 text-center">
                            <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" /></svg>
                            <span class="text-xs font-medium text-gray-600">Expert Stylists</span>
                        </div>
                        <div class="flex flex-col items-center gap-2 p-4 rounded-2xl bg-gray-50 text-center">
                            <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" /></svg>
                            <span class="text-xs font-medium text-gray-600">Free Consultation</span>
                        </div>
                        <div class="flex flex-col items-center gap-2 p-4 rounded-2xl bg-gray-50 text-center">
                            <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
                            <span class="text-xs font-medium text-gray-600">Premium Products</span>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Reviews Section -->
    <div class="mt-20 lg:mt-28">
        <div class="text-center mb-12">
            <span class="inline-block text-sm font-semibold text-gray-500 tracking-[0.3em] uppercase mb-3">Reviews</span>
            <h2 class="text-3xl lg:text-4xl font-light text-gray-900 tracking-tight">
                Customer <span class="font-semibold">Feedback</span>
            </h2>
        </div>
        
        <!-- Review Form -->
        <?php if (isLoggedIn()): ?>
            <?php if (!$hasReviewed): ?>
                <div class="bg-white p-8 lg:p-10 rounded-3xl border border-gray-100 shadow-sm mb-10 max-w-7xl mx-auto">
                    <h3 class="text-xl font-semibold mb-6 text-center">Share Your Experience</h3>
                    <form id="review-form" class="space-y-6">
                        <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                        
                        <div class="text-center">
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-4">Your Rating</label>
                            <div class="flex justify-center gap-2" id="star-rating">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <button type="button" class="star-btn text-4xl text-gray-300 hover:text-amber-400 transition" data-rating="<?php echo $i; ?>">★</button>
                                <?php endfor; ?>
                            </div>
                            <input type="hidden" name="rating" id="rating-input" required>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Your Review</label>
                            <textarea name="comment" rows="4" class="w-full px-5 py-4 border border-gray-200 rounded-2xl focus:outline-none focus:border-gray-400 focus:ring-2 focus:ring-gray-100 transition resize-none text-sm" placeholder="Share your thoughts about this product..."></textarea>
                        </div>

                        <button type="submit" class="w-full bg-gray-900 text-white py-4 rounded-full font-semibold hover:bg-gray-800 transition">
                            Submit Review
                        </button>
                    </form>
                    <div id="review-message" class="mt-4 hidden"></div>
                </div>
            <?php else: ?>
                <div class="bg-blue-50/50 p-6 rounded-2xl border border-blue-100/50 mb-10 max-w-7xl mx-auto text-center">
                    <p class="text-blue-700 font-medium">You have already submitted a review for this product.</p>
                </div>
            <?php endif; ?>
        <?php else: ?>
            <div class="bg-gray-50 p-8 rounded-3xl mb-10 max-w-7xl mx-auto text-center">
                <p class="text-gray-600">
                    <a href="https://parlour.com/auth/login" class="font-semibold text-gray-900 hover:underline">Sign in</a> to write a review
                </p>
            </div>
        <?php endif; ?>

        <!-- Reviews List -->
        <?php if (empty($reviews)): ?>
            <div class="text-center py-16 bg-gray-50 rounded-3xl max-w-7xl mx-auto">
                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" /></svg>
                </div>
                <p class="text-gray-500">No reviews yet. Be the first to review this product!</p>
            </div>
        <?php else: ?>
            <div class="grid md:grid-cols-2 gap-6 max-w-7xl mx-auto">
                <?php foreach ($reviews as $review): ?>
                    <div class="review-card bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 bg-gray-100 rounded-full overflow-hidden flex-shrink-0">
                                <?php if (!empty($review['profileImage'])): ?>
                                    <img src="<?php echo htmlspecialchars($review['profileImage']); ?>" alt="<?php echo htmlspecialchars($review['userName']); ?>" class="w-full h-full object-cover">
                                <?php else: ?>
                                    <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-gray-200 to-gray-300 text-gray-600 font-semibold">
                                        <?php echo strtoupper(substr($review['userName'] ?? 'U', 0, 1)); ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between mb-1">
                                    <h4 class="font-semibold text-gray-900 truncate"><?php echo htmlspecialchars($review['userName'] ?? 'Anonymous'); ?></h4>
                                    <span class="text-xs text-gray-400 flex-shrink-0 ml-2"><?php echo date('M d, Y', strtotime($review['createdAt'])); ?></span>
                                </div>
                                <div class="flex gap-0.5 mb-3">
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <span class="text-sm <?php echo $i <= $review['rating'] ? 'text-amber-400' : 'text-gray-200'; ?>">★</span>
                                    <?php endfor; ?>
                                </div>
                                <?php if (!empty($review['comment'])): ?>
                                    <p class="text-gray-600 text-sm leading-relaxed"><?php echo nl2br(htmlspecialchars($review['comment'])); ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
// Image Zoom Logic
const wrapper = document.querySelector('.main-image-wrapper');
const image = document.querySelector('#main-image');

if (wrapper && image) {
    wrapper.addEventListener('mousemove', (e) => {
        const { left, top, width, height } = wrapper.getBoundingClientRect();
        const x = (e.clientX - left) / width * 100;
        const y = (e.clientY - top) / height * 100;
        image.style.transformOrigin = `${x}% ${y}%`;
        image.style.transform = 'scale(1.5)';
    });

    wrapper.addEventListener('mouseleave', () => {
        image.style.transform = 'scale(1)';
        setTimeout(() => {
            if (image.style.transform === 'scale(1)') {
                image.style.transformOrigin = 'center center';
            }
        }, 300);
    });
}

function changeMainImage(imageSrc, element) {
    document.getElementById('main-image').src = imageSrc;
    element.parentElement.querySelectorAll('button').forEach(btn => {
        btn.classList.remove('ring-gray-900', 'ring-2', 'opacity-100');
        btn.classList.add('ring-gray-200', 'ring-1', 'opacity-60');
    });
    element.classList.remove('ring-gray-200', 'ring-1', 'opacity-60');
    element.classList.add('ring-gray-900', 'ring-2', 'opacity-100');
}

// Star rating functionality
document.querySelectorAll('.star-btn').forEach(star => {
    star.addEventListener('click', function() {
        const rating = this.getAttribute('data-rating');
        document.getElementById('rating-input').value = rating;
        
        document.querySelectorAll('.star-btn').forEach((s, index) => {
            if (index < rating) {
                s.classList.remove('text-gray-300');
                s.classList.add('text-amber-400');
            } else {
                s.classList.remove('text-amber-400');
                s.classList.add('text-gray-300');
            }
        });
    });
});

// Add to cart form submission with AJAX
document.getElementById('add-to-cart-form')?.addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const button = this.querySelector('button[type="submit"]');
    const originalButtonText = button.innerHTML;
    const messageDiv = document.getElementById('cart-message');
    
    button.disabled = true;
    button.innerHTML = '<span>Adding...</span>';
    
    try {
        const response = await fetch('https://parlour.com/api/cart.php', {
            method: 'POST',
            body: formData
        });
        
        const data = await response.json();
        
        if (data.success) {
            messageDiv.className = 'p-4 bg-emerald-50 border border-emerald-200 rounded-2xl text-emerald-700 font-medium text-sm flex items-center gap-3';
            messageDiv.innerHTML = `
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                <span>Added to your bag!</span>
            `;
            messageDiv.classList.remove('hidden');
            
            const cartBadge = document.querySelector('.bg-gray-900.text-white.text-xs.font-bold.rounded-full');
            if (cartBadge) {
                cartBadge.textContent = data.cartCount;
            }
            
            setTimeout(() => {
                window.location.href = 'https://parlour.com/shop';
            }, 1000);
        } else {
            messageDiv.className = 'p-4 bg-rose-50 border border-rose-200 rounded-2xl text-rose-700 font-medium text-sm';
            messageDiv.textContent = data.message || 'Failed to add product to cart';
            messageDiv.classList.remove('hidden');
        }
    } catch (error) {
        messageDiv.className = 'p-4 bg-rose-50 border border-rose-200 rounded-2xl text-rose-700 font-medium text-sm';
        messageDiv.textContent = 'An error occurred. Please try again.';
        messageDiv.classList.remove('hidden');
    } finally {
        button.disabled = false;
        button.innerHTML = originalButtonText;
    }
});

// Review form submission
document.getElementById('review-form')?.addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const messageDiv = document.getElementById('review-message');
    
    if (!formData.get('rating')) {
        messageDiv.className = 'mt-4 p-4 bg-rose-50 border border-rose-200 rounded-2xl text-rose-700 text-sm';
        messageDiv.textContent = 'Please select a rating';
        messageDiv.classList.remove('hidden');
        return;
    }
    
    try {
        const response = await fetch('/api/submit_review.php', {
            method: 'POST',
            body: formData
        });
        
        const data = await response.json();
        
        if (data.success) {
            messageDiv.className = 'mt-4 p-4 bg-emerald-50 border border-emerald-200 rounded-2xl text-emerald-700 font-medium text-sm';
            messageDiv.textContent = data.message;
            messageDiv.classList.remove('hidden');
            
            this.reset();
            document.querySelectorAll('.star-btn').forEach(s => {
                s.classList.remove('text-amber-400');
                s.classList.add('text-gray-300');
            });
            
            setTimeout(() => {
                location.reload();
            }, 2000);
        } else {
            messageDiv.className = 'mt-4 p-4 bg-rose-50 border border-rose-200 rounded-2xl text-rose-700 text-sm';
            messageDiv.textContent = data.message;
            messageDiv.classList.remove('hidden');
        }
    } catch (error) {
        messageDiv.className = 'mt-4 p-4 bg-rose-50 border border-rose-200 rounded-2xl text-rose-700 text-sm';
        messageDiv.textContent = 'An error occurred. Please try again.';
        messageDiv.classList.remove('hidden');
    }
});
</script>

<?php include __DIR__ . '/includes/footer.php'; ?>
