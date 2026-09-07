<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2><i class="bi bi-bar-chart-line"></i> Analytics Dashboard</h2>
                    <p class="text-muted mb-0">Intelligent insights and predictive analytics for your courses</p>
                </div>
                <div>
                    <select class="form-select" id="courseSelector" onchange="loadAnalytics(this.value)">
                        <option value="">Select a course...</option>
                        <?php foreach ($courses as $course): ?>
                            <option value="<?= $course['id'] ?>" <?= $course['id'] == $selectedCourseId ? 'selected' : '' ?>>
                                <?= e($course['course_code']) ?> - <?= e($course['course_name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </div>
    </div>
    
    <?php if (!$analytics): ?>
        <!-- No Course Selected State -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="bi bi-graph-up" style="font-size: 64px; color: #dee2e6;"></i>
                        <h4 class="mt-3">Select a Course to View Analytics</h4>
                        <p class="text-muted">Choose a course from the dropdown above to see intelligent insights and predictions</p>
                    </div>
                </div>
            </div>
        </div>
    <?php else: ?>
        
        <!-- AI Insights -->
        <?php if (!empty($analytics['insights'])): ?>
        <div class="row mb-4">
            <div class="col-12">
                <?php foreach ($analytics['insights'] as $insight): ?>
                <div class="alert alert-<?= $insight['type'] ?> alert-dismissible fade show" role="alert">
                    <i class="<?= $insight['icon'] ?> me-2"></i>
                    <strong><?= $insight['title'] ?>:</strong> <?= $insight['message'] ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
        
        <!-- Overall Statistics Cards -->
        <div class="row mb-4">
            <div class="col-md-3 mb-3">
                <div class="card stat-card border-primary">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted mb-1">Total Sessions</p>
                                <h3 class="mb-0"><?= number_format($analytics['overall_stats']['total_sessions']) ?></h3>
                            </div>
                            <i class="bi bi-calendar-event text-primary" style="font-size: 2.5rem; opacity: 0.3;"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card stat-card border-success">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted mb-1">Enrolled Students</p>
                                <h3 class="mb-0"><?= number_format($analytics['overall_stats']['total_enrolled']) ?></h3>
                            </div>
                            <i class="bi bi-people text-success" style="font-size: 2.5rem; opacity: 0.3;"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card stat-card border-info">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted mb-1">Avg. Attendance</p>
                                <h3 class="mb-0"><?= number_format($analytics['overall_stats']['avg_attendance_rate'], 1) ?>%</h3>
                            </div>
                            <i class="bi bi-graph-up text-info" style="font-size: 2.5rem; opacity: 0.3;"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card stat-card border-warning">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted mb-1">Active Students</p>
                                <h3 class="mb-0"><?= number_format($analytics['overall_stats']['active_students']) ?></h3>
                            </div>
                            <i class="bi bi-person-check text-warning" style="font-size: 2.5rem; opacity: 0.3;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Charts Row -->
        <div class="row mb-4">
            <!-- Attendance Trends Chart -->
            <div class="col-lg-8 mb-4">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="bi bi-graph-up-arrow"></i> Attendance Trends</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="trendsChart" height="80"></canvas>
                    </div>
                </div>
            </div>
            
            <!-- Performance Distribution Chart -->
            <div class="col-lg-4 mb-4">
                <div class="card">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0"><i class="bi bi-pie-chart"></i> Performance Distribution</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="distributionChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- At-Risk Students & Engagement -->
        <div class="row mb-4">
            <!-- At-Risk Students Table -->
            <div class="col-lg-7 mb-4">
                <div class="card">
                    <div class="card-header bg-danger text-white">
                        <h5 class="mb-0"><i class="bi bi-exclamation-triangle"></i> At-Risk Students (ML-Based Detection)</h5>
                    </div>
                    <div class="card-body">
                        <?php if (empty($analytics['at_risk_students'])): ?>
                            <div class="alert alert-success">
                                <i class="bi bi-check-circle"></i> Great news! No students are currently at risk.
                            </div>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Student</th>
                                            <th>Attendance</th>
                                            <th>Risk Score</th>
                                            <th>Risk Level</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach (array_slice($analytics['at_risk_students'], 0, 10) as $student): ?>
                                        <?php
                                        $badgeClass = [
                                            'critical' => 'danger',
                                            'high' => 'warning',
                                            'medium' => 'info',
                                            'low' => 'secondary'
                                        ][$student['risk_level']];
                                        ?>
                                        <tr>
                                            <td>
                                                <strong><?= e($student['first_name'] . ' ' . $student['last_name']) ?></strong><br>
                                                <small class="text-muted"><?= e($student['student_id']) ?></small>
                                            </td>
                                            <td><?= $student['sessions_attended'] ?>/<?= $student['total_sessions'] ?></td>
                                            <td>
                                                <div class="progress" style="height: 20px; min-width: 60px;">
                                                    <div class="progress-bar bg-<?= $badgeClass ?>" 
                                                         style="width: <?= $student['risk_score'] ?>%">
                                                        <?= round($student['risk_score']) ?>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-<?= $badgeClass ?>">
                                                    <?= ucfirst($student['risk_level']) ?>
                                                </span>
                                            </td>
                                            <td>
                                                <button class="btn btn-sm btn-outline-primary" 
                                                        onclick="showRecommendations(<?= htmlspecialchars(json_encode($student)) ?>)">
                                                    <i class="bi bi-lightbulb"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <!-- Engagement Metrics -->
            <div class="col-lg-5 mb-4">
                <div class="card">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0"><i class="bi bi-activity"></i> Engagement Metrics</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-4">
                            <h6 class="text-muted">Punctuality Rate</h6>
                            <div class="progress" style="height: 30px;">
                                <div class="progress-bar bg-success" 
                                     style="width: <?= $analytics['engagement']['punctuality_rate'] ?>%">
                                    <strong><?= number_format($analytics['engagement']['punctuality_rate'], 1) ?>%</strong>
                                </div>
                            </div>
                            <small class="text-muted">Students checking in within 5 minutes</small>
                        </div>
                        
                        <div class="mb-4">
                            <h6 class="text-muted">Avg. Check-in Delay</h6>
                            <h3 class="mb-0">
                                <?= number_format($analytics['engagement']['avg_check_in_delay'] ?? 0, 1) ?> 
                                <small>minutes</small>
                            </h3>
                        </div>
                        
                        <div>
                            <h6 class="text-muted">Active Participants</h6>
                            <h3 class="mb-0">
                                <?= $analytics['engagement']['total_attendees'] ?>
                                <small>/ <?= $analytics['overall_stats']['total_enrolled'] ?> students</small>
                            </h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Best Performance Times -->
        <?php if (!empty($analytics['session_analysis'])): ?>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-secondary text-white">
                        <h5 class="mb-0"><i class="bi bi-clock-history"></i> Best Performance Times</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Day of Week</th>
                                        <th>Time</th>
                                        <th>Avg. Attendance</th>
                                        <th>Session Count</th>
                                        <th>Performance</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach (array_slice($analytics['session_analysis'], 0, 5) as $analysis): ?>
                                    <?php $performance = $analysis['avg_attendance'] / $analytics['overall_stats']['total_enrolled'] * 100; ?>
                                    <tr>
                                        <td><strong><?= $analysis['day_of_week'] ?></strong></td>
                                        <td><?= sprintf('%02d:00', $analysis['hour_of_day']) ?></td>
                                        <td><?= number_format($analysis['avg_attendance'], 1) ?></td>
                                        <td><?= $analysis['session_count'] ?></td>
                                        <td>
                                            <div class="progress" style="height: 20px; min-width: 100px;">
                                                <div class="progress-bar bg-<?= $performance >= 75 ? 'success' : ($performance >= 50 ? 'warning' : 'danger') ?>" 
                                                     style="width: <?= $performance ?>%">
                                                    <?= number_format($performance, 1) ?>%
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
        
    <?php endif; ?>
</div>

<!-- Recommendations Modal -->
<div class="modal fade" id="recommendationsModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="bi bi-lightbulb"></i> AI-Generated Recommendations</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="recommendationsContent">
                <!-- Content loaded dynamically -->
            </div>
        </div>
    </div>
</div>

<style>
.stat-card {
    transition: transform 0.2s, box-shadow 0.2s;
}

.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.card {
    transition: box-shadow 0.2s;
}

.card:hover {
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}
</style>

<script>
// Load analytics for selected course
function loadAnalytics(courseId) {
    if (courseId) {
        window.location.href = '<?= url('analytics') ?>?course_id=' + courseId;
    }
}

// Show recommendations modal
function showRecommendations(student) {
    const content = document.getElementById('recommendationsContent');
    let html = `
        <h6>Student: ${student.first_name} ${student.last_name}</h6>
        <p><strong>Risk Level:</strong> <span class="badge bg-danger">${student.risk_level.toUpperCase()}</span></p>
        <p><strong>Risk Score:</strong> ${student.risk_score}/100</p>
        <p><strong>Attendance Rate:</strong> ${student.attendance_rate}%</p>
        <hr>
        <h6><i class="bi bi-robot"></i> AI Recommendations:</h6>
        <ul>
    `;
    
    student.recommendations.forEach(rec => {
        html += `<li>${rec}</li>`;
    });
    
    html += '</ul>';
    content.innerHTML = html;
    
    new bootstrap.Modal(document.getElementById('recommendationsModal')).show();
}

<?php if ($analytics): ?>
// Attendance Trends Chart
const trendsCtx = document.getElementById('trendsChart').getContext('2d');
new Chart(trendsCtx, {
    type: 'line',
    data: {
        labels: <?= json_encode(array_column($analytics['trends'], 'session_date')) ?>,
        datasets: [{
            label: 'Attendance Rate (%)',
            data: <?= json_encode(array_column($analytics['trends'], 'attendance_rate')) ?>,
            borderColor: 'rgb(13, 110, 253)',
            backgroundColor: 'rgba(13, 110, 253, 0.1)',
            tension: 0.4,
            fill: true
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: true,
                position: 'top'
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                max: 100,
                ticks: {
                    callback: function(value) {
                        return value + '%';
                    }
                }
            }
        }
    }
});

// Performance Distribution Chart
const distCtx = document.getElementById('distributionChart').getContext('2d');
new Chart(distCtx, {
    type: 'doughnut',
    data: {
        labels: <?= json_encode(array_column($analytics['distribution'], 'category')) ?>,
        datasets: [{
            data: <?= json_encode(array_column($analytics['distribution'], 'student_count')) ?>,
            backgroundColor: [
                'rgba(25, 135, 84, 0.8)',
                'rgba(13, 202, 240, 0.8)',
                'rgba(255, 193, 7, 0.8)',
                'rgba(220, 53, 69, 0.8)'
            ],
            borderWidth: 2,
            borderColor: '#fff'
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: {
                position: 'bottom'
            }
        }
    }
});
<?php endif; ?>
</script>
