<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2><i class="bi bi-qr-code"></i> <?= e($session['session_name'] ?? 'Attendance Session') ?></h2>
                    <p class="text-muted mb-0"><?= e($session['course_code']) ?> - <?= e($session['course_name']) ?></p>
                </div>
                <div>
                    <?php if ($session['is_active'] && strtotime($session['expires_at']) > time()): ?>
                        <span class="badge bg-success fs-5 pulse">
                            <i class="bi bi-broadcast"></i> ACTIVE
                        </span>
                    <?php else: ?>
                        <span class="badge bg-secondary fs-5">
                            <i class="bi bi-x-circle"></i> CLOSED
                        </span>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Session Info & QR Code -->
    <div class="row mb-4">
        <!-- QR Code Display -->
        <div class="col-md-6 mb-4">
            <div class="card shadow">
                <div class="card-header bg-primary text-white text-center">
                    <h5 class="mb-0"><i class="bi bi-qr-code-scan"></i> QR Code for Students</h5>
                </div>
                <div class="card-body text-center bg-light">
                    <?php if ($session['qr_code_path']): ?>
                        <img src="<?= e($session['qr_code_path']) ?>" 
                             alt="QR Code" 
                             class="img-fluid mb-3"
                             style="max-width: 350px; border: 5px solid #fff; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
                        
                        <p class="text-muted small mb-2">Students should scan this code to record attendance</p>
                        
                        <?php if ($session['is_active'] && strtotime($session['expires_at']) > time()): ?>
                            <div class="alert alert-success mt-3">
                                <i class="bi bi-clock"></i> Expires at <strong><?= date('h:i A', strtotime($session['expires_at'])) ?></strong>
                            </div>
                        <?php endif; ?>
                    <?php else: ?>
                        <div class="alert alert-warning">
                            <i class="bi bi-exclamation-triangle"></i> QR Code not available
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <!-- Session Information -->
        <div class="col-md-6 mb-4">
            <div class="card shadow">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="bi bi-info-circle"></i> Session Information</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless mb-0">
                        <tr>
                            <td class="text-muted"><i class="bi bi-calendar-event"></i> Date:</td>
                            <td><strong><?= date('l, F j, Y', strtotime($session['session_date'])) ?></strong></td>
                        </tr>
                        <tr>
                            <td class="text-muted"><i class="bi bi-clock"></i> Start Time:</td>
                            <td><strong><?= date('h:i A', strtotime($session['start_time'])) ?></strong></td>
                        </tr>
                        <?php if ($session['end_time']): ?>
                        <tr>
                            <td class="text-muted"><i class="bi bi-clock-history"></i> End Time:</td>
                            <td><strong><?= date('h:i A', strtotime($session['end_time'])) ?></strong></td>
                        </tr>
                        <?php endif; ?>
                        <tr>
                            <td class="text-muted"><i class="bi bi-hourglass"></i> Expires At:</td>
                            <td><strong><?= date('h:i A', strtotime($session['expires_at'])) ?></strong></td>
                        </tr>
                        <tr>
                            <td class="text-muted"><i class="bi bi-person"></i> Lecturer:</td>
                            <td><strong><?= e($session['lecturer_name']) ?></strong></td>
                        </tr>
                    </table>
                    
                    <!-- Actions -->
                    <div class="mt-4 d-grid gap-2">
                        <?php if ($session['is_active']): ?>
                            <?php if (strtotime($session['expires_at']) > time()): ?>
                                <!-- Session is active and not expired -->
                                <form method="POST" action="<?= url('lecturer/sessions/close?id=' . $session['id']) ?>" 
                                      onsubmit="return confirm('Are you sure you want to close this session? Students will no longer be able to record attendance.')">
                                    <button type="submit" class="btn btn-danger btn-lg w-100">
                                        <i class="bi bi-x-circle"></i> Close Session Now
                                    </button>
                                </form>
                            <?php else: ?>
                                <!-- Session expired but still marked active in database -->
                                <div class="alert alert-warning">
                                    <i class="bi bi-clock-history"></i> Session has expired.
                                </div>
                                <form method="POST" action="<?= url('lecturer/sessions/close?id=' . $session['id']) ?>" 
                                      onsubmit="return confirm('Mark this session as closed in the database?')">
                                    <button type="submit" class="btn btn-secondary btn-lg w-100">
                                        <i class="bi bi-archive"></i> Mark as Closed
                                    </button>
                                </form>
                            <?php endif; ?>
                        <?php else: ?>
                            <!-- Session already closed -->
                            <div class="alert alert-secondary">
                                <i class="bi bi-check-circle"></i> Session is closed
                            </div>
                        <?php endif; ?>
                        
                        <a href="<?= url('lecturer/sessions') ?>" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left"></i> Back to Sessions
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Live Attendance Stats -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card stat-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="stat-label mb-1">Total Enrolled</p>
                            <h2 class="stat-value mb-0"><?= $session['total_enrolled'] ?></h2>
                        </div>
                        <i class="bi bi-people stat-icon text-primary"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-3">
            <div class="card stat-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="stat-label mb-1">Present</p>
                            <h2 class="stat-value mb-0 text-success" id="attendee-count"><?= $session['total_attendees'] ?></h2>
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
                            <p class="stat-label mb-1">Absent</p>
                            <h2 class="stat-value mb-0 text-danger" id="absent-count"><?= $session['total_enrolled'] - $session['total_attendees'] ?></h2>
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
                            <p class="stat-label mb-1">Attendance Rate</p>
                            <h2 class="stat-value mb-0" id="attendance-percentage">
                                <?= $session['total_enrolled'] > 0 ? round(($session['total_attendees'] / $session['total_enrolled']) * 100, 1) : 0 ?>%
                            </h2>
                        </div>
                        <i class="bi bi-graph-up-arrow stat-icon text-info"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Attendance Records List -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-list-check"></i> Attendance Records</h5>
                    <?php if ($session['is_active'] && strtotime($session['expires_at']) > time()): ?>
                        <span class="badge bg-light text-dark">
                            <i class="bi bi-arrow-clockwise"></i> Auto-refreshing
                        </span>
                    <?php endif; ?>
                </div>
                <div class="card-body" id="attendance-list">
                    <?php if (empty($attendanceRecords)): ?>
                        <div class="text-center py-5 text-muted">
                            <i class="bi bi-inbox" style="font-size: 64px; color: #dee2e6;"></i>
                            <p class="mt-3">No attendance recorded yet. Waiting for students to scan...</p>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Student ID</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Recorded At</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($attendanceRecords as $index => $record): ?>
                                    <tr>
                                        <td><?= $index + 1 ?></td>
                                        <td><strong><?= e($record['student_id']) ?></strong></td>
                                        <td><?= e($record['first_name'] . ' ' . $record['last_name']) ?></td>
                                        <td><?= e($record['email']) ?></td>
                                        <td>
                                            <i class="bi bi-clock"></i>
                                            <?= date('h:i:s A', strtotime($record['attendance_time'])) ?>
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
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.pulse {
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.7; }
}

.stat-card {
    border-left: 4px solid #0d6efd;
    transition: transform 0.2s;
}

.stat-card:hover {
    transform: translateY(-3px);
}

.stat-icon {
    font-size: 2.5rem;
    opacity: 0.3;
}

.stat-label {
    font-size: 0.85rem;
    color: #6c757d;
}

.stat-value {
    font-size: 2rem;
    font-weight: bold;
}
</style>

<?php if ($session['is_active'] && strtotime($session['expires_at']) > time()): ?>
<script>
// Auto-refresh attendance every 5 seconds for active sessions
setInterval(function() {
    fetch('<?= url('lecturer/sessions/live?id=' . $session['id']) ?>')
        .then(response => response.json())
        .then(data => {
            // Update counters
            document.getElementById('attendee-count').textContent = data.total_attendees;
            document.getElementById('absent-count').textContent = <?= $session['total_enrolled'] ?> - data.total_attendees;
            document.getElementById('attendance-percentage').textContent = data.percentage.toFixed(1) + '%';
            
            // TODO: Update attendance list if needed
        })
        .catch(error => console.error('Error fetching live data:', error));
}, 5000);
</script>
<?php endif; ?>
