<?php
// php_web/shipping.php
include __DIR__ . '/includes/header.php';
?>

<section class="py-24 lg:py-32">
    <div class="max-w-5xl mx-auto px-6 lg:px-8">
        <div class="text-center mb-12">
            <h1 class="text-4xl lg:text-5xl font-light text-gray-900 tracking-tight">Shipping <span class="font-semibold">Policy</span></h1>
            <p class="mt-4 text-gray-600">We ship worldwide. This page explains shipping methods, costs, and delivery times.</p>
        </div>

        <div class="prose prose-lg max-w-none text-gray-700">
            <h2>Processing Time</h2>
            <p>Orders are typically processed within 1–2 business days. Processing may be longer during sale periods or public holidays.</p>

            <h2>Shipping Methods & Delivery Times</h2>
            <ul>
                <li><strong>Standard Shipping:</strong> 3–7 business days (domestic).</li>
                <li><strong>Express Shipping:</strong> 1–3 business days (domestic).</li>
                <li><strong>International Shipping:</strong> 7–21 business days depending on destination and customs processing.</li>
            </ul>

            <h2>Shipping Costs</h2>
            <p>Shipping costs are calculated at checkout based on weight, destination, and chosen shipping method. Free shipping promotions may apply for qualifying orders.</p>

            <h2>Customs, Duties & Taxes</h2>
            <p>International orders may be subject to import duties and taxes. These are the responsibility of the recipient and are not included in the item price or shipping cost.</p>

            <h2>Tracking</h2>
            <p>Once your order ships, you will receive a tracking number via email. Use the provided tracking link to follow your shipment.</p>

            <h2>Lost or Delayed Shipments</h2>
            <p>If your order appears lost or delayed, contact us at <a href="https://parlour.com/contact" class="text-gray-900 underline">Contact</a> or support@parlour.com and include your order number and tracking details.</p>

            <h2>Contact</h2>
            <p>For shipping questions, please reach out via our <a href="https://parlour.com/contact" class="text-gray-900 underline">Contact</a> page.</p>
        </div>
    </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
