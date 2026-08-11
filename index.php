<?php
// php_web/index.php
include __DIR__ . '/includes/header.php';

// Fetch featured or recent products
$stmt = $pdo->prepare("SELECT * FROM products ORDER BY featured DESC, createdAt DESC LIMIT 8");
$stmt->execute();
$featured_products = $stmt->fetchAll();

// Fetch products for slider
$stmtSlider = $pdo->prepare("SELECT * FROM products ORDER BY createdAt DESC LIMIT 6");
$stmtSlider->execute();
$slider_products = $stmtSlider->fetchAll();
?>

<!-- Custom Styles for Modern Design -->
<style>
    /* Elegant Typography */
    .font-display { font-family: 'Inter', system-ui, sans-serif; }
    
    /* Smooth Reveal Animations */
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(40px); }
        to { opacity: 1; transform: translateY(0); }
    }
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    @keyframes scaleIn {
        from { opacity: 0; transform: scale(0.95); }
        to { opacity: 1; transform: scale(1); }
    }
    @keyframes slideInRight {
        from { opacity: 0; transform: translateX(60px); }
        to { opacity: 1; transform: translateX(0); }
    }
    @keyframes float {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-10px); }
    }
    
    .animate-fade-in-up { animation: fadeInUp 0.8s ease-out forwards; }
    .animate-fade-in { animation: fadeIn 1s ease-out forwards; }
    .animate-scale-in { animation: scaleIn 0.6s ease-out forwards; }
    .animate-slide-right { animation: slideInRight 0.8s ease-out forwards; }
    .animate-float { animation: float 6s ease-in-out infinite; }
    
    .delay-100 { animation-delay: 0.1s; }
    .delay-200 { animation-delay: 0.2s; }
    .delay-300 { animation-delay: 0.3s; }
    .delay-400 { animation-delay: 0.4s; }
    .delay-500 { animation-delay: 0.5s; }
    
    /* Gradient Text */
    .gradient-text {
        background: linear-gradient(135deg, #1a1a1a 0%, #4a4a4a 50%, #1a1a1a 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    
    /* Glass Effect */
    .glass {
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
    }
    
    /* Elegant Hover Effects */
    .hover-lift {
        transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1), box-shadow 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .hover-lift:hover {
        transform: translateY(-8px);
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.15);
    }
    
    /* Smooth Image Zoom */
    .img-zoom {
        transition: transform 0.7s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .group:hover .img-zoom {
        transform: scale(1.08);
    }
    
    /* Elegant Button Styles */
    .btn-elegant {
        position: relative;
        overflow: hidden;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .btn-elegant::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
        transition: left 0.5s;
    }
    .btn-elegant:hover::before {
        left: 100%;
    }
    
    /* Marquee Animation */
    @keyframes marquee {
        0% { transform: translateX(0); }
        100% { transform: translateX(-50%); }
    }
    .animate-marquee {
        animation: marquee 30s linear infinite;
    }
    
    /* Scroll indicator */
    @keyframes bounce-slow {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(8px); }
    }
    .animate-bounce-slow {
        animation: bounce-slow 2s ease-in-out infinite;
    }
</style>

<!-- Structured Data (JSON-LD) -->
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "WebSite",
  "name": "Parlour",
    "url": "http://localhost:8080",
  "description": "Premium beauty and hair salon services for the discerning client",
  "potentialAction": {
    "@type": "SearchAction",
    "target": "http://localhost:8080/shop.php?search={search_term_string}",
    "query-input": "required name=search_term_string"
  }
}
</script>
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "Organization",
  "name": "Parlour",
    "url": "http://localhost:8080",
    "logo": "http://localhost:8080/assets/images/logo.png",
  "sameAs": [
    "https://facebook.com/parlour",
    "https://instagram.com/parlour",
    "https://twitter.com/parlour"
  ],
  "contactPoint": {
    "@type": "ContactPoint",
    "contactType": "Customer Service",
    "email": "support@parlour.com"
  }
}
</script>

<div class="relative overflow-hidden">
    <!-- Hero Section - Full Screen Immersive -->
    <header class="relative min-h-screen flex items-center justify-center overflow-hidden" role="banner">
        <!-- Animated Background with Parallax Effect -->
        <div class="absolute inset-0 -z-10">
            <div id="heroSlider" class="relative w-full h-full">
                <!-- Slide 1 -->
                <div class="hero-slide absolute inset-0 opacity-100 transition-opacity duration-1000">
                    <img 
                        src="https://images.unsplash.com/photo-1560066984-138dadb4c035?w=1920&q=85" 
                        alt="Premium Hair Salon" 
                        class="w-full h-full object-cover scale-105"
                    />
                </div>
                <!-- Slide 2 -->
                <div class="hero-slide absolute inset-0 opacity-0 transition-opacity duration-1000">
                    <img 
                        src="https://images.unsplash.com/photo-1522337360788-8b13dee7a37e?w=1920&q=85" 
                        alt="Beauty Treatment" 
                        class="w-full h-full object-cover scale-105"
                    />
                </div>
                <!-- Slide 3 -->
                <div class="hero-slide absolute inset-0 opacity-0 transition-opacity duration-1000">
                    <img 
                        src="https://images.unsplash.com/photo-1487412947147-5cebf100ffc2?w=1920&q=85" 
                        alt="Nail Art Studio" 
                        class="w-full h-full object-cover scale-105"
                    />
                </div>
            </div>
            <!-- Sophisticated Gradient Overlay -->
            <div class="absolute inset-0 bg-gradient-to-b from-black/30 via-black/40 to-black/70"></div>
            <div class="absolute inset-0 bg-gradient-to-r from-black/50 via-transparent to-transparent"></div>
        </div>

        <!-- Decorative Elements -->
        <div class="absolute top-1/4 right-10 w-72 h-72 bg-white/5 rounded-full blur-3xl animate-float"></div>
        <div class="absolute bottom-1/4 left-10 w-96 h-96 bg-white/5 rounded-full blur-3xl animate-float delay-300"></div>

        <!-- Hero Content -->
        <div class="relative z-10 max-w-7xl mx-auto px-6 lg:px-8 w-full pt-32 pb-20">
            <div class="flex items-center justify-center min-h-[70vh]">
                <!-- Main Content -->
                <div class="space-y-8 text-center max-w-4xl">
                    <!-- Elegant Badge -->
                    <div class="animate-fade-in-up opacity-0" style="animation-delay: 0.2s">
                        <span class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full glass border border-white/20 text-white/90 text-sm font-medium tracking-wide">
                            <span class="w-2 h-2 bg-emerald-400 rounded-full animate-pulse"></span>
                            New Services 2026
                        </span>
                    </div>

                    <!-- Main Heading with Elegant Typography -->
                    <div class="space-y-4 animate-fade-in-up opacity-0" style="animation-delay: 0.4s">
                        <h1 class="text-5xl sm:text-6xl lg:text-7xl xl:text-8xl font-light text-white leading-[0.95] tracking-tight">
                            Your Beauty
                            <span class="block font-semibold mt-2">Perfected</span>
                        </h1>
                    </div>

                    <!-- Refined Subheading -->
                    <p class="text-lg lg:text-xl text-white/70 max-w-xl leading-relaxed font-light animate-fade-in-up opacity-0" style="animation-delay: 0.6s">
                        Discover curated beauty services that blend timeless elegance with modern techniques. 
                        Every treatment tells a story of artistry and sophistication.
                    </p>

                    <!-- CTA Buttons -->
                    <div class="flex flex-col sm:flex-row gap-4 pt-4 animate-fade-in-up opacity-0 justify-center" style="animation-delay: 0.8s">
                        <a href="http://localhost:8080/shop.php" class="btn-elegant group inline-flex items-center justify-center gap-3 bg-white text-gray-900 px-8 py-4 rounded-full font-semibold text-base shadow-2xl shadow-white/20 hover:shadow-white/30 hover:bg-gray-50">
                            Explore Services
                            <svg class="w-5 h-5 transition-transform duration-300 group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                            </svg>
                        </a>
                        <a href="#gallery-section" class="group inline-flex items-center justify-center gap-3 px-8 py-4 rounded-full font-semibold text-white border border-white/30 hover:bg-white/10 hover:border-white/50 transition-all duration-300">
                            View Services
                        </a>
                    </div>

                    <!-- Trust Indicators -->
                    <!-- <div class="flex items-center gap-8 pt-8 animate-fade-in-up opacity-0 justify-center flex-wrap" style="animation-delay: 1s">
                        <div class="flex items-center gap-3">
                            <div class="flex -space-x-2">
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-amber-200 to-amber-400 border-2 border-white/50"></div>
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-rose-200 to-rose-400 border-2 border-white/50"></div>
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-violet-200 to-violet-400 border-2 border-white/50"></div>
                            </div>
                            <div class="text-white/80 text-sm">
                                <span class="font-semibold text-white">2,500+</span> Happy Customers
                            </div>
                        </div>
                        <div class="hidden sm:flex items-center gap-2 text-white/80 text-sm">
                            <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                            <span><span class="font-semibold text-white">4.9</span> Rating</span>
                        </div>
                    </div> -->
                </div>
            </div>
        </div>


        <!-- Slide Indicators -->
        <div class="absolute bottom-10 right-10 flex gap-3 z-20">
            <div class="hero-dot w-12 h-1.5 rounded-full bg-white transition-all duration-500"></div>
            <div class="hero-dot w-6 h-1.5 rounded-full bg-white/40 transition-all duration-500 hover:bg-white/60 cursor-pointer"></div>
            <div class="hero-dot w-6 h-1.5 rounded-full bg-white/40 transition-all duration-500 hover:bg-white/60 cursor-pointer"></div>
        </div>
    </header>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const slides = document.querySelectorAll('.hero-slide');
            const dots = document.querySelectorAll('.hero-dot');
            let current = 0;
            const slidesCount = slides.length;

            function updateHero() {
                slides.forEach((slide, i) => {
                    slide.style.opacity = i === current ? '1' : '0';
                });
                dots.forEach((dot, i) => {
                    dot.classList.toggle('w-12', i === current);
                    dot.classList.toggle('bg-white', i === current);
                    dot.classList.toggle('w-6', i !== current);
                    dot.classList.toggle('bg-white/40', i !== current);
                });
            }

            // Click on dots to navigate
            dots.forEach((dot, i) => {
                dot.addEventListener('click', () => {
                    current = i;
                    updateHero();
                });
            });

            // Auto slide
            setInterval(() => {
                current = (current + 1) % slidesCount;
                updateHero();
            }, 5000);
        });
    </script>

    <!-- Marquee Brand Strip -->
    <div class="bg-gray-950 py-4 overflow-hidden">
        <div class="flex animate-marquee whitespace-nowrap">
            <?php for($i = 0; $i < 10; $i++): ?>
                <span class="mx-8 text-white/40 text-sm font-medium tracking-widest uppercase">Expert Stylists</span>
                <span class="mx-4 text-white/20">✦</span>
                <span class="mx-8 text-white/40 text-sm font-medium tracking-widest uppercase">Premium Products</span>
                <span class="mx-4 text-white/20">✦</span>
                <span class="mx-8 text-white/40 text-sm font-medium tracking-widest uppercase">Relaxing Experience</span>
                <span class="mx-4 text-white/20">✦</span>
            <?php endfor; ?>
        </div>
    </div>

    <!-- New Services Section - Refined -->
    <section class="py-24 lg:py-32 bg-white overflow-hidden">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <!-- Section Header -->
            <div class="flex flex-col lg:flex-row justify-between items-start lg:items-end mb-16 gap-8">
                <div class="max-w-xl">
                    <span class="inline-block text-sm font-semibold text-gray-400 tracking-widest uppercase mb-4">Just Added</span>
                    <h2 class="text-4xl lg:text-5xl font-light text-gray-900 mb-4 tracking-tight">
                        New <span class="font-semibold">Services</span>
                    </h2>
                    <p class="text-gray-500 text-lg leading-relaxed">Fresh beauty services crafted for the modern client. Discover treatments that define contemporary elegance.</p>
                </div>
                <div class="flex items-center gap-3">
                    <button id="prevBtn" class="w-14 h-14 rounded-full border-2 border-gray-200 flex items-center justify-center hover:bg-gray-900 hover:border-gray-900 hover:text-white transition-all duration-300 group">
                        <svg class="w-5 h-5 transition-transform group-hover:-translate-x-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    </button>
                    <button id="nextBtn" class="w-14 h-14 rounded-full bg-gray-900 text-white flex items-center justify-center hover:bg-gray-700 transition-all duration-300 group">
                        <svg class="w-5 h-5 transition-transform group-hover:translate-x-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </button>
                </div>
            </div>

            <!-- Product Slider -->
            <div class="relative -mx-6 lg:mx-0">
                <div id="productSlider" class="flex transition-transform duration-700 ease-out gap-6 px-6 lg:px-0">
                    <?php foreach ($slider_products as $index => $product): ?>
                        <div class="min-w-[85%] sm:min-w-[45%] lg:min-w-[calc(33.333%-16px)] group">
                            <a href="http://localhost:8080/product.php?id=<?php echo $product['id']; ?>" class="block">
                                <div class="relative aspect-[3/4] rounded-3xl overflow-hidden mb-6 bg-gray-100">
                                    <img 
                                        src="<?php echo htmlspecialchars($product['image']); ?>" 
                                        alt="<?php echo htmlspecialchars($product['name'] . ' - ' . $product['category']); ?>" 
                                        class="w-full h-full object-cover img-zoom"
                                        loading="lazy"
                                    >
                                    <!-- Hover Overlay -->
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                                    
                                    <!-- Quick View Button -->
                                    <div class="absolute bottom-6 left-6 right-6 opacity-0 group-hover:opacity-100 translate-y-4 group-hover:translate-y-0 transition-all duration-500">
                                        <span class="w-full bg-white text-gray-900 py-3 rounded-full font-semibold text-sm flex items-center justify-center gap-2">
                                            View Product
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                                            </svg>
                                        </span>
                                    </div>

                                    <!-- New Badge -->
                                    <?php if ($index < 2): ?>
                                    <div class="absolute top-5 left-5">
                                        <span class="bg-gray-900 text-white text-xs font-semibold px-4 py-1.5 rounded-full">New</span>
                                    </div>
                                    <?php endif; ?>
                                </div>
                                <div class="space-y-2">
                                    <p class="text-xs text-gray-400 uppercase tracking-[0.2em] font-medium"><?php echo htmlspecialchars($product['category']); ?></p>
                                    <h3 class="text-lg font-semibold text-gray-900 group-hover:text-gray-600 transition-colors"><?php echo htmlspecialchars($product['name']); ?></h3>
                                    <p class="text-lg font-semibold text-gray-900">PKR <?php echo number_format($product['price'], 0); ?></p>
                                </div>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const slider = document.getElementById('productSlider');
            const prevBtn = document.getElementById('prevBtn');
            const nextBtn = document.getElementById('nextBtn');
            let currentIdx = 0;
            const totalItems = <?php echo count($slider_products); ?>;
            
            function updateSlider() {
                const isMobile = window.innerWidth < 640;
                const isTablet = window.innerWidth >= 640 && window.innerWidth < 1024;
                let itemsVisible = 3;
                let gap = 24;
                
                if (isMobile) {
                    itemsVisible = 1.15;
                } else if (isTablet) {
                    itemsVisible = 2.15;
                }
                
                const maxIdx = Math.max(0, totalItems - Math.floor(itemsVisible));
                if (currentIdx > maxIdx) currentIdx = maxIdx;
                
                const containerWidth = slider.parentElement.offsetWidth;
                const itemWidth = (containerWidth - (gap * (Math.ceil(itemsVisible) - 1))) / itemsVisible;
                const offset = currentIdx * (itemWidth + gap);
                
                slider.style.transform = `translateX(-${offset}px)`;
                
                prevBtn.style.opacity = currentIdx === 0 ? '0.4' : '1';
                prevBtn.style.pointerEvents = currentIdx === 0 ? 'none' : 'auto';
                nextBtn.style.opacity = currentIdx >= maxIdx ? '0.4' : '1';
                nextBtn.style.pointerEvents = currentIdx >= maxIdx ? 'none' : 'auto';
            }

            nextBtn.addEventListener('click', () => {
                const itemsVisible = window.innerWidth < 640 ? 1 : (window.innerWidth < 1024 ? 2 : 3);
                if (currentIdx < totalItems - itemsVisible) {
                    currentIdx++;
                    updateSlider();
                }
            });

            prevBtn.addEventListener('click', () => {
                if (currentIdx > 0) {
                    currentIdx--;
                    updateSlider();
                }
            });

            window.addEventListener('resize', updateSlider);
            updateSlider();
        });
    </script>


    <!-- Services Section - Bento Grid Style -->
    <section id="gallery-section" class="relative bg-gray-50 py-24 lg:py-32 px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <!-- Section Header -->
            <div class="text-center mb-16 lg:mb-20">
                <span class="inline-block text-sm font-semibold text-gray-400 tracking-widest uppercase mb-4">Curated For You</span>
                <h2 class="text-4xl lg:text-6xl font-light text-gray-900 mb-6 tracking-tight">
                    Shop by <span class="font-semibold">Service</span>
                </h2>
                <p class="text-lg text-gray-500 max-w-2xl mx-auto leading-relaxed">
                    Explore our thoughtfully curated services, each designed to complement your unique style and occasion.
                </p>
            </div>

            <!-- Bento Grid Layout -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8">
                <!-- Large Card - Hair Services -->
                <a href="http://localhost:8080/gallery.php?category=hair" class="group relative lg:col-span-2 lg:row-span-2 h-[500px] lg:h-auto rounded-[2rem] overflow-hidden hover-lift block">
                    <img 
                        src="https://images.unsplash.com/photo-1560066984-138dadb4c035?w=1200&q=85" 
                        alt="Hair Services Collection" 
                        class="w-full h-full object-cover img-zoom"
                        loading="lazy"
                    />
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/30 to-transparent"></div>
                    
                    <!-- Content -->
                    <div class="absolute inset-0 p-8 lg:p-12 flex flex-col justify-end">
                        <span class="inline-block w-fit bg-white/20 backdrop-blur-md text-white text-xs font-semibold px-4 py-1.5 rounded-full mb-4">Featured Service</span>
                        <h3 class="text-3xl lg:text-4xl font-semibold text-white mb-3 tracking-tight">Hair Services</h3>
                        <p class="text-white/80 text-base lg:text-lg mb-6 max-w-md leading-relaxed">Experience elegance with our premium hair styling and treatments.</p>
                        <div class="flex items-center gap-3 text-white font-medium">
                            <span>Explore Services</span>
                            <div class="w-10 h-10 rounded-full bg-white/20 backdrop-blur-md flex items-center justify-center group-hover:bg-white group-hover:text-gray-900 transition-all duration-300">
                                <svg class="w-5 h-5 transition-transform group-hover:translate-x-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                </a>

                <!-- Skin Treatments Card -->
                <a href="http://localhost:8080/gallery.php?category=skin" class="group relative h-[350px] lg:h-auto rounded-[2rem] overflow-hidden hover-lift block">
                    <img 
                        src="https://images.unsplash.com/photo-1522337360788-8b13dee7a37e?w=800&q=85" 
                        alt="Skin Treatments Collection" 
                        class="w-full h-full object-cover img-zoom"
                        loading="lazy"
                    />
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/30 to-transparent"></div>
                    
                    <div class="absolute inset-0 p-6 lg:p-8 flex flex-col justify-end">
                        <h3 class="text-2xl font-semibold text-white mb-2 tracking-tight">Skin Treatments</h3>
                        <p class="text-white/70 text-sm mb-4">Glow meets relaxation</p>
                        <div class="flex items-center gap-2 text-white text-sm font-medium">
                            <span>Book Now</span>
                            <svg class="w-4 h-4 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </div>
                    </div>
                </a>

                <!-- Nail Art Card -->
                <a href="http://localhost:8080/gallery.php?category=nails" class="group relative h-[350px] lg:h-auto rounded-[2rem] overflow-hidden hover-lift block">
                    <img 
                        src="https://images.unsplash.com/photo-1604654894610-df63bc536371?w=800&q=85" 
                        alt="Nail Art Collection" 
                        class="w-full h-full object-cover img-zoom"
                        loading="lazy"
                    />
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/30 to-transparent"></div>
                    
                    <div class="absolute inset-0 p-6 lg:p-8 flex flex-col justify-end">
                        <h3 class="text-2xl font-semibold text-white mb-2 tracking-tight">Nail Art</h3>
                        <p class="text-white/70 text-sm mb-4">Creativity & precision</p>
                        <div class="flex items-center gap-2 text-white text-sm font-medium">
                            <span>Book Now</span>
                            <svg class="w-4 h-4 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </div>
                    </div>
                </a>
            </div>

            <!-- View All Services -->
            <div class="text-center mt-12 lg:mt-16">
                <a href="http://localhost:8080/gallery.php" class="inline-flex items-center gap-3 px-8 py-4 bg-gray-900 text-white rounded-full font-semibold hover:bg-gray-800 transition-all duration-300 group">
                    View All Services
                    <svg class="w-5 h-5 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                    </svg>
                </a>
            </div>
        </div>
    </section>

    <!-- Featured Products Section - Premium Grid -->
    <section id="featured-products" class="relative bg-white py-24 lg:py-32 px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <!-- Section Header -->
            <div class="flex flex-col lg:flex-row justify-between items-start lg:items-end mb-16 gap-8">
                <div class="max-w-xl">
                    <span class="inline-block text-sm font-semibold text-gray-400 tracking-widest uppercase mb-4">Handpicked</span>
                    <h2 class="text-4xl lg:text-5xl font-light text-gray-900 mb-4 tracking-tight">
                        Featured <span class="font-semibold">Products</span>
                    </h2>
                    <p class="text-gray-500 text-lg leading-relaxed">Our most loved beauty products, meticulously selected for those who appreciate refined quality.</p>
                </div>
                <a href="http://localhost:8080/shop.php" class="group inline-flex items-center gap-2 text-gray-900 font-semibold hover:text-gray-600 transition-colors">
                    View All
                    <svg class="w-5 h-5 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                    </svg>
                </a>
            </div>

            <!-- Product Grid -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-8">
                <?php foreach ($featured_products as $index => $product) : 
                    $displayPrice = $product['discountedPrice'] ?? $product['price'];
                    $hasDiscount = !empty($product['discountedPrice']) && $product['discountedPrice'] < $product['price'];
                ?>
                    <!-- Product Structured Data -->
                    <script type="application/ld+json">
                    {
                      "@context": "https://schema.org",
                      "@type": "Product",
                      "name": "<?php echo htmlspecialchars($product['name']); ?>",
                      "image": "http://localhost:8080<?php echo htmlspecialchars($product['image']); ?>",
                      "description": "<?php echo htmlspecialchars($product['description'] ?? 'Premium quality ' . $product['name'] . ' from Parlour'); ?>",
                      "brand": {
                        "@type": "Brand",
                        "name": "Parlour"
                      },
                      "offers": {
                        "@type": "Offer",
                        "url": "http://localhost:8080/product.php?id=<?php echo $product['id']; ?>",
                        "priceCurrency": "PKR",
                        "price": "<?php echo $displayPrice; ?>",
                        "availability": "https://schema.org/InStock",
                        "itemCondition": "https://schema.org/NewCondition"
                      }
                    }
                    </script>
                    
                    <article itemscope itemtype="https://schema.org/Product" class="group">
                        <a href="http://localhost:8080/product.php?id=<?php echo $product['id']; ?>" class="block">
                            <div class="relative aspect-[3/4] rounded-2xl lg:rounded-3xl overflow-hidden mb-4 lg:mb-6 bg-gray-100">
                                <img 
                                    itemprop="image"
                                    src="<?php echo htmlspecialchars($product['image']); ?>" 
                                    alt="<?php echo htmlspecialchars($product['name'] . ' - ' . $product['category']); ?>" 
                                    class="w-full h-full object-cover img-zoom"
                                    loading="lazy"
                                >
                                <!-- Overlay on Hover -->
                                <div class="absolute inset-0 bg-gradient-to-t from-black/50 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                                
                                <!-- Badges -->
                                <div class="absolute top-3 lg:top-4 left-3 lg:left-4 flex flex-col gap-2">
                                    <?php if ($product['sale']) : ?>
                                        <span class="bg-rose-500 text-white text-[10px] lg:text-xs font-semibold px-2.5 lg:px-3 py-1 rounded-full">Sale</span>
                                    <?php endif; ?>
                                    <?php if ($product['featured']) : ?>
                                        <span class="bg-amber-500 text-white text-[10px] lg:text-xs font-semibold px-2.5 lg:px-3 py-1 rounded-full">Featured</span>
                                    <?php endif; ?>
                                </div>

                                <!-- Quick Actions -->
                                <div class="absolute bottom-4 left-4 right-4 opacity-0 group-hover:opacity-100 translate-y-4 group-hover:translate-y-0 transition-all duration-500 hidden lg:block">
                                    <div class="flex gap-2">
                                        <span class="flex-1 bg-white text-gray-900 py-3 rounded-full font-semibold text-sm flex items-center justify-center">
                                            Quick View
                                        </span>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Product Info -->
                            <div class="space-y-1.5 lg:space-y-2">
                                <p class="text-[10px] lg:text-xs text-gray-400 uppercase tracking-[0.15em] lg:tracking-[0.2em] font-medium"><?php echo htmlspecialchars($product['category']); ?></p>
                                <h3 itemprop="name" class="text-sm lg:text-base font-semibold text-gray-900 truncate group-hover:text-gray-600 transition-colors"><?php echo htmlspecialchars($product['name']); ?></h3>
                                <div itemprop="offers" itemscope itemtype="https://schema.org/Offer" class="flex items-center gap-2 flex-wrap">
                                    <meta itemprop="priceCurrency" content="PKR">
                                    <span itemprop="price" content="<?php echo $displayPrice; ?>" class="text-sm lg:text-base font-semibold text-gray-900">PKR <?php echo number_format($displayPrice, 0); ?></span>
                                    <?php if ($hasDiscount): ?>
                                        <span class="text-xs lg:text-sm text-gray-400 line-through">PKR <?php echo number_format($product['price'], 0); ?></span>
                                        <span class="text-xs font-semibold text-rose-500"><?php echo round((($product['price'] - $displayPrice) / $product['price']) * 100); ?>% off</span>
                                    <?php elseif ($product['originalPrice']): ?>
                                        <span class="text-xs lg:text-sm text-gray-400 line-through">PKR <?php echo number_format($product['originalPrice'], 0); ?></span>
                                    <?php endif; ?>
                                </div>
                                
                                <!-- Add to Cart Button -->
                                <button 
                                    onclick="addToCart(<?php echo $product['id']; ?>, '<?php echo htmlspecialchars($product['name'], ENT_QUOTES); ?>', <?php echo $displayPrice; ?>, '<?php echo htmlspecialchars($product['image'], ENT_QUOTES); ?>')"
                                    class="mt-3 w-full group relative overflow-hidden bg-gray-900 hover:bg-gray-800 text-white text-xs lg:text-sm font-semibold py-2.5 lg:py-3 px-4 rounded-full transition-all duration-300 transform hover:scale-105 hover:shadow-xl flex items-center justify-center gap-2"
                                >
                                    <svg class="w-4 h-4 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                                    </svg>
                                    <span class="relative z-10">Add to Cart</span>
                                    <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/20 to-transparent -translate-x-full group-hover:translate-x-full transition-transform duration-700"></div>
                                </button>
                            </div>
                        </a>
                    </article>
                <?php endforeach; ?>
            </div>

            <!-- Bottom CTA -->
            <div class="mt-16 lg:mt-20 text-center">
                <a href="http://localhost:8080/shop.php" class="inline-flex items-center gap-3 px-8 py-4 bg-gray-900 text-white rounded-full font-semibold hover:bg-gray-800 transition-all duration-300 group">
                    Explore Full Collection
                    <svg class="w-5 h-5 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                    </svg>
                </a>
            </div>
        </div>
    </section>

    <!-- Promotional Banner - Split Design -->
    <section class="relative py-0 overflow-hidden">
        <div class="grid lg:grid-cols-2 min-h-[600px]">
            <!-- Left: Image -->
            <div class="relative h-[400px] lg:h-auto">
                <img 
                    src="https://images.unsplash.com/photo-1487412947147-5cebf100ffc2?w=1000&q=85" 
                    alt="Parlour Gift Cards - Premium Beauty Gift" 
                    class="w-full h-full object-cover"
                    loading="lazy"
                />
                <div class="absolute inset-0 bg-black/20"></div>
            </div>
            
            <!-- Right: Content -->
            <div class="bg-gray-950 flex items-center justify-center p-12 lg:p-20">
                <div class="max-w-lg text-center lg:text-left">
                    <span class="inline-block text-sm font-semibold text-white/50 tracking-[0.3em] uppercase mb-6">The Perfect Gift</span>
                    <h2 class="text-4xl lg:text-5xl xl:text-6xl font-light text-white mb-6 leading-tight tracking-tight">
                        Give the Gift of <span class="font-semibold">Beauty</span>
                    </h2>
                    <p class="text-lg text-white/60 mb-10 leading-relaxed">
                        Let them choose their perfect beauty treatment. Our gift cards are the ideal present for any beauty enthusiast.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                        <a href="http://localhost:8080/shop.php" class="btn-elegant group inline-flex items-center justify-center gap-3 bg-white text-gray-900 px-8 py-4 rounded-full font-semibold hover:bg-gray-100 transition-all duration-300">
                            Purchase Gift Card
                            <svg class="w-5 h-5 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                            </svg>
                        </a>
                        <a href="http://localhost:8080/about.php" class="inline-flex items-center justify-center gap-2 text-white/80 hover:text-white font-medium transition-colors">
                            Learn More
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    </div>
                    
                    <!-- Gift Card Values -->
                    <div class="mt-12 flex flex-wrap gap-3 justify-center lg:justify-start">
                        <span class="px-4 py-2 rounded-full border border-white/20 text-white/70 text-sm font-medium">PKR 2,000</span>
                        <span class="px-4 py-2 rounded-full border border-white/20 text-white/70 text-sm font-medium">PKR 5,000</span>
                        <span class="px-4 py-2 rounded-full border border-white/20 text-white/70 text-sm font-medium">PKR 10,000</span>
                        <span class="px-4 py-2 rounded-full border border-white/20 text-white/70 text-sm font-medium">Custom</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Customer Reviews Section -->
    <section class="py-24 lg:py-32 overflow-hidden">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="text-center mb-16">
                <span class="inline-block text-sm font-semibold text-gray-500 tracking-[0.3em] uppercase mb-4">Testimonials</span>
                <h2 class="text-4xl lg:text-5xl font-light text-gray-900 mb-6 tracking-tight">
                    What Customers <span class="font-semibold">Say</span>
                </h2>
                <div class="flex justify-center gap-1">
                    <?php for($i=0; $i<5; $i++): ?>
                        <svg class="w-5 h-5 text-amber-400 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    <?php endfor; ?>
                </div>
            </div>
    
            <div class="relative">
                <div id="reviewSlider" class="flex transition-transform duration-500 ease-out gap-6">
                    <?php 
                    $reviews = [
                        ['name' => 'Sarah Johnson', 'role' => 'Beauty Enthusiast', 'text' => 'The hair styling service is exceptional. Expert precision at its finest. Definitely worth the investment.'],
                        ['name' => 'Michael Chen', 'role' => 'Business Executive', 'text' => 'The skin treatment perfectly blends relaxation with results. Best experience I\'ve had this year.'],
                        ['name' => 'Emma Williams', 'role' => 'Fitness Trainer', 'text' => 'The nail art service is incredibly detailed and beautiful. Perfect for my training sessions!'],
                        ['name' => 'James Wilson', 'role' => 'Investment Banker', 'text' => 'Premium service and perfect results. The hair treatment elevates my professional look.'],
                        ['name' => 'Olivia Brown', 'role' => 'Lifestyle Blogger', 'text' => 'I love the relaxing atmosphere. The services are versatile and work for any occasion, day or night.'],
                        ['name' => 'David Miller', 'role' => 'Architect', 'text' => 'The bridal package is both comprehensive and stunning. Robust service quality with attention to detail.'],
                    ];
                    foreach ($reviews as $review): 
                    ?>
                        <div class="min-w-[100%] md:min-w-[calc(50%-12px)] lg:min-w-[calc(33.333%-16px)]">
                            <div class="bg-gray-50 p-8 rounded-3xl h-full flex flex-col border border-gray-100">
                                <div class="flex gap-1 mb-4">
                                    <?php for($i=0; $i<5; $i++): ?>
                                        <svg class="w-4 h-4 text-amber-400 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                    <?php endfor; ?>
                                </div>
                                <p class="text-gray-600 leading-relaxed mb-6 flex-grow">"<?php echo $review['text']; ?>"</p>
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-gray-200 rounded-full flex items-center justify-center">
                                        <span class="text-sm font-semibold text-gray-600"><?php echo substr($review['name'], 0, 1); ?></span>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-gray-900 text-sm"><?php echo $review['name']; ?></h4>
                                        <p class="text-xs text-gray-500"><?php echo $review['role']; ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
    
                <div class="flex justify-center mt-10 gap-3">
                    <button id="revPrev" class="w-11 h-11 rounded-full bg-white border border-gray-200 flex items-center justify-center hover:bg-gray-900 hover:text-white hover:border-gray-900 transition-all duration-300 shadow-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    </button>
                    <button id="revNext" class="w-11 h-11 rounded-full bg-white border border-gray-200 flex items-center justify-center hover:bg-gray-900 hover:text-white hover:border-gray-900 transition-all duration-300 shadow-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </button>
                </div>
            </div>
        </div>
    </section>
    
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const slider = document.getElementById('reviewSlider');
        const prev = document.getElementById('revPrev');
        const next = document.getElementById('revNext');
        let current = 0;
        const total = 6;
    
        function updateSlider() {
            const isMobile = window.innerWidth < 768;
            const isTablet = window.innerWidth >= 768 && window.innerWidth < 1024;
            let visible = isMobile ? 1 : (isTablet ? 2 : 3);
            const max = total - visible;
            if (current > max) current = max;
            if (current < 0) current = 0;
            const gap = 24;
            const width = slider.parentElement.offsetWidth;
            const itemWidth = (width - (gap * (visible - 1))) / visible;
            slider.style.transform = `translateX(-${current * (itemWidth + gap)}px)`;
        }
    
        next?.addEventListener('click', () => {
            const visible = window.innerWidth < 768 ? 1 : (window.innerWidth < 1024 ? 2 : 3);
            current = current < total - visible ? current + 1 : 0;
            updateSlider();
        });
    
        prev?.addEventListener('click', () => {
            const visible = window.innerWidth < 768 ? 1 : (window.innerWidth < 1024 ? 2 : 3);
            current = current > 0 ? current - 1 : total - visible;
            updateSlider();
        });
    
        window.addEventListener('resize', updateSlider);
        updateSlider();
    });
    </script>

    <!-- Service Features Section - Refined Cards -->
    <section class="py-24 lg:py-12 bg-white">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <!-- Section Header -->
            <div class="text-center mb-16 lg:mb-20">
                <span class="inline-block text-sm font-semibold text-gray-400 tracking-widest uppercase mb-4">Why Choose Us</span>
                <h2 class="text-4xl lg:text-5xl font-light text-gray-900 mb-6 tracking-tight">
                    The Parlour <span class="font-semibold">Experience</span>
                </h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 lg:gap-12">
                <!-- Feature 1: Expert Stylists -->
                <div class="group relative p-8 lg:p-10 rounded-3xl bg-gray-50 hover:bg-gray-900 transition-all duration-500 hover-lift">
                    <div class="w-16 h-16 bg-white rounded-2xl flex items-center justify-center mb-8 shadow-sm group-hover:bg-white/10 transition-colors duration-500">
                        <svg class="w-8 h-8 text-gray-900 group-hover:text-white transition-colors duration-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                    <h3 class="text-xl lg:text-2xl font-semibold text-gray-900 group-hover:text-white mb-4 tracking-tight transition-colors duration-500">Expert Stylists</h3>
                    <p class="text-gray-500 group-hover:text-white/70 leading-relaxed transition-colors duration-500">
                        Our team of expert stylists are dedicated to enhancing your beauty with precision and care.
                    </p>
                    
                    <!-- Decorative Arrow -->
                    <div class="absolute bottom-8 right-8 w-10 h-10 rounded-full bg-gray-200 group-hover:bg-white flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all duration-500 translate-y-2 group-hover:translate-y-0">
                        <svg class="w-5 h-5 text-gray-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                        </svg>
                    </div>
                </div>

                <!-- Feature 2: Premium Products -->
                <div class="group relative p-8 lg:p-10 rounded-3xl bg-gray-50 hover:bg-gray-900 transition-all duration-500 hover-lift">
                    <div class="w-16 h-16 bg-white rounded-2xl flex items-center justify-center mb-8 shadow-sm group-hover:bg-white/10 transition-colors duration-500">
                        <svg class="w-8 h-8 text-gray-900 group-hover:text-white transition-colors duration-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                        </svg>
                    </div>
                    <h3 class="text-xl lg:text-2xl font-semibold text-gray-900 group-hover:text-white mb-4 tracking-tight transition-colors duration-500">Premium Products</h3>
                    <p class="text-gray-500 group-hover:text-white/70 leading-relaxed transition-colors duration-500">
                        We use only the finest beauty products to ensure exceptional results for every treatment.
                    </p>
                    
                    <div class="absolute bottom-8 right-8 w-10 h-10 rounded-full bg-gray-200 group-hover:bg-white flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all duration-500 translate-y-2 group-hover:translate-y-0">
                        <svg class="w-5 h-5 text-gray-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                        </svg>
                    </div>
                </div>

                <!-- Feature 3: Relaxing Experience -->
                <div class="group relative p-8 lg:p-10 rounded-3xl bg-gray-50 hover:bg-gray-900 transition-all duration-500 hover-lift">
                    <div class="w-16 h-16 bg-white rounded-2xl flex items-center justify-center mb-8 shadow-sm group-hover:bg-white/10 transition-colors duration-500">
                        <svg class="w-8 h-8 text-gray-900 group-hover:text-white transition-colors duration-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                    <h3 class="text-xl lg:text-2xl font-semibold text-gray-900 group-hover:text-white mb-4 tracking-tight transition-colors duration-500">Relaxing Experience</h3>
                    <p class="text-gray-500 group-hover:text-white/70 leading-relaxed transition-colors duration-500">
                        Our serene environment is designed to help you unwind and enjoy your beauty journey.
                    </p>
                    
                    <div class="absolute bottom-8 right-8 w-10 h-10 rounded-full bg-gray-200 group-hover:bg-white flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all duration-500 translate-y-2 group-hover:translate-y-0">
                        <svg class="w-5 h-5 text-gray-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Cart Notification Toast -->
<div id="cartToast" class="fixed top-6 right-6 z-50 transform translate-x-[500px] transition-all duration-500 ease-out">
    <div class="bg-gray-900 text-white px-6 py-4 rounded-2xl shadow-2xl flex items-center gap-4 min-w-[320px]">
        <div class="w-12 h-12 rounded-full bg-emerald-500 flex items-center justify-center flex-shrink-0">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
        </div>
        <div class="flex-1">
            <h4 class="font-semibold text-sm mb-1">Added to Cart!</h4>
            <p id="toastMessage" class="text-white/70 text-xs"></p>
        </div>
        <a href="http://localhost:8080/cart.php" class="text-xs font-semibold hover:underline">View Cart</a>
    </div>
</div>

<!-- Add to Cart JavaScript -->
<script>
function addToCart(productId, productName, productPrice, productImage) {
    // Disable button temporarily
    event.target.disabled = true;
    event.target.innerHTML = '<svg class="w-4 h-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg><span class="ml-2">Adding...</span>';
    
    // Prepare data
    const formData = new FormData();
    formData.append('action', 'add');
    formData.append('product_id', productId);
    formData.append('quantity', 1);
    
    // Send AJAX request
    fetch('/api/cart.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Show success notification
            showCartToast(productName);
            
            // Reset button
            event.target.disabled = false;
            event.target.innerHTML = `
                <svg class="w-4 h-4 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                </svg>
                <span class="relative z-10">Add to Cart</span>
                <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/20 to-transparent -translate-x-full group-hover:translate-x-full transition-transform duration-700"></div>
            `;
            
            // Update cart count if exists
            updateCartCount();
        } else {
            // Show error
            alert(data.message || 'Failed to add item to cart. Please try again.');
            event.target.disabled = false;
            event.target.innerHTML = `
                <svg class="w-4 h-4 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                </svg>
                <span class="relative z-10">Add to Cart</span>
                <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/20 to-transparent -translate-x-full group-hover:translate-x-full transition-transform duration-700"></div>
            `;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred. Please try again.');
        event.target.disabled = false;
        event.target.innerHTML = `
            <svg class="w-4 h-4 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
            </svg>
            <span class="relative z-10">Add to Cart</span>
            <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/20 to-transparent -translate-x-full group-hover:translate-x-full transition-transform duration-700"></div>
        `;
    });
}

function showCartToast(productName) {
    const toast = document.getElementById('cartToast');
    const message = document.getElementById('toastMessage');
    message.textContent = productName;
    
    // Show toast
    toast.style.transform = 'translateX(0)';
    
    // Hide after 4 seconds
    setTimeout(() => {
        toast.style.transform = 'translateX(500px)';
    }, 4000);
}

function updateCartCount() {
    // Update cart count in header if it exists
    fetch('/api/cart.php?action=count')
        .then(response => response.json())
        .then(data => {
            const cartBadge = document.querySelector('[data-cart-count]');
            if (cartBadge && data.count) {
                cartBadge.textContent = data.count;
                cartBadge.classList.remove('hidden');
            }
        })
        .catch(error => console.error('Error updating cart count:', error));
}
</script>

<?php include __DIR__ . '/includes/footer.php'; ?>
