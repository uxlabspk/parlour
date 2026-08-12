<?php
// php_web/shop.php
include __DIR__ . '/includes/header.php';

$category = $_GET['category'] ?? null;
$search = $_GET['search'] ?? null;
$priceRange = $_GET['price'] ?? null;

$query = "SELECT * FROM products WHERE 1=1";
$params = [];

if ($category) {
    $query .= " AND category = ?";
    $params[] = $category;
}

if ($search) {
    $query .= " AND (name LIKE ? OR description LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

if ($priceRange) {
    switch ($priceRange) {
        case 'under-50':
            $query .= " AND price < 50";
            break;
        case '50-100':
            $query .= " AND price BETWEEN 50 AND 100";
            break;
        case '100-200':
            $query .= " AND price BETWEEN 100 AND 200";
            break;
        case '200-500':
            $query .= " AND price BETWEEN 200 AND 500";
            break;
        case 'over-500':
            $query .= " AND price > 500";
            break;
    }
}

$query .= " ORDER BY createdAt DESC";
$stmt = $pdo->prepare($query);
$stmt->execute($params);
$products = $stmt->fetchAll();

// Fetch featured products for the Essential Picks section
$stmtFeatured = $pdo->prepare("SELECT * FROM products ORDER BY featured DESC, createdAt DESC LIMIT 5");
$stmtFeatured->execute();
$featured_products = $stmtFeatured->fetchAll();
?>

<!-- Modern Shop Page Styles -->
<style>
    .filter-chip {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .filter-chip:hover {
        transform: translateY(-2px);
    }
    .product-card {
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .product-card:hover {
        transform: translateY(-8px);
    }
    .product-card:hover .product-image {
        transform: scale(1.05);
    }
    .product-image {
        transition: transform 0.7s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .sidebar-link {
        position: relative;
        transition: all 0.3s ease;
    }
    .sidebar-link::before {
        content: '';
        position: absolute;
        left: 0;
        top: 50%;
        transform: translateY(-50%);
        width: 0;
        height: 70%;
        background: #111;
        border-radius: 0 4px 4px 0;
        transition: width 0.3s ease;
    }
    .sidebar-link:hover::before,
    .sidebar-link.active::before {
        width: 3px;
    }
</style>

<!-- Hero Banner -->
<div class="relative h-[60vh] min-h-[500px] flex items-center justify-center overflow-hidden">
    <div class="absolute inset-0 -z-10">
        <img 
            src="https://images.unsplash.com/photo-1560066984-138dadb4c035?w=1920&q=85" 
            alt="Shop Beauty Services" 
            class="w-full h-full object-cover"
        />
        <div class="absolute inset-0 bg-gradient-to-r from-black/70 via-black/50 to-transparent"></div>
    </div>
    
    <div class="relative z-10 text-center px-6">
        <span class="inline-block text-sm font-semibold text-white/70 tracking-[0.3em] uppercase mb-4">Discover</span>
        <h1 class="text-5xl lg:text-6xl font-light text-white mb-4 tracking-tight">
            Our <span class="font-semibold">Services</span>
        </h1>
        <p class="text-lg text-white/70 max-w-xl mx-auto">
            <?php if ($category): ?>
                Explore our <?php echo htmlspecialchars($category); ?> services
            <?php elseif ($search): ?>
                Search results for "<?php echo htmlspecialchars($search); ?>"
            <?php else: ?>
                Curated beauty services designed for timeless elegance
            <?php endif; ?>
        </p>
    </div>
</div>

<div class="bg-rose-50">
    <div class="max-w-7xl mx-auto px-6 lg:px-8 py-12 lg:py-16">
    <!-- Search Bar -->
    <div class="mb-12">
        <form action="http://localhost:8080/shop.php" method="GET" class="flex flex-col sm:flex-row gap-4 max-w-2xl mx-auto">
            <?php if ($category): ?>
                <input type="hidden" name="category" value="<?php echo htmlspecialchars($category); ?>">
            <?php endif; ?>
            <?php if ($priceRange): ?>
                <input type="hidden" name="price" value="<?php echo htmlspecialchars($priceRange); ?>">
            <?php endif; ?>
            <div class="relative flex-1">
                <input 
                    type="text" 
                    name="search" 
                    value="<?php echo htmlspecialchars($search ?? ''); ?>" 
                    placeholder="Search for products..." 
                    class="w-full px-6 py-4 pl-14 rounded-full border border-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-900/10 focus:border-gray-300 transition-all text-base"
                >
                <svg class="absolute left-5 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>
            <button type="submit" class="bg-gray-900 text-white px-8 py-4 rounded-full font-semibold hover:bg-gray-800 transition-all whitespace-nowrap">
                Search
            </button>
        </form>
    </div>

    <!-- Mobile Filter Toggle -->
    <button id="filterToggle" class="lg:hidden mb-6 flex items-center gap-3 px-5 py-3 bg-gray-100 hover:bg-gray-200 rounded-full transition-all font-medium">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
        </svg>
        <span>Filters</span>
        <?php if ($priceRange || $category): ?>
            <span class="bg-gray-900 text-white text-xs px-2.5 py-1 rounded-full font-semibold"><?php echo ($priceRange ? 1 : 0) + ($category ? 1 : 0); ?></span>
        <?php endif; ?>
    </button>

    <div class="flex gap-12">
        <!-- Sidebar Filters -->
        <aside id="filterSidebar" class="fixed lg:sticky top-0 left-0 h-screen lg:h-auto w-80 lg:w-64 bg-white lg:bg-transparent z-50 lg:z-auto transform -translate-x-full lg:translate-x-0 transition-transform duration-300 overflow-y-auto border-r lg:border-0 border-gray-100 lg:top-32 lg:self-start">
            <div class="p-8 lg:p-0 space-y-8">
                <!-- Filter Header -->
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-semibold tracking-tight">Filters</h2>
                    <button id="closeSidebar" class="lg:hidden p-2 hover:bg-gray-100 rounded-full transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <!-- Active Filters -->
                <?php if ($priceRange || $category || $search): ?>
                    <div class="pb-6 border-b border-gray-100">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Active Filters</h3>
                            <a href="http://localhost:8080/shop.php" class="text-xs text-gray-900 hover:underline font-medium">Clear All</a>
                        </div>
                        <div class="flex flex-wrap gap-2">
                            <?php if ($category): ?>
                                <a href="http://localhost:8080/shop.php?<?php echo http_build_query(array_filter(['price' => $priceRange, 'search' => $search])); ?>" class="filter-chip inline-flex items-center gap-2 px-4 py-2 bg-gray-900 text-white rounded-full text-sm font-medium hover:bg-gray-800">
                                    <span><?php echo htmlspecialchars($category); ?></span>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </a>
                            <?php endif; ?>
                            <?php if ($priceRange): ?>
                                <a href="http://localhost:8080/shop.php?<?php echo http_build_query(array_filter(['category' => $category, 'search' => $search])); ?>" class="filter-chip inline-flex items-center gap-2 px-4 py-2 bg-gray-900 text-white rounded-full text-sm font-medium hover:bg-gray-800">
                                    <span><?php 
                                        $priceLabels = [
                                            'under-50' => 'Under PKR 50',
                                            '50-100' => 'PKR 50-100',
                                            '100-200' => 'PKR 100-200',
                                            '200-500' => 'PKR 200-500',
                                            'over-500' => 'Over PKR 500'
                                        ];
                                        echo $priceLabels[$priceRange] ?? $priceRange;
                                    ?></span>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </a>
                            <?php endif; ?>
                            <?php if ($search): ?>
                                <a href="http://localhost:8080/shop.php?<?php echo http_build_query(array_filter(['category' => $category, 'price' => $priceRange])); ?>" class="filter-chip inline-flex items-center gap-2 px-4 py-2 bg-gray-900 text-white rounded-full text-sm font-medium hover:bg-gray-800">
                                    <span>"<?php echo htmlspecialchars(substr($search, 0, 15)); ?><?php echo strlen($search) > 15 ? '...' : ''; ?>"</span>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Category Filter -->
                <div class="pb-6 border-b border-gray-100">
                    <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-4">Category</h3>
                    <div class="space-y-1">
                        <?php
                        $categories = ['Hair Services', 'Skin Treatments', 'Nail Art', 'Bridal Packages', 'Beauty Products'];
                        foreach ($categories as $cat):
                        ?>
                            <a href="http://localhost:8080/shop.php?<?php echo http_build_query(array_filter(['category' => $cat, 'price' => $priceRange, 'search' => $search])); ?>" 
                               class="sidebar-link <?php echo $category === $cat ? 'active' : ''; ?> block px-4 py-3 rounded-xl text-sm <?php echo $category === $cat ? 'bg-gray-900 text-white font-semibold' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900'; ?> transition-all">
                                <?php echo $cat; ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Price Range Filter -->
                <div>
                    <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-4">Price Range</h3>
                    <div class="space-y-1">
                        <?php
                        $priceRanges = [
                            'under-200' => 'Under PKR 2000',
                            '2500-3000' => 'PKR 2500 - PKR 3000',
                            '3000-3500' => 'PKR 3000 - PKR 3500',
                            '3500-4000' => 'PKR 3500 - PKR 4000',
                            'over-5000' => 'Over PKR 5000'
                        ];
                        foreach ($priceRanges as $value => $label):
                        ?>
                            <a href="http://localhost:8080/shop.php?<?php echo http_build_query(array_filter(['category' => $category, 'price' => $value, 'search' => $search])); ?>" 
                               class="sidebar-link <?php echo $priceRange === $value ? 'active' : ''; ?> block px-4 py-3 rounded-xl text-sm <?php echo $priceRange === $value ? 'bg-gray-900 text-white font-semibold' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900'; ?> transition-all">
                                <?php echo $label; ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Overlay for mobile -->
        <div id="filterOverlay" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-40 lg:hidden opacity-0 pointer-events-none transition-opacity duration-300"></div>

        <!-- Main Content -->
        <div class="flex-1 min-w-0">
            <!-- Results Count -->
            <?php if (!empty($products)): ?>
                <div class="flex items-center justify-between mb-8">
                    <p class="text-gray-500">
                        Showing <span class="font-semibold text-gray-900"><?php echo count($products); ?></span> products
                    </p>
                </div>
            <?php endif; ?>

            <!-- Products Grid -->
            <?php if (empty($products)): ?>
                <div class="text-center py-24 bg-gray-50 rounded-3xl">
                    <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">No products found</h3>
                    <p class="text-gray-500 mb-6">Try adjusting your filters or search term</p>
                    <a href="http://localhost:8080/shop.php" class="inline-flex items-center gap-2 text-gray-900 font-semibold hover:underline">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        View all products
                    </a>
                </div>
            <?php else: ?>
                <div class="grid grid-cols-2 lg:grid-cols-3 gap-4 lg:gap-8">
                    <?php foreach ($products as $product): 
                        $displayPrice = $product['discountedPrice'] ?? $product['price'];
                        $hasDiscount = !empty($product['discountedPrice']) && $product['discountedPrice'] < $product['price'];
                    ?>
                        <a href="http://localhost:8080/product.php?id=<?php echo $product['id']; ?>" class="product-card group block">
                            <div class="relative aspect-[3/4] rounded-2xl lg:rounded-3xl overflow-hidden mb-4 bg-gray-100">
                                <img 
                                    src="<?php echo htmlspecialchars($product['image']); ?>" 
                                    alt="<?php echo htmlspecialchars($product['name']); ?>" 
                                    class="product-image w-full h-full object-cover"
                                    loading="lazy"
                                >
                                
                                <!-- Overlay on Hover -->
                                <div class="absolute inset-0 bg-gradient-to-t from-black/50 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                                
                                <!-- Badges -->
                                <div class="absolute top-3 lg:top-4 left-3 lg:left-4 flex flex-col gap-2">
                                    <?php if ($product['sale']): ?>
                                        <span class="bg-rose-500 text-white text-[10px] lg:text-xs font-semibold px-2.5 lg:px-3 py-1 rounded-full">Sale</span>
                                    <?php endif; ?>
                                    <?php if ($product['featured']): ?>
                                        <span class="bg-amber-500 text-white text-[10px] lg:text-xs font-semibold px-2.5 lg:px-3 py-1 rounded-full">Featured</span>
                                    <?php endif; ?>
                                </div>

                                <!-- Quick View -->
                                <div class="absolute bottom-4 left-4 right-4 opacity-0 group-hover:opacity-100 translate-y-4 group-hover:translate-y-0 transition-all duration-500 hidden lg:block">
                                    <span class="w-full bg-white text-gray-900 py-3 rounded-full font-semibold text-sm flex items-center justify-center">
                                        Quick View
                                    </span>
                                </div>
                            </div>
                            
                            <div class="space-y-1.5">
                                <p class="text-[10px] lg:text-xs text-gray-400 uppercase tracking-[0.15em] font-medium"><?php echo htmlspecialchars($product['category']); ?></p>
                                <h3 class="text-sm lg:text-base font-semibold text-gray-900 truncate group-hover:text-gray-600 transition-colors"><?php echo htmlspecialchars($product['name']); ?></h3>
                                <div class="flex items-center gap-2 flex-wrap">
                                    <span class="text-sm lg:text-base font-semibold text-gray-900">PKR <?php echo number_format($displayPrice, 0); ?></span>
                                    <?php if ($hasDiscount): ?>
                                        <span class="text-xs lg:text-sm text-gray-400 line-through">PKR <?php echo number_format($product['price'], 0); ?></span>
                                    <?php elseif ($product['originalPrice']): ?>
                                        <span class="text-xs lg:text-sm text-gray-400 line-through">PKR <?php echo number_format($product['originalPrice'], 0); ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <!-- Add to Cart Button -->
                                <button 
                                    onclick="addToCart(<?php echo $product['id']; ?>, '<?php echo htmlspecialchars($product['name'], ENT_QUOTES); ?>', <?php echo $displayPrice; ?>, '<?php echo htmlspecialchars($product['image'], ENT_QUOTES); ?>')"
                                    class="mt-3 w-full group relative overflow-hidden bg-gray-900 hover:bg-gray-800 text-white text-xs lg:text-sm font-semibold py-2.5 lg:py-3 px-4 rounded-full transition-all duration-300 transform hover:scale-105 hover:shadow-xl flex items-center justify-center gap-2"
                                >
                                    <svg class="w-4 h-4 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                                    </svg>
                                    <span class="relative z-10">Add to Cart</span>
                                    <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/20 to-transparent -translate-x-full group-hover:translate-x-full transition-transform duration-700"></div>
                                </button>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
</div>

<!-- Customer Reviews Section -->
<section class="py-24 lg:py-32 overflow-hidden bg-rose-50">
    <div class="max-w-7xl mx-auto px-6 lg:px-8">
        <div class="text-center mb-16">
            <span class="inline-block text-sm font-semibold text-gray-500 tracking-[0.3em] uppercase mb-4">Testimonials</span>
            <h2 class="text-4xl lg:text-5xl font-light text-gray-900 mb-6 tracking-tight">
                What Customers <span class="font-semibold">Say</span>
            </h2>
            <div class="flex justify-center gap-1">
                <?php for($i=0; $i<5; $i++): ?>
                    <svg class="w-5 h-5 text-amber-400 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                <?php endfor; ?>
            </div>
        </div>

        <div class="relative">
            <div id="reviewSlider" class="flex transition-transform duration-500 ease-out gap-6">
                <?php 
                $reviews = [
                    ['name' => 'Sarah Johnson', 'role' => 'Beauty Enthusiast', 'text' => 'The hair styling service is exceptional. Expert precision at its finest. Definitely worth the investment.'],
                    ['name' => 'Michael Chen', 'role' => 'Business Executive', 'text' => 'The skin treatment perfectly blends relaxation with results. Best experience I\'ve had this year.'],
                    ['name' => 'Emma Williams', 'role' => 'Fitness Trainer', 'text' => 'Customer service was incredibly helpful. The nail art is perfect for my training sessions!'],
                    ['name' => 'James Wilson', 'role' => 'Investment Banker', 'text' => 'Premium service and perfect results. The hair treatment elevates my professional look.'],
                    ['name' => 'Olivia Brown', 'role' => 'Lifestyle Blogger', 'text' => 'I love the relaxing atmosphere. The services are versatile and work for any occasion, day or night.'],
                    ['name' => 'David Miller', 'role' => 'Architect', 'text' => 'The bridal package is both comprehensive and stunning. Robust service quality with attention to detail.'],
                ];
                foreach ($reviews as $review): 
                ?>
                    <div class="min-w-[100%] md:min-w-[calc(50%-12px)] lg:min-w-[calc(33.333%-16px)]">
                        <div class="bg-gray-50 p-8 rounded-3xl h-full flex flex-col border border-gray-100">
                            <div class="flex gap-1 mb-4">
                                <?php for($i=0; $i<5; $i++): ?>
                                    <svg class="w-4 h-4 text-amber-400 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                <?php endfor; ?>
                            </div>
                            <p class="text-gray-600 leading-relaxed mb-6 flex-grow">"<?php echo $review['text']; ?>"</p>
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-gray-200 rounded-full flex items-center justify-center">
                                    <span class="text-sm font-semibold text-gray-600"><?php echo substr($review['name'], 0, 1); ?></span>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-900 text-sm"><?php echo $review['name']; ?></h4>
                                    <p class="text-xs text-gray-500"><?php echo $review['role']; ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="flex justify-center mt-10 gap-3">
                <button id="revPrev" class="w-11 h-11 rounded-full bg-white border border-gray-200 flex items-center justify-center hover:bg-gray-900 hover:text-white hover:border-gray-900 transition-all duration-300 shadow-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                </button>
                <button id="revNext" class="w-11 h-11 rounded-full bg-white border border-gray-200 flex items-center justify-center hover:bg-gray-900 hover:text-white hover:border-gray-900 transition-all duration-300 shadow-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </button>
            </div>
        </div>
    </div>
</section>

<!-- Filter Sidebar Script -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const filterToggle = document.getElementById('filterToggle');
    const filterSidebar = document.getElementById('filterSidebar');
    const filterOverlay = document.getElementById('filterOverlay');
    const closeSidebar = document.getElementById('closeSidebar');

    function openFilters() {
        filterSidebar.classList.remove('-translate-x-full');
        filterOverlay.classList.remove('opacity-0', 'pointer-events-none');
        document.body.style.overflow = 'hidden';
    }

    function closeFilters() {
        filterSidebar.classList.add('-translate-x-full');
        filterOverlay.classList.add('opacity-0', 'pointer-events-none');
        document.body.style.overflow = '';
    }

    filterToggle?.addEventListener('click', openFilters);
    closeSidebar?.addEventListener('click', closeFilters);
    filterOverlay?.addEventListener('click', closeFilters);

    // Review Slider
    const slider = document.getElementById('reviewSlider');
    const prev = document.getElementById('revPrev');
    const next = document.getElementById('revNext');
    let current = 0;
    const total = 6;

    function updateSlider() {
        const isMobile = window.innerWidth < 768;
        const isTablet = window.innerWidth >= 768 && window.innerWidth < 1024;
        let visible = isMobile ? 1 : (isTablet ? 2 : 3);
        const max = total - visible;
        if (current > max) current = max;
        if (current < 0) current = 0;
        const gap = 24;
        const width = slider.parentElement.offsetWidth;
        const itemWidth = (width - (gap * (visible - 1))) / visible;
        slider.style.transform = `translateX(-${current * (itemWidth + gap)}px)`;
    }

    next?.addEventListener('click', () => {
        const visible = window.innerWidth < 768 ? 1 : (window.innerWidth < 1024 ? 2 : 3);
        current = current < total - visible ? current + 1 : 0;
        updateSlider();
    });

    prev?.addEventListener('click', () => {
        const visible = window.innerWidth < 768 ? 1 : (window.innerWidth < 1024 ? 2 : 3);
        current = current > 0 ? current - 1 : total - visible;
        updateSlider();
    });

    window.addEventListener('resize', updateSlider);
    updateSlider();
});
</script>

<?php include __DIR__ . '/includes/footer.php'; ?>
