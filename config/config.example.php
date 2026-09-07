<?php
/**
 * Application Configuration Example
 * 
 * Copy this file to config.php and update with your actual settings
 * DO NOT commit config.php to version control!
 */

// Base URL - Update this based on your environment
define('BASE_URL', 'http://localhost:8000');

// Site Configuration
define('SITE_NAME', 'UniSIRAJ Attendance System');

// Debug Mode - Set to false in production!
define('DEBUG_MODE', true);

// Session Configuration
define('SESSION_LIFETIME', 3600); // 1 hour

// Security Settings
define('MAX_LOGIN_ATTEMPTS', 5);
define('LOGIN_TIMEOUT', 900); // 15 minutes

// File Upload Settings (if needed)
define('MAX_UPLOAD_SIZE', 5242880); // 5MB in bytes
define('ALLOWED_FILE_TYPES', ['jpg', 'jpeg', 'png', 'pdf']);

// QR Code Settings
define('QR_CODE_SIZE', 300);
define('QR_CODE_QUALITY', 'M');

// Pagination
define('ITEMS_PER_PAGE', 10);

// Timezone
date_default_timezone_set('Asia/Kuala_Lumpur');
