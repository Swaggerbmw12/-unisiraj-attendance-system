<?php
/**
 * Database Configuration Example
 * 
 * Copy this file to database.php and update with your actual credentials
 * DO NOT commit database.php to version control!
 */

return [
    'host' => 'localhost',
    'database' => 'attendance_system',
    'username' => 'your_username',
    'password' => 'your_password',
    'charset' => 'utf8mb4',
    'port' => 3306,
    'options' => [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]
];
