<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1">
                <i class="bi bi-people-fill text-primary me-2"></i>
                Students Management
            </h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="<?= url('admin/dashboard') ?>">Dashboard</a></li>
                    <li class="breadcrumb-item active">Students</li>
                </ol>
            </nav>
        </div>
        <button type="button" class="btn btn-primary" onclick="showAddStudentModal()">
            <i class="bi bi-person-plus me-2"></i>
            Add Student
        </button>
    </div>
    
    <!-- Search and Filter Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <form method="GET" action="<?= url('admin/students') ?>" class="row g-3">
                        <div class="col-md-10">
                            <div class="input-group">
                                <span class="input-group-text bg-white">
                                    <i class="bi bi-search"></i>
                                </span>
                                <input type="text" class="form-control" name="search" 
                                       placeholder="Search by name, matric number, or email..." 
                                       value="<?= e($search ?? '') ?>">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="bi bi-search me-2"></i>
                                Search
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Students Table -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="bi bi-list-ul text-primary me-2"></i>
                            All Students
                        </h5>
                        <span class="badge bg-primary-subtle text-primary">
                            <?= $pagination['total'] ?? 0 ?> Total Students
                        </span>
                    </div>
                </div>
                <div class="card-body p-0">
                    <?php if (empty($students)): ?>
                        <div class="text-center py-5">
                            <i class="bi bi-people text-muted" style="font-size: 3rem;"></i>
                            <p class="text-muted mt-3 mb-0">No students found</p>
                            <?php if (!empty($search)): ?>
                                <a href="<?= url('admin/students') ?>" class="btn btn-sm btn-outline-primary mt-3">
                                    Clear Search
                                </a>
                            <?php endif; ?>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-4" width="5%">#</th>
                                        <th width="25%">Student</th>
                                        <th width="15%">Matric No.</th>
                                        <th width="20%">Email</th>
                                        <th width="15%">Program</th>
                                        <th width="10%" class="text-center">Intake</th>
                                        <th width="10%" class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $counter = (($pagination['page'] - 1) * $pagination['perPage']) + 1;
                                    foreach ($students as $student): ?>
                                    <tr>
                                        <td class="ps-4"><?= $counter++ ?></td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-circle bg-primary-subtle text-primary me-2">
                                                    <?= strtoupper(substr($student['first_name'], 0, 1)) ?>
                                                </div>
                                                <div>
                                                    <strong><?= e($student['first_name'] . ' ' . $student['last_name']) ?></strong><br>
                                                    <small class="text-muted">
                                                        <i class="bi bi-circle-fill <?= $student['is_active'] ? 'text-success' : 'text-danger' ?>" style="font-size: 8px;"></i>
                                                        <?= $student['is_active'] ? 'Active' : 'Inactive' ?>
                                                    </small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary-subtle text-secondary">
                                                <?= e($student['student_id']) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <small>
                                                <i class="bi bi-envelope me-1"></i>
                                                <?= e($student['email']) ?>
                                            </small>
                                        </td>
                                        <td>
                                            <small class="text-muted"><?= e($student['program'] ?? 'N/A') ?></small>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-info-subtle text-info">
                                                <?php
                                                // Determine intake based on student_id or year_of_study
                                                // You can customize this logic based on your data
                                                $intake = 'Not Assigned';
                                                if (!empty($student['year_of_study'])) {
                                                    // Map year to intake (example logic)
                                                    $intakeMap = [
                                                        1 => 'Feb 2023',
                                                        2 => 'Sep 2022',
                                                        3 => 'Feb 2022',
                                                        4 => 'Sep 2021'
                                                    ];
                                                    $intake = $intakeMap[$student['year_of_study']] ?? 'Feb 2023';
                                                }
                                                echo $intake;
                                                ?>
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group btn-group-sm" role="group">
                                                <button type="button" class="btn btn-outline-primary" 
                                                        onclick='editStudent(<?= json_encode($student) ?>)' 
                                                        title="Edit">
                                                    <i class="bi bi-pencil"></i>
                                                </button>
                                                <button type="button" class="btn btn-outline-danger" 
                                                        onclick="confirmDeleteStudent(<?= $student['id'] ?>, '<?= e($student['first_name'] . ' ' . $student['last_name']) ?>')" 
                                                        title="Delete">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Pagination -->
                        <?php if ($pagination['totalPages'] > 1): ?>
                        <div class="card-footer bg-white">
                            <nav aria-label="Students pagination">
                                <ul class="pagination justify-content-center mb-0">
                                    <?php if ($pagination['page'] > 1): ?>
                                    <li class="page-item">
                                        <a class="page-link" href="?page=<?= $pagination['page'] - 1 ?><?= !empty($search) ? '&search=' . urlencode($search) : '' ?>">
                                            <i class="bi bi-chevron-left"></i>
                                        </a>
                                    </li>
                                    <?php endif; ?>
                                    
                                    <?php for ($i = 1; $i <= $pagination['totalPages']; $i++): ?>
                                        <?php if ($i == $pagination['page']): ?>
                                            <li class="page-item active"><span class="page-link"><?= $i ?></span></li>
                                        <?php elseif ($i == 1 || $i == $pagination['totalPages'] || abs($i - $pagination['page']) <= 2): ?>
                                            <li class="page-item">
                                                <a class="page-link" href="?page=<?= $i ?><?= !empty($search) ? '&search=' . urlencode($search) : '' ?>">
                                                    <?= $i ?>
                                                </a>
                                            </li>
                                        <?php elseif (abs($i - $pagination['page']) == 3): ?>
                                            <li class="page-item disabled"><span class="page-link">...</span></li>
                                        <?php endif; ?>
                                    <?php endfor; ?>
                                    
                                    <?php if ($pagination['page'] < $pagination['totalPages']): ?>
                                    <li class="page-item">
                                        <a class="page-link" href="?page=<?= $pagination['page'] + 1 ?><?= !empty($search) ? '&search=' . urlencode($search) : '' ?>">
                                            <i class="bi bi-chevron-right"></i>
                                        </a>
                                    </li>
                                    <?php endif; ?>
                                </ul>
                            </nav>
                        </div>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Student Modal -->
<div class="modal fade" id="addStudentModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-person-plus text-primary me-2"></i>
                    Add New Student
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="addStudentForm" method="POST" action="<?= url('admin/students/store') ?>">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">First Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="first_name" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Last Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="last_name" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Matric Number <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="student_id" required placeholder="e.g., 8231123215">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" name="email" required placeholder="student@unisiraj.edu.my">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Phone</label>
                            <input type="text" class="form-control" name="phone" placeholder="+60123456789">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Program</label>
                            <input type="text" class="form-control" name="program" placeholder="e.g., Computer Science">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Intake</label>
                            <select class="form-select" name="intake">
                                <option value="">Select Intake</option>
                                <option value="Feb 2023" selected>Intake February 2023</option>
                                <option value="Sep 2023">Intake September 2023</option>
                                <option value="Feb 2022">Intake February 2022</option>
                                <option value="Sep 2022">Intake September 2022</option>
                                <option value="Feb 2021">Intake February 2021</option>
                                <option value="Sep 2021">Intake September 2021</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Password <span class="text-danger">*</span></label>
                            <input type="password" class="form-control" name="password" required minlength="8" placeholder="Min. 8 characters">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
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
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-pencil text-warning me-2"></i>
                    Edit Student
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editStudentForm" method="POST" action="<?= url('admin/students/update') ?>">
                <div class="modal-body">
                    <input type="hidden" name="student_id" id="edit_student_id">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">First Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="first_name" id="edit_first_name" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Last Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="last_name" id="edit_last_name" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Matric Number</label>
                            <input type="text" class="form-control" id="edit_matric_no" readonly disabled>
                            <small class="text-muted">Matric number cannot be changed</small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" name="email" id="edit_email" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Phone</label>
                            <input type="text" class="form-control" name="phone" id="edit_phone">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Program</label>
                            <input type="text" class="form-control" name="program" id="edit_program">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Intake</label>
                            <select class="form-select" name="intake" id="edit_intake">
                                <option value="">Select Intake</option>
                                <option value="Feb 2023">Intake February 2023</option>
                                <option value="Sep 2023">Intake September 2023</option>
                                <option value="Feb 2022">Intake February 2022</option>
                                <option value="Sep 2022">Intake September 2022</option>
                                <option value="Feb 2021">Intake February 2021</option>
                                <option value="Sep 2021">Intake September 2021</option>
                            </select>
                        </div>
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
.avatar-circle {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 16px;
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

.pagination .page-link {
    color: #667eea;
    border: 1px solid #dee2e6;
}

.pagination .page-item.active .page-link {
    background-color: #667eea;
    border-color: #667eea;
}

.pagination .page-link:hover {
    background-color: #f8f9fa;
    color: #667eea;
}
</style>

<script>
// Show Add Student Modal
function showAddStudentModal() {
    document.getElementById('addStudentForm').reset();
    const modal = new bootstrap.Modal(document.getElementById('addStudentModal'));
    modal.show();
}

// Edit Student
function editStudent(student) {
    document.getElementById('edit_student_id').value = student.id;
    document.getElementById('edit_first_name').value = student.first_name;
    document.getElementById('edit_last_name').value = student.last_name;
    document.getElementById('edit_matric_no').value = student.student_id;
    document.getElementById('edit_email').value = student.email;
    document.getElementById('edit_phone').value = student.phone || '';
    document.getElementById('edit_program').value = student.program || '';
    
    // Map year_of_study to intake for display
    const intakeMap = {
        1: 'Feb 2023',
        2: 'Sep 2022',
        3: 'Feb 2022',
        4: 'Sep 2021'
    };
    const intake = intakeMap[student.year_of_study] || 'Feb 2023';
    document.getElementById('edit_intake').value = intake;
    
    // Update form action
    document.getElementById('editStudentForm').action = '<?= url('admin/students/update') ?>/' + student.id;
    
    const modal = new bootstrap.Modal(document.getElementById('editStudentModal'));
    modal.show();
}

// Confirm Delete Student
function confirmDeleteStudent(studentId, studentName) {
    document.getElementById('deleteMessage').textContent = 
        `Are you sure you want to delete student "${studentName}"?`;
    document.getElementById('deleteForm').action = 
        '<?= url('admin/students/delete') ?>/' + studentId;
    
    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    modal.show();
}
</script>
