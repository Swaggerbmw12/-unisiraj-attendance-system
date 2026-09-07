<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <h2><i class="bi bi-speedometer2"></i> Student Dashboard</h2>
            <p class="text-muted">Welcome back, <?= e($_SESSION['user_name']) ?>! (<?= e($_SESSION['student_number']) ?>)</p>
        </div>
    </div>
    
    <!-- Statistics Cards -->
    <div class="row mb-4">
        <!-- Enrolled Courses -->
        <div class="col-md-4 mb-3">
            <div class="card stat-card stat-primary">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="stat-label mb-1">Enrolled Courses</p>
                            <h2 class="stat-value mb-0"><?= number_format($stats['courses_count']) ?></h2>
                        </div>
                        <i class="bi bi-book stat-icon text-primary"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Total Attendance -->
        <div class="col-md-4 mb-3">
            <div class="card stat-card stat-success">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="stat-label mb-1">Total Attendance</p>
                            <h2 class="stat-value mb-0"><?= number_format($stats['attendance_count']) ?></h2>
                        </div>
                        <i class="bi bi-check-circle stat-icon text-success"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Overall Attendance Percentage -->
        <div class="col-md-4 mb-3">
            <div class="card stat-card stat-<?= $stats['overall_percentage'] >= 75 ? 'success' : ($stats['overall_percentage'] >= 50 ? 'warning' : 'danger') ?>">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="stat-label mb-1">Overall Attendance</p>
                            <h2 class="stat-value mb-0"><?= number_format($stats['overall_percentage'], 1) ?>%</h2>
                        </div>
                        <i class="bi bi-graph-up-arrow stat-icon text-<?= $stats['overall_percentage'] >= 75 ? 'success' : ($stats['overall_percentage'] >= 50 ? 'warning' : 'danger') ?>"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Content Row -->
    <div class="row">
        <!-- My Courses with Attendance -->
        <div class="col-md-8 mb-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-book"></i> My Courses & Attendance</h5>
                    <a href="<?= url('student/attendance/scan') ?>" class="btn btn-sm btn-primary">
                        <i class="bi bi-qr-code-scan"></i> Scan QR Code
                    </a>
                </div>
                <div class="card-body">
                    <?php if (empty($stats['courses'])): ?>
                        <p class="text-muted text-center py-4">No courses enrolled yet.</p>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Course</th>
                                        <th>Lecturer</th>
                                        <th>Sessions</th>
                                        <th>Attended</th>
                                        <th>Percentage</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($stats['courses'] as $course): ?>
                                    <tr>
                                        <td>
                                            <strong><?= e($course['course_code']) ?></strong><br>
                                            <small class="text-muted"><?= e($course['course_name']) ?></small>
                                        </td>
                                        <td><?= e($course['lecturer_name'] ?? 'Not Assigned') ?></td>
                                        <td><?= number_format($course['total_sessions']) ?></td>
                                        <td><?= number_format($course['attended_sessions']) ?></td>
                                        <td>
                                            <strong class="text-<?= $course['attendance_percentage'] >= 75 ? 'success' : ($course['attendance_percentage'] >= 50 ? 'warning' : 'danger') ?>">
                                                <?= number_format($course['attendance_percentage'], 1) ?>%
                                            </strong>
                                        </td>
                                        <td>
                                            <?php if ($course['attendance_percentage'] >= 75): ?>
                                                <span class="badge bg-success"><i class="bi bi-check-circle"></i> Good</span>
                                            <?php elseif ($course['attendance_percentage'] >= 50): ?>
                                                <span class="badge bg-warning"><i class="bi bi-exclamation-triangle"></i> Fair</span>
                                            <?php else: ?>
                                                <span class="badge bg-danger"><i class="bi bi-x-circle"></i> Low</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <!-- Recent Attendance -->
        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-clock-history"></i> Recent Attendance</h5>
                </div>
                <div class="card-body">
                    <?php if (empty($stats['recent_attendance'])): ?>
                        <p class="text-muted text-center py-4">No attendance records yet.</p>
                    <?php else: ?>
                        <div class="list-group">
                            <?php foreach ($stats['recent_attendance'] as $record): ?>
                            <div class="list-group-item">
                                <div class="d-flex justify-content-between align-items-start mb-1">
                                    <h6 class="mb-0"><?= e($record['course_code']) ?></h6>
                                    <span class="badge bg-success"><i class="bi bi-check-circle"></i></span>
                                </div>
                                <p class="mb-1 small"><?= e($record['session_name']) ?></p>
                                <small class="text-muted">
                                    <?= date('M d, Y - h:i A', strtotime($record['attendance_time'])) ?>
                                </small>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Quick Actions -->
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-lightning"></i> Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="<?= url('student/attendance/scan') ?>" class="btn btn-primary">
                            <i class="bi bi-qr-code-scan"></i> Scan QR Code
                        </a>
                        <a href="<?= url('student/attendance/history') ?>" class="btn btn-success">
                            <i class="bi bi-clock-history"></i> View Full History
                        </a>
                        <a href="<?= url('change-password') ?>" class="btn btn-warning">
                            <i class="bi bi-key"></i> Change Password
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
