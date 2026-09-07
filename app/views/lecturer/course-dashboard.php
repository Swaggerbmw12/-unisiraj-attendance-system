<div class="d-flex">
    <!-- Include Sidebar -->
    <?php require VIEW_PATH . '/layouts/lecturer-sidebar.php'; ?>
    
    <!-- Main Content Area -->
    <div class="flex-grow-1 p-4">
        <!-- Add visible debugging info -->
        <?php if (APP_ENV === 'development'): ?>
            <div class="alert alert-info alert-dismissible fade show" role="alert" id="debugAlert">
                <strong>Debug Info:</strong> Course ID: <?= $course['id'] ?> | 
                Course Code: <?= $course['course_code'] ?> | 
                Loaded at: <?= date('H:i:s', $requestTime ?? time()) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        
        <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-md-8">
            <h2><i class="bi bi-book"></i> <?= e($course['course_code']) ?> Dashboard</h2>
            <p class="text-muted">
                <strong><?= e($course['course_name']) ?></strong><br>
                <small><?= e($course['semester']) ?>, <?= e($course['academic_year']) ?></small>
                <?php if (!empty($course['credits'])): ?>
                    <span class="badge bg-secondary ms-2"><?= $course['credits'] ?> Credits</span>
                <?php endif; ?>
                <span class="badge bg-info ms-2" title="Course Database ID">ID: <?= $course['id'] ?></span>
            </p>
        </div>
        <div class="col-md-4 text-end">
            <div class="btn-group">
                <a href="<?= url('lecturer/sessions/create?course=' . $course['id']) ?>" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Create Session
                </a>
                <button type="button" class="btn btn-outline-secondary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false">
                    <span class="visually-hidden">Toggle Actions</span>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="<?= url('reports?course=' . $course['id']) ?>">
                        <i class="bi bi-file-earmark-text"></i> Generate Report
                    </a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item text-danger" href="#" onclick="deleteCourse(<?= $course['id'] ?>, '<?= e($course['course_code']) ?>'); return false;">
                        <i class="bi bi-trash"></i> Delete Course
                    </a></li>
                </ul>
            </div>
        </div>
    </div>
    
    <!-- Statistics Cards -->
    <div class="row mb-4">
        <!-- Enrolled Students -->
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card stat-card stat-primary h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="stat-label mb-1">Students</p>
                            <h2 class="stat-value mb-0"><?= number_format($stats['total_enrolled']) ?></h2>
                            <small class="text-white-50">Enrolled</small>
                        </div>
                        <i class="bi bi-people stat-icon"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Total Sessions -->
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card stat-card stat-success h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="stat-label mb-1">Sessions</p>
                            <h2 class="stat-value mb-0"><?= number_format($stats['total_sessions']) ?></h2>
                            <small class="text-white-50">Total created</small>
                        </div>
                        <i class="bi bi-qr-code stat-icon"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Active Sessions -->
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card stat-card stat-warning h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="stat-label mb-1">Active Now</p>
                            <h2 class="stat-value mb-0"><?= number_format($stats['active_sessions']) ?></h2>
                            <small class="text-white-50">Live sessions</small>
                        </div>
                        <i class="bi bi-broadcast stat-icon"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Average Attendance -->
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card stat-card stat-info h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="stat-label mb-1">Avg Attendance</p>
                            <h2 class="stat-value mb-0"><?= number_format($stats['avg_attendance'], 1) ?>%</h2>
                            <small class="text-white-50">Overall rate</small>
                        </div>
                        <i class="bi bi-graph-up stat-icon"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
    <!-- Content Row -->
    <div class="row">
        <!-- Left Column - Sessions & Students -->
        <div class="col-lg-8 mb-4">
            
            <!-- Recent Sessions -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-clock-history"></i> Recent Sessions</h5>
                    <a href="<?= url('lecturer/sessions?course=' . $course['id']) ?>" class="btn btn-sm btn-outline-primary">View All</a>
                </div>
                <div class="card-body">
                    <?php if (empty($stats['sessions'])): ?>
                        <div class="text-center py-5">
                            <i class="bi bi-qr-code display-1 text-muted"></i>
                            <p class="text-muted mt-3">No sessions created yet for this course.</p>
                            <a href="<?= url('lecturer/sessions/create?course=' . $course['id']) ?>" class="btn btn-primary mt-2">
                                <i class="bi bi-plus-circle"></i> Create First Session
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Session Name</th>
                                        <th>Date</th>
                                        <th>Time</th>
                                        <th class="text-center">Attendance</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach (array_slice($stats['sessions'], 0, 10) as $session): ?>
                                    <?php
                                        $attendance_percentage = $session['total_students'] > 0 
                                            ? round(($session['attendees'] / $session['total_students']) * 100) 
                                            : 0;
                                        $badge_color = $attendance_percentage >= 75 ? 'success' : ($attendance_percentage >= 50 ? 'warning' : 'danger');
                                    ?>
                                    <tr>
                                        <td><strong><?= e($session['session_name']) ?></strong></td>
                                        <td><?= date('M d, Y', strtotime($session['session_date'])) ?></td>
                                        <td><?= date('h:i A', strtotime($session['start_time'])) ?></td>
                                        <td class="text-center">
                                            <span class="badge bg-<?= $badge_color ?>">
                                                <?= number_format($session['attendees']) ?>/<?= number_format($session['total_students']) ?>
                                                (<?= $attendance_percentage ?>%)
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <?php if ($session['is_active']): ?>
                                                <span class="badge bg-success">
                                                    <i class="bi bi-broadcast"></i> Active
                                                </span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary">Closed</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center">
                                            <a href="<?= url('lecturer/sessions/view?id=' . $session['id']) ?>" 
                                               class="btn btn-sm btn-primary">
                                                <i class="bi bi-eye"></i> View
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
            
            <!-- Student Attendance Overview -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-people"></i> Student Attendance Overview</h5>
                </div>
                <div class="card-body">
                    <?php if (empty($stats['attendance_stats'])): ?>
                        <div class="text-center py-5">
                            <i class="bi bi-people display-1 text-muted"></i>
                            <p class="text-muted mt-3">No students enrolled yet.</p>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Student ID</th>
                                        <th>Name</th>
                                        <th class="text-center">Total Sessions</th>
                                        <th class="text-center">Attended</th>
                                        <th class="text-center">Attendance Rate</th>
                                        <th class="text-center">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($stats['attendance_stats'] as $student): ?>
                                    <?php
                                        $percentage = $student['percentage'];
                                        $status_color = $percentage >= 75 ? 'success' : ($percentage >= 50 ? 'warning' : 'danger');
                                        $status_text = $percentage >= 75 ? 'Good' : ($percentage >= 50 ? 'Fair' : 'Low');
                                    ?>
                                    <tr>
                                        <td><?= e($student['student_number']) ?></td>
                                        <td><?= e($student['student_name']) ?></td>
                                        <td class="text-center"><?= number_format($student['total_sessions']) ?></td>
                                        <td class="text-center"><?= number_format($student['attended_sessions']) ?></td>
                                        <td class="text-center">
                                            <div class="progress" style="height: 20px;">
                                                <div class="progress-bar bg-<?= $status_color ?>" 
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
                                            <span class="badge bg-<?= $status_color ?>">
                                                <?= $status_text ?>
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
        
        <!-- Right Column - Quick Info & Active Sessions -->
        <div class="col-lg-4 mb-4">
            
            <!-- Course Info -->
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-info-circle"></i> Course Information</h5>
                </div>
                <div class="card-body">
                    <dl class="row mb-0">
                        <dt class="col-sm-5">Course Code:</dt>
                        <dd class="col-sm-7"><strong><?= e($course['course_code']) ?></strong></dd>
                        
                        <dt class="col-sm-5">Course Name:</dt>
                        <dd class="col-sm-7"><?= e($course['course_name']) ?></dd>
                        
                        <dt class="col-sm-5">Semester:</dt>
                        <dd class="col-sm-7"><?= e($course['semester']) ?></dd>
                        
                        <dt class="col-sm-5">Academic Year:</dt>
                        <dd class="col-sm-7"><?= e($course['academic_year']) ?></dd>
                        
                        <?php if (!empty($course['credits'])): ?>
                        <dt class="col-sm-5">Credits:</dt>
                        <dd class="col-sm-7"><?= $course['credits'] ?></dd>
                        <?php endif; ?>
                        
                        <dt class="col-sm-5">Enrolled:</dt>
                        <dd class="col-sm-7"><span class="badge bg-primary"><?= $stats['total_enrolled'] ?> students</span></dd>
                    </dl>
                    
                    <?php if (!empty($course['description'])): ?>
                    <hr>
                    <h6>Description:</h6>
                    <p class="small text-muted"><?= nl2br(e($course['description'])) ?></p>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Active Sessions -->
            <?php if (!empty($stats['active_sessions_list'])): ?>
            <div class="card mb-3">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-broadcast"></i> Active Sessions
                        <span class="badge bg-light text-success float-end"><?= count($stats['active_sessions_list']) ?></span>
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        <?php foreach ($stats['active_sessions_list'] as $session): ?>
                        <div class="list-group-item">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h6 class="mb-0 fw-bold"><?= e($session['session_name']) ?></h6>
                                <span class="badge bg-success">Live</span>
                            </div>
                            <small class="text-muted d-block mb-2">
                                <i class="bi bi-calendar3"></i> <?= date('M d, Y', strtotime($session['session_date'])) ?>
                            </small>
                            <small class="text-danger">
                                <i class="bi bi-clock"></i> Expires: <?= date('h:i A', strtotime($session['expires_at'])) ?>
                            </small>
                            <div class="mt-2">
                                <a href="<?= url('lecturer/sessions/view?id=' . $session['id']) ?>" class="btn btn-sm btn-success w-100">
                                    <i class="bi bi-eye"></i> Monitor Session
                                </a>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>
            
            <!-- Quick Actions -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-lightning-fill"></i> Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="<?= url('lecturer/sessions/create?course=' . $course['id']) ?>" class="btn btn-primary">
                            <i class="bi bi-plus-circle"></i> Create New Session
                        </a>
                        <a href="<?= url('lecturer/sessions?course=' . $course['id']) ?>" class="btn btn-success">
                            <i class="bi bi-list-ul"></i> View All Sessions
                        </a>
                        <a href="<?= url('reports?course=' . $course['id']) ?>" class="btn btn-info">
                            <i class="bi bi-file-earmark-text"></i> Generate Report
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
function deleteCourse(courseId, courseCode) {
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
            window.location.href = '<?= url('lecturer/dashboard') ?>';
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
