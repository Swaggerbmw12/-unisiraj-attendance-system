<?php

require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'Student.php';
require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'Lecturer.php';
require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'Course.php';
require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'AttendanceSession.php';

/**
 * Admin Dashboard Controller
 * UniSIRAJ Automated Attendance System
 * 
 * Handles admin dashboard and overview
 */
class AdminDashboardController extends BaseController
{
    /**
     * Display admin dashboard
     */
    public function index()
    {
        // Require admin role
        $this->requireAuth(['admin']);
        
        // Get dashboard statistics
        $stats = $this->getDashboardStats();
        
        // Display dashboard
        $this->render('admin/dashboard', [
            'title' => 'Admin Dashboard',
            'stats' => $stats
        ], 'layouts/admin-sidebar');
    }
    
    /**
     * Get dashboard statistics
     * @return array Statistics data
     */
    private function getDashboardStats()
    {
        try {
            $db = Database::getInstance()->getConnection();
            
            // Count total students
            $stmt = $db->query("SELECT COUNT(*) as count FROM students");
            $studentsCount = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
            
            // Count total lecturers
            $stmt = $db->query("SELECT COUNT(*) as count FROM lecturers");
            $lecturersCount = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
            
            // Count total courses
            $stmt = $db->query("SELECT COUNT(*) as count FROM courses WHERE is_active = 1");
            $coursesCount = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
            
            // Count total attendance sessions today
            $stmt = $db->query("SELECT COUNT(*) as count FROM attendance_sessions 
                               WHERE DATE(session_date) = CURDATE()");
            $todaySessionsCount = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
            
            // Get recent attendance sessions
            $stmt = $db->query("
                SELECT ats.id, ats.session_name, ats.session_date, ats.start_time,
                       ats.is_active, c.course_code, c.course_name,
                       CONCAT(l.first_name, ' ', l.last_name) as lecturer_name,
                       (SELECT COUNT(*) FROM attendance_records WHERE session_id = ats.id) as attendees
                FROM attendance_sessions ats
                INNER JOIN courses c ON ats.course_id = c.id
                INNER JOIN lecturers l ON ats.lecturer_id = l.id
                ORDER BY ats.created_at DESC
                LIMIT 5
            ");
            $recentSessions = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Get recent registrations
            $stmt = $db->query("
                SELECT s.id, s.student_id, s.first_name, s.last_name, s.program, s.created_at
                FROM students s
                ORDER BY s.created_at DESC
                LIMIT 5
            ");
            $recentStudents = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            return [
                'students_count' => $studentsCount,
                'lecturers_count' => $lecturersCount,
                'courses_count' => $coursesCount,
                'today_sessions_count' => $todaySessionsCount,
                'recent_sessions' => $recentSessions,
                'recent_students' => $recentStudents
            ];
            
        } catch (Exception $e) {
            error_log('Dashboard stats error: ' . $e->getMessage());
            return [
                'students_count' => 0,
                'lecturers_count' => 0,
                'courses_count' => 0,
                'today_sessions_count' => 0,
                'recent_sessions' => [],
                'recent_students' => []
            ];
        }
    }
}
