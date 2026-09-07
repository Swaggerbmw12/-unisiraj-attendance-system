<?php
/**
 * Lecturer Dashboard Controller
 * UniSIRAJ Automated Attendance System
 * 
 * Handles lecturer dashboard and overview
 */

class LecturerDashboardController extends BaseController {
    
    /**
     * Display lecturer dashboard
     */
    public function index()
    {
        $this->requireAuth(['lecturer']);
        
        // Get lecturer profile ID from session
        $lecturerId = $_SESSION['profile_id'] ?? null;
        
        if (!$lecturerId) {
            $this->setFlashMessage('Lecturer profile not found', 'error');
            $this->redirect('/login');
            return;
        }
        
        // Get dashboard statistics
        $stats = $this->getDashboardStats($lecturerId);
        
        // Display dashboard
        $this->render('lecturer/dashboard', [
            'title' => 'Lecturer Dashboard',
            'stats' => $stats,
            'additionalCss' => '<link rel="stylesheet" href="' . asset('css/lecturer-dashboard.css') . '">'
        ]);
    }
    
    /**
     * Get lecturer dashboard statistics
     * @param int $lecturerId Lecturer ID
     * @return array Statistics data
     */
    private function getDashboardStats($lecturerId) {
        try {
            // Count assigned courses
            $coursesCount = $this->db->queryOne("
                SELECT COUNT(*) as count FROM courses 
                WHERE lecturer_id = ? AND is_active = 1 AND archived = 0
            ", [$lecturerId])['count'];
            
            // Count total sessions created
            $sessionsCount = $this->db->queryOne("
                SELECT COUNT(*) as count FROM attendance_sessions 
                WHERE lecturer_id = ?
            ", [$lecturerId])['count'];
            
            // Count active sessions today
            $todayActiveSessions = $this->db->queryOne("
                SELECT COUNT(*) as count FROM attendance_sessions 
                WHERE lecturer_id = ? 
                AND DATE(session_date) = CURDATE()
                AND is_active = 1
            ", [$lecturerId])['count'];
            
            // Count total enrolled students across all courses
            $totalStudents = $this->db->queryOne("
                SELECT COUNT(DISTINCT e.student_id) as count
                FROM enrollments e
                INNER JOIN courses c ON e.course_id = c.id
                WHERE c.lecturer_id = ? AND e.status = 'active'
            ", [$lecturerId])['count'];
            
            // Get average attendance rate
            $avgAttendance = $this->db->queryOne("
                SELECT 
                    COALESCE(AVG(attendance_rate), 0) as avg_rate
                FROM (
                    SELECT 
                        ats.id,
                        (COUNT(DISTINCT ar.student_id) / NULLIF(
                            (SELECT COUNT(*) FROM enrollments WHERE course_id = ats.course_id AND status = 'active'), 
                        0)) * 100 as attendance_rate
                    FROM attendance_sessions ats
                    LEFT JOIN attendance_records ar ON ats.id = ar.session_id
                    WHERE ats.lecturer_id = ?
                    GROUP BY ats.id
                ) as rates
            ", [$lecturerId])['avg_rate'];
            
            // Get assigned courses with detailed stats
            $courses = $this->db->query("
                SELECT c.id, c.course_code, c.course_name, c.semester, c.academic_year, c.archived,
                       (SELECT COUNT(*) FROM enrollments WHERE course_id = c.id AND status = 'active') as enrolled_students,
                       (SELECT COUNT(*) FROM attendance_sessions WHERE course_id = c.id) as total_sessions,
                       (SELECT COUNT(*) FROM attendance_sessions WHERE course_id = c.id AND is_active = 1) as active_sessions
                FROM courses c
                WHERE c.lecturer_id = ? AND c.is_active = 1 AND c.archived = 0
                ORDER BY c.course_code
            ", [$lecturerId]);
            
            // Get recent sessions
            $recentSessions = $this->db->query("
                SELECT ats.id, ats.session_name, ats.session_date, ats.start_time,
                       ats.is_active, c.course_code, c.course_name, c.id as course_id,
                       (SELECT COUNT(*) FROM attendance_records WHERE session_id = ats.id) as attendees,
                       (SELECT COUNT(*) FROM enrollments WHERE course_id = c.id AND status = 'active') as total_students
                FROM attendance_sessions ats
                INNER JOIN courses c ON ats.course_id = c.id
                WHERE ats.lecturer_id = ?
                ORDER BY ats.created_at DESC
                LIMIT 5
            ", [$lecturerId]);
            
            // Get upcoming sessions (next 7 days)
            $upcomingSessions = $this->db->query("
                SELECT ats.id, ats.session_name, ats.session_date, ats.start_time,
                       c.course_code, c.course_name
                FROM attendance_sessions ats
                INNER JOIN courses c ON ats.course_id = c.id
                WHERE ats.lecturer_id = ?
                AND ats.session_date BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 7 DAY)
                AND ats.is_active = 1
                ORDER BY ats.session_date, ats.start_time
            ", [$lecturerId]);
            
            // Get weekly attendance trend (last 7 days)
            $weeklyTrend = $this->db->query("
                SELECT 
                    DATE(ats.session_date) as date,
                    COUNT(DISTINCT ats.id) as sessions,
                    COUNT(DISTINCT ar.student_id) as total_attendance
                FROM attendance_sessions ats
                LEFT JOIN attendance_records ar ON ats.id = ar.session_id
                WHERE ats.lecturer_id = ?
                AND ats.session_date >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
                GROUP BY DATE(ats.session_date)
                ORDER BY date
            ", [$lecturerId]);
            
            return [
                'courses_count' => $coursesCount,
                'sessions_count' => $sessionsCount,
                'today_active_sessions' => $todayActiveSessions,
                'total_students' => $totalStudents,
                'avg_attendance' => round($avgAttendance, 1),
                'courses' => $courses,
                'recent_sessions' => $recentSessions,
                'upcoming_sessions' => $upcomingSessions,
                'weekly_trend' => $weeklyTrend
            ];
            
        } catch (Exception $e) {
            error_log('Lecturer dashboard stats error: ' . $e->getMessage());
            return [
                'courses_count' => 0,
                'sessions_count' => 0,
                'today_active_sessions' => 0,
                'total_students' => 0,
                'avg_attendance' => 0,
                'courses' => [],
                'recent_sessions' => [],
                'upcoming_sessions' => [],
                'weekly_trend' => []
            ];
        }
    }
}
