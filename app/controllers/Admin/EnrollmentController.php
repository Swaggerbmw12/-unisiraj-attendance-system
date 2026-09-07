<?php

require_once dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'Enrollment.php';
require_once dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'Student.php';
require_once dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'Course.php';

class EnrollmentController extends BaseController
{
    private $enrollmentModel;
    private $studentModel;
    private $courseModel;
    
    public function __construct()
    {
        parent::__construct();
        $this->requireAuth(['admin']);
        $this->enrollmentModel = new Enrollment();
        $this->studentModel = new Student();
        $this->courseModel = new Course();
    }
    
    public function index()
    {
        $page = $_GET['page'] ?? 1;
        $filters = [
            'course_id' => $_GET['course_id'] ?? null,
            'student_id' => $_GET['student_id'] ?? null,
            'status' => $_GET['status'] ?? null
        ];
        
        $result = $this->enrollmentModel->getAll($page, 10, $filters);
        $courses = $this->courseModel->getAll(1, 100);
        
        $this->render('admin/enrollments/index', [
            'enrollments' => $result['data'],
            'pagination' => $result,
            'filters' => $filters,
            'courses' => $courses['data']
        ]);
    }
    
    public function create()
    {
        $students = $this->studentModel->getAll(1, 200);
        $courses = $this->courseModel->getAll(1, 100);
        
        $this->render('admin/enrollments/create', [
            'students' => $students['data'],
            'courses' => $courses['data']
        ]);
    }
    
    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/enrollments');
            return;
        }
        
        $errors = [];
        
        if (empty($_POST['student_id'])) $errors[] = 'Student is required';
        if (empty($_POST['course_id'])) $errors[] = 'Course is required';
        
        if (!empty($errors)) {
            $this->setFlashMessage(implode(', ', $errors), 'error');
            $this->redirect('/admin/enrollments/create');
            return;
        }
        
        // Check if already enrolled
        if ($this->enrollmentModel->isEnrolled($_POST['student_id'], $_POST['course_id'])) {
            $this->setFlashMessage('Student is already enrolled in this course', 'error');
            $this->redirect('/admin/enrollments/create');
            return;
        }
        
        if ($this->enrollmentModel->enroll($_POST['student_id'], $_POST['course_id'])) {
            $this->setFlashMessage('Student enrolled successfully', 'success');
            $this->redirect('/admin/enrollments');
        } else {
            $this->setFlashMessage('Failed to enroll student', 'error');
            $this->redirect('/admin/enrollments/create');
        }
    }
    
    public function updateStatus($id)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/enrollments');
            return;
        }
        
        if (empty($_POST['status'])) {
            $this->setFlashMessage('Status is required', 'error');
            $this->redirect('/admin/enrollments');
            return;
        }
        
        if ($this->enrollmentModel->updateStatus($id, $_POST['status'])) {
            $this->setFlashMessage('Enrollment status updated', 'success');
        } else {
            $this->setFlashMessage('Failed to update status', 'error');
        }
        
        $this->redirect('/admin/enrollments');
    }
    
    public function delete($id)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/enrollments');
            return;
        }
        
        if ($this->enrollmentModel->delete($id)) {
            $this->setFlashMessage('Enrollment removed successfully', 'success');
        } else {
            $this->setFlashMessage('Failed to remove enrollment', 'error');
        }
        
        $this->redirect('/admin/enrollments');
    }
}
