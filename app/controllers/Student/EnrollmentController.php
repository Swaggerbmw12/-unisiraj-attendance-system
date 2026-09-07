<?php

require_once dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'Student.php';
require_once dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'Enrollment.php';
require_once dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'Course.php';

/**
 * Student Enrollment Controller
 * UniSIRAJ Automated Attendance System
 * 
 * Handles student course enrollment
 */
class StudentEnrollmentController extends BaseController
{
    private $enrollmentModel;
    private $courseModel;
    
    public function __construct()
    {
        parent::__construct();
        $this->requireAuth(['student']);
        $this->enrollmentModel = new Enrollment();
        $this->courseModel = new Course();
    }
    
    /**
     * Show available courses for enrollment
     */
    public function index()
    {
        $studentId = $_SESSION['profile_id'] ?? null;
        
        if (!$studentId) {
            $this->setFlashMessage('Student profile not found', 'error');
            $this->redirect('/login');
            return;
        }
        
        // Get courses student is already enrolled in
        $enrolledCourses = $this->db->query("
            SELECT course_id FROM enrollments 
            WHERE student_id = ? AND status = 'active'
        ", [$studentId]);
        
        $enrolledIds = array_column($enrolledCourses, 'course_id');
        
        // Get available courses (not enrolled + active)
        $availableCourses = [];
        if (!empty($enrolledIds)) {
            $placeholders = str_repeat('?,', count($enrolledIds) - 1) . '?';
            $availableCourses = $this->db->query("
                SELECT c.*, 
                       CONCAT(l.first_name, ' ', l.last_name) as lecturer_name,
                       (SELECT COUNT(*) FROM enrollments WHERE course_id = c.id AND status = 'active') as enrolled_students
                FROM courses c
                LEFT JOIN lecturers l ON c.lecturer_id = l.id
                WHERE c.is_active = 1 
                AND c.archived = 0
                AND c.id NOT IN ($placeholders)
                ORDER BY c.course_code
            ", $enrolledIds);
        } else {
            $availableCourses = $this->db->query("
                SELECT c.*, 
                       CONCAT(l.first_name, ' ', l.last_name) as lecturer_name,
                       (SELECT COUNT(*) FROM enrollments WHERE course_id = c.id AND status = 'active') as enrolled_students
                FROM courses c
                LEFT JOIN lecturers l ON c.lecturer_id = l.id
                WHERE c.is_active = 1 AND c.archived = 0
                ORDER BY c.course_code
            ");
        }
        
        // Get student's enrolled courses
        $myCourses = $this->db->query("
            SELECT c.*, 
                   CONCAT(l.first_name, ' ', l.last_name) as lecturer_name,
                   e.enrollment_date,
                   (SELECT COUNT(*) FROM enrollments WHERE course_id = c.id AND status = 'active') as enrolled_students
            FROM enrollments e
            INNER JOIN courses c ON e.course_id = c.id
            LEFT JOIN lecturers l ON c.lecturer_id = l.id
            WHERE e.student_id = ? AND e.status = 'active'
            ORDER BY c.course_code
        ", [$studentId]);
        
        $this->render('student/enrollment/index', [
            'title' => 'Course Enrollment',
            'available_courses' => $availableCourses,
            'my_courses' => $myCourses
        ]);
    }
    
    /**
     * Enroll in a course
     */
    public function enroll()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/student/enrollment');
            return;
        }
        
        $studentId = $_SESSION['profile_id'] ?? null;
        $courseId = $_POST['course_id'] ?? null;
        
        if (!$studentId || !$courseId) {
            if ($this->isAjax()) {
                $this->jsonResponse(['success' => false, 'message' => 'Invalid request']);
            } else {
                $this->setFlashMessage('Invalid request', 'error');
                $this->redirect('/student/enrollment');
            }
            return;
        }
        
        // Check if already enrolled
        $existing = $this->db->queryOne("
            SELECT id FROM enrollments 
            WHERE student_id = ? AND course_id = ? AND status = 'active'
        ", [$studentId, $courseId]);
        
        if ($existing) {
            if ($this->isAjax()) {
                $this->jsonResponse(['success' => false, 'message' => 'Already enrolled in this course']);
            } else {
                $this->setFlashMessage('You are already enrolled in this course', 'warning');
                $this->redirect('/student/enrollment');
            }
            return;
        }
        
        // Check if course exists and is active
        $course = $this->db->queryOne("
            SELECT * FROM courses 
            WHERE id = ? AND is_active = 1 AND archived = 0
        ", [$courseId]);
        
        if (!$course) {
            if ($this->isAjax()) {
                $this->jsonResponse(['success' => false, 'message' => 'Course not found or not available']);
            } else {
                $this->setFlashMessage('Course not found or not available', 'error');
                $this->redirect('/student/enrollment');
            }
            return;
        }
        
        // Enroll student
        try {
            $this->db->execute("
                INSERT INTO enrollments (student_id, course_id, enrollment_date, status)
                VALUES (?, ?, CURDATE(), 'active')
            ", [$studentId, $courseId]);
            
            if ($this->isAjax()) {
                $this->jsonResponse([
                    'success' => true, 
                    'message' => 'Successfully enrolled in ' . $course['course_code']
                ]);
            } else {
                $this->setFlashMessage('Successfully enrolled in ' . $course['course_code'], 'success');
                $this->redirect('/student/enrollment');
            }
        } catch (Exception $e) {
            error_log('Enrollment error: ' . $e->getMessage());
            
            if ($this->isAjax()) {
                $this->jsonResponse(['success' => false, 'message' => 'Failed to enroll']);
            } else {
                $this->setFlashMessage('Failed to enroll in course', 'error');
                $this->redirect('/student/enrollment');
            }
        }
    }
    
    /**
     * Unenroll from a course
     */
    public function unenroll()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/student/enrollment');
            return;
        }
        
        $studentId = $_SESSION['profile_id'] ?? null;
        $courseId = $_POST['course_id'] ?? null;
        
        if (!$studentId || !$courseId) {
            if ($this->isAjax()) {
                $this->jsonResponse(['success' => false, 'message' => 'Invalid request']);
            } else {
                $this->setFlashMessage('Invalid request', 'error');
                $this->redirect('/student/enrollment');
            }
            return;
        }
        
        // Check if has attendance records
        $hasAttendance = $this->db->queryOne("
            SELECT COUNT(*) as count
            FROM attendance_records ar
            INNER JOIN attendance_sessions ats ON ar.session_id = ats.id
            WHERE ar.student_id = ? AND ats.course_id = ?
        ", [$studentId, $courseId]);
        
        if ($hasAttendance['count'] > 0) {
            if ($this->isAjax()) {
                $this->jsonResponse([
                    'success' => false, 
                    'message' => 'Cannot unenroll: You have attendance records in this course'
                ]);
            } else {
                $this->setFlashMessage('Cannot unenroll: You have attendance records in this course', 'warning');
                $this->redirect('/student/enrollment');
            }
            return;
        }
        
        // Unenroll (delete enrollment)
        try {
            $this->db->execute("
                DELETE FROM enrollments 
                WHERE student_id = ? AND course_id = ?
            ", [$studentId, $courseId]);
            
            if ($this->isAjax()) {
                $this->jsonResponse(['success' => true, 'message' => 'Successfully unenrolled from course']);
            } else {
                $this->setFlashMessage('Successfully unenrolled from course', 'success');
                $this->redirect('/student/enrollment');
            }
        } catch (Exception $e) {
            error_log('Unenroll error: ' . $e->getMessage());
            
            if ($this->isAjax()) {
                $this->jsonResponse(['success' => false, 'message' => 'Failed to unenroll']);
            } else {
                $this->setFlashMessage('Failed to unenroll from course', 'error');
                $this->redirect('/student/enrollment');
            }
        }
    }
}
