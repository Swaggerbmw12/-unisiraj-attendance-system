<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <h2><i class="bi bi-clock-history"></i> Attendance History</h2>
            <p class="text-muted">View your complete attendance records and statistics</p>
        </div>
    </div>
    
    <!-- Overall Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card stat-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="stat-label mb-1">Total Sessions</p>
                            <h3 class="stat-value mb-0"><?= number_format($totalSessions) ?></h3>
                        </div>
                        <i class="bi bi-calendar-event stat-icon text-primary"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-3">
            <div class="card stat-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="stat-label mb-1">Attended</p>
                            <h3 class="stat-value mb-0 text-success"><?= number_format($totalAttended) ?></h3>
                        </div>
                        <i class="bi bi-check-circle stat-icon text-success"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-3">
            <div class="card stat-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="stat-label mb-1">Missed</p>
                            <h3 class="stat-value mb-0 text-danger"><?= number_format($totalSessions - $totalAttended) ?></h3>
                        </div>
                        <i class="bi bi-x-circle stat-icon text-danger"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-3">
            <div class="card stat-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="stat-label mb-1">Overall Rate</p>
                            <h3 class="stat-value mb-0">
                                <?php 
                                $overallRate = $totalSessions > 0 ? round(($totalAttended / $totalSessions) * 100, 1) : 0;
                                $rateClass = $overallRate >= 75 ? 'success' : ($overallRate >= 50 ? 'warning' : 'danger');
                                ?>
                                <span class="text-<?= $rateClass ?>"><?= $overallRate ?>%</span>
                            </h3>
                        </div>
                        <i class="bi bi-graph-up-arrow stat-icon text-<?= $rateClass ?>"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Course-wise Statistics -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-bar-chart"></i> Course-wise Attendance</h5>
                </div>
                <div class="card-body">
                    <?php if (empty($stats)): ?>
                        <p class="text-muted text-center py-4">No attendance statistics available yet.</p>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Course</th>
                                        <th>Total Sessions</th>
                                        <th>Attended</th>
                                        <th>Missed</th>
                                        <th>Percentage</th>
                                        <th>Progress</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($stats as $stat): ?>
                                    <?php 
                                    $percentage = $stat['percentage'] ?? 0;
                                    $progressClass = $percentage >= 75 ? 'success' : ($percentage >= 50 ? 'warning' : 'danger');
                                    ?>
                                    <tr>
                                        <td>
                                            <strong><?= e($stat['course_code']) ?></strong><br>
                                            <small class="text-muted"><?= e($stat['course_name']) ?></small>
                                        </td>
                                        <td><?= number_format($stat['total_sessions']) ?></td>
                                        <td class="text-success"><?= number_format($stat['attended_sessions']) ?></td>
                                        <td class="text-danger"><?= number_format($stat['total_sessions'] - $stat['attended_sessions']) ?></td>
                                        <td>
                                            <strong class="text-<?= $progressClass ?>">
                                                <?= number_format($percentage, 1) ?>%
                                            </strong>
                                        </td>
                                        <td>
                                            <div class="progress" style="height: 25px;">
                                                <div class="progress-bar bg-<?= $progressClass ?>" 
                                                     role="progressbar" 
                                                     style="width: <?= $percentage ?>%"
                                                     aria-valuenow="<?= $percentage ?>" 
                                                     aria-valuemin="0" 
                                                     aria-valuemax="100">
                                                    <?= number_format($percentage, 1) ?>%
                                                </div>
                                            </div>
                                        </td>
                                        <td>
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
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Detailed Attendance History -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-list-check"></i> Attendance Records</h5>
                    <div>
                        <button class="btn btn-sm btn-light" onclick="window.print()">
                            <i class="bi bi-printer"></i> Print
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <?php if (empty($history)): ?>
                        <div class="text-center py-5">
                            <i class="bi bi-inbox" style="font-size: 64px; color: #dee2e6;"></i>
                            <p class="text-muted mt-3">No attendance records found.</p>
                            <a href="<?= url('student/attendance/scan') ?>" class="btn btn-primary">
                                <i class="bi bi-qr-code-scan"></i> Record Your First Attendance
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Date & Time</th>
                                        <th>Course</th>
                                        <th>Session Name</th>
                                        <th>Session Date</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($history as $index => $record): ?>
                                    <tr>
                                        <td><?= $index + 1 ?></td>
                                        <td>
                                            <i class="bi bi-clock"></i>
                                            <?= date('M d, Y', strtotime($record['attendance_time'])) ?><br>
                                            <small class="text-muted"><?= date('h:i A', strtotime($record['attendance_time'])) ?></small>
                                        </td>
                                        <td>
                                            <strong><?= e($record['course_code']) ?></strong><br>
                                            <small class="text-muted"><?= e($record['course_name']) ?></small>
                                        </td>
                                        <td><?= e($record['session_name'] ?? 'Regular Session') ?></td>
                                        <td>
                                            <i class="bi bi-calendar-event"></i>
                                            <?= date('M d, Y', strtotime($record['session_date'])) ?>
                                        </td>
                                        <td>
                                            <span class="badge bg-success">
                                                <i class="bi bi-check-circle-fill"></i> Present
                                            </span>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Pagination would go here if needed -->
                        <div class="mt-3 text-center text-muted">
                            <small>Showing <?= count($history) ?> most recent records</small>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Quick Actions -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="d-flex gap-2 justify-content-center">
                <a href="<?= url('student/dashboard') ?>" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Back to Dashboard
                </a>
                <a href="<?= url('student/attendance/scan') ?>" class="btn btn-primary">
                    <i class="bi bi-qr-code-scan"></i> Scan QR Code
                </a>
            </div>
        </div>
    </div>
</div>

<style>
.stat-card {
    border-left: 4px solid #0d6efd;
    transition: transform 0.2s, box-shadow 0.2s;
}

.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.stat-icon {
    font-size: 2.5rem;
    opacity: 0.3;
}

.stat-label {
    font-size: 0.85rem;
    color: #6c757d;
    font-weight: 500;
}

.stat-value {
    font-size: 2rem;
    font-weight: bold;
}

.progress {
    border-radius: 10px;
}

.progress-bar {
    border-radius: 10px;
    font-weight: bold;
}

@media print {
    .btn, .card-header, nav, .stat-card { display: none !important; }
    .card { border: none !important; box-shadow: none !important; }
}
</style>
