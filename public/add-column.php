<?php
define('ROOT_PATH', dirname(__DIR__));
require_once ROOT_PATH . '/config/config.php';
require_once ROOT_PATH . '/config/database.php';

try {
    $db = Database::getInstance();
    $pdo = $db->getConnection();
    
    // Check if column exists
    $stmt = $pdo->query("SHOW COLUMNS FROM courses LIKE 'lecturer_name'");
    if ($stmt->fetch()) {
        echo 'SUCCESS: Column already exists!';
    } else {
        $pdo->exec("ALTER TABLE courses ADD COLUMN lecturer_name VARCHAR(255) NULL AFTER lecturer_id");
        echo 'SUCCESS: Column added!';
    }
} catch (Exception $e) {
    echo 'ERROR: ' . $e->getMessage();
}
