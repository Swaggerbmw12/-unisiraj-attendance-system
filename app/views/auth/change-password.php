<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">
                        <i class="bi bi-key"></i> Change Password
                    </h4>
                </div>
                <div class="card-body">
                    <!-- Flash Messages -->
                    <?php if ($error = flash('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show">
                        <i class="bi bi-x-circle me-2"></i>
                        <?= e($error) ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    <?php endif; ?>
                    
                    <?php if ($success = flash('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show">
                        <i class="bi bi-check-circle me-2"></i>
                        <?= e($success) ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    <?php endif; ?>
                    
                    <form method="POST" action="<?= url('change-password') ?>">
                        <!-- CSRF Token -->
                        <input type="hidden" name="<?= CSRF_TOKEN_NAME ?>" value="<?= e($csrfToken) ?>">
                        
                        <!-- Current Password -->
                        <div class="mb-3">
                            <label for="current_password" class="form-label">Current Password</label>
                            <input type="password" 
                                   class="form-control <?= isset($_SESSION['errors']['current_password']) ? 'is-invalid' : '' ?>" 
                                   id="current_password" 
                                   name="current_password" 
                                   required>
                            <?php if (isset($_SESSION['errors']['current_password'])): ?>
                                <div class="invalid-feedback">
                                    <?= e($_SESSION['errors']['current_password']) ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <!-- New Password -->
                        <div class="mb-3">
                            <label for="new_password" class="form-label">New Password</label>
                            <input type="password" 
                                   class="form-control <?= isset($_SESSION['errors']['new_password']) ? 'is-invalid' : '' ?>" 
                                   id="new_password" 
                                   name="new_password" 
                                   required
                                   minlength="<?= PASSWORD_MIN_LENGTH ?>">
                            <small class="text-muted">Minimum <?= PASSWORD_MIN_LENGTH ?> characters</small>
                            <?php if (isset($_SESSION['errors']['new_password'])): ?>
                                <div class="invalid-feedback">
                                    <?= e($_SESSION['errors']['new_password']) ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Confirm Password -->
                        <div class="mb-3">
                            <label for="confirm_password" class="form-label">Confirm New Password</label>
                            <input type="password" 
                                   class="form-control <?= isset($_SESSION['errors']['confirm_password']) ? 'is-invalid' : '' ?>" 
                                   id="confirm_password" 
                                   name="confirm_password" 
                                   required>
                            <?php if (isset($_SESSION['errors']['confirm_password'])): ?>
                                <div class="invalid-feedback">
                                    <?= e($_SESSION['errors']['confirm_password']) ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Submit Button -->
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-lg"></i> Change Password
                            </button>
                            <a href="javascript:history.back()" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Password Requirements Info -->
            <div class="card mt-3">
                <div class="card-body">
                    <h6 class="card-title"><i class="bi bi-info-circle"></i> Password Requirements</h6>
                    <ul class="mb-0">
                        <li>Minimum <?= PASSWORD_MIN_LENGTH ?> characters</li>
                        <li>Use a mix of letters, numbers, and symbols</li>
                        <li>Avoid common words or personal information</li>
                        <li>Don't reuse old passwords</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<?php 
// Clear errors from session
unset($_SESSION['errors']);
?>
