<?php
/**
 * Web Routes
 * UniSIRAJ Automated Attendance System
 * 
 * Define all application routes here
 * Format: 'url' => ['controller' => 'ControllerName', 'method' => 'methodName']
 */

return [
    
    // ============================================
    // PUBLIC ROUTES
    // ============================================
    
    // Home/Landing page
    '' => ['controller' => 'HomeController', 'method' => 'index'],
    '/' => ['controller' => 'HomeController', 'method' => 'index'],
    '/home' => ['controller' => 'HomeController', 'method' => 'index'],
    
    // Authentication
    '/login' => ['controller' => 'AuthController', 'method' => 'login'],
    '/logout' => ['controller' => 'AuthController', 'method' => 'logout'],
    '/change-password' => ['controller' => 'AuthController', 'method' => 'changePassword'],
    '/forgot-password' => ['controller' => 'AuthController', 'method' => 'forgotPassword'],
    
    // ============================================
    // ADMIN ROUTES
    // ============================================
    
    '/admin' => ['controller' => 'AdminDashboardController', 'method' => 'index'],
    '/admin/dashboard' => ['controller' => 'AdminDashboardController', 'method' => 'index'],
    
    // Students Management
    '/admin/students' => ['controller' => 'Admin\StudentController', 'method' => 'index'],
    '/admin/students/create' => ['controller' => 'Admin\StudentController', 'method' => 'create'],
    '/admin/students/store' => ['controller' => 'Admin\StudentController', 'method' => 'store'],
    '/admin/students/edit' => ['controller' => 'Admin\StudentController', 'method' => 'edit'],
    '/admin/students/update' => ['controller' => 'Admin\StudentController', 'method' => 'update'],
    '/admin/students/delete' => ['controller' => 'Admin\StudentController', 'method' => 'delete'],
    
    // Lecturers Management
    '/admin/lecturers' => ['controller' => 'Admin\LecturerController', 'method' => 'index'],
    '/admin/lecturers/faculty/islamic-studies' => ['controller' => 'Admin\LecturerController', 'method' => 'faculty', 'params' => ['islamic-studies']],
    '/admin/lecturers/faculty/islamic-transaction' => ['controller' => 'Admin\LecturerController', 'method' => 'faculty', 'params' => ['islamic-transaction']],
    '/admin/lecturers/faculty/business-management' => ['controller' => 'Admin\LecturerController', 'method' => 'faculty', 'params' => ['business-management']],
    '/admin/lecturers/faculty/quran-sunnah' => ['controller' => 'Admin\LecturerController', 'method' => 'faculty', 'params' => ['quran-sunnah']],
    '/admin/lecturers/store' => ['controller' => 'Admin\LecturerController', 'method' => 'store'],
    '/admin/lecturers/update' => ['controller' => 'Admin\LecturerController', 'method' => 'update'],
    '/admin/lecturers/delete' => ['controller' => 'Admin\LecturerController', 'method' => 'delete'],
    '/admin/lecturers/create' => ['controller' => 'Admin\LecturerController', 'method' => 'create'],
    '/admin/lecturers/store' => ['controller' => 'Admin\LecturerController', 'method' => 'store'],
    '/admin/lecturers/edit' => ['controller' => 'Admin\LecturerController', 'method' => 'edit'],
    '/admin/lecturers/update' => ['controller' => 'Admin\LecturerController', 'method' => 'update'],
    '/admin/lecturers/delete' => ['controller' => 'Admin\LecturerController', 'method' => 'delete'],
    
    // Courses Management
    '/admin/courses' => ['controller' => 'Admin\CourseController', 'method' => 'index'],
    '/admin/courses/create' => ['controller' => 'Admin\CourseController', 'method' => 'create'],
    '/admin/courses/store' => ['controller' => 'Admin\CourseController', 'method' => 'store'],
    '/admin/courses/view' => ['controller' => 'Admin\CourseController', 'method' => 'view'],
    '/admin/courses/edit' => ['controller' => 'Admin\CourseController', 'method' => 'edit'],
    '/admin/courses/update' => ['controller' => 'Admin\CourseController', 'method' => 'update'],
    '/admin/courses/delete' => ['controller' => 'Admin\CourseController', 'method' => 'delete'],
    
    // Enrollments Management
    '/admin/enrollments' => ['controller' => 'Admin\EnrollmentController', 'method' => 'index'],
    '/admin/enrollments/create' => ['controller' => 'Admin\EnrollmentController', 'method' => 'create'],
    '/admin/enrollments/store' => ['controller' => 'Admin\EnrollmentController', 'method' => 'store'],
    '/admin/enrollments/update-status' => ['controller' => 'Admin\EnrollmentController', 'method' => 'updateStatus'],
    '/admin/enrollments/delete' => ['controller' => 'Admin\EnrollmentController', 'method' => 'delete'],
    
    // Departments
    '/admin/departments/computer-science' => ['controller' => 'Admin\DepartmentController', 'method' => 'computerScience'],
    
    // Intakes
    '/admin/intakes/computer-science/feb-2023' => ['controller' => 'Admin\DepartmentController', 'method' => 'intake', 'params' => ['computer-science', 'feb-2023']],
    '/admin/intakes/computer-science/sep-2023' => ['controller' => 'Admin\DepartmentController', 'method' => 'intake', 'params' => ['computer-science', 'sep-2023']],
    
    // Course CRUD for Intakes
    '/admin/intakes/course/add' => ['controller' => 'Admin\DepartmentController', 'method' => 'addCourse'],
    '/admin/intakes/course/update' => ['controller' => 'Admin\DepartmentController', 'method' => 'updateCourse'],
    '/admin/intakes/course/delete' => ['controller' => 'Admin\DepartmentController', 'method' => 'deleteCourse'],
    
    // Student CRUD for Intakes
    '/admin/intakes/student/add' => ['controller' => 'Admin\DepartmentController', 'method' => 'addStudent'],
    '/admin/intakes/student/update' => ['controller' => 'Admin\DepartmentController', 'method' => 'updateStudent'],
    '/admin/intakes/student/delete' => ['controller' => 'Admin\DepartmentController', 'method' => 'deleteStudent'],
    
    // ============================================
    // LECTURER ROUTES
    // ============================================
    
    '/lecturer' => ['controller' => 'LecturerDashboardController', 'method' => 'index'],
    '/lecturer/dashboard' => ['controller' => 'LecturerDashboardController', 'method' => 'index'],
    
    // Course Management
    '/lecturer/courses/create' => ['controller' => 'LecturerCourseController', 'method' => 'create'],
    '/lecturer/courses/store' => ['controller' => 'LecturerCourseController', 'method' => 'store'],
    '/lecturer/courses/dashboard' => ['controller' => 'LecturerCourseController', 'method' => 'dashboard'],
    '/lecturer/courses/archive' => ['controller' => 'LecturerCourseController', 'method' => 'archive'],
    '/lecturer/courses/unarchive' => ['controller' => 'LecturerCourseController', 'method' => 'unarchive'],
    '/lecturer/courses/delete' => ['controller' => 'LecturerCourseController', 'method' => 'delete'],
    
    // Attendance Sessions
    '/lecturer/sessions' => ['controller' => 'Lecturer\SessionController', 'method' => 'index'],
    '/lecturer/sessions/create' => ['controller' => 'Lecturer\SessionController', 'method' => 'create'],
    '/lecturer/sessions/store' => ['controller' => 'Lecturer\SessionController', 'method' => 'store'],
    '/lecturer/sessions/view' => ['controller' => 'Lecturer\SessionController', 'method' => 'viewSession'],
    '/lecturer/sessions/close' => ['controller' => 'Lecturer\SessionController', 'method' => 'close'],
    '/lecturer/sessions/delete' => ['controller' => 'Lecturer\SessionController', 'method' => 'delete'],
    '/lecturer/sessions/live' => ['controller' => 'Lecturer\SessionController', 'method' => 'liveAttendance'],
    
    // ============================================
    // STUDENT ROUTES
    // ============================================
    
    '/student' => ['controller' => 'Student\DashboardController', 'method' => 'index'],
    '/student/dashboard' => ['controller' => 'Student\DashboardController', 'method' => 'index'],
    '/student/course/dashboard' => ['controller' => 'Student\DashboardController', 'method' => 'courseDashboard'],
    
    // Course Enrollment
    '/student/enrollment' => ['controller' => 'Student\EnrollmentController', 'method' => 'index'],
    '/student/enrollment/enroll' => ['controller' => 'Student\EnrollmentController', 'method' => 'enroll'],
    '/student/enrollment/unenroll' => ['controller' => 'Student\EnrollmentController', 'method' => 'unenroll'],
    
    // Attendance
    '/student/attendance/scan' => ['controller' => 'Student\AttendanceController', 'method' => 'scan'],
    '/student/attendance/verify' => ['controller' => 'Student\AttendanceController', 'method' => 'verify'],
    '/student/attendance/history' => ['controller' => 'Student\AttendanceController', 'method' => 'history'],
    
    // ============================================
    // SHARED ROUTES (Multiple Roles)
    // ============================================
    
    // Reports
    '/reports' => ['controller' => 'ReportController', 'method' => 'index'],
    '/reports/generate' => ['controller' => 'ReportController', 'method' => 'generate'],
    '/reports/export-pdf' => ['controller' => 'ReportController', 'method' => 'exportPdf'],
    '/reports/export-excel' => ['controller' => 'ReportController', 'method' => 'exportExcel'],
    
    // Analytics
    '/analytics' => ['controller' => 'AnalyticsController', 'method' => 'index'],
    '/analytics/data' => ['controller' => 'AnalyticsController', 'method' => 'data'],
    
    // ============================================
    // API ROUTES (AJAX)
    // ============================================
    
    '/api/attendance/live' => ['controller' => 'Api\AttendanceController', 'method' => 'live'],
    '/api/attendance/validate' => ['controller' => 'Api\AttendanceController', 'method' => 'validate'],
    
    // ============================================
    // ERROR PAGES
    // ============================================
    
    '/403' => ['controller' => 'ErrorController', 'method' => 'forbidden'],
    '/404' => ['controller' => 'ErrorController', 'method' => 'notFound'],
    '/500' => ['controller' => 'ErrorController', 'method' => 'serverError'],
];
