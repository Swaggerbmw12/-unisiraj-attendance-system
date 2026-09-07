<?php
/**
 * Error Controller
 * UniSIRAJ Automated Attendance System
 * 
 * Handles error pages (403, 404, 500)
 */

class ErrorController extends BaseController {
    
    /**
     * 403 Forbidden
     */
    public function forbidden() {
        http_response_code(403);
        $this->view('errors/403', [
            'title' => '403 - Forbidden'
        ]);
    }
    
    /**
     * 404 Not Found
     */
    public function notFound() {
        http_response_code(404);
        $this->view('errors/404', [
            'title' => '404 - Page Not Found'
        ]);
    }
    
    /**
     * 500 Server Error
     */
    public function serverError() {
        http_response_code(500);
        $this->view('errors/500', [
            'title' => '500 - Server Error'
        ]);
    }
}
