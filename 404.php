<?php include __DIR__ . '/includes/header.php'; ?>

<div class="min-h-screen flex items-center justify-center pt-24 pb-20 px-4">
    <div class="max-w-3xl mx-auto text-center">
        <!-- 404 Image -->
        <div class="relative mb-6 md:mb-8">
            <div class="absolute inset-0 bg-gradient-to-b from-gray-100/50 to-transparent rounded-3xl blur-3xl -z-10"></div>
            <img 
                src="https://parlour.com/assets/images/not_found.png" 
                alt="Page not found" 
                class="w-64 sm:w-80 md:w-96 lg:w-[420px] mx-auto drop-shadow-2xl animate-float"
            />
        </div>
        
        <!-- Message -->
        <div class="space-y-4 md:space-y-5">
            <h1 class="text-3xl sm:text-4xl md:text-5xl font-bold text-gray-900">
                Oops! Page Not Found
            </h1>
            <p class="text-base sm:text-lg text-gray-500 max-w-lg mx-auto leading-relaxed">
                The page you're looking for seems to have wandered off. It might have been moved, deleted, or never existed.
            </p>
            
            <!-- Actions -->
            <div class="flex flex-col sm:flex-row items-center justify-center gap-3 sm:gap-4 pt-4">
                <a href="https://parlour.com/" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-gray-900 text-white px-7 py-3.5 rounded-xl font-semibold hover:bg-gray-800 hover:scale-105 transition-all duration-200 shadow-lg shadow-gray-900/20">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    Back to Home
                </a>
                <a href="https://parlour.com/shop" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-white text-gray-700 px-7 py-3.5 rounded-xl font-semibold border border-gray-200 hover:bg-gray-50 hover:border-gray-300 hover:scale-105 transition-all duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                    </svg>
                    Browse Shop
                </a>
            </div>
        </div>
        
        <!-- Helpful Links -->
        <div class="mt-14 md:mt-16 pt-8 border-t border-gray-100">
            <p class="text-sm text-gray-400 mb-4">You might find these helpful:</p>
            <div class="flex flex-wrap items-center justify-center gap-x-6 gap-y-2 text-sm">
                <a href="https://parlour.com/collections" class="text-gray-600 hover:text-gray-900 transition-colors">Collections</a>
                <span class="text-gray-200 hidden sm:inline">•</span>
                <a href="https://parlour.com/about" class="text-gray-600 hover:text-gray-900 transition-colors">About Us</a>
                <span class="text-gray-200 hidden sm:inline">•</span>
                <a href="https://parlour.com/contact" class="text-gray-600 hover:text-gray-900 transition-colors">Contact</a>
            </div>
        </div>
    </div>
</div>

<style>
@keyframes float {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-12px); }
}
.animate-float {
    animation: float 4s ease-in-out infinite;
}
</style>

<?php include __DIR__ . '/includes/footer.php'; ?>