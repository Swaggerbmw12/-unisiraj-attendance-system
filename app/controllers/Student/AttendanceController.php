<?php

require_once dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'AttendanceSession.php';
require_once dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'AttendanceRecord.php';
require_once dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'Student.php';

class AttendanceController extends BaseController
{
    private $sessionModel;
    private $recordModel;
    private $studentModel;
    
    public function __construct()
    {
        parent::__construct();
        $this->requireAuth(['student']);
        $this->sessionModel = new AttendanceSession();
        $this->recordModel = new AttendanceRecord();
        $this->studentModel = new Student();
    }
    
    public function scan()
    {
        $this->render('student/attendance/scan');
    }
    
    public function verify()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' && empty($_GET['token'])) {
            $this->redirect('/student/attendance/scan');
            return;
        }
        
        $token = $_POST['token'] ?? $_GET['token'] ?? '';
        
        if (empty($token)) {
            $this->setFlashMessage('Invalid QR code', 'error');
            $this->redirect('/student/attendance/scan');
            return;
        }
        
        // Get session by token
        $session = $this->sessionModel->getByToken($token);
        
        if (!$session) {
            $this->setFlashMessage('Invalid session', 'error');
            $this->redirect('/student/attendance/scan');
            return;
        }
        
        // Check if session is active and not expired
        if (!$this->sessionModel->isValidSession($token)) {
            $this->setFlashMessage('Session has expired or is closed', 'error');
            $this->redirect('/student/attendance/scan');
            return;
        }
        
        $studentId = $_SESSION['profile_id'];
        
        // Check if student has already recorded attendance
        if ($this->recordModel->hasAttended($session['id'], $studentId)) {
            $this->setFlashMessage('You have already recorded attendance for this session', 'info');
            $this->redirect('/student/dashboard');
            return;
        }
        
        // Record attendance
        $ipAddress = $_SERVER['REMOTE_ADDR'] ?? null;
        $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? null;
        
        if ($this->recordModel->recordAttendance($session['id'], $studentId, $ipAddress, $userAgent)) {
            $this->setFlashMessage('Attendance recorded successfully for ' . $session['course_name'], 'success');
        } else {
            $this->setFlashMessage('Failed to record attendance', 'error');
        }
        
        $this->redirect('/student/dashboard');
    }
    
    public function history()
    {
        $studentId = $_SESSION['profile_id'];
        $history = $this->recordModel->getStudentHistory($studentId, 50);
        $stats = $this->studentModel->getAttendanceStats($studentId);
        
        // Calculate totals
        $totalSessions = 0;
        $totalAttended = 0;
        
        foreach ($stats as $stat) {
            $totalSessions += $stat['total_sessions'];
            $totalAttended += $stat['attended_sessions'];
        }
        
        $this->render('student/attendance/history', [
            'history' => $history,
            'stats' => $stats,
            'totalSessions' => $totalSessions,
            'totalAttended' => $totalAttended
        ]);
    }
}
