<?php
/**
 * migrate_hbl_payment.php
 * Script to migrate database for HBL Konnect payment integration
 * 
 * Usage: 
 * - Command line: php migrate_hbl_payment.php
 * - Web browser: http://yoursite.com/scripts/migrate_hbl_payment.php
 */

// Prevent direct web access in production (optional security)
// Uncomment the following lines in production:
// if (php_sapi_name() !== 'cli') {
//     die('This script can only be run from command line');
// }

error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "==================================\n";
echo "HBL Konnect Payment Migration\n";
echo "==================================\n\n";

// Database configuration
// Option 1: Load from existing db.php
if (file_exists(__DIR__ . '/../includes/db.php')) {
    echo "Loading database configuration...\n";
    require_once __DIR__ . '/../includes/db.php';
    
    // Use existing PDO connection
    if (!isset($pdo)) {
        die("Error: Database connection not found in db.php\n");
    }
} else {
    // Option 2: Manual configuration
    echo "Enter database credentials:\n";
    
    $DB_HOST = readline("Database host [localhost]: ") ?: 'localhost';
    $DB_NAME = readline("Database name: ");
    $DB_USER = readline("Database username: ");
    $DB_PASS = readline("Database password: ");
    
    if (empty($DB_NAME) || empty($DB_USER)) {
        die("Error: Database name and username are required\n");
    }
    
    try {
        $pdo = new PDO("mysql:host=$DB_HOST;dbname=$DB_NAME;charset=utf8mb4", $DB_USER, $DB_PASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        echo "Database connection established\n\n";
    } catch (PDOException $e) {
        die("Database connection failed: " . $e->getMessage() . "\n");
    }
}

// Create backup
echo "Creating backup...\n";
$backupFile = __DIR__ . "/backup_" . date('Ymd_His') . ".sql";

try {
    // Get database name from PDO
    $dbName = $pdo->query('SELECT DATABASE()')->fetchColumn();
    
    // Get all tables
    $tables = [];
    $result = $pdo->query('SHOW TABLES');
    while ($row = $result->fetch(PDO::FETCH_NUM)) {
        $tables[] = $row[0];
    }
    
    $backup = "-- Database Backup\n";
    $backup .= "-- Generated: " . date('Y-m-d H:i:s') . "\n\n";
    $backup .= "SET FOREIGN_KEY_CHECKS=0;\n\n";
    
    foreach ($tables as $table) {
        // Get CREATE TABLE statement
        $createTable = $pdo->query("SHOW CREATE TABLE `$table`")->fetch(PDO::FETCH_ASSOC);
        $backup .= "-- Table: $table\n";
        $backup .= "DROP TABLE IF EXISTS `$table`;\n";
        $backup .= $createTable['Create Table'] . ";\n\n";
        
        // Get table data
        $rows = $pdo->query("SELECT * FROM `$table`")->fetchAll(PDO::FETCH_ASSOC);
        if (!empty($rows)) {
            $backup .= "-- Data for $table\n";
            foreach ($rows as $row) {
                $values = array_map(function($value) use ($pdo) {
                    return $value === null ? 'NULL' : $pdo->quote($value);
                }, array_values($row));
                $backup .= "INSERT INTO `$table` VALUES (" . implode(', ', $values) . ");\n";
            }
            $backup .= "\n";
        }
    }
    
    $backup .= "SET FOREIGN_KEY_CHECKS=1;\n";
    
    file_put_contents($backupFile, $backup);
    echo "✓ Backup created: $backupFile\n\n";
} catch (Exception $e) {
    echo "Warning: Could not create backup: " . $e->getMessage() . "\n";
    echo "Continue anyway? (y/n): ";
    $continue = strtolower(trim(fgets(STDIN)));
    if ($continue !== 'y' && $continue !== 'yes') {
        die("Migration cancelled\n");
    }
    echo "\n";
}

// Apply migrations
echo "Applying database migrations...\n\n";

$migrations = [
    [
        'name' => 'Add paymentStatus column to orders table',
        'sql' => "ALTER TABLE orders 
                  ADD COLUMN paymentStatus ENUM('PENDING', 'COMPLETED', 'FAILED', 'REFUNDED') DEFAULT 'PENDING' 
                  AFTER paymentMethod"
    ],
    [
        'name' => 'Add transactionId column to orders table',
        'sql' => "ALTER TABLE orders 
                  ADD COLUMN transactionId VARCHAR(255) 
                  AFTER paymentStatus"
    ],
    [
        'name' => 'Add transactionData column to orders table',
        'sql' => "ALTER TABLE orders 
                  ADD COLUMN transactionData JSON 
                  AFTER transactionId"
    ],
    [
        'name' => 'Add receiptNumber column to orders table',
        'sql' => "ALTER TABLE orders 
                  ADD COLUMN receiptNumber VARCHAR(100) 
                  AFTER transactionData"
    ],
    [
        'name' => 'Add index on paymentStatus',
        'sql' => "ALTER TABLE orders 
                  ADD INDEX idx_paymentStatus (paymentStatus)"
    ],
    [
        'name' => 'Add index on transactionId',
        'sql' => "ALTER TABLE orders 
                  ADD INDEX idx_transactionId (transactionId)"
    ],
    [
        'name' => 'Create payments table',
        'sql' => "CREATE TABLE IF NOT EXISTS payments (
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
                )"
    ]
];

$successCount = 0;
$errorCount = 0;
$errors = [];

foreach ($migrations as $migration) {
    try {
        // Check if column/table already exists
        $skip = false;
        
        if (strpos($migration['sql'], 'ADD COLUMN') !== false) {
            // Extract column name
            preg_match('/ADD COLUMN (\w+)/', $migration['sql'], $matches);
            if (isset($matches[1])) {
                $columnName = $matches[1];
                $stmt = $pdo->query("SHOW COLUMNS FROM orders LIKE '$columnName'");
                if ($stmt->rowCount() > 0) {
                    echo "  ⊘ Skipped: {$migration['name']} (already exists)\n";
                    $skip = true;
                }
            }
        } elseif (strpos($migration['sql'], 'ADD INDEX') !== false) {
            // Extract index name
            preg_match('/ADD INDEX (\w+)/', $migration['sql'], $matches);
            if (isset($matches[1])) {
                $indexName = $matches[1];
                $stmt = $pdo->query("SHOW INDEX FROM orders WHERE Key_name = '$indexName'");
                if ($stmt->rowCount() > 0) {
                    echo "  ⊘ Skipped: {$migration['name']} (already exists)\n";
                    $skip = true;
                }
            }
        } elseif (strpos($migration['sql'], 'CREATE TABLE') !== false) {
            // Check if table exists
            $stmt = $pdo->query("SHOW TABLES LIKE 'payments'");
            if ($stmt->rowCount() > 0) {
                echo "  ⊘ Skipped: {$migration['name']} (already exists)\n";
                $skip = true;
            }
        }
        
        if (!$skip) {
            $pdo->exec($migration['sql']);
            echo "  ✓ Success: {$migration['name']}\n";
            $successCount++;
        }
    } catch (PDOException $e) {
        // Check if error is due to duplicate column/index
        if (strpos($e->getMessage(), 'Duplicate column') !== false || 
            strpos($e->getMessage(), 'Duplicate key') !== false ||
            strpos($e->getMessage(), 'already exists') !== false) {
            echo "  ⊘ Skipped: {$migration['name']} (already exists)\n";
        } else {
            echo "  ✗ Failed: {$migration['name']}\n";
            echo "    Error: {$e->getMessage()}\n";
            $errorCount++;
            $errors[] = [
                'migration' => $migration['name'],
                'error' => $e->getMessage()
            ];
        }
    }
}

echo "\n";
echo "==================================\n";
echo "Migration Summary\n";
echo "==================================\n";
echo "Total migrations: " . count($migrations) . "\n";
echo "Successful: $successCount\n";
echo "Errors: $errorCount\n";
echo "\n";

if ($errorCount > 0) {
    echo "❌ Migration completed with errors!\n\n";
    echo "Errors:\n";
    foreach ($errors as $error) {
        echo "- {$error['migration']}: {$error['error']}\n";
    }
    echo "\nYou can restore from backup if needed: $backupFile\n";
    exit(1);
} else {
    echo "✅ Migration completed successfully!\n\n";
    echo "Next steps:\n";
    echo "1. Review HBL_PAYMENT_SETUP.md for integration guide\n";
    echo "2. Contact HBL Bank for merchant account\n";
    echo "3. Test checkout flow on your website\n";
    echo "\n";
    exit(0);
}
?>
