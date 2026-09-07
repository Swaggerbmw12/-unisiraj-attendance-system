<?php require VIEW_PATH . '/layouts/header.php'; ?>

<div class="container">
    <div class="row justify-content-center align-items-center" style="min-height: 80vh;">
        <div class="col-md-6 text-center">
            <div class="error-icon mb-4">
                <i class="bi bi-exclamation-triangle" style="font-size: 120px; color: #ffc107;"></i>
            </div>
            <h1 class="display-1 fw-bold text-warning">404</h1>
            <h2 class="mb-4">Page Not Found</h2>
            <p class="lead text-muted mb-4">
                The page you are looking for doesn't exist or has been moved.
            </p>
            <div class="d-flex gap-2 justify-content-center">
                <a href="javascript:history.back()" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Go Back
                </a>
                <a href="<?= url('/') ?>" class="btn btn-primary">
                    <i class="bi bi-house"></i> Go to Home
                </a>
            </div>
        </div>
    </div>
</div>

<?php require VIEW_PATH . '/layouts/footer.php'; ?>
