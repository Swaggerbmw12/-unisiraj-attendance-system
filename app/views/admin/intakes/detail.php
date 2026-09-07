<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1">
                <i class="bi bi-calendar-event text-primary me-2"></i>
                <?= e($intake['name']) ?>
            </h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="<?= url('admin/dashboard') ?>">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="<?= url('admin/departments/computer-science') ?>">Computer Science</a></li>
                    <li class="breadcrumb-item active"><?= e($intake['name']) ?></li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2 align-items-center">
            <div class="dropdown">
                <button class="btn btn-outline-primary dropdown-toggle" type="button" id="semesterDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-filter-circle me-2"></i>
                    Semester 1
                </button>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="semesterDropdown">
                    <li><a class="dropdown-item active" href="?semester=1">
                        <i class="bi bi-check-circle-fill text-success me-2"></i>Semester 1
                    </a></li>
                    <li><a class="dropdown-item" href="?semester=2">Semester 2</a></li>
                    <li><a class="dropdown-item" href="?semester=3">Semester 3</a></li>
                    <li><a class="dropdown-item" href="?semester=4">Semester 4</a></li>
                    <li><a class="dropdown-item" href="?semester=5">Semester 5</a></li>
                    <li><a class="dropdown-item" href="?semester=6">Semester 6</a></li>
                    <li><a class="dropdown-item" href="?semester=7">Semester 7</a></li>
                </ul>
            </div>
            <a href="<?= url('admin/departments/computer-science') ?>" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-2"></i>
                Back
            </a>
        </div>
    </div>
    
    <!-- Intake Info -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <h5 class="mb-3">Intake Information</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <p class="mb-2">
                                        <strong>Department:</strong><br>
                                        <span class="text-muted"><?= e($intake['department']) ?></span>
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <p class="mb-2">
                                        <strong>Start Date:</strong><br>
                                        <span class="text-muted"><?= date('F d, Y', strtotime($intake['start_date'])) ?></span>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="row g-2 text-center">
                                <div class="col-6">
                                    <div class="p-3 bg-light rounded">
                                        <h4 class="mb-0 text-primary"><?= count($intake['courses']) ?></h4>
                                        <small class="text-muted">Courses</small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="p-3 bg-light rounded">
                                        <h4 class="mb-0 text-success"><?= count($intake['students']) ?></h4>
                                        <small class="text-muted">Students</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Courses Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="bi bi-journal-text text-primary me-2"></i>
                            Courses
                        </h5>
                        <button type="button" class="btn btn-sm btn-primary" onclick="showAddCourseModal()">
                            <i class="bi bi-plus-circle me-1"></i>
                            Add Course
                        </button>
                    </div>
                </div>
                <div class="card-body p-0">
                    <?php if (empty($intake['courses'])): ?>
                        <div class="text-center py-5">
                            <i class="bi bi-journal-x text-muted" style="font-size: 3rem;"></i>
                            <p class="text-muted mt-3 mb-0">No courses assigned yet</p>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-4" width="5%">#</th>
                                        <th width="20%">Course Code</th>
                                        <th width="30%">Course Name</th>
                                        <th width="25%">Lecturer</th>
                                        <th width="10%" class="text-center">Credits</th>
                                        <th width="10%" class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $counter = 1;
                                    foreach ($intake['courses'] as $course): ?>
                                    <tr>
                                        <td class="ps-4"><?= $counter++ ?></td>
                                        <td>
                                            <span class="badge bg-primary-subtle text-primary">
                                                <?= e($course['course_code']) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <strong><?= e($course['course_name']) ?></strong>
                                        </td>
                                        <td>
                                            <small class="text-muted"><?= e($course['lecturer']) ?></small>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-info"><?= $course['credits'] ?></span>
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group btn-group-sm" role="group">
                                                <button type="button" class="btn btn-outline-primary" 
                                                        onclick="editCourse(<?= $course['id'] ?>, '<?= e($course['course_code']) ?>', '<?= e($course['course_name']) ?>')">
                                                    <i class="bi bi-pencil"></i>
                                                </button>
                                                <button type="button" class="btn btn-outline-danger" 
                                                        onclick="confirmDeleteCourse(<?= $course['id'] ?>, '<?= e($course['course_name']) ?>')">
                                                    <i class="bi bi-trash"></i>
                                                </button>
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
    
    <!-- Students Section -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="bi bi-people-fill text-success me-2"></i>
                            Students List
                        </h5>
                        <button type="button" class="btn btn-sm btn-success" onclick="showAddStudentModal()">
                            <i class="bi bi-person-plus me-1"></i>
                            Add Student
                        </button>
                    </div>
                </div>
                <div class="card-body p-0">
                    <?php if (empty($intake['students'])): ?>
                        <div class="text-center py-5">
                            <i class="bi bi-people text-muted" style="font-size: 3rem;"></i>
                            <p class="text-muted mt-3 mb-0">No students enrolled yet</p>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-4" width="5%">#</th>
                                        <th width="35%">Name</th>
                                        <th width="20%">Matric No.</th>
                                        <th width="30%">Email</th>
                                        <th width="10%" class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $counter = 1;
                                    foreach ($intake['students'] as $student): ?>
                                    <tr>
                                        <td class="ps-4"><?= $counter++ ?></td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm bg-primary-subtle text-primary rounded-circle me-2">
                                                    <i class="bi bi-person-fill"></i>
                                                </div>
                                                <div>
                                                    <strong><?= e($student['name']) ?></strong><br>
                                                    <small class="text-muted"><?= e($student['program']) ?></small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary-subtle text-secondary">
                                                <?= e($student['matric_no']) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <small>
                                                <i class="bi bi-envelope me-1"></i>
                                                <?= e($student['email']) ?>
                                            </small>
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group btn-group-sm" role="group">
                                                <button type="button" class="btn btn-outline-primary" 
                                                        onclick="editStudent(<?= $student['id'] ?>, '<?= e($student['name']) ?>', '<?= e($student['matric_no']) ?>', '<?= e($student['email']) ?>', '<?= e($student['program']) ?>')">
                                                    <i class="bi bi-pencil"></i>
                                                </button>
                                                <button type="button" class="btn btn-outline-danger" 
                                                        onclick="confirmDeleteStudent(<?= $student['id'] ?>, '<?= e($student['name']) ?>')">
                                                    <i class="bi bi-trash"></i>
                                                </button>
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
</div>

<!-- Add Course Modal -->
<div class="modal fade" id="addCourseModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-plus-circle text-primary me-2"></i>
                    Add New Course
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="addCourseForm" method="POST" action="<?= url('admin/intakes/course/add') ?>">
                <div class="modal-body">
                    <input type="hidden" name="intake_slug" value="<?= e($intake['slug']) ?>">
                    <div class="mb-3">
                        <label class="form-label">Course Code <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="course_code" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Course Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="course_name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Lecturer</label>
                        <input type="text" class="form-control" name="lecturer_name">
                        <small class="text-muted">Leave empty if not assigned yet</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Credits</label>
                        <input type="number" class="form-control" name="credits" value="3" min="1" max="6">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Semester</label>
                        <input type="text" class="form-control" name="semester">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Academic Year</label>
                        <input type="text" class="form-control" name="academic_year">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle me-1"></i>
                        Add Course
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Course Modal -->
<div class="modal fade" id="editCourseModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-pencil text-warning me-2"></i>
                    Edit Course
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editCourseForm" method="POST" action="<?= url('admin/intakes/course/update') ?>">
                <div class="modal-body">
                    <input type="hidden" name="course_id" id="edit_course_id">
                    <input type="hidden" name="intake_slug" value="<?= e($intake['slug']) ?>">
                    <div class="mb-3">
                        <label class="form-label">Course Code <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="course_code" id="edit_course_code" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Course Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="course_name" id="edit_course_name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Lecturer</label>
                        <input type="text" class="form-control" name="lecturer_name" id="edit_lecturer_name">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Credits</label>
                        <input type="number" class="form-control" name="credits" id="edit_credits" min="1" max="6">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning text-white">
                        <i class="bi bi-check-circle me-1"></i>
                        Update Course
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add Student Modal -->
<div class="modal fade" id="addStudentModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-person-plus text-success me-2"></i>
                    Add New Student
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="addStudentForm" method="POST" action="<?= url('admin/intakes/student/add') ?>">
                <div class="modal-body">
                    <input type="hidden" name="intake_slug" value="<?= e($intake['slug']) ?>">
                    <div class="mb-3">
                        <label class="form-label">Student Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Matric Number <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="matric_no" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Program</label>
                        <input type="text" class="form-control" name="program" value="Computer Science">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Phone</label>
                        <input type="text" class="form-control" name="phone">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" name="password" required>
                        <small class="text-muted">Student can change this later</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-check-circle me-1"></i>
                        Add Student
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Student Modal -->
<div class="modal fade" id="editStudentModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-pencil text-warning me-2"></i>
                    Edit Student
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editStudentForm" method="POST" action="<?= url('admin/intakes/student/update') ?>">
                <div class="modal-body">
                    <input type="hidden" name="student_id" id="edit_student_id">
                    <input type="hidden" name="intake_slug" value="<?= e($intake['slug']) ?>">
                    <div class="mb-3">
                        <label class="form-label">Student Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="name" id="edit_student_name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Matric Number <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="matric_no" id="edit_student_matric" readonly disabled>
                        <small class="text-muted">Matric number cannot be changed</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control" name="email" id="edit_student_email" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Program</label>
                        <input type="text" class="form-control" name="program" id="edit_student_program">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning text-white">
                        <i class="bi bi-check-circle me-1"></i>
                        Update Student
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    Confirm Delete
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p id="deleteMessage"></p>
                <p class="text-muted small mb-0">This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-trash me-1"></i>
                        Delete
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
.avatar-sm {
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
}

.breadcrumb {
    font-size: 0.875rem;
}

.breadcrumb-item a {
    color: #6c757d;
    text-decoration: none;
}

.breadcrumb-item a:hover {
    color: #667eea;
}

.table thead th {
    font-size: 0.813rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    border-bottom: 2px solid #dee2e6;
}

.table tbody tr {
    transition: background-color 0.2s ease;
}

.dropdown-menu {
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    border: none;
    min-width: 160px;
}

.dropdown-item {
    padding: 10px 16px;
    transition: all 0.2s ease;
}

.dropdown-item:hover {
    background: #f8f9fa;
    color: #667eea;
    padding-left: 20px;
}

.dropdown-item.active {
    background: #e7f3ff;
    color: #667eea;
    font-weight: 600;
}

.dropdown-item i {
    font-size: 14px;
}
</style>

<script>
// Get current semester from URL parameter
function getCurrentSemester() {
    const urlParams = new URLSearchParams(window.location.search);
    return urlParams.get('semester') || '1';
}

// Update semester dropdown on page load
document.addEventListener('DOMContentLoaded', function() {
    const currentSemester = getCurrentSemester();
    
    // Update dropdown button text
    const dropdownButton = document.getElementById('semesterDropdown');
    if (dropdownButton) {
        dropdownButton.innerHTML = `<i class="bi bi-filter-circle me-2"></i>Semester ${currentSemester}`;
    }
    
    // Update active state in dropdown menu
    document.querySelectorAll('.dropdown-menu a.dropdown-item').forEach(item => {
        item.classList.remove('active');
        const semesterNum = new URL(item.href).searchParams.get('semester');
        if (semesterNum === currentSemester) {
            item.classList.add('active');
            item.innerHTML = `<i class="bi bi-check-circle-fill text-success me-2"></i>Semester ${currentSemester}`;
        } else {
            item.innerHTML = `Semester ${semesterNum}`;
        }
    });
});

// Show Add Course Modal
function showAddCourseModal() {
    document.getElementById('addCourseForm').reset();
    // Set semester value based on current selection
    const currentSemester = getCurrentSemester();
    const semesterInput = document.querySelector('[name="semester"]');
    if (semesterInput) {
        semesterInput.value = `Semester ${currentSemester}`;
    }
    const modal = new bootstrap.Modal(document.getElementById('addCourseModal'));
    modal.show();
}

// Show Add Student Modal
function showAddStudentModal() {
    document.getElementById('addStudentForm').reset();
    const modal = new bootstrap.Modal(document.getElementById('addStudentModal'));
    modal.show();
}

// Edit Course
function editCourse(courseId, courseCode, courseName) {
    document.getElementById('edit_course_id').value = courseId;
    document.getElementById('edit_course_code').value = courseCode;
    document.getElementById('edit_course_name').value = courseName;
    
    const modal = new bootstrap.Modal(document.getElementById('editCourseModal'));
    modal.show();
}

// Edit Student
function editStudent(studentId, name, matricNo, email, program) {
    document.getElementById('edit_student_id').value = studentId;
    document.getElementById('edit_student_name').value = name;
    document.getElementById('edit_student_matric').value = matricNo;
    document.getElementById('edit_student_email').value = email;
    document.getElementById('edit_student_program').value = program || 'Computer Science';
    
    const modal = new bootstrap.Modal(document.getElementById('editStudentModal'));
    modal.show();
}

// Confirm Delete Course
function confirmDeleteCourse(courseId, courseName) {
    document.getElementById('deleteMessage').textContent = 
        `Are you sure you want to delete the course "${courseName}"?`;
    document.getElementById('deleteForm').action = 
        '<?= url('admin/intakes/course/delete') ?>?id=' + courseId + '&intake_slug=<?= e($intake['slug']) ?>';
    
    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    modal.show();
}

// Confirm Delete Student
function confirmDeleteStudent(studentId, studentName) {
    document.getElementById('deleteMessage').textContent = 
        `Are you sure you want to remove student "${studentName}" from this intake?`;
    document.getElementById('deleteForm').action = 
        '<?= url('admin/intakes/student/delete') ?>?id=' + studentId + '&intake_slug=<?= e($intake['slug']) ?>';
    
    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    modal.show();
}
</script>
