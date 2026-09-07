/**
 * Main JavaScript File
 * UniSIRAJ Automated Attendance System
 */

// ============================================
// GLOBAL FUNCTIONS
// ============================================

/**
 * Show alert message
 * @param {string} type - Alert type (success, error, warning, info)
 * @param {string} message - Alert message
 * @param {number} duration - Duration in milliseconds (default: 5000)
 */
function showAlert(type, message, duration = 5000) {
    const alertTypes = {
        success: 'alert-success',
        error: 'alert-danger',
        warning: 'alert-warning',
        info: 'alert-info'
    };
    
    const icons = {
        success: 'bi-check-circle-fill',
        error: 'bi-x-circle-fill',
        warning: 'bi-exclamation-triangle-fill',
        info: 'bi-info-circle-fill'
    };
    
    const alertClass = alertTypes[type] || 'alert-info';
    const iconClass = icons[type] || 'bi-info-circle-fill';
    
    const alertHtml = `
        <div class="alert ${alertClass} alert-dismissible fade show position-fixed top-0 start-50 translate-middle-x mt-3" 
             role="alert" style="z-index: 9999; min-width: 300px; max-width: 500px;">
            <i class="bi ${iconClass} me-2"></i>
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `;
    
    $('body').append(alertHtml);
    
    // Auto dismiss after duration
    setTimeout(function() {
        $('.alert').alert('close');
    }, duration);
}

/**
 * Confirm action with modal
 * @param {string} message - Confirmation message
 * @param {function} callback - Callback function if confirmed
 */
function confirmAction(message, callback) {
    if (confirm(message)) {
        callback();
    }
}

/**
 * Show loading spinner
 */
function showLoading() {
    const spinnerHtml = `
        <div class="loading-spinner" id="loadingSpinner">
            <div class="spinner-border text-light" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
    `;
    $('body').append(spinnerHtml);
}

/**
 * Hide loading spinner
 */
function hideLoading() {
    $('#loadingSpinner').remove();
}

/**
 * Format date to readable format
 * @param {string} dateString - Date string
 * @returns {string} Formatted date
 */
function formatDate(dateString) {
    const date = new Date(dateString);
    const options = { year: 'numeric', month: 'long', day: 'numeric' };
    return date.toLocaleDateString('en-MY', options);
}

/**
 * Format datetime to readable format
 * @param {string} datetimeString - Datetime string
 * @returns {string} Formatted datetime
 */
function formatDateTime(datetimeString) {
    const date = new Date(datetimeString);
    const options = { 
        year: 'numeric', 
        month: 'long', 
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    };
    return date.toLocaleDateString('en-MY', options);
}

/**
 * Validate email format
 * @param {string} email - Email address
 * @returns {boolean} Valid or not
 */
function isValidEmail(email) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(email);
}

/**
 * Copy text to clipboard
 * @param {string} text - Text to copy
 */
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        showAlert('success', 'Copied to clipboard!', 2000);
    }, function() {
        showAlert('error', 'Failed to copy!', 2000);
    });
}

// ============================================
// FORM HANDLING
// ============================================

/**
 * Handle form submission with validation
 */
$(document).on('submit', 'form[data-validate="true"]', function(e) {
    let isValid = true;
    
    // Check required fields
    $(this).find('[required]').each(function() {
        if (!$(this).val()) {
            isValid = false;
            $(this).addClass('is-invalid');
        } else {
            $(this).removeClass('is-invalid');
        }
    });
    
    // Check email fields
    $(this).find('input[type="email"]').each(function() {
        if ($(this).val() && !isValidEmail($(this).val())) {
            isValid = false;
            $(this).addClass('is-invalid');
            showAlert('error', 'Please enter a valid email address');
        }
    });
    
    if (!isValid) {
        e.preventDefault();
        showAlert('error', 'Please fill in all required fields correctly');
    }
});

/**
 * Clear validation errors on input
 */
$(document).on('input change', '.is-invalid', function() {
    $(this).removeClass('is-invalid');
});

// ============================================
// DELETE CONFIRMATION
// ============================================

/**
 * Confirm delete action
 */
$(document).on('click', '.btn-delete', function(e) {
    e.preventDefault();
    const url = $(this).attr('href') || $(this).data('url');
    const message = $(this).data('confirm') || 'Are you sure you want to delete this item?';
    
    confirmAction(message, function() {
        window.location.href = url;
    });
});

// ============================================
// AJAX HELPERS
// ============================================

/**
 * Setup AJAX defaults
 */
$.ajaxSetup({
    headers: {
        'X-Requested-With': 'XMLHttpRequest'
    },
    beforeSend: function() {
        showLoading();
    },
    complete: function() {
        hideLoading();
    },
    error: function(xhr, status, error) {
        hideLoading();
        let message = 'An error occurred. Please try again.';
        
        if (xhr.responseJSON && xhr.responseJSON.message) {
            message = xhr.responseJSON.message;
        }
        
        showAlert('error', message);
    }
});

// ============================================
// TABLE FEATURES
// ============================================

/**
 * Search table
 */
$(document).on('input', '.table-search', function() {
    const searchValue = $(this).val().toLowerCase();
    const $table = $($(this).data('table'));
    
    $table.find('tbody tr').each(function() {
        const text = $(this).text().toLowerCase();
        $(this).toggle(text.indexOf(searchValue) > -1);
    });
});

/**
 * Sort table
 */
$(document).on('click', '.sortable', function() {
    const $table = $(this).closest('table');
    const column = $(this).data('column');
    const order = $(this).data('order') || 'asc';
    
    // Toggle sort order
    const newOrder = order === 'asc' ? 'desc' : 'asc';
    $(this).data('order', newOrder);
    
    // Update sort icon
    $(this).find('.sort-icon').removeClass('bi-arrow-up bi-arrow-down')
           .addClass(newOrder === 'asc' ? 'bi-arrow-up' : 'bi-arrow-down');
    
    // Sort rows
    const rows = $table.find('tbody tr').get();
    rows.sort(function(a, b) {
        const A = $(a).find('td').eq(column).text().toUpperCase();
        const B = $(b).find('td').eq(column).text().toUpperCase();
        
        if (newOrder === 'asc') {
            return (A < B) ? -1 : (A > B) ? 1 : 0;
        } else {
            return (A > B) ? -1 : (A < B) ? 1 : 0;
        }
    });
    
    $.each(rows, function(index, row) {
        $table.find('tbody').append(row);
    });
});

// ============================================
// SESSION TIMEOUT WARNING
// ============================================

/**
 * Warn user before session timeout
 */
let sessionWarningShown = false;
setInterval(function() {
    // Check if user is authenticated
    if ($('.navbar').find('.dropdown-toggle').length > 0) {
        const warningTime = 25 * 60 * 1000; // 25 minutes (5 minutes before timeout)
        const lastActivity = parseInt(localStorage.getItem('lastActivity') || '0');
        const now = Date.now();
        
        if (lastActivity && (now - lastActivity) > warningTime && !sessionWarningShown) {
            sessionWarningShown = true;
            showAlert('warning', 'Your session will expire in 5 minutes. Please save your work.', 10000);
        }
    }
}, 60000); // Check every minute

// Update last activity on any user interaction
$(document).on('click keypress', function() {
    localStorage.setItem('lastActivity', Date.now().toString());
});

// ============================================
// BOOTSTRAP TOOLTIPS & POPOVERS
// ============================================

/**
 * Initialize Bootstrap tooltips
 */
$(function() {
    $('[data-bs-toggle="tooltip"]').tooltip();
});

/**
 * Initialize Bootstrap popovers
 */
$(function() {
    $('[data-bs-toggle="popover"]').popover();
});

// ============================================
// DOCUMENT READY
// ============================================

$(document).ready(function() {
    // Initialize last activity timestamp
    localStorage.setItem('lastActivity', Date.now().toString());
    
    // Auto-hide alerts after 5 seconds
    setTimeout(function() {
        $('.alert:not(.alert-permanent)').fadeOut('slow', function() {
            $(this).remove();
        });
    }, 5000);
    
    // Smooth scroll for anchor links
    $('a[href^="#"]').on('click', function(e) {
        const target = $(this.getAttribute('href'));
        if (target.length) {
            e.preventDefault();
            $('html, body').stop().animate({
                scrollTop: target.offset().top - 70
            }, 500);
        }
    });
    
    // Console message
    console.log('%cUniSIRAJ Attendance System', 'font-size: 20px; font-weight: bold; color: #0d6efd;');
    console.log('%cVersion: 1.0.0', 'font-size: 12px; color: #6c757d;');
    console.log('%cDeveloped by: Ahmed Mohammed Alsadig Mohammed', 'font-size: 12px; color: #6c757d;');
});
