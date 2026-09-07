<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($title) ?> - <?= APP_NAME ?></title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .login-container {
            width: 100%;
            max-width: 420px;
            padding: 15px;
        }
        
        .login-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            overflow: hidden;
        }
        
        .login-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2.5rem 2rem;
            text-align: center;
        }
        
        .login-header i {
            font-size: 3.5rem;
            margin-bottom: 0.5rem;
            opacity: 0.95;
        }
        
        .login-header h1 {
            margin: 0;
            font-weight: 700;
            font-size: 1.5rem;
            letter-spacing: 0.5px;
        }
        
        .login-header p {
            margin: 0.5rem 0 0;
            opacity: 0.85;
            font-size: 0.875rem;
        }
        
        .login-body {
            padding: 2.5rem 2rem;
        }
        
        .form-floating {
            margin-bottom: 1.25rem;
        }
        
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        
        .btn-login {
            width: 100%;
            padding: 0.875rem;
            font-size: 1rem;
            font-weight: 600;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            transition: all 0.3s ease;
        }
        
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
        }
        
        .login-footer {
            text-align: center;
            padding: 1.25rem;
            background: #f8f9fa;
            color: #6c757d;
            font-size: 0.813rem;
        }
        
        .alert {
            border-radius: 8px;
            border: none;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <!-- Header -->
            <div class="login-header">
                <i class="bi bi-qr-code-scan"></i>
                <h1>UniSIRAJ Attendance System</h1>
                <?php 
                $role = $_GET['role'] ?? null;
                $roleText = 'Student Attendance Management';
                $roleIcon = 'bi-qr-code-scan';
                
                if ($role === 'admin') {
                    $roleText = 'Administrator Login';
                    $roleIcon = 'bi-shield-lock';
                } elseif ($role === 'lecturer') {
                    $roleText = 'Lecturer Login';
                    $roleIcon = 'bi-person-workspace';
                } elseif ($role === 'student') {
                    $roleText = 'Student Login';
                    $roleIcon = 'bi-person-badge';
                }
                ?>
                <p><?= $roleText ?></p>
            </div>
            
            <!-- Body -->
            <div class="login-body">
                <!-- Flash Messages -->
                <?php if ($error = flash('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-circle me-2"></i>
                    <?= e($error) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php endif; ?>
                
                <?php if ($success = flash('success')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle me-2"></i>
                    <?= e($success) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php endif; ?>
                
                <!-- Login Form -->
                <form method="POST" action="<?= url('login') ?>" novalidate>
                    <!-- CSRF Token -->
                    <input type="hidden" name="<?= CSRF_TOKEN_NAME ?>" value="<?= e($csrfToken) ?>">
                    
                    <!-- Email Field -->
                    <div class="form-floating mb-3">
                        <input type="email" 
                               class="form-control <?= isset($_SESSION['errors']['email']) ? 'is-invalid' : '' ?>" 
                               id="email" 
                               name="email" 
                               placeholder="name@example.com"
                               value="<?= e($_SESSION['old_input']['email'] ?? '') ?>"
                               required
                               autofocus>
                        <label for="email"><i class="bi bi-envelope me-2"></i>Email Address</label>
                        <?php if (isset($_SESSION['errors']['email'])): ?>
                            <div class="invalid-feedback">
                                <?= e($_SESSION['errors']['email']) ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Password Field -->
                    <div class="form-floating mb-3">
                        <input type="password" 
                               class="form-control <?= isset($_SESSION['errors']['password']) ? 'is-invalid' : '' ?>" 
                               id="password" 
                               name="password" 
                               placeholder="Password"
                               required>
                        <label for="password"><i class="bi bi-lock me-2"></i>Password</label>
                        <?php if (isset($_SESSION['errors']['password'])): ?>
                            <div class="invalid-feedback">
                                <?= e($_SESSION['errors']['password']) ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Remember Me -->
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="remember" name="remember">
                            <label class="form-check-label text-muted" for="remember">
                                Remember me
                            </label>
                        </div>
                    </div>
                    
                    <!-- Submit Button -->
                    <button type="submit" class="btn btn-primary btn-login">
                        <i class="bi bi-box-arrow-in-right me-2"></i>
                        Sign In
                    </button>
                    
                    <!-- Back to Home -->
                    <div class="text-center mt-3">
                        <a href="<?= url('/') ?>" class="text-decoration-none text-muted">
                            <i class="bi bi-arrow-left me-1"></i>
                            Back to Home
                        </a>
                    </div>
                </form>
            </div>
            
            <!-- Footer -->
            <div class="login-footer">
                <i class="bi bi-shield-check me-1"></i>
                Secure Login Portal
            </div>
        </div>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Clear errors and old input from session after displaying
        <?php 
        unset($_SESSION['errors']);
        unset($_SESSION['old_input']);
        ?>
        
        // Auto-hide alerts after 5 seconds
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                setTimeout(() => {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                }, 5000);
            });
        }, 100);
    </script>
</body>
</html>
