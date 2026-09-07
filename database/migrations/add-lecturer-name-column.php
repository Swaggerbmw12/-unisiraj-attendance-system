<?php
/**
 * Database Migration: Add lecturer_name column to courses table
 * 
 * This migration adds the lecturer_name column which allows courses
 * to have a lecturer name even when lecturer_id is NULL
 */

// Define root path constant
define('ROOT_PATH', dirname(dirname(__DIR__)));

// Load configuration
require_once ROOT_PATH . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'config.php';
require_once ROOT_PATH . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'database.php';

echo "===========================================\n";
echo "Migration: Add lecturer_name column\n";
echo "===========================================\n\n";

try {
    $db = Database::getInstance();
    $pdo = $db->getConnection();
    
    // Check if column already exists
    echo "Checking if lecturer_name column exists...\n";
    $stmt = $pdo->query("SHOW COLUMNS FROM courses LIKE 'lecturer_name'");
    
    if ($stmt->fetch()) {
        echo "✓ Column 'lecturer_name' already exists. No action needed.\n";
    } else {
        echo "Adding 'lecturer_name' column...\n";
        
        // Add the column
        $pdo->exec("ALTER TABLE courses ADD COLUMN lecturer_name VARCHAR(255) NULL AFTER lecturer_id");
        
        echo "✓ Column 'lecturer_name' added successfully!\n";
    }
    
    echo "\n===========================================\n";
    echo "Migration completed successfully!\n";
    echo "===========================================\n";
    
} catch (PDOException $e) {
    echo "✗ Migration failed: " . $e->getMessage() . "\n";
    exit(1);
}
