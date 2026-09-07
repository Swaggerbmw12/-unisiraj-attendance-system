<?php
/**
 * Check Lecturer Data
 */

define('ROOT_PATH', __DIR__);
require_once ROOT_PATH . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'config.php';
require_once ROOT_PATH . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'database.php';

echo "<h2>Checking Lecturer Data</h2>";
echo "<pre>";

try {
    $db = Database::getInstance();
    
    // Check all lecturers
    echo "All Lecturers:\n";
    echo str_repeat('=', 80) . "\n";
    $lecturers = $db->query("SELECT l.*, u.email FROM lecturers l INNER JOIN users u ON l.user_id = u.id");
    
    if (empty($lecturers)) {
        echo "✗ NO LECTURERS FOUND!\n";
    } else {
        foreach ($lecturers as $lec) {
            echo "ID: {$lec['id']}\n";
            echo "Staff ID: {$lec['staff_id']}\n";
            echo "Name: {$lec['first_name']} {$lec['last_name']}\n";
            echo "Email: {$lec['email']}\n";
            echo "User ID: {$lec['user_id']}\n";
            echo str_repeat('-', 80) . "\n";
        }
    }
    
    // Check users with lecturer role
    echo "\nUsers with Lecturer Role:\n";
    echo str_repeat('=', 80) . "\n";
    $lecturerUsers = $db->query("
        SELECT u.*, r.name as role_name 
        FROM users u 
        INNER JOIN roles r ON u.role_id = r.id 
        WHERE r.name = 'lecturer'
    ");
    
    if (empty($lecturerUsers)) {
        echo "✗ NO LECTURER USERS FOUND!\n";
    } else {
        foreach ($lecturerUsers as $user) {
            echo "User ID: {$user['id']}\n";
            echo "Email: {$user['email']}\n";
            echo "Role: {$user['role_name']}\n";
            
            // Check if they have a lecturer profile
            $profile = $db->queryOne("SELECT * FROM lecturers WHERE user_id = ?", [$user['id']]);
            if ($profile) {
                echo "Profile ID: {$profile['id']}\n";
                echo "Name: {$profile['first_name']} {$profile['last_name']}\n";
            } else {
                echo "✗ NO LECTURER PROFILE!\n";
            }
            echo str_repeat('-', 80) . "\n";
        }
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

echo "</pre>";
