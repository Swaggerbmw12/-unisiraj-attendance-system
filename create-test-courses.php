<?php
/**
 * Create Additional Test Courses
 * Creates multiple courses to test navigation between them
 */

// Define root path
define('ROOT_PATH', __DIR__);

// Load configuration
require_once ROOT_PATH . '/config/config.php';
require_once ROOT_PATH . '/config/database.php';

echo "====================================\n";
echo "Creating Additional Test Courses\n";
echo "====================================\n\n";

try {
    $db = Database::getInstance();
    
    // Get lecturer ID
    $lecturer = $db->queryOne("SELECT id FROM lecturers WHERE staff_id = 'LEC001'");
    
    if (!$lecturer) {
        die("ERROR: Lecturer LEC001 not found. Run setup-test-users.php first.\n");
    }
    
    $lecturerId = $lecturer['id'];
    echo "✓ Found lecturer ID: $lecturerId\n\n";
    
    // Courses to create
    $courses = [
        [
            'course_code' => 'BOT4423',
            'alt_code' => 'BTT3134',
            'course_name' => 'Software Engineering',
            'semester' => 'Semester 2',
            'academic_year' => '2025/2026',
            'credits' => 3,
            'description' => 'Software development methodologies and practices'
        ],
        [
            'course_code' => 'BOT4013',
            'alt_code' => 'BTT3123',
            'course_name' => 'Advanced Programming Java',
            'semester' => 'Semester 1',
            'academic_year' => '2025/2026',
            'credits' => 4,
            'description' => 'Advanced Java programming concepts and frameworks'
        ]
    ];
    
    foreach ($courses as $courseData) {
        echo "Creating course: {$courseData['course_code']}/{$courseData['alt_code']}\n";
        
        // Check if course exists
        $existing = $db->queryOne("SELECT id FROM courses WHERE course_code = ? OR course_code = ?", 
                                 [$courseData['course_code'], $courseData['alt_code']]);
        
        if ($existing) {
            echo "   ✓ Course already exists (ID: {$existing['id']})\n";
            continue;
        }
        
        // Create course
        $db->execute("
            INSERT INTO courses (course_code, course_name, lecturer_id, semester, academic_year, credits, description, is_active)
            VALUES (?, ?, ?, ?, ?, ?, ?, 1)
        ", [
            $courseData['course_code'] . '/' . $courseData['alt_code'],
            $courseData['course_name'],
            $lecturerId,
            $courseData['semester'],
            $courseData['academic_year'],
            $courseData['credits'],
            $courseData['description']
        ]);
        
        $courseId = $db->lastInsertId();
        echo "   ✓ Created course ID: $courseId\n";
        
        // Enroll some students
        $students = $db->query("SELECT id FROM students LIMIT 3");
        foreach ($students as $student) {
            $db->execute(
                "INSERT INTO enrollments (student_id, course_id, enrollment_date, status) VALUES (?, ?, CURDATE(), 'active')",
                [$student['id'], $courseId]
            );
        }
        echo "   ✓ Enrolled 3 students\n";
    }
    
    echo "\n====================================\n";
    echo "Courses Created Successfully!\n";
    echo "====================================\n\n";
    
    // List all courses for this lecturer
    $allCourses = $db->query("
        SELECT c.id, c.course_code, c.course_name, c.semester,
               COUNT(e.id) as enrolled_students
        FROM courses c
        LEFT JOIN enrollments e ON c.id = e.course_id AND e.status = 'active'
        WHERE c.lecturer_id = ? AND c.is_active = 1
        GROUP BY c.id
        ORDER BY c.course_code
    ", [$lecturerId]);
    
    echo "Lecturer's Courses:\n";
    foreach ($allCourses as $course) {
        echo "  ID {$course['id']}: {$course['course_code']} - {$course['course_name']} ({$course['enrolled_students']} students)\n";
    }
    
    echo "\nYou can now test navigation between courses!\n";
    echo "Login as: fatimah@unisiraj.edu.my / Admin@123\n";
    echo "URL: http://localhost:8000/login\n\n";
    
} catch (Exception $e) {
    echo "\n✗ ERROR: " . $e->getMessage() . "\n";
    exit(1);
}