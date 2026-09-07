<?php
/**
 * Migration: Add faculty column to lecturers table
 */

require_once 'config/config.php';
require_once 'config/database.php';

try {
    $db = Database::getInstance();
    $conn = $db->getConnection();
    
    // Check if faculty column exists
    $stmt = $conn->query("SHOW COLUMNS FROM lecturers LIKE 'faculty'");
    
    if ($stmt->rowCount() == 0) {
        // Add faculty column
        $conn->exec("ALTER TABLE lecturers ADD COLUMN faculty VARCHAR(100) DEFAULT NULL AFTER department");
        echo "✅ Faculty column added successfully!\n";
    } else {
        echo "ℹ️  Faculty column already exists\n";
    }
    
    echo "Migration completed!\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    exit(1);
}
