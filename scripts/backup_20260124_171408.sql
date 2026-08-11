-- Database Backup
-- Generated: 2026-01-24 17:14:09

SET FOREIGN_KEY_CHECKS=0;

-- Table: addresses
DROP TABLE IF EXISTS `addresses`;
CREATE TABLE `addresses` (
  `id` varchar(36) NOT NULL,
  `userId` varchar(36) NOT NULL,
  `type` enum('SHIPPING','BILLING','BOTH') DEFAULT 'SHIPPING',
  `fullName` varchar(255) NOT NULL,
  `phone` varchar(50) NOT NULL,
  `addressLine1` varchar(255) NOT NULL,
  `addressLine2` varchar(255) DEFAULT NULL,
  `city` varchar(100) NOT NULL,
  `state` varchar(100) NOT NULL,
  `postalCode` varchar(20) NOT NULL,
  `country` varchar(100) NOT NULL,
  `isDefault` tinyint(1) DEFAULT 0,
  `createdAt` datetime DEFAULT current_timestamp(),
  `updatedAt` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `userId` (`userId`),
  KEY `isDefault` (`isDefault`),
  CONSTRAINT `addresses_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data for addresses
INSERT INTO `addresses` VALUES ('23bc31d0949ba51fd894dadecce9e835c95c', 'b8e60d11-9742-4f03-a930-572efec5e9e2', 'SHIPPING', 'Muhammad Yousaf', '07438283520', '57 MONTAGU AVE', '', 'Leeds', 'United Kingdom', 'LS8 3ET', 'United Kingdom', '0', '2026-01-23 13:00:39', '2026-01-23 13:00:39');
INSERT INTO `addresses` VALUES ('66cc5058-29a9-441a-9f8e-56b2ff8f0195', 'a7a77994-d73f-415c-bd51-3daacdfee6d6', 'SHIPPING', 'john', '99999999999', 'io', 'iou', 'ljlj', 'ljlk', '8888', 'DE', '1', '2026-01-23 15:08:53', '2026-01-23 15:08:53');
INSERT INTO `addresses` VALUES ('af397f6e-950f-45ac-afef-7a612e6c01c9', '726b40cf-d8e3-4688-b5cd-d5a1fdb9b082', 'SHIPPING', 'Muhammad Yousaf', '07438283520', '57 MONTAGU AVE', '', 'Leeds', 'United Kingdom', 'LS8 3ET', 'UK', '0', '2026-01-23 13:19:09', '2026-01-23 13:20:00');
INSERT INTO `addresses` VALUES ('db1608ca-d433-4f8c-b23c-07a86499b0fe', 'b5c22532-710f-4d72-bfb7-1fdb5e1582bf', 'SHIPPING', 'Muhammad Shazil', '07438283520', '57 MONTAGU AVE', '', 'Leeds', 'United Kingdom', 'LS8 3ET', 'UK', '1', '2026-01-23 15:47:37', '2026-01-23 15:47:37');

-- Table: contacts
DROP TABLE IF EXISTS `contacts`;
CREATE TABLE `contacts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `status` enum('NEW','READ','REPLIED') DEFAULT 'NEW',
  `createdAt` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `email` (`email`),
  KEY `status` (`status`),
  KEY `createdAt` (`createdAt`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: orderItems
DROP TABLE IF EXISTS `orderItems`;
CREATE TABLE `orderItems` (
  `id` varchar(36) NOT NULL,
  `orderId` varchar(36) NOT NULL,
  `productId` varchar(36) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `size` varchar(50) DEFAULT NULL,
  `color` varchar(50) DEFAULT NULL,
  `createdAt` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `orderId` (`orderId`),
  KEY `productId` (`productId`),
  CONSTRAINT `orderItems_ibfk_1` FOREIGN KEY (`orderId`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `orderItems_ibfk_2` FOREIGN KEY (`productId`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: orders
DROP TABLE IF EXISTS `orders`;
CREATE TABLE `orders` (
  `id` varchar(36) NOT NULL,
  `userId` varchar(36) NOT NULL,
  `status` enum('PENDING','PROCESSING','SHIPPED','DELIVERED','CANCELLED') DEFAULT 'PENDING',
  `subtotal` decimal(10,2) NOT NULL,
  `shipping` decimal(10,2) NOT NULL,
  `tax` decimal(10,2) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `shippingAddress` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`shippingAddress`)),
  `billingAddress` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`billingAddress`)),
  `paymentMethod` varchar(255) NOT NULL,
  `createdAt` datetime DEFAULT current_timestamp(),
  `updatedAt` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `userId` (`userId`),
  KEY `status` (`status`),
  CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: products
DROP TABLE IF EXISTS `products`;
CREATE TABLE `products` (
  `id` varchar(36) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `discountedPrice` decimal(10,2) DEFAULT NULL,
  `originalPrice` decimal(10,2) DEFAULT NULL,
  `category` varchar(255) NOT NULL,
  `subcategory` varchar(255) DEFAULT NULL,
  `image` varchar(255) NOT NULL,
  `images` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`images`)),
  `inStock` tinyint(1) DEFAULT 1,
  `stockQuantity` int(11) DEFAULT 0,
  `rating` decimal(3,2) DEFAULT 0.00,
  `reviewCount` int(11) DEFAULT 0,
  `sale` tinyint(1) DEFAULT 0,
  `featured` tinyint(1) DEFAULT 0,
  `size` varchar(255) DEFAULT NULL,
  `sizes` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`sizes`)),
  `color` varchar(255) DEFAULT NULL,
  `colors` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`colors`)),
  `shippingPricing` decimal(10,2) DEFAULT 0.00,
  `material` varchar(255) DEFAULT NULL,
  `tags` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`tags`)),
  `createdAt` datetime DEFAULT current_timestamp(),
  `updatedAt` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `category` (`category`),
  KEY `featured` (`featured`),
  KEY `sale` (`sale`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data for products
INSERT INTO `products` VALUES ('0a9799b4-f36e-4d6f-8a63-2839a446c158', 'Rose Gold Dress Watch', 'Elegant rose gold watch perfect for formal occasions.', '425.00', NULL, NULL, 'Luxury Watches', NULL, 'https://images.unsplash.com/photo-1533139502658-0198f920d8e8?w=600&q=80', NULL, '1', '0', '0.00', '0', '0', '1', NULL, '[\"S\",\"M\",\"L\",\"XL\"]', NULL, '[\"Black\",\"Navy\",\"Grey\"]', '0.00', NULL, NULL, '2026-01-24 15:00:24', '2026-01-24 15:00:24');
INSERT INTO `products` VALUES ('2b9ff5df-ed95-4737-b633-76c4136a7ab4', 'Tactical Military Watch', 'Rugged tactical watch with compass and multiple time zones.', '159.00', NULL, NULL, 'Sport Watches', NULL, 'https://images.unsplash.com/photo-1614164185128-e4ec99c436d7?w=600&q=80', NULL, '1', '0', '0.00', '0', '0', '0', NULL, '[\"S\",\"M\",\"L\",\"XL\"]', NULL, '[\"Black\",\"Navy\",\"Grey\"]', '0.00', NULL, NULL, '2026-01-24 15:00:25', '2026-01-24 15:00:25');
INSERT INTO `products` VALUES ('319d5f59-bcd9-40d0-9b25-5d048a7ae854', 'Minimalist Quartz Watch', 'Sleek minimalist design with mesh band and ultra-thin case.', '129.00', NULL, '179.00', 'Casual Watches', NULL, 'https://images.unsplash.com/photo-1508685096489-7aacd43bd3b1?w=600&q=80', NULL, '1', '0', '0.00', '0', '1', '1', NULL, '[\"S\",\"M\",\"L\",\"XL\"]', NULL, '[\"Black\",\"Navy\",\"Grey\"]', '0.00', NULL, NULL, '2026-01-24 15:00:26', '2026-01-24 15:00:26');
INSERT INTO `products` VALUES ('4b3b50b4-35a5-47bd-a428-a3ab1d106451', 'Luxury Watch Box', 'Premium wooden watch storage box with velvet interior for 6 watches.', '129.00', NULL, NULL, 'Watch Accessories', NULL, 'https://images.unsplash.com/photo-1611930022073-b7a4ba5fcccd?w=600&q=80', NULL, '1', '0', '0.00', '0', '0', '1', NULL, '[\"S\",\"M\",\"L\",\"XL\"]', NULL, '[\"Black\",\"Navy\",\"Grey\"]', '0.00', NULL, NULL, '2026-01-24 15:00:27', '2026-01-24 15:00:27');
INSERT INTO `products` VALUES ('4d7cc6ab-47d2-4e02-b831-93fe2ddfc71b', 'Casual Canvas Watch', 'Comfortable canvas strap watch with easy-read dial.', '89.00', NULL, NULL, 'Casual Watches', NULL, 'https://images.unsplash.com/photo-1524805444758-089113d48a6d?w=600&q=80', NULL, '1', '0', '0.00', '0', '0', '0', NULL, '[\"S\",\"M\",\"L\",\"XL\"]', NULL, '[\"Black\",\"Navy\",\"Grey\"]', '0.00', NULL, NULL, '2026-01-24 15:00:26', '2026-01-24 15:00:26');
INSERT INTO `products` VALUES ('5026b9cc-46db-4a87-8dd8-aaf85e2abbf0', 'Professional Pilot Watch', 'Aviation-inspired pilot watch with large dial and leather strap.', '549.00', NULL, '649.00', 'Sport Watches', NULL, 'https://images.unsplash.com/photo-1600721391776-b5cd0e0048a9?w=600&q=80', NULL, '1', '0', '0.00', '0', '1', '1', NULL, '[\"S\",\"M\",\"L\",\"XL\"]', NULL, '[\"Black\",\"Navy\",\"Grey\"]', '0.00', NULL, NULL, '2026-01-24 15:00:25', '2026-01-24 15:00:25');
INSERT INTO `products` VALUES ('56e4442a-f6e2-48ca-bd27-224b147c87b1', 'Casual Canvas Watch', 'Comfortable canvas strap watch with easy-read dial.', '89.00', NULL, NULL, 'Casual Watches', NULL, 'https://images.unsplash.com/photo-1524805444758-089113d48a6d?w=600&q=80', NULL, '1', '0', '0.00', '0', '0', '0', NULL, '[\"S\",\"M\",\"L\",\"XL\"]', NULL, '[\"Black\",\"Navy\",\"Grey\"]', '0.00', NULL, NULL, '2026-01-24 15:00:26', '2026-01-24 15:00:26');
INSERT INTO `products` VALUES ('5c9d8632-db58-43b7-be95-b02297d1699a', 'Vintage Leather Watch', 'Vintage-inspired timepiece with genuine leather strap.', '189.00', NULL, '229.00', 'Casual Watches', NULL, 'https://images.unsplash.com/photo-1522312346375-d1a52e2b99b3?w=600&q=80', NULL, '1', '0', '0.00', '0', '1', '0', NULL, '[\"S\",\"M\",\"L\",\"XL\"]', NULL, '[\"Black\",\"Navy\",\"Grey\"]', '0.00', NULL, NULL, '2026-01-24 15:00:26', '2026-01-24 15:00:26');
INSERT INTO `products` VALUES ('5da973eb-5d65-466c-8fde-66945a697ac8', 'Watch Winder', 'Automatic watch winder for 2 watches with quiet motor.', '199.00', NULL, '249.00', 'Watch Accessories', NULL, 'https://images.unsplash.com/photo-1509941943102-10c232535736?w=600&q=80', NULL, '1', '0', '0.00', '0', '1', '1', NULL, '[\"S\",\"M\",\"L\",\"XL\"]', NULL, '[\"Black\",\"Navy\",\"Grey\"]', '0.00', NULL, NULL, '2026-01-24 15:00:27', '2026-01-24 15:00:27');
INSERT INTO `products` VALUES ('5ea54a6e-6ed9-4f23-9597-83dbd484c97b', 'Classic Chronograph Watch', 'Elegant chronograph watch with stainless steel band and sapphire crystal.', '349.00', NULL, '449.00', 'Luxury Watches', NULL, 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=600&q=80', NULL, '1', '0', '0.00', '0', '1', '1', NULL, '[\"S\",\"M\",\"L\",\"XL\"]', NULL, '[\"Black\",\"Navy\",\"Grey\"]', '0.00', NULL, NULL, '2026-01-24 15:00:24', '2026-01-24 15:00:24');
INSERT INTO `products` VALUES ('89cc1d95-e772-4847-8131-40f21a8bbe24', 'Hybrid Smartwatch', 'Elegant hybrid watch combining classic design with smart features.', '189.00', NULL, NULL, 'Smart Watches', NULL, 'https://images.unsplash.com/photo-1557438159-51eec7a6c9e8?w=600&q=80', NULL, '1', '0', '0.00', '0', '0', '0', NULL, '[\"S\",\"M\",\"L\",\"XL\"]', NULL, '[\"Black\",\"Navy\",\"Grey\"]', '0.00', NULL, NULL, '2026-01-24 15:00:26', '2026-01-24 15:00:26');
INSERT INTO `products` VALUES ('8a090364-2234-4a44-8b6b-d88ce9394652', 'Watch Display Stand', 'Elegant acrylic display stand for showcasing your watch collection.', '39.00', NULL, NULL, 'Watch Accessories', NULL, 'https://images.unsplash.com/photo-1563291074-2bf8677ac0e5?w=600&q=80', NULL, '1', '0', '0.00', '0', '0', '0', NULL, '[\"S\",\"M\",\"L\",\"XL\"]', NULL, '[\"Black\",\"Navy\",\"Grey\"]', '0.00', NULL, NULL, '2026-01-24 15:00:28', '2026-01-24 15:00:28');
INSERT INTO `products` VALUES ('999071a4-d056-4729-ac36-ab6a96f2d7e1', 'Digital Sports Watch', 'Durable digital watch with stopwatch and backlight features.', '79.00', NULL, '99.00', 'Sport Watches', NULL, 'https://images.unsplash.com/photo-1542496658-e33a6d0d50f6?w=600&q=80', NULL, '1', '0', '0.00', '0', '1', '0', NULL, '[\"S\",\"M\",\"L\",\"XL\"]', NULL, '[\"Black\",\"Navy\",\"Grey\"]', '0.00', NULL, NULL, '2026-01-24 15:00:25', '2026-01-24 15:00:25');
INSERT INTO `products` VALUES ('a23b0f5d-8a06-43de-bcc3-d03caebc1516', 'Smart Fitness Watch', 'Advanced fitness tracker with heart rate monitor and GPS.', '249.00', NULL, NULL, 'Smart Watches', NULL, 'https://images.unsplash.com/photo-1579586337278-3befd40fd17a?w=600&q=80', NULL, '1', '0', '0.00', '0', '0', '1', NULL, '[\"S\",\"M\",\"L\",\"XL\"]', NULL, '[\"Black\",\"Navy\",\"Grey\"]', '0.00', NULL, NULL, '2026-01-24 15:00:25', '2026-01-24 15:00:25');
INSERT INTO `products` VALUES ('c548e6e9-246a-48be-8397-fb8175c90eb9', 'Leather Watch Band', 'Premium genuine leather replacement watch band in various sizes.', '45.00', NULL, '59.00', 'Watch Accessories', NULL, 'https://images.unsplash.com/photo-1617625802912-cde586faf331?w=600&q=80', NULL, '1', '0', '0.00', '0', '1', '0', NULL, '[\"S\",\"M\",\"L\",\"XL\"]', NULL, '[\"Black\",\"Navy\",\"Grey\"]', '0.00', NULL, NULL, '2026-01-24 15:00:26', '2026-01-24 15:00:26');
INSERT INTO `products` VALUES ('c5e83fca-3c19-49ad-800f-4fb8c1449687', 'Stainless Steel Watch Band', 'Elegant stainless steel mesh band with quick-release mechanism.', '69.00', NULL, NULL, 'Watch Accessories', NULL, 'https://images.unsplash.com/photo-1614164185128-e4ec99c436d7?w=600&q=80', NULL, '1', '0', '0.00', '0', '0', '0', NULL, '[\"S\",\"M\",\"L\",\"XL\"]', NULL, '[\"Black\",\"Navy\",\"Grey\"]', '0.00', NULL, NULL, '2026-01-24 15:00:27', '2026-01-24 15:00:27');
INSERT INTO `products` VALUES ('cdd388e5-cbca-40e7-88b0-0096351d5fa5', 'Sport Dive Watch', 'Water-resistant dive watch with rotating bezel and luminous hands.', '279.00', NULL, NULL, 'Sport Watches', NULL, 'https://images.unsplash.com/photo-1587836374828-4dbafa94cf0e?w=600&q=80', NULL, '1', '0', '0.00', '0', '0', '1', NULL, '[\"S\",\"M\",\"L\",\"XL\"]', NULL, '[\"Black\",\"Navy\",\"Grey\"]', '0.00', NULL, NULL, '2026-01-24 15:00:25', '2026-01-24 15:00:25');
INSERT INTO `products` VALUES ('d44fa9ac-80db-4005-8850-c373f9d8be47', 'Platinum Automatic Watch', 'Exquisite platinum automatic watch with exhibition caseback.', '1299.00', NULL, '1499.00', 'Luxury Watches', NULL, 'https://images.unsplash.com/photo-1547996160-81dfa63595aa?w=600&q=80', NULL, '1', '0', '0.00', '0', '1', '1', NULL, '[\"S\",\"M\",\"L\",\"XL\"]', NULL, '[\"Black\",\"Navy\",\"Grey\"]', '0.00', NULL, NULL, '2026-01-24 15:00:24', '2026-01-24 15:00:24');
INSERT INTO `products` VALUES ('d86f5530-0617-4aeb-9f0d-240c1a50cf3c', 'NATO Watch Strap', 'Durable nylon NATO strap in multiple colors.', '25.00', NULL, '35.00', 'Watch Accessories', NULL, 'https://images.unsplash.com/photo-1622434641406-a158123450f9?w=600&q=80', NULL, '1', '0', '0.00', '0', '1', '0', NULL, '[\"S\",\"M\",\"L\",\"XL\"]', NULL, '[\"Black\",\"Navy\",\"Grey\"]', '0.00', NULL, NULL, '2026-01-24 15:00:27', '2026-01-24 15:00:27');
INSERT INTO `products` VALUES ('d8a030dd-a625-47e5-ae1f-9f32d70915da', 'Watch Travel Case', 'Compact leather travel case for 4 watches with zipper closure.', '49.00', NULL, NULL, 'Watch Accessories', NULL, 'https://images.unsplash.com/photo-1553062407-98eeb64c6a62?w=600&q=80', NULL, '1', '0', '0.00', '0', '0', '0', NULL, '[\"S\",\"M\",\"L\",\"XL\"]', NULL, '[\"Black\",\"Navy\",\"Grey\"]', '0.00', NULL, NULL, '2026-01-24 15:00:28', '2026-01-24 15:00:28');
INSERT INTO `products` VALUES ('dc90e017-1854-44e0-951c-39d566d955ee', 'Premium Smartwatch', 'Premium smartwatch with AMOLED display and health tracking.', '399.00', NULL, '499.00', 'Smart Watches', NULL, 'https://images.unsplash.com/photo-1546868871-7041f2a55e12?w=600&q=80', NULL, '1', '0', '0.00', '0', '1', '1', NULL, '[\"S\",\"M\",\"L\",\"XL\"]', NULL, '[\"Black\",\"Navy\",\"Grey\"]', '0.00', NULL, NULL, '2026-01-24 15:00:25', '2026-01-24 15:00:25');
INSERT INTO `products` VALUES ('f7a6b031-26c6-4f2e-b2c5-d0cf093152d0', 'Luxury Gold Watch', 'Premium gold-plated watch with leather strap and Swiss movement.', '899.00', NULL, NULL, 'Luxury Watches', NULL, 'https://images.unsplash.com/photo-1524592094714-0f0654e20314?w=600&q=80', NULL, '1', '0', '0.00', '0', '0', '1', NULL, '[\"S\",\"M\",\"L\",\"XL\"]', NULL, '[\"Black\",\"Navy\",\"Grey\"]', '0.00', NULL, NULL, '2026-01-24 15:00:24', '2026-01-24 15:00:24');
INSERT INTO `products` VALUES ('ffd3b1c4-10e4-47af-9839-3ea89bc1670e', 'Watch Cleaning Kit', 'Complete watch cleaning and maintenance kit with tools.', '29.00', NULL, NULL, 'Watch Accessories', NULL, 'https://images.unsplash.com/photo-1586170848607-a3c4d66fe7d4?w=600&q=80', NULL, '1', '0', '0.00', '0', '0', '0', NULL, '[\"S\",\"M\",\"L\",\"XL\"]', NULL, '[\"Black\",\"Navy\",\"Grey\"]', '0.00', NULL, NULL, '2026-01-24 15:00:27', '2026-01-24 15:00:27');

-- Table: reviews
DROP TABLE IF EXISTS `reviews`;
CREATE TABLE `reviews` (
  `id` varchar(36) NOT NULL,
  `productId` varchar(36) NOT NULL,
  `userId` varchar(36) NOT NULL,
  `rating` int(11) NOT NULL,
  `comment` text DEFAULT NULL,
  `approved` tinyint(1) DEFAULT 0,
  `approvedAt` datetime DEFAULT NULL,
  `createdAt` datetime DEFAULT current_timestamp(),
  `updatedAt` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `productId` (`productId`),
  KEY `userId` (`userId`),
  KEY `approved` (`approved`),
  CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`productId`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`userId`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data for reviews
INSERT INTO `reviews` VALUES ('0e992f9b-e14c-42b1-9625-77b42bd13903', '690f7bf1-dd04-4d09-a1b6-07f0f2cbfc18', '726b40cf-d8e3-4688-b5cd-d5a1fdb9b082', '5', 'An effective e-commerce admin profile/panel is the central hub for managing online store operations, requiring a combination of robust functional modules, security, and analytical tools. The core requirements include user management, content control, product and order management, and secure authentication. ', '1', '2026-01-23 14:53:38', '2026-01-23 14:53:24', '2026-01-23 14:53:38');
INSERT INTO `reviews` VALUES ('9e3068d9-63a1-478d-b65e-fbadeded8bfa', 'fa8c663b-1c11-41b8-ac92-671bf649e274', 'b8e60d11-9742-4f03-a930-572efec5e9e2', '5', 'A good product', '1', '2026-01-24 05:52:50', '2026-01-24 05:52:32', '2026-01-24 05:52:50');
INSERT INTO `reviews` VALUES ('c20fee6c-68d9-466f-8b34-7a3301905165', 'ae262f50-2457-450e-a0b1-d10f0b8db793', '587953f5-83ec-47e9-92c3-69815428d6b9', '4', 'very good product', '1', '2026-01-23 14:39:11', '2026-01-23 14:38:49', '2026-01-23 14:39:11');

-- Table: users
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` varchar(36) NOT NULL,
  `email` varchar(255) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `profileImage` varchar(255) DEFAULT NULL,
  `role` enum('USER','MANAGER','ADMIN') DEFAULT 'USER',
  `emailVerified` tinyint(1) DEFAULT 0,
  `verificationToken` varchar(255) DEFAULT NULL,
  `resetToken` varchar(255) DEFAULT NULL,
  `resetTokenExpiry` datetime DEFAULT NULL,
  `createdAt` datetime DEFAULT current_timestamp(),
  `updatedAt` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  KEY `email_2` (`email`),
  KEY `role` (`role`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data for users
INSERT INTO `users` VALUES ('773f06bd-e46a-4d2a-94bc-a4bbdaa4822c', 'codehuntspk@gmail.com', 'Shazil', '$2y$10$4NKu/YgGsyU/scNNgEtNOeQhjEPbLjlK68U7uMjdZMUiP7Ee1pC6e', NULL, 'ADMIN', '1', NULL, NULL, NULL, '2026-01-24 17:04:39', '2026-01-24 17:06:30');
INSERT INTO `users` VALUES ('7d624250-16ca-47c4-bf87-d402ca5659c0', 'naveed@codehuntspk.com', 'Muhammad Naveed', '$2y$12$gnLb4oNz0bqlg8AZwjEifu5NfI9eGAJLofYxDsTsfrZbWVNKC8OJq', NULL, 'USER', '0', '37197b', NULL, NULL, '2026-01-24 17:06:03', '2026-01-24 17:06:03');
INSERT INTO `users` VALUES ('8320f7ee-c8ba-4a85-a787-56b5530c66cf', 'admin@maessentials.com', 'Admin User', '$2y$12$ADc4vKAmSldIenOvnYEnLuNC3YZ4wEOVefRXVceLq70YITYIepkfK', NULL, 'ADMIN', '1', NULL, NULL, NULL, '2026-01-24 15:00:24', '2026-01-24 15:00:24');
INSERT INTO `users` VALUES ('9e201327-4f40-4f8e-938c-188b9509348f', 'sheikhbakar180@gmail.com', 'Abu Bakar Sheikh', '$2y$10$PhG26RGG7bEM0QT9qTGCB.Ok3fs6BLITwiaKck0DU8gUCmGst.lHO', NULL, 'ADMIN', '1', NULL, NULL, NULL, '2026-01-24 17:01:01', '2026-01-24 17:03:30');

SET FOREIGN_KEY_CHECKS=1;
