<!-- Course Summary Report -->

<!-- Summary Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-3 mb-3">
        <div class="card stat-card border-primary">
            <div class="card-body text-center">
                <i class="bi bi-calendar-event text-primary" style="font-size: 2rem;"></i>
                <h3 class="mt-2 mb-0"><?= number_format($reportData['summary']['total_sessions']) ?></h3>
                <p class="text-muted mb-0">Total Sessions</p>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card stat-card border-success">
            <div class="card-body text-center">
                <i class="bi bi-people text-success" style="font-size: 2rem;"></i>
                <h3 class="mt-2 mb-0"><?= number_format($reportData['summary']['total_enrolled']) ?></h3>
                <p class="text-muted mb-0">Total Enrolled</p>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card stat-card border-info">
            <div class="card-body text-center">
                <i class="bi bi-check-circle text-info" style="font-size: 2rem;"></i>
                <h3 class="mt-2 mb-0"><?= number_format($reportData['summary']['total_attendance_records']) ?></h3>
                <p class="text-muted mb-0">Total Check-ins</p>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card stat-card border-warning">
            <div class="card-body text-center">
                <i class="bi bi-person-check text-warning" style="font-size: 2rem;"></i>
                <h3 class="mt-2 mb-0"><?= number_format($reportData['summary']['unique_students_attended']) ?></h3>
                <p class="text-muted mb-0">Active Students</p>
            </div>
        </div>
    </div>
</div>

<!-- Sessions Table -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="bi bi-calendar-week"></i> Sessions Overview</h5>
            </div>
            <div class="card-body">
                <?php if (empty($reportData['sessions'])): ?>
                    <p class="text-center text-muted py-4">No sessions found for the selected period.</p>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Session Name</th>
                                    <th>Date</th>
                                    <th>Time</th>
                                    <th>Attendees</th>
                                    <th>Enrolled</th>
                                    <th>Rate</th>
                                    <th>Progress</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($reportData['sessions'] as $index => $session): ?>
                                <?php
                                $percentage = $session['percentage'] ?? 0;
                                $progressClass = $percentage >= 75 ? 'success' : ($percentage >= 50 ? 'warning' : 'danger');
                                ?>
                                <tr>
                                    <td><?= $index + 1 ?></td>
                                    <td><strong><?= e($session['session_name']) ?></strong></td>
                                    <td><?= date('M d, Y', strtotime($session['session_date'])) ?></td>
                                    <td>
                                        <?= date('h:i A', strtotime($session['start_time'])) ?>
                                        <?php if ($session['end_time']): ?>
                                            - <?= date('h:i A', strtotime($session['end_time'])) ?>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center"><strong><?= $session['attendees'] ?></strong></td>
                                    <td class="text-center"><?= $session['enrolled'] ?></td>
                                    <td class="text-center">
                                        <span class="badge bg-<?= $progressClass ?>">
                                            <?= number_format($percentage, 1) ?>%
                                        </span>
                                    </td>
                                    <td style="width: 200px;">
                                        <div class="progress" style="height: 20px;">
                                            <div class="progress-bar bg-<?= $progressClass ?>" 
                                                 style="width: <?= $percentage ?>%">
                                            </div>
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
</div>

<!-- Student Performance Table -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="bi bi-people"></i> Student Performance</h5>
            </div>
            <div class="card-body">
                <?php if (empty($reportData['students'])): ?>
                    <p class="text-center text-muted py-4">No student data available.</p>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Student ID</th>
                                    <th>Name</th>
                                    <th>Sessions Attended</th>
                                    <th>Total Sessions</th>
                                    <th>Attendance Rate</th>
                                    <th>Progress</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($reportData['students'] as $index => $student): ?>
                                <?php
                                $percentage = $student['percentage'] ?? 0;
                                $progressClass = $percentage >= 75 ? 'success' : ($percentage >= 50 ? 'warning' : 'danger');
                                $statusText = $percentage >= 75 ? 'Excellent' : ($percentage >= 50 ? 'Fair' : 'Low');
                                ?>
                                <tr>
                                    <td><?= $index + 1 ?></td>
                                    <td><?= e($student['student_id']) ?></td>
                                    <td><strong><?= e($student['first_name'] . ' ' . $student['last_name']) ?></strong></td>
                                    <td class="text-center"><?= $student['sessions_attended'] ?></td>
                                    <td class="text-center"><?= $student['total_sessions'] ?></td>
                                    <td class="text-center">
                                        <span class="badge bg-<?= $progressClass ?>">
                                            <?= number_format($percentage, 1) ?>%
                                        </span>
                                    </td>
                                    <td style="width: 200px;">
                                        <div class="progress" style="height: 20px;">
                                            <div class="progress-bar bg-<?= $progressClass ?>" 
                                                 style="width: <?= $percentage ?>%">
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-<?= $progressClass ?>">
                                            <?= $statusText ?>
                                        </span>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Summary Statistics -->
                    <div class="mt-4 p-3 bg-light rounded">
                        <div class="row text-center">
                            <div class="col-md-4">
                                <h6 class="text-muted">Average Attendance Rate</h6>
                                <?php
                                $avgPercentage = count($reportData['students']) > 0 
                                    ? array_sum(array_column($reportData['students'], 'percentage')) / count($reportData['students'])
                                    : 0;
                                $avgClass = $avgPercentage >= 75 ? 'success' : ($avgPercentage >= 50 ? 'warning' : 'danger');
                                ?>
                                <h3 class="text-<?= $avgClass ?>"><?= number_format($avgPercentage, 1) ?>%</h3>
                            </div>
                            <div class="col-md-4">
                                <h6 class="text-muted">Students Above 75%</h6>
                                <?php
                                $excellentCount = count(array_filter($reportData['students'], function($s) {
                                    return $s['percentage'] >= 75;
                                }));
                                ?>
                                <h3 class="text-success"><?= $excellentCount ?></h3>
                            </div>
                            <div class="col-md-4">
                                <h6 class="text-muted">Students Below 50%</h6>
                                <?php
                                $lowCount = count(array_filter($reportData['students'], function($s) {
                                    return $s['percentage'] < 50;
                                }));
                                ?>
                                <h3 class="text-danger"><?= $lowCount ?></h3>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
