#!/bin/bash
# migrate_hbl_payment.sh
# Script to migrate database for HBL Konnect payment integration

echo "=================================="
echo "HBL Konnect Payment Migration"
echo "=================================="
echo ""

# Database credentials (update these)
read -p "Enter MySQL username: " DB_USER
read -sp "Enter MySQL password: " DB_PASS
echo ""
read -p "Enter database name: " DB_NAME
echo ""

echo "Creating backup..."
mysqldump -u "$DB_USER" -p"$DB_PASS" "$DB_NAME" > "backup_$(date +%Y%m%d_%H%M%S).sql"
echo "Backup created!"
echo ""

echo "Applying database migrations..."

mysql -u "$DB_USER" -p"$DB_PASS" "$DB_NAME" << EOF

-- Update orders table
ALTER TABLE orders 
ADD COLUMN IF NOT EXISTS paymentStatus ENUM('PENDING', 'COMPLETED', 'FAILED', 'REFUNDED') DEFAULT 'PENDING' AFTER paymentMethod;

ALTER TABLE orders 
ADD COLUMN IF NOT EXISTS transactionId VARCHAR(255) AFTER paymentStatus;

ALTER TABLE orders 
ADD COLUMN IF NOT EXISTS transactionData JSON AFTER transactionId;

ALTER TABLE orders 
ADD COLUMN IF NOT EXISTS receiptNumber VARCHAR(100) AFTER transactionData;

ALTER TABLE orders 
ADD INDEX IF NOT EXISTS idx_paymentStatus (paymentStatus);

ALTER TABLE orders 
ADD INDEX IF NOT EXISTS idx_transactionId (transactionId);

-- Create payments table
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

EOF

if [ $? -eq 0 ]; then
    echo ""
    echo "✅ Migration completed successfully!"
    echo ""
    echo "Next steps:"
    echo "1. Review HBL_PAYMENT_SETUP.md for integration guide"
    echo "2. Contact HBL Bank for merchant account"
    echo "3. Test checkout flow on your website"
    echo ""
else
    echo ""
    echo "❌ Migration failed! Check errors above."
    echo "You can restore from backup if needed."
    echo ""
fi
