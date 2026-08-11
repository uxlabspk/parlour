<?php
// php_web/contact.php
include __DIR__ . '/includes/header.php';
?>

<!-- Hero Section -->
 <div class="relative h-[60vh] min-h-[500px] flex items-center justify-center overflow-hidden">
    <div class="absolute inset-0 -z-10">
        <img 
            src="https://images.unsplash.com/photo-1441984904996-e0b6ba687e04?w=1920&q=85" 
            alt="Shop Collection" 
            class="w-full h-full object-cover"
        />
        <div class="absolute inset-0 bg-gradient-to-r from-black/70 via-black/50 to-transparent"></div>
    </div>
    
    <div class="relative z-10 text-center px-6">
        <span class="inline-block text-sm font-semibold text-white/70 tracking-[0.3em] uppercase mb-4">Get In Touch</span>
        <h1 class="text-5xl lg:text-6xl font-light text-white mb-4 tracking-tight">
            Contact <span class="font-semibold">Us</span>
        </h1>
        <p class="text-lg text-white/70 max-w-xl mx-auto">
            Have a question or need assistance? We're here to help you with anything you need.
        </p>
    </div>
</div>

<!-- Contact Content -->
<section class="py-12">
    <div class="max-w-7xl mx-auto px-6 lg:px-8">
        <div class="grid lg:grid-cols-5 gap-12 lg:gap-16">
            <!-- Contact Info -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Showroom Card -->
                <div class="bg-gray-50 rounded-3xl p-8 border border-gray-100">
                    <div class="w-12 h-12 bg-white rounded-2xl flex items-center justify-center mb-6 shadow-sm">
                        <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-3">LOCATION</h3>
                    <p class="text-gray-600 text-sm leading-relaxed">
                        Dhoke Ellahi Buksh<br>
                        Commeti Chowk Rawalpindi<br>
                        Pakistan
                    </p>
                </div>

                <!-- Support Card -->
                <div class="bg-gray-50 rounded-3xl p-8 border border-gray-100">
                    <div class="w-12 h-12 bg-white rounded-2xl flex items-center justify-center mb-6 shadow-sm">
                        <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-3">Customer Support</h3>
                    <div class="space-y-2 text-sm text-gray-600">
                        <p class="flex items-center gap-2">
                            <span class="text-gray-400">Email:</span>
                            <a href="mailto:contact@parlour.com" class="hover:text-gray-900 transition-colors">Contact@parlour.com</a>
                        </p>
                        <p class="flex items-center gap-2">
                            <span class="text-gray-400">Phone:</span>
                            <a href="tel:+15551234567" class="hover:text-gray-900 transition-colors">+92 314 5394040</a>
                        </p>
                        <p class="flex items-center gap-2">
                            <span class="text-gray-400">Hours:</span>
                            Mon-sundy, 9am - 11pm 
                        </p>
                    </div>
                </div>

                <!-- Social Links -->
                <div class="pt-4">
                    <h3 class="text-sm font-semibold text-gray-500 tracking-[0.2em] uppercase mb-5">Follow Us</h3>
                    <div class="flex gap-3">
                        <a href="#" class="w-11 h-11 bg-gray-900 text-white rounded-full flex items-center justify-center hover:bg-gray-700 transition-colors duration-300">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                        </a>
                        <a href="#" class="w-11 h-11 bg-gray-900 text-white rounded-full flex items-center justify-center hover:bg-gray-700 transition-colors duration-300">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                        </a>
                        <a href="#" class="w-11 h-11 bg-gray-900 text-white rounded-full flex items-center justify-center hover:bg-gray-700 transition-colors duration-300">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                        </a>
                        <a href="#" class="w-11 h-11 bg-gray-900 text-white rounded-full flex items-center justify-center hover:bg-gray-700 transition-colors duration-300">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/></svg>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Contact Form -->
            <div class="lg:col-span-3">
                <div class="bg-white rounded-3xl p-8 lg:p-10 border border-gray-100 shadow-sm">
                    <h2 class="text-2xl font-light text-gray-900 mb-2">
                        Send Us a <span class="font-semibold">Message</span>
                    </h2>
                    <p class="text-gray-500 text-sm mb-8">We'll get back to you within 24 hours.</p>
                    
                    <!-- Success Message -->
                    <div id="successMessage" class="hidden mb-6 bg-emerald-50 text-emerald-700 p-4 rounded-2xl text-sm border border-emerald-100 flex items-center gap-3">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span><strong>Thank you!</strong> Your message has been sent successfully.</span>
                    </div>
                    
                    <!-- Error Message -->
                    <div id="errorMessage" class="hidden mb-6 bg-red-50 text-red-600 p-4 rounded-2xl text-sm border border-red-100 flex items-center gap-3">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span id="errorText"></span>
                    </div>
                    
                    <form id="contactForm" action="api/contact.php" method="POST" class="space-y-6">
                        <div class="grid md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Name</label>
                                <input type="text" name="name" required 
                                    class="w-full px-5 py-4 rounded-xl border border-gray-200 outline-none focus:border-gray-400 focus:ring-2 focus:ring-gray-100 transition-all duration-200 text-gray-900 placeholder-gray-400"
                                    placeholder="Your name">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                                <input type="email" name="email" required 
                                    class="w-full px-5 py-4 rounded-xl border border-gray-200 outline-none focus:border-gray-400 focus:ring-2 focus:ring-gray-100 transition-all duration-200 text-gray-900 placeholder-gray-400"
                                    placeholder="your@email.com">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Subject</label>
                            <select name="subject" class="w-full px-5 py-4 rounded-xl border border-gray-200 outline-none focus:border-gray-400 focus:ring-2 focus:ring-gray-100 transition-all duration-200 text-gray-900 bg-white">
                                <option value="general">General Inquiry</option>
                                <option value="order">Order Support</option>
                                <option value="returns">Returns & Exchanges</option>
                                <option value="feedback">Feedback</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Message</label>
                            <textarea name="message" required rows="5"
                                class="w-full px-5 py-4 rounded-xl border border-gray-200 outline-none focus:border-gray-400 focus:ring-2 focus:ring-gray-100 transition-all duration-200 text-gray-900 placeholder-gray-400 resize-none"
                                placeholder="How can we help you?"></textarea>
                        </div>
                        <button type="submit" id="submitBtn" 
                            class="w-full bg-gray-900 text-white py-4 rounded-full font-semibold hover:bg-gray-800 transition-all duration-300 flex items-center justify-center gap-2 group">
                            <span id="btnText">Send Message</span>
                            <span id="btnLoading" class="hidden">
                                <svg class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </span>
                            <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" id="btnArrow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                            </svg>
                        </button>
                    </form>
                    
                    <script>
                    document.getElementById('contactForm').addEventListener('submit', async function(e) {
                        e.preventDefault();
                        
                        const form = this;
                        const submitBtn = document.getElementById('submitBtn');
                        const btnText = document.getElementById('btnText');
                        const btnLoading = document.getElementById('btnLoading');
                        const btnArrow = document.getElementById('btnArrow');
                        const successMsg = document.getElementById('successMessage');
                        const errorMsg = document.getElementById('errorMessage');
                        const errorText = document.getElementById('errorText');
                        
                        successMsg.classList.add('hidden');
                        errorMsg.classList.add('hidden');
                        
                        submitBtn.disabled = true;
                        btnText.classList.add('hidden');
                        btnArrow.classList.add('hidden');
                        btnLoading.classList.remove('hidden');
                        
                        try {
                            const formData = new FormData(form);
                            const response = await fetch('api/contact.php', {
                                method: 'POST',
                                body: formData
                            });
                            
                            const data = await response.json();
                            
                            if (data.success) {
                                successMsg.classList.remove('hidden');
                                form.reset();
                                successMsg.scrollIntoView({ behavior: 'smooth', block: 'center' });
                            } else {
                                errorText.textContent = data.message || 'An error occurred. Please try again.';
                                errorMsg.classList.remove('hidden');
                                errorMsg.scrollIntoView({ behavior: 'smooth', block: 'center' });
                            }
                        } catch (error) {
                            errorText.textContent = 'An error occurred. Please try again later.';
                            errorMsg.classList.remove('hidden');
                            console.error('Contact form error:', error);
                        } finally {
                            submitBtn.disabled = false;
                            btnText.classList.remove('hidden');
                            btnArrow.classList.remove('hidden');
                            btnLoading.classList.add('hidden');
                        }
                    });
                    </script>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Map Section -->
<section class="">
    <div class="">
        <div class="overflow-hidden h-[500px] bg-gray-100 border border-gray-200">
            <iframe 
                src="https://maps.google.com/maps?width=600&height=400&hl=en&q=Dhoke%20Ellahi%20Buksh%20Commeti%20Chowk%20Rawalpindi%20Pakistan&t=&z=14&ie=UTF8&iwloc=B&output=embed" 
                width="100%" 
                height="100%" 
                style="border:0;" 
                allowfullscreen="" 
                loading="lazy" 
                referrerpolicy="no-referrer-when-downgrade"
                class=""
            ></iframe>
        </div>
    </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
