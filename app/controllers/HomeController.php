<?php
/**
 * Home Controller
 * UniSIRAJ Automated Attendance System
 * 
 * Handles homepage and public pages
 */

class HomeController extends BaseController {
    
    /**
     * Display homepage
     * If user is logged in, redirect to role-appropriate dashboard
     */
    public function index() {
        // If user is already logged in, redirect to dashboard
        if (isAuthenticated()) {
            $role = currentUserRole();
            
            switch ($role) {
                case 'admin':
                    redirect('admin/dashboard');
                    break;
                case 'lecturer':
                    redirect('lecturer/dashboard');
                    break;
                case 'student':
                    redirect('student/dashboard');
                    break;
                default:
                    // Invalid role, logout
                    $this->logout();
                    redirect('login');
            }
        }
        
        // Show landing page with role selection
        $this->view('home/landing');
    }
}
