<?php
// Migration script to add approval fields to reviews table
include __DIR__ . '/../includes/db.php';

try {
    // Check if approved column exists
    $stmt = $pdo->query("SHOW COLUMNS FROM reviews LIKE 'approved'");
    $columnExists = $stmt->fetch();

    if (!$columnExists) {
        echo "Adding 'approved' column to reviews table...\n";
        $pdo->exec("ALTER TABLE reviews ADD COLUMN approved BOOLEAN DEFAULT FALSE AFTER comment");
        echo "✓ Added 'approved' column\n";
    } else {
        echo "✓ 'approved' column already exists\n";
    }

    // Check if approvedAt column exists
    $stmt = $pdo->query("SHOW COLUMNS FROM reviews LIKE 'approvedAt'");
    $columnExists = $stmt->fetch();

    if (!$columnExists) {
        echo "Adding 'approvedAt' column to reviews table...\n";
        $pdo->exec("ALTER TABLE reviews ADD COLUMN approvedAt DATETIME AFTER approved");
        echo "✓ Added 'approvedAt' column\n";
    } else {
        echo "✓ 'approvedAt' column already exists\n";
    }

    // Add index on approved column if it doesn't exist
    $stmt = $pdo->query("SHOW INDEX FROM reviews WHERE Key_name = 'approved'");
    $indexExists = $stmt->fetch();

    if (!$indexExists) {
        echo "Adding index on 'approved' column...\n";
        $pdo->exec("CREATE INDEX approved ON reviews(approved)");
        echo "✓ Added index on 'approved' column\n";
    } else {
        echo "✓ Index on 'approved' column already exists\n";
    }

    echo "\n✅ Migration completed successfully!\n";

} catch (PDOException $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    exit(1);
}
?>
