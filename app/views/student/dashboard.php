<div class="container-fluid px-4">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-md-8">
            <h2><i class="bi bi-speedometer2"></i> Student Dashboard</h2>
            <p class="text-muted">
                Welcome back, <strong><?= e($_SESSION['user_name']) ?></strong>! 
                <small class="text-muted">(<?= e($_SESSION['student_number'] ?? 'N/A') ?>)</small>
                <br>
                <small><?= date('l, F j, Y') ?></small>
            </p>
        </div>
        <div class="col-md-4 text-end">
            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                <a href="<?= url('student/attendance/scan') ?>" class="btn btn-primary btn-lg pulse-button">
                    <i class="bi bi-qr-code-scan"></i> <span class="d-none d-sm-inline">Scan QR Code</span><span class="d-inline d-sm-none">Scan</span>
                </a>
                <a href="<?= url('student/enrollment') ?>" class="btn btn-success btn-lg">
                    <i class="bi bi-journal-plus"></i> <span class="d-none d-sm-inline">Enroll in Courses</span><span class="d-inline d-sm-none">Enroll</span>
                </a>
            </div>
        </div>
    </div>
    
    <!-- Active Sessions Alert -->
    <?php if (!empty($stats['active_sessions'])): ?>
    <div class="row mb-4">
        <div class="col-12">
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <h5 class="alert-heading"><i class="bi bi-broadcast"></i> Active Sessions Available!</h5>
                <p class="mb-0">
                    You have <strong><?= count($stats['active_sessions']) ?></strong> active session(s) waiting for attendance. 
                    <a href="<?= url('student/attendance/scan') ?>" class="alert-link">Scan QR code now</a>
                </p>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    </div>
    <?php endif; ?>
    
    <!-- Statistics Cards -->
    <div class="row mb-4">
        <!-- Enrolled Courses -->
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card stat-card stat-primary h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="stat-label mb-1">My Courses</p>
                            <h2 class="stat-value mb-0"><?= number_format($stats['courses_count']) ?></h2>
                            <small class="text-white-50">Enrolled</small>
                        </div>
                        <i class="bi bi-book stat-icon"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Total Attendance -->
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card stat-card stat-success h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="stat-label mb-1">Attended</p>
                            <h2 class="stat-value mb-0"><?= number_format($stats['attendance_count']) ?></h2>
                            <small class="text-white-50">of <?= number_format($stats['total_sessions']) ?> sessions</small>
                        </div>
                        <i class="bi bi-check-circle stat-icon"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Missed Sessions -->
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card stat-card stat-warning h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="stat-label mb-1">Missed</p>
                            <h2 class="stat-value mb-0"><?= number_format($stats['missed_count']) ?></h2>
                            <small class="text-white-50">Sessions</small>
                        </div>
                        <i class="bi bi-exclamation-triangle stat-icon"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Overall Attendance Percentage -->
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card stat-card stat-<?= $stats['overall_percentage'] >= 75 ? 'success' : ($stats['overall_percentage'] >= 50 ? 'warning' : 'danger') ?> h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="stat-label mb-1">Overall Rate</p>
                            <h2 class="stat-value mb-0"><?= number_format($stats['overall_percentage'], 1) ?>%</h2>
                            <small class="text-white-50">
                                <?php if ($stats['overall_percentage'] >= 75): ?>
                                    <i class="bi bi-emoji-smile"></i> Excellent!
                                <?php elseif ($stats['overall_percentage'] >= 50): ?>
                                    <i class="bi bi-emoji-neutral"></i> Keep it up
                                <?php else: ?>
                                    <i class="bi bi-emoji-frown"></i> Needs improvement
                                <?php endif; ?>
                            </small>
                        </div>
                        <i class="bi bi-graph-up-arrow stat-icon"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
    <!-- Content Row -->
    <div class="row">
        <!-- Left Column - Courses & Active Sessions -->
        <div class="col-lg-8 mb-4">
            
            <!-- Active Sessions to Attend -->
            <?php if (!empty($stats['active_sessions'])): ?>
            <div class="card mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-broadcast"></i> Active Sessions - Scan Now!
                        <span class="badge bg-light text-success float-end"><?= count($stats['active_sessions']) ?></span>
                    </h5>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        <?php foreach ($stats['active_sessions'] as $session): ?>
                        <div class="list-group-item active-session-item">
                            <div class="row align-items-center">
                                <div class="col-md-1 text-center">
                                    <div class="session-icon">
                                        <i class="bi bi-qr-code display-6 text-success"></i>
                                    </div>
                                </div>
                                <div class="col-md-7">
                                    <h6 class="mb-1 fw-bold"><?= e($session['session_name']) ?></h6>
                                    <p class="mb-1 text-muted">
                                        <i class="bi bi-book"></i> <?= e($session['course_code']) ?> - <?= e($session['course_name']) ?>
                                    </p>
                                    <small class="text-muted">
                                        <i class="bi bi-person"></i> <?= e($session['lecturer_name']) ?>
                                    </small>
                                </div>
                                <div class="col-md-4 text-end">
                                    <div class="mb-2">
                                        <small class="text-danger">
                                            <i class="bi bi-clock-history"></i> 
                                            Expires: <?= date('h:i A', strtotime($session['expires_at'])) ?>
                                        </small>
                                    </div>
                                    <a href="<?= url('student/attendance/scan') ?>" class="btn btn-success btn-sm">
                                        <i class="bi bi-qr-code-scan"></i> Mark Attendance
                                    </a>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>
            
            <!-- My Courses & Attendance -->
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-book"></i> My Courses & Attendance</h5>
                    <span class="badge bg-secondary"><?= count($stats['courses']) ?> Courses</span>
                </div>
                <div class="card-body">
                    <?php if (empty($stats['courses'])): ?>
                        <div class="text-center py-5">
                            <i class="bi bi-inbox display-1 text-muted"></i>
                            <p class="text-muted mt-3">You are not enrolled in any courses yet.</p>
                            <p class="text-muted">Contact your administrator for course enrollment.</p>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead>
                                    <tr>
                                        <th>Course</th>
                                        <th>Lecturer</th>
                                        <th class="text-center">Sessions</th>
                                        <th class="text-center">Attended</th>
                                        <th class="text-center">Missed</th>
                                        <th class="text-center">Rate</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($stats['courses'] as $course): ?>
                                    <?php
                                        $missed = $course['total_sessions'] - $course['attended_sessions'];
                                        $percentage = $course['attendance_percentage'];
                                    ?>
                                    <tr>
                                        <td>
                                            <div>
                                                <a href="<?= url('student/course/dashboard?id=' . $course['course_id']) ?>" class="text-decoration-none">
                                                    <strong class="text-primary"><?= e($course['course_code']) ?></strong>
                                                </a>
                                                <br>
                                                <small class="text-muted"><?= e($course['course_name']) ?></small>
                                                <br>
                                                <small class="text-muted"><?= e($course['semester']) ?>, <?= e($course['academic_year']) ?></small>
                                            </div>
                                        </td>
                                        <td><?= e($course['lecturer_name'] ?? 'Not Assigned') ?></td>
                                        <td class="text-center">
                                            <span class="badge bg-secondary"><?= number_format($course['total_sessions']) ?></span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-success"><?= number_format($course['attended_sessions']) ?></span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-<?= $missed > 0 ? 'danger' : 'secondary' ?>"><?= number_format($missed) ?></span>
                                        </td>
                                        <td class="text-center">
                                            <div class="progress" style="height: 20px;">
                                                <div class="progress-bar bg-<?= $percentage >= 75 ? 'success' : ($percentage >= 50 ? 'warning' : 'danger') ?>" 
                                                     role="progressbar" 
                                                     style="width: <?= $percentage ?>%"
                                                     aria-valuenow="<?= $percentage ?>" 
                                                     aria-valuemin="0" 
                                                     aria-valuemax="100">
                                                    <?= number_format($percentage, 1) ?>%
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <?php if ($percentage >= 75): ?>
                                                <span class="badge bg-success">
                                                    <i class="bi bi-check-circle"></i> Excellent
                                                </span>
                                            <?php elseif ($percentage >= 50): ?>
                                                <span class="badge bg-warning">
                                                    <i class="bi bi-exclamation-triangle"></i> Fair
                                                </span>
                                            <?php else: ?>
                                                <span class="badge bg-danger">
                                                    <i class="bi bi-x-circle"></i> Low
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center">
                                            <a href="<?= url('student/course/dashboard?id=' . $course['course_id']) ?>" class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-eye"></i> View Details
                                            </a>
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

        
        <!-- Right Column - Quick Actions & Recent History -->
        <div class="col-lg-4 mb-4">
            
            <!-- Quick Actions -->
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-lightning-fill"></i> Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="<?= url('student/attendance/scan') ?>" class="btn btn-primary btn-lg">
                            <i class="bi bi-qr-code-scan"></i> Scan QR Code
                        </a>
                        <a href="<?= url('student/enrollment') ?>" class="btn btn-success">
                            <i class="bi bi-journal-plus"></i> Enroll in Courses
                        </a>
                        <a href="<?= url('student/attendance/history') ?>" class="btn btn-outline-info">
                            <i class="bi bi-clock-history"></i> Full Attendance History
                        </a>
                        <a href="<?= url('change-password') ?>" class="btn btn-outline-warning">
                            <i class="bi bi-key"></i> Change Password
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Recent Attendance -->
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-clock-history"></i> Recent Attendance</h5>
                    <a href="<?= url('student/attendance/history') ?>" class="btn btn-sm btn-outline-primary">View All</a>
                </div>
                <div class="card-body p-0">
                    <?php if (empty($stats['recent_attendance'])): ?>
                        <div class="text-center py-5">
                            <i class="bi bi-clock-history display-3 text-muted"></i>
                            <p class="text-muted mt-3 px-3">No attendance records yet.</p>
                            <a href="<?= url('student/attendance/scan') ?>" class="btn btn-sm btn-primary">
                                <i class="bi bi-qr-code-scan"></i> Mark Your First Attendance
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="list-group list-group-flush">
                            <?php foreach ($stats['recent_attendance'] as $index => $record): ?>
                            <?php if ($index < 7): ?>
                            <div class="list-group-item">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1 fw-bold text-success">
                                            <i class="bi bi-check-circle-fill"></i> <?= e($record['course_code']) ?>
                                        </h6>
                                        <p class="mb-1 small text-truncate"><?= e($record['session_name']) ?></p>
                                    </div>
                                </div>
                                <small class="text-muted">
                                    <i class="bi bi-calendar3"></i> 
                                    <?= date('M d, Y', strtotime($record['attendance_time'])) ?>
                                    <br>
                                    <i class="bi bi-clock"></i>
                                    <?= date('h:i A', strtotime($record['attendance_time'])) ?>
                                </small>
                            </div>
                            <?php endif; ?>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Attendance Trend Chart -->
            <?php if (!empty($stats['weekly_trend'])): ?>
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-graph-up"></i> 30-Day Attendance Trend</h5>
                </div>
                <div class="card-body">
                    <canvas id="attendanceTrendChart" height="200"></canvas>
                </div>
            </div>
            <?php endif; ?>
            
        </div>
    </div>
</div>

<?php if (!empty($stats['weekly_trend'])): ?>
<!-- Chart.js Script for Attendance Trend -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('attendanceTrendChart');
    if (ctx) {
        const trendData = <?= json_encode($stats['weekly_trend']) ?>;
        
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: trendData.map(d => {
                    const date = new Date(d.date);
                    return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
                }),
                datasets: [{
                    label: 'Sessions Attended',
                    data: trendData.map(d => d.attendance_count),
                    backgroundColor: 'rgba(40, 167, 69, 0.7)',
                    borderColor: 'rgb(40, 167, 69)',
                    borderWidth: 2,
                    borderRadius: 5
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return 'Attended: ' + context.parsed.y + ' session' + (context.parsed.y !== 1 ? 's' : '');
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1,
                            callback: function(value) {
                                return Math.floor(value);
                            }
                        }
                    }
                }
            }
        });
    }
});
</script>
<?php endif; ?>

<!-- Dashboard Custom JavaScript -->
<script src="<?= asset('js/student-dashboard.js') ?>"></script>
