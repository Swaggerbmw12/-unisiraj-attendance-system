<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1">
                <i class="bi bi-person-workspace text-primary me-2"></i>
                <?= e($faculty_name) ?>
            </h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="<?= url('admin/dashboard') ?>">Dashboard</a></li>
                    <li class="breadcrumb-item">Lecturers</li>
                    <li class="breadcrumb-item active"><?= e($faculty_name) ?></li>
                </ol>
            </nav>
        </div>
        <button type="button" class="btn btn-primary" onclick="showAddLecturerModal()">
            <i class="bi bi-person-plus me-2"></i>
            Add Lecturer
        </button>
    </div>
    
    <!-- Search Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <form method="GET" action="<?= url('admin/lecturers/faculty/' . $faculty_slug) ?>" class="row g-3">
                        <div class="col-md-10">
                            <div class="input-group">
                                <span class="input-group-text bg-white">
                                    <i class="bi bi-search"></i>
                                </span>
                                <input type="text" class="form-control" name="search" 
                                       placeholder="Search by name, staff ID, or email..." 
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
    
    <!-- Lecturers Table -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="bi bi-list-ul text-primary me-2"></i>
                            Faculty Lecturers
                        </h5>
                        <span class="badge bg-primary-subtle text-primary">
                            <?= $pagination['total'] ?? 0 ?> Total Lecturers
                        </span>
                    </div>
                </div>
                <div class="card-body p-0">
                    <?php if (empty($lecturers)): ?>
                        <div class="text-center py-5">
                            <i class="bi bi-person-workspace text-muted" style="font-size: 3rem;"></i>
                            <p class="text-muted mt-3 mb-0">No lecturers found</p>
                            <?php if (!empty($search)): ?>
                                <a href="<?= url('admin/lecturers/faculty/' . $faculty_slug) ?>" class="btn btn-sm btn-outline-primary mt-3">
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
                                        <th width="40%">Lecturer</th>
                                        <th width="20%">Staff ID</th>
                                        <th width="30%">Email</th>
                                        <th width="10%" class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $counter = (($pagination['page'] - 1) * $pagination['perPage']) + 1;
                                    foreach ($lecturers as $lecturer): ?>
                                    <tr>
                                        <td class="ps-4"><?= $counter++ ?></td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-circle bg-primary-subtle text-primary me-2">
                                                    <?= strtoupper(substr($lecturer['first_name'], 0, 1)) ?>
                                                </div>
                                                <div>
                                                    <strong>Dr. <?= e($lecturer['first_name'] . ' ' . $lecturer['last_name']) ?></strong><br>
                                                    <small class="text-muted">
                                                        <i class="bi bi-circle-fill <?= $lecturer['is_active'] ? 'text-success' : 'text-danger' ?>" style="font-size: 8px;"></i>
                                                        <?= $lecturer['is_active'] ? 'Active' : 'Inactive' ?>
                                                    </small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary-subtle text-secondary">
                                                <?= e($lecturer['staff_id']) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <small>
                                                <i class="bi bi-envelope me-1"></i>
                                                <?= e($lecturer['email']) ?>
                                            </small>
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group btn-group-sm" role="group">
                                                <button type="button" class="btn btn-outline-primary" 
                                                        onclick='editLecturer(<?= json_encode($lecturer) ?>)' 
                                                        title="Edit">
                                                    <i class="bi bi-pencil"></i>
                                                </button>
                                                <button type="button" class="btn btn-outline-danger" 
                                                        onclick="confirmDeleteLecturer(<?= $lecturer['id'] ?>, '<?= e($lecturer['first_name'] . ' ' . $lecturer['last_name']) ?>')" 
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
                            <nav aria-label="Lecturers pagination">
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

<!-- Add Lecturer Modal -->
<div class="modal fade" id="addLecturerModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-person-plus text-primary me-2"></i>
                    Add New Lecturer
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="addLecturerForm" method="POST" action="<?= url('admin/lecturers/store') ?>">
                <div class="modal-body">
                    <input type="hidden" name="faculty_slug" value="<?= e($faculty_slug) ?>">
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
                            <label class="form-label">Staff ID <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="staff_id" required placeholder="e.g., L001">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" name="email" required placeholder="lecturer@unisiraj.edu.my">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Phone</label>
                            <input type="text" class="form-control" name="phone" placeholder="+60123456789">
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
                        Add Lecturer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Lecturer Modal -->
<div class="modal fade" id="editLecturerModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-pencil text-warning me-2"></i>
                    Edit Lecturer
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editLecturerForm" method="POST" action="<?= url('admin/lecturers/update') ?>">
                <div class="modal-body">
                    <input type="hidden" name="lecturer_id" id="edit_lecturer_id">
                    <input type="hidden" name="faculty_slug" value="<?= e($faculty_slug) ?>">
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
                            <label class="form-label">Staff ID</label>
                            <input type="text" class="form-control" id="edit_staff_id" readonly disabled>
                            <small class="text-muted">Staff ID cannot be changed</small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" name="email" id="edit_email" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Phone</label>
                            <input type="text" class="form-control" name="phone" id="edit_phone">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning text-white">
                        <i class="bi bi-check-circle me-1"></i>
                        Update Lecturer
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
// Show Add Lecturer Modal
function showAddLecturerModal() {
    document.getElementById('addLecturerForm').reset();
    const modal = new bootstrap.Modal(document.getElementById('addLecturerModal'));
    modal.show();
}

// Edit Lecturer
function editLecturer(lecturer) {
    document.getElementById('edit_lecturer_id').value = lecturer.id;
    document.getElementById('edit_first_name').value = lecturer.first_name;
    document.getElementById('edit_last_name').value = lecturer.last_name;
    document.getElementById('edit_staff_id').value = lecturer.staff_id;
    document.getElementById('edit_email').value = lecturer.email;
    document.getElementById('edit_phone').value = lecturer.phone || '';
    
    // Update form action
    document.getElementById('editLecturerForm').action = '<?= url('admin/lecturers/update') ?>/' + lecturer.id;
    
    const modal = new bootstrap.Modal(document.getElementById('editLecturerModal'));
    modal.show();
}

// Confirm Delete Lecturer
function confirmDeleteLecturer(lecturerId, lecturerName) {
    document.getElementById('deleteMessage').textContent = 
        `Are you sure you want to delete lecturer "${lecturerName}"?`;
    document.getElementById('deleteForm').action = 
        '<?= url('admin/lecturers/delete') ?>?id=' + lecturerId + '&faculty=<?= e($faculty_slug) ?>';
    
    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    modal.show();
}
</script>
