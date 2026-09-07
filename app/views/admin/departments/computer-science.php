<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1">
                <i class="bi bi-cpu text-primary me-2"></i>
                Computer Science Department
            </h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="<?= url('admin/dashboard') ?>">Dashboard</a></li>
                    <li class="breadcrumb-item">Faculties</li>
                    <li class="breadcrumb-item">Business and Management Science</li>
                    <li class="breadcrumb-item active">Computer Science</li>
                </ol>
            </nav>
        </div>
        <button class="btn btn-primary" disabled>
            <i class="bi bi-plus-circle me-2"></i>
            Add Intake (Coming Soon)
        </button>
    </div>
    
    <!-- Department Info Card -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <h5 class="mb-0">Department Overview</h5>
                        </div>
                        <div class="col-md-6">
                            <div class="row g-3 text-center">
                                <div class="col-6">
                                    <div class="p-3 bg-light rounded">
                                        <h4 class="mb-0 text-primary"><?= count($intakes) ?></h4>
                                        <small class="text-muted">Intakes</small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="p-3 bg-light rounded">
                                        <h4 class="mb-0 text-success">
                                            <?= array_sum(array_column($intakes, 'student_count')) ?>
                                        </h4>
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
    
    <!-- Intakes Section -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">
                        <i class="bi bi-calendar3 text-primary me-2"></i>
                        Program Intakes
                    </h5>
                </div>
                <div class="card-body">
                    <?php if (empty($intakes)): ?>
                        <div class="text-center py-5">
                            <i class="bi bi-inbox text-muted" style="font-size: 3rem;"></i>
                            <p class="text-muted mt-3">No intakes available yet</p>
                        </div>
                    <?php else: ?>
                        <div class="row g-4">
                            <?php foreach ($intakes as $intake): ?>
                            <div class="col-md-6 col-lg-4">
                                <div class="card h-100 border hover-card">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start mb-3">
                                            <div class="intake-icon">
                                                <i class="bi bi-calendar-event"></i>
                                            </div>
                                            <span class="badge bg-<?= $intake['status'] === 'active' ? 'success' : 'secondary' ?>">
                                                <?= ucfirst($intake['status']) ?>
                                            </span>
                                        </div>
                                        
                                        <h5 class="card-title mb-2"><?= e($intake['name']) ?></h5>
                                        <p class="text-muted small mb-3">
                                            <i class="bi bi-calendar3 me-1"></i>
                                            Started: <?= date('F Y', strtotime($intake['start_date'])) ?>
                                        </p>
                                        
                                        <div class="row g-2 mb-3">
                                            <div class="col-6">
                                                <div class="d-flex align-items-center">
                                                    <i class="bi bi-journal-text text-primary me-2"></i>
                                                    <div>
                                                        <small class="text-muted d-block">Courses</small>
                                                        <strong><?= $intake['course_count'] ?></strong>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="d-flex align-items-center">
                                                    <i class="bi bi-people text-success me-2"></i>
                                                    <div>
                                                        <small class="text-muted d-block">Students</small>
                                                        <strong><?= $intake['student_count'] ?></strong>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <a href="<?= url('admin/intakes/computer-science/' . $intake['slug']) ?>" 
                                           class="btn btn-outline-primary w-100">
                                            <i class="bi bi-eye me-2"></i>
                                            View Details
                                        </a>
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
.hover-card {
    transition: all 0.3s ease;
}

.hover-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.12) !important;
}

.intake-icon {
    width: 50px;
    height: 50px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 24px;
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

.breadcrumb-item.active {
    color: #495057;
}
</style>
