<?php
/**
 * Automatic Password Fix Script
 * UniSIRAJ Automated Attendance System
 * 
 * This script will update all user passwords to 'Admin@123'
 */

// Define constants to bypass security check
define('ROOT_PATH', __DIR__);
define('APP_PATH', ROOT_PATH . '/app');

// Load database configuration
require_once __DIR__ . '/config/config.php';

echo "========================================\n";
echo "Fixing User Passwords\n";
echo "UniSIRAJ Automated Attendance System\n";
echo "========================================\n\n";

try {
    // Create database connection
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
    $pdo = new PDO($dsn, DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
    
    echo "✅ Database connected successfully\n\n";
    
    // The correct password hash for 'Admin@123'
    $correctHash = '$2y$12$NJMIjC.25FGtd8z393Wp8eFxjjN5ikQ7JUJzYdQzdGgCFGMA/l.5C';
    
    // Update all user passwords
    $sql = "UPDATE users SET password = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$correctHash]);
    
    $updated = $stmt->rowCount();
    echo "✅ Updated $updated user password(s)\n\n";
    
    // Verify the update
    echo "Verifying users:\n";
    echo "----------------------------------------\n";
    
    $sql = "SELECT id, email, role_id, is_active FROM users ORDER BY id";
    $stmt = $pdo->query($sql);
    $users = $stmt->fetchAll();
    
    foreach ($users as $user) {
        $status = $user['is_active'] ? '✅ Active' : '❌ Inactive';
        echo "ID: {$user['id']} | Email: {$user['email']} | Role: {$user['role_id']} | $status\n";
    }
    
    echo "\n========================================\n";
    echo "✅ Password fix completed successfully!\n";
    echo "========================================\n\n";
    
    echo "You can now login with:\n";
    echo "Email:    admin@unisiraj.edu.my\n";
    echo "Password: Admin@123\n\n";
    
    echo "Go to: http://localhost:8000\n\n";
    
    // Test password verification
    echo "Testing password verification:\n";
    if (password_verify('Admin@123', $correctHash)) {
        echo "✅ Password verification: SUCCESSFUL\n";
    } else {
        echo "❌ Password verification: FAILED\n";
    }
    
} catch (PDOException $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    exit(1);
}

echo "\n";
