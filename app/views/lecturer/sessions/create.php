<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <h2><i class="bi bi-plus-circle"></i> Create Attendance Session</h2>
            <p class="text-muted">Generate a new QR code attendance session for your course</p>
        </div>
    </div>
    
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-qr-code"></i> Session Details</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="<?= url('lecturer/sessions/store') ?>" id="createSessionForm">
                        <!-- Course Selection -->
                        <div class="mb-4">
                            <label for="course_id" class="form-label">
                                Course <span class="text-danger">*</span>
                            </label>
                            <select class="form-select" id="course_id" name="course_id" required>
                                <option value="">Select a course...</option>
                                <?php foreach ($courses as $course): ?>
                                <option value="<?= $course['id'] ?>">
                                    <?= e($course['course_code']) ?> - <?= e($course['course_name']) ?>
                                    (<?= $course['enrolled_students'] ?> students)
                                </option>
                                <?php endforeach; ?>
                            </select>
                            <div class="form-text">Select the course for this attendance session</div>
                        </div>
                        
                        <!-- Session Name -->
                        <div class="mb-4">
                            <label for="session_name" class="form-label">Session Name (Optional)</label>
                            <input type="text" 
                                   class="form-control" 
                                   id="session_name" 
                                   name="session_name" 
                                   placeholder="e.g., Week 5 - Introduction to PHP">
                            <div class="form-text">Give this session a descriptive name</div>
                        </div>
                        
                        <div class="row">
                            <!-- Session Date -->
                            <div class="col-md-6 mb-4">
                                <label for="session_date" class="form-label">
                                    Session Date <span class="text-danger">*</span>
                                </label>
                                <input type="date" 
                                       class="form-control" 
                                       id="session_date" 
                                       name="session_date" 
                                       value="<?= date('Y-m-d') ?>"
                                       required>
                            </div>
                            
                            <!-- Start Time -->
                            <div class="col-md-6 mb-4">
                                <label for="start_time" class="form-label">
                                    Start Time <span class="text-danger">*</span>
                                </label>
                                <input type="time" 
                                       class="form-control" 
                                       id="start_time" 
                                       name="start_time" 
                                       value="<?= date('H:i') ?>"
                                       required>
                            </div>
                        </div>
                        
                        <!-- Duration -->
                        <div class="mb-4">
                            <label for="duration" class="form-label">
                                Duration (Minutes) <span class="text-danger">*</span>
                            </label>
                            <select class="form-select" id="duration" name="duration" required>
                                <option value="5">5 minutes</option>
                                <option value="10">10 minutes</option>
                                <option value="15" selected>15 minutes</option>
                                <option value="20">20 minutes</option>
                                <option value="30">30 minutes</option>
                                <option value="45">45 minutes</option>
                                <option value="60">60 minutes</option>
                                <option value="90">90 minutes</option>
                                <option value="120">120 minutes</option>
                            </select>
                            <div class="form-text">How long should the QR code remain valid?</div>
                        </div>
                        
                        <!-- Information Box -->
                        <div class="alert alert-info">
                            <h6 class="alert-heading"><i class="bi bi-info-circle"></i> Important Information</h6>
                            <ul class="mb-0 small">
                                <li>A unique QR code will be generated for this session</li>
                                <li>Students can only record attendance during the active period</li>
                                <li>Each student can record attendance only once per session</li>
                                <li>You can close the session manually at any time</li>
                                <li>The QR code will be displayed immediately after creation</li>
                            </ul>
                        </div>
                        
                        <!-- Submit Buttons -->
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-check-circle"></i> Create Session & Generate QR Code
                            </button>
                            <a href="<?= url('lecturer/sessions') ?>" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('createSessionForm').addEventListener('submit', function(e) {
    const btn = this.querySelector('button[type="submit"]');
    btn.disabled = true;
    btn.innerHTML = '<i class="bi bi-hourglass-split"></i> Creating Session...';
});
</script>
