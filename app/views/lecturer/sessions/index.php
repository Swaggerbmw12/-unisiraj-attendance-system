<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2><i class="bi bi-qr-code"></i> Attendance Sessions</h2>
                    <p class="text-muted">Manage and monitor your attendance sessions</p>
                </div>
                <a href="<?= url('lecturer/sessions/create') ?>" class="btn btn-primary btn-lg">
                    <i class="bi bi-plus-circle"></i> Create New Session
                </a>
            </div>
        </div>
    </div>
    
    <!-- Active Sessions -->
    <?php
    $activeSessions = array_filter($sessions, function($s) {
        return $s['is_active'] && strtotime($s['expires_at']) > time();
    });
    ?>
    <?php if (!empty($activeSessions)): ?>
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-success">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="bi bi-broadcast"></i> Active Sessions (<?= count($activeSessions) ?>)</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <?php foreach ($activeSessions as $session): ?>
                        <div class="col-md-6 mb-3">
                            <div class="card border-success">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <div>
                                            <h5 class="card-title mb-1"><?= e($session['course_code']) ?> - <?= e($session['course_name']) ?></h5>
                                            <p class="card-text small text-muted mb-0"><?= e($session['session_name'] ?? 'Regular Session') ?></p>
                                        </div>
                                        <span class="badge bg-success pulse">LIVE</span>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <div class="row g-2">
                                            <div class="col-6">
                                                <small class="text-muted d-block">Start Time</small>
                                                <strong><?= date('h:i A', strtotime($session['start_time'])) ?></strong>
                                            </div>
                                            <div class="col-6">
                                                <small class="text-muted d-block">Expires At</small>
                                                <strong><?= date('h:i A', strtotime($session['expires_at'])) ?></strong>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="progress mb-2" style="height: 25px;">
                                        <?php
                                        $percentage = $session['total_enrolled'] > 0 
                                            ? round(($session['total_attendees'] / $session['total_enrolled']) * 100, 1) 
                                            : 0;
                                        ?>
                                        <div class="progress-bar bg-success" style="width: <?= $percentage ?>%">
                                            <?= $session['total_attendees'] ?> / <?= $session['total_enrolled'] ?> (<?= $percentage ?>%)
                                        </div>
                                    </div>
                                    
                                    <div class="d-grid gap-2">
                                        <a href="<?= url('lecturer/sessions/view?id=' . $session['id']) ?>" class="btn btn-success btn-sm">
                                            <i class="bi bi-eye"></i> View & Monitor
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
    
    <!-- All Sessions -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-list"></i> All Sessions</h5>
                </div>
                <div class="card-body">
                    <?php if (empty($sessions)): ?>
                        <div class="text-center py-5">
                            <i class="bi bi-inbox" style="font-size: 64px; color: #dee2e6;"></i>
                            <p class="text-muted mt-3">No sessions created yet.</p>
                            <a href="<?= url('lecturer/sessions/create') ?>" class="btn btn-primary">
                                <i class="bi bi-plus-circle"></i> Create Your First Session
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Course</th>
                                        <th>Session Name</th>
                                        <th>Time</th>
                                        <th>Attendance</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($sessions as $session): ?>
                                    <tr>
                                        <td>
                                            <i class="bi bi-calendar-event"></i>
                                            <?= date('M d, Y', strtotime($session['session_date'])) ?>
                                        </td>
                                        <td>
                                            <strong><?= e($session['course_code']) ?></strong><br>
                                            <small class="text-muted"><?= e($session['course_name']) ?></small>
                                        </td>
                                        <td><?= e($session['session_name'] ?? 'Regular Session') ?></td>
                                        <td>
                                            <?= date('h:i A', strtotime($session['start_time'])) ?>
                                            <?php if ($session['end_time']): ?>
                                                <br><small class="text-muted">Ended: <?= date('h:i A', strtotime($session['end_time'])) ?></small>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <strong><?= $session['total_attendees'] ?></strong> / <?= $session['total_enrolled'] ?>
                                            <small class="text-muted d-block">
                                                (<?= $session['total_enrolled'] > 0 ? round(($session['total_attendees'] / $session['total_enrolled']) * 100, 1) : 0 ?>%)
                                            </small>
                                        </td>
                                        <td>
                                            <?php if ($session['is_active'] && strtotime($session['expires_at']) > time()): ?>
                                                <span class="badge bg-success"><i class="bi bi-broadcast"></i> Active</span>
                                            <?php elseif (strtotime($session['expires_at']) < time()): ?>
                                                <span class="badge bg-secondary"><i class="bi bi-clock-history"></i> Expired</span>
                                            <?php else: ?>
                                                <span class="badge bg-danger"><i class="bi bi-x-circle"></i> Closed</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <a href="<?= url('lecturer/sessions/view?id=' . $session['id']) ?>" 
                                               class="btn btn-sm btn-primary" 
                                               title="View Details">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <button type="button" 
                                                    class="btn btn-sm btn-danger" 
                                                    title="Delete Session"
                                                    onclick="confirmDelete(<?= $session['id'] ?>, '<?= e($session['session_name'] ?? 'Session') ?>')">
                                                <i class="bi bi-trash"></i>
                                            </button>
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

<!-- Delete Confirmation Form (Hidden) -->
<form id="deleteForm" method="POST" style="display: none;">
    <input type="hidden" name="session_id" id="deleteSessionId">
</form>

<style>
.pulse {
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.5; }
}

.card {
    transition: transform 0.2s, box-shadow 0.2s;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}
</style>

<script>
function confirmDelete(sessionId, sessionName) {
    if (confirm('Are you sure you want to delete "' + sessionName + '"?\n\nThis will permanently delete the session and all attendance records. This action cannot be undone.')) {
        // Create and submit form
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '<?= url('lecturer/sessions/delete?id=') ?>' + sessionId;
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
