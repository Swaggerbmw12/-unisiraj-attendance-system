<div class="container-fluid" id="reportContent">
    <!-- Report Header -->
    <div class="row mb-4 no-print">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2><i class="bi bi-file-earmark-bar-graph"></i> Attendance Report</h2>
                    <p class="text-muted mb-0">
                        <?= e($reportData['course']['course_code']) ?> - <?= e($reportData['course']['course_name']) ?>
                    </p>
                </div>
                <div>
                    <button onclick="window.print()" class="btn btn-primary">
                        <i class="bi bi-printer"></i> Print Report
                    </button>
                    <a href="<?= url('reports') ?>" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left"></i> Back
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Print Header (only visible when printing) -->
    <div class="print-only text-center mb-4">
        <h3>UniSIRAJ Attendance System</h3>
        <h4>Attendance Report</h4>
        <p class="mb-1">
            <strong><?= e($reportData['course']['course_code']) ?></strong> - 
            <?= e($reportData['course']['course_name']) ?>
        </p>
        <?php if ($dateFrom && $dateTo): ?>
        <p class="text-muted">
            Period: <?= date('M d, Y', strtotime($dateFrom)) ?> to <?= date('M d, Y', strtotime($dateTo)) ?>
        </p>
        <?php else: ?>
        <p class="text-muted">All Sessions</p>
        <?php endif; ?>
        <p class="text-muted">Generated: <?= date('F d, Y h:i A') ?></p>
        <hr>
    </div>
    
    <!-- Report Content Based on Type -->
    <?php if ($reportType === 'course_summary'): ?>
        <?php include 'partials/course_summary.php'; ?>
    <?php elseif ($reportType === 'student_details'): ?>
        <?php include 'partials/student_details.php'; ?>
    <?php elseif ($reportType === 'session_details'): ?>
        <?php include 'partials/session_details.php'; ?>
    <?php endif; ?>
</div>

<style>
@media print {
    .no-print {
        display: none !important;
    }
    
    .print-only {
        display: block !important;
    }
    
    .card {
        border: 1px solid #dee2e6 !important;
        box-shadow: none !important;
        page-break-inside: avoid;
    }
    
    table {
        page-break-inside: auto;
    }
    
    tr {
        page-break-inside: avoid;
        page-break-after: auto;
    }
    
    thead {
        display: table-header-group;
    }
    
    .progress {
        border: 1px solid #dee2e6;
    }
}

.print-only {
    display: none;
}

.stat-card {
    transition: transform 0.2s;
}

.stat-card:hover {
    transform: translateY(-5px);
}
</style>
