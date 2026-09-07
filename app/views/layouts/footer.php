    <!-- Footer -->
    <footer class="bg-light text-center text-lg-start mt-auto">
        <div class="container p-4">
            <div class="text-center text-muted">
                <p class="mb-0">&copy; <?= date('Y') ?> <?= APP_NAME ?>. All rights reserved.</p>
                <p class="mb-0">
                    <small>
                        Final Year Project by Ahmed Mohammed Alsadig Mohammed<br>
                        Supervisor: Dr. Fatimah Noni Muhamad<br>
                        Universiti Islam Antarabangsa Tuanku Syed Sirajuddin (UniSIRAJ)
                    </small>
                </p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap 5 JS Bundle (includes Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- jQuery (for AJAX operations) -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    
    <!-- Custom JS -->
    <script src="<?= asset('js/main.js') ?>"></script>
    
    <!-- Additional JS (if specified) -->
    <?php if (isset($additionalJs)): ?>
        <?= $additionalJs ?>
    <?php endif; ?>
    
    <!-- Flash Messages -->
    <?php if ($success = flash('success')): ?>
    <script>
        showAlert('success', '<?= e($success) ?>');
    </script>
    <?php endif; ?>
    
    <?php if ($error = flash('error')): ?>
    <script>
        showAlert('error', '<?= e($error) ?>');
    </script>
    <?php endif; ?>
    
    <?php if ($info = flash('info')): ?>
    <script>
        showAlert('info', '<?= e($info) ?>');
    </script>
    <?php endif; ?>
    
    <?php if ($warning = flash('warning')): ?>
    <script>
        showAlert('warning', '<?= e($warning) ?>');
    </script>
    <?php endif; ?>
</body>
</html>
