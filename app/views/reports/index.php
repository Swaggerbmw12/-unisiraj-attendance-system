<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <h2><i class="bi bi-file-earmark-bar-graph"></i> Attendance Reports</h2>
            <p class="text-muted">Generate comprehensive attendance reports for your courses</p>
        </div>
    </div>
    
    <!-- Report Generation Form -->
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-sliders"></i> Report Configuration</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="<?= url('reports/generate') ?>" id="reportForm">
                        <!-- Report Type -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">
                                <i class="bi bi-card-list"></i> Report Type
                            </label>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="report_type" 
                                               id="report_summary" value="course_summary" checked>
                                        <label class="form-check-label" for="report_summary">
                                            <strong>Course Summary</strong>
                                            <small class="d-block text-muted">Overview of all sessions and student performance</small>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="report_type" 
                                               id="report_students" value="student_details">
                                        <label class="form-check-label" for="report_students">
                                            <strong>Student Details</strong>
                                            <small class="d-block text-muted">Detailed breakdown by student</small>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="report_type" 
                                               id="report_sessions" value="session_details">
                                        <label class="form-check-label" for="report_sessions">
                                            <strong>Session Details</strong>
                                            <small class="d-block text-muted">Attendance records per session</small>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <hr>
                        
                        <!-- Course Selection -->
                        <div class="mb-3">
                            <label for="course_id" class="form-label fw-bold">
                                <i class="bi bi-book"></i> Select Course <span class="text-danger">*</span>
                            </label>
                            <select class="form-select form-select-lg" id="course_id" name="course_id" required>
                                <option value="">-- Choose a course --</option>
                                <?php foreach ($courses as $course): ?>
                                    <option value="<?= $course['id'] ?>">
                                        <?= e($course['course_code']) ?> - <?= e($course['course_name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <!-- Date Range -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="date_from" class="form-label fw-bold">
                                    <i class="bi bi-calendar-range"></i> Date From
                                </label>
                                <input type="date" class="form-control" id="date_from" name="date_from">
                                <small class="text-muted">Leave empty for all dates</small>
                            </div>
                            <div class="col-md-6">
                                <label for="date_to" class="form-label fw-bold">
                                    <i class="bi bi-calendar-check"></i> Date To
                                </label>
                                <input type="date" class="form-control" id="date_to" name="date_to">
                                <small class="text-muted">Leave empty for all dates</small>
                            </div>
                        </div>
                        
                        <hr>
                        
                        <!-- Output Format -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">
                                <i class="bi bi-file-earmark"></i> Output Format
                            </label>
                            <div class="row g-2">
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="format" 
                                               id="format_html" value="html" checked>
                                        <label class="form-check-label" for="format_html">
                                            <i class="bi bi-display"></i> View Online
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="format" 
                                               id="format_pdf" value="pdf">
                                        <label class="form-check-label" for="format_pdf">
                                            <i class="bi bi-file-pdf"></i> PDF Document
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="format" 
                                               id="format_excel" value="excel">
                                        <label class="form-check-label" for="format_excel">
                                            <i class="bi bi-file-excel"></i> Excel/CSV
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Submit Buttons -->
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-graph-up"></i> Generate Report
                            </button>
                            <a href="<?= url($_SESSION['role'] . '/dashboard') ?>" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left"></i> Back to Dashboard
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Quick Info Cards -->
    <div class="row mt-4">
        <div class="col-md-4">
            <div class="card border-primary">
                <div class="card-body">
                    <h6 class="card-title text-primary">
                        <i class="bi bi-info-circle"></i> Course Summary
                    </h6>
                    <p class="card-text small mb-0">
                        Get an overview of attendance across all sessions with aggregate statistics
                        and student performance metrics.
                    </p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-success">
                <div class="card-body">
                    <h6 class="card-title text-success">
                        <i class="bi bi-people"></i> Student Details
                    </h6>
                    <p class="card-text small mb-0">
                        View detailed attendance records for each student including timestamps
                        and participation rates.
                    </p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-info">
                <div class="card-body">
                    <h6 class="card-title text-info">
                        <i class="bi bi-calendar-event"></i> Session Details
                    </h6>
                    <p class="card-text small mb-0">
                        Examine individual session attendance with complete student lists
                        and check-in times.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.form-check {
    padding: 15px;
    border: 2px solid #dee2e6;
    border-radius: 8px;
    transition: all 0.3s;
}

.form-check:hover {
    border-color: #0d6efd;
    background-color: #f8f9fa;
}

.form-check-input:checked + .form-check-label {
    color: #0d6efd;
    font-weight: 600;
}

.card {
    transition: transform 0.2s, box-shadow 0.2s;
}

.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}
</style>

<script>
document.getElementById('reportForm').addEventListener('submit', function(e) {
    const courseId = document.getElementById('course_id').value;
    if (!courseId) {
        e.preventDefault();
        alert('Please select a course');
        return false;
    }
    
    const btn = this.querySelector('button[type="submit"]');
    btn.disabled = true;
    btn.innerHTML = '<i class="bi bi-hourglass-split"></i> Generating Report...';
});

// Set max date to today
const today = new Date().toISOString().split('T')[0];
document.getElementById('date_to').setAttribute('max', today);
document.getElementById('date_from').setAttribute('max', today);

// Date validation
document.getElementById('date_to').addEventListener('change', function() {
    const dateFrom = document.getElementById('date_from').value;
    const dateTo = this.value;
    
    if (dateFrom && dateTo && dateFrom > dateTo) {
        alert('End date must be after start date');
        this.value = '';
    }
});
</script>
