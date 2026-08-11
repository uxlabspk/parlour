<?php
// auth/reset-password.php
require_once __DIR__ . '/../includes/db.php';

if (isLoggedIn()) {
    header("Location: ../");
    exit;
}

$token = $_GET['token'] ?? '';
$error = '';
$validToken = false;

if (empty($token)) {
    $error = 'Invalid or missing reset token';
} else {
    // Verify token exists and is not expired
    $stmt = $pdo->prepare("SELECT id, email, name FROM users WHERE resetToken = ? AND resetTokenExpiry > NOW()");
    $stmt->execute([$token]);
    $user = $stmt->fetch();
    
    if ($user) {
        $validToken = true;
    } else {
        $error = 'Invalid or expired reset token. Please request a new password reset.';
    }
}

if (isset($_GET['error'])) {
    $error = urldecode($_GET['error']);
}

include __DIR__ . '/../includes/header.php';
?>

<div class="min-h-screen flex items-center justify-center py-32 px-6">
    <div class="w-full max-w-md">
        <!-- Logo/Brand -->
        <div class="text-center mb-10">
            <a href="https://parlour.com/" class="inline-block text-2xl font-light tracking-tight text-gray-900">
                Ma<span class="font-semibold">Essentials</span>
            </a>
        </div>
        
        <div class="bg-white rounded-3xl p-8 lg:p-10 border border-gray-100 shadow-sm">
            <div class="text-center mb-8">
                <div class="w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-6">
                    <svg class="w-8 h-8 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                </div>
                <h1 class="text-2xl font-light text-gray-900 mb-2">
                    Reset <span class="font-semibold">Password</span>
                </h1>
                <p class="text-gray-500 text-sm">Enter your new password below.</p>
            </div>
            
            <?php if ($error): ?>
                <div class="bg-red-50 text-red-600 p-4 rounded-2xl mb-6 text-sm border border-red-100 flex items-center gap-3">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span><?php echo htmlspecialchars($error); ?></span>
                </div>
                <div class="text-center">
                    <a href="https://parlour.com/auth/forgot-password" class="inline-flex items-center gap-2 text-gray-900 font-semibold hover:underline text-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        Request new reset link
                    </a>
                </div>
            <?php endif; ?>

            <?php if ($validToken): ?>
                <form action="https://parlour.com/api/reset_password.php" method="POST" class="space-y-5">
                    <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">New Password</label>
                        <input type="password" name="password" required minlength="6" 
                            class="w-full px-5 py-4 rounded-xl border border-gray-200 outline-none focus:border-gray-400 focus:ring-2 focus:ring-gray-100 transition-all duration-200 text-gray-900 placeholder-gray-400" 
                            placeholder="••••••••">
                        <p class="text-xs text-gray-400 mt-2">Minimum 6 characters</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Confirm Password</label>
                        <input type="password" name="confirm_password" required minlength="6" 
                            class="w-full px-5 py-4 rounded-xl border border-gray-200 outline-none focus:border-gray-400 focus:ring-2 focus:ring-gray-100 transition-all duration-200 text-gray-900 placeholder-gray-400" 
                            placeholder="••••••••">
                    </div>
                    
                    <button type="submit" class="w-full bg-gray-900 text-white py-4 rounded-full font-semibold hover:bg-gray-800 transition-colors duration-300 mt-2">
                        Reset Password
                    </button>
                </form>
            <?php endif; ?>
            
            <div class="mt-8 pt-6 border-t border-gray-100 text-center">
                <a href="https://parlour.com/auth/login" class="inline-flex items-center gap-2 text-gray-600 hover:text-gray-900 text-sm transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Back to Sign In
                </a>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
