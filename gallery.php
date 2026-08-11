<?php
// php_web/gallery.php
include __DIR__ . '/includes/header.php';
?>

<!-- Hero Section -->
<div class="relative h-[60vh] min-h-[500px] flex items-center justify-center overflow-hidden">
    <div class="absolute inset-0 -z-10">
        <img 
            src="https://images.unsplash.com/photo-1560066984-138dadb4c035?w=1920&q=85" 
            alt="Parlour Gallery" 
            class="w-full h-full object-cover"
        />
        <div class="absolute inset-0 bg-gradient-to-r from-black/70 via-black/50 to-transparent"></div>
    </div>
    
    <div class="relative z-10 text-center px-6">
        <span class="inline-block text-sm font-semibold text-white/70 tracking-[0.3em] uppercase mb-4">Our Work</span>
        <h1 class="text-5xl lg:text-6xl font-light text-white mb-4 tracking-tight">
            Gallery <span class="font-semibold">Showcase</span>
        </h1>
        <p class="text-lg text-white/70 max-w-xl mx-auto">
            Browse through our latest work and see the artistry of our expert stylists.
        </p>
    </div>
</div>

<!-- Gallery Filter -->
<section class="py-12 bg-white sticky top-24 z-30 border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-6 lg:px-8">
        <div class="flex flex-wrap justify-center gap-3">
            <button class="gallery-filter active px-6 py-2.5 rounded-full text-sm font-medium transition-all duration-300 bg-gray-900 text-white" data-filter="all">
                All Work
            </button>
            <button class="gallery-filter px-6 py-2.5 rounded-full text-sm font-medium transition-all duration-300 bg-gray-100 text-gray-600 hover:bg-gray-200" data-filter="hair">
                Hair
            </button>
            <button class="gallery-filter px-6 py-2.5 rounded-full text-sm font-medium transition-all duration-300 bg-gray-100 text-gray-600 hover:bg-gray-200" data-filter="skin">
                Skin
            </button>
            <button class="gallery-filter px-6 py-2.5 rounded-full text-sm font-medium transition-all duration-300 bg-gray-100 text-gray-600 hover:bg-gray-200" data-filter="nails">
                Nails
            </button>
            <button class="gallery-filter px-6 py-2.5 rounded-full text-sm font-medium transition-all duration-300 bg-gray-100 text-gray-600 hover:bg-gray-200" data-filter="bridal">
                Bridal
            </button>
        </div>
    </div>
</section>

<!-- Gallery Grid -->
<section class="py-12 lg:py-16">
    <div class="max-w-7xl mx-auto px-6 lg:px-8">
        <div class="columns-1 sm:columns-2 lg:columns-3 gap-4 space-y-4">
            <!-- Hair Work -->
            <div class="gallery-item break-inside-avoid" data-category="hair">
                <div class="group relative rounded-2xl overflow-hidden bg-gray-100">
                    <img 
                        src="https://images.unsplash.com/photo-1560066984-138dadb4c035?w=800&q=80" 
                        alt="Hair Styling Work" 
                        class="w-full object-cover transition-transform duration-700 group-hover:scale-105"
                        loading="lazy"
                    >
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    <div class="absolute bottom-4 left-4 right-4 opacity-0 group-hover:opacity-100 translate-y-4 group-hover:translate-y-0 transition-all duration-500">
                        <span class="bg-white/90 backdrop-blur-sm text-gray-900 px-4 py-2 rounded-full text-xs font-semibold">Hair Styling</span>
                    </div>
                </div>
            </div>

            <div class="gallery-item break-inside-avoid" data-category="nails">
                <div class="group relative rounded-2xl overflow-hidden bg-gray-100">
                    <img 
                        src="https://images.unsplash.com/photo-1604654894610-df63bc536371?w=800&q=80" 
                        alt="Nail Art Work" 
                        class="w-full object-cover transition-transform duration-700 group-hover:scale-105"
                        loading="lazy"
                    >
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    <div class="absolute bottom-4 left-4 right-4 opacity-0 group-hover:opacity-100 translate-y-4 group-hover:translate-y-0 transition-all duration-500">
                        <span class="bg-white/90 backdrop-blur-sm text-gray-900 px-4 py-2 rounded-full text-xs font-semibold">Nail Art</span>
                    </div>
                </div>
            </div>

            <div class="gallery-item break-inside-avoid" data-category="skin">
                <div class="group relative rounded-2xl overflow-hidden bg-gray-100">
                    <img 
                        src="https://images.unsplash.com/photo-1522337360788-8b13dee7a37e?w=800&q=80" 
                        alt="Skin Treatment Work" 
                        class="w-full object-cover transition-transform duration-700 group-hover:scale-105"
                        loading="lazy"
                    >
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    <div class="absolute bottom-4 left-4 right-4 opacity-0 group-hover:opacity-100 translate-y-4 group-hover:translate-y-0 transition-all duration-500">
                        <span class="bg-white/90 backdrop-blur-sm text-gray-900 px-4 py-2 rounded-full text-xs font-semibold">Skin Treatment</span>
                    </div>
                </div>
            </div>

            <div class="gallery-item break-inside-avoid" data-category="bridal">
                <div class="group relative rounded-2xl overflow-hidden bg-gray-100">
                    <img 
                        src="https://images.unsplash.com/photo-1519741497674-611481863552?w=800&q=80" 
                        alt="Bridal Styling" 
                        class="w-full object-cover transition-transform duration-700 group-hover:scale-105"
                        loading="lazy"
                    >
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    <div class="absolute bottom-4 left-4 right-4 opacity-0 group-hover:opacity-100 translate-y-4 group-hover:translate-y-0 transition-all duration-500">
                        <span class="bg-white/90 backdrop-blur-sm text-gray-900 px-4 py-2 rounded-full text-xs font-semibold">Bridal</span>
                    </div>
                </div>
            </div>

            <div class="gallery-item break-inside-avoid" data-category="hair">
                <div class="group relative rounded-2xl overflow-hidden bg-gray-100">
                    <img 
                        src="https://images.unsplash.com/photo-1522337360788-8b13dee7a37e?w=800&q=80" 
                        alt="Hair Color Work" 
                        class="w-full object-cover transition-transform duration-700 group-hover:scale-105"
                        loading="lazy"
                    >
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    <div class="absolute bottom-4 left-4 right-4 opacity-0 group-hover:opacity-100 translate-y-4 group-hover:translate-y-0 transition-all duration-500">
                        <span class="bg-white/90 backdrop-blur-sm text-gray-900 px-4 py-2 rounded-full text-xs font-semibold">Hair Color</span>
                    </div>
                </div>
            </div>

            <div class="gallery-item break-inside-avoid" data-category="nails">
                <div class="group relative rounded-2xl overflow-hidden bg-gray-100">
                    <img 
                        src="https://images.unsplash.com/photo-1604654894610-df63bc536371?w=800&q=80" 
                        alt="Manicure Work" 
                        class="w-full object-cover transition-transform duration-700 group-hover:scale-105"
                        loading="lazy"
                    >
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    <div class="absolute bottom-4 left-4 right-4 opacity-0 group-hover:opacity-100 translate-y-4 group-hover:translate-y-0 transition-all duration-500">
                        <span class="bg-white/90 backdrop-blur-sm text-gray-900 px-4 py-2 rounded-full text-xs font-semibold">Manicure</span>
                    </div>
                </div>
            </div>

            <div class="gallery-item break-inside-avoid" data-category="skin">
                <div class="group relative rounded-2xl overflow-hidden bg-gray-100">
                    <img 
                        src="https://images.unsplash.com/photo-1570172619644-dfd03ed5d881?w=800&q=80" 
                        alt="Facial Treatment" 
                        class="w-full object-cover transition-transform duration-700 group-hover:scale-105"
                        loading="lazy"
                    >
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    <div class="absolute bottom-4 left-4 right-4 opacity-0 group-hover:opacity-100 translate-y-4 group-hover:translate-y-0 transition-all duration-500">
                        <span class="bg-white/90 backdrop-blur-sm text-gray-900 px-4 py-2 rounded-full text-xs font-semibold">Facial</span>
                    </div>
                </div>
            </div>

            <div class="gallery-item break-inside-avoid" data-category="bridal">
                <div class="group relative rounded-2xl overflow-hidden bg-gray-100">
                    <img 
                        src="https://images.unsplash.com/photo-1595476108010-b4d1f102b1b1?w=800&q=80" 
                        alt="Bridal Makeup" 
                        class="w-full object-cover transition-transform duration-700 group-hover:scale-105"
                        loading="lazy"
                    >
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    <div class="absolute bottom-4 left-4 right-4 opacity-0 group-hover:opacity-100 translate-y-4 group-hover:translate-y-0 transition-all duration-500">
                        <span class="bg-white/90 backdrop-blur-sm text-gray-900 px-4 py-2 rounded-full text-xs font-semibold">Bridal Makeup</span>
                    </div>
                </div>
            </div>

            <div class="gallery-item break-inside-avoid" data-category="hair">
                <div class="group relative rounded-2xl overflow-hidden bg-gray-100">
                    <img 
                        src="https://images.unsplash.com/photo-1595476108010-b4d1f102b1b1?w=800&q=80" 
                        alt="Hair Treatment" 
                        class="w-full object-cover transition-transform duration-700 group-hover:scale-105"
                        loading="lazy"
                    >
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    <div class="absolute bottom-4 left-4 right-4 opacity-0 group-hover:opacity-100 translate-y-4 group-hover:translate-y-0 transition-all duration-500">
                        <span class="bg-white/90 backdrop-blur-sm text-gray-900 px-4 py-2 rounded-full text-xs font-semibold">Hair Treatment</span>
                    </div>
                </div>
            </div>

            <div class="gallery-item break-inside-avoid" data-category="nails">
                <div class="group relative rounded-2xl overflow-hidden bg-gray-100">
                    <img 
                        src="https://images.unsplash.com/photo-1604654894610-df63bc536371?w=800&q=80" 
                        alt="Pedicure Work" 
                        class="w-full object-cover transition-transform duration-700 group-hover:scale-105"
                        loading="lazy"
                    >
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    <div class="absolute bottom-4 left-4 right-4 opacity-0 group-hover:opacity-100 translate-y-4 group-hover:translate-y-0 transition-all duration-500">
                        <span class="bg-white/90 backdrop-blur-sm text-gray-900 px-4 py-2 rounded-full text-xs font-semibold">Pedicure</span>
                    </div>
                </div>
            </div>

            <div class="gallery-item break-inside-avoid" data-category="skin">
                <div class="group relative rounded-2xl overflow-hidden bg-gray-100">
                    <img 
                        src="https://images.unsplash.com/photo-1570172619644-dfd03ed5d881?w=800&q=80" 
                        alt="Skin Care Treatment" 
                        class="w-full object-cover transition-transform duration-700 group-hover:scale-105"
                        loading="lazy"
                    >
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    <div class="absolute bottom-4 left-4 right-4 opacity-0 group-hover:opacity-100 translate-y-4 group-hover:translate-y-0 transition-all duration-500">
                        <span class="bg-white/90 backdrop-blur-sm text-gray-900 px-4 py-2 rounded-full text-xs font-semibold">Skin Care</span>
                    </div>
                </div>
            </div>

            <div class="gallery-item break-inside-avoid" data-category="bridal">
                <div class="group relative rounded-2xl overflow-hidden bg-gray-100">
                    <img 
                        src="https://images.unsplash.com/photo-1519741497674-611481863552?w=800&q=80" 
                        alt="Bridal Hair" 
                        class="w-full object-cover transition-transform duration-700 group-hover:scale-105"
                        loading="lazy"
                    >
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    <div class="absolute bottom-4 left-4 right-4 opacity-0 group-hover:opacity-100 translate-y-4 group-hover:translate-y-0 transition-all duration-500">
                        <span class="bg-white/90 backdrop-blur-sm text-gray-900 px-4 py-2 rounded-full text-xs font-semibold">Bridal Hair</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-24 bg-gray-50">
    <div class="max-w-4xl mx-auto px-6 lg:px-8 text-center">
        <h2 class="text-3xl lg:text-4xl font-light text-gray-900 mb-6 tracking-tight">
            Ready to <span class="font-semibold">Transform</span>?
        </h2>
        <p class="text-lg text-gray-600 mb-8 max-w-2xl mx-auto">
            Book your appointment today and let our expert stylists create something beautiful for you.
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="http://localhost:8080/shop.php" class="inline-flex items-center justify-center gap-3 bg-gray-900 text-white px-8 py-4 rounded-full font-semibold hover:bg-gray-800 transition-all duration-300">
                Book Now
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                </svg>
            </a>
            <a href="http://localhost:8080/contact.php" class="inline-flex items-center justify-center gap-3 bg-white text-gray-900 px-8 py-4 rounded-full font-semibold border border-gray-200 hover:bg-gray-50 transition-all duration-300">
                Contact Us
            </a>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const filterButtons = document.querySelectorAll('.gallery-filter');
    const galleryItems = document.querySelectorAll('.gallery-item');
    
    filterButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Update active state
            filterButtons.forEach(btn => {
                btn.classList.remove('active', 'bg-gray-900', 'text-white');
                btn.classList.add('bg-gray-100', 'text-gray-600');
            });
            this.classList.add('active', 'bg-gray-900', 'text-white');
            this.classList.remove('bg-gray-100', 'text-gray-600');
            
            const filter = this.dataset.filter;
            
            galleryItems.forEach(item => {
                if (filter === 'all' || item.dataset.category === filter) {
                    item.style.display = 'block';
                    item.style.opacity = '0';
                    item.style.transform = 'scale(0.95)';
                    setTimeout(() => {
                        item.style.transition = 'all 0.4s ease';
                        item.style.opacity = '1';
                        item.style.transform = 'scale(1)';
                    }, 50);
                } else {
                    item.style.opacity = '0';
                    item.style.transform = 'scale(0.95)';
                    setTimeout(() => {
                        item.style.display = 'none';
                    }, 400);
                }
            });
        });
    });
});
</script>

<?php include __DIR__ . '/includes/footer.php'; ?>
