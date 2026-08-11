<?php
// php_web/shipping.php
include __DIR__ . '/includes/header.php';
?>

<section class="py-24 lg:py-32">
    <div class="max-w-5xl mx-auto px-6 lg:px-8">
        <div class="text-center mb-12">
            <h1 class="text-4xl lg:text-5xl font-light text-gray-900 tracking-tight">Service <span class="font-semibold">Policy</span></h1>
            <p class="mt-4 text-gray-600">We provide premium beauty services. This page explains our booking, service, and product policies.</p>
        </div>

        <div class="prose prose-lg max-w-none text-gray-700">
            <h2>Appointment Booking</h2>
            <p>Appointments can be booked through our website, by calling us, or by visiting our salon. We recommend booking in advance to secure your preferred time slot.</p>

            <h2>Service Availability</h2>
            <ul>
                <li><strong>Hair Services:</strong> Available throughout the week.</li>
                <li><strong>Skin Treatments:</strong> Available by appointment.</li>
                <li><strong>Nail Art:</strong> Walk-ins and appointments welcome.</li>
                <li><strong>Bridal Packages:</strong> Advance booking required (2-4 weeks).</li>
            </ul>

            <h2>Product Orders</h2>
            <p>Beauty products can be purchased in-salon or through our online store. Orders are typically processed within 1-2 business days.</p>

            <h2>Delivery</h2>
            <p>Product deliveries are handled by our trusted delivery partners. Delivery times may vary based on your location and product availability.</p>

            <h2>Cancellation Policy</h2>
            <p>We offer free cancellation up to 24 hours before your appointment. Late cancellations may incur a fee.</p>

            <h2>Contact</h2>
            <p>For any service-related questions, please reach out via our <a href="http://localhost:8080/contact.php" class="text-gray-900 underline">Contact</a> page.</p>
        </div>
    </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
