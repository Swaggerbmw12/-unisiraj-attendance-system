<?php

require_once dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'Student.php';
require_once dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'User.php';

class StudentController extends BaseController
{
    private $studentModel;
    private $userModel;
    
    public function __construct()
    {
        parent::__construct();
        $this->requireAuth(['admin']);
        $this->studentModel = new Student();
        $this->userModel = new User();
    }
    
    /**
     * List all students
     */
    public function index()
    {
        $page = $_GET['page'] ?? 1;
        $search = $_GET['search'] ?? '';
        
        $result = $this->studentModel->getAll($page, 10, $search);
        
        $this->render('admin/students/index', [
            'title' => 'Students Management',
            'students' => $result['data'],
            'pagination' => $result,
            'search' => $search
        ], 'layouts/admin-sidebar');
    }
    
    /**
     * Show create form
     */
    public function create()
    {
        $this->render('admin/students/create');
    }
    
    /**
     * Store new student
     */
    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('admin/students');
            return;
        }
        
        // Validate input
        $errors = [];
        
        if (empty($_POST['student_id'])) {
            $errors[] = 'Student ID is required';
        } elseif ($this->studentModel->studentIdExists($_POST['student_id'])) {
            $errors[] = 'Student ID already exists';
        }
        
        if (empty($_POST['email'])) {
            $errors[] = 'Email is required';
        } elseif (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Invalid email format';
        }
        
        if (empty($_POST['first_name'])) $errors[] = 'First name is required';
        if (empty($_POST['last_name'])) $errors[] = 'Last name is required';
        if (empty($_POST['password']) || strlen($_POST['password']) < 8) {
            $errors[] = 'Password must be at least 8 characters';
        }
        
        if (!empty($errors)) {
            flash('error', implode(', ', $errors));
            redirect('admin/students');
            return;
        }
        
        // Create student
        $studentId = $this->studentModel->create([
            'student_id' => $_POST['student_id'],
            'first_name' => $_POST['first_name'],
            'last_name' => $_POST['last_name'],
            'email' => $_POST['email'],
            'phone' => $_POST['phone'] ?? null,
            'program' => $_POST['program'] ?? null,
            'year_of_study' => $this->mapIntakeToYear($_POST['intake'] ?? 'Feb 2023'),
            'password' => $_POST['password']
        ]);
        
        if ($studentId) {
            flash('success', 'Student created successfully');
        } else {
            flash('error', 'Failed to create student');
        }
        
        redirect('admin/students');
    }
    
    /**
     * Update student
     */
    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('admin/students');
            return;
        }
        
        // Extract ID from URL path
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $parts = explode('/', trim($path, '/'));
        $id = end($parts);
        
        $student = $this->studentModel->getById($id);
        if (!$student) {
            flash('error', 'Student not found');
            redirect('admin/students');
            return;
        }
        
        // Validate input
        $errors = [];
        
        if (empty($_POST['email'])) {
            $errors[] = 'Email is required';
        } elseif (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Invalid email format';
        }
        
        if (empty($_POST['first_name'])) $errors[] = 'First name is required';
        if (empty($_POST['last_name'])) $errors[] = 'Last name is required';
        
        if (!empty($errors)) {
            flash('error', implode(', ', $errors));
            redirect('admin/students');
            return;
        }
        
        // Update student
        if ($this->studentModel->update($id, [
            'first_name' => $_POST['first_name'],
            'last_name' => $_POST['last_name'],
            'email' => $_POST['email'],
            'phone' => $_POST['phone'] ?? null,
            'program' => $_POST['program'] ?? null,
            'year_of_study' => $this->mapIntakeToYear($_POST['intake'] ?? 'Feb 2023')
        ])) {
            flash('success', 'Student updated successfully');
        } else {
            flash('error', 'Failed to update student');
        }
        
        redirect('admin/students');
    }
    
    /**
     * Delete student
     */
    public function delete()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('admin/students');
            return;
        }
        
        // Extract ID from URL path
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $parts = explode('/', trim($path, '/'));
        $id = end($parts);
        
        if ($this->studentModel->delete($id)) {
            flash('success', 'Student deleted successfully');
        } else {
            flash('error', 'Failed to delete student');
        }
        
        redirect('admin/students');
    }
    
    /**
     * Map intake string to year of study
     * 
     * @param string $intake Intake name
     * @return int Year of study
     */
    private function mapIntakeToYear($intake)
    {
        $intakeYearMap = [
            'Feb 2023' => 1,
            'Sep 2023' => 1,
            'Feb 2022' => 2,
            'Sep 2022' => 2,
            'Feb 2021' => 3,
            'Sep 2021' => 4
        ];
        
        return $intakeYearMap[$intake] ?? 1;
    }
}
