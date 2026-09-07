<div class="d-flex">
    <!-- Include Sidebar -->
    <?php require VIEW_PATH . '/layouts/lecturer-sidebar.php'; ?>
    
    <!-- Main Content Area -->
    <div class="flex-grow-1 p-4">
        <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-md-8">
            <h2><i class="bi bi-speedometer2"></i> Lecturer Dashboard</h2>
            <p class="text-muted">Welcome back, <?= e($_SESSION['user_name']) ?>! <small class="text-muted"><?= date('l, F j, Y') ?></small></p>
        </div>
        <div class="col-md-4 text-end">
            <a href="<?= url('lecturer/sessions/create') ?>" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Create New Session
            </a>
        </div>
    </div>
    
    <!-- Statistics Cards -->
    <div class="row mb-4">
        <!-- My Courses -->
        <div class="col-lg-4 col-md-6 mb-3">
            <div class="card stat-card stat-primary h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="stat-label mb-1">My Courses</p>
                            <h2 class="stat-value mb-0"><?= number_format($stats['courses_count']) ?></h2>
                            <small class="text-muted">Active courses</small>
                        </div>
                        <i class="bi bi-book stat-icon text-primary"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Total Students -->
        <div class="col-lg-4 col-md-6 mb-3">
            <div class="card stat-card stat-info h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="stat-label mb-1">Total Students</p>
                            <h2 class="stat-value mb-0"><?= number_format($stats['total_students']) ?></h2>
                            <small class="text-muted">Enrolled students</small>
                        </div>
                        <i class="bi bi-people stat-icon text-info"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Total Sessions Created -->
        <div class="col-lg-4 col-md-6 mb-3">
            <div class="card stat-card stat-success h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="stat-label mb-1">Total Sessions</p>
                            <h2 class="stat-value mb-0"><?= number_format($stats['sessions_count']) ?></h2>
                            <small class="text-muted">All time</small>
                        </div>
                        <i class="bi bi-qr-code stat-icon text-success"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Content Row -->
    <div class="row">
        <!-- Left Column - My Courses & Upcoming Sessions -->
        <div class="col-lg-8 mb-4">
            
            <!-- Upcoming Sessions This Week -->
            <?php if (!empty($stats['upcoming_sessions'])): ?>
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-calendar-week"></i> Upcoming Sessions This Week</h5>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        <?php foreach ($stats['upcoming_sessions'] as $session): ?>
                        <div class="list-group-item px-0">
                            <div class="row align-items-center">
                                <div class="col-md-2 text-center">
                                    <div class="bg-light rounded p-2">
                                        <div class="fw-bold text-primary"><?= date('d', strtotime($session['session_date'])) ?></div>
                                        <small class="text-muted"><?= date('M', strtotime($session['session_date'])) ?></small>
                                    </div>
                                </div>
                                <div class="col-md-7">
                                    <h6 class="mb-1"><?= e($session['session_name']) ?></h6>
                                    <p class="mb-0 text-muted small">
                                        <i class="bi bi-book"></i> <?= e($session['course_code']) ?> - <?= e($session['course_name']) ?>
                                    </p>
                                </div>
                                <div class="col-md-3 text-end">
                                    <span class="badge bg-info">
                                        <i class="bi bi-clock"></i> <?= date('H:i', strtotime($session['start_time'])) ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>
            
            <!-- My Courses -->
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-book"></i> My Courses</h5>
                    <div>
                        <span class="badge bg-secondary"><?= count($stats['courses']) ?> Courses</span>
                    </div>
                </div>
                <div class="card-body">
                    <?php if (empty($stats['courses'])): ?>
                        <div class="text-center py-5">
                            <i class="bi bi-book display-1 text-muted"></i>
                            <p class="text-muted mt-3">No courses assigned yet.</p>
                            <p class="text-muted">Contact administrator to assign courses.</p>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Course Code</th>
                                        <th>Course Name</th>
                                        <th>Semester</th>
                                        <th class="text-center">Students</th>
                                        <th class="text-center">Sessions</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($stats['courses'] as $course): ?>
                                    <tr>
                                        <td>
                                            <strong class="text-primary"><?= e($course['course_code']) ?></strong>
                                        </td>
                                        <td><?= e($course['course_name']) ?></td>
                                        <td>
                                            <small class="text-muted">
                                                <?= e($course['semester']) ?><br>
                                                <?= e($course['academic_year']) ?>
                                            </small>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-info"><?= number_format($course['enrolled_students']) ?></span>
                                        </td>
                                        <td class="text-center">
                                            <?php if ($course['active_sessions'] > 0): ?>
                                                <span class="badge bg-success" title="Active sessions">
                                                    <?= $course['active_sessions'] ?> active
                                                </span>
                                            <?php else: ?>
                                                <span class="text-muted small"><?= $course['total_sessions'] ?> total</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group" role="group">
                                                <a href="<?= url('lecturer/courses/dashboard?id=' . $course['id']) ?>" 
                                                   class="btn btn-sm btn-primary" title="View dashboard">
                                                    <i class="bi bi-eye"></i> View
                                                </a>
                                                <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false">
                                                    <span class="visually-hidden">More actions</span>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <li><a class="dropdown-item" href="<?= url('lecturer/sessions/create?course=' . $course['id']) ?>">
                                                        <i class="bi bi-qr-code"></i> New Session
                                                    </a></li>
                                                    <li><hr class="dropdown-divider"></li>
                                                    <li><a class="dropdown-item text-danger" href="#" onclick="deleteCourseFromList(<?= $course['id'] ?>, '<?= e($course['course_code']) ?>'); return false;">
                                                        <i class="bi bi-trash"></i> Delete
                                                    </a></li>
                                                </ul>
                                            </div>
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
        
        <!-- Right Column - Recent Sessions & Quick Actions -->
        <div class="col-lg-4 mb-4">
            
            <!-- Quick Actions -->
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-lightning-fill"></i> Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="<?= url('lecturer/sessions/create') ?>" class="btn btn-primary btn-lg">
                            <i class="bi bi-qr-code"></i> Create New Session
                        </a>
                        <a href="<?= url('lecturer/sessions') ?>" class="btn btn-outline-success">
                            <i class="bi bi-list-ul"></i> View All Sessions
                        </a>
                        <a href="<?= url('reports') ?>" class="btn btn-outline-info">
                            <i class="bi bi-file-earmark-text"></i> View Reports
                        </a>
                        <a href="<?= url('analytics') ?>" class="btn btn-outline-secondary">
                            <i class="bi bi-graph-up"></i> Analytics
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Recent Sessions -->
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-clock-history"></i> Recent Sessions</h5>
                    <a href="<?= url('lecturer/sessions') ?>" class="btn btn-sm btn-outline-primary">View All</a>
                </div>
                <div class="card-body p-0">
                    <?php if (empty($stats['recent_sessions'])): ?>
                        <div class="text-center py-5">
                            <i class="bi bi-qr-code display-3 text-muted"></i>
                            <p class="text-muted mt-3">No sessions created yet.</p>
                            <a href="<?= url('lecturer/sessions/create') ?>" class="btn btn-sm btn-primary mt-2">
                                <i class="bi bi-plus-circle"></i> Create Your First Session
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="list-group list-group-flush">
                            <?php foreach ($stats['recent_sessions'] as $session): ?>
                            <a href="<?= url('lecturer/sessions/view?id=' . $session['id']) ?>" 
                               class="list-group-item list-group-item-action">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h6 class="mb-0 fw-bold"><?= e($session['course_code']) ?></h6>
                                    <?php if ($session['is_active']): ?>
                                        <span class="badge bg-success">
                                            <i class="bi bi-broadcast"></i> Active
                                        </span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Closed</span>
                                    <?php endif; ?>
                                </div>
                                <p class="mb-2 small text-truncate"><?= e($session['session_name']) ?></p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <small class="text-muted">
                                        <i class="bi bi-calendar3"></i> 
                                        <?= date('M d, Y', strtotime($session['session_date'])) ?>
                                    </small>
                                    <div>
                                        <?php 
                                        $attendance_percentage = $session['total_students'] > 0 
                                            ? round(($session['attendees'] / $session['total_students']) * 100) 
                                            : 0;
                                        $badge_color = $attendance_percentage >= 75 ? 'success' : ($attendance_percentage >= 50 ? 'warning' : 'danger');
                                        ?>
                                        <span class="badge bg-<?= $badge_color ?>">
                                            <?= number_format($session['attendees']) ?>/<?= number_format($session['total_students']) ?> 
                                            (<?= $attendance_percentage ?>%)
                                        </span>
                                    </div>
                                </div>
                            </a>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Attendance Trend Chart Placeholder -->
            <?php if (!empty($stats['weekly_trend'])): ?>
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-graph-up"></i> Weekly Attendance Trend</h5>
                </div>
                <div class="card-body">
                    <canvas id="weeklyTrendChart" height="200"></canvas>
                </div>
            </div>
            <?php endif; ?>
            
        </div>
    </div>
</div>

<?php if (!empty($stats['weekly_trend'])): ?>
<!-- Chart.js Script for Weekly Trend -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('weeklyTrendChart');
    if (ctx) {
        const weeklyData = <?= json_encode($stats['weekly_trend']) ?>;
        
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: weeklyData.map(d => {
                    const date = new Date(d.date);
                    return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
                }),
                datasets: [{
                    label: 'Total Attendance',
                    data: weeklyData.map(d => d.total_attendance),
                    borderColor: 'rgb(75, 192, 192)',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    tension: 0.3,
                    fill: true
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
                                return 'Attendance: ' + context.parsed.y + ' students';
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
<script src="<?= asset('js/lecturer-dashboard.js') ?>"></script>


<script>
function deleteCourseFromList(courseId, courseCode) {
    if (!confirm(`⚠️ WARNING: Are you sure you want to delete "${courseCode}"?\n\nThis action cannot be undone!\n\nNote: If the course has attendance sessions, it will be archived instead of deleted.`)) {
        return;
    }
    
    fetch('<?= url('lecturer/courses/delete') ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: 'course_id=' + courseId
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('✓ ' + data.message);
            // Reload the page to update the course list
            window.location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while deleting the course');
    });
}
</script>
