<!-- Student Details Report -->

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="bi bi-person-lines-fill"></i> Detailed Student Attendance</h5>
            </div>
            <div class="card-body">
                <?php if (empty($reportData['students'])): ?>
                    <p class="text-center text-muted py-4">No student data available for the selected period.</p>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Student ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Sessions Attended</th>
                                    <th>Total Sessions</th>
                                    <th>Attendance Rate</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($reportData['students'] as $index => $student): ?>
                                <?php
                                $attended = $student['sessions_attended'];
                                $total = $student['total_sessions'];
                                $percentage = $total > 0 ? round(($attended / $total) * 100, 2) : 0;
                                $progressClass = $percentage >= 75 ? 'success' : ($percentage >= 50 ? 'warning' : 'danger');
                                $statusText = $percentage >= 75 ? 'Excellent' : ($percentage >= 50 ? 'Fair' : 'At Risk');
                                ?>
                                <tr>
                                    <td><?= $index + 1 ?></td>
                                    <td><span class="badge bg-secondary"><?= e($student['student_id']) ?></span></td>
                                    <td>
                                        <strong><?= e($student['first_name'] . ' ' . $student['last_name']) ?></strong>
                                    </td>
                                    <td><small><?= e($student['email']) ?></small></td>
                                    <td class="text-center">
                                        <span class="badge bg-<?= $progressClass ?>"><?= $attended ?></span>
                                    </td>
                                    <td class="text-center"><?= $total ?></td>
                                    <td class="text-center">
                                        <div class="progress" style="height: 25px; min-width: 80px;">
                                            <div class="progress-bar bg-<?= $progressClass ?>" 
                                                 style="width: <?= $percentage ?>%">
                                                <strong><?= number_format($percentage, 1) ?>%</strong>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-<?= $progressClass ?>">
                                            <i class="bi bi-<?= $percentage >= 75 ? 'check-circle' : ($percentage >= 50 ? 'exclamation-triangle' : 'x-circle') ?>"></i>
                                            <?= $statusText ?>
                                        </span>
                                    </td>
                                </tr>
                                <!-- Expandable row with attendance dates -->
                                <?php if (!empty($student['attendance_dates'])): ?>
                                <tr class="table-light">
                                    <td colspan="8">
                                        <small class="text-muted">
                                            <strong>Attendance Records:</strong> 
                                            <?= e($student['attendance_dates']) ?>
                                        </small>
                                    </td>
                                </tr>
                                <?php endif; ?>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Summary Footer -->
                    <div class="mt-4 p-3 bg-light rounded">
                        <div class="row text-center">
                            <div class="col-md-3">
                                <h6 class="text-muted">Total Students</h6>
                                <h3><?= count($reportData['students']) ?></h3>
                            </div>
                            <div class="col-md-3">
                                <h6 class="text-muted">Excellent (≥75%)</h6>
                                <?php
                                $excellentCount = count(array_filter($reportData['students'], function($s) {
                                    $percentage = $s['total_sessions'] > 0 ? ($s['sessions_attended'] / $s['total_sessions']) * 100 : 0;
                                    return $percentage >= 75;
                                }));
                                ?>
                                <h3 class="text-success"><?= $excellentCount ?></h3>
                            </div>
                            <div class="col-md-3">
                                <h6 class="text-muted">Fair (50-74%)</h6>
                                <?php
                                $fairCount = count(array_filter($reportData['students'], function($s) {
                                    $percentage = $s['total_sessions'] > 0 ? ($s['sessions_attended'] / $s['total_sessions']) * 100 : 0;
                                    return $percentage >= 50 && $percentage < 75;
                                }));
                                ?>
                                <h3 class="text-warning"><?= $fairCount ?></h3>
                            </div>
                            <div class="col-md-3">
                                <h6 class="text-muted">At Risk (<50%)</h6>
                                <?php
                                $riskCount = count(array_filter($reportData['students'], function($s) {
                                    $percentage = $s['total_sessions'] > 0 ? ($s['sessions_attended'] / $s['total_sessions']) * 100 : 0;
                                    return $percentage < 50;
                                }));
                                ?>
                                <h3 class="text-danger"><?= $riskCount ?></h3>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
