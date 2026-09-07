<?php

require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'AttendanceSession.php';
require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'AttendanceRecord.php';
require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'Course.php';
require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'Lecturer.php';
require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'Student.php';

class AnalyticsController extends BaseController
{
    private $sessionModel;
    private $recordModel;
    private $courseModel;
    private $lecturerModel;
    private $studentModel;
    
    public function __construct()
    {
        parent::__construct();
        $this->requireAuth(['lecturer', 'admin']);
        $this->sessionModel = new AttendanceSession();
        $this->recordModel = new AttendanceRecord();
        $this->courseModel = new Course();
        $this->lecturerModel = new Lecturer();
        $this->studentModel = new Student();
    }
    
    /**
     * Analytics dashboard - main page
     */
    public function index()
    {
        $userRole = $_SESSION['role'];
        $profileId = $_SESSION['profile_id'];
        
        // Get available courses based on role
        if ($userRole === 'lecturer') {
            $courses = $this->lecturerModel->getAssignedCourses($profileId);
        } else {
            $courses = $this->courseModel->getAll()['data'];
        }
        
        // Get selected course from query or use first available
        $selectedCourseId = $_GET['course_id'] ?? ($courses[0]['id'] ?? null);
        
        $analyticsData = null;
        if ($selectedCourseId) {
            $analyticsData = $this->generateAnalytics($selectedCourseId);
        }
        
        $this->render('analytics/index', [
            'courses' => $courses,
            'selectedCourseId' => $selectedCourseId,
            'analytics' => $analyticsData
        ]);
    }
    
    /**
     * Get analytics data as JSON (for AJAX updates)
     */
    public function data()
    {
        header('Content-Type: application/json');
        
        $courseId = $_GET['course_id'] ?? null;
        
        if (!$courseId) {
            echo json_encode(['error' => 'Course ID required']);
            exit;
        }
        
        // Check authorization for lecturer
        if ($_SESSION['role'] === 'lecturer') {
            $course = $this->courseModel->getById($courseId);
            if (!$course || $course['lecturer_id'] != $_SESSION['profile_id']) {
                echo json_encode(['error' => 'Unauthorized']);
                exit;
            }
        }
        
        $analyticsData = $this->generateAnalytics($courseId);
        echo json_encode($analyticsData);
        exit;
    }
    
    /**
     * Generate comprehensive analytics for a course
     */
    private function generateAnalytics($courseId)
    {
        $course = $this->courseModel->getById($courseId);
        
        // 1. Overall Statistics
        $overallStats = $this->getOverallStatistics($courseId);
        
        // 2. Attendance Trends (last 8 weeks)
        $attendanceTrends = $this->getAttendanceTrends($courseId);
        
        // 3. At-Risk Students (ML-inspired scoring)
        $atRiskStudents = $this->identifyAtRiskStudents($courseId);
        
        // 4. Performance Distribution
        $performanceDistribution = $this->getPerformanceDistribution($courseId);
        
        // 5. Session Analysis
        $sessionAnalysis = $this->getSessionAnalysis($courseId);
        
        // 6. Engagement Metrics
        $engagementMetrics = $this->calculateEngagementMetrics($courseId);
        
        // 7. Predictive Insights
        $insights = $this->generateInsights($courseId, $overallStats, $atRiskStudents, $attendanceTrends);
        
        return [
            'course' => $course,
            'overall_stats' => $overallStats,
            'trends' => $attendanceTrends,
            'at_risk_students' => $atRiskStudents,
            'distribution' => $performanceDistribution,
            'session_analysis' => $sessionAnalysis,
            'engagement' => $engagementMetrics,
            'insights' => $insights
        ];
    }
    
    /**
     * Get overall statistics
     */
    private function getOverallStatistics($courseId)
    {
        $sql = "SELECT 
                    COUNT(DISTINCT ats.id) as total_sessions,
                    COUNT(DISTINCT e.student_id) as total_enrolled,
                    COUNT(DISTINCT ar.id) as total_attendance_records,
                    COUNT(DISTINCT ar.student_id) as active_students,
                    ROUND(AVG(session_attendance.attendance_rate), 2) as avg_attendance_rate
                FROM courses c
                LEFT JOIN attendance_sessions ats ON c.id = ats.course_id
                LEFT JOIN enrollments e ON c.id = e.course_id AND e.status = 'active'
                LEFT JOIN attendance_records ar ON ats.id = ar.session_id
                LEFT JOIN (
                    SELECT 
                        ats2.id,
                        COUNT(ar2.id) / (SELECT COUNT(*) FROM enrollments WHERE course_id = ? AND status = 'active') * 100 as attendance_rate
                    FROM attendance_sessions ats2
                    LEFT JOIN attendance_records ar2 ON ats2.id = ar2.session_id
                    WHERE ats2.course_id = ?
                    GROUP BY ats2.id
                ) as session_attendance ON ats.id = session_attendance.id
                WHERE c.id = ?";
        
        $stmt = $this->db->getConnection()->prepare($sql);
        $stmt->execute([$courseId, $courseId, $courseId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    /**
     * Get attendance trends over time
     */
    private function getAttendanceTrends($courseId)
    {
        $sql = "SELECT 
                    ats.session_date,
                    ats.session_name,
                    COUNT(ar.id) as attendees,
                    (SELECT COUNT(*) FROM enrollments WHERE course_id = ? AND status = 'active') as enrolled,
                    ROUND(COUNT(ar.id) / (SELECT COUNT(*) FROM enrollments WHERE course_id = ? AND status = 'active') * 100, 2) as attendance_rate
                FROM attendance_sessions ats
                LEFT JOIN attendance_records ar ON ats.id = ar.session_id
                WHERE ats.course_id = ?
                GROUP BY ats.id
                ORDER BY ats.session_date ASC";
        
        $stmt = $this->db->getConnection()->prepare($sql);
        $stmt->execute([$courseId, $courseId, $courseId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Identify at-risk students using multi-factor scoring
     */
    private function identifyAtRiskStudents($courseId)
    {
        // Get student attendance data
        $sql = "SELECT 
                    s.id,
                    s.student_id,
                    s.first_name,
                    s.last_name,
                    COUNT(DISTINCT ar.id) as sessions_attended,
                    COUNT(DISTINCT ats.id) as total_sessions,
                    ROUND(COUNT(DISTINCT ar.id) / NULLIF(COUNT(DISTINCT ats.id), 0) * 100, 2) as attendance_rate
                FROM students s
                INNER JOIN enrollments e ON s.id = e.student_id
                CROSS JOIN attendance_sessions ats ON ats.course_id = ?
                LEFT JOIN attendance_records ar ON s.id = ar.student_id AND ar.session_id = ats.id
                WHERE e.course_id = ? AND e.status = 'active'
                GROUP BY s.id
                HAVING attendance_rate < 75
                ORDER BY attendance_rate ASC";
        
        $stmt = $this->db->getConnection()->prepare($sql);
        $stmt->execute([$courseId, $courseId]);
        $students = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Calculate risk scores
        foreach ($students as &$student) {
            $student['risk_score'] = $this->calculateRiskScore($student, $courseId);
            $student['risk_level'] = $this->getRiskLevel($student['risk_score']);
            $student['recommendations'] = $this->getRecommendations($student);
        }
        
        return $students;
    }
    
    /**
     * Calculate risk score (0-100, higher = more at risk)
     */
    private function calculateRiskScore($student, $courseId)
    {
        $score = 0;
        
        // Factor 1: Attendance rate (40% weight)
        $attendanceRate = $student['attendance_rate'] ?? 0;
        $score += (100 - $attendanceRate) * 0.4;
        
        // Factor 2: Declining trend (30% weight)
        $trend = $this->getStudentTrend($student['id'], $courseId);
        if ($trend === 'declining') {
            $score += 30;
        } elseif ($trend === 'stable_low') {
            $score += 20;
        }
        
        // Factor 3: Recent absences (30% weight)
        $recentAbsences = $this->getRecentAbsenceCount($student['id'], $courseId, 3);
        $score += min($recentAbsences * 10, 30);
        
        return min(round($score, 2), 100);
    }
    
    /**
     * Get student attendance trend
     */
    private function getStudentTrend($studentId, $courseId)
    {
        $sql = "SELECT 
                    ats.session_date,
                    COUNT(ar.id) as attended
                FROM attendance_sessions ats
                LEFT JOIN attendance_records ar ON ats.id = ar.session_id AND ar.student_id = ?
                WHERE ats.course_id = ?
                GROUP BY ats.id
                ORDER BY ats.session_date DESC
                LIMIT 5";
        
        $stmt = $this->db->getConnection()->prepare($sql);
        $stmt->execute([$studentId, $courseId]);
        $recent = $stmt->fetchAll(PDO::FETCH_COLUMN, 1);
        
        if (count($recent) < 3) return 'insufficient_data';
        
        $firstHalf = array_slice($recent, 0, ceil(count($recent) / 2));
        $secondHalf = array_slice($recent, ceil(count($recent) / 2));
        
        $firstAvg = array_sum($firstHalf) / count($firstHalf);
        $secondAvg = array_sum($secondHalf) / count($secondHalf);
        
        if ($secondAvg < $firstAvg - 0.2) return 'declining';
        if ($secondAvg < 0.5) return 'stable_low';
        return 'stable';
    }
    
    /**
     * Get recent absence count
     */
    private function getRecentAbsenceCount($studentId, $courseId, $sessionCount)
    {
        $sql = "SELECT 
                    COUNT(*) as absences
                FROM attendance_sessions ats
                LEFT JOIN attendance_records ar ON ats.id = ar.session_id AND ar.student_id = ?
                WHERE ats.course_id = ? AND ar.id IS NULL
                ORDER BY ats.session_date DESC
                LIMIT ?";
        
        $stmt = $this->db->getConnection()->prepare($sql);
        $stmt->execute([$studentId, $courseId, $sessionCount]);
        return $stmt->fetchColumn();
    }
    
    /**
     * Get risk level label
     */
    private function getRiskLevel($score)
    {
        if ($score >= 70) return 'critical';
        if ($score >= 50) return 'high';
        if ($score >= 30) return 'medium';
        return 'low';
    }
    
    /**
     * Get recommendations based on student data
     */
    private function getRecommendations($student)
    {
        $recommendations = [];
        
        if ($student['attendance_rate'] < 50) {
            $recommendations[] = 'Immediate intervention required - contact student urgently';
        } elseif ($student['attendance_rate'] < 75) {
            $recommendations[] = 'Schedule meeting to discuss attendance concerns';
        }
        
        if ($student['risk_score'] > 70) {
            $recommendations[] = 'Consider academic advising referral';
            $recommendations[] = 'Check for personal/health issues affecting attendance';
        }
        
        return $recommendations;
    }
    
    /**
     * Get performance distribution
     */
    private function getPerformanceDistribution($courseId)
    {
        $sql = "SELECT 
                    CASE 
                        WHEN attendance_rate >= 90 THEN 'Excellent (90-100%)'
                        WHEN attendance_rate >= 75 THEN 'Good (75-89%)'
                        WHEN attendance_rate >= 50 THEN 'Fair (50-74%)'
                        ELSE 'Poor (<50%)'
                    END as category,
                    COUNT(*) as student_count
                FROM (
                    SELECT 
                        s.id,
                        ROUND(COUNT(DISTINCT ar.id) / NULLIF(COUNT(DISTINCT ats.id), 0) * 100, 2) as attendance_rate
                    FROM students s
                    INNER JOIN enrollments e ON s.id = e.student_id
                    CROSS JOIN attendance_sessions ats ON ats.course_id = ?
                    LEFT JOIN attendance_records ar ON s.id = ar.student_id AND ar.session_id = ats.id
                    WHERE e.course_id = ? AND e.status = 'active'
                    GROUP BY s.id
                ) as student_rates
                GROUP BY category
                ORDER BY 
                    CASE category
                        WHEN 'Excellent (90-100%)' THEN 1
                        WHEN 'Good (75-89%)' THEN 2
                        WHEN 'Fair (50-74%)' THEN 3
                        ELSE 4
                    END";
        
        $stmt = $this->db->getConnection()->prepare($sql);
        $stmt->execute([$courseId, $courseId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Get session analysis
     */
    private function getSessionAnalysis($courseId)
    {
        $sql = "SELECT 
                    DAYNAME(ats.session_date) as day_of_week,
                    HOUR(ats.start_time) as hour_of_day,
                    AVG(attendance_count) as avg_attendance,
                    COUNT(*) as session_count
                FROM (
                    SELECT 
                        ats.id,
                        ats.session_date,
                        ats.start_time,
                        COUNT(ar.id) as attendance_count
                    FROM attendance_sessions ats
                    LEFT JOIN attendance_records ar ON ats.id = ar.session_id
                    WHERE ats.course_id = ?
                    GROUP BY ats.id
                ) as session_data
                JOIN attendance_sessions ats ON session_data.id = ats.id
                GROUP BY day_of_week, hour_of_day
                ORDER BY avg_attendance DESC";
        
        $stmt = $this->db->getConnection()->prepare($sql);
        $stmt->execute([$courseId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Calculate engagement metrics
     */
    private function calculateEngagementMetrics($courseId)
    {
        $sql = "SELECT 
                    AVG(TIMESTAMPDIFF(SECOND, ats.start_time, ar.attendance_time)) / 60 as avg_check_in_delay,
                    COUNT(DISTINCT CASE WHEN TIMESTAMPDIFF(MINUTE, ats.start_time, ar.attendance_time) <= 5 THEN ar.student_id END) as punctual_students,
                    COUNT(DISTINCT ar.student_id) as total_attendees
                FROM attendance_sessions ats
                INNER JOIN attendance_records ar ON ats.id = ar.session_id
                WHERE ats.course_id = ?";
        
        $stmt = $this->db->getConnection()->prepare($sql);
        $stmt->execute([$courseId]);
        $metrics = $stmt->fetch(PDO::FETCH_ASSOC);
        
        $metrics['punctuality_rate'] = $metrics['total_attendees'] > 0 
            ? round(($metrics['punctual_students'] / $metrics['total_attendees']) * 100, 2)
            : 0;
        
        return $metrics;
    }
    
    /**
     * Generate actionable insights
     */
    private function generateInsights($courseId, $stats, $atRiskStudents, $trends)
    {
        $insights = [];
        
        // Overall attendance insight
        $avgRate = $stats['avg_attendance_rate'] ?? 0;
        if ($avgRate >= 85) {
            $insights[] = [
                'type' => 'success',
                'icon' => 'bi-check-circle',
                'title' => 'Excellent Attendance',
                'message' => "Your course maintains an outstanding {$avgRate}% average attendance rate. Keep up the great work!"
            ];
        } elseif ($avgRate < 70) {
            $insights[] = [
                'type' => 'warning',
                'icon' => 'bi-exclamation-triangle',
                'title' => 'Attendance Needs Improvement',
                'message' => "Average attendance rate of {$avgRate}% is below target. Consider reviewing engagement strategies."
            ];
        }
        
        // At-risk students insight
        $criticalCount = count(array_filter($atRiskStudents, function($s) {
            return $s['risk_level'] === 'critical';
        }));
        
        if ($criticalCount > 0) {
            $insights[] = [
                'type' => 'danger',
                'icon' => 'bi-exclamation-octagon',
                'title' => 'Critical Risk Alert',
                'message' => "{$criticalCount} student(s) are at critical risk. Immediate intervention recommended."
            ];
        }
        
        // Trend insight
        if (count($trends) >= 3) {
            $recentTrends = array_slice($trends, -3);
            $isImproving = $recentTrends[2]['attendance_rate'] > $recentTrends[0]['attendance_rate'];
            
            if ($isImproving) {
                $insights[] = [
                    'type' => 'info',
                    'icon' => 'bi-graph-up-arrow',
                    'title' => 'Positive Trend',
                    'message' => 'Attendance shows improvement in recent sessions. Your engagement strategies are working!'
                ];
            }
        }
        
        return $insights;
    }
}
