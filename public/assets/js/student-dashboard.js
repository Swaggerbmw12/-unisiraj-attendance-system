/**
 * Student Dashboard JavaScript
 * UniSIRAJ Automated Attendance System
 */

(function() {
    'use strict';

    // ============================================
    // Initialize Dashboard
    // ============================================
    document.addEventListener('DOMContentLoaded', function() {
        initTooltips();
        initTimeBasedGreeting();
        initCardAnimations();
        checkActiveSessionsNotification();
        initCountdownTimers();
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
    // Time-Based Greeting
    // ============================================
    function initTimeBasedGreeting() {
        const hour = new Date().getHours();
        let greeting = 'Welcome back';
        let emoji = '👋';
        
        if (hour < 12) {
            greeting = 'Good morning';
            emoji = '🌅';
        } else if (hour < 17) {
            greeting = 'Good afternoon';
            emoji = '☀️';
        } else {
            greeting = 'Good evening';
            emoji = '🌙';
        }
        
        const welcomeText = document.querySelector('.container-fluid .row .col-md-8 p.text-muted strong');
        if (welcomeText && welcomeText.parentElement) {
            const userName = welcomeText.textContent.trim();
            const studentNumber = welcomeText.nextElementSibling?.textContent.trim() || '';
            const dateText = new Date().toLocaleDateString('en-US', { 
                weekday: 'long', 
                year: 'numeric', 
                month: 'long', 
                day: 'numeric' 
            });
            
            welcomeText.parentElement.innerHTML = `
                ${greeting}, <strong>${userName}</strong>! ${emoji}
                <small class="text-muted">${studentNumber}</small>
                <br>
                <small>${dateText}</small>
            `;
        }
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
    // Active Sessions Notification
    // ============================================
    function checkActiveSessionsNotification() {
        const activeSessionsAlert = document.querySelector('.alert-success');
        
        if (activeSessionsAlert) {
            // Play subtle notification sound (if available)
            // playNotificationSound();
            
            // Highlight scan button
            const scanButton = document.querySelector('.pulse-button');
            if (scanButton) {
                scanButton.classList.add('btn-pulse-strong');
            }
        }
    }

    // ============================================
    // Countdown Timers for Expiring Sessions
    // ============================================
    function initCountdownTimers() {
        const expiryElements = document.querySelectorAll('[data-expires]');
        
        expiryElements.forEach(function(element) {
            const expiryTime = new Date(element.dataset.expires).getTime();
            updateCountdown(element, expiryTime);
            
            // Update every minute
            setInterval(function() {
                updateCountdown(element, expiryTime);
            }, 60000);
        });
    }

    function updateCountdown(element, expiryTime) {
        const now = new Date().getTime();
        const distance = expiryTime - now;
        
        if (distance < 0) {
            element.textContent = 'Expired';
            element.classList.remove('text-danger');
            element.classList.add('text-muted');
            return;
        }
        
        const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        
        if (minutes < 10) {
            element.classList.add('text-danger', 'fw-bold');
            element.innerHTML = `<i class="bi bi-clock-fill"></i> ${minutes} min left!`;
        } else {
            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            if (hours > 0) {
                element.innerHTML = `<i class="bi bi-clock"></i> ${hours}h ${minutes}m`;
            } else {
                element.innerHTML = `<i class="bi bi-clock"></i> ${minutes} minutes`;
            }
        }
    }

    // ============================================
    // Show Notification Toast
    // ============================================
    function showNotification(message, type = 'info') {
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
    // Animate Numbers
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
    // Attendance Status Color Coding
    // ============================================
    function getAttendanceStatusColor(percentage) {
        if (percentage >= 75) return 'success';
        if (percentage >= 50) return 'warning';
        return 'danger';
    }

    function getAttendanceStatusText(percentage) {
        if (percentage >= 75) return 'Excellent';
        if (percentage >= 50) return 'Fair';
        return 'Low';
    }

    function getAttendanceStatusIcon(percentage) {
        if (percentage >= 75) return 'check-circle';
        if (percentage >= 50) return 'exclamation-triangle';
        return 'x-circle';
    }

    // ============================================
    // Confirm Actions
    // ============================================
    window.confirmAction = function(message) {
        return confirm(message || 'Are you sure you want to perform this action?');
    };

    // ============================================
    // Play Notification Sound
    // ============================================
    function playNotificationSound() {
        // Optional: Add a subtle notification sound
        // const audio = new Audio('/assets/sounds/notification.mp3');
        // audio.volume = 0.3;
        // audio.play().catch(e => console.log('Audio play failed:', e));
    }

    // ============================================
    // Export Functions
    // ============================================
    window.StudentDashboard = {
        showNotification: showNotification,
        animateValue: animateValue,
        getAttendanceStatusColor: getAttendanceStatusColor,
        getAttendanceStatusText: getAttendanceStatusText,
        getAttendanceStatusIcon: getAttendanceStatusIcon
    };

})();
