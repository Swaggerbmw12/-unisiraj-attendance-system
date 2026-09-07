<?php
/**
 * Check PHP Error Logs
 * Run this to see what errors are occurring
 */

echo "<!DOCTYPE html>
<html>
<head>
    <meta charset='UTF-8'>
    <title>Error Log Checker</title>
    <style>
        body { 
            font-family: 'Courier New', monospace; 
            margin: 20px;
            background: #1e1e1e;
            color: #d4d4d4;
        }
        h1 { color: #4ec9b0; }
        .error { color: #f48771; }
        .warning { color: #dcdcaa; }
        .info { color: #4fc1ff; }
        pre {
            background: #252526;
            padding: 15px;
            border-radius: 5px;
            overflow-x: auto;
            border-left: 3px solid #007acc;
        }
        .section {
            margin: 20px 0;
            padding: 15px;
            background: #252526;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <h1>🔍 Error Log Checker</h1>";

// Check PHP error log location
$errorLog = ini_get('error_log');
echo "<div class='section'>";
echo "<h2>PHP Error Log Location:</h2>";
echo "<pre>" . ($errorLog ? $errorLog : "Not configured - errors go to web server log") . "</pre>";
echo "</div>";

// Check if we can read recent errors
echo "<div class='section'>";
echo "<h2>Recent PHP Errors (last 50 lines):</h2>";

if ($errorLog && file_exists($errorLog)) {
    $lines = file($errorLog);
    $recentLines = array_slice($lines, -50);
    echo "<pre>";
    foreach ($recentLines as $line) {
        if (stripos($line, 'error') !== false) {
            echo "<span class='error'>" . htmlspecialchars($line) . "</span>";
        } elseif (stripos($line, 'warning') !== false) {
            echo "<span class='warning'>" . htmlspecialchars($line) . "</span>";
        } else {
            echo htmlspecialchars($line);
        }
    }
    echo "</pre>";
} else {
    echo "<p class='warning'>⚠️ Error log file not found or not readable</p>";
    echo "<p>Errors may be displayed on screen or in web server logs</p>";
}
echo "</div>";

// Test database connection
echo "<div class='section'>";
echo "<h2>Database Connection Test:</h2>";
try {
    define('ROOT_PATH', __DIR__);
    require_once ROOT_PATH . '/config/config.php';
    require_once ROOT_PATH . '/config/database.php';
    
    $db = Database::getInstance();
    if ($db->testConnection()) {
        echo "<p class='info'>✅ Database connection successful</p>";
        
        // Check if lecturer_name column exists
        $pdo = $db->getConnection();
        $stmt = $pdo->query("SHOW COLUMNS FROM courses");
        $columns = $stmt->fetchAll(PDO::FETCH_COLUMN);
        
        echo "<h3>Courses Table Columns:</h3>";
        echo "<pre>";
        foreach ($columns as $col) {
            if ($col === 'lecturer_name') {
                echo "<span class='info'>✅ $col</span>\n";
            } else {
                echo "$col\n";
            }
        }
        echo "</pre>";
        
        if (!in_array('lecturer_name', $columns)) {
            echo "<p class='error'>❌ lecturer_name column is MISSING!</p>";
            echo "<p>Run this to fix: <a href='add-lecturer-column.php' style='color: #4fc1ff;'>add-lecturer-column.php</a></p>";
        } else {
            echo "<p class='info'>✅ lecturer_name column exists</p>";
        }
    } else {
        echo "<p class='error'>❌ Database connection failed</p>";
    }
} catch (Exception $e) {
    echo "<p class='error'>❌ Error: " . htmlspecialchars($e->getMessage()) . "</p>";
}
echo "</div>";

// Test course creation
echo "<div class='section'>";
echo "<h2>Test Course Creation:</h2>";
try {
    require_once ROOT_PATH . '/app/models/BaseModel.php';
    require_once ROOT_PATH . '/app/models/Course.php';
    
    $courseModel = new Course();
    
    // Try to create a test course
    $testCode = 'TEST_' . time();
    echo "<p>Attempting to create test course with code: <strong>$testCode</strong></p>";
    
    $courseId = $courseModel->create([
        'course_code' => $testCode,
        'course_name' => 'Test Course Creation',
        'lecturer_id' => null,
        'lecturer_name' => 'Test Lecturer',
        'credits' => 3,
        'semester' => 'Test Semester',
        'academic_year' => '2023/2024',
        'is_active' => 1
    ]);
    
    if ($courseId) {
        echo "<p class='info'>✅ SUCCESS! Course created with ID: $courseId</p>";
        
        // Retrieve it
        $course = $courseModel->getById($courseId);
        echo "<pre>";
        print_r($course);
        echo "</pre>";
        
        // Delete it
        $courseModel->delete($courseId);
        echo "<p class='info'>✅ Test course deleted (cleanup)</p>";
    } else {
        echo "<p class='error'>❌ FAILED to create course</p>";
        echo "<p>Check the logs above for the error message</p>";
    }
} catch (Exception $e) {
    echo "<p class='error'>❌ Exception: " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
}
echo "</div>";

// Check sessions
echo "<div class='section'>";
echo "<h2>Session Status:</h2>";
session_start();
echo "<pre>";
echo "Session ID: " . session_id() . "\n";
echo "Session Status: " . (session_status() === PHP_SESSION_ACTIVE ? "Active" : "Inactive") . "\n";
echo "\nSession Data:\n";
print_r($_SESSION);
echo "</pre>";
echo "</div>";

echo "</body></html>";
