<?php
require 'config/config.php';
require 'config/database.php';

$db = Database::getInstance();

echo "Lecturers:\n";
$lecturers = $db->query("SELECT * FROM lecturers");
foreach($lecturers as $l) {
    echo "ID: {$l['id']}, Name: {$l['first_name']} {$l['last_name']}\n";
}

echo "\nCourses:\n";
$courses = $db->query("SELECT * FROM courses WHERE is_active = 1");
foreach($courses as $c) {
    echo "ID: {$c['id']}, Code: {$c['course_code']}, Lecturer ID: {$c['lecturer_id']}\n";
}
?>