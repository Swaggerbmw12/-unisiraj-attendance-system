---
inclusion: auto
---

# UniSIRAJ Attendance System - Project Standards

## Project Context
This is a Final Year Project for Universiti Islam Antarabangsa Tuanku Syed Sirajuddin (UniSIRAJ).
- **Student**: Ahmed Mohammed Alsadig Mohammed
- **Supervisor**: Dr. Fatimah Noni Muhamad
- **Purpose**: Replace manual attendance with QR Code-based automated system

## Development Principles

### 1. Phase-Based Development
- **NEVER skip phases**
- Complete current phase fully before proceeding
- Wait for explicit approval before moving to next phase
- Each phase must be production-quality

### 2. Code Quality Standards
- Always write clean, well-commented code
- Follow MVC architecture pattern
- Use meaningful variable and function names
- Document all functions with purpose and parameters
- Include inline comments for complex logic

### 3. Security Requirements
```php
// Password Hashing
password_hash($password, PASSWORD_BCRYPT);

// Prepared Statements (ALWAYS)
$stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
$stmt->execute([$email]);

// CSRF Protection
$_SESSION['csrf_token'] = bin2hex(random_bytes(32));

// Input Validation
$email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
```

### 4. Database Standards
- Use prepared statements (NEVER string concatenation)
- Define proper foreign keys and constraints
- Add indexes for performance
- Use transactions for related operations
- Include audit trail logging

### 5. Documentation Requirements
For every feature implemented, provide:
- **Architecture explanation**: Why this approach?
- **Database design**: Table structure and relationships
- **Security measures**: What protections are implemented?
- **Code comments**: Clear inline documentation
- **Usage instructions**: How to use the feature

## Technology Stack

### Frontend
- HTML5 (semantic markup)
- CSS3 (modern styling)
- Bootstrap 5 (responsive design)
- JavaScript (ES6+)
- AJAX (asynchronous operations)

### Backend
- PHP 8+ (strict types enabled)
- MVC Architecture
- PDO for database access
- PHP Sessions for authentication

### Database
- MySQL 8.0+
- InnoDB engine
- UTF8MB4 charset

### Development Environment
- Local development server (PHP built-in or Laragon)
- VS Code with PHP extensions
- Git for version control

### Libraries
- PHP QR Code library (phpqrcode)
- Chart.js for analytics
- TCPDF or FPDF for PDF generation
- PhpSpreadsheet for Excel export

## File Organization
```
project-root/
├── app/
│   ├── controllers/    # Business logic
│   ├── models/         # Database operations
│   └── views/          # UI templates
├── config/             # Configuration files
├── public/             # Web root
│   ├── index.php       # Entry point
│   └── assets/         # Static files
├── storage/            # Uploads, logs
├── database/           # SQL scripts
└── routes/             # Route definitions
```

## Naming Conventions

### PHP Files
- Controllers: `PascalCase` + `Controller.php` (e.g., `StudentController.php`)
- Models: `PascalCase` + `.php` (e.g., `Student.php`)
- Views: `lowercase-with-dashes.php` (e.g., `student-dashboard.php`)

### Database
- Tables: `lowercase_with_underscores` (e.g., `attendance_sessions`)
- Columns: `lowercase_with_underscores` (e.g., `created_at`)
- Foreign keys: `table_id` (e.g., `student_id`)

### CSS/JS
- Classes: `kebab-case` (e.g., `.student-card`)
- IDs: `camelCase` (e.g., `#studentList`)
- Functions: `camelCase` (e.g., `function validateSession()`)

## Error Handling
```php
// Always use try-catch for database operations
try {
    $pdo->beginTransaction();
    // operations
    $pdo->commit();
} catch (Exception $e) {
    $pdo->rollBack();
    error_log($e->getMessage());
    // User-friendly error message
}
```

## Session Management
```php
// Start session securely
session_start([
    'cookie_httponly' => true,
    'cookie_secure' => true,
    'cookie_samesite' => 'Strict'
]);

// Check authentication
if (!isset($_SESSION['user_id'])) {
    header('Location: /login');
    exit;
}

// Check role authorization
if ($_SESSION['role'] !== 'admin') {
    die('Unauthorized access');
}
```

## Git Commit Standards
- Use clear, descriptive commit messages
- Format: `[PHASE-X] Feature: Description`
- Example: `[PHASE-2] Setup: Configure MVC architecture`

## Testing Checklist
Before marking a phase complete:
- [ ] All features work as specified
- [ ] Security measures implemented
- [ ] Code is commented and clean
- [ ] Database relationships are correct
- [ ] No hardcoded values (use config)
- [ ] Error handling in place
- [ ] User-friendly error messages
- [ ] Tested all user roles
- [ ] Documentation updated
