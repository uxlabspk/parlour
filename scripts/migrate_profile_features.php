<?php
// Migration script to add profile features
require_once __DIR__ . '/../includes/db.php';

try {
    echo "Starting migration...\n";

    // Add profileImage column to users table
    $pdo->exec("ALTER TABLE users ADD COLUMN IF NOT EXISTS profileImage VARCHAR(255) AFTER password");
    echo "✓ Added profileImage column to users table\n";

    // Create addresses table
    $pdo->exec("
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
        )
    ");
    echo "✓ Created addresses table\n";

    echo "\n✅ Migration completed successfully!\n";
} catch (Exception $e) {
    echo "❌ Migration failed: " . $e->getMessage() . "\n";
    exit(1);
}
