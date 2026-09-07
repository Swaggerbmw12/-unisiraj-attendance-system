<?php

require_once dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'Course.php';
require_once dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'Lecturer.php';

class CourseController extends BaseController
{
    private $courseModel;
    private $lecturerModel;
    
    public function __construct()
    {
        parent::__construct();
        $this->requireAuth(['admin']);
        $this->courseModel = new Course();
        $this->lecturerModel = new Lecturer();
    }
    
    public function index()
    {
        $page = $_GET['page'] ?? 1;
        $search = $_GET['search'] ?? '';
        
        $result = $this->courseModel->getAll($page, 10, $search);
        
        $this->render('admin/courses/index', [
            'courses' => $result['data'],
            'pagination' => $result,
            'search' => $search
        ]);
    }
    
    public function create()
    {
        $lecturers = $this->lecturerModel->getAll(1, 100);
        
        $this->render('admin/courses/create', [
            'lecturers' => $lecturers['data']
        ]);
    }
    
    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/courses');
            return;
        }
        
        $errors = [];
        
        if (empty($_POST['course_code'])) {
            $errors[] = 'Course code is required';
        } elseif ($this->courseModel->courseCodeExists($_POST['course_code'])) {
            $errors[] = 'Course code already exists';
        }
        
        if (empty($_POST['course_name'])) $errors[] = 'Course name is required';
        
        if (!empty($errors)) {
            $this->setFlashMessage(implode(', ', $errors), 'error');
            $this->redirect('/admin/courses/create');
            return;
        }
        
        $courseId = $this->courseModel->create($_POST);
        
        if ($courseId) {
            $this->setFlashMessage('Course created successfully', 'success');
            $this->redirect('/admin/courses');
        } else {
            $this->setFlashMessage('Failed to create course', 'error');
            $this->redirect('/admin/courses/create');
        }
    }
    
    public function edit($id)
    {
        $course = $this->courseModel->getById($id);
        
        if (!$course) {
            $this->setFlashMessage('Course not found', 'error');
            $this->redirect('/admin/courses');
            return;
        }
        
        $lecturers = $this->lecturerModel->getAll(1, 100);
        
        $this->render('admin/courses/edit', [
            'course' => $course,
            'lecturers' => $lecturers['data']
        ]);
    }
    
    public function update($id)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/courses');
            return;
        }
        
        $course = $this->courseModel->getById($id);
        if (!$course) {
            $this->setFlashMessage('Course not found', 'error');
            $this->redirect('/admin/courses');
            return;
        }
        
        $errors = [];
        
        if (empty($_POST['course_name'])) $errors[] = 'Course name is required';
        
        if (!empty($errors)) {
            $this->setFlashMessage(implode(', ', $errors), 'error');
            $this->redirect('/admin/courses/edit/' . $id);
            return;
        }
        
        if ($this->courseModel->update($id, $_POST)) {
            $this->setFlashMessage('Course updated successfully', 'success');
            $this->redirect('/admin/courses');
        } else {
            $this->setFlashMessage('Failed to update course', 'error');
            $this->redirect('/admin/courses/edit/' . $id);
        }
    }
    
    public function delete($id)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/courses');
            return;
        }
        
        if ($this->courseModel->delete($id)) {
            $this->setFlashMessage('Course deleted successfully', 'success');
        } else {
            $this->setFlashMessage('Failed to delete course', 'error');
        }
        
        $this->redirect('/admin/courses');
    }
    
    public function view($id)
    {
        $course = $this->courseModel->getById($id);
        
        if (!$course) {
            $this->setFlashMessage('Course not found', 'error');
            $this->redirect('/admin/courses');
            return;
        }
        
        $students = $this->courseModel->getEnrolledStudents($id);
        
        $this->render('admin/courses/view', [
            'course' => $course,
            'students' => $students
        ]);
    }
}
