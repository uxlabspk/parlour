-- SQL Schema for PHP Version (MySQL compatible)

CREATE TABLE IF NOT EXISTS users (
    id VARCHAR(36) PRIMARY KEY,
    email VARCHAR(255) UNIQUE NOT NULL,
    name VARCHAR(255),
    password VARCHAR(255) NOT NULL,
    profileImage VARCHAR(255),
    role ENUM('USER', 'MANAGER', 'ADMIN') DEFAULT 'USER',
    emailVerified BOOLEAN DEFAULT FALSE,
    verificationToken VARCHAR(255),
    resetToken VARCHAR(255),
    resetTokenExpiry DATETIME,
    createdAt DATETIME DEFAULT CURRENT_TIMESTAMP,
    updatedAt DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX (email),
    INDEX (role)
);

CREATE TABLE IF NOT EXISTS products (
    id VARCHAR(36) PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    price DECIMAL(10, 2) NOT NULL,
    discountedPrice DECIMAL(10, 2),
    originalPrice DECIMAL(10, 2),
    category VARCHAR(255) NOT NULL,
    subcategory VARCHAR(255),
    image VARCHAR(255) NOT NULL,
    images JSON,
    inStock BOOLEAN DEFAULT TRUE,
    stockQuantity INT DEFAULT 0,
    rating DECIMAL(3, 2) DEFAULT 0,
    reviewCount INT DEFAULT 0,
    sale BOOLEAN DEFAULT FALSE,
    featured BOOLEAN DEFAULT FALSE,
    size VARCHAR(255),
    sizes JSON,
    color VARCHAR(255),
    colors JSON,
    shippingPricing DECIMAL(10, 2) DEFAULT 0,
    material VARCHAR(255),
    tags JSON,
    createdAt DATETIME DEFAULT CURRENT_TIMESTAMP,
    updatedAt DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX (category),
    INDEX (featured),
    INDEX (sale)
);

CREATE TABLE IF NOT EXISTS orders (
    id VARCHAR(36) PRIMARY KEY,
    userId VARCHAR(36) NOT NULL,
    status ENUM('PENDING', 'PROCESSING', 'SHIPPED', 'DELIVERED', 'CANCELLED') DEFAULT 'PENDING',
    subtotal DECIMAL(10, 2) NOT NULL,
    shipping DECIMAL(10, 2) NOT NULL,
    tax DECIMAL(10, 2) NOT NULL,
    total DECIMAL(10, 2) NOT NULL,
    shippingAddress JSON NOT NULL,
    billingAddress JSON NOT NULL,
    paymentMethod VARCHAR(255) NOT NULL,
    paymentStatus ENUM('PENDING', 'COMPLETED', 'FAILED', 'REFUNDED') DEFAULT 'PENDING',
    transactionId VARCHAR(255),
    transactionData JSON,
    receiptNumber VARCHAR(100),
    createdAt DATETIME DEFAULT CURRENT_TIMESTAMP,
    updatedAt DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (userId) REFERENCES users(id) ON DELETE CASCADE,
    INDEX (userId),
    INDEX (status),
    INDEX (paymentStatus),
    INDEX (transactionId)
);

CREATE TABLE IF NOT EXISTS payments (
    id VARCHAR(36) PRIMARY KEY,
    orderId VARCHAR(36) NOT NULL,
    userId VARCHAR(36) NOT NULL,
    paymentMethod VARCHAR(100) NOT NULL,
    amount DECIMAL(10, 2) NOT NULL,
    transactionId VARCHAR(255),
    raastId VARCHAR(100),
    accountNumber VARCHAR(50),
    status ENUM('PENDING', 'COMPLETED', 'FAILED', 'REFUNDED') DEFAULT 'PENDING',
    gatewayResponse JSON,
    receiptData JSON,
    createdAt DATETIME DEFAULT CURRENT_TIMESTAMP,
    updatedAt DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (orderId) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (userId) REFERENCES users(id) ON DELETE CASCADE,
    INDEX (orderId),
    INDEX (userId),
    INDEX (transactionId),
    INDEX (status)
);

CREATE TABLE IF NOT EXISTS orderItems (
    id VARCHAR(36) PRIMARY KEY,
    orderId VARCHAR(36) NOT NULL,
    productId VARCHAR(36) NOT NULL,
    quantity INT NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    size VARCHAR(50),
    color VARCHAR(50),
    createdAt DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (orderId) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (productId) REFERENCES products(id) ON DELETE CASCADE,
    INDEX (orderId),
    INDEX (productId)
);

CREATE TABLE IF NOT EXISTS reviews (
    id VARCHAR(36) PRIMARY KEY,
    productId VARCHAR(36) NOT NULL,
    userId VARCHAR(36) NOT NULL,
    rating INT NOT NULL,
    comment TEXT,
    approved BOOLEAN DEFAULT FALSE,
    approvedAt DATETIME,
    createdAt DATETIME DEFAULT CURRENT_TIMESTAMP,
    updatedAt DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (productId) REFERENCES products(id) ON DELETE CASCADE,
    FOREIGN KEY (userId) REFERENCES users(id) ON DELETE CASCADE,
    INDEX (productId),
    INDEX (userId),
    INDEX (approved)
);

CREATE TABLE IF NOT EXISTS contacts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    status ENUM('NEW', 'READ', 'REPLIED') DEFAULT 'NEW',
    createdAt DATETIME DEFAULT CURRENT_TIMESTAMP,
    INDEX (email),
    INDEX (status),
    INDEX (createdAt)
);

CREATE TABLE IF NOT EXISTS addresses (
    id VARCHAR(36) PRIMARY KEY,
    userId VARCHAR(36) NOT NULL,
    type ENUM('SHIPPING', 'BILLING', 'BOTH') DEFAULT 'SHIPPING',
    fullName VARCHAR(255) NOT NULL,
    phone VARCHAR(50) NOT NULL,
    addressLine1 VARCHAR(255) NOT NULL,
    addressLine2 VARCHAR(255),
    city VARCHAR(100) NOT NULL,
    state VARCHAR(100) NOT NULL,
    postalCode VARCHAR(20) NOT NULL,
    country VARCHAR(100) NOT NULL,
    isDefault BOOLEAN DEFAULT FALSE,
    createdAt DATETIME DEFAULT CURRENT_TIMESTAMP,
    updatedAt DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (userId) REFERENCES users(id) ON DELETE CASCADE,
    INDEX (userId),
    INDEX (isDefault)
);

