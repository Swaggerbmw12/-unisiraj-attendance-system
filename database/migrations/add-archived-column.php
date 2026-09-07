<?php
/**
 * Database Migration: Add archived column to courses table
 * 
 * This migration adds the archived column which allows courses
 * to be archived instead of deleted
 */

// Define root path constant
define('ROOT_PATH', dirname(dirname(__DIR__)));

// Load configuration
require_once ROOT_PATH . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'config.php';
require_once ROOT_PATH . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'database.php';

echo "===========================================\n";
echo "Migration: Add archived column\n";
echo "===========================================\n\n";

try {
    $db = Database::getInstance();
    $pdo = $db->getConnection();
    
    // Check if column already exists
    echo "Checking if archived column exists...\n";
    $stmt = $pdo->query("SHOW COLUMNS FROM courses LIKE 'archived'");
    
    if ($stmt->fetch()) {
        echo "✓ Column 'archived' already exists. No action needed.\n";
    } else {
        echo "Adding 'archived' column...\n";
        
        // Add the column
        $pdo->exec("ALTER TABLE courses ADD COLUMN archived TINYINT(1) DEFAULT 0 AFTER is_active");
        
        echo "✓ Column 'archived' added successfully!\n";
    }
    
    // Also add archived_at timestamp
    echo "\nChecking if archived_at column exists...\n";
    $stmt = $pdo->query("SHOW COLUMNS FROM courses LIKE 'archived_at'");
    
    if ($stmt->fetch()) {
        echo "✓ Column 'archived_at' already exists. No action needed.\n";
    } else {
        echo "Adding 'archived_at' column...\n";
        
        // Add the column
        $pdo->exec("ALTER TABLE courses ADD COLUMN archived_at TIMESTAMP NULL DEFAULT NULL AFTER archived");
        
        echo "✓ Column 'archived_at' added successfully!\n";
    }
    
    echo "\n===========================================\n";
    echo "Migration completed successfully!\n";
    echo "===========================================\n";
    
} catch (PDOException $e) {
    echo "✗ Migration failed: " . $e->getMessage() . "\n";
    exit(1);
}
