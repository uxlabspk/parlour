<?php
// php_web/includes/header.php
require_once __DIR__ . '/db.php';

$cartCount = 0;
if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        $cartCount += $item['quantity'];
    }
}

$userInitial = 'U';
$userProfileImage = null;
if (isLoggedIn()) {
    $userInitial = strtoupper(substr($_SESSION['name'] ?? $_SESSION['email'], 0, 1));
    // Fetch user profile image from database
    $stmt = $pdo->prepare("SELECT profileImage FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $userData = $stmt->fetch();
    if ($userData && !empty($userData['profileImage'])) {
        $userProfileImage = $userData['profileImage'];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Primary Meta Tags -->
    <title>Parlour - Premium Watches & Timepieces</title>
    <meta name="title" content="Parlour - Premium Watches & Timepieces">
    <meta name="description" content="Discover Parlour' curated collection of premium watches. Shop luxury timepieces, sport watches, smart watches, and watch accessories. Free shipping on orders over PKR 100.">
    <meta name="keywords" content="watches, luxury watches, sport watches, smart watches, timepieces, watch accessories, online watch store, premium watches, parlour">
    <meta name="author" content="Parlour">
    <meta name="robots" content="index, follow">
    
    <!-- Canonical URL -->
    <link rel="canonical" href="https://parlour.com<?php echo $_SERVER['REQUEST_URI']; ?>">
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://parlour.com<?php echo $_SERVER['REQUEST_URI']; ?>">
    <meta property="og:title" content="Parlour - Premium Watches & Timepieces">
    <meta property="og:description" content="Discover Parlour' curated collection of premium watches. Shop luxury timepieces, sport watches, and smart watches.">
    <meta property="og:image" content="https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=1200&h=630&q=80&fit=crop">
    <meta property="og:site_name" content="Parlour">
    
    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="https://parlour.com<?php echo $_SERVER['REQUEST_URI']; ?>">
    <meta property="twitter:title" content="Parlour - Premium Watches & Timepieces">
    <meta property="twitter:description" content="Discover Parlour' curated collection of premium watches. Shop luxury timepieces, sport watches, and smart watches.">
    <meta property="twitter:image" content="https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=1200&h=630&q=80&fit=crop">
    
    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="https://parlour.com/assets/images/favicon.svg">
    <link rel="icon" type="image/x-icon" href="https://parlour.com/assets/images/favicon.svg">
    <link rel="apple-touch-icon" href="https://parlour.com/assets/images/favicon.svg">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://parlour.com/assets/css/style.css">
    <style>
        @keyframes slide-up {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes fade-in {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        .animate-slide-up { animation: slide-up 0.3s ease-out; }
        .animate-fade-in { animation: fade-in 0.2s ease-out; }
        .nav-link { position: relative; }
        .nav-link::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 0;
            height: 1px;
            background-color: #111;
            transition: width 0.3s ease;
        }
        .nav-link:hover::after { width: 100%; }
    </style>
</head>
<body class="bg-white text-gray-900 antialiased">
    <nav class="fixed top-6 left-1/2 -translate-x-1/2 z-50 w-[92%] max-w-5xl">
        <div class="bg-white/95 backdrop-blur-md rounded-2xl shadow-lg shadow-gray-900/5 px-6 py-4 flex items-center justify-between border border-gray-100/80">
            <!-- Logo -->
            <a href="https://parlour.com/" class="flex items-center gap-2 group" aria-label="Parlour Home - Premium Fashion Store">
                <img 
                    src="https://parlour.com/assets/images/logo.png" 
                    alt="Parlour Logo - Premium Watches & Timepieces" 
                    title="Parlour - Your Destination for Luxury Watches"
                    width="60" 
                    height="60"
                    class="transition-transform duration-300 group-hover:scale-105"
                    loading="eager"
                />
                <span class="text-xl font-light tracking-tight text-gray-900">
                    <span class="font-semibold">Essentials</span>
                </span>
            </a>
            
            <!-- Desktop Navigation -->
            <div class="hidden lg:flex items-center gap-8">
                <a href="https://parlour.com/" class="nav-link text-sm text-gray-600 hover:text-gray-900 transition-colors duration-200">Home</a>
                <a href="https://parlour.com/collections" class="nav-link text-sm text-gray-600 hover:text-gray-900 transition-colors duration-200">Collections</a>
                <a href="https://parlour.com/shop" class="nav-link text-sm text-gray-600 hover:text-gray-900 transition-colors duration-200">Shop</a>
                <a href="https://parlour.com/about" class="nav-link text-sm text-gray-600 hover:text-gray-900 transition-colors duration-200">About</a>
                <a href="https://parlour.com/contact" class="nav-link text-sm text-gray-600 hover:text-gray-900 transition-colors duration-200">Contact</a>
            </div>
            
            <!-- Right Actions -->
            <div class="flex items-center gap-2">
                <!-- Cart -->
                <a href="https://parlour.com/cart" class="relative p-2.5 hover:bg-gray-100 rounded-xl transition-colors duration-200 group">
                    <svg class="w-5 h-5 text-gray-600 group-hover:text-gray-900 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                    <?php if ($cartCount > 0): ?>
                    <span class="absolute -top-0.5 -right-0.5 bg-gray-900 text-white text-[10px] font-semibold rounded-full w-4.5 h-4.5 flex items-center justify-center min-w-[18px] px-1">
                        <?php echo $cartCount; ?>
                    </span>
                    <?php endif; ?>
                </a>

                <?php if (isLoggedIn()): ?>
                    <!-- User Dropdown -->
                    <div class="relative group">
                        <button class="flex items-center gap-2 p-1.5 hover:bg-gray-100 rounded-xl transition-colors duration-200">
                            <div class="w-8 h-8 bg-gray-900 text-white rounded-xl flex items-center justify-center font-medium text-sm overflow-hidden">
                                <?php if ($userProfileImage): ?>
                                    <img src="<?php echo htmlspecialchars($userProfileImage); ?>?t=<?php echo time(); ?>" alt="Profile" class="w-full h-full object-cover">
                                <?php else: ?>
                                    <?php echo $userInitial; ?>
                                <?php endif; ?>
                            </div>
                            <svg class="w-4 h-4 text-gray-400 hidden md:block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        <div class="absolute right-0 mt-3 w-60 bg-white rounded-2xl shadow-xl shadow-gray-900/10 border border-gray-100 py-2 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 animate-fade-in">
                            <div class="px-4 py-3 border-b border-gray-100">
                                <p class="text-sm font-medium text-gray-900">Hi, <?php echo htmlspecialchars($_SESSION['name'] ?? 'User'); ?></p>
                                <p class="text-xs text-gray-500 mt-0.5"><?php echo htmlspecialchars($_SESSION['email'] ?? ''); ?></p>
                            </div>
                            <?php if (isManager()): ?>
                                <a href="https://parlour.com/admin" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-600 hover:text-gray-900 hover:bg-gray-50 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    Admin Panel
                                </a>
                            <?php endif; ?>
                            <a href="https://parlour.com/profile" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-600 hover:text-gray-900 hover:bg-gray-50 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                My Profile
                            </a>
                            <?php if (!isManager()): ?>
                                <a href="https://parlour.com/profile?tab=orders" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-600 hover:text-gray-900 hover:bg-gray-50 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                    </svg>
                                    My Orders
                                </a>
                            <?php endif; ?>
                            <div class="border-t border-gray-100 mt-2 pt-2">
                                <a href="https://parlour.com/auth/logout" class="flex items-center gap-3 px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                    </svg>
                                    Sign Out
                                </a>
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <a href="https://parlour.com/auth/login" class="hidden md:flex items-center gap-2 px-5 py-2.5 bg-gray-900 text-white text-sm font-medium rounded-xl hover:bg-gray-800 transition-colors duration-200">
                        Sign In
                    </a>
                <?php endif; ?>

                <!-- Mobile Menu Button -->
                <button id="mobileMenuBtn" class="lg:hidden p-2.5 hover:bg-gray-100 rounded-xl transition-colors duration-200">
                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>
        </div>
        
        <!-- Mobile Menu Dropdown -->
        <div id="mobileMenu" class="hidden lg:hidden absolute top-full left-0 right-0 mt-3 bg-white rounded-2xl shadow-xl shadow-gray-900/10 border border-gray-100 overflow-hidden animate-slide-up">
            <div class="py-2">
                <a href="https://parlour.com/" class="flex items-center gap-3 px-5 py-3.5 text-gray-600 hover:text-gray-900 hover:bg-gray-50 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    Home
                </a>
                <a href="https://parlour.com/collections" class="flex items-center gap-3 px-5 py-3.5 text-gray-600 hover:text-gray-900 hover:bg-gray-50 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                    Collections
                </a>
                <a href="https://parlour.com/shop" class="flex items-center gap-3 px-5 py-3.5 text-gray-600 hover:text-gray-900 hover:bg-gray-50 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                    </svg>
                    Shop
                </a>
                <a href="https://parlour.com/about" class="flex items-center gap-3 px-5 py-3.5 text-gray-600 hover:text-gray-900 hover:bg-gray-50 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    About
                </a>
                <a href="https://parlour.com/contact" class="flex items-center gap-3 px-5 py-3.5 text-gray-600 hover:text-gray-900 hover:bg-gray-50 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    Contact
                </a>
                <?php if (!isLoggedIn()): ?>
                    <div class="border-t border-gray-100 mt-2 pt-2 px-4 pb-3">
                        <a href="https://parlour.com/auth/login" class="flex items-center justify-center gap-2 w-full py-3 bg-gray-900 text-white text-sm font-medium rounded-xl hover:bg-gray-800 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                            </svg>
                            Sign In
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </nav>
    
    <script>
        // Mobile menu toggle
        document.addEventListener('DOMContentLoaded', function() {
            const mobileMenuBtn = document.getElementById('mobileMenuBtn');
            const mobileMenu = document.getElementById('mobileMenu');
            
            if (mobileMenuBtn && mobileMenu) {
                mobileMenuBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    mobileMenu.classList.toggle('hidden');
                });
                
                // Close menu when clicking outside
                document.addEventListener('click', function(e) {
                    if (!mobileMenu.contains(e.target) && !mobileMenuBtn.contains(e.target)) {
                        mobileMenu.classList.add('hidden');
                    }
                });
                
                // Close menu when clicking a link
                mobileMenu.querySelectorAll('a').forEach(link => {
                    link.addEventListener('click', function() {
                        mobileMenu.classList.add('hidden');
                    });
                });
            }
        });
    </script>
    
    <main class="min-h-screen">
