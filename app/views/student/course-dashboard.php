<div class="container-fluid px-4">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-md-8">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= url('student/dashboard') ?>">Dashboard</a></li>
                    <li class="breadcrumb-item active"><?= e($course['course_code']) ?></li>
                </ol>
            </nav>
            <h2><i class="bi bi-book"></i> <?= e($course['course_code']) ?> - <?= e($course['course_name']) ?></h2>
            <p class="text-muted">
                <?= e($course['semester']) ?>, <?= e($course['academic_year']) ?>
                <br>
                <i class="bi bi-person"></i> <strong><?= e($course['lecturer_name'] ?? 'Not Assigned') ?></strong>
                <?php if (!empty($course['lecturer_email'])): ?>
                    | <i class="bi bi-envelope"></i> <a href="mailto:<?= e($course['lecturer_email']) ?>"><?= e($course['lecturer_email']) ?></a>
                <?php endif; ?>
            </p>
        </div>
        <div class="col-md-4 text-md-end mt-3 mt-md-0">
            <a href="<?= url('student/attendance/scan') ?>" class="btn btn-primary btn-lg pulse-button w-100 w-md-auto">
                <i class="bi bi-qr-code-scan"></i> Scan QR Code
            </a>
        </div>
    </div>
    
    <!-- Active Sessions Alert -->
    <?php if (!empty($stats['active_sessions'])): ?>
    <div class="row mb-4">
        <div class="col-12">
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <h5 class="alert-heading"><i class="bi bi-broadcast"></i> Active Session Available!</h5>
                <p class="mb-0">
                    You have <strong><?= count($stats['active_sessions']) ?></strong> active session(s) in this course. 
                    <a href="<?= url('student/attendance/scan') ?>" class="alert-link">Scan QR code now</a>
                </p>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    </div>
    <?php endif; ?>
    
    <!-- Statistics Cards -->
    <div class="row mb-4">
        <!-- Total Sessions -->
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card stat-card stat-info h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="stat-label mb-1">Total Sessions</p>
                            <h2 class="stat-value mb-0"><?= number_format($stats['total_sessions']) ?></h2>
                            <small class="text-white-50">All sessions</small>
                        </div>
                        <i class="bi bi-calendar3 stat-icon"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Attended -->
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card stat-card stat-success h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="stat-label mb-1">Attended</p>
                            <h2 class="stat-value mb-0"><?= number_format($stats['attended']) ?></h2>
                            <small class="text-white-50">Marked present</small>
                        </div>
                        <i class="bi bi-check-circle stat-icon"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Missed -->
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card stat-card stat-<?= $stats['missed'] > 0 ? 'danger' : 'secondary' ?> h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="stat-label mb-1">Missed</p>
                            <h2 class="stat-value mb-0"><?= number_format($stats['missed']) ?></h2>
                            <small class="text-white-50">Absent sessions</small>
                        </div>
                        <i class="bi bi-x-circle stat-icon"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Attendance Rate -->
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card stat-card stat-<?= $stats['attendance_rate'] >= 75 ? 'success' : ($stats['attendance_rate'] >= 50 ? 'warning' : 'danger') ?> h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="stat-label mb-1">Attendance Rate</p>
                            <h2 class="stat-value mb-0"><?= number_format($stats['attendance_rate'], 1) ?>%</h2>
                            <small class="text-white-50">
                                <?php if ($stats['attendance_rate'] >= 75): ?>
                                    <i class="bi bi-emoji-smile"></i> Excellent!
                                <?php elseif ($stats['attendance_rate'] >= 50): ?>
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
        <!-- Left Column - Session List -->
        <div class="col-lg-8 mb-4">
            
            <!-- Active Sessions -->
            <?php if (!empty($stats['active_sessions'])): ?>
            <div class="card mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-broadcast"></i> Active Sessions - Mark Attendance Now!
                        <span class="badge bg-light text-success float-end"><?= count($stats['active_sessions']) ?></span>
                    </h5>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        <?php foreach ($stats['active_sessions'] as $session): ?>
                        <div class="list-group-item">
                            <div class="row align-items-center">
                                <div class="col-md-1 text-center">
                                    <i class="bi bi-qr-code display-6 text-success"></i>
                                </div>
                                <div class="col-md-7">
                                    <h6 class="mb-1 fw-bold"><?= e($session['session_name']) ?></h6>
                                    <p class="mb-1 text-muted">
                                        <i class="bi bi-calendar3"></i> <?= date('l, F j, Y', strtotime($session['session_date'])) ?>
                                    </p>
                                    <small class="text-muted">
                                        <i class="bi bi-clock"></i> <?= date('h:i A', strtotime($session['start_time'])) ?>
                                        <?php if ($session['end_time']): ?>
                                            - <?= date('h:i A', strtotime($session['end_time'])) ?>
                                        <?php else: ?>
                                            - <span class="text-warning">In Progress</span>
                                        <?php endif; ?>
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
            
            <!-- All Sessions -->
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-list-ul"></i> All Sessions</h5>
                    <span class="badge bg-secondary"><?= count($stats['all_sessions']) ?> Total</span>
                </div>
                <div class="card-body p-0">
                    <?php if (empty($stats['all_sessions'])): ?>
                        <div class="text-center py-5">
                            <i class="bi bi-inbox display-1 text-muted"></i>
                            <p class="text-muted mt-3">No sessions have been created for this course yet.</p>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th>Session</th>
                                        <th>Date & Time</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-center">Attendance Time</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($stats['all_sessions'] as $session): ?>
                                    <tr>
                                        <td>
                                            <strong><?= e($session['session_name']) ?></strong>
                                        </td>
                                        <td>
                                            <div>
                                                <i class="bi bi-calendar3"></i> <?= date('M d, Y', strtotime($session['session_date'])) ?>
                                                <br>
                                                <small class="text-muted">
                                                    <i class="bi bi-clock"></i> <?= date('h:i A', strtotime($session['start_time'])) ?>
                                                    <?php if ($session['end_time']): ?>
                                                        - <?= date('h:i A', strtotime($session['end_time'])) ?>
                                                    <?php else: ?>
                                                        - <span class="text-warning">In Progress</span>
                                                    <?php endif; ?>
                                                </small>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <?php if ($session['status'] === 'present'): ?>
                                                <span class="badge bg-success">
                                                    <i class="bi bi-check-circle-fill"></i> Present
                                                </span>
                                            <?php elseif ($session['status'] === 'active'): ?>
                                                <span class="badge bg-warning">
                                                    <i class="bi bi-broadcast"></i> Active - Scan Now
                                                </span>
                                            <?php elseif ($session['status'] === 'missed'): ?>
                                                <span class="badge bg-danger">
                                                    <i class="bi bi-x-circle-fill"></i> Missed
                                                </span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary">
                                                    <i class="bi bi-clock"></i> Upcoming
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center">
                                            <?php if ($session['attendance_time']): ?>
                                                <small class="text-success">
                                                    <i class="bi bi-check-circle"></i>
                                                    <?= date('h:i A', strtotime($session['attendance_time'])) ?>
                                                </small>
                                            <?php else: ?>
                                                <span class="text-muted">-</span>
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
        
        <!-- Right Column - Charts & Statistics -->
        <div class="col-lg-4 mb-4">
            
            <!-- Attendance Summary -->
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-pie-chart"></i> Attendance Summary</h5>
                </div>
                <div class="card-body">
                    <canvas id="attendanceDonutChart" height="200"></canvas>
                    <div class="mt-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span><i class="bi bi-circle-fill text-success"></i> Present</span>
                            <strong><?= $stats['attended'] ?></strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span><i class="bi bi-circle-fill text-danger"></i> Missed</span>
                            <strong><?= $stats['missed'] ?></strong>
                        </div>
                        <?php if ($stats['active'] > 0): ?>
                        <div class="d-flex justify-content-between mb-2">
                            <span><i class="bi bi-circle-fill text-warning"></i> Active</span>
                            <strong><?= $stats['active'] ?></strong>
                        </div>
                        <?php endif; ?>
                        <?php if ($stats['upcoming'] > 0): ?>
                        <div class="d-flex justify-content-between">
                            <span><i class="bi bi-circle-fill text-secondary"></i> Upcoming</span>
                            <strong><?= $stats['upcoming'] ?></strong>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <!-- Monthly Trend -->
            <?php if (!empty($stats['monthly_trend'])): ?>
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-graph-up"></i> Monthly Attendance Trend</h5>
                </div>
                <div class="card-body">
                    <canvas id="monthlyTrendChart" height="200"></canvas>
                </div>
            </div>
            <?php endif; ?>
            
            <!-- Weekly Pattern -->
            <?php if (!empty($stats['weekly_pattern'])): ?>
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-calendar-week"></i> Weekly Attendance Pattern</h5>
                </div>
                <div class="card-body">
                    <canvas id="weeklyPatternChart" height="200"></canvas>
                </div>
            </div>
            <?php endif; ?>
            
            <!-- Quick Actions -->
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-lightning-fill"></i> Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="<?= url('student/attendance/scan') ?>" class="btn btn-primary">
                            <i class="bi bi-qr-code-scan"></i> Scan QR Code
                        </a>
                        <a href="<?= url('student/dashboard') ?>" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left"></i> Back to Dashboard
                        </a>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</div>

<!-- Chart.js Scripts -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    
    // Attendance Donut Chart
    const donutCtx = document.getElementById('attendanceDonutChart');
    if (donutCtx) {
        new Chart(donutCtx, {
            type: 'doughnut',
            data: {
                labels: ['Present', 'Missed'<?= $stats['active'] > 0 ? ", 'Active'" : '' ?><?= $stats['upcoming'] > 0 ? ", 'Upcoming'" : '' ?>],
                datasets: [{
                    data: [<?= $stats['attended'] ?>, <?= $stats['missed'] ?><?= $stats['active'] > 0 ? ", {$stats['active']}" : '' ?><?= $stats['upcoming'] > 0 ? ", {$stats['upcoming']}" : '' ?>],
                    backgroundColor: [
                        'rgba(40, 167, 69, 0.8)',
                        'rgba(220, 53, 69, 0.8)'
                        <?= $stats['active'] > 0 ? ", 'rgba(255, 193, 7, 0.8)'" : '' ?>
                        <?= $stats['upcoming'] > 0 ? ", 'rgba(108, 117, 125, 0.8)'" : '' ?>
                    ],
                    borderColor: [
                        'rgb(40, 167, 69)',
                        'rgb(220, 53, 69)'
                        <?= $stats['active'] > 0 ? ", 'rgb(255, 193, 7)'" : '' ?>
                        <?= $stats['upcoming'] > 0 ? ", 'rgb(108, 117, 125)'" : '' ?>
                    ],
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    }
    
    <?php if (!empty($stats['monthly_trend'])): ?>
    // Monthly Trend Chart
    const monthlyCtx = document.getElementById('monthlyTrendChart');
    if (monthlyCtx) {
        const monthlyData = <?= json_encode(array_reverse($stats['monthly_trend'])) ?>;
        
        new Chart(monthlyCtx, {
            type: 'line',
            data: {
                labels: monthlyData.map(d => {
                    const date = new Date(d.month + '-01');
                    return date.toLocaleDateString('en-US', { month: 'short', year: 'numeric' });
                }),
                datasets: [{
                    label: 'Attendance Rate %',
                    data: monthlyData.map(d => {
                        return d.total_sessions > 0 ? ((d.attended_sessions / d.total_sessions) * 100).toFixed(1) : 0;
                    }),
                    borderColor: 'rgb(40, 167, 69)',
                    backgroundColor: 'rgba(40, 167, 69, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100,
                        ticks: {
                            callback: function(value) {
                                return value + '%';
                            }
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const index = context.dataIndex;
                                const attended = monthlyData[index].attended_sessions;
                                const total = monthlyData[index].total_sessions;
                                return `${attended}/${total} sessions (${context.parsed.y}%)`;
                            }
                        }
                    }
                }
            }
        });
    }
    <?php endif; ?>
    
    <?php if (!empty($stats['weekly_pattern'])): ?>
    // Weekly Pattern Chart
    const weeklyCtx = document.getElementById('weeklyPatternChart');
    if (weeklyCtx) {
        const weeklyData = <?= json_encode($stats['weekly_pattern']) ?>;
        
        new Chart(weeklyCtx, {
            type: 'bar',
            data: {
                labels: weeklyData.map(d => d.day_name),
                datasets: [{
                    label: 'Attendance Rate %',
                    data: weeklyData.map(d => {
                        return d.total_sessions > 0 ? ((d.attended_sessions / d.total_sessions) * 100).toFixed(1) : 0;
                    }),
                    backgroundColor: 'rgba(13, 110, 253, 0.7)',
                    borderColor: 'rgb(13, 110, 253)',
                    borderWidth: 2,
                    borderRadius: 5
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100,
                        ticks: {
                            callback: function(value) {
                                return value + '%';
                            }
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const index = context.dataIndex;
                                const attended = weeklyData[index].attended_sessions;
                                const total = weeklyData[index].total_sessions;
                                return `${attended}/${total} sessions (${context.parsed.y}%)`;
                            }
                        }
                    }
                }
            }
        });
    }
    <?php endif; ?>
});
</script>
