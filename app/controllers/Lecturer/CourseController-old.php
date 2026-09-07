<?php
/**
 * Lecturer Course Controller
 * UniSIRAJ Automated Attendance System
 * 
 * Handles course management for lecturers
 */

class LecturerCourseController extends BaseController {
    
    /**
     * Display course-specific dashboard
     */
    public function dashboard() {
        $this->requireAuth(['lecturer']);
        
        $lecturerId = $_SESSION['profile_id'] ?? null;
        $courseId = $_GET['id'] ?? null;
        
        if (!$lecturerId || !$courseId) {
            $this->setFlashMessage('Invalid request', 'error');
            $this->redirect('/lecturer/dashboard');
            return;
        }
        
        // Verify lecturer owns this course
        $course = $this->db->queryOne("
            SELECT c.*, 
                   (SELECT COUNT(*) FROM enrollments WHERE course_id = c.id AND status = 'active') as enrolled_students
            FROM courses c
            WHERE c.id = ? AND c.lecturer_id = ?
        ", [$courseId, $lecturerId]);
        
        if (!$course) {
            $this->setFlashMessage('Course not found or access denied', 'error');
            $this->redirect('/lecturer/dashboard');
            return;
        }
        
        // Get course statistics
        $stats = $this->getCourseStats($courseId, $lecturerId);
        
        $this->render('lecturer/course-dashboard', [
            'title' => $course['course_code'] . ' - Dashboard',
            'course' => $course,
            'stats' => $stats,
            'additionalCss' => '<link rel="stylesheet" href="' . asset('css/lecturer-dashboard.css') . '">'
        ]);
    }
    
    /**
     * Display add course form
     */
    public function create() {
        $this->requireAuth(['lecturer']);
        
        $this->render('lecturer/course-create', [
            'title' => 'Add New Course'
        ]);
    }
    
    /**
     * Store new course
     */
    public function store() {
        $this->requireAuth(['lecturer']);
        
        $lecturerId = $_SESSION['profile_id'] ?? null;
        
        if (!$lecturerId) {
            $this->jsonResponse(['success' => false, 'message' => 'Lecturer profile not found']);
            return;
        }
        
        // Validate input
        $courseCode = trim($_POST['course_code'] ?? '');
        $courseName = trim($_POST['course_name'] ?? '');
        $semester = trim($_POST['semester'] ?? '');
        $academicYear = trim($_POST['academic_year'] ?? '');
        $credits = intval($_POST['credits'] ?? 0);
        $description = trim($_POST['description'] ?? '');
        
        $errors = [];
        
        if (empty($courseCode)) {
            $errors[] = 'Course code is required';
        }
        
        if (empty($courseName)) {
            $errors[] = 'Course name is required';
        }
        
        if (empty($semester)) {
            $errors[] = 'Semester is required';
        }
        
        if (empty($academicYear)) {
            $errors[] = 'Academic year is required';
        }
        
        // Check if course code already exists
        $existing = $this->db->queryOne("SELECT id FROM courses WHERE course_code = ?", [$courseCode]);
        if ($existing) {
            $errors[] = 'Course code already exists';
        }
        
        if (!empty($errors)) {
            if ($this->isAjax()) {
                $this->jsonResponse(['success' => false, 'errors' => $errors]);
            } else {
                $this->setFlashMessage(implode(', ', $errors), 'error');
                $this->redirect('/lecturer/courses/create');
            }
            return;
        }
        
        // Insert course
        try {
            $this->db->execute("
                INSERT INTO courses (course_code, course_name, lecturer_id, semester, academic_year, credits, description, is_active)
                VALUES (?, ?, ?, ?, ?, ?, ?, 1)
            ", [$courseCode, $courseName, $lecturerId, $semester, $academicYear, $credits, $description]);
            
            $courseId = $this->db->lastInsertId();
            
            if ($this->isAjax()) {
                $this->jsonResponse([
                    'success' => true,
                    'message' => 'Course added successfully',
                    'course_id' => $courseId
                ]);
            } else {
                $this->setFlashMessage('Course added successfully', 'success');
                $this->redirect('/lecturer/courses/dashboard?id=' . $courseId);
            }
            
        } catch (Exception $e) {
            error_log('Add course error: ' . $e->getMessage());
            
            if ($this->isAjax()) {
                $this->jsonResponse(['success' => false, 'message' => 'Failed to add course']);
            } else {
                $this->setFlashMessage('Failed to add course', 'error');
                $this->redirect('/lecturer/courses/create');
            }
        }
    }
    
    /**
     * Get course statistics
     */
    private function getCourseStats($courseId, $lecturerId) {
        try {
            // Get enrolled students
            $enrolledStudents = $this->db->query("
                SELECT s.*, u.email, e.enrollment_date, e.status
                FROM enrollments e
                INNER JOIN students s ON e.student_id = s.id
                INNER JOIN users u ON s.user_id = u.id
                WHERE e.course_id = ?
                ORDER BY s.first_name, s.last_name
            ", [$courseId]);
            
            // Get sessions
            $sessions = $this->db->query("
                SELECT ats.*,
                       (SELECT COUNT(*) FROM attendance_records WHERE session_id = ats.id) as attendees,
                       (SELECT COUNT(*) FROM enrollments WHERE course_id = ? AND status = 'active') as total_students
                FROM attendance_sessions ats
                WHERE ats.course_id = ?
                ORDER BY ats.session_date DESC, ats.start_time DESC
            ", [$courseId, $courseId]);
            
            // Get active sessions
            $activeSessions = $this->db->query("
                SELECT ats.*,
                       (SELECT COUNT(*) FROM attendance_records WHERE session_id = ats.id) as attendees,
                       (SELECT COUNT(*) FROM enrollments WHERE course_id = ? AND status = 'active') as total_students
                FROM attendance_sessions ats
                WHERE ats.course_id = ? AND ats.is_active = 1
                ORDER BY ats.expires_at ASC
            ", [$courseId, $courseId]);
            
            // Get attendance statistics
            $attendanceStats = $this->db->query("
                SELECT 
                    s.id as student_id,
                    CONCAT(s.first_name, ' ', s.last_name) as student_name,
                    s.student_id as student_number,
                    COUNT(DISTINCT ats.id) as total_sessions,
                    COUNT(DISTINCT ar.id) as attended_sessions,
                    COALESCE(ROUND((COUNT(DISTINCT ar.id) / NULLIF(COUNT(DISTINCT ats.id), 0) * 100), 2), 0) as percentage
                FROM students s
                INNER JOIN enrollments e ON s.id = e.student_id
                LEFT JOIN attendance_sessions ats ON e.course_id = ats.course_id
                LEFT JOIN attendance_records ar ON ats.id = ar.session_id AND ar.student_id = s.id
                WHERE e.course_id = ? AND e.status = 'active'
                GROUP BY s.id, s.first_name, s.last_name, s.student_id
                ORDER BY student_name
            ", [$courseId]);
            
            // Calculate totals
            $totalSessions = count($sessions);
            $totalActive = count($activeSessions);
            $totalEnrolled = count($enrolledStudents);
            
            $avgAttendance = 0;
            if (!empty($attendanceStats)) {
                $sum = array_sum(array_column($attendanceStats, 'percentage'));
                $avgAttendance = round($sum / count($attendanceStats), 2);
            }
            
            return [
                'total_sessions' => $totalSessions,
                'active_sessions' => $totalActive,
                'total_enrolled' => $totalEnrolled,
                'avg_attendance' => $avgAttendance,
                'enrolled_students' => $enrolledStudents,
                'sessions' => $sessions,
                'active_sessions_list' => $activeSessions,
                'attendance_stats' => $attendanceStats
            ];
            
        } catch (Exception $e) {
            error_log('Get course stats error: ' . $e->getMessage());
            return [
                'total_sessions' => 0,
                'active_sessions' => 0,
                'total_enrolled' => 0,
                'avg_attendance' => 0,
                'enrolled_students' => [],
                'sessions' => [],
                'active_sessions_list' => [],
                'attendance_stats' => []
            ];
        }
    }
}
