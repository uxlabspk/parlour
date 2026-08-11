<?php
// php_web/admin/products.php
include __DIR__ . '/../includes/header.php';
requireAdmin();

$action = $_GET['action'] ?? 'list';
$error = '';
$success = '';
$editProduct = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_product'])) {
        $id = $_POST['id'];
        $name = $_POST['name'];
        $price = (float)$_POST['price'];
        $discountedPrice = !empty($_POST['discounted_price']) ? (float)$_POST['discounted_price'] : null;
        $category = $_POST['category'];
        $description = $_POST['description'];
        $sale = isset($_POST['sale']) ? 1 : 0;
        $size = $_POST['size'] ?? null;
        $color = $_POST['color'] ?? null;
        $shippingPricing = !empty($_POST['shipping_pricing']) ? (float)$_POST['shipping_pricing'] : 0;

        // Get existing product
        $stmt = $pdo->prepare("SELECT image, images FROM products WHERE id = ?");
        $stmt->execute([$id]);
        $existingProduct = $stmt->fetch();
        
        $existingImages = json_decode($existingProduct['images'], true) ?? [];
        $deletedImages = json_decode($_POST['deleted_images'] ?? '[]', true);
        
        // Remove deleted images from array and filesystem
        foreach ($deletedImages as $imgPath) {
            $fullPath = __DIR__ . '/../' . $imgPath;
            if (file_exists($fullPath)) {
                unlink($fullPath);
            }
            $existingImages = array_values(array_filter($existingImages, fn($img) => $img !== $imgPath));
        }
        
        $uploadedImages = $existingImages;
        $uploadDir = __DIR__ . '/../assets/images/products/';
        
        // Handle new image uploads
        if (isset($_FILES['new_images']) && !empty($_FILES['new_images']['name'][0])) {
            $fileCount = count($_FILES['new_images']['name']);
            
            if (count($uploadedImages) + $fileCount > 8) {
                $error = 'Total images cannot exceed 8.';
            } else {
                for ($i = 0; $i < $fileCount; $i++) {
                    if ($_FILES['new_images']['error'][$i] === UPLOAD_ERR_OK) {
                        $tmpName = $_FILES['new_images']['tmp_name'][$i];
                        $fileName = $_FILES['new_images']['name'][$i];
                        $fileSize = $_FILES['new_images']['size'][$i];
                        $fileType = $_FILES['new_images']['type'][$i];
                        
                        $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
                        if (!in_array($fileType, $allowedTypes)) {
                            $error = 'Invalid file type. Only JPG, PNG, GIF, and WEBP are allowed.';
                            break;
                        }
                        
                        if ($fileSize > 5 * 1024 * 1024) {
                            $error = 'File size must be less than 5MB.';
                            break;
                        }
                        
                        $extension = pathinfo($fileName, PATHINFO_EXTENSION);
                        $uniqueName = uniqid() . '_' . time() . '_' . $i . '.' . $extension;
                        $destination = $uploadDir . $uniqueName;
                        
                        if (move_uploaded_file($tmpName, $destination)) {
                            $uploadedImages[] = '/assets/images/products/' . $uniqueName;
                        } else {
                            $error = 'Failed to upload image: ' . $fileName;
                            break;
                        }
                    }
                }
            }
        }

        if (empty($error) && !empty($uploadedImages)) {
            $mainImage = $uploadedImages[0];
            $allImages = json_encode($uploadedImages);
            
            $stmt = $pdo->prepare("UPDATE products SET name = ?, price = ?, discountedPrice = ?, category = ?, image = ?, images = ?, description = ?, sale = ?, size = ?, color = ?, shippingPricing = ? WHERE id = ?");
            try {
                $stmt->execute([$name, $price, $discountedPrice, $category, $mainImage, $allImages, $description, $sale, $size, $color, $shippingPricing, $id]);
                $success = 'Product updated successfully with ' . count($uploadedImages) . ' image(s)!';
                $action = 'list';
            } catch (Exception $e) {
                $error = 'Error updating product: ' . $e->getMessage();
            }
        } elseif (empty($uploadedImages)) {
            $error = 'Product must have at least one image.';
        }
    } elseif (isset($_POST['add_product'])) {
        $name = $_POST['name'];
        $price = (float)$_POST['price'];
        $discountedPrice = !empty($_POST['discounted_price']) ? (float)$_POST['discounted_price'] : null;
        $category = $_POST['category'];
        $description = $_POST['description'];
        $sale = isset($_POST['sale']) ? 1 : 0;
        $size = $_POST['size'] ?? null;
        $color = $_POST['color'] ?? null;
        $shippingPricing = !empty($_POST['shipping_pricing']) ? (float)$_POST['shipping_pricing'] : 0;

        // Handle image uploads
        $uploadedImages = [];
        $uploadDir = __DIR__ . '/../assets/images/products/';
        
        // Create directory if it doesn't exist
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        if (isset($_FILES['images']) && !empty($_FILES['images']['name'][0])) {
            $fileCount = count($_FILES['images']['name']);
            
            if ($fileCount < 1 || $fileCount > 8) {
                $error = 'Please upload between 1 and 8 images.';
            } else {
                for ($i = 0; $i < $fileCount; $i++) {
                    if ($_FILES['images']['error'][$i] === UPLOAD_ERR_OK) {
                        $tmpName = $_FILES['images']['tmp_name'][$i];
                        $fileName = $_FILES['images']['name'][$i];
                        $fileSize = $_FILES['images']['size'][$i];
                        $fileType = $_FILES['images']['type'][$i];
                        
                        // Validate file type
                        $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
                        if (!in_array($fileType, $allowedTypes)) {
                            $error = 'Invalid file type. Only JPG, PNG, GIF, and WEBP are allowed.';
                            break;
                        }
                        
                        // Validate file size (max 5MB)
                        if ($fileSize > 5 * 1024 * 1024) {
                            $error = 'File size must be less than 5MB.';
                            break;
                        }
                        
                        // Generate unique filename
                        $extension = pathinfo($fileName, PATHINFO_EXTENSION);
                        $uniqueName = uniqid() . '_' . time() . '_' . $i . '.' . $extension;
                        $destination = $uploadDir . $uniqueName;
                        
                        if (move_uploaded_file($tmpName, $destination)) {
                            $uploadedImages[] = '/assets/images/products/' . $uniqueName;
                        } else {
                            $error = 'Failed to upload image: ' . $fileName;
                            break;
                        }
                    }
                }
            }
        } else {
            $error = 'Please upload at least one image.';
        }

        if (empty($error) && !empty($uploadedImages)) {
            // Store first image in main image field, all images as JSON array
            $mainImage = $uploadedImages[0];
            $allImages = json_encode($uploadedImages);
            
            $id = generateUUID();
            $stmt = $pdo->prepare("INSERT INTO products (id, name, price, discountedPrice, category, image, images, description, sale, size, color, shippingPricing) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            try {
                $stmt->execute([$id, $name, $price, $discountedPrice, $category, $mainImage, $allImages, $description, $sale, $size, $color, $shippingPricing]);
                $success = 'Product added successfully with ' . count($uploadedImages) . ' image(s)!';
                $action = 'list';
            } catch (Exception $e) {
                $error = 'Error adding product: ' . $e->getMessage();
                // Clean up uploaded files on error
                foreach ($uploadedImages as $img) {
                    $fullPath = __DIR__ . '/../' . $img;
                    if (file_exists($fullPath)) {
                        unlink($fullPath);
                    }
                }
            }
        }
    }
}

if ($action === 'delete') {
    $id = $_GET['id'];
    // Get product images to delete files
    $stmt = $pdo->prepare("SELECT images FROM products WHERE id = ?");
    $stmt->execute([$id]);
    $product = $stmt->fetch();
    if ($product) {
        $images = json_decode($product['images'], true) ?? [];
        foreach ($images as $imgPath) {
            $fullPath = __DIR__ . '/../' . $imgPath;
            if (file_exists($fullPath)) {
                unlink($fullPath);
            }
        }
    }
    $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
    $stmt->execute([$id]);
    $success = 'Product deleted successfully!';
    $action = 'list';
}

if ($action === 'edit') {
    $id = $_GET['id'];
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([$id]);
    $editProduct = $stmt->fetch();
    if (!$editProduct) {
        $error = 'Product not found.';
        $action = 'list';
    }
}

$stmt = $pdo->query("SELECT * FROM products ORDER BY createdAt DESC");
$products = $stmt->fetchAll();
?>

<div class="">
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
                        Product <span class="font-semibold">Inventory</span>
                    </h1>
                    <p class="text-gray-400 mt-2"><?php echo count($products); ?> products in catalog</p>
                </div>
                <button onclick="document.getElementById('add-modal').classList.toggle('hidden')" class="inline-flex items-center gap-2 bg-white text-gray-900 px-5 py-2.5 rounded-xl font-semibold text-sm hover:bg-gray-100 transition-colors duration-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Add Product
                </button>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-6 -mt-6">
        <?php if ($success): ?>
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-5 py-4 rounded-xl mb-6 flex items-center gap-3">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <?php echo $success; ?>
            </div>
        <?php endif; ?>
        
        <?php if ($error): ?>
            <div class="bg-red-50 border border-red-200 text-red-700 px-5 py-4 rounded-xl mb-6 flex items-center gap-3">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <!-- Product Table -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-100">
                <h2 class="text-lg font-semibold text-gray-900">All Products</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-50/50">
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Product</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Category</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Price</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <?php foreach ($products as $p): ?>
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-4">
                                        <img src="<?php echo htmlspecialchars($p['image']); ?>" class="w-12 h-16 object-cover rounded-xl border border-gray-100">
                                        <div>
                                            <span class="font-semibold text-gray-900"><?php echo htmlspecialchars($p['name']); ?></span>
                                            <?php if (!empty($p['size']) || !empty($p['color'])): ?>
                                            <p class="text-xs text-gray-500 mt-0.5">
                                                <?php echo !empty($p['size']) ? 'Size: ' . htmlspecialchars($p['size']) : ''; ?>
                                                <?php echo (!empty($p['size']) && !empty($p['color'])) ? ' • ' : ''; ?>
                                                <?php echo !empty($p['color']) ? 'Color: ' . htmlspecialchars($p['color']) : ''; ?>
                                            </p>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium bg-gray-100 text-gray-700">
                                        <?php echo htmlspecialchars($p['category']); ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div>
                                        <span class="text-sm font-semibold text-gray-900">PKR <?php echo number_format($p['price'], 0); ?></span>
                                        <?php if (!empty($p['discountedPrice'])): ?>
                                        <span class="block text-xs text-emerald-600 font-medium">Sale: PKR <?php echo number_format($p['discountedPrice'], 0); ?></span>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <?php if ($p['sale']): ?>
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">On Sale</span>
                                    <?php else: ?>
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium bg-gray-50 text-gray-600 border border-gray-200">Regular</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <a href="https://parlour.com/admin/products?action=edit&id=<?php echo $p['id']; ?>" class="text-gray-600 hover:text-gray-900 transition-colors" title="Edit">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </a>
                                        <a href="https://parlour.com/admin/products?action=delete&id=<?php echo $p['id']; ?>" onclick="return confirm('Are you sure you want to delete this product?')" class="text-red-500 hover:text-red-700 transition-colors" title="Delete">
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
        </div>
    </div>
    <br>
</div>

<!-- Add Product Modal -->
<div id="add-modal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-[100] flex items-center justify-center hidden">
    <div class="bg-white rounded-2xl p-8 max-w-2xl w-full mx-4 shadow-2xl max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-semibold text-gray-900">Add New Product</h2>
            <button onclick="closeModal()" class="p-2 hover:bg-gray-100 rounded-xl transition-colors">
                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <form method="POST" enctype="multipart/form-data" class="space-y-5" id="product-form">
            <input type="hidden" name="add_product" value="1">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Product Name</label>
                <input type="text" name="name" required class="w-full px-4 py-3 border border-gray-200 rounded-xl outline-none focus:border-gray-400 focus:ring-2 focus:ring-gray-100 transition-all duration-200">
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Price (PKR)</label>
                    <input type="number" step="0.01" name="price" required class="w-full px-4 py-3 border border-gray-200 rounded-xl outline-none focus:border-gray-400 focus:ring-2 focus:ring-gray-100 transition-all duration-200">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Sale Price <span class="text-gray-400 font-normal">(Optional)</span></label>
                    <input type="number" step="0.01" name="discounted_price" class="w-full px-4 py-3 border border-gray-200 rounded-xl outline-none focus:border-gray-400 focus:ring-2 focus:ring-gray-100 transition-all duration-200">
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                    <select name="category" required class="w-full px-4 py-3 border border-gray-200 rounded-xl outline-none focus:border-gray-400 focus:ring-2 focus:ring-gray-100 transition-all duration-200">
                        <option value="">Select category</option>
                        <option value="Hair Services">Hair Services</option>
                        <option value="Skin Treatments">Skin Treatments</option>
                        <option value="Nail Art">Nail Art</option>
                        <option value="Bridal Packages">Bridal Packages</option>
                        <option value="Beauty Products">Beauty Products</option>
                    </select>
                </div>
                <div class="flex items-center pt-8">
                    <input type="checkbox" name="sale" id="sale" class="w-5 h-5 text-gray-900 border-gray-300 rounded-lg focus:ring-gray-900">
                    <label for="sale" class="ml-3 text-sm font-medium text-gray-700">Product on Sale</label>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Size <span class="text-gray-400 font-normal">(Optional)</span></label>
                    <input type="text" name="size" class="w-full px-4 py-3 border border-gray-200 rounded-xl outline-none focus:border-gray-400 focus:ring-2 focus:ring-gray-100 transition-all duration-200" placeholder="S, M, L, XL">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Color <span class="text-gray-400 font-normal">(Optional)</span></label>
                    <input type="text" name="color" class="w-full px-4 py-3 border border-gray-200 rounded-xl outline-none focus:border-gray-400 focus:ring-2 focus:ring-gray-100 transition-all duration-200" placeholder="Red, Blue, etc.">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Shipping Cost <span class="text-gray-400 font-normal">(Optional)</span></label>
                <input type="number" step="0.01" name="shipping_pricing" placeholder="0.00" class="w-full px-4 py-3 border border-gray-200 rounded-xl outline-none focus:border-gray-400 focus:ring-2 focus:ring-gray-100 transition-all duration-200">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Product Images <span class="text-gray-400 font-normal">(1-8 images)</span></label>
                <div class="border-2 border-dashed border-gray-200 rounded-xl p-8 text-center hover:border-gray-400 transition-colors cursor-pointer" id="upload-area">
                    <input type="file" name="images[]" id="image-input" multiple accept="image/*" class="hidden" required>
                    <div id="upload-prompt">
                        <div class="w-14 h-14 bg-gray-100 rounded-xl mx-auto mb-4 flex items-center justify-center">
                            <svg class="w-7 h-7 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <p class="text-sm text-gray-600 font-medium">Click to upload or drag and drop</p>
                        <p class="text-xs text-gray-400 mt-1">PNG, JPG, GIF, WEBP up to 5MB each</p>
                    </div>
                </div>
                <div id="image-preview" class="grid grid-cols-4 gap-3 mt-4 hidden"></div>
                <p class="text-xs text-gray-500 mt-2"><span id="image-count">0</span> / 8 images selected</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                <textarea name="description" class="w-full px-4 py-3 border border-gray-200 rounded-xl outline-none focus:border-gray-400 focus:ring-2 focus:ring-gray-100 transition-all duration-200 h-24 resize-none"></textarea>
            </div>
            <div class="flex gap-3 pt-2">
                <button type="button" onclick="closeModal()" class="flex-1 bg-gray-100 text-gray-700 py-3 rounded-xl font-semibold hover:bg-gray-200 transition-colors duration-200">Cancel</button>
                <button type="submit" id="submit-btn" class="flex-1 bg-gray-900 text-white py-3 rounded-xl font-semibold hover:bg-gray-800 transition-colors duration-200">Save Product</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Product Modal -->
<div id="edit-modal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-[100] flex items-center justify-center <?php echo $action !== 'edit' ? 'hidden' : ''; ?>">
    <div class="bg-white rounded-2xl p-8 max-w-2xl w-full mx-4 shadow-2xl max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-semibold text-gray-900">Edit Product</h2>
            <a href="https://parlour.com/admin/products" class="p-2 hover:bg-gray-100 rounded-xl transition-colors">
                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </a>
        </div>
        <form method="POST" enctype="multipart/form-data" class="space-y-5" id="edit-form">
            <input type="hidden" name="update_product" value="1">
            <input type="hidden" name="id" value="<?php echo $editProduct['id'] ?? ''; ?>">
            <input type="hidden" name="deleted_images" id="deleted-images" value="[]">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Product Name</label>
                <input type="text" name="name" value="<?php echo htmlspecialchars($editProduct['name'] ?? ''); ?>" required class="w-full px-4 py-3 border border-gray-200 rounded-xl outline-none focus:border-gray-400 focus:ring-2 focus:ring-gray-100 transition-all duration-200">
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Price (PKR)</label>
                    <input type="number" step="0.01" name="price" value="<?php echo $editProduct['price'] ?? ''; ?>" required class="w-full px-4 py-3 border border-gray-200 rounded-xl outline-none focus:border-gray-400 focus:ring-2 focus:ring-gray-100 transition-all duration-200">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Sale Price <span class="text-gray-400 font-normal">(Optional)</span></label>
                    <input type="number" step="0.01" name="discounted_price" value="<?php echo $editProduct['discountedPrice'] ?? ''; ?>" class="w-full px-4 py-3 border border-gray-200 rounded-xl outline-none focus:border-gray-400 focus:ring-2 focus:ring-gray-100 transition-all duration-200">
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                    <?php $curCat = htmlspecialchars($editProduct['category'] ?? ''); ?>
                    <select name="category" required class="w-full px-4 py-3 border border-gray-200 rounded-xl outline-none focus:border-gray-400 focus:ring-2 focus:ring-gray-100 transition-all duration-200">
                        <option value="">Select category</option>
                        <option value="Hair Services" <?php echo $curCat === 'Hair Services' ? 'selected' : ''; ?>>Hair Services</option>
                        <option value="Skin Treatments" <?php echo $curCat === 'Skin Treatments' ? 'selected' : ''; ?>>Skin Treatments</option>
                        <option value="Nail Art" <?php echo $curCat === 'Nail Art' ? 'selected' : ''; ?>>Nail Art</option>
                        <option value="Bridal Packages" <?php echo $curCat === 'Bridal Packages' ? 'selected' : ''; ?>>Bridal Packages</option>
                        <option value="Beauty Products" <?php echo $curCat === 'Beauty Products' ? 'selected' : ''; ?>>Beauty Products</option>
                    </select>
                </div>
                <div class="flex items-center pt-8">
                    <input type="checkbox" name="sale" id="edit-sale" class="w-5 h-5 text-gray-900 border-gray-300 rounded-lg focus:ring-gray-900" <?php echo ($editProduct['sale'] ?? false) ? 'checked' : ''; ?>>
                    <label for="edit-sale" class="ml-3 text-sm font-medium text-gray-700">Product on Sale</label>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Size <span class="text-gray-400 font-normal">(Optional)</span></label>
                    <input type="text" name="size" value="<?php echo htmlspecialchars($editProduct['size'] ?? ''); ?>" class="w-full px-4 py-3 border border-gray-200 rounded-xl outline-none focus:border-gray-400 focus:ring-2 focus:ring-gray-100 transition-all duration-200" placeholder="S, M, L, XL">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Color <span class="text-gray-400 font-normal">(Optional)</span></label>
                    <input type="text" name="color" value="<?php echo htmlspecialchars($editProduct['color'] ?? ''); ?>" class="w-full px-4 py-3 border border-gray-200 rounded-xl outline-none focus:border-gray-400 focus:ring-2 focus:ring-gray-100 transition-all duration-200" placeholder="Red, Blue, etc.">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Shipping Cost <span class="text-gray-400 font-normal">(Optional)</span></label>
                <input type="number" step="0.01" name="shipping_pricing" value="<?php echo $editProduct['shippingPricing'] ?? '0'; ?>" placeholder="0.00" class="w-full px-4 py-3 border border-gray-200 rounded-xl outline-none focus:border-gray-400 focus:ring-2 focus:ring-gray-100 transition-all duration-200">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Product Images <span class="text-gray-400 font-normal">(1-8 images)</span></label>
                
                <!-- Existing Images -->
                <div id="existing-images" class="grid grid-cols-4 gap-3 mb-4">
                    <?php 
                    if ($editProduct) {
                        $existingImages = json_decode($editProduct['images'], true) ?? [];
                        foreach ($existingImages as $index => $imgPath): 
                    ?>
                    <div class="relative group" data-image="<?php echo htmlspecialchars($imgPath); ?>">
                        <img src="<?php echo htmlspecialchars($imgPath); ?>" class="w-full h-24 object-cover rounded-xl border border-gray-200">
                        <button type="button" onclick="removeExistingImage(this, '<?php echo htmlspecialchars($imgPath); ?>')" class="absolute top-1 right-1 bg-red-500 text-white rounded-lg w-6 h-6 flex items-center justify-center opacity-0 group-hover:opacity-100 transition font-bold text-xs hover:bg-red-600">
                            ×
                        </button>
                        <?php if ($index === 0): ?>
                        <span class="absolute bottom-1 left-1 bg-gray-900 text-white text-xs px-2 py-0.5 rounded-lg font-medium">Main</span>
                        <?php endif; ?>
                    </div>
                    <?php 
                        endforeach;
                    }
                    ?>
                </div>
                
                <!-- Upload New Images -->
                <div class="border-2 border-dashed border-gray-200 rounded-xl p-6 text-center hover:border-gray-400 transition-colors cursor-pointer" id="edit-upload-area">
                    <input type="file" name="new_images[]" id="edit-image-input" multiple accept="image/*" class="hidden">
                    <div id="edit-upload-prompt">
                        <div class="w-12 h-12 bg-gray-100 rounded-xl mx-auto mb-3 flex items-center justify-center">
                            <svg class="w-6 h-6 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v16m8-8H4"/>
                            </svg>
                        </div>
                        <p class="text-sm text-gray-600 font-medium">Add more images</p>
                        <p class="text-xs text-gray-400 mt-1">PNG, JPG, GIF, WEBP up to 5MB each</p>
                    </div>
                </div>
                <div id="edit-image-preview" class="grid grid-cols-4 gap-3 mt-4 hidden"></div>
                <p class="text-xs text-gray-500 mt-2"><span id="edit-image-count"><?php echo isset($editProduct) ? count(json_decode($editProduct['images'], true) ?? []) : 0; ?></span> / 8 images</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                <textarea name="description" class="w-full px-4 py-3 border border-gray-200 rounded-xl outline-none focus:border-gray-400 focus:ring-2 focus:ring-gray-100 transition-all duration-200 h-24 resize-none"><?php echo htmlspecialchars($editProduct['description'] ?? ''); ?></textarea>
            </div>
            <div class="flex gap-3 pt-2">
                <a href="https://parlour.com/admin/products" class="flex-1 text-center bg-gray-100 text-gray-700 py-3 rounded-xl font-semibold hover:bg-gray-200 transition-colors duration-200">Cancel</a>
                <button type="submit" id="edit-submit-btn" class="flex-1 bg-gray-900 text-white py-3 rounded-xl font-semibold hover:bg-gray-800 transition-colors duration-200">Update Product</button>
            </div>
        </form>
    </div>
</div>

<script>
let selectedFiles = [];
let editSelectedFiles = [];
let deletedImages = [];

function closeModal() {
    document.getElementById('add-modal').classList.add('hidden');
    document.getElementById('product-form').reset();
    selectedFiles = [];
    updatePreview();
}

function closeEditModal() {
    window.location.href = 'products.php';
}

const uploadArea = document.getElementById('upload-area');
const imageInput = document.getElementById('image-input');
const imagePreview = document.getElementById('image-preview');
const imageCount = document.getElementById('image-count');
const uploadPrompt = document.getElementById('upload-prompt');
const submitBtn = document.getElementById('submit-btn');

uploadArea.addEventListener('click', () => imageInput.click());

// Drag and drop
uploadArea.addEventListener('dragover', (e) => {
    e.preventDefault();
    uploadArea.classList.add('border-gray-400', 'bg-gray-50');
});

uploadArea.addEventListener('dragleave', () => {
    uploadArea.classList.remove('border-gray-400', 'bg-gray-50');
});

uploadArea.addEventListener('drop', (e) => {
    e.preventDefault();
    uploadArea.classList.remove('border-gray-400', 'bg-gray-50');
    handleFiles(e.dataTransfer.files);
});

imageInput.addEventListener('change', (e) => {
    handleFiles(e.target.files);
});

function handleFiles(files) {
    const filesArray = Array.from(files);
    
    if (selectedFiles.length + filesArray.length > 8) {
        alert('You can only upload up to 8 images');
        return;
    }
    
    filesArray.forEach(file => {
        if (file.type.startsWith('image/')) {
            selectedFiles.push(file);
        }
    });
    
    updatePreview();
}

function updatePreview() {
    imagePreview.innerHTML = '';
    imageCount.textContent = selectedFiles.length;
    
    if (selectedFiles.length === 0) {
        imagePreview.classList.add('hidden');
        uploadPrompt.classList.remove('hidden');
        submitBtn.disabled = true;
        submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
        return;
    }
    
    imagePreview.classList.remove('hidden');
    uploadPrompt.classList.add('hidden');
    submitBtn.disabled = false;
    submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
    
    selectedFiles.forEach((file, index) => {
        const reader = new FileReader();
        reader.onload = (e) => {
            const div = document.createElement('div');
            div.className = 'relative group';
            div.innerHTML = `
                <img src="${e.target.result}" class="w-full h-24 object-cover rounded-xl border border-gray-200">
                <button type="button" onclick="removeImage(${index})" class="absolute top-1 right-1 bg-red-500 text-white rounded-lg w-6 h-6 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-200 text-xs hover:bg-red-600">
                    ×
                </button>
                ${index === 0 ? '<span class="absolute bottom-1 left-1 bg-gray-900 text-white text-xs px-2 py-0.5 rounded-lg font-medium">Main</span>' : ''}
            `;
            imagePreview.appendChild(div);
        };
        reader.readAsDataURL(file);
    });
    
    // Update file input
    const dt = new DataTransfer();
    selectedFiles.forEach(file => dt.items.add(file));
    imageInput.files = dt.files;
}

function removeImage(index) {
    selectedFiles.splice(index, 1);
    updatePreview();
}

// Initialize
updatePreview();

// Edit Modal Logic
const editUploadArea = document.getElementById('edit-upload-area');
const editImageInput = document.getElementById('edit-image-input');
const editImagePreview = document.getElementById('edit-image-preview');
const editImageCount = document.getElementById('edit-image-count');
const editUploadPrompt = document.getElementById('edit-upload-prompt');
const deletedImagesInput = document.getElementById('deleted-images');

if (editUploadArea) {
    editUploadArea.addEventListener('click', () => editImageInput.click());

    editUploadArea.addEventListener('dragover', (e) => {
        e.preventDefault();
        editUploadArea.classList.add('border-gray-400', 'bg-gray-50');
    });

    editUploadArea.addEventListener('dragleave', () => {
        editUploadArea.classList.remove('border-gray-400', 'bg-gray-50');
    });

    editUploadArea.addEventListener('drop', (e) => {
        e.preventDefault();
        editUploadArea.classList.remove('border-gray-400', 'bg-gray-50');
        handleEditFiles(e.dataTransfer.files);
    });

    editImageInput.addEventListener('change', (e) => {
        handleEditFiles(e.target.files);
    });
}

function handleEditFiles(files) {
    const filesArray = Array.from(files);
    const existingCount = document.querySelectorAll('#existing-images > div').length;
    
    if (existingCount + editSelectedFiles.length + filesArray.length > 8) {
        alert('Total images cannot exceed 8');
        return;
    }
    
    filesArray.forEach(file => {
        if (file.type.startsWith('image/')) {
            editSelectedFiles.push(file);
        }
    });
    
    updateEditPreview();
}

function updateEditPreview() {
    editImagePreview.innerHTML = '';
    const existingCount = document.querySelectorAll('#existing-images > div').length;
    editImageCount.textContent = existingCount + editSelectedFiles.length;
    
    if (editSelectedFiles.length === 0) {
        editImagePreview.classList.add('hidden');
        return;
    }
    
    editImagePreview.classList.remove('hidden');
    
    editSelectedFiles.forEach((file, index) => {
        const reader = new FileReader();
        reader.onload = (e) => {
            const div = document.createElement('div');
            div.className = 'relative group';
            div.innerHTML = `
                <img src="${e.target.result}" class="w-full h-24 object-cover rounded-xl border border-gray-200">
                <button type="button" onclick="removeEditImage(${index})" class="absolute top-1 right-1 bg-red-500 text-white rounded-lg w-6 h-6 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-200 text-xs hover:bg-red-600">
                    ×
                </button>
            `;
            editImagePreview.appendChild(div);
        };
        reader.readAsDataURL(file);
    });
    
    const dt = new DataTransfer();
    editSelectedFiles.forEach(file => dt.items.add(file));
    editImageInput.files = dt.files;
}

function removeEditImage(index) {
    editSelectedFiles.splice(index, 1);
    updateEditPreview();
}

function removeExistingImage(btn, imgPath) {
    if (confirm('Are you sure you want to remove this image?')) {
        deletedImages.push(imgPath);
        deletedImagesInput.value = JSON.stringify(deletedImages);
        btn.closest('div').remove();
        
        const existingCount = document.querySelectorAll('#existing-images > div').length;
        editImageCount.textContent = existingCount + editSelectedFiles.length;
        
        if (existingCount === 0 && editSelectedFiles.length === 0) {
            alert('Product must have at least one image!');
        }
    }
}
</script>

<?php include __DIR__ . '/../includes/footer.php'; ?>
