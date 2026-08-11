<?php
// admin/reviews.php
include __DIR__ . '/../includes/header.php';
requireAdmin();

// Handle actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $reviewId = $_POST['review_id'] ?? '';
    
    if ($action === 'approve' && $reviewId) {
        // Approve review
        $stmt = $pdo->prepare("UPDATE reviews SET approved = TRUE, approvedAt = NOW() WHERE id = ?");
        $stmt->execute([$reviewId]);
        
        // Get the review details to update product rating
        $stmt = $pdo->prepare("SELECT productId FROM reviews WHERE id = ?");
        $stmt->execute([$reviewId]);
        $review = $stmt->fetch();
        
        if ($review) {
            // Recalculate product rating and review count
            $stmt = $pdo->prepare("
                SELECT AVG(rating) as avgRating, COUNT(*) as reviewCount 
                FROM reviews 
                WHERE productId = ? AND approved = TRUE
            ");
            $stmt->execute([$review['productId']]);
            $stats = $stmt->fetch();
            
            // Update product
            $stmt = $pdo->prepare("UPDATE products SET rating = ?, reviewCount = ? WHERE id = ?");
            $stmt->execute([
                round($stats['avgRating'], 2),
                $stats['reviewCount'],
                $review['productId']
            ]);
        }
        
        $_SESSION['success_message'] = 'Review approved successfully';
        header("Location: reviews.php");
        exit;
    } elseif ($action === 'reject' && $reviewId) {
        // Delete review
        $stmt = $pdo->prepare("DELETE FROM reviews WHERE id = ?");
        $stmt->execute([$reviewId]);
        
        $_SESSION['success_message'] = 'Review deleted successfully';
        header("Location: reviews.php");
        exit;
    }
}

// Fetch reviews
$filter = $_GET['filter'] ?? 'pending';
$page = max(1, intval($_GET['page'] ?? 1));
$perPage = 20;
$offset = ($page - 1) * $perPage;

$whereClause = $filter === 'pending' ? "WHERE r.approved = FALSE" : ($filter === 'approved' ? "WHERE r.approved = TRUE" : "");

$stmt = $pdo->prepare("
    SELECT r.*, u.name as userName, u.email as userEmail, u.profileImage as userProfileImage, p.name as productName, p.image as productImage
    FROM reviews r
    JOIN users u ON r.userId = u.id
    JOIN products p ON r.productId = p.id
    $whereClause
    ORDER BY r.createdAt DESC
    LIMIT $perPage OFFSET $offset
");
$stmt->execute();
$reviews = $stmt->fetchAll();

// Get total count
$countStmt = $pdo->prepare("SELECT COUNT(*) FROM reviews r $whereClause");
$countStmt->execute();
$totalReviews = $countStmt->fetchColumn();
$totalPages = ceil($totalReviews / $perPage);

// Get counts for tabs
$pendingCount = $pdo->query("SELECT COUNT(*) FROM reviews WHERE approved = FALSE")->fetchColumn();
$approvedCount = $pdo->query("SELECT COUNT(*) FROM reviews WHERE approved = TRUE")->fetchColumn();
?>

<div class="">
    <!-- Admin Header -->
    <div class="sm:pt-36 pt-24 bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900 text-white">
        <div class="max-w-7xl mx-auto px-6 py-12">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                <div>
                    <a href="http://localhost:8080/admin/index.php" class="inline-flex items-center gap-2 text-sm text-gray-400 hover:text-white transition-colors mb-3">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Back to Dashboard
                    </a>
                    <h1 class="text-3xl lg:text-4xl font-light">
                        Review <span class="font-semibold">Management</span>
                    </h1>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-6 -mt-6">
        <?php if (isset($_SESSION['success_message'])): ?>
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-5 py-4 rounded-xl mb-6 flex items-center gap-3">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <?php 
                echo htmlspecialchars($_SESSION['success_message']); 
                unset($_SESSION['success_message']);
                ?>
            </div>
        <?php endif; ?>

        <!-- Filter Tabs -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-2 mb-6 inline-flex gap-1">
            <a href="http://localhost:8080/admin/index.php/reviews.php?filter=pending" class="px-5 py-2.5 rounded-xl font-semibold text-sm transition-all duration-200 <?php echo $filter === 'pending' ? 'bg-gray-900 text-white' : 'text-gray-600 hover:bg-gray-100'; ?>">
                Pending <span class="ml-1 text-xs opacity-70">(<?php echo $pendingCount; ?>)</span>
            </a>
            <a href="http://localhost:8080/admin/index.php/reviews.php?filter=approved" class="px-5 py-2.5 rounded-xl font-semibold text-sm transition-all duration-200 <?php echo $filter === 'approved' ? 'bg-gray-900 text-white' : 'text-gray-600 hover:bg-gray-100'; ?>">
                Approved <span class="ml-1 text-xs opacity-70">(<?php echo $approvedCount; ?>)</span>
            </a>
            <a href="http://localhost:8080/admin/index.php/reviews.php?filter=all" class="px-5 py-2.5 rounded-xl font-semibold text-sm transition-all duration-200 <?php echo $filter === 'all' ? 'bg-gray-900 text-white' : 'text-gray-600 hover:bg-gray-100'; ?>">
                All <span class="ml-1 text-xs opacity-70">(<?php echo $pendingCount + $approvedCount; ?>)</span>
            </a>
        </div>

        <!-- Reviews List -->
        <?php if (empty($reviews)): ?>
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-16 text-center">
                <div class="w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                    </svg>
                </div>
                <p class="text-gray-500">No reviews found</p>
            </div>
        <?php else: ?>
            <div class="space-y-4">
                <?php foreach ($reviews as $review): ?>
                    <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition-shadow duration-300">
                        <div class="flex flex-col lg:flex-row gap-6">
                            <!-- Product Image -->
                            <div class="w-24 h-24 bg-gray-100 rounded-xl overflow-hidden flex-shrink-0">
                                <img src="<?php echo htmlspecialchars($review['productImage']); ?>" alt="<?php echo htmlspecialchars($review['productName']); ?>" class="w-full h-full object-cover">
                            </div>

                            <!-- Review Content -->
                            <div class="flex-1 min-w-0">
                                <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-4 mb-4">
                                    <div class="flex items-center gap-3">
                                        <!-- User Profile Image -->
                                        <div class="w-10 h-10 bg-gray-200 rounded-xl overflow-hidden flex-shrink-0">
                                            <?php if (!empty($review['userProfileImage'])): ?>
                                                <img src="<?php echo htmlspecialchars($review['userProfileImage']); ?>" alt="<?php echo htmlspecialchars($review['userName']); ?>" class="w-full h-full object-cover">
                                            <?php else: ?>
                                                <div class="w-full h-full flex items-center justify-center bg-gray-900 text-white font-semibold text-sm">
                                                    <?php echo strtoupper(substr($review['userName'] ?? 'U', 0, 1)); ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        <div>
                                            <h3 class="font-semibold text-gray-900">
                                                <a href="http://localhost:8080/product.php?id=<?php echo $review['productId']; ?>" class="hover:underline">
                                                    <?php echo htmlspecialchars($review['productName']); ?>
                                                </a>
                                            </h3>
                                            <p class="text-sm text-gray-500">
                                                By <span class="font-medium text-gray-700"><?php echo htmlspecialchars($review['userName']); ?></span>
                                            </p>
                                        </div>
                                    </div>
                                    <?php if ($review['approved']): ?>
                                        <span class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                            Approved
                                        </span>
                                    <?php else: ?>
                                        <span class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            Pending
                                        </span>
                                    <?php endif; ?>
                                </div>

                                <!-- Rating -->
                                <div class="flex gap-1 mb-3">
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <svg class="w-5 h-5 <?php echo $i <= $review['rating'] ? 'text-amber-400' : 'text-gray-200'; ?>" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                    <?php endfor; ?>
                                </div>

                                <!-- Comment -->
                                <?php if (!empty($review['comment'])): ?>
                                    <p class="text-gray-600 text-sm leading-relaxed mb-4"><?php echo nl2br(htmlspecialchars($review['comment'])); ?></p>
                                <?php endif; ?>

                                <!-- Metadata -->
                                <p class="text-xs text-gray-400 mb-4">
                                    Submitted on <?php echo date('M j, Y', strtotime($review['createdAt'])); ?>
                                    <?php if ($review['approved'] && $review['approvedAt']): ?>
                                        · Approved on <?php echo date('M j, Y', strtotime($review['approvedAt'])); ?>
                                    <?php endif; ?>
                                </p>

                                <!-- Actions -->
                                <div class="flex gap-3">
                                    <?php if (!$review['approved']): ?>
                                        <form method="POST" class="inline">
                                            <input type="hidden" name="action" value="approve">
                                            <input type="hidden" name="review_id" value="<?php echo $review['id']; ?>">
                                            <button type="submit" class="inline-flex items-center gap-2 bg-emerald-600 text-white px-4 py-2 rounded-xl font-semibold text-sm hover:bg-emerald-700 transition-colors duration-200">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                </svg>
                                                Approve
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                    <form method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this review?');">
                                        <input type="hidden" name="action" value="reject">
                                        <input type="hidden" name="review_id" value="<?php echo $review['id']; ?>">
                                        <button type="submit" class="inline-flex items-center gap-2 bg-red-50 text-red-600 px-4 py-2 rounded-xl font-semibold text-sm hover:bg-red-100 transition-colors duration-200 border border-red-200">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Pagination -->
            <?php if ($totalPages > 1): ?>
                <div class="flex justify-center gap-2 mt-10">
                    <?php if ($page > 1): ?>
                        <a href="http://localhost:8080/admin/index.php/reviews.php?filter=<?php echo $filter; ?>&page=<?php echo $page - 1; ?>" class="px-4 py-2 rounded-xl border border-gray-200 text-gray-600 hover:bg-gray-50 transition-colors text-sm font-medium">Previous</a>
                    <?php endif; ?>
                    
                    <?php for ($i = max(1, $page - 2); $i <= min($totalPages, $page + 2); $i++): ?>
                        <a href="http://localhost:8080/admin/index.php/reviews.php?filter=<?php echo $filter; ?>&page=<?php echo $i; ?>" class="px-4 py-2 rounded-xl border text-sm font-medium transition-colors <?php echo $i === $page ? 'bg-gray-900 text-white border-gray-900' : 'border-gray-200 text-gray-600 hover:bg-gray-50'; ?>">
                            <?php echo $i; ?>
                        </a>
                    <?php endfor; ?>
                    
                    <?php if ($page < $totalPages): ?>
                        <a href="http://localhost:8080/admin/index.php/reviews.php?filter=<?php echo $filter; ?>&page=<?php echo $page + 1; ?>" class="px-4 py-2 rounded-xl border border-gray-200 text-gray-600 hover:bg-gray-50 transition-colors text-sm font-medium">Next</a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
