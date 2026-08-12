<?php
require_once __DIR__ . '/../includes/db.php';

echo "Seeding database...\n";

$pdo->exec("SET FOREIGN_KEY_CHECKS = 0");
$pdo->exec("TRUNCATE TABLE orderItems");
$pdo->exec("TRUNCATE TABLE orders");
$pdo->exec("TRUNCATE TABLE products");
$pdo->exec("TRUNCATE TABLE reviews");
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
    // Hair Services
    [
        'name' => 'Precision Haircut & Style',
        'price' => 2500.00,
        'originalPrice' => 3000.00,
        'category' => 'Hair Services',
        'image' => 'https://images.unsplash.com/photo-1560066984-138dadb4c035?w=600&q=80',
        'images' => [
            'https://images.unsplash.com/photo-1522337360788-8b13dee7a37e?w=600&q=80',
            'https://images.unsplash.com/photo-1605497788044-5a32c7078486?w=600&q=80',
        ],
        'description' => 'Expert haircut tailored to your face shape with professional styling and finishing products.',
        'sizes' => null,
        'colors' => null,
        'sale' => true,
        'featured' => true,
    ],
    [
        'name' => 'Hair Coloring & Highlights',
        'price' => 5500.00,
        'originalPrice' => 7000.00,
        'category' => 'Hair Services',
        'image' => 'https://images.unsplash.com/photo-1527799820374-dcf8d9d4a388?w=600&q=80',
        'images' => [
            'https://images.unsplash.com/photo-1605497788044-5a32c7078486?w=600&q=80',
            'https://images.unsplash.com/photo-1519699047748-de8e457a634e?w=600&q=80',
        ],
        'description' => 'Full color, balayage, or highlights using premium ammonia-free dyes for vibrant long-lasting color.',
        'sizes' => null,
        'colors' => ['Natural Black', 'Chestnut Brown', 'Platinum Blonde', 'Copper Red', 'Ash Grey'],
        'sale' => true,
        'featured' => true,
    ],
    [
        'name' => 'Keratin Smoothing Treatment',
        'price' => 12000.00,
        'originalPrice' => 15000.00,
        'category' => 'Hair Services',
        'image' => 'https://images.unsplash.com/photo-1519699047748-de8e457a634e?w=600&q=80',
        'images' => [
            'https://images.unsplash.com/photo-1560066984-138dadb4c035?w=600&q=80',
            'https://images.unsplash.com/photo-1522337360788-8b13dee7a37e?w=600&q=80',
        ],
        'description' => 'Deep keratin treatment that smooths, strengthens, and adds lasting shine for up to 3 months.',
        'sizes' => null,
        'colors' => null,
        'sale' => true,
        'featured' => false,
    ],
    [
        'name' => 'Blow Dry & Styling',
        'price' => 1500.00,
        'originalPrice' => null,
        'category' => 'Hair Services',
        'image' => 'https://images.unsplash.com/photo-1522337360788-8b13dee7a37e?w=600&q=80',
        'images' => [],
        'description' => 'Professional blow-dry with volume, curls, or sleek straight finish using heat protection.',
        'sizes' => null,
        'colors' => null,
        'sale' => false,
        'featured' => false,
    ],
    [
        'name' => 'Scalp Detox Therapy',
        'price' => 3500.00,
        'originalPrice' => null,
        'category' => 'Hair Services',
        'image' => 'https://images.unsplash.com/photo-1508214751196-bcfd4ca60f91?w=600&q=80',
        'images' => [],
        'description' => 'Deep scalp cleansing with essential oils and massage to remove buildup and promote hair growth.',
        'sizes' => null,
        'colors' => null,
        'sale' => false,
        'featured' => true,
    ],

    // Skin Treatments
    [
        'name' => 'Deep Cleansing Facial',
        'price' => 4000.00,
        'originalPrice' => 5500.00,
        'category' => 'Skin Treatments',
        'image' => 'https://images.unsplash.com/photo-1570172619644-dfd03ed5d881?w=600&q=80',
        'images' => [
            'https://images.unsplash.com/photo-1596755389378-c31d21fd1273?w=600&q=80',
            'https://images.unsplash.com/photo-1616394584738-fc6e612e71b9?w=600&q=80',
        ],
        'description' => 'Deep pore cleansing facial with steam, extraction, mask, and hydration for refreshed skin.',
        'sizes' => null,
        'colors' => null,
        'sale' => true,
        'featured' => true,
    ],
    [
        'name' => 'Brightening Vitamin C Facial',
        'price' => 5000.00,
        'originalPrice' => 6500.00,
        'category' => 'Skin Treatments',
        'image' => 'https://images.unsplash.com/photo-1596755389378-c31d21fd1273?w=600&q=80',
        'images' => [
            'https://images.unsplash.com/photo-1570172619644-dfd03ed5d881?w=600&q=80',
            'https://images.unsplash.com/photo-1616394584738-fc6e612e71b9?w=600&q=80',
        ],
        'description' => 'Vitamin C infusion facial to brighten dull skin, fade dark spots, and boost collagen production.',
        'sizes' => null,
        'colors' => null,
        'sale' => true,
        'featured' => false,
    ],
    [
        'name' => 'Anti-Aging Collagen Facial',
        'price' => 7500.00,
        'originalPrice' => 9000.00,
        'category' => 'Skin Treatments',
        'image' => 'https://images.unsplash.com/photo-1616394584738-fc6e612e71b9?w=600&q=80',
        'images' => [
            'https://images.unsplash.com/photo-1570172619644-dfd03ed5d881?w=600&q=80',
            'https://images.unsplash.com/photo-1596755389378-c31d21fd1273?w=600&q=80',
        ],
        'description' => 'Premium anti-aging treatment with collagen mask, LED therapy, and lifting massage techniques.',
        'sizes' => null,
        'colors' => null,
        'sale' => true,
        'featured' => true,
    ],
    [
        'name' => 'Hydra Dermabrasion',
        'price' => 6000.00,
        'originalPrice' => null,
        'category' => 'Skin Treatments',
        'image' => 'https://images.unsplash.com/photo-1519415510236-718bdfcd89c8?w=600&q=80',
        'images' => [],
        'description' => 'Advanced hydradermabrasion that exfoliates, extracts, and hydrates simultaneously for glowing skin.',
        'sizes' => null,
        'colors' => null,
        'sale' => false,
        'featured' => false,
    ],
    [
        'name' => 'Acne Scar Treatment',
        'price' => 8000.00,
        'originalPrice' => 10000.00,
        'category' => 'Skin Treatments',
        'image' => 'https://images.unsplash.com/photo-1508214751196-bcfd4ca60f91?w=600&q=80',
        'images' => [],
        'description' => 'Multi-session acne scar treatment combining micro-needling and serum infusion for smoother skin.',
        'sizes' => null,
        'colors' => null,
        'sale' => true,
        'featured' => false,
    ],

    // Nail Art
    [
        'name' => 'Classic Gel Manicure',
        'price' => 2000.00,
        'originalPrice' => 2500.00,
        'category' => 'Nail Art',
        'image' => 'https://images.unsplash.com/photo-1604654894610-df63bc536371?w=600&q=80',
        'images' => [
            'https://images.unsplash.com/photo-1632344866101-d0a04aec5c57?w=600&q=80',
            'https://images.unsplash.com/photo-1519014816548-bf5fe059798b?w=600&q=80',
        ],
        'description' => 'Long-lasting gel manicure with cuticle care, shaping, and premium gel polish finish.',
        'sizes' => null,
        'colors' => ['Classic Red', 'Nude Pink', 'French White', 'Deep Burgundy', 'Coral Sunset', 'Midnight Blue'],
        'sale' => true,
        'featured' => true,
    ],
    [
        'name' => 'Luxury Spa Pedicure',
        'price' => 3000.00,
        'originalPrice' => 3500.00,
        'category' => 'Nail Art',
        'image' => 'https://images.unsplash.com/photo-1519014816548-bf5fe059798b?w=600&q=80',
        'images' => [
            'https://images.unsplash.com/photo-1604654894610-df63bc536371?w=600&q=80',
        ],
        'description' => 'Relaxing spa pedicure with foot soak, scrub, mask, massage, and gel polish application.',
        'sizes' => null,
        'colors' => ['Rose Pink', 'Mint Green', 'Pearl White', 'Lavender'],
        'sale' => true,
        'featured' => true,
    ],
    [
        'name' => 'Acrylic Full Set',
        'price' => 3500.00,
        'originalPrice' => null,
        'category' => 'Nail Art',
        'image' => 'https://images.unsplash.com/photo-1632344866101-d0a04aec5c57?w=600&q=80',
        'images' => [],
        'description' => 'Full set of custom-shaped acrylic nails with your choice of color and finish.',
        'sizes' => ['Short', 'Medium', 'Long', 'Extra Long'],
        'colors' => ['French', 'Ombre', 'Glitter', 'Matte Black', 'Chrome'],
        'sale' => false,
        'featured' => false,
    ],
    [
        'name' => 'Nail Art Combo',
        'price' => 1200.00,
        'originalPrice' => null,
        'category' => 'Nail Art',
        'image' => 'https://images.unsplash.com/photo-1577467014087-ad0911f82b2a?w=600&q=80',
        'images' => [],
        'description' => 'Add artful designs to your nails — flowers, lines, gems, or custom patterns per nail.',
        'sizes' => null,
        'colors' => null,
        'sale' => false,
        'featured' => false,
    ],
    [
        'name' => 'Paraffin Wax Treatment',
        'price' => 1800.00,
        'originalPrice' => 2200.00,
        'category' => 'Nail Art',
        'image' => 'https://images.unsplash.com/photo-1519014816548-bf5fe059798b?w=600&q=80',
        'images' => [],
        'description' => 'Moisturizing paraffin wax dip for hands and feet to soften skin and improve circulation.',
        'sizes' => null,
        'colors' => null,
        'sale' => true,
        'featured' => false,
    ],

    // Bridal Packages
    [
        'name' => 'Bridal Glow Package',
        'price' => 25000.00,
        'originalPrice' => 32000.00,
        'category' => 'Bridal Packages',
        'image' => 'https://images.unsplash.com/photo-1519741497674-611481863552?w=600&q=80',
        'images' => [
            'https://images.unsplash.com/photo-1522673607200-164d1b6ce486?w=600&q=80',
            'https://images.unsplash.com/photo-1595514535215-95bfbe905b75?w=600&q=80',
        ],
        'description' => 'Complete bridal skin prep: 4 sessions of facial, body polish, and whitening treatment before the big day.',
        'sizes' => null,
        'colors' => null,
        'sale' => true,
        'featured' => true,
    ],
    [
        'name' => 'Full Bridal Hair & Makeup',
        'price' => 18000.00,
        'originalPrice' => 22000.00,
        'category' => 'Bridal Packages',
        'image' => 'https://images.unsplash.com/photo-1522673607200-164d1b6ce486?w=600&q=80',
        'images' => [
            'https://images.unsplash.com/photo-1519741497674-611481863552?w=600&q=80',
        ],
        'description' => 'Professional bridal hairstyling and HD makeup application for your wedding day.',
        'sizes' => null,
        'colors' => null,
        'sale' => true,
        'featured' => true,
    ],
    [
        'name' => 'Mehndi & Manicure Combo',
        'price' => 4500.00,
        'originalPrice' => null,
        'category' => 'Bridal Packages',
        'image' => 'https://images.unsplash.com/photo-1595514535215-95bfbe905b75?w=600&q=80',
        'images' => [],
        'description' => 'Intricate bridal mehndi design on hands paired with a gel manicure for a polished finish.',
        'sizes' => null,
        'colors' => null,
        'sale' => false,
        'featured' => false,
    ],
    [
        'name' => 'Pre-Wedding Couple Facial',
        'price' => 8000.00,
        'originalPrice' => 10000.00,
        'category' => 'Bridal Packages',
        'image' => 'https://images.unsplash.com/photo-1515934751635-c81c6bc9a2d8?w=600&q=80',
        'images' => [],
        'description' => 'Luxury facial treatment for the bride and groom — radiance boost, hydration, and calming massage.',
        'sizes' => null,
        'colors' => null,
        'sale' => true,
        'featured' => false,
    ],

    // Beauty Products
    [
        'name' => 'Argan Oil Hair Serum',
        'price' => 1800.00,
        'originalPrice' => 2200.00,
        'category' => 'Beauty Products',
        'image' => 'https://images.unsplash.com/photo-1608248543803-ba4f8c70ae0b?w=600&q=80',
        'images' => [
            'https://images.unsplash.com/photo-1556228578-0d85b1a4d571?w=600&q=80',
        ],
        'description' => 'Pure Moroccan argan oil serum for frizz control, shine, and deep nourishment.',
        'sizes' => ['50ml', '100ml'],
        'colors' => null,
        'sale' => true,
        'featured' => true,
    ],
    [
        'name' => 'Vitamin C Brightening Cream',
        'price' => 2500.00,
        'originalPrice' => 3000.00,
        'category' => 'Beauty Products',
        'image' => 'https://images.unsplash.com/photo-1556228578-0d85b1a4d571?w=600&q=80',
        'images' => [],
        'description' => 'Lightweight brightening cream with 20% vitamin C to even skin tone and reduce dark spots.',
        'sizes' => ['30g', '50g'],
        'colors' => null,
        'sale' => true,
        'featured' => false,
    ],
    [
        'name' => 'Retinol Night Repair Serum',
        'price' => 3200.00,
        'originalPrice' => null,
        'category' => 'Beauty Products',
        'image' => 'https://images.unsplash.com/photo-1611930022073-b7a4ba5fcccd?w=600&q=80',
        'images' => [],
        'description' => 'Advanced retinol serum that works overnight to reduce fine lines, wrinkles, and uneven texture.',
        'sizes' => ['30ml'],
        'colors' => null,
        'sale' => false,
        'featured' => true,
    ],
    [
        'name' => 'Hyaluronic Acid Moisturizer',
        'price' => 2000.00,
        'originalPrice' => 2500.00,
        'category' => 'Beauty Products',
        'image' => 'https://images.unsplash.com/photo-1600884523326-4f738449a6e2?w=600&q=80',
        'images' => [],
        'description' => 'Deep hydration moisturizer with hyaluronic acid and ceramides for plump, dewy skin.',
        'sizes' => ['50ml', '100ml'],
        'colors' => null,
        'sale' => true,
        'featured' => false,
    ],
    [
        'name' => 'Sunscreen SPF 50+',
        'price' => 1500.00,
        'originalPrice' => null,
        'category' => 'Beauty Products',
        'image' => 'https://images.unsplash.com/photo-1556228578-0d85b1a4d571?w=600&q=80',
        'images' => [],
        'description' => 'Non-greasy broad-spectrum SPF 50+ sunscreen with a matte finish, perfect under makeup.',
        'sizes' => ['50ml', '100ml'],
        'colors' => null,
        'sale' => false,
        'featured' => false,
    ],
    [
        'name' => 'Rose Water Toner',
        'price' => 900.00,
        'originalPrice' => null,
        'category' => 'Beauty Products',
        'image' => 'https://images.unsplash.com/photo-1598440947619-2c35fc9aa908?w=600&q=80',
        'images' => [],
        'description' => 'Pure steam-distilled rose water toner that hydrates, soothes, and balances skin pH.',
        'sizes' => ['100ml', '200ml'],
        'colors' => null,
        'sale' => false,
        'featured' => false,
    ],
];

$stmtProd = $pdo->prepare("INSERT INTO products (id, name, price, originalPrice, category, image, images, description, sale, featured, sizes, colors, shippingPricing) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

foreach ($products as $p) {
    $imagesJson = !empty($p['images']) ? json_encode($p['images']) : null;
    $sizesJson = !empty($p['sizes']) ? json_encode($p['sizes']) : null;
    $colorsJson = !empty($p['colors']) ? json_encode($p['colors']) : null;

    $stmtProd->execute([
        generateUUID(),
        $p['name'],
        $p['price'],
        $p['originalPrice'],
        $p['category'],
        $p['image'],
        $imagesJson,
        $p['description'],
        $p['sale'] ? 1 : 0,
        $p['featured'] ? 1 : 0,
        $sizesJson,
        $colorsJson,
        200.00,
    ]);
}

echo count($products) . " products seeded.\n";
echo "Done.\n";