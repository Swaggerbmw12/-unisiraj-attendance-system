<?php

require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'Course.php';
require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'Student.php';

/**
 * Department Controller
 * Handles department and intake management
 */
class DepartmentController extends BaseController
{
    private $courseModel;
    private $studentModel;
    
    public function __construct()
    {
        parent::__construct();
        $this->courseModel = new Course();
        $this->studentModel = new Student();
    }
    /**
     * Display Computer Science department with intakes
     */
    public function computerScience()
    {
        $this->requireAuth(['admin']);
        
        // Mock data for intakes - will be database-driven later
        $intakes = [
            [
                'id' => 1,
                'name' => 'Intake February 2023',
                'slug' => 'feb-2023',
                'start_date' => '2023-02-01',
                'student_count' => 1,
                'course_count' => 2,
                'status' => 'active'
            ],
            [
                'id' => 2,
                'name' => 'Intake September 2023',
                'slug' => 'sep-2023',
                'start_date' => '2023-09-01',
                'student_count' => 0,
                'course_count' => 0,
                'status' => 'active'
            ]
        ];
        
        $this->render('admin/departments/computer-science', [
            'title' => 'Computer Science Department',
            'intakes' => $intakes
        ], 'layouts/admin-sidebar');
    }
    
    /**
     * Display intake details (courses and students)
     */
    public function intake($department, $intakeSlug)
    {
        $this->requireAuth(['admin']);
        
        // Map intake slugs to metadata
        $intakeMetadata = [
            'feb-2023' => [
                'name' => 'Intake February 2023',
                'slug' => 'feb-2023',
                'start_date' => '2023-02-01',
                'department' => 'Computer Science'
            ],
            'sep-2023' => [
                'name' => 'Intake September 2023',
                'slug' => 'sep-2023',
                'start_date' => '2023-09-01',
                'department' => 'Computer Science'
            ]
        ];
        
        $intake = $intakeMetadata[$intakeSlug] ?? null;
        
        if (!$intake) {
            flash('error', 'Intake not found');
            redirect('admin/departments/computer-science');
            return;
        }
        
        // Fetch real courses from database
        $coursesResult = $this->courseModel->getAll(1, 100); // Get all courses
        $allCourses = $coursesResult['data'];
        
        // Format courses for display
        $courses = [];
        foreach ($allCourses as $course) {
            $courses[] = [
                'id' => $course['id'],
                'course_code' => $course['course_code'],
                'course_name' => $course['course_name'],
                'lecturer' => $course['lecturer_name'] ?? 'Not Assigned',
                'credits' => $course['credits'] ?? 3,
                'enrolled_students' => $course['enrolled_students'] ?? 0
            ];
        }
        
        // Fetch real students from database
        $studentsResult = $this->studentModel->getAll(1, 100); // Get all students
        $allStudents = $studentsResult['data'];
        
        // Format students for display
        $students = [];
        foreach ($allStudents as $student) {
            $students[] = [
                'id' => $student['id'],
                'name' => trim($student['first_name'] . ' ' . $student['last_name']),
                'matric_no' => $student['student_id'],
                'email' => $student['email'],
                'program' => $student['program'] ?? 'Computer Science',
                'year' => 'Year ' . ($student['year_of_study'] ?? 1),
                'status' => $student['is_active'] ? 'active' : 'inactive'
            ];
        }
        
        $intake['courses'] = $courses;
        $intake['students'] = $students;
        
        $this->render('admin/intakes/detail', [
            'title' => $intake['name'] . ' - ' . $intake['department'],
            'intake' => $intake
        ], 'layouts/admin-sidebar');
    }
    
    /**
     * Add Course to Intake
     */
    public function addCourse()
    {
        $this->requireAuth(['admin']);
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $courseCode = trim($_POST['course_code'] ?? '');
            $courseName = trim($_POST['course_name'] ?? '');
            $lecturerName = trim($_POST['lecturer_name'] ?? '');
            $credits = !empty($_POST['credits']) ? intval($_POST['credits']) : 3;
            $semester = trim($_POST['semester'] ?? '');
            $academicYear = trim($_POST['academic_year'] ?? '');
            $intakeSlug = $_POST['intake_slug'] ?? 'feb-2023';
            
            error_log("=== ADD COURSE ATTEMPT ===");
            error_log("Course Code: $courseCode");
            error_log("Course Name: $courseName");
            error_log("Lecturer: $lecturerName");
            
            // Validation
            if (empty($courseCode) || empty($courseName)) {
                error_log("ERROR: Course code or name is empty");
                flash('error', 'Course code and name are required');
                redirect("admin/intakes/computer-science/$intakeSlug");
                return;
            }
            
            // Check if course code already exists
            if ($this->courseModel->courseCodeExists($courseCode)) {
                error_log("ERROR: Course code $courseCode already exists");
                flash('error', "Course code '$courseCode' already exists. Please use a different code.");
                redirect("admin/intakes/computer-science/$intakeSlug");
                return;
            }
            
            // Create course
            error_log("Creating course with code: $courseCode");
            $courseId = $this->courseModel->create([
                'course_code' => $courseCode,
                'course_name' => $courseName,
                'lecturer_id' => null,
                'lecturer_name' => !empty($lecturerName) ? $lecturerName : null,
                'credits' => $credits,
                'semester' => !empty($semester) ? $semester : null,
                'academic_year' => !empty($academicYear) ? $academicYear : null,
                'is_active' => 1
            ]);
            
            if ($courseId) {
                error_log("SUCCESS: Course created with ID: $courseId");
                flash('success', 'Course added successfully');
            } else {
                error_log("ERROR: Failed to create course");
                flash('error', 'Failed to add course. Please check the logs.');
            }
            
            redirect("admin/intakes/computer-science/$intakeSlug");
        } else {
            redirect("admin/intakes/computer-science/feb-2023");
        }
    }
    
    /**
     * Update Course
     */
    public function updateCourse()
    {
        $this->requireAuth(['admin']);
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $courseId = intval($_POST['course_id'] ?? 0);
            $courseCode = trim($_POST['course_code'] ?? '');
            $courseName = trim($_POST['course_name'] ?? '');
            $lecturerName = trim($_POST['lecturer_name'] ?? '');
            $credits = !empty($_POST['credits']) ? intval($_POST['credits']) : 3;
            $intakeSlug = $_POST['intake_slug'] ?? 'feb-2023';
            
            // Validation
            if ($courseId <= 0 || empty($courseCode) || empty($courseName)) {
                flash('error', 'Invalid course data');
                redirect("admin/intakes/computer-science/$intakeSlug");
                return;
            }
            
            // Check if course exists
            $course = $this->courseModel->getById($courseId);
            if (!$course) {
                flash('error', 'Course not found');
                redirect("admin/intakes/computer-science/$intakeSlug");
                return;
            }
            
            // Update course
            $success = $this->courseModel->update($courseId, [
                'course_name' => $courseName,
                'lecturer_id' => null,
                'lecturer_name' => !empty($lecturerName) ? $lecturerName : null,
                'credits' => $credits,
                'semester' => $course['semester'],
                'academic_year' => $course['academic_year'],
                'is_active' => 1
            ]);
            
            if ($success) {
                flash('success', 'Course updated successfully');
            } else {
                flash('error', 'Failed to update course');
            }
            
            redirect("admin/intakes/computer-science/$intakeSlug");
        }
    }
    
    /**
     * Delete Course
     */
    public function deleteCourse()
    {
        $this->requireAuth(['admin']);
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $courseId = intval($_GET['id'] ?? 0);
            $intakeSlug = $_GET['intake_slug'] ?? 'feb-2023';
            
            if ($courseId <= 0) {
                flash('error', 'Invalid course ID');
                redirect("admin/intakes/computer-science/$intakeSlug");
                return;
            }
            
            // Check if course exists
            $course = $this->courseModel->getById($courseId);
            if (!$course) {
                flash('error', 'Course not found');
                redirect("admin/intakes/computer-science/$intakeSlug");
                return;
            }
            
            // Delete course
            $success = $this->courseModel->delete($courseId);
            
            if ($success) {
                flash('success', 'Course deleted successfully');
            } else {
                flash('error', 'Failed to delete course. It may have associated records.');
            }
            
            redirect("admin/intakes/computer-science/$intakeSlug");
        }
    }
    
    /**
     * Add Student to Intake
     */
    public function addStudent()
    {
        $this->requireAuth(['admin']);
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name'] ?? '');
            $matricNo = trim($_POST['matric_no'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $program = trim($_POST['program'] ?? 'Computer Science');
            $phone = trim($_POST['phone'] ?? '');
            $password = trim($_POST['password'] ?? '');
            $intakeSlug = $_POST['intake_slug'] ?? 'feb-2023';
            
            error_log("=== ADD STUDENT ATTEMPT ===");
            error_log("Name: $name");
            error_log("Matric No: $matricNo");
            error_log("Email: $email");
            error_log("Program: $program");
            error_log("Phone: $phone");
            
            // Validation
            if (empty($name) || empty($matricNo) || empty($email) || empty($password)) {
                error_log("ERROR: Required fields are empty");
                flash('error', 'All required fields must be filled');
                redirect("admin/intakes/computer-science/$intakeSlug");
                return;
            }
            
            // Validate email format
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                error_log("ERROR: Invalid email format: $email");
                flash('error', 'Invalid email format');
                redirect("admin/intakes/computer-science/$intakeSlug");
                return;
            }
            
            // Check if student ID already exists
            if ($this->studentModel->studentIdExists($matricNo)) {
                error_log("ERROR: Matric number $matricNo already exists");
                flash('error', "Matric number '$matricNo' already exists");
                redirect("admin/intakes/computer-science/$intakeSlug");
                return;
            }
            
            // Split name into first and last name
            $nameParts = explode(' ', $name, 2);
            $firstName = $nameParts[0];
            $lastName = $nameParts[1] ?? '';
            
            error_log("Split name - First: $firstName, Last: $lastName");
            
            // Create student
            try {
                $studentId = $this->studentModel->create([
                    'student_id' => $matricNo,
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'email' => $email,
                    'program' => $program,
                    'phone' => $phone,
                    'password' => $password,
                    'year_of_study' => 1
                ]);
                
                if ($studentId) {
                    error_log("SUCCESS: Student created with ID: $studentId");
                    flash('success', 'Student added successfully');
                } else {
                    error_log("ERROR: Failed to create student - studentModel->create returned false");
                    flash('error', 'Failed to add student. Please check the logs.');
                }
            } catch (Exception $e) {
                error_log("EXCEPTION: Student creation error - " . $e->getMessage());
                flash('error', 'Error: ' . $e->getMessage());
            }
            
            redirect("admin/intakes/computer-science/$intakeSlug");
        } else {
            // GET request - redirect back
            redirect("admin/intakes/computer-science/feb-2023");
        }
    }
    
    /**
     * Update Student
     */
    public function updateStudent()
    {
        $this->requireAuth(['admin']);
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $studentId = intval($_POST['student_id'] ?? 0);
            $name = trim($_POST['name'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $program = trim($_POST['program'] ?? 'Computer Science');
            $intakeSlug = $_POST['intake_slug'] ?? 'feb-2023';
            
            // Log for debugging
            error_log("=== UPDATE STUDENT ATTEMPT ===");
            error_log("Student ID: $studentId");
            error_log("Name: $name");
            error_log("Email: $email");
            error_log("Program: $program");
            
            // Validation
            if ($studentId <= 0 || empty($name) || empty($email)) {
                error_log("ERROR: Invalid student data - ID: $studentId, Name: $name, Email: $email");
                flash('error', 'Invalid student data');
                redirect("admin/intakes/computer-science/$intakeSlug");
                return;
            }
            
            // Validate email format
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                error_log("ERROR: Invalid email format: $email");
                flash('error', 'Invalid email format');
                redirect("admin/intakes/computer-science/$intakeSlug");
                return;
            }
            
            // Check if student exists
            $student = $this->studentModel->getById($studentId);
            if (!$student) {
                error_log("ERROR: Student not found with ID: $studentId");
                flash('error', 'Student not found');
                redirect("admin/intakes/computer-science/$intakeSlug");
                return;
            }
            
            error_log("Found student: {$student['student_id']} - {$student['first_name']} {$student['last_name']}");
            
            // Split name into first and last name
            $nameParts = explode(' ', $name, 2);
            $firstName = $nameParts[0];
            $lastName = $nameParts[1] ?? '';
            
            // Update student
            try {
                $success = $this->studentModel->update($studentId, [
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'email' => $email,
                    'program' => $program,
                    'phone' => $student['phone'],
                    'year_of_study' => $student['year_of_study']
                ]);
                
                if ($success) {
                    error_log("SUCCESS: Student updated successfully");
                    flash('success', 'Student updated successfully');
                } else {
                    error_log("ERROR: Failed to update student");
                    flash('error', 'Failed to update student');
                }
            } catch (Exception $e) {
                error_log("EXCEPTION: Student update error - " . $e->getMessage());
                flash('error', 'Error: ' . $e->getMessage());
            }
            
            redirect("admin/intakes/computer-science/$intakeSlug");
        } else {
            // GET request - redirect back
            redirect("admin/intakes/computer-science/feb-2023");
        }
    }
    
    /**
     * Delete Student from Intake
     */
    public function deleteStudent()
    {
        $this->requireAuth(['admin']);
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $studentId = intval($_GET['id'] ?? 0);
            $intakeSlug = $_GET['intake_slug'] ?? 'feb-2023';
            
            error_log("=== DELETE STUDENT ATTEMPT ===");
            error_log("Student ID: $studentId");
            error_log("Intake Slug: $intakeSlug");
            
            if ($studentId <= 0) {
                error_log("ERROR: Invalid student ID");
                flash('error', 'Invalid student ID');
                redirect("admin/intakes/computer-science/$intakeSlug");
                return;
            }
            
            // Check if student exists
            $student = $this->studentModel->getById($studentId);
            if (!$student) {
                error_log("ERROR: Student not found with ID: $studentId");
                flash('error', 'Student not found');
                redirect("admin/intakes/computer-science/$intakeSlug");
                return;
            }
            
            error_log("Found student: {$student['student_id']} - {$student['first_name']} {$student['last_name']}");
            
            // Delete student
            try {
                $success = $this->studentModel->delete($studentId);
                
                if ($success) {
                    error_log("SUCCESS: Student deleted successfully");
                    flash('success', 'Student removed successfully');
                } else {
                    error_log("ERROR: Failed to delete student");
                    flash('error', 'Failed to remove student. They may have associated records.');
                }
            } catch (Exception $e) {
                error_log("EXCEPTION: Student delete error - " . $e->getMessage());
                flash('error', 'Error: ' . $e->getMessage());
            }
            
            redirect("admin/intakes/computer-science/$intakeSlug");
        } else {
            // GET request - redirect back
            redirect("admin/intakes/computer-science/feb-2023");
        }
    }
}
