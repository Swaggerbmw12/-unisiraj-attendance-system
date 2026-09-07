<?php
/**
 * Base Controller
 * UniSIRAJ Automated Attendance System
 * 
 * Parent controller that provides common functionality
 * All controllers should extend this class
 */

class BaseController {
    
    /**
     * @var Database Database instance
     */
    protected $db;
    
    /**
     * Constructor
     * Initializes database connection and starts session
     */
    public function __construct() {
        // Start session if not already started
        if (session_status() === PHP_SESSION_NONE) {
            $this->startSecureSession();
        }
        
        // Get database instance
        $this->db = Database::getInstance();
        
        // Check session timeout
        $this->checkSessionTimeout();
    }
    
    /**
     * Start secure session with security settings
     */
    protected function startSecureSession() {
        session_start([
            'name' => SESSION_NAME,
            'cookie_lifetime' => 0, // Until browser closes
            'cookie_httponly' => SESSION_HTTPONLY,
            'cookie_secure' => SESSION_SECURE,
            'cookie_samesite' => SESSION_SAMESITE,
            'use_strict_mode' => true,
            'use_only_cookies' => true,
        ]);
    }
    
    /**
     * Check session timeout
     * Logout user if session has expired
     */
    protected function checkSessionTimeout() {
        if (isset($_SESSION['last_activity'])) {
            $elapsed = time() - $_SESSION['last_activity'];
            
            if ($elapsed > SESSION_LIFETIME) {
                $this->logout();
                flash('error', 'Your session has expired. Please login again.');
                redirect('login');
            }
        }
        
        $_SESSION['last_activity'] = time();
    }
    
    /**
     * Load a view file
     * @param string $view View file name (without .php)
     * @param array $data Data to pass to view
     * @param bool $return Whether to return output instead of displaying
     * @return string|null
     */
    protected function view($view, $data = [], $return = false) {
        // Extract data array to variables
        extract($data);
        
        // Build view file path
        $viewFile = VIEW_PATH . '/' . $view . '.php';
        
        // Check if view file exists
        if (!file_exists($viewFile)) {
            die("View file not found: {$viewFile}");
        }
        
        // Start output buffering
        ob_start();
        
        // Include view file
        require $viewFile;
        
        // Get buffered content
        $output = ob_get_clean();
        
        // Return or display
        if ($return) {
            return $output;
        } else {
            echo $output;
        }
    }
    
    /**
     * Load view with layout
     * @param string $view View file name
     * @param array $data Data to pass to view
     * @param string $layout Layout file name (default: 'layouts/main')
     */
    protected function viewWithLayout($view, $data = [], $layout = 'layouts/main') {
        // Get view content
        $content = $this->view($view, $data, true);
        
        // Add content to data
        $data['content'] = $content;
        
        // Load layout with content
        $this->view($layout, $data);
    }
    
    /**
     * Redirect to a URL
     * @param string $path Path to redirect to
     * @param int $statusCode HTTP status code
     */
    protected function redirect($path, $statusCode = 302) {
        redirect($path, $statusCode);
    }
    
    /**
     * Return JSON response
     * @param mixed $data Data to return as JSON
     * @param int $statusCode HTTP status code
     */
    protected function json($data, $statusCode = 200) {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
    
    /**
     * Return JSON response (alias for json)
     * @param mixed $data Data to return as JSON
     * @param int $statusCode HTTP status code
     */
    protected function jsonResponse($data, $statusCode = 200) {
        $this->json($data, $statusCode);
    }
    
    /**
     * Check if current request is AJAX
     * @return bool
     */
    protected function isAjax() {
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
               strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
    }
    
    /**
     * Check if user is authenticated
     * Redirect to login if not authenticated
     * @param array|string|null $roles Optional role(s) to check
     */
    protected function requireAuth($roles = null) {
        if (!isAuthenticated()) {
            $this->setFlashMessage('Please login to access this page.', 'error');
            redirect('login');
        }
        
        // If roles specified, check them
        if ($roles !== null) {
            if (!hasRole($roles)) {
                http_response_code(403);
                $this->view('errors/403');
                exit;
            }
        }
    }
    
    /**
     * Render view with layout (alias for viewWithLayout)
     * @param string $view View file name
     * @param array $data Data to pass to view
     * @param string $layout Layout file name (default: 'layouts/main')
     */
    protected function render($view, $data = [], $layout = 'layouts/main') {
        $this->viewWithLayout($view, $data, $layout);
    }
    
    /**
     * Set flash message
     * @param string $message Message text
     * @param string $type Message type (success, error, info, warning)
     */
    protected function setFlashMessage($message, $type = 'info') {
        $_SESSION['flash_message'] = $message;
        $_SESSION['flash_type'] = $type;
    }
    
    /**
     * Check if user is authenticated
     * Redirect to login if not authenticated
     */
    protected function requireAuthOld() {
        if (!isAuthenticated()) {
            flash('error', 'Please login to access this page.');
            redirect('login');
        }
    }
    
    /**
     * Check if user has specific role
     * Show 403 error if user doesn't have required role
     * @param string|array $roles Required role(s)
     */
    protected function requireRole($roles) {
        $this->requireAuth();
        
        if (!hasRole($roles)) {
            http_response_code(403);
            $this->view('errors/403');
            exit;
        }
    }
    
    /**
     * Validate CSRF token
     * @return bool
     */
    protected function validateCsrfToken() {
        $token = $_POST[CSRF_TOKEN_NAME] ?? $_GET[CSRF_TOKEN_NAME] ?? '';
        
        if (empty($token) || !isset($_SESSION[CSRF_TOKEN_NAME])) {
            return false;
        }
        
        return hash_equals($_SESSION[CSRF_TOKEN_NAME], $token);
    }
    
    /**
     * Generate CSRF token
     * @return string
     */
    protected function generateCsrfToken() {
        if (!isset($_SESSION[CSRF_TOKEN_NAME])) {
            $_SESSION[CSRF_TOKEN_NAME] = bin2hex(random_bytes(32));
        }
        return $_SESSION[CSRF_TOKEN_NAME];
    }
    
    /**
     * Get CSRF token HTML input
     * @return string
     */
    protected function csrfField() {
        $token = $this->generateCsrfToken();
        return '<input type="hidden" name="' . CSRF_TOKEN_NAME . '" value="' . $token . '">';
    }
    
    /**
     * Validate input data
     * @param array $data Data to validate
     * @param array $rules Validation rules
     * @return array Errors (empty if valid)
     */
    protected function validate($data, $rules) {
        $errors = [];
        
        foreach ($rules as $field => $ruleSet) {
            $ruleArray = explode('|', $ruleSet);
            
            foreach ($ruleArray as $rule) {
                // Parse rule and parameters
                $ruleParts = explode(':', $rule);
                $ruleName = $ruleParts[0];
                $ruleParams = isset($ruleParts[1]) ? explode(',', $ruleParts[1]) : [];
                
                // Get field value
                $value = $data[$field] ?? '';
                
                // Apply validation rule
                switch ($ruleName) {
                    case 'required':
                        if (empty($value)) {
                            $errors[$field] = ucfirst($field) . ' is required';
                        }
                        break;
                        
                    case 'email':
                        if (!empty($value) && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                            $errors[$field] = ucfirst($field) . ' must be a valid email';
                        }
                        break;
                        
                    case 'min':
                        if (!empty($value) && strlen($value) < $ruleParams[0]) {
                            $errors[$field] = ucfirst($field) . ' must be at least ' . $ruleParams[0] . ' characters';
                        }
                        break;
                        
                    case 'max':
                        if (!empty($value) && strlen($value) > $ruleParams[0]) {
                            $errors[$field] = ucfirst($field) . ' must not exceed ' . $ruleParams[0] . ' characters';
                        }
                        break;
                        
                    case 'numeric':
                        if (!empty($value) && !is_numeric($value)) {
                            $errors[$field] = ucfirst($field) . ' must be numeric';
                        }
                        break;
                }
            }
        }
        
        return $errors;
    }
    
    /**
     * Logout user
     */
    protected function logout() {
        // Unset all session variables
        $_SESSION = [];
        
        // Destroy session cookie
        if (isset($_COOKIE[SESSION_NAME])) {
            setcookie(SESSION_NAME, '', time() - 3600, '/');
        }
        
        // Destroy session
        session_destroy();
    }
}
