<?php
/**
 * Remove Sample Students Script
 * This script removes the hardcoded sample students from the database
 */

// Define required constant
define('ROOT_PATH', __DIR__);

// Load configuration
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';

try {
    $database = Database::getInstance();
    $db = $database->getConnection();
    
    echo "=== Removing Sample Students ===\n\n";
    
    // Start transaction
    $db->beginTransaction();
    
    // Sample student emails to remove
    $sampleEmails = [
        'ahmed@student.unisiraj.edu.my',
        'aisha@student.unisiraj.edu.my',
        'hassan@student.unisiraj.edu.my'
    ];
    
    foreach ($sampleEmails as $email) {
        echo "Checking for student with email: $email\n";
        
        // Get user ID
        $stmt = $db->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user) {
            $userId = $user['id'];
            echo "  Found user ID: $userId\n";
            
            // Get student record
            $stmt = $db->prepare("SELECT id, student_id, first_name, last_name FROM students WHERE user_id = ?");
            $stmt->execute([$userId]);
            $student = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($student) {
                echo "  Found student: {$student['student_id']} - {$student['first_name']} {$student['last_name']}\n";
                
                // Delete related records first
                
                // Delete attendance records
                $stmt = $db->prepare("DELETE FROM attendance_records WHERE student_id = ?");
                $stmt->execute([$student['id']]);
                echo "    Deleted attendance records\n";
                
                // Delete enrollments
                $stmt = $db->prepare("DELETE FROM enrollments WHERE student_id = ?");
                $stmt->execute([$student['id']]);
                echo "    Deleted enrollments\n";
                
                // Delete student record
                $stmt = $db->prepare("DELETE FROM students WHERE id = ?");
                $stmt->execute([$student['id']]);
                echo "    Deleted student record\n";
                
                // Delete user account
                $stmt = $db->prepare("DELETE FROM users WHERE id = ?");
                $stmt->execute([$userId]);
                echo "    Deleted user account\n";
                
                echo "  ✓ Successfully removed student\n\n";
            } else {
                echo "  No student record found for this user\n\n";
            }
        } else {
            echo "  User not found (already removed or never existed)\n\n";
        }
    }
    
    // Commit transaction
    $db->commit();
    
    echo "=== All Sample Students Removed Successfully! ===\n";
    echo "\nYou can now add students from the frontend.\n";
    
} catch (Exception $e) {
    if (isset($db) && $db->inTransaction()) {
        $db->rollBack();
    }
    echo "ERROR: " . $e->getMessage() . "\n";
    exit(1);
}
