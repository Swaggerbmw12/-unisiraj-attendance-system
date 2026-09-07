<?php

require_once dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'Student.php';
require_once dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'AttendanceRecord.php';

/**
 * Student Dashboard Controller
 * UniSIRAJ Automated Attendance System
 * 
 * Handles student dashboard and overview
 */
class StudentDashboardController extends BaseController
{
    private $studentModel;
    private $recordModel;
    
    public function __construct()
    {
        parent::__construct();
        $this->studentModel = new Student();
        $this->recordModel = new AttendanceRecord();
    }
    
    /**
     * Display student dashboard
     */
    public function index()
    {
        $this->requireAuth(['student']);
        
        // Get student profile ID from session
        $studentId = $_SESSION['profile_id'] ?? null;
        
        if (!$studentId) {
            $this->setFlashMessage('Student profile not found', 'error');
            $this->redirect('/login');
            return;
        }
        
        // Get dashboard data
        $stats = $this->getDashboardStats($studentId);
        
        // Display dashboard
        $this->render('student/dashboard', [
            'title' => 'Student Dashboard',
            'stats' => $stats,
            'additionalCss' => '<link rel="stylesheet" href="' . asset('css/student-dashboard.css') . '">'
        ]);
    }
    
    /**
     * Display course-specific dashboard for student
     */
    public function courseDashboard()
    {
        $this->requireAuth(['student']);
        
        $studentId = $_SESSION['profile_id'] ?? null;
        $courseId = $_GET['id'] ?? null;
        
        if (!$studentId) {
            $this->setFlashMessage('Student profile not found', 'error');
            $this->redirect('/login');
            return;
        }
        
        if (!$courseId) {
            $this->setFlashMessage('Course ID is required', 'error');
            $this->redirect('/student/dashboard');
            return;
        }
        
        // Verify student is enrolled in this course
        $enrollment = $this->db->queryOne("
            SELECT e.*, c.course_code, c.course_name, c.semester, c.academic_year,
                   CONCAT(l.first_name, ' ', l.last_name) as lecturer_name,
                   u.email as lecturer_email
            FROM enrollments e
            INNER JOIN courses c ON e.course_id = c.id
            LEFT JOIN lecturers l ON c.lecturer_id = l.id
            LEFT JOIN users u ON l.user_id = u.id
            WHERE e.student_id = ? AND e.course_id = ? AND e.status = 'active'
        ", [$studentId, $courseId]);
        
        if (!$enrollment) {
            $this->setFlashMessage('You are not enrolled in this course or enrollment is not active', 'error');
            $this->redirect('/student/dashboard');
            return;
        }
        
        // Get course attendance statistics
        $stats = $this->getCourseStats($studentId, $courseId);
        
        // Display course dashboard
        $this->render('student/course-dashboard', [
            'title' => $enrollment['course_code'] . ' - Course Dashboard',
            'course' => $enrollment,
            'stats' => $stats,
            'additionalCss' => '<link rel="stylesheet" href="' . asset('css/student-dashboard.css') . '?v=' . time() . '">'
        ]);
    }
    
    /**
     * Get detailed statistics for a specific course
     */
    private function getCourseStats($studentId, $courseId)
    {
        try {
            // Get all sessions for this course
            $allSessions = $this->db->query("
                SELECT 
                    ats.id,
                    ats.session_name,
                    ats.session_date,
                    ats.start_time,
                    ats.end_time,
                    ats.expires_at,
                    ats.is_active,
                    ar.id as attendance_id,
                    ar.attendance_time,
                    CASE 
                        WHEN ar.id IS NOT NULL THEN 'present'
                        WHEN ats.is_active = 1 AND ats.expires_at > NOW() THEN 'active'
                        WHEN ats.expires_at < NOW() THEN 'missed'
                        ELSE 'upcoming'
                    END as status
                FROM attendance_sessions ats
                LEFT JOIN attendance_records ar ON ats.id = ar.session_id AND ar.student_id = ?
                WHERE ats.course_id = ?
                ORDER BY ats.session_date DESC, ats.start_time DESC
            ", [$studentId, $courseId]);
            
            // Calculate statistics
            $totalSessions = count($allSessions);
            $attended = 0;
            $missed = 0;
            $active = 0;
            $upcoming = 0;
            
            $presentSessions = [];
            $missedSessions = [];
            $activeSessions = [];
            
            foreach ($allSessions as $session) {
                switch ($session['status']) {
                    case 'present':
                        $attended++;
                        $presentSessions[] = $session;
                        break;
                    case 'missed':
                        $missed++;
                        $missedSessions[] = $session;
                        break;
                    case 'active':
                        $active++;
                        $activeSessions[] = $session;
                        break;
                    case 'upcoming':
                        $upcoming++;
                        break;
                }
            }
            
            $attendanceRate = $totalSessions > 0 ? round(($attended / $totalSessions) * 100, 2) : 0;
            
            // Get monthly attendance trend
            $monthlyTrend = $this->db->query("
                SELECT 
                    DATE_FORMAT(ats.session_date, '%Y-%m') as month,
                    COUNT(DISTINCT ats.id) as total_sessions,
                    COUNT(DISTINCT ar.id) as attended_sessions
                FROM attendance_sessions ats
                LEFT JOIN attendance_records ar ON ats.id = ar.session_id AND ar.student_id = ?
                WHERE ats.course_id = ?
                GROUP BY DATE_FORMAT(ats.session_date, '%Y-%m')
                ORDER BY month DESC
                LIMIT 6
            ", [$studentId, $courseId]);
            
            // Get weekly attendance pattern
            $weeklyPattern = $this->db->query("
                SELECT 
                    DAYNAME(ats.session_date) as day_name,
                    DAYOFWEEK(ats.session_date) as day_number,
                    COUNT(DISTINCT ats.id) as total_sessions,
                    COUNT(DISTINCT ar.id) as attended_sessions
                FROM attendance_sessions ats
                LEFT JOIN attendance_records ar ON ats.id = ar.session_id AND ar.student_id = ?
                WHERE ats.course_id = ?
                GROUP BY DAYNAME(ats.session_date), DAYOFWEEK(ats.session_date)
                ORDER BY day_number
            ", [$studentId, $courseId]);
            
            return [
                'total_sessions' => $totalSessions,
                'attended' => $attended,
                'missed' => $missed,
                'active' => $active,
                'upcoming' => $upcoming,
                'attendance_rate' => $attendanceRate,
                'all_sessions' => $allSessions,
                'present_sessions' => $presentSessions,
                'missed_sessions' => $missedSessions,
                'active_sessions' => $activeSessions,
                'monthly_trend' => $monthlyTrend,
                'weekly_pattern' => $weeklyPattern
            ];
            
        } catch (Exception $e) {
            error_log('Course stats error: ' . $e->getMessage());
            return [
                'total_sessions' => 0,
                'attended' => 0,
                'missed' => 0,
                'active' => 0,
                'upcoming' => 0,
                'attendance_rate' => 0,
                'all_sessions' => [],
                'present_sessions' => [],
                'missed_sessions' => [],
                'active_sessions' => [],
                'monthly_trend' => [],
                'weekly_pattern' => []
            ];
        }
    }
    
    /**
     * Get student dashboard statistics
     */
    private function getDashboardStats($studentId)
    {
        try {
            // Get attendance statistics per course with lecturer info
            $courseStats = $this->db->query("
                SELECT 
                    c.id as course_id,
                    c.course_code,
                    c.course_name,
                    c.semester,
                    c.academic_year,
                    CONCAT(l.first_name, ' ', l.last_name) as lecturer_name,
                    COUNT(DISTINCT ats.id) as total_sessions,
                    COUNT(DISTINCT ar.id) as attended_sessions,
                    COALESCE(ROUND((COUNT(DISTINCT ar.id) / NULLIF(COUNT(DISTINCT ats.id), 0) * 100), 2), 0) as percentage
                FROM enrollments e
                INNER JOIN courses c ON e.course_id = c.id
                LEFT JOIN lecturers l ON c.lecturer_id = l.id
                LEFT JOIN attendance_sessions ats ON c.id = ats.course_id
                LEFT JOIN attendance_records ar ON ats.id = ar.session_id AND ar.student_id = ?
                WHERE e.student_id = ? AND e.status = 'active'
                GROUP BY c.id, c.course_code, c.course_name, c.semester, c.academic_year, l.first_name, l.last_name
                ORDER BY c.course_code
            ", [$studentId, $studentId]);
            
            // Get recent attendance history
            $recentAttendance = $this->db->query("
                SELECT 
                    ar.attendance_time,
                    ats.session_name,
                    ats.session_date,
                    c.course_code,
                    c.course_name
                FROM attendance_records ar
                INNER JOIN attendance_sessions ats ON ar.session_id = ats.id
                INNER JOIN courses c ON ats.course_id = c.id
                WHERE ar.student_id = ?
                ORDER BY ar.attendance_time DESC
                LIMIT 10
            ", [$studentId]);
            
            // Get active sessions available to scan
            $activeSessions = $this->db->query("
                SELECT 
                    ats.id,
                    ats.session_name,
                    ats.session_date,
                    ats.expires_at,
                    c.course_code,
                    c.course_name,
                    CONCAT(l.first_name, ' ', l.last_name) as lecturer_name
                FROM attendance_sessions ats
                INNER JOIN courses c ON ats.course_id = c.id
                INNER JOIN enrollments e ON c.id = e.course_id
                LEFT JOIN lecturers l ON c.lecturer_id = l.id
                WHERE e.student_id = ?
                AND ats.is_active = 1
                AND ats.expires_at > NOW()
                AND e.status = 'active'
                AND NOT EXISTS (
                    SELECT 1 FROM attendance_records 
                    WHERE session_id = ats.id AND student_id = ?
                )
                ORDER BY ats.session_date DESC, ats.expires_at ASC
            ", [$studentId, $studentId]);
            
            // Get weekly attendance trend (last 30 days)
            $weeklyTrend = $this->db->query("
                SELECT 
                    DATE(ar.attendance_time) as date,
                    COUNT(*) as attendance_count
                FROM attendance_records ar
                WHERE ar.student_id = ?
                AND ar.attendance_time >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
                GROUP BY DATE(ar.attendance_time)
                ORDER BY date
            ", [$studentId]);
            
            // Get student profile info
            $studentInfo = $this->db->queryOne("
                SELECT s.*, u.email
                FROM students s
                INNER JOIN users u ON s.user_id = u.id
                WHERE s.id = ?
            ", [$studentId]);
            
            // Calculate totals
            $coursesCount = count($courseStats);
            $totalSessions = 0;
            $totalAttended = 0;
            $missedSessions = 0;
            
            foreach ($courseStats as &$course) {
                $totalSessions += $course['total_sessions'];
                $totalAttended += $course['attended_sessions'];
                $missedSessions += ($course['total_sessions'] - $course['attended_sessions']);
                
                // Ensure percentage is numeric
                $course['attendance_percentage'] = (float)($course['percentage'] ?? 0);
            }
            
            $overallPercentage = $totalSessions > 0 ? round(($totalAttended / $totalSessions) * 100, 2) : 0;
            
            return [
                'courses_count' => $coursesCount,
                'attendance_count' => $totalAttended,
                'missed_count' => $missedSessions,
                'overall_percentage' => $overallPercentage,
                'total_sessions' => $totalSessions,
                'courses' => $courseStats,
                'recent_attendance' => $recentAttendance,
                'active_sessions' => $activeSessions,
                'weekly_trend' => $weeklyTrend,
                'student_info' => $studentInfo
            ];
            
        } catch (Exception $e) {
            error_log('Student dashboard stats error: ' . $e->getMessage());
            return [
                'courses_count' => 0,
                'attendance_count' => 0,
                'missed_count' => 0,
                'overall_percentage' => 0,
                'total_sessions' => 0,
                'courses' => [],
                'recent_attendance' => [],
                'active_sessions' => [],
                'weekly_trend' => [],
                'student_info' => null
            ];
        }
    }
}
