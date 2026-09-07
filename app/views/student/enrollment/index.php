<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <h2><i class="bi bi-journal-plus"></i> Course Enrollment</h2>
            <p class="text-muted">Browse and enroll in available courses</p>
        </div>
    </div>
    
    <!-- My Enrolled Courses -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-book-fill"></i> My Enrolled Courses</h5>
                    <span class="badge bg-light text-primary"><?= count($my_courses) ?> Courses</span>
                </div>
                <div class="card-body">
                    <?php if (empty($my_courses)): ?>
                        <div class="text-center py-5">
                            <i class="bi bi-inbox display-1 text-muted"></i>
                            <p class="text-muted mt-3">You are not enrolled in any courses yet.</p>
                            <p class="text-muted">Browse available courses below and enroll.</p>
                        </div>
                    <?php else: ?>
                        <div class="row">
                            <?php foreach ($my_courses as $course): ?>
                            <div class="col-md-6 col-lg-4 mb-3">
                                <div class="card h-100 enrolled-course-card">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <h5 class="card-title text-primary mb-0">
                                                <?= e($course['course_code']) ?>
                                            </h5>
                                            <span class="badge bg-success">Enrolled</span>
                                        </div>
                                        <h6 class="card-subtitle mb-2 text-muted"><?= e($course['course_name']) ?></h6>
                                        <p class="card-text small">
                                            <i class="bi bi-calendar3"></i> <?= e($course['semester']) ?><br>
                                            <i class="bi bi-calendar-range"></i> <?= e($course['academic_year']) ?><br>
                                            <i class="bi bi-person"></i> <?= e($course['lecturer_name'] ?? 'Not Assigned') ?><br>
                                            <i class="bi bi-people"></i> <?= $course['enrolled_students'] ?> students
                                        </p>
                                        <small class="text-muted">
                                            Enrolled: <?= date('M d, Y', strtotime($course['enrollment_date'])) ?>
                                        </small>
                                    </div>
                                    <div class="card-footer bg-transparent">
                                        <button class="btn btn-sm btn-danger w-100" 
                                                onclick="unenrollCourse(<?= $course['id'] ?>, '<?= e($course['course_code']) ?>')">
                                            <i class="bi bi-x-circle"></i> Unenroll
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Available Courses -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-plus-circle"></i> Available Courses</h5>
                    <span class="badge bg-light text-success"><?= count($available_courses) ?> Available</span>
                </div>
                <div class="card-body">
                    <?php if (empty($available_courses)): ?>
                        <div class="text-center py-5">
                            <i class="bi bi-check-circle display-1 text-success"></i>
                            <p class="text-muted mt-3">You are enrolled in all available courses!</p>
                            <p class="text-muted">Check back later for new courses.</p>
                        </div>
                    <?php else: ?>
                        <div class="row">
                            <?php foreach ($available_courses as $course): ?>
                            <div class="col-md-6 col-lg-4 mb-3">
                                <div class="card h-100 available-course-card">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <h5 class="card-title text-success mb-0">
                                                <?= e($course['course_code']) ?>
                                            </h5>
                                            <span class="badge bg-light text-success">Available</span>
                                        </div>
                                        <h6 class="card-subtitle mb-2 text-muted"><?= e($course['course_name']) ?></h6>
                                        <p class="card-text small">
                                            <i class="bi bi-calendar3"></i> <?= e($course['semester']) ?><br>
                                            <i class="bi bi-calendar-range"></i> <?= e($course['academic_year']) ?><br>
                                            <i class="bi bi-person"></i> <?= e($course['lecturer_name'] ?? 'Not Assigned') ?><br>
                                            <i class="bi bi-people"></i> <?= $course['enrolled_students'] ?> students enrolled
                                        </p>
                                        <?php if (!empty($course['description'])): ?>
                                        <p class="card-text small text-muted">
                                            <?= e(substr($course['description'], 0, 100)) ?><?= strlen($course['description']) > 100 ? '...' : '' ?>
                                        </p>
                                        <?php endif; ?>
                                    </div>
                                    <div class="card-footer bg-transparent">
                                        <button class="btn btn-sm btn-success w-100" 
                                                onclick="enrollCourse(<?= $course['id'] ?>, '<?= e($course['course_code']) ?>')">
                                            <i class="bi bi-plus-circle"></i> Enroll Now
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.enrolled-course-card {
    border-left: 4px solid #28a745;
    transition: transform 0.2s, box-shadow 0.2s;
}

.enrolled-course-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 16px rgba(0,0,0,0.1);
}

.available-course-card {
    border-left: 4px solid #20c997;
    transition: transform 0.2s, box-shadow 0.2s;
}

.available-course-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 16px rgba(0,0,0,0.1);
}

.card-title {
    font-weight: 700;
    font-size: 1.1rem;
}
</style>

<script>
function enrollCourse(courseId, courseCode) {
    if (!confirm(`Do you want to enroll in ${courseCode}?`)) {
        return;
    }
    
    fetch('<?= url('student/enrollment/enroll') ?>', {
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
            window.location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while enrolling');
    });
}

function unenrollCourse(courseId, courseCode) {
    if (!confirm(`⚠️ Are you sure you want to unenroll from ${courseCode}?\n\nNote: You can only unenroll if you have no attendance records in this course.`)) {
        return;
    }
    
    fetch('<?= url('student/enrollment/unenroll') ?>', {
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
            window.location.reload();
        } else {
            alert('⚠️ ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while unenrolling');
    });
}
</script>
