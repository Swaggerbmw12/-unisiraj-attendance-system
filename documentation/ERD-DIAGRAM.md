# Entity Relationship Diagram (ERD)
# UniSIRAJ Automated Attendance System

## Visual ERD Representation

```
┌─────────────────┐
│     ROLES       │
│─────────────────│
│ PK id           │
│    name         │
│    description  │
│    created_at   │
└────────┬────────┘
         │
         │ 1
         │
         │ M
         ▼
┌─────────────────┐
│     USERS       │
│─────────────────│
│ PK id           │
│ FK role_id      │
│    email        │◄────────────────────┐
│    password     │                     │
│    is_active    │                     │
│    last_login   │                     │
│    created_at   │                     │
│    updated_at   │                     │
└────┬────────────┘                     │
     │                                   │
     ├───────────────────┬───────────────┤
     │ 1                 │ 1             │ M
     │                   │               │
     │ 1                 │ 1             │
     ▼                   ▼               ▼
┌────────────┐    ┌────────────┐  ┌──────────────┐
│  STUDENTS  │    │ LECTURERS  │  │ AUDIT_LOGS   │
│────────────│    │────────────│  │──────────────│
│ PK id      │    │ PK id      │  │ PK id        │
│ FK user_id │    │ FK user_id │  │ FK user_id   │
│ student_id │    │ staff_id   │  │ action       │
│ first_name │    │ first_name │  │ details      │
│ last_name  │    │ last_name  │  │ ip_address   │
│ phone      │    │ phone      │  │ user_agent   │
│ program    │    │ department │  │ created_at   │
│ year_study │    │ created_at │  └──────────────┘
│ created_at │    │ updated_at │
│ updated_at │    └──────┬─────┘
└──────┬─────┘           │
       │                 │ 1
       │ M               │
       │                 │ M
       │                 ▼
       │          ┌─────────────┐
       │          │   COURSES   │
       │          │─────────────│
       │          │ PK id       │
       │          │ FK lecturer │
       │          │ course_code │
       │          │ course_name │
       │          │ semester    │
       │          │ acad_year   │
       │          │ credits     │
       │          │ description │
       │          │ is_active   │
       │          │ created_at  │
       │          │ updated_at  │
       │          └──────┬──────┘
       │                 │
       │ M               │ M
       │                 │
       │                 │ 1
       ▼                 ▼
┌──────────────────────────────┐
│       ENROLLMENTS            │
│──────────────────────────────│
│ PK id                        │
│ FK student_id                │
│ FK course_id                 │
│    enrollment_date           │
│    status                    │
│    created_at                │
│    updated_at                │
└──────────────────────────────┘
       │
       │
       │                 ┌──────────────────────┐
       │                 │ ATTENDANCE_SESSIONS  │
       │                 │──────────────────────│
       │                 │ PK id                │
       │                 │ FK course_id         │
       │                 │ FK lecturer_id       │
       │                 │ session_name         │
       │                 │ session_date         │
       │                 │ start_time           │
       │                 │ end_time             │
       │                 │ token (UNIQUE)       │
       │                 │ expires_at           │
       │                 │ is_active            │
       │                 │ qr_code_path         │
       │                 │ created_at           │
       │                 │ updated_at           │
       │                 └────────┬─────────────┘
       │                          │ 1
       │                          │
       │                          │ M
       │                          ▼
       │                 ┌──────────────────────┐
       │                 │ ATTENDANCE_RECORDS   │
       │                 │──────────────────────│
       │                 │ PK id                │
       │                 │ FK session_id        │
       │                 │ FK student_id        │◄──────────┐
       │                 │ attendance_time      │           │
       │                 │ ip_address           │           │
       │                 │ user_agent           │           │
       │                 │ created_at           │           │
       │                 └──────────────────────┘           │
       │                                                     │
       └─────────────────────────────────────────────────────┘
                                    M
```

## Cardinality Legend
- **1** = One
- **M** = Many
- **PK** = Primary Key
- **FK** = Foreign Key
- **◄──** = Relationship direction

## Relationship Summary

### 1-to-Many Relationships

1. **roles → users** (1:M)
   - One role can be assigned to many users
   - Each user has exactly one role

2. **users → students** (1:1)
   - Each user can have one student profile
   - Each student profile belongs to one user

3. **users → lecturers** (1:1)
   - Each user can have one lecturer profile
   - Each lecturer profile belongs to one user

4. **users → audit_logs** (1:M)
   - One user can have many audit log entries
   - Each log entry belongs to one user

5. **lecturers → courses** (1:M)
   - One lecturer can teach many courses
   - Each course is taught by one lecturer

6. **courses → attendance_sessions** (1:M)
   - One course can have many attendance sessions
   - Each session belongs to one course

7. **lecturers → attendance_sessions** (1:M)
   - One lecturer can create many sessions
   - Each session is created by one lecturer

8. **attendance_sessions → attendance_records** (1:M)
   - One session can have many attendance records
   - Each record belongs to one session

9. **students → attendance_records** (1:M)
   - One student can have many attendance records
   - Each record belongs to one student

### Many-to-Many Relationships

1. **students ↔ courses** (M:M) via enrollments
   - Students can enroll in multiple courses
   - Courses can have multiple students
   - Junction table: enrollments

## Key Constraints

### Unique Constraints
- `users.email` - No duplicate emails
- `students.student_id` - No duplicate student IDs
- `lecturers.staff_id` - No duplicate staff IDs
- `courses.course_code` - No duplicate course codes
- `attendance_sessions.token` - No duplicate QR tokens
- `enrollments(student_id, course_id)` - No duplicate enrollments
- `attendance_records(session_id, student_id)` - No duplicate attendance

### Cascade Rules
- **ON DELETE CASCADE**: Related records are deleted when parent is deleted
  - users → students, lecturers
  - courses → enrollments, attendance_sessions
  - attendance_sessions → attendance_records
  
- **ON DELETE SET NULL**: Foreign key set to NULL when parent is deleted
  - courses.lecturer_id (if lecturer deleted)
  - audit_logs.user_id (if user deleted)
  
- **ON DELETE RESTRICT**: Prevents deletion if related records exist
  - users.role_id (cannot delete role if users exist)

### Indexes
All foreign keys are indexed for query performance:
- `idx_users_role_id`
- `idx_students_user_id`
- `idx_lecturers_user_id`
- `idx_courses_lecturer_id`
- `idx_enrollments_student_id`
- `idx_enrollments_course_id`
- `idx_sessions_course_id`
- `idx_sessions_lecturer_id`
- `idx_records_session_id`
- `idx_records_student_id`
- `idx_audit_user_id`

Additional indexes on frequently queried columns:
- `idx_users_email`
- `idx_students_student_id`
- `idx_lecturers_staff_id`
- `idx_courses_code`
- `idx_sessions_token`
- `idx_sessions_date`
- `idx_audit_action`
- `idx_audit_created_at`

## Database Views

### 1. view_students_with_users
Combines student and user information for easy querying.

### 2. view_lecturers_with_users
Combines lecturer and user information for easy querying.

### 3. view_course_enrollments
Shows course enrollment summary with student count.

### 4. view_attendance_sessions_summary
Shows attendance session details with attendance statistics.

## Stored Procedures

### 1. sp_get_student_attendance_percentage
Calculates attendance percentage for a student in a specific course.

**Parameters:**
- `p_student_id` (INT)
- `p_course_id` (INT)

**Returns:**
- total_sessions
- attended_sessions
- percentage

## Design Decisions

### Normalization
- Database follows 3rd Normal Form (3NF)
- Eliminates data redundancy
- Ensures data integrity

### Security
- Passwords stored as bcrypt hashes
- Session tokens are 64-character cryptographic strings
- IP address and user agent logged for audit trail

### Scalability
- Proper indexing for performance
- Views for common complex queries
- Stored procedures for business logic

### Data Integrity
- Foreign key constraints enforce relationships
- Unique constraints prevent duplicates
- Cascade rules maintain consistency
