<?php
/**
 * Add lecturer_name column to courses table
 * Run this file directly in your browser: http://localhost:8000/add-lecturer-column.php
 */

// Define constants
define('ROOT_PATH', __DIR__);

// Load configuration
require_once ROOT_PATH . '/config/config.php';
require_once ROOT_PATH . '/config/database.php';

echo "<!DOCTYPE html>
<html>
<head>
    <meta charset='UTF-8'>
    <title>Add Lecturer Name Column</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            margin: 40px;
            background: #f5f5f5;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        h1 { color: #333; }
        .success { 
            color: #28a745; 
            background: #d4edda;
            border: 1px solid #c3e6cb;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }
        .error { 
            color: #dc3545;
            background: #f8d7da;
            border: 1px solid #f5c6cb;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }
        .info {
            color: #004085;
            background: #cce5ff;
            border: 1px solid #b8daff;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
        }
        .btn:hover {
            background: #0056b3;
        }
        pre {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            overflow-x: auto;
        }
    </style>
</head>
<body>
    <div class='container'>
        <h1>Add Lecturer Name Column to Courses Table</h1>";

try {
    $db = Database::getInstance();
    $pdo = $db->getConnection();
    
    // Check if column already exists
    $checkSql = "SHOW COLUMNS FROM courses LIKE 'lecturer_name'";
    $stmt = $pdo->prepare($checkSql);
    $stmt->execute();
    $columnExists = $stmt->fetch();
    
    if ($columnExists) {
        echo "<div class='info'>
                <strong>ℹ️ Column Already Exists</strong><br>
                The 'lecturer_name' column already exists in the courses table. No action needed!
              </div>";
    } else {
        // Add the column
        $alterSql = "ALTER TABLE courses ADD COLUMN lecturer_name VARCHAR(255) NULL AFTER lecturer_id";
        $pdo->exec($alterSql);
        
        echo "<div class='success'>
                <strong>✅ Success!</strong><br>
                The 'lecturer_name' column has been added to the courses table.
              </div>";
        
        // Try to migrate existing data
        try {
            $updateSql = "UPDATE courses c
                         LEFT JOIN lecturers l ON c.lecturer_id = l.id
                         SET c.lecturer_name = CONCAT(l.first_name, ' ', l.last_name)
                         WHERE c.lecturer_id IS NOT NULL";
            $pdo->exec($updateSql);
            
            echo "<div class='success'>
                    <strong>✅ Data Migrated</strong><br>
                    Existing lecturer names have been copied from the lecturers table.
                  </div>";
        } catch (Exception $e) {
            echo "<div class='info'>
                    <strong>ℹ️ Note:</strong> Could not migrate existing data. This is OK if you don't have any lecturers in the database yet.
                  </div>";
        }
    }
    
    // Show current structure
    echo "<h2>Current Courses Table Structure:</h2>";
    $descSql = "DESCRIBE courses";
    $stmt = $pdo->prepare($descSql);
    $stmt->execute();
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<pre>";
    printf("%-20s %-20s %-10s %-10s\n", "Field", "Type", "Null", "Key");
    echo str_repeat("-", 70) . "\n";
    foreach ($columns as $column) {
        printf("%-20s %-20s %-10s %-10s\n", 
            $column['Field'], 
            $column['Type'], 
            $column['Null'], 
            $column['Key']
        );
    }
    echo "</pre>";
    
    // Test query
    echo "<h2>Test: Recent Courses</h2>";
    $testSql = "SELECT id, course_code, course_name, lecturer_id, lecturer_name 
                FROM courses 
                ORDER BY created_at DESC 
                LIMIT 5";
    $stmt = $pdo->prepare($testSql);
    $stmt->execute();
    $courses = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (empty($courses)) {
        echo "<p>No courses found in the database yet.</p>";
    } else {
        echo "<pre>";
        printf("%-5s %-15s %-30s %-12s %-20s\n", "ID", "Code", "Name", "Lecturer ID", "Lecturer Name");
        echo str_repeat("-", 90) . "\n";
        foreach ($courses as $course) {
            printf("%-5s %-15s %-30s %-12s %-20s\n", 
                $course['id'], 
                substr($course['course_code'], 0, 15),
                substr($course['course_name'], 0, 30),
                $course['lecturer_id'] ?? 'NULL',
                substr($course['lecturer_name'] ?? 'NULL', 0, 20)
            );
        }
        echo "</pre>";
    }
    
    echo "<div class='success'>
            <strong>✅ All Done!</strong><br>
            You can now add courses with lecturer names. The system will work correctly.
          </div>";
    
    echo "<a href='" . url('admin/departments/computer-science') . "' class='btn'>Go to Admin Dashboard</a>";
    
} catch (Exception $e) {
    echo "<div class='error'>
            <strong>❌ Error:</strong><br>
            " . htmlspecialchars($e->getMessage()) . "
          </div>";
    
    echo "<h3>Manual SQL (if needed):</h3>";
    echo "<p>If the automatic update failed, you can run this SQL manually:</p>";
    echo "<pre>ALTER TABLE courses ADD COLUMN lecturer_name VARCHAR(255) NULL AFTER lecturer_id;</pre>";
}

echo "    </div>
</body>
</html>";
