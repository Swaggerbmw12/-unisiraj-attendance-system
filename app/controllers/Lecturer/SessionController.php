<?php

require_once dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'AttendanceSession.php';
require_once dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'Lecturer.php';
require_once dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'Course.php';

class SessionController extends BaseController
{
    private $sessionModel;
    private $lecturerModel;
    private $courseModel;
    
    public function __construct()
    {
        parent::__construct();
        $this->requireAuth(['lecturer']);
        $this->sessionModel = new AttendanceSession();
        $this->lecturerModel = new Lecturer();
        $this->courseModel = new Course();
    }
    
    public function index()
    {
        $lecturerId = $_SESSION['profile_id'];
        $sessions = $this->sessionModel->getByLecturer($lecturerId, 50);
        $courses = $this->lecturerModel->getAssignedCourses($lecturerId);
        
        $this->render('lecturer/sessions/index', [
            'sessions' => $sessions,
            'courses' => $courses
        ]);
    }
    
    public function create()
    {
        $lecturerId = $_SESSION['profile_id'];
        $courses = $this->lecturerModel->getAssignedCourses($lecturerId);
        
        $this->render('lecturer/sessions/create', [
            'courses' => $courses
        ]);
    }
    
    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/lecturer/sessions');
            return;
        }
        
        $errors = [];
        
        if (empty($_POST['course_id'])) $errors[] = 'Course is required';
        if (empty($_POST['session_date'])) $errors[] = 'Session date is required';
        if (empty($_POST['start_time'])) $errors[] = 'Start time is required';
        if (empty($_POST['duration'])) $errors[] = 'Duration is required';
        
        if (!empty($errors)) {
            $this->setFlashMessage(implode(', ', $errors), 'error');
            $this->redirect('/lecturer/sessions/create');
            return;
        }
        
        // Calculate expiration time
        $duration = (int)$_POST['duration'];
        $expiresAt = date('Y-m-d H:i:s', strtotime($_POST['session_date'] . ' ' . $_POST['start_time'] . ' +' . $duration . ' minutes'));
        
        $data = [
            'course_id' => $_POST['course_id'],
            'lecturer_id' => $_SESSION['profile_id'],
            'session_name' => $_POST['session_name'] ?? null,
            'session_date' => $_POST['session_date'],
            'start_time' => $_POST['start_time'],
            'expires_at' => $expiresAt
        ];
        
        $sessionId = $this->sessionModel->create($data);
        
        if ($sessionId) {
            // Get session to retrieve token
            $session = $this->sessionModel->getById($sessionId);
            
            // Generate QR code
            $this->generateQRCode($session['token'], $sessionId);
            
            $this->setFlashMessage('Session created successfully', 'success');
            $this->redirect('/lecturer/sessions/view?id=' . $sessionId);
        } else {
            $this->setFlashMessage('Failed to create session', 'error');
            $this->redirect('/lecturer/sessions/create');
        }
    }
    
    public function viewSession($id = null)
    {
        // Get ID from parameter or query string
        $id = $id ?? ($_GET['id'] ?? null);
        
        if (!$id) {
            $this->setFlashMessage('Session ID is required', 'error');
            $this->redirect('/lecturer/sessions');
            return;
        }
        
        $session = $this->sessionModel->getById($id);
        
        if (!$session || $session['lecturer_id'] != $_SESSION['profile_id']) {
            $this->setFlashMessage('Session not found', 'error');
            $this->redirect('/lecturer/sessions');
            return;
        }
        
        $attendanceRecords = $this->sessionModel->getAttendanceRecords($id);
        
        $this->render('lecturer/sessions/view', [
            'session' => $session,
            'attendanceRecords' => $attendanceRecords
        ]);
    }
    
    public function close()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/lecturer/sessions');
            return;
        }
        
        // Get ID from query string
        $id = $_GET['id'] ?? null;
        
        if (!$id) {
            $this->setFlashMessage('Session ID is required', 'error');
            $this->redirect('/lecturer/sessions');
            return;
        }
        
        $session = $this->sessionModel->getById($id);
        
        if (!$session || $session['lecturer_id'] != $_SESSION['profile_id']) {
            $this->setFlashMessage('Session not found', 'error');
            $this->redirect('/lecturer/sessions');
            return;
        }
        
        if ($this->sessionModel->closeSession($id)) {
            $this->setFlashMessage('Session closed successfully', 'success');
        } else {
            $this->setFlashMessage('Failed to close session', 'error');
        }
        
        $this->redirect('/lecturer/sessions/view?id=' . $id);
    }
    
    /**
     * Generate QR code for session
     */
    private function generateQRCode($token, $sessionId)
    {
        // Create QR code URL using dynamic base URL detection
        // Detect protocol (supports HTTPS and proxy headers)
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') 
            || (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https')
            || (!empty($_SERVER['HTTP_X_FORWARDED_SSL']) && $_SERVER['HTTP_X_FORWARDED_SSL'] === 'on')
            || (!empty($_SERVER['REQUEST_SCHEME']) && $_SERVER['REQUEST_SCHEME'] === 'https')
            ? 'https' : 'http';
        
        // Detect host (supports Cloudflare tunnels and other proxies)
        $host = $_SERVER['HTTP_HOST'] ?? $_SERVER['SERVER_NAME'] ?? 'localhost:8000';
        
        $baseUrl = $protocol . '://' . $host;
        $qrUrl = $baseUrl . '/student/attendance/scan?token=' . $token;
        
        // Use simple QR code API (for now, we'll use a free service)
        $qrCodePath = "qr_session_{$sessionId}.png";
        $qrCodeUrl = "https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=" . urlencode($qrUrl);
        
        // Save QR code path to database
        $this->sessionModel->updateQrCodePath($sessionId, $qrCodeUrl);
        
        return $qrCodePath;
    }
    
    /**
     * Delete a session
     */
    public function delete()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/lecturer/sessions');
            return;
        }
        
        // Get ID from query string
        $id = $_GET['id'] ?? null;
        
        if (!$id) {
            $this->setFlashMessage('Session ID is required', 'error');
            $this->redirect('/lecturer/sessions');
            return;
        }
        
        $session = $this->sessionModel->getById($id);
        
        if (!$session || $session['lecturer_id'] != $_SESSION['profile_id']) {
            $this->setFlashMessage('Session not found or unauthorized', 'error');
            $this->redirect('/lecturer/sessions');
            return;
        }
        
        if ($this->sessionModel->delete($id)) {
            $this->setFlashMessage('Session deleted successfully', 'success');
        } else {
            $this->setFlashMessage('Failed to delete session', 'error');
        }
        
        $this->redirect('/lecturer/sessions');
    }
    
    /**
     * Get live attendance (AJAX endpoint)
     */
    public function liveAttendance($id)
    {
        header('Content-Type: application/json');
        
        $session = $this->sessionModel->getById($id);
        
        if (!$session || $session['lecturer_id'] != $_SESSION['profile_id']) {
            echo json_encode(['error' => 'Unauthorized']);
            exit;
        }
        
        $attendanceRecords = $this->sessionModel->getAttendanceRecords($id);
        
        echo json_encode([
            'total_attendees' => count($attendanceRecords),
            'total_enrolled' => $session['total_enrolled'],
            'percentage' => round((count($attendanceRecords) / max($session['total_enrolled'], 1)) * 100, 2),
            'records' => $attendanceRecords
        ]);
        exit;
    }
}
