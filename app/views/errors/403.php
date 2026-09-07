<?php require VIEW_PATH . '/layouts/header.php'; ?>

<div class="container">
    <div class="row justify-content-center align-items-center" style="min-height: 80vh;">
        <div class="col-md-6 text-center">
            <div class="error-icon mb-4">
                <i class="bi bi-shield-x" style="font-size: 120px; color: #dc3545;"></i>
            </div>
            <h1 class="display-1 fw-bold text-danger">403</h1>
            <h2 class="mb-4">Access Forbidden</h2>
            <p class="lead text-muted mb-4">
                You don't have permission to access this page.
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
