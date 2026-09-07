# PHASE 1: SYSTEM ANALYSIS
# Automated Student Attendance System for UniSIRAJ

**Student**: Ahmed Mohammed Alsadig Mohammed  
**Supervisor**: Dr. Fatimah Noni Muhamad  
**Institution**: Universiti Islam Antarabangsa Tuanku Syed Sirajuddin (UniSIRAJ)

---

## 1. FUNCTIONAL REQUIREMENTS

### 1.1 Authentication & Authorization
- **FR-1.1**: System shall provide login functionality for three user roles (Admin, Lecturer, Student)
- **FR-1.2**: System shall authenticate users using email and password
- **FR-1.3**: System shall maintain secure sessions for authenticated users
- **FR-1.4**: System shall implement role-based access control (RBAC)
- **FR-1.5**: System shall automatically logout users after 30 minutes of inactivity
- **FR-1.6**: System shall redirect users to role-appropriate dashboards after login

### 1.2 Administrator Functions
- **FR-2.1**: Admin shall be able to create, view, update, and delete student records
- **FR-2.2**: Admin shall be able to create, view, update, and delete lecturer records
- **FR-2.3**: Admin shall be able to create, view, update, and delete course records
- **FR-2.4**: Admin shall be able to assign lecturers to courses
- **FR-2.5**: Admin shall be able to enroll students into courses
- **FR-2.6**: Admin shall be able to view all attendance sessions and records
- **FR-2.7**: Admin shall be able to generate system-wide reports
- **FR-2.8**: Admin shall be able to export reports to PDF and Excel formats
- **FR-2.9**: Admin shall be able to view analytics dashboard
- **FR-2.10**: Admin shall be able to view audit logs

### 1.3 Lecturer Functions
- **FR-3.1**: Lecturer shall be able to view assigned courses
- **FR-3.2**: Lecturer shall be able to create attendance sessions for assigned courses
- **FR-3.3**: Lecturer shall be able to generate unique QR codes for each session
- **FR-3.4**: Lecturer shall be able to view real-time attendance during active sessions
- **FR-3.5**: Lecturer shall be able to manually close attendance sessions
- **FR-3.6**: Lecturer shall be able to view attendance reports for their courses
- **FR-3.7**: Lecturer shall be able to export attendance reports to PDF and Excel
- **FR-3.8**: Lecturer shall be able to view enrolled students in their courses
- **FR-3.9**: Lecturer shall be able to view attendance statistics and trends

### 1.4 Student Functions
- **FR-4.1**: Student shall be able to view enrolled courses
- **FR-4.2**: Student shall be able to scan QR codes to record attendance
- **FR-4.3**: Student shall be able to view personal attendance history
- **FR-4.4**: Student shall be able to view attendance percentage per course
- **FR-4.5**: Student shall be able to view attendance status (present/absent) for each session
- **FR-4.6**: Student shall receive immediate confirmation after recording attendance

### 1.5 QR Code Attendance System
- **FR-5.1**: System shall generate unique QR codes for each attendance session
- **FR-5.2**: QR codes shall contain encrypted session tokens
- **FR-5.3**: QR codes shall expire after a defined time period (configurable, default 30 min)
- **FR-5.4**: System shall validate QR code authenticity before recording attendance
- **FR-5.5**: System shall prevent duplicate attendance for the same session
- **FR-5.6**: System shall verify student enrollment before recording attendance
- **FR-5.7**: System shall capture attendance timestamp, IP address, and user agent
- **FR-5.8**: System shall display success/error messages to students after scanning

### 1.6 Reporting Functions
- **FR-6.1**: System shall generate daily attendance reports
- **FR-6.2**: System shall generate weekly attendance reports
- **FR-6.3**: System shall generate monthly attendance reports
- **FR-6.4**: System shall generate semester attendance reports
- **FR-6.5**: System shall generate student-specific attendance reports
- **FR-6.6**: System shall generate course-specific attendance reports
- **FR-6.7**: System shall calculate attendance percentages automatically
- **FR-6.8**: System shall identify students with low attendance
- **FR-6.9**: System shall export reports to PDF format
- **FR-6.10**: System shall export reports to Excel format

### 1.7 Analytics Dashboard
- **FR-7.1**: System shall display attendance statistics with charts and graphs
- **FR-7.2**: System shall show attendance trends over time
- **FR-7.3**: System shall show course-wise attendance comparison
- **FR-7.4**: System shall identify and display students at risk (low attendance)
- **FR-7.5**: System shall show daily, weekly, and monthly attendance summaries
- **FR-7.6**: System shall provide filtering options by date range, course, and student

---

## 2. NON-FUNCTIONAL REQUIREMENTS

### 2.1 Security Requirements
- **NFR-1.1**: All passwords shall be hashed using bcrypt algorithm
- **NFR-1.2**: System shall use prepared statements for all database queries
- **NFR-1.3**: System shall implement CSRF protection on all forms
- **NFR-1.4**: System shall validate and sanitize all user inputs
- **NFR-1.5**: System shall use secure session management with HTTP-only cookies
- **NFR-1.6**: System shall log all critical actions in audit logs
- **NFR-1.7**: System shall prevent SQL injection attacks
- **NFR-1.8**: System shall prevent XSS (Cross-Site Scripting) attacks
- **NFR-1.9**: QR code tokens shall be cryptographically secure (64 characters minimum)

### 2.2 Performance Requirements
- **NFR-2.1**: System shall load pages within 3 seconds on standard broadband
- **NFR-2.2**: QR code generation shall complete within 2 seconds
- **NFR-2.3**: Attendance recording shall complete within 1 second
- **NFR-2.4**: System shall support at least 100 concurrent users
- **NFR-2.5**: Database queries shall be optimized with proper indexing
- **NFR-2.6**: System shall implement pagination for large data sets

### 2.3 Usability Requirements
- **NFR-3.1**: System shall have intuitive and user-friendly interface
- **NFR-3.2**: System shall be responsive and mobile-friendly (Bootstrap 5)
- **NFR-3.3**: Error messages shall be clear and user-friendly
- **NFR-3.4**: QR code scanning shall work on mobile devices
- **NFR-3.5**: Navigation shall be consistent across all pages
- **NFR-3.6**: Forms shall include proper validation and feedback

### 2.4 Reliability Requirements
- **NFR-4.1**: System shall have 99% uptime during operational hours
- **NFR-4.2**: System shall handle errors gracefully without crashes
- **NFR-4.3**: Database transactions shall ensure data integrity
- **NFR-4.4**: System shall implement database backups
- **NFR-4.5**: System shall log errors for debugging purposes

### 2.5 Maintainability Requirements
- **NFR-5.1**: Code shall follow MVC architecture pattern
- **NFR-5.2**: Code shall be well-commented and documented
- **NFR-5.3**: System shall use consistent naming conventions
- **NFR-5.4**: Database schema shall be properly normalized
- **NFR-5.5**: System shall be modular for easy updates

### 2.6 Compatibility Requirements
- **NFR-6.1**: System shall work on Chrome, Firefox, Safari, and Edge browsers
- **NFR-6.2**: System shall work on Windows, macOS, and Linux
- **NFR-6.3**: System shall be compatible with PHP 8.0+
- **NFR-6.4**: System shall be compatible with MySQL 8.0+
- **NFR-6.5**: Mobile QR scanning shall work on iOS and Android devices

---

## 3. USE CASES

### 3.1 UC-01: User Login
**Actor**: Admin / Lecturer / Student  
**Precondition**: User has valid credentials  
**Postcondition**: User is authenticated and redirected to role-appropriate dashboard

**Main Flow**:
1. User navigates to login page
2. User enters email and password
3. System validates credentials
4. System creates secure session
5. System redirects user based on role:
   - Admin → Admin Dashboard
   - Lecturer → Lecturer Dashboard
   - Student → Student Dashboard

**Alternative Flow**:
- If credentials invalid: Display error message "Invalid email or password"
- If account inactive: Display error message "Account is deactivated"

### 3.2 UC-02: Admin Manages Students
**Actor**: Administrator  
**Precondition**: Admin is logged in  
**Postcondition**: Student record is created/updated/deleted

**Main Flow**:
1. Admin navigates to Student Management
2. Admin clicks "Add New Student"
3. Admin fills student information form
4. System validates input data
5. System creates user account with hashed password
6. System creates student profile
7. System sends email with login credentials (optional)
8. System displays success message

**Alternative Flow**:
- If email already exists: Display error "Email already registered"
- If student ID already exists: Display error "Student ID already exists"

### 3.3 UC-03: Admin Enrolls Students in Courses
**Actor**: Administrator  
**Precondition**: Admin is logged in, students and courses exist  
**Postcondition**: Student is enrolled in course

**Main Flow**:
1. Admin navigates to Enrollment Management
2. Admin selects course
3. Admin searches and selects students
4. System validates enrollment (no duplicates)
5. System creates enrollment records
6. System displays success message

### 3.4 UC-04: Lecturer Creates Attendance Session
**Actor**: Lecturer  
**Precondition**: Lecturer is logged in and has assigned courses  
**Postcondition**: Attendance session is created with QR code

**Main Flow**:
1. Lecturer navigates to course page
2. Lecturer clicks "Create Attendance Session"
3. Lecturer enters session details (name, duration)
4. System generates unique session token (64-char cryptographic)
5. System calculates expiry time (current time + duration)
6. System generates QR code image containing token
7. System saves session to database
8. System displays QR code on screen
9. Students can now scan QR code to record attendance

**Alternative Flow**:
- If course has no enrolled students: Display warning message

### 3.5 UC-05: Student Records Attendance via QR Code
**Actor**: Student  
**Precondition**: Student is logged in, active session exists  
**Postcondition**: Attendance is recorded in database

**Main Flow**:
1. Student logs into mobile device
2. Student navigates to "Scan QR Code" page
3. Student scans QR code displayed by lecturer
4. System extracts session token from QR code
5. System validates session:
   - Session exists and is active
   - Session not expired
   - Student enrolled in course
   - No duplicate attendance
6. System records attendance with timestamp, IP, user agent
7. System displays success message "Attendance recorded successfully"

**Alternative Flows**:
- If session expired: Display error "Session has expired"
- If already attended: Display error "Attendance already recorded"
- If not enrolled: Display error "You are not enrolled in this course"
- If session invalid: Display error "Invalid QR code"

### 3.6 UC-06: Lecturer Monitors Live Attendance
**Actor**: Lecturer  
**Precondition**: Attendance session is active  
**Postcondition**: Lecturer views real-time attendance list

**Main Flow**:
1. Lecturer clicks "View Live Attendance" on session
2. System displays enrolled students list
3. System highlights students who have recorded attendance (green)
4. System shows attendance count and percentage
5. Page auto-refreshes every 10 seconds (AJAX)

### 3.7 UC-07: Generate Attendance Report
**Actor**: Admin / Lecturer  
**Precondition**: User is logged in, attendance data exists  
**Postcondition**: Report is generated and displayed/downloaded

**Main Flow**:
1. User navigates to Reports section
2. User selects report type (daily/weekly/monthly/student/course)
3. User selects date range and filters
4. User clicks "Generate Report"
5. System retrieves data from database
6. System calculates attendance percentages
7. System displays report on screen
8. User can export to PDF or Excel

### 3.8 UC-08: View Analytics Dashboard
**Actor**: Admin / Lecturer  
**Precondition**: User is logged in, attendance data exists  
**Postcondition**: Dashboard displays charts and statistics

**Main Flow**:
1. User navigates to Analytics Dashboard
2. System retrieves attendance statistics
3. System generates charts using Chart.js:
   - Attendance percentage pie chart
   - Attendance trends line chart
   - Course comparison bar chart
   - At-risk students list
4. System displays dashboard with interactive charts

---

## 4. USER STORIES

### Administrator Stories
- **US-01**: As an admin, I want to manage student accounts so that I can maintain accurate student records
- **US-02**: As an admin, I want to manage lecturer accounts so that I can control who teaches courses
- **US-03**: As an admin, I want to manage courses so that I can organize the academic structure
- **US-04**: As an admin, I want to enroll students in courses so that they can attend classes
- **US-05**: As an admin, I want to view system-wide reports so that I can monitor attendance trends
- **US-06**: As an admin, I want to export reports so that I can share data with management
- **US-07**: As an admin, I want to view audit logs so that I can track system activities

### Lecturer Stories
- **US-08**: As a lecturer, I want to create attendance sessions so that I can track class attendance
- **US-09**: As a lecturer, I want to generate QR codes so that students can easily record attendance
- **US-10**: As a lecturer, I want to monitor live attendance so that I know who is present in class
- **US-11**: As a lecturer, I want to view attendance reports so that I can identify struggling students
- **US-12**: As a lecturer, I want to export attendance data so that I can keep records
- **US-13**: As a lecturer, I want to view attendance statistics so that I can analyze class performance

### Student Stories
- **US-14**: As a student, I want to scan QR codes so that I can quickly record my attendance
- **US-15**: As a student, I want to view my attendance history so that I can track my records
- **US-16**: As a student, I want to see my attendance percentage so that I know my standing
- **US-17**: As a student, I want immediate confirmation so that I know my attendance was recorded
- **US-18**: As a student, I want to view my enrolled courses so that I know which classes I'm in

---

## 5. SYSTEM ARCHITECTURE

### 5.1 Architecture Pattern: MVC (Model-View-Controller)

**Why MVC?**
- **Separation of Concerns**: Business logic, data, and presentation are separated
- **Maintainability**: Easy to update and modify components independently
- **Scalability**: Can add features without affecting existing code
- **Testability**: Components can be tested in isolation
- **Industry Standard**: Widely used and understood pattern

### 5.2 Architecture Layers

```
┌─────────────────────────────────────────────┐
│         Presentation Layer (Views)          │
│  HTML, CSS, JavaScript, Bootstrap, AJAX     │
└──────────────────┬──────────────────────────┘
                   │
┌──────────────────▼──────────────────────────┐
│       Application Layer (Controllers)       │
│  Business Logic, Validation, Routing        │
└──────────────────┬──────────────────────────┘
                   │
┌──────────────────▼──────────────────────────┐
│         Data Layer (Models)                 │
│  Database Operations, Data Validation       │
└──────────────────┬──────────────────────────┘
                   │
┌──────────────────▼──────────────────────────┐
│         Database Layer (MySQL)              │
│  Data Storage and Retrieval                 │
└─────────────────────────────────────────────┘
```

### 5.3 System Components

**1. Authentication Module**
- Login/Logout functionality
- Session management
- Password reset
- Role-based access control

**2. User Management Module**
- Student CRUD operations
- Lecturer CRUD operations
- User profile management
- Role assignment

**3. Course Management Module**
- Course CRUD operations
- Course assignment to lecturers
- Student enrollment

**4. QR Attendance Module**
- Session creation
- QR code generation
- QR code validation
- Attendance recording
- Duplicate prevention

**5. Reporting Module**
- Report generation (multiple types)
- PDF export
- Excel export
- Data filtering

**6. Analytics Module**
- Statistical calculations
- Chart generation
- Trend analysis

**7. Audit Logging Module**
- Activity tracking
- Security event logging

### 5.4 Technology Stack Rationale

**Frontend Technologies:**
- **HTML5**: Modern semantic markup, form validation
- **CSS3**: Advanced styling, animations, transitions
- **Bootstrap 5**: Responsive design, mobile-first, pre-built components
- **JavaScript (ES6+)**: Client-side interactivity, form validation
- **AJAX**: Asynchronous operations without page reload

**Backend Technologies:**
- **PHP 8+**: 
  - Modern language features (typed properties, attributes)
  - Performance improvements
  - Better error handling
  - Free and open source
- **MVC Architecture**: Organized, maintainable code structure

**Database:**
- **MySQL 8.0+**:
  - Reliable and proven
  - Excellent performance
  - Strong ACID compliance
  - JSON support for audit logs
  - Free and open source

**Development Environment:**
- **Localhost**: Safe development environment
- **PHP Built-in Server / Laragon**: Quick setup, no complex configuration
- **VS Code**: Modern IDE with PHP extensions
- **Git**: Version control for tracking changes

**Libraries:**
- **phpqrcode**: QR code generation
- **Chart.js**: Interactive charts and graphs
- **TCPDF/FPDF**: PDF generation
- **PhpSpreadsheet**: Excel export

---

## 6. DATABASE DESIGN

### 6.1 Entity Relationship Diagram (ERD) - Text Representation

```
ROLES (1) ──────< (M) USERS
                        │
                        ├──< (1) STUDENTS (1) ───< (M) ENROLLMENTS ──── (M) > COURSES
                        │                   │
                        │                   └───< (M) ATTENDANCE_RECORDS
                        │
                        └──< (1) LECTURERS (1) ──< (M) COURSES
                                            │
                                            └───< (M) ATTENDANCE_SESSIONS (1) ──< (M) ATTENDANCE_RECORDS

USERS (M) ──────< (M) AUDIT_LOGS
```

### 6.2 Database Tables Overview

**Core Tables:**
1. **roles** - Defines user roles (Admin, Lecturer, Student)
2. **users** - Authentication and user accounts
3. **students** - Student profile information
4. **lecturers** - Lecturer profile information
5. **courses** - Course information
6. **enrollments** - Student-Course relationships
7. **attendance_sessions** - QR attendance sessions
8. **attendance_records** - Individual attendance records
9. **audit_logs** - System activity logs

### 6.3 Table Relationships Explained

**users ↔ roles** (Many-to-One)
- Each user has one role
- One role can be assigned to many users
- Foreign Key: `users.role_id` → `roles.id`

**users ↔ students** (One-to-One)
- Each user can have one student profile
- Each student profile belongs to one user
- Foreign Key: `students.user_id` → `users.id`

**users ↔ lecturers** (One-to-One)
- Each user can have one lecturer profile
- Each lecturer profile belongs to one user
- Foreign Key: `lecturers.user_id` → `users.id`

**lecturers ↔ courses** (One-to-Many)
- Each lecturer can teach multiple courses
- Each course is taught by one lecturer
- Foreign Key: `courses.lecturer_id` → `lecturers.id`

**students ↔ courses** (Many-to-Many via enrollments)
- Students can enroll in multiple courses
- Courses can have multiple students
- Junction Table: `enrollments`
- Foreign Keys: 
  - `enrollments.student_id` → `students.id`
  - `enrollments.course_id` → `courses.id`

**courses ↔ attendance_sessions** (One-to-Many)
- Each course can have multiple attendance sessions
- Each session belongs to one course
- Foreign Key: `attendance_sessions.course_id` → `courses.id`

**lecturers ↔ attendance_sessions** (One-to-Many)
- Each lecturer can create multiple sessions
- Each session is created by one lecturer
- Foreign Key: `attendance_sessions.lecturer_id` → `lecturers.id`

**attendance_sessions ↔ attendance_records** (One-to-Many)
- Each session can have multiple attendance records
- Each record belongs to one session
- Foreign Key: `attendance_records.session_id` → `attendance_sessions.id`

**students ↔ attendance_records** (One-to-Many)
- Each student can have multiple attendance records
- Each record belongs to one student
- Foreign Key: `attendance_records.student_id` → `students.id`

**users ↔ audit_logs** (One-to-Many)
- Each user can have multiple audit log entries
- Each log entry belongs to one user
- Foreign Key: `audit_logs.user_id` → `users.id`

### 6.4 Key Design Decisions

**1. Normalized Database Structure**
- 3rd Normal Form (3NF) to eliminate redundancy
- Separate tables for users, students, and lecturers
- Junction table for many-to-many relationships

**2. Cascading Deletes**
- When user is deleted, related student/lecturer profile is deleted
- When session is deleted, related attendance records are deleted
- Prevents orphaned records

**3. Unique Constraints**
- Email must be unique across all users
- Student ID must be unique
- Staff ID must be unique
- Session tokens must be unique
- Prevents duplicate entries

**4. Indexes for Performance**
- Foreign key columns are indexed
- Frequently searched columns (email, student_id, token) are indexed
- Improves query performance

**5. Audit Trail**
- All critical actions are logged in audit_logs table
- Stores user_id, action, details (JSON), IP address, user agent
- Enables security monitoring and troubleshooting

**6. Timestamps**
- All tables have `created_at` and `updated_at` columns
- Automatic timestamp management
- Enables tracking when records were created/modified

---

## 7. SYSTEM ARCHITECTURE DIAGRAM

```
┌─────────────────────────────────────────────────────────────────┐
│                         PRESENTATION LAYER                      │
│  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐         │
│  │    Admin     │  │   Lecturer   │  │   Student    │         │
│  │  Dashboard   │  │  Dashboard   │  │  Dashboard   │         │
│  └──────────────┘  └──────────────┘  └──────────────┘         │
│         │                  │                 │                  │
│         └──────────────────┴─────────────────┘                  │
│                            │                                     │
└────────────────────────────┼─────────────────────────────────────┘
                             │
┌────────────────────────────┼─────────────────────────────────────┐
│                  APPLICATION LAYER (CONTROLLERS)                │
│  ┌──────────────────────────┼──────────────────────────┐       │
│  │        Router/Front Controller (index.php)          │       │
│  └──────────────────────────┬──────────────────────────┘       │
│                             │                                    │
│  ┌──────────────┬───────────┴────────┬─────────────────┐       │
│  │   Auth       │   Admin            │   QR Attendance │       │
│  │ Controller   │ Controllers        │   Controller    │       │
│  └──────────────┴────────────────────┴─────────────────┘       │
│  ┌──────────────┬────────────────────┬─────────────────┐       │
│  │  Lecturer    │   Student          │   Report        │       │
│  │ Controller   │ Controller         │   Controller    │       │
│  └──────────────┴────────────────────┴─────────────────┘       │
└────────────────────────────┬─────────────────────────────────────┘
                             │
┌────────────────────────────┼─────────────────────────────────────┐
│                     DATA LAYER (MODELS)                          │
│  ┌─────────────┬──────────────┬─────────────┬──────────────┐    │
│  │    User     │   Student    │  Lecturer   │   Course     │    │
│  │   Model     │   Model      │   Model     │   Model      │    │
│  └─────────────┴──────────────┴─────────────┴──────────────┘    │
│  ┌─────────────┬──────────────┬─────────────┬──────────────┐    │
