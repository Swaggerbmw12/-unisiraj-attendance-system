<?php
/**
 * Remove hard-coded test data
 * This will remove Dr. Fatimah Noni and other test users
 */

require_once 'config/config.php';
require_once 'config/database.php';

try {
    $db = Database::getInstance();
    $conn = $db->getConnection();
    
    echo "Starting cleanup of test data...\n\n";
    
    // Find and remove Dr. Fatimah Noni (lecturer)
    $stmt = $conn->prepare("SELECT u.id as user_id, l.id as lecturer_id 
                           FROM users u 
                           LEFT JOIN lecturers l ON u.id = l.user_id 
                           WHERE u.email LIKE '%fatimah%'");
    $stmt->execute();
    $fatimahUsers = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($fatimahUsers as $user) {
        if ($user['lecturer_id']) {
            $conn->prepare("DELETE FROM lecturers WHERE id = ?")->execute([$user['lecturer_id']]);
            echo "✅ Removed lecturer record for Fatimah (ID: {$user['lecturer_id']})\n";
        }
        $conn->prepare("DELETE FROM users WHERE id = ?")->execute([$user['user_id']]);
        echo "✅ Removed user account for Fatimah (ID: {$user['user_id']})\n";
    }
    
    // Find and remove Ahmed (student test data)
    $stmt = $conn->prepare("SELECT u.id as user_id, s.id as student_id 
                           FROM users u 
                           LEFT JOIN students s ON u.id = s.user_id 
                           WHERE u.email LIKE '%ahmed%' OR s.student_id = '8231123215'");
    $stmt->execute();
    $ahmedUsers = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($ahmedUsers as $user) {
        if ($user['student_id']) {
            // Remove enrollments first
            $conn->prepare("DELETE FROM enrollments WHERE student_id = ?")->execute([$user['student_id']]);
            echo "✅ Removed enrollments for Ahmed (Student ID: {$user['student_id']})\n";
            
            // Remove attendance records
            $conn->prepare("DELETE FROM attendance_records WHERE student_id = ?")->execute([$user['student_id']]);
            echo "✅ Removed attendance records for Ahmed\n";
            
            // Remove student record
            $conn->prepare("DELETE FROM students WHERE id = ?")->execute([$user['student_id']]);
            echo "✅ Removed student record for Ahmed (ID: {$user['student_id']})\n";
        }
        if ($user['user_id']) {
            $conn->prepare("DELETE FROM users WHERE id = ?")->execute([$user['user_id']]);
            echo "✅ Removed user account for Ahmed (ID: {$user['user_id']})\n";
        }
    }
    
    // Remove test courses
    $stmt = $conn->prepare("SELECT id, course_code, course_name FROM courses 
                           WHERE course_code IN ('BOT4153/BTE3234', 'BOT4103/BTE3233') 
                           OR course_name LIKE '%JAVA%' 
                           OR course_name LIKE '%Python%'");
    $stmt->execute();
    $testCourses = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($testCourses as $course) {
        // Remove enrollments
        $conn->prepare("DELETE FROM enrollments WHERE course_id = ?")->execute([$course['id']]);
        echo "✅ Removed enrollments for course: {$course['course_name']}\n";
        
        // Remove attendance sessions
        $conn->prepare("DELETE FROM attendance_sessions WHERE course_id = ?")->execute([$course['id']]);
        echo "✅ Removed attendance sessions for course: {$course['course_name']}\n";
        
        // Remove course
        $conn->prepare("DELETE FROM courses WHERE id = ?")->execute([$course['id']]);
        echo "✅ Removed course: {$course['course_name']} ({$course['course_code']})\n";
    }
    
    echo "\n✅ Test data cleanup completed!\n";
    echo "You can now add Dr. Fatimah Noni and other users through the frontend.\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    exit(1);
}
