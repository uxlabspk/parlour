<?php
// php_web/auth/login.php
require_once __DIR__ . '/../includes/db.php';

$redirectTo = $_GET['redirect'] ?? 'index';

if (isLoggedIn()) {
    header("Location: ../" . $redirectTo);
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $redirect = $_POST['redirect'] ?? 'index';

    if (empty($email) || empty($password)) {
        $error = 'Email and password are required';
    } else {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['role'] = $user['role'];
            
            header("Location: ../" . $redirect);
            exit;
        } else {
            $error = 'Invalid email or password';
        }
    }
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
                <h1 class="text-2xl font-light text-gray-900 mb-2">
                    Welcome <span class="font-semibold">Back</span>
                </h1>
                <p class="text-gray-500 text-sm">Sign in to continue to your account</p>
            </div>
            
            <?php if ($redirectTo === 'checkout'): ?>
                <div class="bg-blue-50 text-blue-700 p-4 rounded-2xl mb-6 text-sm border border-blue-100 flex items-center gap-3">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span>Please login to continue with checkout</span>
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

            <form method="POST" class="space-y-5">
                <input type="hidden" name="redirect" value="<?php echo htmlspecialchars($redirectTo); ?>">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                    <input type="email" name="email" required 
                        class="w-full px-5 py-4 rounded-xl border border-gray-200 outline-none focus:border-gray-400 focus:ring-2 focus:ring-gray-100 transition-all duration-200 text-gray-900 placeholder-gray-400" 
                        placeholder="you@example.com">
                </div>
                <div>
                    <div class="flex justify-between items-center mb-2">
                        <label class="block text-sm font-medium text-gray-700">Password</label>
                        <a href="http://localhost:8080/auth/forgot-password.php" class="text-xs text-gray-500 hover:text-gray-900 transition-colors">Forgot password?</a>
                    </div>
                    <div class="relative">
                        <input id="password" type="password" name="password" required 
                            class="w-full px-5 pr-12 py-4 rounded-xl border border-gray-200 outline-none focus:border-gray-400 focus:ring-2 focus:ring-gray-100 transition-all duration-200 text-gray-900 placeholder-gray-400" 
                            placeholder="••••••••">
                        <button type="button" id="togglePassword" aria-label="Toggle password visibility" class="absolute top-1/2 transform -translate-y-1/2 right-3 text-gray-500 hover:text-gray-900">
                            <svg id="eyeOpen" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <svg id="eyeClosed" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7a9.956 9.956 0 012.223-3.452M6.1 6.1A9.953 9.953 0 0112 5c4.477 0 8.268 2.943 9.542 7a9.97 9.97 0 01-4.13 5.9M3 3l18 18" />
                            </svg>
                        </button>
                    </div>
                </div>
                <button type="submit" class="w-full bg-gray-900 text-white py-4 rounded-full font-semibold hover:bg-gray-800 transition-colors duration-300 mt-2">
                    Sign In
                </button>
            </form>
            
            <div class="mt-8 pt-6 border-t border-gray-100 text-center">
                <p class="text-gray-500 text-sm">
                    Don't have an account? 
                        <a href="http://localhost:8080/auth/signup.php?redirect=<?php echo htmlspecialchars($redirectTo); ?>" class="text-gray-900 font-semibold hover:underline">Create one</a>
                </p>
            </div>
        </div>
        
        <!-- Trust Indicators -->
        <div class="mt-8 flex justify-center gap-6 text-xs text-gray-400">
            <span class="flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
                Secure Login
            </span>
            <span class="flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
                Privacy Protected
            </span>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var toggle = document.getElementById('togglePassword');
    var input = document.getElementById('password');
    var eyeOpen = document.getElementById('eyeOpen');
    var eyeClosed = document.getElementById('eyeClosed');

    if (!toggle || !input) return;

    toggle.addEventListener('click', function() {
        if (input.type === 'password') {
            input.type = 'text';
            eyeOpen.classList.add('hidden');
            eyeClosed.classList.remove('hidden');
            toggle.setAttribute('aria-pressed', 'true');
        } else {
            input.type = 'password';
            eyeOpen.classList.remove('hidden');
            eyeClosed.classList.add('hidden');
            toggle.setAttribute('aria-pressed', 'false');
        }
    });
});
</script>
