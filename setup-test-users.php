<?php
/**
 * Setup Test Users
 * Creates sample users for testing the attendance system
 */

// Define root path
define('ROOT_PATH', __DIR__);

// Load configuration
require_once ROOT_PATH . '/config/config.php';
require_once ROOT_PATH . '/config/database.php';

echo "====================================\n";
echo "UniSIRAJ Attendance System\n";
echo "Test Users Setup Script\n";
echo "====================================\n\n";

try {
    $db = Database::getInstance();
    
    // Check database connection
    if (!$db->testConnection()) {
        die("ERROR: Cannot connect to database. Please check your database configuration.\n");
    }
    
    echo "✓ Database connection successful\n\n";
    
    // Check if tables exist
    echo "Checking database tables...\n";
    $tables = ['roles', 'users', 'lecturers', 'students', 'courses', 'enrollments'];
    foreach ($tables as $table) {
        $result = $db->queryOne("SHOW TABLES LIKE '$table'");
        if ($result) {
            echo "✓ Table '$table' exists\n";
        } else {
            echo "✗ Table '$table' missing - Please run schema.sql first!\n";
            die("\nERROR: Database not properly set up. Run database/schema.sql first.\n");
        }
    }
    
    echo "\n====================================\n";
    echo "Creating/Updating Test Users\n";
    echo "====================================\n\n";
    
    // Password: Admin@123
    $hashedPassword = password_hash('Admin@123', PASSWORD_BCRYPT);
    
    // 1. Create Admin User
    echo "1. Creating Admin user...\n";
    $adminEmail = 'admin@unisiraj.edu.my';
    
    // Check if admin exists
    $existingAdmin = $db->queryOne("SELECT id FROM users WHERE email = ?", [$adminEmail]);
    
    if ($existingAdmin) {
        // Update password
        $db->execute("UPDATE users SET password = ? WHERE email = ?", [$hashedPassword, $adminEmail]);
        echo "   ✓ Admin user updated (password reset)\n";
        echo "   Email: $adminEmail\n";
        echo "   Password: Admin@123\n\n";
    } else {
        // Insert new admin
        $db->execute(
            "INSERT INTO users (role_id, email, password, is_active) VALUES (?, ?, ?, ?)",
            [1, $adminEmail, $hashedPassword, 1]
        );
        echo "   ✓ Admin user created\n";
        echo "   Email: $adminEmail\n";
        echo "   Password: Admin@123\n\n";
    }
    
    // 2. Create Lecturer User
    echo "2. Creating Lecturer user...\n";
    $lecturerEmail = 'fatimah@unisiraj.edu.my';
    
    // Check if lecturer user exists
    $existingLecturerUser = $db->queryOne("SELECT id FROM users WHERE email = ?", [$lecturerEmail]);
    
    if ($existingLecturerUser) {
        // Update password
        $db->execute("UPDATE users SET password = ? WHERE email = ?", [$hashedPassword, $lecturerEmail]);
        $lecturerUserId = $existingLecturerUser['id'];
        echo "   ✓ Lecturer user updated (password reset)\n";
    } else {
        // Insert new lecturer user
        $db->execute(
            "INSERT INTO users (role_id, email, password, is_active) VALUES (?, ?, ?, ?)",
            [2, $lecturerEmail, $hashedPassword, 1]
        );
        $lecturerUserId = $db->lastInsertId();
        echo "   ✓ Lecturer user created\n";
    }
    
    // Check if lecturer profile exists
    $existingLecturer = $db->queryOne("SELECT id FROM lecturers WHERE user_id = ?", [$lecturerUserId]);
    
    if (!$existingLecturer) {
        $db->execute(
            "INSERT INTO lecturers (user_id, staff_id, first_name, last_name, phone, department) VALUES (?, ?, ?, ?, ?, ?)",
            [$lecturerUserId, 'LEC001', 'Fatimah', 'Noni Muhamad', '+60123456789', 'Computer Science']
        );
        echo "   ✓ Lecturer profile created\n";
    } else {
        echo "   ✓ Lecturer profile already exists\n";
    }
    
    echo "   Email: $lecturerEmail\n";
    echo "   Password: Admin@123\n";
    echo "   Staff ID: LEC001\n\n";
    
    // 3. Create Student Users
    echo "3. Creating Student users...\n";
    
    $students = [
        ['ahmed@student.unisiraj.edu.my', 'STU2024001', 'Ahmed', 'Mohammed', '+60123456780', 'Computer Science', 4],
        ['aisha@student.unisiraj.edu.my', 'STU2024002', 'Aisha', 'Rahman', '+60123456781', 'Information Technology', 3],
        ['hassan@student.unisiraj.edu.my', 'STU2024003', 'Hassan', 'Ali', '+60123456782', 'Software Engineering', 2],
    ];
    
    foreach ($students as $student) {
        list($email, $studentId, $firstName, $lastName, $phone, $program, $year) = $student;
        
        // Check if student user exists
        $existingStudentUser = $db->queryOne("SELECT id FROM users WHERE email = ?", [$email]);
        
        if ($existingStudentUser) {
            $db->execute("UPDATE users SET password = ? WHERE email = ?", [$hashedPassword, $email]);
            $studentUserId = $existingStudentUser['id'];
        } else {
            $db->execute(
                "INSERT INTO users (role_id, email, password, is_active) VALUES (?, ?, ?, ?)",
                [3, $email, $hashedPassword, 1]
            );
            $studentUserId = $db->lastInsertId();
        }
        
        // Check if student profile exists
        $existingStudent = $db->queryOne("SELECT id FROM students WHERE user_id = ?", [$studentUserId]);
        
        if (!$existingStudent) {
            $db->execute(
                "INSERT INTO students (user_id, student_id, first_name, last_name, phone, program, year_of_study) VALUES (?, ?, ?, ?, ?, ?, ?)",
                [$studentUserId, $studentId, $firstName, $lastName, $phone, $program, $year]
            );
        }
        
        echo "   ✓ Student: $firstName $lastName ($studentId)\n";
    }
    
    echo "   All students password: Admin@123\n\n";
    
    // 4. Create Sample Course
    echo "4. Creating sample course...\n";
    
    $lecturerId = $db->queryOne("SELECT id FROM lecturers WHERE staff_id = 'LEC001'")['id'];
    
    $existingCourse = $db->queryOne("SELECT id FROM courses WHERE course_code = 'CS401'");
    
    if (!$existingCourse) {
        $db->execute(
            "INSERT INTO courses (course_code, course_name, lecturer_id, semester, academic_year, credits, description, is_active) VALUES (?, ?, ?, ?, ?, ?, ?, ?)",
            ['CS401', 'Final Year Project', $lecturerId, 'Semester 2', '2025/2026', 6, 'Final year capstone project', 1]
        );
        $courseId = $db->lastInsertId();
        echo "   ✓ Course 'CS401 - Final Year Project' created\n\n";
    } else {
        $courseId = $existingCourse['id'];
        echo "   ✓ Course 'CS401' already exists\n\n";
    }
    
    // 5. Enroll Students
    echo "5. Enrolling students in course...\n";
    
    $studentIds = $db->query("SELECT id, student_id FROM students WHERE student_id IN ('STU2024001', 'STU2024002', 'STU2024003')");
    
    foreach ($studentIds as $student) {
        $existingEnrollment = $db->queryOne(
            "SELECT id FROM enrollments WHERE student_id = ? AND course_id = ?",
            [$student['id'], $courseId]
        );
        
        if (!$existingEnrollment) {
            $db->execute(
                "INSERT INTO enrollments (student_id, course_id, enrollment_date, status) VALUES (?, ?, CURDATE(), 'active')",
                [$student['id'], $courseId]
            );
            echo "   ✓ Enrolled student {$student['student_id']}\n";
        } else {
            echo "   ✓ Student {$student['student_id']} already enrolled\n";
        }
    }
    
    echo "\n====================================\n";
    echo "Setup Complete!\n";
    echo "====================================\n\n";
    
    echo "Test Credentials:\n\n";
    
    echo "ADMIN:\n";
    echo "  Email: admin@unisiraj.edu.my\n";
    echo "  Password: Admin@123\n";
    echo "  URL: http://localhost:8000/login\n\n";
    
    echo "LECTURER:\n";
    echo "  Email: fatimah@unisiraj.edu.my\n";
    echo "  Password: Admin@123\n";
    echo "  URL: http://localhost:8000/login\n\n";
    
    echo "STUDENTS:\n";
    echo "  Email: ahmed@student.unisiraj.edu.my\n";
    echo "  Email: aisha@student.unisiraj.edu.my\n";
    echo "  Email: hassan@student.unisiraj.edu.my\n";
    echo "  Password: Admin@123 (all students)\n";
    echo "  URL: http://localhost:8000/login\n\n";
    
    echo "You can now login to the system!\n";
    
} catch (Exception $e) {
    echo "\n✗ ERROR: " . $e->getMessage() . "\n";
    echo "\nPlease ensure:\n";
    echo "1. MySQL server is running\n";
    echo "2. Database 'unisiraj_attendance' exists\n";
    echo "3. You have run database/schema.sql\n";
    exit(1);
}
