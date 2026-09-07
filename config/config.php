<?php
/**
 * Application Configuration File
 * UniSIRAJ Automated Attendance System
 * 
 * This file contains all application-wide configuration settings
 */

// Prevent direct access - but allow inclusion
if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', dirname(__DIR__));
}

// ============================================
// APPLICATION SETTINGS
// ============================================

if (!defined('APP_NAME')) {
    define('APP_NAME', 'UniSIRAJ Attendance System');
}
if (!defined('APP_VERSION')) {
    define('APP_VERSION', '1.0.0');
}
if (!defined('APP_ENV')) {
    define('APP_ENV', 'development'); // development, production
}

// ============================================
// PATH SETTINGS
// ============================================

// Application paths
if (!defined('APP_PATH')) {
    define('APP_PATH', ROOT_PATH . DIRECTORY_SEPARATOR . 'app');
}
if (!defined('CONTROLLER_PATH')) {
    define('CONTROLLER_PATH', APP_PATH . DIRECTORY_SEPARATOR . 'controllers');
}
if (!defined('MODEL_PATH')) {
    define('MODEL_PATH', APP_PATH . DIRECTORY_SEPARATOR . 'models');
}
if (!defined('VIEW_PATH')) {
    define('VIEW_PATH', APP_PATH . DIRECTORY_SEPARATOR . 'views');
}

// Public and storage paths
if (!defined('PUBLIC_PATH')) {
    define('PUBLIC_PATH', ROOT_PATH . DIRECTORY_SEPARATOR . 'public');
}
if (!defined('STORAGE_PATH')) {
    define('STORAGE_PATH', ROOT_PATH . DIRECTORY_SEPARATOR . 'storage');
}
if (!defined('UPLOAD_PATH')) {
    define('UPLOAD_PATH', STORAGE_PATH . DIRECTORY_SEPARATOR . 'uploads');
}
if (!defined('LOG_PATH')) {
    define('LOG_PATH', STORAGE_PATH . DIRECTORY_SEPARATOR . 'logs');
}
if (!defined('QR_PATH')) {
    define('QR_PATH', STORAGE_PATH . DIRECTORY_SEPARATOR . 'qr-codes');
}

// ============================================
// URL SETTINGS
// ============================================

// Base URL - Auto-detect from request
// Supports localhost, Cloudflare tunnels, and production domains
if (!defined('BASE_URL')) {
    // Detect protocol
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') 
        || (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https')
        || (!empty($_SERVER['HTTP_X_FORWARDED_SSL']) && $_SERVER['HTTP_X_FORWARDED_SSL'] === 'on')
        ? 'https' : 'http';
    
    // Detect host (supports Cloudflare tunnels and other proxies)
    $host = $_SERVER['HTTP_HOST'] ?? $_SERVER['SERVER_NAME'] ?? 'localhost:8000';
    
    // Build base URL
    define('BASE_URL', $protocol . '://' . $host);
}
if (!defined('ASSETS_URL')) {
    define('ASSETS_URL', BASE_URL . '/assets');
}

// ============================================
// SESSION SETTINGS
// ============================================

if (!defined('SESSION_NAME')) {
    define('SESSION_NAME', 'unisiraj_attendance_session');
}
if (!defined('SESSION_LIFETIME')) {
    define('SESSION_LIFETIME', 1800); // 30 minutes in seconds
}
if (!defined('SESSION_SECURE')) {
    define('SESSION_SECURE', false); // Set to true in production with HTTPS
}
if (!defined('SESSION_HTTPONLY')) {
    define('SESSION_HTTPONLY', true);
}
if (!defined('SESSION_SAMESITE')) {
    define('SESSION_SAMESITE', 'Strict');
}

// ============================================
// SECURITY SETTINGS
// ============================================

if (!defined('CSRF_TOKEN_NAME')) {
    define('CSRF_TOKEN_NAME', 'csrf_token');
}
if (!defined('PASSWORD_MIN_LENGTH')) {
    define('PASSWORD_MIN_LENGTH', 8);
}
if (!defined('BCRYPT_COST')) {
    define('BCRYPT_COST', 12);
}

// ============================================
// QR CODE SETTINGS
// ============================================

if (!defined('QR_DEFAULT_EXPIRY')) {
    define('QR_DEFAULT_EXPIRY', 30); // Default session expiry in minutes
}
if (!defined('QR_TOKEN_LENGTH')) {
    define('QR_TOKEN_LENGTH', 32); // Token length in bytes (64 characters hex)
}
if (!defined('QR_CODE_SIZE')) {
    define('QR_CODE_SIZE', 10); // QR code size (1-10)
}
if (!defined('QR_CODE_MARGIN')) {
    define('QR_CODE_MARGIN', 2); // QR code margin
}

// ============================================
// PAGINATION SETTINGS
// ============================================

if (!defined('ITEMS_PER_PAGE')) {
    define('ITEMS_PER_PAGE', 10);
}
if (!defined('MAX_PAGINATION_LINKS')) {
    define('MAX_PAGINATION_LINKS', 5);
}

// ============================================
// FILE UPLOAD SETTINGS
// ============================================

if (!defined('MAX_FILE_SIZE')) {
    define('MAX_FILE_SIZE', 5 * 1024 * 1024); // 5MB in bytes
}
if (!defined('ALLOWED_IMAGE_TYPES')) {
    define('ALLOWED_IMAGE_TYPES', serialize(['image/jpeg', 'image/png', 'image/jpg']));
}
if (!defined('ALLOWED_DOCUMENT_TYPES')) {
    define('ALLOWED_DOCUMENT_TYPES', serialize(['application/pdf', 'application/msword']));
}

// ============================================
// ERROR SETTINGS
// ============================================

if (APP_ENV === 'development') {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
} else {
    error_reporting(E_ALL);
    ini_set('display_errors', 0);
    ini_set('display_startup_errors', 0);
    ini_set('log_errors', 1);
    ini_set('error_log', LOG_PATH . '/php-errors.log');
}

// ============================================
// TIMEZONE
// ============================================

date_default_timezone_set('Asia/Kuala_Lumpur');

// ============================================
// HELPER FUNCTIONS
// ============================================

/**
 * Get full URL for a given path
 * @param string $path Path relative to base URL
 * @return string Full URL
 */
function url($path = '') {
    return BASE_URL . '/' . ltrim($path, '/');
}

/**
 * Get asset URL
 * @param string $path Path relative to assets folder
 * @return string Full asset URL
 */
function asset($path) {
    return ASSETS_URL . '/' . ltrim($path, '/');
}

/**
 * Redirect to a given URL
 * @param string $path Path to redirect to
 * @param int $statusCode HTTP status code
 */
function redirect($path, $statusCode = 302) {
    header('Location: ' . url($path), true, $statusCode);
    exit;
}

/**
 * Get or set flash messages
 * @param string|null $key Flash message key
 * @param mixed $value Flash message value
 * @return mixed
 */
function flash($key = null, $value = null) {
    if (!isset($_SESSION)) {
        session_start();
    }
    
    if ($key === null) {
        return $_SESSION['flash'] ?? [];
    }
    
    if ($value === null) {
        $message = $_SESSION['flash'][$key] ?? null;
        unset($_SESSION['flash'][$key]);
        return $message;
    }
    
    $_SESSION['flash'][$key] = $value;
}

/**
 * Sanitize output for HTML
 * @param string $string String to sanitize
 * @return string Sanitized string
 */
function e($string) {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}

/**
 * Check if user is authenticated
 * @return bool
 */
function isAuthenticated() {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

/**
 * Get current user ID
 * @return int|null
 */
function currentUserId() {
    return $_SESSION['user_id'] ?? null;
}

/**
 * Get current user role
 * @return string|null
 */
function currentUserRole() {
    return $_SESSION['role'] ?? null;
}

/**
 * Check if current user has a specific role
 * @param string|array $roles Role(s) to check
 * @return bool
 */
function hasRole($roles) {
    if (!isAuthenticated()) {
        return false;
    }
    
    $currentRole = currentUserRole();
    
    if (is_array($roles)) {
        return in_array($currentRole, $roles);
    }
    
    return $currentRole === $roles;
}
