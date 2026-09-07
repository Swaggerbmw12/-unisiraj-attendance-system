---
inclusion: auto
---

# Database Guidelines - UniSIRAJ Attendance System

## Database Design Principles

### 1. Naming Conventions
- Tables: `lowercase_with_underscores`, plural nouns (e.g., `students`, `attendance_sessions`)
- Columns: `lowercase_with_underscores` (e.g., `first_name`, `created_at`)
- Primary Keys: `id` (auto-increment)
- Foreign Keys: `table_singular_id` (e.g., `student_id`, `course_id`)
- Indexes: `idx_table_column` (e.g., `idx_users_email`)

### 2. Standard Columns
Every table should include:
```sql
id INT PRIMARY KEY AUTO_INCREMENT,
created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
```

For soft deletes (when applicable):
```sql
deleted_at TIMESTAMP NULL DEFAULT NULL
```

### 3. Data Types
- IDs: `INT UNSIGNED AUTO_INCREMENT`
- Text (short): `VARCHAR(255)`
- Text (long): `TEXT`
- Email: `VARCHAR(255)` with UNIQUE index
- Passwords: `VARCHAR(255)` (for bcrypt hashes)
- Dates: `DATE`
- Timestamps: `TIMESTAMP` or `DATETIME`
- Status/Enum: `ENUM('value1', 'value2')` or `VARCHAR` with CHECK constraint
- Boolean: `TINYINT(1)` or `BOOLEAN`
- Decimal: `DECIMAL(10,2)` for percentages/money

### 4. Indexes
Add indexes for:
- Foreign keys
- Columns used in WHERE clauses
- Columns used in JOIN operations
- Columns used in ORDER BY
- Unique constraints (email, username, tokens)

```sql
-- Example Indexes
CREATE INDEX idx_students_user_id ON students(user_id);
CREATE INDEX idx_attendance_records_session_id ON attendance_records(session_id);
CREATE INDEX idx_attendance_records_student_id ON attendance_records(student_id);
CREATE UNIQUE INDEX idx_users_email ON users(email);
```

### 5. Foreign Key Constraints
Always define foreign key relationships:

```sql
CONSTRAINT fk_students_user 
    FOREIGN KEY (user_id) REFERENCES users(id) 
    ON DELETE CASCADE ON UPDATE CASCADE,

CONSTRAINT fk_attendance_records_session 
    FOREIGN KEY (session_id) REFERENCES attendance_sessions(id) 
    ON DELETE CASCADE ON UPDATE CASCADE,

CONSTRAINT fk_attendance_records_student 
    FOREIGN KEY (student_id) REFERENCES students(id) 
    ON DELETE CASCADE ON UPDATE CASCADE
```

**Cascade Rules:**
- `ON DELETE CASCADE`: Delete related records when parent is deleted
- `ON DELETE SET NULL`: Set foreign key to NULL when parent is deleted
- `ON DELETE RESTRICT`: Prevent deletion if related records exist
- `ON UPDATE CASCADE`: Update foreign key when parent key changes

### 6. Required Tables Structure

#### users
```sql
CREATE TABLE users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    role_id INT UNSIGNED NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    is_active TINYINT(1) DEFAULT 1,
    last_login TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_users_role FOREIGN KEY (role_id) 
        REFERENCES roles(id) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE INDEX idx_users_email ON users(email);
CREATE INDEX idx_users_role_id ON users(role_id);
```

#### roles
```sql
CREATE TABLE roles (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL UNIQUE,
    description VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert default roles
INSERT INTO roles (name, description) VALUES
('admin', 'System Administrator'),
('lecturer', 'Course Lecturer'),
('student', 'Student');
```

#### students
```sql
CREATE TABLE students (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NOT NULL UNIQUE,
    student_id VARCHAR(50) NOT NULL UNIQUE,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    phone VARCHAR(20),
    program VARCHAR(100),
    year_of_study INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_students_user FOREIGN KEY (user_id) 
        REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE INDEX idx_students_user_id ON students(user_id);
CREATE INDEX idx_students_student_id ON students(student_id);
```

#### lecturers
```sql
CREATE TABLE lecturers (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NOT NULL UNIQUE,
    staff_id VARCHAR(50) NOT NULL UNIQUE,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    phone VARCHAR(20),
    department VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_lecturers_user FOREIGN KEY (user_id) 
        REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE INDEX idx_lecturers_user_id ON lecturers(user_id);
CREATE INDEX idx_lecturers_staff_id ON lecturers(staff_id);
```

#### courses
```sql
CREATE TABLE courses (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    course_code VARCHAR(20) NOT NULL UNIQUE,
    course_name VARCHAR(255) NOT NULL,
    lecturer_id INT UNSIGNED,
    semester VARCHAR(20),
    academic_year VARCHAR(20),
    credits INT,
    description TEXT,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_courses_lecturer FOREIGN KEY (lecturer_id) 
        REFERENCES lecturers(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE INDEX idx_courses_lecturer_id ON courses(lecturer_id);
CREATE INDEX idx_courses_code ON courses(course_code);
```

#### enrollments
```sql
CREATE TABLE enrollments (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    student_id INT UNSIGNED NOT NULL,
    course_id INT UNSIGNED NOT NULL,
    enrollment_date DATE NOT NULL,
    status ENUM('active', 'dropped', 'completed') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_enrollments_student FOREIGN KEY (student_id) 
        REFERENCES students(id) ON DELETE CASCADE,
    CONSTRAINT fk_enrollments_course FOREIGN KEY (course_id) 
        REFERENCES courses(id) ON DELETE CASCADE,
    UNIQUE KEY unique_enrollment (student_id, course_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE INDEX idx_enrollments_student_id ON enrollments(student_id);
CREATE INDEX idx_enrollments_course_id ON enrollments(course_id);
```

#### attendance_sessions
```sql
CREATE TABLE attendance_sessions (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    course_id INT UNSIGNED NOT NULL,
    lecturer_id INT UNSIGNED NOT NULL,
    session_name VARCHAR(255),
    session_date DATE NOT NULL,
    start_time TIME NOT NULL,
    end_time TIME,
    token VARCHAR(64) NOT NULL UNIQUE,
    expires_at TIMESTAMP NOT NULL,
    is_active TINYINT(1) DEFAULT 1,
    qr_code_path VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_sessions_course FOREIGN KEY (course_id) 
        REFERENCES courses(id) ON DELETE CASCADE,
    CONSTRAINT fk_sessions_lecturer FOREIGN KEY (lecturer_id) 
        REFERENCES lecturers(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE INDEX idx_sessions_course_id ON attendance_sessions(course_id);
CREATE INDEX idx_sessions_lecturer_id ON attendance_sessions(lecturer_id);
CREATE INDEX idx_sessions_token ON attendance_sessions(token);
CREATE INDEX idx_sessions_date ON attendance_sessions(session_date);
```

#### attendance_records
```sql
CREATE TABLE attendance_records (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    session_id INT UNSIGNED NOT NULL,
    student_id INT UNSIGNED NOT NULL,
    attendance_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    ip_address VARCHAR(45),
    user_agent TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_records_session FOREIGN KEY (session_id) 
        REFERENCES attendance_sessions(id) ON DELETE CASCADE,
    CONSTRAINT fk_records_student FOREIGN KEY (student_id) 
        REFERENCES students(id) ON DELETE CASCADE,
    UNIQUE KEY unique_attendance (session_id, student_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE INDEX idx_records_session_id ON attendance_records(session_id);
CREATE INDEX idx_records_student_id ON attendance_records(student_id);
CREATE INDEX idx_records_time ON attendance_records(attendance_time);
```

#### audit_logs
```sql
CREATE TABLE audit_logs (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED,
    action VARCHAR(100) NOT NULL,
    details JSON,
    ip_address VARCHAR(45),
    user_agent TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_audit_user FOREIGN KEY (user_id) 
        REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE INDEX idx_audit_user_id ON audit_logs(user_id);
CREATE INDEX idx_audit_action ON audit_logs(action);
CREATE INDEX idx_audit_created_at ON audit_logs(created_at);
```

### 7. PDO Connection Template

```php
// config/database.php
class Database {
    private $host = 'localhost';
    private $dbname = 'unisiraj_attendance';
    private $username = 'root';
    private $password = '';
    private $charset = 'utf8mb4';
    private $pdo;
    
    public function connect() {
        if ($this->pdo === null) {
            $dsn = "mysql:host={$this->host};dbname={$this->dbname};charset={$this->charset}";
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ];
            
            try {
                $this->pdo = new PDO($dsn, $this->username, $this->password, $options);
            } catch (PDOException $e) {
                error_log('Database Connection Error: ' . $e->getMessage());
                die('Database connection failed');
            }
        }
        
        return $this->pdo;
    }
}
```

### 8. Query Best Practices

```php
// ✅ GOOD: Use Prepared Statements
$stmt = $pdo->prepare("SELECT * FROM students WHERE id = ?");
$stmt->execute([$studentId]);

// ✅ GOOD: Named Parameters
$stmt = $pdo->prepare("INSERT INTO students (user_id, first_name, last_name) VALUES (:user_id, :first_name, :last_name)");
$stmt->execute([
    ':user_id' => $userId,
    ':first_name' => $firstName,
    ':last_name' => $lastName
]);

// ✅ GOOD: Transactions
try {
    $pdo->beginTransaction();
    // Multiple operations
    $pdo->commit();
} catch (Exception $e) {
    $pdo->rollBack();
    throw $e;
}

// ❌ BAD: String Concatenation
$query = "SELECT * FROM students WHERE id = $studentId"; // NEVER DO THIS
```
