/**
 * Lecturer Dashboard JavaScript
 * UniSIRAJ Automated Attendance System
 */

(function() {
    'use strict';

    // ============================================
    // Initialize Dashboard
    // ============================================
    document.addEventListener('DOMContentLoaded', function() {
        initTooltips();
        initAutoRefresh();
        initCardAnimations();
        displayWelcomeMessage();
    });

    // ============================================
    // Initialize Bootstrap Tooltips
    // ============================================
    function initTooltips() {
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    }

    // ============================================
    // Auto-refresh Active Sessions
    // ============================================
    function initAutoRefresh() {
        // Check for active sessions every 30 seconds
        const activeSessionBadges = document.querySelectorAll('.badge.bg-success');
        
        if (activeSessionBadges.length > 0) {
            // Only auto-refresh if there are active sessions
            setInterval(function() {
                refreshActiveSessions();
            }, 30000); // 30 seconds
        }
    }

    function refreshActiveSessions() {
        // Fetch updated session data via AJAX
        // This would be implemented with your backend API
        console.log('Refreshing active sessions...');
        
        // Example implementation (you'll need to create the API endpoint)
        /*
        fetch('/api/lecturer/active-sessions')
            .then(response => response.json())
            .then(data => {
                updateActiveSessionCount(data.count);
            })
            .catch(error => console.error('Error refreshing sessions:', error));
        */
    }

    function updateActiveSessionCount(count) {
        const activeCountElement = document.querySelector('.stat-card.stat-warning .stat-value');
        if (activeCountElement) {
            // Animate the count update
            animateValue(activeCountElement, parseInt(activeCountElement.textContent), count, 500);
        }
    }

    // ============================================
    // Animate Number Values
    // ============================================
    function animateValue(element, start, end, duration) {
        let startTimestamp = null;
        const step = (timestamp) => {
            if (!startTimestamp) startTimestamp = timestamp;
            const progress = Math.min((timestamp - startTimestamp) / duration, 1);
            element.textContent = Math.floor(progress * (end - start) + start);
            if (progress < 1) {
                window.requestAnimationFrame(step);
            }
        };
        window.requestAnimationFrame(step);
    }

    // ============================================
    // Card Animation on Scroll
    // ============================================
    function initCardAnimations() {
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(function(entry) {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, observerOptions);

        // Observe all cards
        const cards = document.querySelectorAll('.card');
        cards.forEach(function(card) {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            card.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
            observer.observe(card);
        });
    }

    // ============================================
    // Welcome Message based on Time of Day
    // ============================================
    function displayWelcomeMessage() {
        const hour = new Date().getHours();
        let greeting = 'Welcome back';
        
        if (hour < 12) {
            greeting = 'Good morning';
        } else if (hour < 17) {
            greeting = 'Good afternoon';
        } else {
            greeting = 'Good evening';
        }
        
        const welcomeElement = document.querySelector('.container-fluid .row .col-md-8 p.text-muted');
        if (welcomeElement) {
            const userName = welcomeElement.textContent.split(',')[0].replace('Welcome back', '').trim();
            welcomeElement.innerHTML = `${greeting}, <strong>${userName}</strong>! <small class="text-muted">${new Date().toLocaleDateString('en-US', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' })}</small>`;
        }
    }

    // ============================================
    // Confirm Actions
    // ============================================
    window.confirmAction = function(message) {
        return confirm(message || 'Are you sure you want to perform this action?');
    };

    // ============================================
    // Copy to Clipboard
    // ============================================
    window.copyToClipboard = function(text) {
        navigator.clipboard.writeText(text).then(function() {
            showNotification('Copied to clipboard!', 'success');
        }).catch(function(err) {
            console.error('Failed to copy:', err);
            showNotification('Failed to copy to clipboard', 'error');
        });
    };

    // ============================================
    // Show Notification Toast
    // ============================================
    function showNotification(message, type = 'info') {
        // Create toast element
        const toastContainer = document.querySelector('.toast-container') || createToastContainer();
        
        const toastHTML = `
            <div class="toast align-items-center text-white bg-${type === 'success' ? 'success' : type === 'error' ? 'danger' : 'info'} border-0" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">
                        ${message}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        `;
        
        toastContainer.insertAdjacentHTML('beforeend', toastHTML);
        
        const toastElement = toastContainer.lastElementChild;
        const toast = new bootstrap.Toast(toastElement, { delay: 3000 });
        toast.show();
        
        // Remove toast element after it's hidden
        toastElement.addEventListener('hidden.bs.toast', function() {
            toastElement.remove();
        });
    }

    function createToastContainer() {
        const container = document.createElement('div');
        container.className = 'toast-container position-fixed top-0 end-0 p-3';
        container.style.zIndex = '9999';
        document.body.appendChild(container);
        return container;
    }

    // ============================================
    // Export Functions
    // ============================================
    window.LecturerDashboard = {
        refreshActiveSessions: refreshActiveSessions,
        showNotification: showNotification,
        animateValue: animateValue
    };

})();
