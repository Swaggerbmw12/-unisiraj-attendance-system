<?php
// Get lecturer's courses for sidebar
$lecturerId = $_SESSION['profile_id'] ?? null;
$currentCourseId = $_GET['id'] ?? null;

$lecturerCourses = [];
if ($lecturerId) {
    try {
        $db = Database::getInstance();
        $lecturerCourses = $db->query("
            SELECT c.id, c.course_code, c.course_name, c.semester, c.academic_year,
                   (SELECT COUNT(*) FROM enrollments WHERE course_id = c.id AND status = 'active') as enrolled_students,
                   (SELECT COUNT(*) FROM attendance_sessions WHERE course_id = c.id AND is_active = 1) as active_sessions
            FROM courses c
            WHERE c.lecturer_id = ? AND c.is_active = 1 AND c.archived = 0
            ORDER BY c.course_code
        ", [$lecturerId]);
    } catch (Exception $e) {
        error_log('Sidebar courses error: ' . $e->getMessage());
    }
}
?>

<!-- Lecturer Sidebar -->
<div class="lecturer-sidebar bg-light border-end" id="lecturerSidebar">
    <div class="sidebar-header p-3 bg-primary text-white">
        <h5 class="mb-0">
            <i class="bi bi-book"></i> My Courses
        </h5>
    </div>
    
    <div class="sidebar-body">
        <!-- Quick Actions -->
        <div class="p-3">
            <a href="<?= url('lecturer/courses/create') ?>" class="btn btn-success w-100 mb-2" id="addCourseBtn">
                <i class="bi bi-plus-circle"></i> Add New Course
            </a>
            <a href="<?= url('analytics') ?>" class="btn btn-info w-100" id="analyticsBtn">
                <i class="bi bi-bar-chart-line"></i> Analytics Dashboard
            </a>
        </div>
        
        <hr class="my-0">
        
        <!-- Courses List -->
        <div class="courses-list">
            <?php if (empty($lecturerCourses)): ?>
                <div class="text-center p-4 text-muted">
                    <i class="bi bi-inbox display-4 d-block mb-2"></i>
                    <small>No courses yet</small>
                </div>
            <?php else: ?>
                <?php foreach ($lecturerCourses as $course): ?>
                <a href="<?= url('lecturer/courses/dashboard?id=' . $course['id']) ?>" 
                   class="course-item <?= ($currentCourseId == $course['id']) ? 'active' : '' ?>"
                   data-course-id="<?= $course['id'] ?>"
                   data-course-code="<?= e($course['course_code']) ?>">
                    <div class="course-info">
                        <div class="course-code"><?= e($course['course_code']) ?></div>
                        <div class="course-name"><?= e($course['course_name']) ?></div>
                        <div class="course-meta">
                            <small class="text-muted">
                                <i class="bi bi-people"></i> <?= $course['enrolled_students'] ?> students
                                <?php if ($course['active_sessions'] > 0): ?>
                                    <span class="badge bg-success ms-1">
                                        <i class="bi bi-broadcast"></i> <?= $course['active_sessions'] ?>
                                    </span>
                                <?php endif; ?>
                            </small>
                        </div>
                    </div>
                </a>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Sidebar Styles -->
<style>
/* Sidebar container styling */
.lecturer-sidebar {
    width: 100%;
    max-width: 280px;
    min-height: calc(100vh - 56px);
    overflow-y: auto;
    background-color: #f8f9fa;
}

.sidebar-header {
    position: sticky;
    top: 0;
    z-index: 10;
}

.courses-list {
    padding: 0;
}

.course-item {
    display: block;
    padding: 15px 20px;
    border-left: 4px solid transparent;
    border-bottom: 1px solid #e9ecef;
    text-decoration: none;
    color: inherit;
    transition: all 0.3s ease;
}

.course-item:hover {
    background-color: #e9ecef;
    border-left-color: #0d6efd;
    padding-left: 25px;
}

.course-item.active {
    background-color: #e7f3ff;
    border-left-color: #0d6efd;
    font-weight: 600;
}

.course-code {
    font-weight: 700;
    color: #0d6efd;
    margin-bottom: 5px;
}

.course-name {
    font-size: 0.9rem;
    color: #495057;
    margin-bottom: 5px;
    line-height: 1.3;
}

.course-meta {
    font-size: 0.85rem;
}

/* Scrollbar styling */
.lecturer-sidebar::-webkit-scrollbar {
    width: 6px;
}

.lecturer-sidebar::-webkit-scrollbar-track {
    background: #f1f1f1;
}

.lecturer-sidebar::-webkit-scrollbar-thumb {
    background: #888;
    border-radius: 3px;
}

.lecturer-sidebar::-webkit-scrollbar-thumb:hover {
    background: #555;
}
</style>

<!-- JavaScript for handling course navigation -->
<script>
// Simple navigation - no complex checks
document.addEventListener('DOMContentLoaded', function() {
    const courseLinks = document.querySelectorAll('.course-item');
    
    courseLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            // Just let normal navigation happen
            // The server cache headers will handle preventing caching
        });
    });
});
</script>
