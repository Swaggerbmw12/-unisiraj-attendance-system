<?php
/**
 * Fix Lecturer Session Issues
 * 
 * This script identifies and fixes common issues with lecturer sessions:
 * 1. Users with lecturer role but no lecturer profile
 * 2. Invalid profile_id references
 */

define('ROOT_PATH', __DIR__);
require_once ROOT_PATH . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'config.php';
require_once ROOT_PATH . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'database.php';

echo "<h2>Fixing Lecturer Session Issues</h2>";
echo "<pre>";
echo str_repeat('=', 80) . "\n";

try {
    $db = Database::getInstance();
    
    // Find users with lecturer role but no profile
    echo "Step 1: Checking for users with lecturer role but no profile...\n";
    echo str_repeat('-', 80) . "\n";
    
    $orphanedUsers = $db->query("
        SELECT u.id, u.email, r.name as role_name
        FROM users u
        INNER JOIN roles r ON u.role_id = r.id
        LEFT JOIN lecturers l ON u.id = l.user_id
        WHERE r.name = 'lecturer' AND l.id IS NULL
    ");
    
    if (empty($orphanedUsers)) {
        echo "✓ No orphaned lecturer accounts found.\n\n";
    } else {
        echo "⚠ Found " . count($orphanedUsers) . " lecturer user(s) without profile:\n\n";
        foreach ($orphanedUsers as $user) {
            echo "  • Email: {$user['email']} (User ID: {$user['id']})\n";
        }
        echo "\n";
    }
    
    // Show all valid lecturers
    echo "Step 2: Valid Lecturer Profiles\n";
    echo str_repeat('-', 80) . "\n";
    
    $validLecturers = $db->query("
        SELECT l.id, l.user_id, l.staff_id, l.first_name, l.last_name, u.email
        FROM lecturers l
        INNER JOIN users u ON l.user_id = u.id
        ORDER BY l.id
    ");
    
    if (empty($validLecturers)) {
        echo "✗ NO VALID LECTURERS FOUND!\n\n";
    } else {
        echo "Found " . count($validLecturers) . " valid lecturer(s):\n\n";
        foreach ($validLecturers as $lec) {
            echo "  Lecturer ID: {$lec['id']}\n";
            echo "  User ID: {$lec['user_id']}\n";
            echo "  Staff ID: {$lec['staff_id']}\n";
            echo "  Name: {$lec['first_name']} {$lec['last_name']}\n";
            echo "  Email: {$lec['email']}\n";
            echo "  " . str_repeat('-', 76) . "\n";
        }
        echo "\n";
    }
    
    // Check courses with invalid lecturer references
    echo "Step 3: Checking courses with invalid lecturer references...\n";
    echo str_repeat('-', 80) . "\n";
    
    $invalidCourses = $db->query("
        SELECT c.id, c.course_code, c.course_name, c.lecturer_id
        FROM courses c
        LEFT JOIN lecturers l ON c.lecturer_id = l.id
        WHERE c.lecturer_id IS NOT NULL AND l.id IS NULL
    ");
    
    if (empty($invalidCourses)) {
        echo "✓ No courses with invalid lecturer references found.\n\n";
    } else {
        echo "⚠ Found " . count($invalidCourses) . " course(s) with invalid lecturer_id:\n\n";
        foreach ($invalidCourses as $course) {
            echo "  • Course: {$course['course_code']} - {$course['course_name']}\n";
            echo "    Invalid Lecturer ID: {$course['lecturer_id']}\n\n";
        }
        
        // Offer to fix by setting to NULL
        $fixCount = 0;
        foreach ($invalidCourses as $course) {
            $db->execute("UPDATE courses SET lecturer_id = NULL WHERE id = ?", [$course['id']]);
            $fixCount++;
        }
        echo "✓ Fixed $fixCount course(s) by setting lecturer_id to NULL\n\n";
    }
    
    // Summary
    echo str_repeat('=', 80) . "\n";
    echo "SUMMARY\n";
    echo str_repeat('=', 80) . "\n";
    echo "Valid Lecturers: " . count($validLecturers) . "\n";
    echo "Orphaned Users: " . count($orphanedUsers) . "\n";
    echo "Invalid Course References: " . count($invalidCourses) . " (fixed)\n\n";
    
    if (!empty($orphanedUsers)) {
        echo "⚠ ACTION REQUIRED:\n";
        echo "The following users have lecturer role but no lecturer profile.\n";
        echo "You need to either:\n";
        echo "  1. Create lecturer profiles for them, or\n";
        echo "  2. Change their role\n\n";
        
        foreach ($orphanedUsers as $user) {
            echo "User: {$user['email']} (ID: {$user['id']})\n";
        }
    }
    
    echo "\n";
    echo "✓ Done! Please ask your lecturer to LOGOUT and LOGIN again to refresh session.\n";
    
} catch (Exception $e) {
    echo "✗ ERROR: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
}

echo "</pre>";
