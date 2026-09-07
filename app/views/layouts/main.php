<?php require VIEW_PATH . '/layouts/header.php'; ?>

<?php require VIEW_PATH . '/layouts/navbar.php'; ?>

<!-- Main Content -->
<main class="<?= (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'lecturer') ? '' : '' ?> py-4" style="min-height: calc(100vh - 200px);">
    <?= $content ?>
</main>

<?php require VIEW_PATH . '/layouts/footer.php'; ?>
