<div class="container-fluid">
    <div class="row justify-content-center py-4">
        <div class="col-lg-8">
            <!-- Back Button -->
            <div class="mb-3">
                <a href="<?= url('lecturer/dashboard') ?>" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Back to Dashboard
                </a>
            </div>
            
            <div class="card shadow-lg">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="bi bi-plus-circle"></i> Add New Course
                    </h4>
                    <p class="mb-0 small mt-2">Create a new course and start managing attendance</p>
                </div>
                <div class="card-body p-4">
                    <form id="addCourseForm" method="POST" action="<?= url('lecturer/courses/store') ?>">
                        
                        <!-- Course Identification Section -->
                        <div class="mb-4">
                            <h5 class="border-bottom pb-2 mb-3">
                                <i class="bi bi-info-circle text-primary"></i> Course Identification
                            </h5>
                            <div class="row">
                                <!-- Course Code -->
                                <div class="col-md-6 mb-3">
                                    <label for="course_code" class="form-label">
                                        Course Code <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" 
                                           class="form-control form-control-lg" 
                                           id="course_code" 
                                           name="course_code" 
                                           placeholder="e.g., CS101" 
                                           required>
                                    <small class="form-text text-muted">
                                        <i class="bi bi-lightbulb"></i> Unique identifier (e.g., CS101, MATH202)
                                    </small>
                                </div>
                                
                                <!-- Course Name -->
                                <div class="col-md-6 mb-3">
                                    <label for="course_name" class="form-label">
                                        Course Name <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" 
                                           class="form-control form-control-lg" 
                                           id="course_name" 
                                           name="course_name" 
                                           placeholder="e.g., Introduction to Computer Science" 
                                           required>
                                    <small class="form-text text-muted">
                                        <i class="bi bi-lightbulb"></i> Full descriptive name
                                    </small>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Academic Details Section -->
                        <div class="mb-4">
                            <h5 class="border-bottom pb-2 mb-3">
                                <i class="bi bi-calendar3 text-primary"></i> Academic Details
                            </h5>
                            <div class="row">
                                <!-- Semester -->
                                <div class="col-md-4 mb-3">
                                    <label for="semester" class="form-label">
                                        Semester <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select form-select-lg" id="semester" name="semester" required>
                                        <option value="">Select Semester</option>
                                        <option value="Semester 1">Semester 1</option>
                                        <option value="Semester 2">Semester 2</option>
                                        <option value="Semester 3">Semester 3</option>
                                        <option value="Summer">Summer</option>
                                    </select>
                                </div>
                                
                                <!-- Academic Year -->
                                <div class="col-md-4 mb-3">
                                    <label for="academic_year" class="form-label">
                                        Academic Year <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select form-select-lg" id="academic_year" name="academic_year" required>
                                        <option value="">Select Year</option>
                                        <?php
                                        $currentYear = date('Y');
                                        for ($i = 0; $i < 5; $i++) {
                                            $year = $currentYear + $i;
                                            $nextYear = $year + 1;
                                            $value = "$year/$nextYear";
                                            $selected = ($i === 0) ? 'selected' : '';
                                            echo "<option value='$value' $selected>$value</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                
                                <!-- Credits -->
                                <div class="col-md-4 mb-3">
                                    <label for="credits" class="form-label">
                                        Credit Hours
                                    </label>
                                    <input type="number" 
                                           class="form-control form-control-lg" 
                                           id="credits" 
                                           name="credits" 
                                           min="1" 
                                           max="12" 
                                           value="3"
                                           placeholder="3">
                                    <small class="form-text text-muted">
                                        <i class="bi bi-lightbulb"></i> Typically 1-12
                                    </small>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Course Description Section -->
                        <div class="mb-4">
                            <h5 class="border-bottom pb-2 mb-3">
                                <i class="bi bi-card-text text-primary"></i> Course Description
                            </h5>
                            <div class="mb-3">
                                <label for="description" class="form-label">
                                    Description (Optional)
                                </label>
                                <textarea class="form-control" 
                                          id="description" 
                                          name="description" 
                                          rows="5" 
                                          placeholder="Enter course description, objectives, prerequisites, and other relevant information..."></textarea>
                                <small class="form-text text-muted">
                                    <i class="bi bi-lightbulb"></i> Provide details to help students understand the course
                                </small>
                            </div>
                        </div>
                        
                        <!-- Info Box -->
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle"></i>
                            <strong>Note:</strong> Once created, this course will appear in your sidebar menu and be visible to administrators. You can start creating attendance sessions immediately after adding the course.
                        </div>
                        
                        <!-- Buttons -->
                        <div class="d-flex justify-content-between align-items-center">
                            <a href="<?= url('lecturer/dashboard') ?>" class="btn btn-secondary btn-lg">
                                <i class="bi bi-x-circle"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary btn-lg px-5" id="submitBtn">
                                <i class="bi bi-check-circle"></i> Add Course
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Custom Styles for Form -->
<style>
.card {
    border: none;
    border-radius: 15px;
}

.card-header {
    border-radius: 15px 15px 0 0 !important;
}

.form-label {
    font-weight: 600;
    color: #495057;
}

.form-control, .form-select {
    border-radius: 8px;
    border: 2px solid #e9ecef;
    padding: 0.75rem;
    transition: all 0.3s ease;
}

.form-control:focus, .form-select:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.15);
}

.btn-lg {
    border-radius: 8px;
    padding: 0.75rem 2rem;
    font-weight: 600;
}

.alert-info {
    border-left: 4px solid #0dcaf0;
    border-radius: 8px;
}

textarea.form-control {
    resize: vertical;
}

.text-danger {
    font-weight: 600;
}
</style>

<script>
document.getElementById('addCourseForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const submitBtn = document.getElementById('submitBtn');
    const originalText = submitBtn.innerHTML;
    
    // Disable button and show loading
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Adding Course...';
    
    // Submit form
    const formData = new FormData(this);
    
    fetch(this.action, {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: formData
    })
    .then(response => {
        // Check if response is JSON
        const contentType = response.headers.get('content-type');
        if (!contentType || !contentType.includes('application/json')) {
            throw new Error('Server returned non-JSON response');
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            // Show success message
            alert('✓ Course added successfully!');
            // Redirect to course dashboard
            window.location.href = '<?= url('lecturer/courses/dashboard') ?>?id=' + data.course_id;
        } else {
            // Show error
            const errorMessage = data.errors ? data.errors.join('\n') : (data.message || 'An error occurred while adding the course');
            alert('Error: ' + errorMessage);
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while adding the course. Please check your connection and try again.');
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
    });
});
</script>
