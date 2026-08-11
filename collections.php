<?php
// php_web/collections.php
include __DIR__ . '/includes/header.php';
?>

<!-- Hero Section -->
 <div class="relative h-[60vh] min-h-[500px] flex items-center justify-center overflow-hidden">
    <div class="absolute inset-0 -z-10">
        <img 
            src="https://images.unsplash.com/photo-1560066984-138dadb4c035?w=1920&q=85" 
            alt="Beauty Service Collections" 
            class="w-full h-full object-cover"
        />
        <div class="absolute inset-0 bg-gradient-to-r from-black/70 via-black/50 to-transparent"></div>
    </div>
    
    <div class="relative z-10 text-center px-6">
        <span class="inline-block text-sm font-semibold text-white/70 tracking-[0.3em] uppercase mb-4">Explore</span>
        <h1 class="text-5xl lg:text-6xl font-light text-white mb-4 tracking-tight">
            Our <span class="font-semibold">Services</span>
        </h1>
        <p class="text-lg text-white/70 max-w-xl mx-auto">
            Explore our curated selections, each service designed with precision and elegance.
        </p>
    </div>
</div>

<!-- Essential Picks Section -->
<section class="py-24 bg-white overflow-hidden">
    <div class="max-w-7xl mx-auto px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl lg:text-5xl font-light text-gray-900 tracking-tight">
                Essential <span class="font-semibold">Picks</span>
            </h2>
        </div>

        <?php
        // Fetch 5 featured items for the highlight section
        $stmtHighlights = $pdo->prepare("SELECT name, image FROM products ORDER BY featured DESC LIMIT 5");
        $stmtHighlights->execute();
        $highlights = $stmtHighlights->fetchAll();
        ?>

        <!-- Desktop Grid View -->
        <div class="hidden lg:grid grid-cols-5 gap-6">
            <?php foreach ($highlights as $item): ?>
                <div class="text-center group cursor-pointer">
                    <div class="relative w-full h-[200px] rounded-2xl overflow-hidden mb-4 bg-gray-50 border border-gray-100 shadow-sm transition-all duration-500 group-hover:shadow-xl group-hover:-translate-y-1">
                        <img 
                            src="<?php echo htmlspecialchars($item['image']); ?>" 
                            alt="<?php echo htmlspecialchars($item['name']); ?>" 
                            class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
                        >
                        <div class="absolute inset-0 bg-black/5 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    </div>
                    <h3 class="text-[10px] font-bold text-gray-900 uppercase tracking-[0.2em] px-2"><?php echo htmlspecialchars($item['name']); ?></h3>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Mobile/Tablet Slider View -->
        <div class="lg:hidden relative">
            <div id="essentialSlider" class="flex transition-transform duration-500 ease-out">
                <?php foreach ($highlights as $item): ?>
                    <div class="min-w-full px-4 text-center group">
                        <div class="relative w-full max-w-[400px] aspect-[3/2] mx-auto rounded-3xl overflow-hidden mb-6 bg-gray-50 border border-gray-100 shadow-md">
                            <img 
                                src="<?php echo htmlspecialchars($item['image']); ?>" 
                                alt="<?php echo htmlspecialchars($item['name']); ?>" 
                                class="w-full h-full object-cover"
                            >
                        </div>
                        <h3 class="text-base font-bold text-gray-900 uppercase tracking-[0.3em]"><?php echo htmlspecialchars($item['name']); ?></h3>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <!-- Slider Indicators -->
            <div class="flex justify-center mt-10 gap-2.5">
                <?php foreach ($highlights as $i => $item): ?>
                    <div class="essential-dot w-2 h-2 rounded-full <?php echo $i === 0 ? 'bg-gray-900 w-8' : 'bg-gray-300'; ?> transition-all duration-300"></div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const slider = document.getElementById('essentialSlider');
    if (!slider) return;
    
    const dots = document.querySelectorAll('.essential-dot');
    let currentSlide = 0;
    const totalSlides = 5;

    function updateSlider() {
        slider.style.transform = `translateX(-${currentSlide * 100}%)`;
        dots.forEach((dot, i) => {
            dot.classList.toggle('w-8', i === currentSlide);
            dot.classList.toggle('bg-gray-900', i === currentSlide);
            dot.classList.toggle('bg-gray-300', i !== currentSlide);
        });
    }

    // Auto-advance slider
    const autoSlide = setInterval(() => {
        currentSlide = (currentSlide + 1) % totalSlides;
        updateSlider();
    }, 5000);

    // Touch events for mobile swiping
    let startX = 0;
    slider.addEventListener('touchstart', e => startX = e.touches[0].clientX);
    slider.addEventListener('touchend', e => {
        const endX = e.changedTouches[0].clientX;
        const diff = startX - endX;
        
        if (Math.abs(diff) > 50) {
            clearInterval(autoSlide);
            if (diff > 0) currentSlide = (currentSlide + 1) % totalSlides;
            else currentSlide = (currentSlide - 1 + totalSlides) % totalSlides;
            updateSlider();
        }
    });
});
</script>

<!-- Collections Grid -->
<section class="py-24">
    <div class="max-w-7xl mx-auto px-6 lg:px-8">
        <!-- Main Collections - Hair & Skin Services -->
        <div class="grid md:grid-cols-2 gap-6 mb-6">
            <!-- Hair Services Collection -->
            <a href="https://parlour.com/shop?category=Hair Services" class="group relative aspect-[3/4] rounded-3xl overflow-hidden block">
                <img 
                    src="https://images.unsplash.com/photo-1560066984-138dadb4c035?w=1200&q=80" 
                    alt="Hair Services Collection" 
                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700" 
                    loading="lazy"
                >
                <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent"></div>
                <div class="absolute bottom-10 left-10 right-10">
                    <span class="inline-block text-xs font-semibold text-white/60 tracking-[0.2em] uppercase mb-3">Service</span>
                    <h2 class="text-3xl lg:text-4xl font-light text-white mb-4">
                        Hair <span class="font-semibold">Services</span>
                    </h2>
                    <span class="inline-flex items-center gap-2 text-white text-sm font-semibold group-hover:gap-4 transition-all duration-300">
                        Explore Hair
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                        </svg>
                    </span>
                </div>
            </a>

            <!-- Skin Treatments Collection -->
            <a href="https://parlour.com/shop?category=Skin Treatments" class="group relative aspect-[3/4] rounded-3xl overflow-hidden block">
                <img 
                    src="https://images.unsplash.com/photo-1522337360788-8b13dee7a37e?w=1200&q=80" 
                    alt="Skin Treatments Collection" 
                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700" 
                    loading="lazy"
                >
                <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent"></div>
                <div class="absolute bottom-10 left-10 right-10">
                    <span class="inline-block text-xs font-semibold text-white/60 tracking-[0.2em] uppercase mb-3">Service</span>
                    <h2 class="text-3xl lg:text-4xl font-light text-white mb-4">
                        Skin <span class="font-semibold">Treatments</span>
                    </h2>
                    <span class="inline-flex items-center gap-2 text-white text-sm font-semibold group-hover:gap-4 transition-all duration-300">
                        Explore Skin
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                        </svg>
                    </span>
                </div>
            </a>
        </div>

        <!-- Nail Art - Full Width -->
        <a href="https://parlour.com/shop?category=Nail Art" class="group relative aspect-[21/9] rounded-3xl overflow-hidden block mb-6">
            <img 
                src="https://images.unsplash.com/photo-1604654894610-df63bc536371?w=1600&q=80" 
                alt="Nail Art Collection" 
                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700" 
                loading="lazy"
            >
            <div class="absolute inset-0 bg-gradient-to-r from-black/70 via-black/30 to-transparent"></div>
            <div class="absolute bottom-10 left-10 right-10 md:right-auto md:max-w-xl">
                <span class="inline-block text-xs font-semibold text-white/60 tracking-[0.2em] uppercase mb-3">Service</span>
                <h2 class="text-3xl lg:text-4xl font-light text-white mb-4">
                    Nail <span class="font-semibold">Art</span>
                </h2>
                <span class="inline-flex items-center gap-2 text-white text-sm font-semibold group-hover:gap-4 transition-all duration-300">
                    Explore Nail Art
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                    </svg>
                </span>
            </div>
        </a>
    </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
