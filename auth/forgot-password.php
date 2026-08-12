<?php
// auth/forgot-password.php
require_once __DIR__ . '/../includes/db.php';

if (isLoggedIn()) {
    header("Location: ../");
    exit;
}

$success = '';
$error = '';

if (isset($_GET['success'])) {
    $success = 'Password reset link has been sent to your email. Please check your inbox.';
}

if (isset($_GET['error'])) {
    $error = urldecode($_GET['error']);
}

include __DIR__ . '/../includes/header.php';
?>

<div class="min-h-screen flex items-center justify-center py-32 px-6 bg-rose-50">
    <div class="w-full max-w-md">
        <!-- Logo/Brand -->
        <div class="text-center mb-10">
            <a href="http://localhost:8080/" class="inline-block text-2xl font-light tracking-tight text-gray-900">
                Ma<span class="font-semibold">Essentials</span>
            </a>
        </div>
        
        <div class="bg-white rounded-3xl p-8 lg:p-10 border border-gray-100 shadow-sm">
            <div class="text-center mb-8">
                <div class="w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-6">
                    <svg class="w-8 h-8 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                    </svg>
                </div>
                <h1 class="text-2xl font-light text-gray-900 mb-2">
                    Forgot <span class="font-semibold">Password?</span>
                </h1>
                <p class="text-gray-500 text-sm">No worries, we'll send you reset instructions.</p>
            </div>
            
            <?php if ($success): ?>
                <div class="bg-emerald-50 text-emerald-700 p-4 rounded-2xl mb-6 text-sm border border-emerald-100 flex items-center gap-3">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span><?php echo htmlspecialchars($success); ?></span>
                </div>
            <?php endif; ?>

            <?php if ($error): ?>
                <div class="bg-red-50 text-red-600 p-4 rounded-2xl mb-6 text-sm border border-red-100 flex items-center gap-3">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span><?php echo htmlspecialchars($error); ?></span>
                </div>
            <?php endif; ?>

            <form action="http://localhost:8080/api/forgot_password.php" method="POST" class="space-y-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                    <input type="email" name="email" required 
                        class="w-full px-5 py-4 rounded-xl border border-gray-200 outline-none focus:border-gray-400 focus:ring-2 focus:ring-gray-100 transition-all duration-200 text-gray-900 placeholder-gray-400" 
                        placeholder="you@example.com">
                </div>
                <button type="submit" class="w-full bg-gray-900 text-white py-4 rounded-full font-semibold hover:bg-gray-800 transition-colors duration-300">
                    Send Reset Link
                </button>
            </form>
            
            <div class="mt-8 pt-6 border-t border-gray-100 text-center">
                <a href="http://localhost:8080/auth/login.php" class="inline-flex items-center gap-2 text-gray-600 hover:text-gray-900 text-sm transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Back to Sign In
                </a>
            </div>
        </div>
        
        <!-- Help Text -->
        <div class="mt-8 text-center">
            <p class="text-xs text-gray-400">
                Remember your password? <a href="http://localhost:8080/auth/login.php" class="text-gray-600 hover:underline">Sign in here</a>
            </p>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
