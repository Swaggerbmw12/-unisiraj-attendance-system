<?php

require_once dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'Lecturer.php';
require_once dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'User.php';

class LecturerController extends BaseController
{
    private $lecturerModel;
    private $userModel;
    
    public function __construct()
    {
        parent::__construct();
        $this->requireAuth(['admin']);
        $this->lecturerModel = new Lecturer();
        $this->userModel = new User();
    }
    
    /**
     * Display lecturers by faculty
     */
    public function faculty($facultySlug)
    {
        $page = $_GET['page'] ?? 1;
        $search = $_GET['search'] ?? '';
        
        // Faculty mapping
        $faculties = [
            'islamic-studies' => 'Faculty of Islamic Studies',
            'islamic-transaction' => 'Faculty of Islamic Transaction and Finance',
            'business-management' => 'Faculty of Business and Management Science',
            'quran-sunnah' => 'Faculty of the Quran and Sunnah'
        ];
        
        $facultyName = $faculties[$facultySlug] ?? 'Unknown Faculty';
        
        // Get lecturers filtered by faculty
        $result = $this->lecturerModel->getByFaculty($facultySlug, $page, 10, $search);
        
        $this->render('admin/lecturers/faculty', [
            'title' => $facultyName . ' - Lecturers',
            'lecturers' => $result['data'],
            'pagination' => $result,
            'search' => $search,
            'faculty_slug' => $facultySlug,
            'faculty_name' => $facultyName
        ], 'layouts/admin-sidebar');
    }
    
    public function index()
    {
        $page = $_GET['page'] ?? 1;
        $search = $_GET['search'] ?? '';
        
        $result = $this->lecturerModel->getAll($page, 10, $search);
        
        $this->render('admin/lecturers/index', [
            'lecturers' => $result['data'],
            'pagination' => $result,
            'search' => $search
        ]);
    }
    
    public function create()
    {
        $this->render('admin/lecturers/create');
    }
    
    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('admin/lecturers/faculty/business-management');
            return;
        }
        
        $facultySlug = $_POST['faculty_slug'] ?? 'business-management';
        
        $errors = [];
        
        if (empty($_POST['staff_id'])) {
            $errors[] = 'Staff ID is required';
        } elseif ($this->lecturerModel->staffIdExists($_POST['staff_id'])) {
            $errors[] = 'Staff ID already exists';
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
            redirect('admin/lecturers/faculty/' . $facultySlug);
            return;
        }
        
        $lecturerId = $this->lecturerModel->create([
            'staff_id' => $_POST['staff_id'],
            'first_name' => $_POST['first_name'],
            'last_name' => $_POST['last_name'],
            'email' => $_POST['email'],
            'phone' => $_POST['phone'] ?? null,
            'faculty' => $facultySlug,
            'password' => $_POST['password']
        ]);
        
        if ($lecturerId) {
            flash('success', 'Lecturer created successfully');
        } else {
            flash('error', 'Failed to create lecturer');
        }
        
        redirect('admin/lecturers/faculty/' . $facultySlug);
    }
    
    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('admin/lecturers/faculty/business-management');
            return;
        }
        
        $facultySlug = $_POST['faculty_slug'] ?? 'business-management';
        
        // Extract ID from URL path
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $parts = explode('/', trim($path, '/'));
        $id = end($parts);
        
        $lecturer = $this->lecturerModel->getById($id);
        if (!$lecturer) {
            flash('error', 'Lecturer not found');
            redirect('admin/lecturers/faculty/' . $facultySlug);
            return;
        }
        
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
            redirect('admin/lecturers/faculty/' . $facultySlug);
            return;
        }
        
        if ($this->lecturerModel->update($id, [
            'first_name' => $_POST['first_name'],
            'last_name' => $_POST['last_name'],
            'email' => $_POST['email'],
            'phone' => $_POST['phone'] ?? null,
            'faculty' => $facultySlug
        ])) {
            flash('success', 'Lecturer updated successfully');
        } else {
            flash('error', 'Failed to update lecturer');
        }
        
        redirect('admin/lecturers/faculty/' . $facultySlug);
    }
    
    public function delete()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('admin/lecturers/faculty/business-management');
            return;
        }
        
        // Extract ID and faculty from URL
        $facultySlug = $_GET['faculty'] ?? 'business-management';
        $id = $_GET['id'] ?? 0;
        
        if ($this->lecturerModel->delete($id)) {
            flash('success', 'Lecturer deleted successfully');
        } else {
            flash('error', 'Failed to delete lecturer');
        }
        
        redirect('admin/lecturers/faculty/' . $facultySlug);
    }
}
