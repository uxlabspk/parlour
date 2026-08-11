<?php
// php_web/scripts/seed.php
require_once __DIR__ . '/../includes/db.php';

echo "Seeding database...\n";

// Clear existing data (optional, but good for clean seed)
$pdo->exec("SET FOREIGN_KEY_CHECKS = 0");
$pdo->exec("TRUNCATE TABLE orderItems");
$pdo->exec("TRUNCATE TABLE orders");
$pdo->exec("TRUNCATE TABLE products");
$pdo->exec("TRUNCATE TABLE users");
$pdo->exec("SET FOREIGN_KEY_CHECKS = 1");

// 1. Create Admin User
$adminId = generateUUID();
$adminEmail = 'admin@parlour.com';
$adminPass = password_hash('admin123', PASSWORD_BCRYPT, ['cost' => 12]);
$stmt = $pdo->prepare("INSERT INTO users (id, email, password, name, role, emailVerified) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->execute([$adminId, $adminEmail, $adminPass, 'Admin User', 'ADMIN', 1]);
echo "Admin user created: $adminEmail / admin123\n";

// 2. Create Products
$products = [
    // Luxury Watches
    [
        'name' => 'Classic Chronograph Watch',
        'price' => 349.00,
        'originalPrice' => 449.00,
        'category' => 'Luxury Watches',
        'image' => 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=600&q=80',
        'description' => 'Elegant chronograph watch with stainless steel band and sapphire crystal.',
        'sale' => true,
        'featured' => true
    ],
    [
        'name' => 'Luxury Gold Watch',
        'price' => 899.00,
        'originalPrice' => null,
        'category' => 'Luxury Watches',
        'image' => 'https://images.unsplash.com/photo-1524592094714-0f0654e20314?w=600&q=80',
        'description' => 'Premium gold-plated watch with leather strap and Swiss movement.',
        'sale' => false,
        'featured' => true
    ],
    [
        'name' => 'Rose Gold Dress Watch',
        'price' => 425.00,
        'originalPrice' => null,
        'category' => 'Luxury Watches',
        'image' => 'https://images.unsplash.com/photo-1533139502658-0198f920d8e8?w=600&q=80',
        'description' => 'Elegant rose gold watch perfect for formal occasions.',
        'sale' => false,
        'featured' => true
    ],
    [
        'name' => 'Platinum Automatic Watch',
        'price' => 1299.00,
        'originalPrice' => 1499.00,
        'category' => 'Luxury Watches',
        'image' => 'https://images.unsplash.com/photo-1547996160-81dfa63595aa?w=600&q=80',
        'description' => 'Exquisite platinum automatic watch with exhibition caseback.',
        'sale' => true,
        'featured' => true
    ],
    
    // Sport Watches
    [
        'name' => 'Sport Dive Watch',
        'price' => 279.00,
        'originalPrice' => null,
        'category' => 'Sport Watches',
        'image' => 'https://images.unsplash.com/photo-1587836374828-4dbafa94cf0e?w=600&q=80',
        'description' => 'Water-resistant dive watch with rotating bezel and luminous hands.',
        'sale' => false,
        'featured' => true
    ],
    [
        'name' => 'Digital Sports Watch',
        'price' => 79.00,
        'originalPrice' => 99.00,
        'category' => 'Sport Watches',
        'image' => 'https://images.unsplash.com/photo-1542496658-e33a6d0d50f6?w=600&q=80',
        'description' => 'Durable digital watch with stopwatch and backlight features.',
        'sale' => true,
        'featured' => false
    ],
    [
        'name' => 'Tactical Military Watch',
        'price' => 159.00,
        'originalPrice' => null,
        'category' => 'Sport Watches',
        'image' => 'https://images.unsplash.com/photo-1614164185128-e4ec99c436d7?w=600&q=80',
        'description' => 'Rugged tactical watch with compass and multiple time zones.',
        'sale' => false,
        'featured' => false
    ],
    [
        'name' => 'Professional Pilot Watch',
        'price' => 549.00,
        'originalPrice' => 649.00,
        'category' => 'Sport Watches',
        'image' => 'https://images.unsplash.com/photo-1600721391776-b5cd0e0048a9?w=600&q=80',
        'description' => 'Aviation-inspired pilot watch with large dial and leather strap.',
        'sale' => true,
        'featured' => true
    ],
    
    // Smart Watches
    [
        'name' => 'Smart Fitness Watch',
        'price' => 249.00,
        'originalPrice' => null,
        'category' => 'Smart Watches',
        'image' => 'https://images.unsplash.com/photo-1579586337278-3befd40fd17a?w=600&q=80',
        'description' => 'Advanced fitness tracker with heart rate monitor and GPS.',
        'sale' => false,
        'featured' => true
    ],
    [
        'name' => 'Premium Smartwatch',
        'price' => 399.00,
        'originalPrice' => 499.00,
        'category' => 'Smart Watches',
        'image' => 'https://images.unsplash.com/photo-1546868871-7041f2a55e12?w=600&q=80',
        'description' => 'Premium smartwatch with AMOLED display and health tracking.',
        'sale' => true,
        'featured' => true
    ],
    [
        'name' => 'Hybrid Smartwatch',
        'price' => 189.00,
        'originalPrice' => null,
        'category' => 'Smart Watches',
        'image' => 'https://images.unsplash.com/photo-1557438159-51eec7a6c9e8?w=600&q=80',
        'description' => 'Elegant hybrid watch combining classic design with smart features.',
        'sale' => false,
        'featured' => false
    ],
    
    // Casual Watches
    [
        'name' => 'Minimalist Quartz Watch',
        'price' => 129.00,
        'originalPrice' => 179.00,
        'category' => 'Casual Watches',
        'image' => 'https://images.unsplash.com/photo-1508685096489-7aacd43bd3b1?w=600&q=80',
        'description' => 'Sleek minimalist design with mesh band and ultra-thin case.',
        'sale' => true,
        'featured' => true
    ],
    [
        'name' => 'Vintage Leather Watch',
        'price' => 189.00,
        'originalPrice' => 229.00,
        'category' => 'Casual Watches',
        'image' => 'https://images.unsplash.com/photo-1522312346375-d1a52e2b99b3?w=600&q=80',
        'description' => 'Vintage-inspired timepiece with genuine leather strap.',
        'sale' => true,
        'featured' => false
    ],
    [
        'name' => 'Casual Canvas Watch',
        'price' => 89.00,
        'originalPrice' => null,
        'category' => 'Casual Watches',
        'image' => 'https://images.unsplash.com/photo-1524805444758-089113d48a6d?w=600&q=80',
        'description' => 'Comfortable canvas strap watch with easy-read dial.',
        'sale' => false,
        'featured' => false
    ],
    [
        'name' => 'Casual Canvas Watch',
        'price' => 89.00,
        'originalPrice' => null,
        'category' => 'Casual Watches',
        'image' => 'https://images.unsplash.com/photo-1524805444758-089113d48a6d?w=600&q=80',
        'description' => 'Comfortable canvas strap watch with easy-read dial.',
        'sale' => false,
        'featured' => false
    ],
    
    // Watch Accessories
    [
        'name' => 'Leather Watch Band',
        'price' => 45.00,
        'originalPrice' => 59.00,
        'category' => 'Watch Accessories',
        'image' => 'https://images.unsplash.com/photo-1617625802912-cde586faf331?w=600&q=80',
        'description' => 'Premium genuine leather replacement watch band in various sizes.',
        'sale' => true,
        'featured' => false
    ],
    [
        'name' => 'Stainless Steel Watch Band',
        'price' => 69.00,
        'originalPrice' => null,
        'category' => 'Watch Accessories',
        'image' => 'https://images.unsplash.com/photo-1614164185128-e4ec99c436d7?w=600&q=80',
        'description' => 'Elegant stainless steel mesh band with quick-release mechanism.',
        'sale' => false,
        'featured' => false
    ],
    [
        'name' => 'Luxury Watch Box',
        'price' => 129.00,
        'originalPrice' => null,
        'category' => 'Watch Accessories',
        'image' => 'https://images.unsplash.com/photo-1611930022073-b7a4ba5fcccd?w=600&q=80',
        'description' => 'Premium wooden watch storage box with velvet interior for 6 watches.',
        'sale' => false,
        'featured' => true
    ],
    [
        'name' => 'Watch Winder',
        'price' => 199.00,
        'originalPrice' => 249.00,
        'category' => 'Watch Accessories',
        'image' => 'https://images.unsplash.com/photo-1509941943102-10c232535736?w=600&q=80',
        'description' => 'Automatic watch winder for 2 watches with quiet motor.',
        'sale' => true,
        'featured' => true
    ],
    [
        'name' => 'Watch Cleaning Kit',
        'price' => 29.00,
        'originalPrice' => null,
        'category' => 'Watch Accessories',
        'image' => 'https://images.unsplash.com/photo-1586170848607-a3c4d66fe7d4?w=600&q=80',
        'description' => 'Complete watch cleaning and maintenance kit with tools.',
        'sale' => false,
        'featured' => false
    ],
    [
        'name' => 'NATO Watch Strap',
        'price' => 25.00,
        'originalPrice' => 35.00,
        'category' => 'Watch Accessories',
        'image' => 'https://images.unsplash.com/photo-1622434641406-a158123450f9?w=600&q=80',
        'description' => 'Durable nylon NATO strap in multiple colors.',
        'sale' => true,
        'featured' => false
    ],
    [
        'name' => 'Watch Travel Case',
        'price' => 49.00,
        'originalPrice' => null,
        'category' => 'Watch Accessories',
        'image' => 'https://images.unsplash.com/photo-1553062407-98eeb64c6a62?w=600&q=80',
        'description' => 'Compact leather travel case for 4 watches with zipper closure.',
        'sale' => false,
        'featured' => false
    ],
    [
        'name' => 'Watch Display Stand',
        'price' => 39.00,
        'originalPrice' => null,
        'category' => 'Watch Accessories',
        'image' => 'https://images.unsplash.com/photo-1563291074-2bf8677ac0e5?w=600&q=80',
        'description' => 'Elegant acrylic display stand for showcasing your watch collection.',
        'sale' => false,
        'featured' => false
    ]
];

$stmtProd = $pdo->prepare("INSERT INTO products (id, name, price, originalPrice, category, image, description, sale, featured, sizes, colors) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
foreach ($products as $p) {
    $sizes = json_encode(['S', 'M', 'L', 'XL']);
    $colors = json_encode(['Black', 'Navy', 'Grey']);
    $stmtProd->execute([
        generateUUID(),
        $p['name'],
        $p['price'],
        $p['originalPrice'],
        $p['category'],
        $p['image'],
        $p['description'],
        $p['sale'] ? 1 : 0,
        $p['featured'] ? 1 : 0,
        $sizes,
        $colors
    ]);
}

echo "Products seeded successfully!\n";
echo "Done.\n";
?>
