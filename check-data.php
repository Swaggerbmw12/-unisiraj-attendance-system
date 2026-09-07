<?php
require_once 'config/config.php';
require_once 'config/database.php';

$db = Database::getInstance();
$conn = $db->getConnection();

echo "Checking database contents...\n\n";

// Check lecturers
$stmt = $conn->query("SELECT l.*, u.email FROM lecturers l JOIN users u ON l.user_id = u.id");
$lecturers = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo "=== Lecturers ===\n";
if (empty($lecturers)) {
    echo "✅ No lecturers found - ready to add new ones!\n";
} else {
    foreach ($lecturers as $lect) {
        echo "- Staff ID: {$lect['staff_id']}, Name: {$lect['first_name']} {$lect['last_name']}, Email: {$lect['email']}, Faculty: " . ($lect['faculty'] ?? 'None') . "\n";
    }
}

// Check students
$stmt = $conn->query("SELECT s.*, u.email FROM students s JOIN users u ON s.user_id = u.id");
$students = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo "\n=== Students ===\n";
if (empty($students)) {
    echo "✅ No students found - ready to add new ones!\n";
} else {
    foreach ($students as $stud) {
        echo "- Matric: {$stud['student_id']}, Name: {$stud['first_name']} {$stud['last_name']}, Email: {$stud['email']}\n";
    }
}

// Check courses
$stmt = $conn->query("SELECT * FROM courses");
$courses = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo "\n=== Courses ===\n";
if (empty($courses)) {
    echo "✅ No courses found - ready to add new ones!\n";
} else {
    foreach ($courses as $course) {
        echo "- Code: {$course['course_code']}, Name: {$course['course_name']}\n";
    }
}

echo "\n✅ Database check complete!\n";
