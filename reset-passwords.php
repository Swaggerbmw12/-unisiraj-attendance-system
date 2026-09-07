<?php
/**
 * Password Reset Script
 * Resets all user passwords to: Admin@123
 */

// Load configuration
define('ROOT_PATH', __DIR__);
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';

echo "==================================\n";
echo "Password Reset Script\n";
echo "==================================\n\n";

try {
    $db = Database::getInstance()->getConnection();
    
    // The password we want to set
    $plainPassword = 'Admin@123';
    
    // Hash the password using bcrypt
    $hashedPassword = password_hash($plainPassword, PASSWORD_BCRYPT, ['cost' => 12]);
    
    echo "New password hash generated: " . substr($hashedPassword, 0, 20) . "...\n\n";
    
    // Get all users
    $stmt = $db->query("SELECT id, email FROM users");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "Found " . count($users) . " users to update:\n\n";
    
    // Update each user's password
    $updateStmt = $db->prepare("UPDATE users SET password = ? WHERE id = ?");
    
    foreach ($users as $user) {
        $updateStmt->execute([$hashedPassword, $user['id']]);
        echo "✓ Updated password for: " . $user['email'] . "\n";
    }
    
    echo "\n==================================\n";
    echo "SUCCESS! All passwords reset to: Admin@123\n";
    echo "==================================\n\n";
    
    echo "You can now login with:\n";
    echo "- admin@unisiraj.edu.my / Admin@123\n";
    echo "- fatimah@unisiraj.edu.my / Admin@123\n";
    echo "- ahmed@student.unisiraj.edu.my / Admin@123\n";
    
} catch (Exception $e) {
    echo "\nERROR: " . $e->getMessage() . "\n";
    exit(1);
}
