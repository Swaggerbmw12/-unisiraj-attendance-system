<!-- Session Details Report -->

<div class="row">
    <div class="col-12">
        <?php if (empty($reportData['sessions'])): ?>
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="bi bi-calendar-x" style="font-size: 64px; color: #dee2e6;"></i>
                    <p class="text-muted mt-3">No sessions found for the selected period.</p>
                </div>
            </div>
        <?php else: ?>
            <?php foreach ($reportData['sessions'] as $index => $session): ?>
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h5 class="mb-0">
                                <i class="bi bi-calendar-event"></i>
                                Session #<?= $index + 1 ?>: <?= e($session['session_name']) ?>
                            </h5>
                        </div>
                        <div class="col-md-4 text-end">
                            <span class="badge bg-light text-dark">
                                <i class="bi bi-people"></i> 
                                <?= count($session['attendees']) ?> Students
                            </span>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Session Info -->
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <p class="mb-1">
                                <i class="bi bi-calendar3 text-primary"></i> 
                                <strong>Date:</strong> <?= date('l, F j, Y', strtotime($session['session_date'])) ?>
                            </p>
                        </div>
                        <div class="col-md-4">
                            <p class="mb-1">
                                <i class="bi bi-clock text-primary"></i> 
                                <strong>Start Time:</strong> <?= date('h:i A', strtotime($session['start_time'])) ?>
                            </p>
                        </div>
                        <div class="col-md-4">
                            <p class="mb-1">
                                <i class="bi bi-clock-history text-primary"></i> 
                                <strong>End Time:</strong> 
                                <?php if ($session['end_time']): ?>
                                    <?= date('h:i A', strtotime($session['end_time'])) ?>
                                <?php else: ?>
                                    <span class="text-warning">In Progress</span>
                                <?php endif; ?>
                            </p>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <!-- Attendance List -->
                    <h6 class="mb-3"><i class="bi bi-person-check"></i> Attendance Records</h6>
                    
                    <?php if (empty($session['attendees'])): ?>
                        <div class="alert alert-warning">
                            <i class="bi bi-exclamation-triangle"></i>
                            No students recorded attendance for this session.
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-sm table-striped">
                                <thead>
                                    <tr>
                                        <th style="width: 50px;">#</th>
                                        <th>Student ID</th>
                                        <th>Name</th>
                                        <th>Check-in Time</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($session['attendees'] as $i => $attendee): ?>
                                    <tr>
                                        <td><?= $i + 1 ?></td>
                                        <td><span class="badge bg-secondary"><?= e($attendee['student_id']) ?></span></td>
                                        <td><?= e($attendee['first_name'] . ' ' . $attendee['last_name']) ?></td>
                                        <td>
                                            <i class="bi bi-clock"></i>
                                            <?= date('h:i:s A', strtotime($attendee['attendance_time'])) ?>
                                        </td>
                                        <td>
                                            <span class="badge bg-success">
                                                <i class="bi bi-check-circle"></i> Present
                                            </span>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Session Summary -->
                        <div class="mt-3 p-2 bg-light rounded">
                            <div class="row text-center">
                                <div class="col-4">
                                    <small class="text-muted">Total Present</small>
                                    <h5 class="mb-0 text-success"><?= count($session['attendees']) ?></h5>
                                </div>
                                <div class="col-4">
                                    <small class="text-muted">First Check-in</small>
                                    <h6 class="mb-0">
                                        <?php if (!empty($session['attendees'])): ?>
                                            <?= date('h:i A', strtotime($session['attendees'][0]['attendance_time'])) ?>
                                        <?php else: ?>
                                            -
                                        <?php endif; ?>
                                    </h6>
                                </div>
                                <div class="col-4">
                                    <small class="text-muted">Last Check-in</small>
                                    <h6 class="mb-0">
                                        <?php if (!empty($session['attendees'])): ?>
                                            <?= date('h:i A', strtotime(end($session['attendees'])['attendance_time'])) ?>
                                        <?php else: ?>
                                            -
                                        <?php endif; ?>
                                    </h6>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
            
            <!-- Overall Summary -->
            <div class="card bg-light">
                <div class="card-body">
                    <h5 class="card-title"><i class="bi bi-graph-up"></i> Overall Summary</h5>
                    <div class="row text-center">
                        <div class="col-md-3">
                            <h6 class="text-muted">Total Sessions</h6>
                            <h3><?= count($reportData['sessions']) ?></h3>
                        </div>
                        <div class="col-md-3">
                            <h6 class="text-muted">Total Check-ins</h6>
                            <?php
                            $totalCheckins = array_sum(array_map(function($s) {
                                return count($s['attendees']);
                            }, $reportData['sessions']));
                            ?>
                            <h3 class="text-success"><?= $totalCheckins ?></h3>
                        </div>
                        <div class="col-md-3">
                            <h6 class="text-muted">Avg. per Session</h6>
                            <?php
                            $avgPerSession = count($reportData['sessions']) > 0 
                                ? round($totalCheckins / count($reportData['sessions']), 1)
                                : 0;
                            ?>
                            <h3 class="text-primary"><?= $avgPerSession ?></h3>
                        </div>
                        <div class="col-md-3">
                            <h6 class="text-muted">Most Attended</h6>
                            <?php
                            $maxAttendance = !empty($reportData['sessions']) 
                                ? max(array_map(function($s) { return count($s['attendees']); }, $reportData['sessions']))
                                : 0;
                            ?>
                            <h3 class="text-info"><?= $maxAttendance ?></h3>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
