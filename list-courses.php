<?php
require 'config/config.php';
require 'config/database.php';

$db = Database::getInstance();
$courses = $db->query('SELECT * FROM courses WHERE is_active = 1 ORDER BY course_code');

echo "Available Courses:\n";
echo "==================\n";
foreach($courses as $c) {
    echo "ID: {$c['id']} - {$c['course_code']} - {$c['course_name']}\n";
}
?>