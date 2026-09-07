---
inclusion: auto
---

# Security Guidelines - UniSIRAJ Attendance System

## Critical Security Rules

### 1. SQL Injection Prevention
**ALWAYS use prepared statements. NEVER concatenate SQL queries.**

```php
// ✅ CORRECT
$stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? AND active = ?");
$stmt->execute([$email, 1]);

// ❌ NEVER DO THIS
$query = "SELECT * FROM users WHERE email = '$email'";
```

### 2. Password Security
**Never store plain-text passwords.**

```php
// Hashing (Registration/Password Change)
$hashedPassword = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);

// Verification (Login)
if (password_verify($inputPassword, $storedHash)) {
    // Password correct
}

// Password Requirements
// Minimum 8 characters, at least one uppercase, one lowercase, one number
```

### 3. Session Security

```php
// Session Configuration
session_start([
    'cookie_lifetime' => 0,           // Until browser closes
    'cookie_httponly' => true,        // Prevent JavaScript access
    'cookie_secure' => true,          // HTTPS only (use false for localhost)
    'cookie_samesite' => 'Strict',    // CSRF protection
    'use_strict_mode' => true,
    'use_only_cookies' => true
]);

// Session Regeneration (after login)
session_regenerate_id(true);

// Session Timeout (30 minutes)
if (isset($_SESSION['last_activity']) && 
    (time() - $_SESSION['last_activity'] > 1800)) {
    session_unset();
    session_destroy();
    header('Location: /login?timeout=1');
    exit;
}
$_SESSION['last_activity'] = time();
```

### 4. CSRF Protection

```php
// Generate Token (on form load)
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Include in Form
<input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

// Validate Token (on form submission)
if (!isset($_POST['csrf_token']) || 
    $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    die('CSRF token validation failed');
}
```

### 5. Input Validation

```php
// Email Validation
$email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
if (!$email) {
    throw new Exception('Invalid email format');
}

// Integer Validation
$id = filter_var($_POST['id'], FILTER_VALIDATE_INT);
if ($id === false) {
    throw new Exception('Invalid ID');
}

// String Sanitization
$name = htmlspecialchars(trim($_POST['name']), ENT_QUOTES, 'UTF-8');

// Length Validation
if (strlen($name) < 3 || strlen($name) > 100) {
    throw new Exception('Name must be between 3 and 100 characters');
}
```

### 6. Role-Based Access Control

```php
// Define Role Middleware
function requireRole($allowedRoles) {
    if (!isset($_SESSION['user_id'])) {
        header('Location: /login');
        exit;
    }
    
    if (!in_array($_SESSION['role'], $allowedRoles)) {
        http_response_code(403);
        die('Access denied. Insufficient permissions.');
    }
}

// Usage in Controllers
requireRole(['admin']);                    // Admin only
requireRole(['admin', 'lecturer']);        // Admin or Lecturer
requireRole(['admin', 'lecturer', 'student']); // Any authenticated user
```

### 7. QR Code Security

```php
// Generate Secure Session Token
$token = bin2hex(random_bytes(32));
$expiryTime = date('Y-m-d H:i:s', strtotime('+30 minutes'));

// Store in Database
$stmt = $pdo->prepare("
    INSERT INTO attendance_sessions 
    (course_id, lecturer_id, token, expires_at, created_at) 
    VALUES (?, ?, ?, ?, NOW())
");
$stmt->execute([$courseId, $lecturerId, $token, $expiryTime]);

// QR Code Content (include verification data)
$qrContent = json_encode([
    'token' => $token,
    'session_id' => $sessionId,
    'timestamp' => time()
]);
```

### 8. Attendance Validation

```php
// Prevent Duplicate Attendance
$stmt = $pdo->prepare("
    SELECT COUNT(*) FROM attendance_records 
    WHERE session_id = ? AND student_id = ?
");
$stmt->execute([$sessionId, $studentId]);
if ($stmt->fetchColumn() > 0) {
    throw new Exception('Attendance already recorded for this session');
}

// Validate Session Expiry
$stmt = $pdo->prepare("
    SELECT expires_at, is_active FROM attendance_sessions 
    WHERE id = ?
");
$stmt->execute([$sessionId]);
$session = $stmt->fetch();

if (!$session || !$session['is_active']) {
    throw new Exception('Invalid or inactive session');
}

if (strtotime($session['expires_at']) < time()) {
    throw new Exception('Session has expired');
}

// Validate Student Enrollment
$stmt = $pdo->prepare("
    SELECT COUNT(*) FROM enrollments 
    WHERE student_id = ? AND course_id = ? AND status = 'active'
");
$stmt->execute([$studentId, $courseId]);
if ($stmt->fetchColumn() === 0) {
    throw new Exception('Student not enrolled in this course');
}
```

### 9. File Upload Security (if needed)

```php
// Validate File Type
$allowedTypes = ['image/jpeg', 'image/png', 'application/pdf'];
$fileType = mime_content_type($_FILES['file']['tmp_name']);
if (!in_array($fileType, $allowedTypes)) {
    throw new Exception('Invalid file type');
}

// Validate File Size (5MB max)
if ($_FILES['file']['size'] > 5 * 1024 * 1024) {
    throw new Exception('File too large');
}

// Generate Unique Filename
$extension = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
$filename = bin2hex(random_bytes(16)) . '.' . $extension;
$uploadPath = 'storage/uploads/' . $filename;

// Move File Securely
move_uploaded_file($_FILES['file']['tmp_name'], $uploadPath);
```

### 10. Audit Logging

```php
// Log All Security Events
function logAudit($userId, $action, $details = null) {
    global $pdo;
    $stmt = $pdo->prepare("
        INSERT INTO audit_logs 
        (user_id, action, details, ip_address, user_agent, created_at) 
        VALUES (?, ?, ?, ?, ?, NOW())
    ");
    $stmt->execute([
        $userId,
        $action,
        $details ? json_encode($details) : null,
        $_SERVER['REMOTE_ADDR'],
        $_SERVER['HTTP_USER_AGENT']
    ]);
}

// Usage
logAudit($userId, 'login', ['status' => 'success']);
logAudit($userId, 'attendance_recorded', ['session_id' => $sessionId]);
logAudit($userId, 'failed_login_attempt', ['email' => $email]);
```

### 11. Error Handling

```php
// Production vs Development
ini_set('display_errors', 0);  // Never show errors in production
error_reporting(E_ALL);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/../storage/logs/php-errors.log');

// User-Friendly Error Messages
try {
    // Database operation
} catch (PDOException $e) {
    error_log('Database Error: ' . $e->getMessage());
    die('An error occurred. Please try again later.');
}
```

### 12. Headers Security

```php
// Set Security Headers
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY');
header('X-XSS-Protection: 1; mode=block');
header('Referrer-Policy: strict-origin-when-cross-origin');
// header('Content-Security-Policy: default-src \'self\''); // Add if needed
```

## Security Checklist
Before deploying any feature:
- [ ] SQL queries use prepared statements
- [ ] Passwords are hashed with password_hash()
- [ ] CSRF tokens implemented on all forms
- [ ] Input validation on all user data
- [ ] Role-based access control enforced
- [ ] Session security configured
- [ ] Error messages don't leak sensitive info
- [ ] Audit logging in place
- [ ] File uploads validated (if applicable)
- [ ] Security headers set
- [ ] No sensitive data in logs
- [ ] No hardcoded credentials
