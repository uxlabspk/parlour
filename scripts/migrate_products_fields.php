<?php
// Migration script to add new fields to products table
require_once __DIR__ . '/../includes/db.php';

try {
    echo "Starting migration...\n";
    
    // Add discountedPrice column
    try {
        $pdo->exec("ALTER TABLE products ADD COLUMN discountedPrice DECIMAL(10, 2) AFTER price");
        echo "✓ Added discountedPrice column\n";
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'Duplicate column name') !== false) {
            echo "- discountedPrice column already exists\n";
        } else {
            throw $e;
        }
    }
    
    // Add size column
    try {
        $pdo->exec("ALTER TABLE products ADD COLUMN size VARCHAR(255) AFTER featured");
        echo "✓ Added size column\n";
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'Duplicate column name') !== false) {
            echo "- size column already exists\n";
        } else {
            throw $e;
        }
    }
    
    // Add color column
    try {
        $pdo->exec("ALTER TABLE products ADD COLUMN color VARCHAR(255) AFTER sizes");
        echo "✓ Added color column\n";
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'Duplicate column name') !== false) {
            echo "- color column already exists\n";
        } else {
            throw $e;
        }
    }
    
    // Add shippingPricing column
    try {
        $pdo->exec("ALTER TABLE products ADD COLUMN shippingPricing DECIMAL(10, 2) DEFAULT 0 AFTER colors");
        echo "✓ Added shippingPricing column\n";
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'Duplicate column name') !== false) {
            echo "- shippingPricing column already exists\n";
        } else {
            throw $e;
        }
    }
    
    echo "\nMigration completed successfully!\n";
    echo "All new product fields are now available.\n";
    
} catch (Exception $e) {
    echo "Error during migration: " . $e->getMessage() . "\n";
    exit(1);
}
