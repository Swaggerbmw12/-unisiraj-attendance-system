<?php

require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'AttendanceSession.php';
require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'AttendanceRecord.php';
require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'Course.php';
require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'Lecturer.php';
require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'Student.php';

class ReportController extends BaseController
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
     * Reports dashboard - main report selection page
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
        
        $this->render('reports/index', [
            'courses' => $courses,
            'userRole' => $userRole
        ]);
    }
    
    /**
     * Generate attendance report
     */
    public function generate()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/reports');
            return;
        }
        
        $reportType = $_POST['report_type'] ?? 'course_summary';
        $courseId = $_POST['course_id'] ?? null;
        $dateFrom = $_POST['date_from'] ?? null;
        $dateTo = $_POST['date_to'] ?? null;
        $format = $_POST['format'] ?? 'html';
        
        // Validate inputs
        if (!$courseId) {
            $this->setFlashMessage('Please select a course', 'error');
            $this->redirect('/reports');
            return;
        }
        
        // Check authorization for lecturer
        if ($_SESSION['role'] === 'lecturer') {
            $course = $this->courseModel->getById($courseId);
            if (!$course || $course['lecturer_id'] != $_SESSION['profile_id']) {
                $this->setFlashMessage('Unauthorized access to course', 'error');
                $this->redirect('/reports');
                return;
            }
        }
        
        // Generate report based on type
        switch ($reportType) {
            case 'course_summary':
                $reportData = $this->generateCourseSummaryReport($courseId, $dateFrom, $dateTo);
                break;
            case 'student_details':
                $reportData = $this->generateStudentDetailsReport($courseId, $dateFrom, $dateTo);
                break;
            case 'session_details':
                $reportData = $this->generateSessionDetailsReport($courseId, $dateFrom, $dateTo);
                break;
            default:
                $reportData = $this->generateCourseSummaryReport($courseId, $dateFrom, $dateTo);
        }
        
        // Handle output format
        if ($format === 'pdf') {
            $this->exportPdf($reportData, $reportType);
        } elseif ($format === 'excel') {
            $this->exportExcel($reportData, $reportType);
        } else {
            $this->render('reports/view', [
                'reportData' => $reportData,
                'reportType' => $reportType,
                'dateFrom' => $dateFrom,
                'dateTo' => $dateTo
            ]);
        }
    }
    
    /**
     * Generate course summary report
     */
    private function generateCourseSummaryReport($courseId, $dateFrom = null, $dateTo = null)
    {
        $course = $this->courseModel->getById($courseId);
        
        // Build date filter
        $dateFilter = "";
        $params = [$courseId];
        
        if ($dateFrom && $dateTo) {
            $dateFilter = "AND ats.session_date BETWEEN ? AND ?";
            $params[] = $dateFrom;
            $params[] = $dateTo;
        }
        
        // Get sessions summary
        $sql = "SELECT 
                    COUNT(DISTINCT ats.id) as total_sessions,
                    COUNT(DISTINCT ar.id) as total_attendance_records,
                    COUNT(DISTINCT ar.student_id) as unique_students_attended,
                    (SELECT COUNT(*) FROM enrollments WHERE course_id = ? AND status = 'active') as total_enrolled
                FROM attendance_sessions ats
                LEFT JOIN attendance_records ar ON ats.id = ar.session_id
                WHERE ats.course_id = ? $dateFilter";
        
        $summaryParams = [$courseId, $courseId];
        if ($dateFrom && $dateTo) {
            $summaryParams[] = $dateFrom;
            $summaryParams[] = $dateTo;
        }
        
        $stmt = $this->db->getConnection()->prepare($sql);
        $stmt->execute($summaryParams);
        $summary = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Get attendance by session
        $sql = "SELECT 
                    ats.id,
                    ats.session_name,
                    ats.session_date,
                    ats.start_time,
                    ats.end_time,
                    COUNT(ar.id) as attendees,
                    (SELECT COUNT(*) FROM enrollments WHERE course_id = ? AND status = 'active') as enrolled,
                    ROUND((COUNT(ar.id) / (SELECT COUNT(*) FROM enrollments WHERE course_id = ? AND status = 'active') * 100), 2) as percentage
                FROM attendance_sessions ats
                LEFT JOIN attendance_records ar ON ats.id = ar.session_id
                WHERE ats.course_id = ? $dateFilter
                GROUP BY ats.id
                ORDER BY ats.session_date DESC, ats.start_time DESC";
        
        $sessionParams = [$courseId, $courseId, $courseId];
        if ($dateFrom && $dateTo) {
            $sessionParams[] = $dateFrom;
            $sessionParams[] = $dateTo;
        }
        
        $stmt = $this->db->getConnection()->prepare($sql);
        $stmt->execute($sessionParams);
        $sessions = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Build date filter for student query
        $studentDateFilter = "";
        if ($dateFrom && $dateTo) {
            $studentDateFilter = "AND ats2.session_date BETWEEN ? AND ?";
        }
        
        // Get student attendance summary
        $sql = "SELECT 
                    s.id,
                    s.student_id,
                    s.first_name,
                    s.last_name,
                    COUNT(DISTINCT ar.id) as sessions_attended,
                    (SELECT COUNT(*) FROM attendance_sessions ats2 WHERE ats2.course_id = ? $studentDateFilter) as total_sessions,
                    ROUND((COUNT(DISTINCT ar.id) / (SELECT COUNT(*) FROM attendance_sessions ats3 WHERE ats3.course_id = ? $studentDateFilter) * 100), 2) as percentage
                FROM students s
                INNER JOIN enrollments e ON s.id = e.student_id
                LEFT JOIN attendance_records ar ON s.id = ar.student_id
                LEFT JOIN attendance_sessions ats ON ar.session_id = ats.id AND ats.course_id = ?
                WHERE e.course_id = ? AND e.status = 'active' " . ($dateFrom && $dateTo ? "AND ats.session_date BETWEEN ? AND ?" : "") . "
                GROUP BY s.id
                ORDER BY percentage DESC, s.last_name ASC";
        
        $studentParams = [$courseId];
        if ($dateFrom && $dateTo) {
            $studentParams[] = $dateFrom;
            $studentParams[] = $dateTo;
        }
        $studentParams[] = $courseId;
        if ($dateFrom && $dateTo) {
            $studentParams[] = $dateFrom;
            $studentParams[] = $dateTo;
        }
        $studentParams[] = $courseId;
        $studentParams[] = $courseId;
        if ($dateFrom && $dateTo) {
            $studentParams[] = $dateFrom;
            $studentParams[] = $dateTo;
        }
        
        $stmt = $this->db->getConnection()->prepare($sql);
        $stmt->execute($studentParams);
        $students = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return [
            'course' => $course,
            'summary' => $summary,
            'sessions' => $sessions,
            'students' => $students,
            'date_from' => $dateFrom,
            'date_to' => $dateTo
        ];
    }
    
    /**
     * Generate student details report
     */
    private function generateStudentDetailsReport($courseId, $dateFrom = null, $dateTo = null)
    {
        $course = $this->courseModel->getById($courseId);
        
        $dateFilter = "";
        $params = [$courseId];
        
        if ($dateFrom && $dateTo) {
            $dateFilter = "AND ats.session_date BETWEEN ? AND ?";
        }
        
        // Get detailed student attendance
        $sql = "SELECT 
                    s.id,
                    s.student_id,
                    s.first_name,
                    s.last_name,
                    u.email,
                    COUNT(DISTINCT ar.id) as sessions_attended,
                    (SELECT COUNT(*) FROM attendance_sessions ats2 WHERE ats2.course_id = ? " . ($dateFrom && $dateTo ? "AND ats2.session_date BETWEEN ? AND ?" : "") . ") as total_sessions,
                    GROUP_CONCAT(DISTINCT DATE_FORMAT(ar.attendance_time, '%Y-%m-%d %H:%i') ORDER BY ar.attendance_time SEPARATOR ', ') as attendance_dates
                FROM students s
                INNER JOIN users u ON s.user_id = u.id
                INNER JOIN enrollments e ON s.id = e.student_id
                LEFT JOIN attendance_records ar ON s.id = ar.student_id
                LEFT JOIN attendance_sessions ats ON ar.session_id = ats.id AND ats.course_id = ? " . $dateFilter . "
                WHERE e.course_id = ? AND e.status = 'active'
                GROUP BY s.id
                ORDER BY s.last_name ASC, s.first_name ASC";
        
        $queryParams = [$courseId];
        if ($dateFrom && $dateTo) {
            $queryParams[] = $dateFrom;
            $queryParams[] = $dateTo;
        }
        $queryParams[] = $courseId;
        if ($dateFrom && $dateTo) {
            $queryParams[] = $dateFrom;
            $queryParams[] = $dateTo;
        }
        $queryParams[] = $courseId;
        
        $stmt = $this->db->getConnection()->prepare($sql);
        $stmt->execute($queryParams);
        $students = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return [
            'course' => $course,
            'students' => $students,
            'date_from' => $dateFrom,
            'date_to' => $dateTo
        ];
    }
    
    /**
     * Generate session details report
     */
    private function generateSessionDetailsReport($courseId, $dateFrom = null, $dateTo = null)
    {
        $course = $this->courseModel->getById($courseId);
        
        $dateFilter = "";
        $params = [$courseId];
        
        if ($dateFrom && $dateTo) {
            $dateFilter = "AND ats.session_date BETWEEN ? AND ?";
            $params[] = $dateFrom;
            $params[] = $dateTo;
        }
        
        // Get sessions with attendance details
        $sql = "SELECT 
                    ats.id,
                    ats.session_name,
                    ats.session_date,
                    ats.start_time,
                    ats.end_time,
                    ats.created_at
                FROM attendance_sessions ats
                WHERE ats.course_id = ? $dateFilter
                ORDER BY ats.session_date DESC, ats.start_time DESC";
        
        $stmt = $this->db->getConnection()->prepare($sql);
        $stmt->execute($params);
        $sessions = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Get attendance records for each session
        foreach ($sessions as &$session) {
            $sql = "SELECT 
                        s.student_id,
                        s.first_name,
                        s.last_name,
                        ar.attendance_time
                    FROM attendance_records ar
                    INNER JOIN students s ON ar.student_id = s.id
                    WHERE ar.session_id = ?
                    ORDER BY ar.attendance_time ASC";
            
            $stmt = $this->db->getConnection()->prepare($sql);
            $stmt->execute([$session['id']]);
            $session['attendees'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        
        return [
            'course' => $course,
            'sessions' => $sessions,
            'date_from' => $dateFrom,
            'date_to' => $dateTo
        ];
    }
    
    /**
     * Export report as PDF
     */
    public function exportPdf($reportData = null, $reportType = null)
    {
        // For now, redirect back with message
        $this->setFlashMessage('PDF export feature coming soon! Use print function for now.', 'info');
        $this->redirect('/reports');
    }
    
    /**
     * Export report as Excel/CSV
     */
    public function exportExcel($reportData = null, $reportType = null)
    {
        // For now, redirect back with message
        $this->setFlashMessage('Excel export feature coming soon! Use print function for now.', 'info');
        $this->redirect('/reports');
    }
}
