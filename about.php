<?php
// php_web/about.php
include __DIR__ . '/includes/header.php';
?>

<!-- Hero Section -->
<div class="relative h-[60vh] min-h-[500px] flex items-center justify-center overflow-hidden">
    <div class="absolute inset-0 -z-10">
        <img 
            src="https://images.unsplash.com/photo-1560066984-138dadb4c035?w=1920&q=85" 
            alt="Parlour Beauty Salon" 
            class="w-full h-full object-cover"
        />
        <div class="absolute inset-0 bg-gradient-to-r from-black/70 via-black/50 to-transparent"></div>
    </div>
    
    <div class="relative z-10 text-center px-6 max-w-4xl mx-auto">
        <span class="inline-block text-sm font-semibold text-white/70 tracking-[0.3em] uppercase mb-4">Est. 2025</span>
        <h1 class="text-5xl lg:text-7xl font-light text-white mb-6 tracking-tight">
            Our <span class="font-semibold">Story</span>
        </h1>
        <p class="text-xl text-white/80 max-w-2xl mx-auto leading-relaxed">
            Crafting beauty experiences that transcend generations
        </p>
    </div>
</div>

<!-- Story Section -->
<section class="py-24 lg:py-32">
    <div class="max-w-7xl mx-auto px-6 lg:px-8">
        <div class="grid lg:grid-cols-2 gap-16 items-center">
            <div>
                <span class="inline-block text-sm font-semibold text-gray-500 tracking-[0.3em] uppercase mb-4">About Us</span>
                <h2 class="text-4xl lg:text-5xl font-light text-gray-900 mb-8 tracking-tight leading-tight">
                    Timeless <span class="font-semibold">Precision</span>
                </h2>
                <div class="space-y-6 text-gray-600 leading-relaxed">
                    <p>Founded in 2025, Parlour was born from a simple vision: to offer exceptional beauty services that transcend generations. We believe that great beauty care isn't just about looking good—it's about making a statement and appreciating fine artistry.</p>
                    <p>We believe that beauty services are more than just treatments; they're expressions of personality and confidence. Our mission is to provide high-quality, precision beauty services that empower individuals to feel their best every day.</p>
                    <p>Every service in our collection is carefully curated and delivered with excellence, ensuring that you receive only the finest beauty experience. From stylist selection to final touches, we maintain the highest standards.</p>
                </div>
                
                <!-- Stats -->
                <div class="grid grid-cols-3 gap-8 mt-12 pt-12 border-t border-gray-100">
                    <div>
                        <span class="block text-4xl font-semibold text-gray-900 mb-1">10K+</span>
                        <span class="text-sm text-gray-500">Happy Clients</span>
                    </div>
                    <div>
                        <span class="block text-4xl font-semibold text-gray-900 mb-1">200+</span>
                        <span class="text-sm text-gray-500">Services</span>
                    </div>
                    <div>
                        <span class="block text-4xl font-semibold text-gray-900 mb-1">15+</span>
                        <span class="text-sm text-gray-500">Expert Stylists</span>
                    </div>
                </div>
            </div>
            <div class="relative">
                <div class="aspect-[4/5] rounded-3xl overflow-hidden">
                    <img src="https://images.unsplash.com/photo-1558171813-4c088753af8f?w=800&q=80" alt="Our Workshop" class="w-full h-full object-cover">
                </div>
                <div class="absolute -bottom-8 -left-8 w-48 h-48 bg-gray-900 rounded-3xl flex items-center justify-center text-white p-6 shadow-2xl hidden lg:flex">
                    <div class="text-center">
                        <span class="block text-5xl font-bold">2025</span>
                        <span class="text-sm text-gray-400">Founded</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Services Section -->
<section class="py-24 lg:py-32 bg-gray-50/50">
    <div class="max-w-7xl mx-auto px-6 lg:px-8">
        <div class="text-center mb-16">
            <span class="inline-block text-sm font-semibold text-gray-500 tracking-[0.3em] uppercase mb-4">Discover</span>
            <h2 class="text-4xl lg:text-5xl font-light text-gray-900 mb-6 tracking-tight">
                What We <span class="font-semibold">Offer</span>
            </h2>
            <p class="text-gray-600 max-w-2xl mx-auto">
                Discover our curated selection of beauty services designed for every lifestyle.
            </p>
        </div>

        <!-- Desktop Grid View -->
        <div class="hidden md:grid md:grid-cols-3 gap-6">
            <a href="http://localhost:8080/collections.php?category=hair" class="group relative aspect-[3/4] rounded-3xl overflow-hidden">
                <img src="https://images.unsplash.com/photo-1560066984-138dadb4c035?w=800&q=80" alt="Hair Services" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700" loading="lazy"/>
                <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent"></div>
                <div class="absolute bottom-8 left-8 right-8 text-white">
                    <h3 class="text-2xl font-semibold mb-2">Hair Services</h3>
                    <p class="text-white/70 text-sm">Premium hair styling with exquisite craftsmanship.</p>
                </div>
            </a>

            <a href="http://localhost:8080/collections.php?category=skin" class="group relative aspect-[3/4] rounded-3xl overflow-hidden">
                <img src="https://images.unsplash.com/photo-1522337360788-8b13dee7a37e?w=800&q=80" alt="Skin Treatments" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700" loading="lazy"/>
                <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent"></div>
                <div class="absolute bottom-8 left-8 right-8 text-white">
                    <h3 class="text-2xl font-semibold mb-2">Skin Treatments</h3>
                    <p class="text-white/70 text-sm">Refreshing treatments for active lifestyles.</p>
                </div>
            </a>

            <a href="http://localhost:8080/collections.php?category=nails" class="group relative aspect-[3/4] rounded-3xl overflow-hidden">
                <img src="https://images.unsplash.com/photo-1604654894610-df63bc536371?w=800&q=80" alt="Nail Art" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700" loading="lazy"/>
                <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent"></div>
                <div class="absolute bottom-8 left-8 right-8 text-white">
                    <h3 class="text-2xl font-semibold mb-2">Nail Art</h3>
                    <p class="text-white/70 text-sm">Creative artistry meets elegant design.</p>
                </div>
            </a>
        </div>

        <!-- Mobile Slider View -->
        <div class="md:hidden relative overflow-hidden">
            <div id="collectionSlider" class="flex transition-transform duration-500 ease-out">
                <div class="min-w-full px-2">
                    <a href="http://localhost:8080/collections.php?category=hair" class="group relative aspect-[3/4] rounded-3xl overflow-hidden block">
                        <img src="https://images.unsplash.com/photo-1560066984-138dadb4c035?w=800&q=80" alt="Hair Services" class="w-full h-full object-cover" loading="lazy"/>
                        <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent"></div>
                        <div class="absolute bottom-8 left-8 right-8 text-white">
                            <h3 class="text-2xl font-semibold mb-2">Hair Services</h3>
                            <p class="text-white/70 text-sm">Premium hair styling with exquisite craftsmanship.</p>
                        </div>
                    </a>
                </div>
                <div class="min-w-full px-2">
                    <a href="http://localhost:8080/collections.php?category=skin" class="group relative aspect-[3/4] rounded-3xl overflow-hidden block">
                        <img src="https://images.unsplash.com/photo-1522337360788-8b13dee7a37e?w=800&q=80" alt="Skin Treatments" class="w-full h-full object-cover" loading="lazy"/>
                        <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent"></div>
                        <div class="absolute bottom-8 left-8 right-8 text-white">
                            <h3 class="text-2xl font-semibold mb-2">Skin Treatments</h3>
                            <p class="text-white/70 text-sm">Refreshing treatments for active lifestyles.</p>
                        </div>
                    </a>
                </div>
                <div class="min-w-full px-2">
                    <a href="http://localhost:8080/collections.php?category=nails" class="group relative aspect-[3/4] rounded-3xl overflow-hidden block">
                        <img src="https://images.unsplash.com/photo-1604654894610-df63bc536371?w=800&q=80" alt="Nail Art" class="w-full h-full object-cover" loading="lazy"/>
                        <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent"></div>
                        <div class="absolute bottom-8 left-8 right-8 text-white">
                            <h3 class="text-2xl font-semibold mb-2">Nail Art</h3>
                            <p class="text-white/70 text-sm">Creative artistry meets elegant design.</p>
                        </div>
                    </a>
                </div>
            </div>
            <div class="flex justify-center mt-6 gap-2">
                <div class="collection-dot w-8 h-1 rounded-full bg-gray-900 transition-all duration-300"></div>
                <div class="collection-dot w-2 h-1 rounded-full bg-gray-300 transition-all duration-300"></div>
                <div class="collection-dot w-2 h-1 rounded-full bg-gray-300 transition-all duration-300"></div>
            </div>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const collectionSlider = document.getElementById('collectionSlider');
    if (!collectionSlider) return;
    
    const dots = document.querySelectorAll('.collection-dot');
    let currentSlide = 0;
    const totalSlides = 3;

    function updateCollectionSlider() {
        collectionSlider.style.transform = `translateX(-${currentSlide * 100}%)`;
        dots.forEach((dot, i) => {
            dot.classList.toggle('w-8', i === currentSlide);
            dot.classList.toggle('bg-gray-900', i === currentSlide);
            dot.classList.toggle('w-2', i !== currentSlide);
            dot.classList.toggle('bg-gray-300', i !== currentSlide);
        });
    }

    setInterval(() => {
        currentSlide = (currentSlide + 1) % totalSlides;
        updateCollectionSlider();
    }, 5000);

    let touchStartX = 0;
    collectionSlider.addEventListener('touchstart', (e) => touchStartX = e.changedTouches[0].screenX);
    collectionSlider.addEventListener('touchend', (e) => {
        const diff = e.changedTouches[0].screenX - touchStartX;
        if (Math.abs(diff) > 50) {
            currentSlide = diff < 0 ? (currentSlide + 1) % totalSlides : (currentSlide - 1 + totalSlides) % totalSlides;
            updateCollectionSlider();
        }
    });
});
</script>

<!-- Service Features Section -->
<section class="py-24 lg:py-32">
    <div class="max-w-7xl mx-auto px-6 lg:px-8">
        <div class="text-center mb-16">
            <span class="inline-block text-sm font-semibold text-gray-500 tracking-[0.3em] uppercase mb-4">Why Choose Us</span>
            <h2 class="text-4xl lg:text-5xl font-light text-gray-900 tracking-tight">
                Our <span class="font-semibold">Promise</span>
            </h2>
        </div>
        
        <div class="grid md:grid-cols-3 gap-8">
            <div class="bg-white p-8 rounded-3xl border border-gray-100 hover:shadow-xl transition-shadow duration-500 text-center">
                <div class="w-16 h-16 bg-gray-50 rounded-2xl flex items-center justify-center mx-auto mb-6">
                    <svg class="w-7 h-7 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-3">Secure Payments</h3>
                <p class="text-gray-600 text-sm leading-relaxed">
                    Shop with peace of mind. Your transactions are protected with industry-leading encryption.
                </p>
            </div>

            <div class="bg-white p-8 rounded-3xl border border-gray-100 hover:shadow-xl transition-shadow duration-500 text-center">
                <div class="w-16 h-16 bg-gray-50 rounded-2xl flex items-center justify-center mx-auto mb-6">
                    <svg class="w-7 h-7 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-3">Delivered with Care</h3>
                <p class="text-gray-600 text-sm leading-relaxed">
                    Each order is handled with utmost care to ensure it arrives in pristine condition.
                </p>
            </div>

            <div class="bg-white p-8 rounded-3xl border border-gray-100 hover:shadow-xl transition-shadow duration-500 text-center">
                <div class="w-16 h-16 bg-gray-50 rounded-2xl flex items-center justify-center mx-auto mb-6">
                    <svg class="w-7 h-7 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-3">Excellent Service</h3>
                <p class="text-gray-600 text-sm leading-relaxed">
                    Our dedicated support team is here to assist you at every step. Your satisfaction is our priority.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- FAQs Section -->
<section class="py-24 lg:py-32 bg-gray-50/50">
    <div class="max-w-3xl mx-auto px-6 lg:px-8">
        <div class="text-center mb-16">
            <span class="inline-block text-sm font-semibold text-gray-500 tracking-[0.3em] uppercase mb-4">Support</span>
            <h2 class="text-4xl lg:text-5xl font-light text-gray-900 mb-4 tracking-tight">
                Frequently <span class="font-semibold">Asked</span>
            </h2>
            <p class="text-gray-600">Everything you need to know about our products and services.</p>
        </div>
        
        <div class="space-y-4">
            <div class="bg-white rounded-2xl overflow-hidden border border-gray-100">
                <button class="faq-btn w-full px-6 py-5 text-left flex justify-between items-center group">
                    <span class="font-semibold text-gray-900">How do I book an appointment?</span>
                    <span class="faq-icon transform transition-transform duration-300">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </span>
                </button>
                <div class="faq-content hidden px-6 pb-5 text-gray-600 text-sm leading-relaxed">
                    You can book an appointment through our website, by calling us, or by visiting our salon. We recommend booking in advance to secure your preferred time slot.
                </div>
            </div>

            <div class="bg-white rounded-2xl overflow-hidden border border-gray-100">
                <button class="faq-btn w-full px-6 py-5 text-left flex justify-between items-center group">
                    <span class="font-semibold text-gray-900">What is your cancellation policy?</span>
                    <span class="faq-icon transform transition-transform duration-300">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </span>
                </button>
                <div class="faq-content hidden px-6 pb-5 text-gray-600 text-sm leading-relaxed">
                    We offer free cancellation up to 24 hours before your appointment. Late cancellations may incur a fee.
                </div>
            </div>

            <div class="bg-white rounded-2xl overflow-hidden border border-gray-100">
                <button class="faq-btn w-full px-6 py-5 text-left flex justify-between items-center group">
                    <span class="font-semibold text-gray-900">Do you sell beauty products?</span>
                    <span class="faq-icon transform transition-transform duration-300">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </span>
                </button>
                <div class="faq-content hidden px-6 pb-5 text-gray-600 text-sm leading-relaxed">
                    Yes! We offer a curated selection of premium beauty products that you can purchase in-salon or through our online store.
                </div>
            </div>

            <div class="bg-white rounded-2xl overflow-hidden border border-gray-100">
                <button class="faq-btn w-full px-6 py-5 text-left flex justify-between items-center group">
                    <span class="font-semibold text-gray-900">Is parking available?</span>
                    <span class="faq-icon transform transition-transform duration-300">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </span>
                </button>
                <div class="faq-content hidden px-6 pb-5 text-gray-600 text-sm leading-relaxed">
                    Yes, we have parking available for our clients. There is also street parking nearby.
                </div>
            </div>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.faq-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const content = btn.nextElementSibling;
            const icon = btn.querySelector('.faq-icon');
            const isHidden = content.classList.contains('hidden');
            
            document.querySelectorAll('.faq-content').forEach(c => c.classList.add('hidden'));
            document.querySelectorAll('.faq-icon').forEach(i => i.style.transform = 'rotate(0deg)');
            
            if (isHidden) {
                content.classList.remove('hidden');
                icon.style.transform = 'rotate(180deg)';
            }
        });
    });
});
</script>

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
                    ['name' => 'Sarah Johnson', 'role' => 'Fashion Stylist', 'text' => 'The hair styling quality is exceptional. It has become a staple in my beauty routine.'],
                    ['name' => 'Michael Chen', 'role' => 'Creative Director', 'text' => 'Modern techniques with a timeless feel. Parlour truly understands sophisticated beauty.'],
                    ['name' => 'Emma Williams', 'role' => 'Store Manager', 'text' => 'Customer service was incredibly helpful with choosing the right treatment. The facial fits like a dream!'],
                    ['name' => 'James Wilson', 'role' => 'Business Analyst', 'text' => 'Premium products and perfect technique. Worth every penny for the professional look.'],
                    ['name' => 'Olivia Brown', 'role' => 'Lifestyle Blogger', 'text' => 'I love how versatile these services are. They easily transition from day to night.'],
                    ['name' => 'David Miller', 'role' => 'Architect', 'text' => 'Minimalist aesthetic with high-end execution. The nail art is both stylish and comfortable.'],
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

<?php include __DIR__ . '/includes/footer.php'; ?>
