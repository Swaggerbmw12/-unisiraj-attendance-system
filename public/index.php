<?php
/**
 * Front Controller / Entry Point
 * UniSIRAJ Automated Attendance System
 * 
 * All requests are routed through this file
 * Handles routing, controller loading, and request processing
 */

// Define root path constant first (before loading any files)
define('ROOT_PATH', dirname(__DIR__));

// Load configuration
require_once ROOT_PATH . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'config.php';

// Load database connection
require_once ROOT_PATH . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'database.php';

// Load base classes
require_once APP_PATH . DIRECTORY_SEPARATOR . 'controllers' . DIRECTORY_SEPARATOR . 'BaseController.php';
require_once APP_PATH . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'BaseModel.php';

/**
 * Simple Router Class
 * Handles URL routing and controller instantiation
 */
class Router {
    
    private $routes;
    
    /**
     * Constructor
     * Load routes from routes file
     */
    public function __construct() {
        $this->routes = require ROOT_PATH . DIRECTORY_SEPARATOR . 'routes' . DIRECTORY_SEPARATOR . 'web.php';
    }
    
    /**
     * Get current request URI
     * @return string
     */
    private function getRequestUri() {
        $uri = $_SERVER['REQUEST_URI'];
        
        // Remove query string
        if (($pos = strpos($uri, '?')) !== false) {
            $uri = substr($uri, 0, $pos);
        }
        
        // Remove trailing slash
        $uri = rtrim($uri, '/');
        
        // If empty, set to root
        if (empty($uri)) {
            $uri = '/';
        }
        
        return $uri;
    }
    
    /**
     * Dispatch request to appropriate controller
     */
    public function dispatch() {
        $uri = $this->getRequestUri();
        
        // Check if route exists
        if (!isset($this->routes[$uri])) {
            $this->handleNotFound();
            return;
        }
        
        $route = $this->routes[$uri];
        $controllerName = $route['controller'];
        $methodName = $route['method'];
        $params = $route['params'] ?? [];
        
        // Load controller file
        $this->loadController($controllerName);
        
        // Determine actual class name
        // Try multiple strategies since naming is inconsistent:
        // 1. For 'Lecturer\SessionController' -> 'SessionController'
        // 2. For 'Student\DashboardController' -> 'StudentDashboardController'
        // 3. For 'Admin\StudentController' -> class might be either
        
        $className = null;
        
        if (strpos($controllerName, '\\') !== false) {
            $parts = explode('\\', $controllerName);
            
            // Strategy 1: Try just the class name (e.g., SessionController)
            $justClassName = $parts[1];
            if (class_exists($justClassName)) {
                $className = $justClassName;
            } else {
                // Strategy 2: Try namespace + class name (e.g., LecturerDashboardController)
                $prefixedClassName = $parts[0] . $parts[1];
                if (class_exists($prefixedClassName)) {
                    $className = $prefixedClassName;
                }
            }
        } else {
            // No namespace, use as-is
            $className = $controllerName;
        }
        
        // Instantiate controller
        if (!$className || !class_exists($className)) {
            die("Controller class not found: {$controllerName} (tried: " . ($className ?? 'none') . ")");
        }
        
        $controller = new $className();
        
        // Check if method exists
        if (!method_exists($controller, $methodName)) {
            die("Method not found: {$className}::{$methodName}");
        }
        
        // Call controller method with parameters
        if (!empty($params)) {
            call_user_func_array([$controller, $methodName], $params);
        } else {
            $controller->$methodName();
        }
    }
    
    /**
     * Load controller file
     * @param string $controllerName Controller name (may include namespace-like path)
     */
    private function loadController($controllerName) {
        // Check if controller has a namespace-like path (contains backslash)
        if (strpos($controllerName, '\\') !== false) {
            // Convert namespace-like path to file path
            // Example: Admin\StudentController -> Admin/StudentController.php
            $controllerPath = str_replace('\\', DIRECTORY_SEPARATOR, $controllerName) . '.php';
        } else {
            // Controller name without path - check in subdirectories
            // Special case: LecturerDashboardController -> Lecturer/DashboardController.php
            if ($controllerName === 'LecturerDashboardController') {
                $controllerPath = 'Lecturer' . DIRECTORY_SEPARATOR . 'DashboardController.php';
            } 
            // Special case: StudentDashboardController -> Student/DashboardController.php
            elseif ($controllerName === 'StudentDashboardController') {
                $controllerPath = 'Student' . DIRECTORY_SEPARATOR . 'DashboardController.php';
            } 
            // Default: controller file in root controllers directory
            else {
                $controllerPath = $controllerName . '.php';
            }
        }
        
        $controllerFile = CONTROLLER_PATH . DIRECTORY_SEPARATOR . $controllerPath;
        
        if (!file_exists($controllerFile)) {
            die("Controller file not found: {$controllerFile}");
        }
        
        require_once $controllerFile;
    }
    
    /**
     * Handle 404 Not Found
     */
    private function handleNotFound() {
        http_response_code(404);
        
        // Check if error controller exists
        $errorControllerFile = CONTROLLER_PATH . '/ErrorController.php';
        if (file_exists($errorControllerFile)) {
            require_once $errorControllerFile;
            $controller = new ErrorController();
            $controller->notFound();
        } else {
            // Fallback 404 page
            echo '<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>404 - Page Not Found</title>
    <style>
        body { font-family: Arial, sans-serif; text-align: center; padding: 50px; }
        h1 { font-size: 72px; margin: 0; color: #e74c3c; }
        p { font-size: 24px; color: #7f8c8d; }
        a { color: #3498db; text-decoration: none; }
    </style>
</head>
<body>
    <h1>404</h1>
    <p>Page Not Found</p>
    <a href="' . BASE_URL . '">Go to Home</a>
</body>
</html>';
        }
    }
}

// ============================================
// ERROR HANDLING
// ============================================

/**
 * Custom error handler
 */
function customErrorHandler($errno, $errstr, $errfile, $errline) {
    $errorMessage = sprintf(
        "[%s] Error [%d]: %s in %s on line %d\n",
        date('Y-m-d H:i:s'),
        $errno,
        $errstr,
        $errfile,
        $errline
    );
    
    error_log($errorMessage);
    
    if (APP_ENV === 'development') {
        echo "<div style='background: #f8d7da; color: #721c24; padding: 15px; margin: 10px; border: 1px solid #f5c6cb; border-radius: 5px;'>";
        echo "<strong>Error [{$errno}]:</strong> {$errstr}<br>";
        echo "<strong>File:</strong> {$errfile}<br>";
        echo "<strong>Line:</strong> {$errline}";
        echo "</div>";
    }
    
    return true;
}

/**
 * Custom exception handler
 */
function customExceptionHandler($exception) {
    $errorMessage = sprintf(
        "[%s] Exception: %s in %s on line %d\nStack trace:\n%s\n",
        date('Y-m-d H:i:s'),
        $exception->getMessage(),
        $exception->getFile(),
        $exception->getLine(),
        $exception->getTraceAsString()
    );
    
    error_log($errorMessage);
    
    if (APP_ENV === 'development') {
        echo "<div style='background: #f8d7da; color: #721c24; padding: 15px; margin: 10px; border: 1px solid #f5c6cb; border-radius: 5px;'>";
        echo "<strong>Exception:</strong> " . $exception->getMessage() . "<br>";
        echo "<strong>File:</strong> " . $exception->getFile() . "<br>";
        echo "<strong>Line:</strong> " . $exception->getLine() . "<br>";
        echo "<pre>" . $exception->getTraceAsString() . "</pre>";
        echo "</div>";
    } else {
        echo "An error occurred. Please try again later.";
    }
}

// Set custom error handlers
set_error_handler('customErrorHandler');
set_exception_handler('customExceptionHandler');

// ============================================
// BOOTSTRAP APPLICATION
// ============================================

try {
    // Test database connection
    $dbInstance = Database::getInstance();
    if (!$dbInstance->testConnection()) {
        throw new Exception('Database connection failed');
    }
    
    // Create and dispatch router
    $router = new Router();
    $router->dispatch();
    
} catch (Exception $e) {
    customExceptionHandler($e);
}
