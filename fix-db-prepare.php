<?php
/**
 * Fix Database prepare() calls
 * Replaces $this->db->prepare() with $this->db->getConnection()->prepare()
 */

$modelFiles = [
    'Student.php',
    'Lecturer.php',
    'Course.php',
    'Enrollment.php',
    'AttendanceSession.php',
    'AttendanceRecord.php'
];

$totalFixed = 0;

echo "Fixing database prepare() calls in all models...\n\n";

foreach ($modelFiles as $fileName) {
    $file = __DIR__ . '/app/models/' . $fileName;
    
    if (!file_exists($file)) {
        echo "✗ $fileName not found\n";
        continue;
    }
    
    $content = file_get_contents($file);
    $original = $content;
    
    // Replace all instances
    $content = str_replace('$this->db->prepare(', '$this->db->getConnection()->prepare(', $content);
    
    // Save the file
    file_put_contents($file, $content);
    
    // Count replacements
    $count = substr_count($original, '$this->db->prepare(');
    $totalFixed += $count;
    
    echo "✓ $fileName: Fixed $count instances\n";
}

echo "\n==========================================\n";
echo "✓ Total fixed: $totalFixed instances\n";
echo "✓ All models updated successfully!\n";
echo "==========================================\n";
