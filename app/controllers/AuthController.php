<?php
/**
 * Authentication Controller
 * UniSIRAJ Automated Attendance System
 * 
 * Handles user login, logout, and authentication
 */

require_once MODEL_PATH . '/User.php';

class AuthController extends BaseController {
    
    /**
     * @var User User model instance
     */
    private $userModel;
    
    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct();
        $this->userModel = new User();
    }
    
    /**
     * Display login page
     */
    public function login() {
        // If already logged in, redirect to dashboard
        if (isAuthenticated()) {
            $this->redirectToDashboard();
        }
        
        // Handle POST request (login form submission)
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->processLogin();
            return;
        }
        
        // Display login form (GET request)
        $this->view('auth/login', [
            'title' => 'Login',
            'csrfToken' => $this->generateCsrfToken()
        ]);
    }
    
    /**
     * Process login form submission
     */
    private function processLogin() {
        // Validate CSRF token
        if (!$this->validateCsrfToken()) {
            flash('error', 'Invalid request. Please try again.');
            redirect('login');
        }
        
        // Get form data
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $remember = isset($_POST['remember']);
        
        // Validate input
        $errors = [];
        
        if (empty($email)) {
            $errors['email'] = 'Email is required';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Invalid email format';
        }
        
        if (empty($password)) {
            $errors['password'] = 'Password is required';
        }
        
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old_input'] = ['email' => $email];
            redirect('login');
        }
        
        // Attempt authentication
        $user = $this->userModel->authenticate($email, $password);
        
        if (!$user) {
            // Log failed login attempt
            $this->logAudit(null, 'failed_login_attempt', [
                'email' => $email,
                'ip_address' => $_SERVER['REMOTE_ADDR']
            ]);
            
            flash('error', 'Invalid email or password. Please try again.');
            $_SESSION['old_input'] = ['email' => $email];
            redirect('login');
        }
        
        // Get complete user profile
        $userProfile = $this->userModel->getUserProfile($user['id']);
        
        if (!$userProfile) {
            flash('error', 'Unable to load user profile. Please contact administrator.');
            redirect('login');
        }
        
        // Create session
        $this->createUserSession($userProfile, $remember);
        
        // Log successful login
        $this->logAudit($user['id'], 'login', [
            'ip_address' => $_SERVER['REMOTE_ADDR'],
            'user_agent' => $_SERVER['HTTP_USER_AGENT']
        ]);
        
        // Flash success message
        flash('success', 'Welcome back, ' . e($userProfile['profile']['first_name'] ?? 'User') . '!');
        
        // Redirect to appropriate dashboard
        $this->redirectToDashboard();
    }
    
    /**
     * Create user session after successful authentication
     * @param array $user User data
     * @param bool $remember Remember me option
     */
    private function createUserSession($user, $remember = false) {
        // Regenerate session ID for security
        session_regenerate_id(true);
        
        // Store user data in session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role_name'];
        $_SESSION['role_id'] = $user['role_id'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['is_active'] = $user['is_active'];
        $_SESSION['last_activity'] = time();
        
        // Store profile-specific data
        if (isset($user['profile'])) {
            $_SESSION['user_name'] = $user['profile']['first_name'] . ' ' . $user['profile']['last_name'];
            $_SESSION['profile_id'] = $user['profile']['id']; // Generic profile ID
            
            if ($user['role_name'] === 'student') {
                $_SESSION['student_id'] = $user['profile']['id'];
                $_SESSION['student_number'] = $user['profile']['student_id'];
            } elseif ($user['role_name'] === 'lecturer') {
                $_SESSION['lecturer_id'] = $user['profile']['id'];
                $_SESSION['staff_id'] = $user['profile']['staff_id'];
            }
        } else {
            // For admin or users without profile, use email username
            $_SESSION['user_name'] = explode('@', $user['email'])[0];
        }
        
        // Set remember me cookie (optional - for future implementation)
        if ($remember) {
            // TODO: Implement "Remember Me" functionality
            // This would require a separate token-based system
        }
    }
    
    /**
     * Redirect to appropriate dashboard based on role
     */
    private function redirectToDashboard() {
        $role = currentUserRole();
        
        switch ($role) {
            case 'admin':
                redirect('admin/dashboard');
                break;
            case 'lecturer':
                redirect('lecturer/dashboard');
                break;
            case 'student':
                redirect('student/dashboard');
                break;
            default:
                // Invalid role, logout
                $this->logout();
        }
    }
    
    /**
     * Logout user
     */
    public function logout() {
        // Log logout action
        if (isAuthenticated()) {
            $this->logAudit(currentUserId(), 'logout', [
                'ip_address' => $_SERVER['REMOTE_ADDR']
            ]);
        }
        
        // Destroy session
        parent::logout();
        
        // Flash message
        flash('success', 'You have been logged out successfully.');
        
        // Redirect to login
        redirect('login');
    }
    
    /**
     * Log audit entry
     * @param int|null $userId User ID
     * @param string $action Action performed
     * @param array|null $details Additional details
     */
    private function logAudit($userId, $action, $details = null) {
        try {
            $sql = "INSERT INTO audit_logs (user_id, action, details, ip_address, user_agent, created_at)
                    VALUES (?, ?, ?, ?, ?, NOW())";
            
            $this->db->execute($sql, [
                $userId,
                $action,
                $details ? json_encode($details) : null,
                $_SERVER['REMOTE_ADDR'] ?? null,
                $_SERVER['HTTP_USER_AGENT'] ?? null
            ]);
        } catch (Exception $e) {
            // Log error but don't interrupt flow
            error_log('Audit log failed: ' . $e->getMessage());
        }
    }
    
    /**
     * Password reset request (placeholder for future implementation)
     */
    public function forgotPassword() {
        $this->view('auth/forgot-password', [
            'title' => 'Forgot Password'
        ]);
    }
    
    /**
     * Change password (for logged-in users)
     */
    public function changePassword() {
        // Require authentication
        $this->requireAuth();
        
        // Handle POST request
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->processPasswordChange();
            return;
        }
        
        // Display change password form
        $this->viewWithLayout('auth/change-password', [
            'title' => 'Change Password',
            'csrfToken' => $this->generateCsrfToken()
        ]);
    }
    
    /**
     * Process password change
     */
    private function processPasswordChange() {
        // Validate CSRF token
        if (!$this->validateCsrfToken()) {
            flash('error', 'Invalid request. Please try again.');
            redirect('change-password');
        }
        
        $currentPassword = $_POST['current_password'] ?? '';
        $newPassword = $_POST['new_password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';
        
        // Validate input
        $errors = [];
        
        if (empty($currentPassword)) {
            $errors['current_password'] = 'Current password is required';
        }
        
        if (empty($newPassword)) {
            $errors['new_password'] = 'New password is required';
        } elseif (strlen($newPassword) < PASSWORD_MIN_LENGTH) {
            $errors['new_password'] = 'Password must be at least ' . PASSWORD_MIN_LENGTH . ' characters';
        }
        
        if ($newPassword !== $confirmPassword) {
            $errors['confirm_password'] = 'Passwords do not match';
        }
        
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            redirect('change-password');
        }
        
        // Verify current password
        $user = $this->userModel->find(currentUserId());
        if (!password_verify($currentPassword, $user['password'])) {
            flash('error', 'Current password is incorrect');
            redirect('change-password');
        }
        
        // Update password
        $success = $this->userModel->updatePassword(currentUserId(), $newPassword);
        
        if ($success) {
            // Log password change
            $this->logAudit(currentUserId(), 'password_changed', [
                'ip_address' => $_SERVER['REMOTE_ADDR']
            ]);
            
            flash('success', 'Password changed successfully!');
        } else {
            flash('error', 'Failed to change password. Please try again.');
        }
        
        redirect('change-password');
    }
}
