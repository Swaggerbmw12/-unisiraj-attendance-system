-- ============================================
-- UniSIRAJ Automated Attendance System
-- Database Schema
-- ============================================

-- Create Database
CREATE DATABASE IF NOT EXISTS unisiraj_attendance 
CHARACTER SET utf8mb4 
COLLATE utf8mb4_unicode_ci;

USE unisiraj_attendance;

-- ============================================
-- TABLE: roles
-- Purpose: Define user roles (Admin, Lecturer, Student)
-- ============================================
CREATE TABLE roles (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL UNIQUE,
    description VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_roles_name (name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert default roles
INSERT INTO roles (name, description) VALUES
('admin', 'System Administrator with full access'),
('lecturer', 'Course Lecturer who can manage attendance'),
('student', 'Student who can record attendance');

-- ============================================
-- TABLE: users
-- Purpose: Authentication and user accounts
-- ============================================
CREATE TABLE users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    role_id INT UNSIGNED NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    is_active TINYINT(1) DEFAULT 1,
    last_login TIMESTAMP NULL DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_users_role FOREIGN KEY (role_id) 
        REFERENCES roles(id) ON DELETE RESTRICT ON UPDATE CASCADE,
    INDEX idx_users_email (email),
    INDEX idx_users_role_id (role_id),
    INDEX idx_users_is_active (is_active)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- TABLE: students
-- Purpose: Student profile information
-- ============================================
CREATE TABLE students (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NOT NULL UNIQUE,
    student_id VARCHAR(50) NOT NULL UNIQUE,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    phone VARCHAR(20),
    program VARCHAR(100),
    year_of_study INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_students_user FOREIGN KEY (user_id) 
        REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE,
    INDEX idx_students_user_id (user_id),
    INDEX idx_students_student_id (student_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- TABLE: lecturers
-- Purpose: Lecturer profile information
-- ============================================
CREATE TABLE lecturers (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NOT NULL UNIQUE,
    staff_id VARCHAR(50) NOT NULL UNIQUE,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    phone VARCHAR(20),
    department VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_lecturers_user FOREIGN KEY (user_id) 
        REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE,
    INDEX idx_lecturers_user_id (user_id),
    INDEX idx_lecturers_staff_id (staff_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- TABLE: courses
-- Purpose: Course information
-- ============================================
CREATE TABLE courses (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    course_code VARCHAR(20) NOT NULL UNIQUE,
    course_name VARCHAR(255) NOT NULL,
    lecturer_id INT UNSIGNED,
    semester VARCHAR(20),
    academic_year VARCHAR(20),
    credits INT,
    description TEXT,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_courses_lecturer FOREIGN KEY (lecturer_id) 
        REFERENCES lecturers(id) ON DELETE SET NULL ON UPDATE CASCADE,
    INDEX idx_courses_lecturer_id (lecturer_id),
    INDEX idx_courses_code (course_code),
    INDEX idx_courses_active (is_active)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- TABLE: enrollments
-- Purpose: Student-Course relationships (Many-to-Many)
-- ============================================
CREATE TABLE enrollments (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    student_id INT UNSIGNED NOT NULL,
    course_id INT UNSIGNED NOT NULL,
    enrollment_date DATE NOT NULL,
    status ENUM('active', 'dropped', 'completed') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_enrollments_student FOREIGN KEY (student_id) 
        REFERENCES students(id) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_enrollments_course FOREIGN KEY (course_id) 
        REFERENCES courses(id) ON DELETE CASCADE ON UPDATE CASCADE,
    UNIQUE KEY unique_enrollment (student_id, course_id),
    INDEX idx_enrollments_student_id (student_id),
    INDEX idx_enrollments_course_id (course_id),
    INDEX idx_enrollments_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- TABLE: attendance_sessions
-- Purpose: QR attendance sessions created by lecturers
-- ============================================
CREATE TABLE attendance_sessions (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    course_id INT UNSIGNED NOT NULL,
    lecturer_id INT UNSIGNED NOT NULL,
    session_name VARCHAR(255),
    session_date DATE NOT NULL,
    start_time TIME NOT NULL,
    end_time TIME,
    token VARCHAR(64) NOT NULL UNIQUE,
    expires_at TIMESTAMP NOT NULL,
    is_active TINYINT(1) DEFAULT 1,
    qr_code_path VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_sessions_course FOREIGN KEY (course_id) 
        REFERENCES courses(id) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_sessions_lecturer FOREIGN KEY (lecturer_id) 
        REFERENCES lecturers(id) ON DELETE CASCADE ON UPDATE CASCADE,
    INDEX idx_sessions_course_id (course_id),
    INDEX idx_sessions_lecturer_id (lecturer_id),
    INDEX idx_sessions_token (token),
    INDEX idx_sessions_date (session_date),
    INDEX idx_sessions_active (is_active),
    INDEX idx_sessions_expires (expires_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- TABLE: attendance_records
-- Purpose: Individual student attendance records
-- ============================================
CREATE TABLE attendance_records (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    session_id INT UNSIGNED NOT NULL,
    student_id INT UNSIGNED NOT NULL,
    attendance_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    ip_address VARCHAR(45),
    user_agent TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_records_session FOREIGN KEY (session_id) 
        REFERENCES attendance_sessions(id) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_records_student FOREIGN KEY (student_id) 
        REFERENCES students(id) ON DELETE CASCADE ON UPDATE CASCADE,
    UNIQUE KEY unique_attendance (session_id, student_id),
    INDEX idx_records_session_id (session_id),
    INDEX idx_records_student_id (student_id),
    INDEX idx_records_time (attendance_time)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- TABLE: audit_logs
-- Purpose: Track all system activities for security and monitoring
-- ============================================
CREATE TABLE audit_logs (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED,
    action VARCHAR(100) NOT NULL,
    details JSON,
    ip_address VARCHAR(45),
    user_agent TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_audit_user FOREIGN KEY (user_id) 
        REFERENCES users(id) ON DELETE SET NULL ON UPDATE CASCADE,
    INDEX idx_audit_user_id (user_id),
    INDEX idx_audit_action (action),
    INDEX idx_audit_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Insert Default Admin User
-- Email: admin@unisiraj.edu.my
-- Password: Admin@123 (hashed with bcrypt)
-- ============================================
-- Note: This password should be changed after first login
-- Hash generated using: password_hash('Admin@123', PASSWORD_BCRYPT)
INSERT INTO users (role_id, email, password, is_active) VALUES
(1, 'admin@unisiraj.edu.my', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 1);

-- ============================================
-- VIEWS FOR COMMON QUERIES
-- ============================================

-- View: All students with their user information
CREATE VIEW view_students_with_users AS
SELECT 
    s.id,
    s.student_id,
    s.first_name,
    s.last_name,
    s.phone,
    s.program,
    s.year_of_study,
    u.email,
    u.is_active,
    u.last_login,
    s.created_at
FROM students s
INNER JOIN users u ON s.user_id = u.id;

-- View: All lecturers with their user information
CREATE VIEW view_lecturers_with_users AS
SELECT 
    l.id,
    l.staff_id,
    l.first_name,
    l.last_name,
    l.phone,
    l.department,
    u.email,
    u.is_active,
    u.last_login,
    l.created_at
FROM lecturers l
INNER JOIN users u ON l.user_id = u.id;

-- View: Course enrollment summary
CREATE VIEW view_course_enrollments AS
SELECT 
    c.id AS course_id,
    c.course_code,
    c.course_name,
    CONCAT(l.first_name, ' ', l.last_name) AS lecturer_name,
    COUNT(e.id) AS total_students,
    c.semester,
    c.academic_year
FROM courses c
LEFT JOIN lecturers l ON c.lecturer_id = l.id
LEFT JOIN enrollments e ON c.id = e.course_id AND e.status = 'active'
GROUP BY c.id, c.course_code, c.course_name, l.first_name, l.last_name, c.semester, c.academic_year;

-- View: Attendance session summary
CREATE VIEW view_attendance_sessions_summary AS
SELECT 
    ats.id AS session_id,
    ats.session_name,
    ats.session_date,
    c.course_code,
    c.course_name,
    CONCAT(l.first_name, ' ', l.last_name) AS lecturer_name,
    ats.is_active,
    ats.expires_at,
    COUNT(ar.id) AS total_attendees,
    (SELECT COUNT(*) FROM enrollments WHERE course_id = c.id AND status = 'active') AS total_enrolled,
    ROUND((COUNT(ar.id) / (SELECT COUNT(*) FROM enrollments WHERE course_id = c.id AND status = 'active') * 100), 2) AS attendance_percentage
FROM attendance_sessions ats
INNER JOIN courses c ON ats.course_id = c.id
INNER JOIN lecturers l ON ats.lecturer_id = l.id
LEFT JOIN attendance_records ar ON ats.id = ar.session_id
GROUP BY ats.id, ats.session_name, ats.session_date, c.course_code, c.course_name, 
         l.first_name, l.last_name, ats.is_active, ats.expires_at, c.id;

-- ============================================
-- STORED PROCEDURES
-- ============================================

-- Procedure: Get student attendance percentage for a course
DELIMITER $$
CREATE PROCEDURE sp_get_student_attendance_percentage(
    IN p_student_id INT,
    IN p_course_id INT
)
BEGIN
    SELECT 
        COUNT(DISTINCT ats.id) AS total_sessions,
        COUNT(DISTINCT ar.id) AS attended_sessions,
        ROUND((COUNT(DISTINCT ar.id) / COUNT(DISTINCT ats.id) * 100), 2) AS percentage
    FROM attendance_sessions ats
    LEFT JOIN attendance_records ar ON ats.id = ar.session_id AND ar.student_id = p_student_id
    WHERE ats.course_id = p_course_id;
END$$
DELIMITER ;

-- ============================================
-- SAMPLE DATA FOR TESTING (Optional - Remove in production)
-- ============================================

-- Sample Lecturer
INSERT INTO users (role_id, email, password, is_active) VALUES
(2, 'fatimah@unisiraj.edu.my', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 1);

INSERT INTO lecturers (user_id, staff_id, first_name, last_name, phone, department) VALUES
(LAST_INSERT_ID(), 'LEC001', 'Fatimah', 'Noni Muhamad', '+60123456789', 'Computer Science');

-- Sample Students
INSERT INTO users (role_id, email, password, is_active) VALUES
(3, 'ahmed@student.unisiraj.edu.my', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 1),
(3, 'aisha@student.unisiraj.edu.my', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 1),
(3, 'hassan@student.unisiraj.edu.my', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 1);

INSERT INTO students (user_id, student_id, first_name, last_name, phone, program, year_of_study) VALUES
((SELECT id FROM users WHERE email = 'ahmed@student.unisiraj.edu.my'), 'STU2024001', 'Ahmed', 'Mohammed', '+60123456780', 'Computer Science', 4),
((SELECT id FROM users WHERE email = 'aisha@student.unisiraj.edu.my'), 'STU2024002', 'Aisha', 'Rahman', '+60123456781', 'Information Technology', 3),
((SELECT id FROM users WHERE email = 'hassan@student.unisiraj.edu.my'), 'STU2024003', 'Hassan', 'Ali', '+60123456782', 'Software Engineering', 2);

-- Sample Course
INSERT INTO courses (course_code, course_name, lecturer_id, semester, academic_year, credits, description, is_active) VALUES
('CS401', 'Final Year Project', (SELECT id FROM lecturers WHERE staff_id = 'LEC001'), 'Semester 2', '2025/2026', 6, 'Final year capstone project', 1);

-- Enroll Students in Course
INSERT INTO enrollments (student_id, course_id, enrollment_date, status) VALUES
((SELECT id FROM students WHERE student_id = 'STU2024001'), (SELECT id FROM courses WHERE course_code = 'CS401'), CURDATE(), 'active'),
((SELECT id FROM students WHERE student_id = 'STU2024002'), (SELECT id FROM courses WHERE course_code = 'CS401'), CURDATE(), 'active'),
((SELECT id FROM students WHERE student_id = 'STU2024003'), (SELECT id FROM courses WHERE course_code = 'CS401'), CURDATE(), 'active');

-- ============================================
-- DATABASE SCHEMA COMPLETE
-- ============================================
-- 
-- TABLES CREATED:
-- 1. roles (3 records)
-- 2. users (with sample data)
-- 3. students (with sample data)
-- 4. lecturers (with sample data)
-- 5. courses (with sample data)
-- 6. enrollments (with sample data)
-- 7. attendance_sessions
-- 8. attendance_records
-- 9. audit_logs
--
-- VIEWS CREATED:
-- 1. view_students_with_users
-- 2. view_lecturers_with_users
-- 3. view_course_enrollments
-- 4. view_attendance_sessions_summary
--
-- STORED PROCEDURES:
-- 1. sp_get_student_attendance_percentage
--
-- DEFAULT LOGIN CREDENTIALS:
-- Admin:
--   Email: admin@unisiraj.edu.my
--   Password: Admin@123
--
-- Lecturer (Sample):
--   Email: fatimah@unisiraj.edu.my
--   Password: Admin@123
--
-- Student (Sample):
--   Email: ahmed@student.unisiraj.edu.my
--   Password: Admin@123
--
-- SECURITY NOTE: Change all default passwords after first login!
-- ============================================
