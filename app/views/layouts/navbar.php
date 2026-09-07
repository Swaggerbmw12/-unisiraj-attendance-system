<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container-fluid">
        <a class="navbar-brand" href="<?= url('/') ?>">
            <i class="bi bi-qr-code-scan"></i>
            <?= APP_NAME ?>
        </a>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" 
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarNav">
            <?php if (isAuthenticated()): ?>
                <ul class="navbar-nav me-auto">
                    
                    <?php if (hasRole('admin')): ?>
                    <!-- Admin Menu -->
                    <li class="nav-item">
                        <a class="nav-link" href="<?= url('admin/dashboard') ?>">
                            <i class="bi bi-speedometer2"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-gear"></i> Management
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="<?= url('admin/students') ?>">
                                <i class="bi bi-people"></i> Students
                            </a></li>
                            <li><a class="dropdown-item" href="<?= url('admin/lecturers') ?>">
                                <i class="bi bi-person-badge"></i> Lecturers
                            </a></li>
                            <li><a class="dropdown-item" href="<?= url('admin/courses') ?>">
                                <i class="bi bi-book"></i> Courses
                            </a></li>
                            <li><a class="dropdown-item" href="<?= url('admin/enrollments') ?>">
                                <i class="bi bi-card-checklist"></i> Enrollments
                            </a></li>
                        </ul>
                    </li>
                    <?php endif; ?>
                    
                    <?php if (hasRole('lecturer')): ?>
                    <!-- Lecturer Menu -->
                    <li class="nav-item">
                        <a class="nav-link" href="<?= url('lecturer/dashboard') ?>">
                            <i class="bi bi-speedometer2"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-book"></i> My Courses
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="<?= url('lecturer/courses/create') ?>">
                                <i class="bi bi-plus-circle"></i> Add New Course
                            </a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="<?= url('lecturer/dashboard') ?>">
                                <i class="bi bi-list-ul"></i> All Courses
                            </a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= url('lecturer/sessions') ?>">
                            <i class="bi bi-qr-code"></i> Attendance Sessions
                        </a>
                    </li>
                    <?php endif; ?>
                    
                    <?php if (hasRole('student')): ?>
                    <!-- Student Menu -->
                    <li class="nav-item">
                        <a class="nav-link" href="<?= url('student/dashboard') ?>">
                            <i class="bi bi-speedometer2"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= url('student/attendance/scan') ?>">
                            <i class="bi bi-qr-code-scan"></i> Scan QR Code
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= url('student/attendance/history') ?>">
                            <i class="bi bi-clock-history"></i> My Attendance
                        </a>
                    </li>
                    <?php endif; ?>
                    
                    <?php if (hasRole(['admin', 'lecturer'])): ?>
                    <!-- Reports (Admin & Lecturer) -->
                    <li class="nav-item">
                        <a class="nav-link" href="<?= url('reports') ?>">
                            <i class="bi bi-file-earmark-text"></i> Reports
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= url('analytics') ?>">
                            <i class="bi bi-graph-up"></i> Analytics
                        </a>
                    </li>
                    <?php endif; ?>
                </ul>
                
                <!-- User Menu -->
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle"></i> 
                            <?= e($_SESSION['user_name'] ?? 'User') ?>
                            <span class="badge bg-secondary ms-1"><?= ucfirst(currentUserRole()) ?></span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="<?= url('profile') ?>">
                                <i class="bi bi-person"></i> My Profile
                            </a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" href="<?= url('logout') ?>">
                                <i class="bi bi-box-arrow-right"></i> Logout
                            </a></li>
                        </ul>
                    </li>
                </ul>
            <?php else: ?>
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="<?= url('login') ?>">
                            <i class="bi bi-box-arrow-in-right"></i> Login
                        </a>
                    </li>
                </ul>
            <?php endif; ?>
        </div>
    </div>
</nav>
