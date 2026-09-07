<div class="container-fluid py-4">
    <!-- Welcome Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-1">Welcome, <?= e($_SESSION['user_name'] ?? $_SESSION['email'] ?? 'Admin') ?>!</h4>
                    <p class="text-muted mb-0">UniSIRAJ Attendance Management System</p>
                </div>
                <div class="text-end">
                    <small class="text-muted"><?= date('l, F d, Y') ?></small>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Statistics Cards Row -->
    <div class="row g-3 mb-4">
        <!-- Students Card -->
        <div class="col-xl-3 col-md-6">
            <div class="card stat-card-modern card-students h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="stat-icon-modern">
                            <i class="bi bi-mortarboard-fill"></i>
                        </div>
                        <h2 class="stat-number mb-0"><?= number_format($stats['students_count']) ?></h2>
                    </div>
                    <h6 class="stat-title mb-0 mt-3">Students</h6>
                </div>
            </div>
        </div>
        
        <!-- Lecturers Card -->
        <div class="col-xl-3 col-md-6">
            <div class="card stat-card-modern card-lecturers h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="stat-icon-modern">
                            <i class="bi bi-person-workspace"></i>
                        </div>
                        <h2 class="stat-number mb-0"><?= number_format($stats['lecturers_count']) ?></h2>
                    </div>
                    <h6 class="stat-title mb-0 mt-3">Lecturers</h6>
                </div>
            </div>
        </div>
        
        <!-- Courses Card -->
        <div class="col-xl-3 col-md-6">
            <div class="card stat-card-modern card-courses h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="stat-icon-modern">
                            <i class="bi bi-journal-bookmark-fill"></i>
                        </div>
                        <h2 class="stat-number mb-0"><?= number_format($stats['courses_count']) ?></h2>
                    </div>
                    <h6 class="stat-title mb-0 mt-3">Courses</h6>
                </div>
            </div>
        </div>
        
        <!-- Today's Sessions Card -->
        <div class="col-xl-3 col-md-6">
            <div class="card stat-card-modern card-sessions h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="stat-icon-modern">
                            <i class="bi bi-calendar-check-fill"></i>
                        </div>
                        <h2 class="stat-number mb-0"><?= number_format($stats['today_sessions_count']) ?></h2>
                    </div>
                    <h6 class="stat-title mb-0 mt-3">Today's Sessions</h6>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Main Content Row -->
    <div class="row g-4">
        <!-- Left Column - Course Management -->
        <div class="col-xl-7">
            <!-- Courses by Department -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="mb-0 fw-bold">
                            <i class="bi bi-grid-3x3-gap-fill text-primary me-2"></i>
                            Course Management
                        </h6>
                        <a href="<?= url('admin/courses') ?>" class="btn btn-sm btn-outline-primary">
                            View All <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4" width="10%">#</th>
                                    <th width="20%">Course Code</th>
                                    <th width="35%">Course Name</th>
                                    <th width="20%">Lecturer</th>
                                    <th width="15%" class="text-center">Enrolled</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $courses = $this->db->query("
                                    SELECT c.id, c.course_code, c.course_name, c.semester, c.academic_year,
                                           CONCAT(l.first_name, ' ', l.last_name) as lecturer_name,
                                           (SELECT COUNT(*) FROM enrollments WHERE course_id = c.id AND status = 'active') as enrolled_count
                                    FROM courses c
                                    LEFT JOIN lecturers l ON c.lecturer_id = l.id
                                    WHERE c.is_active = 1
                                    ORDER BY c.course_code
                                    LIMIT 6
                                ");
                                
                                if (empty($courses)): ?>
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-4">
                                            No courses available
                                        </td>
                                    </tr>
                                <?php else: 
                                    $counter = 1;
                                    foreach ($courses as $course): ?>
                                    <tr>
                                        <td class="ps-4"><?= $counter++ ?></td>
                                        <td><span class="badge bg-primary-subtle text-primary"><?= e($course['course_code']) ?></span></td>
                                        <td>
                                            <div class="fw-semibold"><?= e($course['course_name']) ?></div>
                                            <small class="text-muted"><?= e($course['semester']) ?> - <?= e($course['academic_year']) ?></small>
                                        </td>
                                        <td>
                                            <small><?= e($course['lecturer_name'] ?? 'Not Assigned') ?></small>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-info"><?= number_format($course['enrolled_count']) ?></span>
                                        </td>
                                    </tr>
                                    <?php endforeach;
                                endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <!-- Recent Attendance Sessions -->
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="mb-0 fw-bold">
                            <i class="bi bi-clock-history text-success me-2"></i>
                            Recent Attendance Sessions
                        </h6>
                        <span class="badge bg-success-subtle text-success">Last 5 Sessions</span>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4" width="15%">Course</th>
                                    <th width="25%">Session Name</th>
                                    <th width="20%">Lecturer</th>
                                    <th width="15%">Date</th>
                                    <th width="15%" class="text-center">Attendance</th>
                                    <th width="10%" class="text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($stats['recent_sessions'])): ?>
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-4">
                                            No sessions recorded yet
                                        </td>
                                    </tr>
                                <?php else: 
                                    foreach ($stats['recent_sessions'] as $session): ?>
                                    <tr>
                                        <td class="ps-4">
                                            <span class="badge bg-secondary-subtle text-secondary"><?= e($session['course_code']) ?></span>
                                        </td>
                                        <td>
                                            <div class="fw-semibold"><?= e($session['session_name']) ?></div>
                                            <small class="text-muted"><?= e($session['course_name']) ?></small>
                                        </td>
                                        <td><small><?= e($session['lecturer_name']) ?></small></td>
                                        <td><small><?= date('M d, Y', strtotime($session['session_date'])) ?></small></td>
                                        <td class="text-center">
                                            <span class="badge bg-info"><?= number_format($session['attendees']) ?> students</span>
                                        </td>
                                        <td class="text-center">
                                            <?php if ($session['is_active']): ?>
                                                <span class="badge bg-success">
                                                    <i class="bi bi-circle-fill" style="font-size: 6px;"></i> Active
                                                </span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary">Closed</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <?php endforeach;
                                endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Right Column - Quick Actions & Recent Students -->
        <div class="col-xl-5">
            <!-- Quick Management Actions -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-bold">
                        <i class="bi bi-lightning-charge-fill text-warning me-2"></i>
                        Quick Management
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-6">
                            <a href="<?= url('admin/students') ?>" class="btn btn-outline-primary w-100 py-3 d-flex flex-column align-items-center">
                                <i class="bi bi-people-fill fs-3 mb-2"></i>
                                <span class="fw-semibold">Manage Students</span>
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="<?= url('admin/lecturers') ?>" class="btn btn-outline-success w-100 py-3 d-flex flex-column align-items-center">
                                <i class="bi bi-person-workspace fs-3 mb-2"></i>
                                <span class="fw-semibold">Manage Lecturers</span>
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="<?= url('admin/courses') ?>" class="btn btn-outline-warning w-100 py-3 d-flex flex-column align-items-center">
                                <i class="bi bi-journal-text fs-3 mb-2"></i>
                                <span class="fw-semibold">Manage Courses</span>
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="<?= url('admin/enrollments') ?>" class="btn btn-outline-info w-100 py-3 d-flex flex-column align-items-center">
                                <i class="bi bi-clipboard-check-fill fs-3 mb-2"></i>
                                <span class="fw-semibold">Enrollments</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Recent Student Registrations -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="mb-0 fw-bold">
                            <i class="bi bi-person-plus-fill text-primary me-2"></i>
                            Recent Students
                        </h6>
                        <a href="<?= url('admin/students') ?>" class="btn btn-sm btn-outline-primary">View All</a>
                    </div>
                </div>
                <div class="card-body">
                    <?php if (empty($stats['recent_students'])): ?>
                        <p class="text-muted text-center py-3 mb-0">No recent registrations</p>
                    <?php else: ?>
                        <div class="list-group list-group-flush">
                            <?php foreach ($stats['recent_students'] as $student): ?>
                            <div class="list-group-item px-0 py-3">
                                <div class="d-flex align-items-center">
                                    <div class="avatar-circle bg-primary-subtle text-primary me-3">
                                        <i class="bi bi-person-fill"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-0"><?= e($student['first_name'] . ' ' . $student['last_name']) ?></h6>
                                        <small class="text-muted">
                                            <?= e($student['student_id']) ?> • <?= e($student['program']) ?>
                                        </small>
                                    </div>
                                    <div class="text-end">
                                        <small class="text-muted"><?= date('M d', strtotime($student['created_at'])) ?></small>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Attendance Statistics -->
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-bold">
                        <i class="bi bi-bar-chart-line-fill text-info me-2"></i>
                        Attendance Overview
                    </h6>
                </div>
                <div class="card-body">
                    <?php 
                    // Get attendance statistics
                    $attendance_stats = $this->db->queryOne("
                        SELECT 
                            COUNT(DISTINCT ats.id) as total_sessions,
                            COUNT(DISTINCT ar.student_id) as unique_students,
                            COUNT(ar.id) as total_records,
                            ROUND(AVG(
                                (SELECT COUNT(*) FROM attendance_records WHERE session_id = ats.id) * 100.0 / 
                                NULLIF((SELECT COUNT(*) FROM enrollments WHERE course_id = ats.course_id AND status = 'active'), 0)
                            ), 1) as avg_attendance_rate
                        FROM attendance_sessions ats
                        LEFT JOIN attendance_records ar ON ats.id = ar.session_id
                        WHERE DATE(ats.session_date) >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
                    ");
                    ?>
                    <div class="row g-3 text-center">
                        <div class="col-6">
                            <div class="p-3 bg-light rounded">
                                <h4 class="mb-0 text-primary"><?= number_format($attendance_stats['total_sessions'] ?? 0) ?></h4>
                                <small class="text-muted">Sessions (30 days)</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-3 bg-light rounded">
                                <h4 class="mb-0 text-success"><?= number_format($attendance_stats['avg_attendance_rate'] ?? 0, 1) ?>%</h4>
                                <small class="text-muted">Avg. Attendance</small>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="p-3 bg-light rounded">
                                <h4 class="mb-0 text-info"><?= number_format($attendance_stats['total_records'] ?? 0) ?></h4>
                                <small class="text-muted">Total Attendance Records</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Modern Statistics Cards */
.stat-card-modern {
    border: none;
    border-radius: 12px;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.stat-card-modern:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.1) !important;
}

.card-students {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.card-lecturers {
    background: linear-gradient(135deg, #48c6ef 0%, #6f86d6 100%);
    color: white;
}

.card-courses {
    background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
    color: white;
}

.card-sessions {
    background: linear-gradient(135deg, #30cfd0 0%, #330867 100%);
    color: white;
}

.stat-icon-modern {
    width: 50px;
    height: 50px;
    background: rgba(255,255,255,0.2);
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
}

.stat-number {
    font-size: 2.5rem;
    font-weight: 700;
    line-height: 1;
}

.stat-title {
    font-size: 1rem;
    font-weight: 600;
    opacity: 0.95;
}

.stat-quote {
    font-size: 0.75rem;
    font-style: italic;
    opacity: 0.85;
    line-height: 1.4;
}

/* Card Shadows */
.card.shadow-sm {
    box-shadow: 0 2px 8px rgba(0,0,0,0.08) !important;
    border: none;
    border-radius: 10px;
}

/* Table Styles */
.table thead th {
    font-size: 0.813rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    border-bottom: 2px solid #dee2e6;
}

.table tbody tr {
    transition: background-color 0.2s ease;
}

.table-hover tbody tr:hover {
    background-color: #f8f9fa;
}

/* Avatar Circle */
.avatar-circle {
    width: 45px;
    height: 45px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    flex-shrink: 0;
}

/* Badge Styles */
.badge {
    padding: 0.4em 0.8em;
    font-weight: 500;
    font-size: 0.75rem;
}

/* Button Styles */
.btn-outline-primary:hover,
.btn-outline-success:hover,
.btn-outline-warning:hover,
.btn-outline-info:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}
</style>
