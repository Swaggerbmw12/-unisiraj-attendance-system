<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Admin Dashboard' ?> - UniSIRAJ Attendance System</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <!-- Custom CSS -->
    <link href="<?= asset('css/style.css') ?>" rel="stylesheet">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f7fa;
        }
        
        /* Top Header */
        .top-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 12px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            position: sticky;
            top: 0;
            z-index: 1000;
        }
        
        .top-header .brand {
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 600;
            font-size: 1.1rem;
        }
        
        .top-header .brand i {
            font-size: 1.5rem;
        }
        
        .top-header .user-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .user-profile {
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
            padding: 8px 15px;
            border-radius: 25px;
            background: rgba(255,255,255,0.1);
            transition: all 0.3s ease;
        }
        
        .user-profile:hover {
            background: rgba(255,255,255,0.2);
        }
        
        .user-avatar {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            background: white;
            color: #667eea;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
        }
        
        /* Layout Container */
        .admin-container {
            display: flex;
            min-height: calc(100vh - 60px);
        }
        
        /* Sidebar */
        .sidebar {
            width: 280px;
            background: white;
            box-shadow: 2px 0 10px rgba(0,0,0,0.05);
            position: sticky;
            top: 60px;
            height: calc(100vh - 60px);
            overflow-y: auto;
            transition: all 0.3s ease;
        }
        
        .sidebar-header {
            padding: 20px;
            border-bottom: 1px solid #e9ecef;
        }
        
        .sidebar-header h5 {
            margin: 0;
            color: #495057;
            font-weight: 600;
            font-size: 1rem;
        }
        
        /* Sidebar Menu */
        .sidebar-menu {
            list-style: none;
            padding: 10px 0;
            margin: 0;
        }
        
        .sidebar-menu > li {
            margin-bottom: 5px;
        }
        
        .menu-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px 20px;
            color: #495057;
            text-decoration: none;
            transition: all 0.3s ease;
            cursor: pointer;
            user-select: none;
        }
        
        .menu-item:hover {
            background: #f8f9fa;
            color: #667eea;
        }
        
        .menu-item.active {
            background: linear-gradient(90deg, #667eea15 0%, transparent 100%);
            color: #667eea;
            border-left: 3px solid #667eea;
            font-weight: 600;
        }
        
        .menu-item i {
            font-size: 1.1rem;
            margin-right: 12px;
            width: 20px;
        }
        
        .menu-item .menu-label {
            flex-grow: 1;
        }
        
        .menu-item .menu-arrow {
            font-size: 0.9rem;
            transition: transform 0.3s ease;
        }
        
        .menu-item.expanded .menu-arrow {
            transform: rotate(90deg);
        }
        
        /* Submenu */
        .submenu {
            list-style: none;
            padding: 0;
            margin: 0;
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease;
            background: #f8f9fa;
        }
        
        .submenu.show {
            max-height: 500px;
        }
        
        .submenu-item {
            display: block;
            padding: 10px 20px 10px 52px;
            color: #6c757d;
            text-decoration: none;
            transition: all 0.3s ease;
            font-size: 0.9rem;
        }
        
        .submenu-item:hover {
            background: #e9ecef;
            color: #667eea;
            padding-left: 56px;
        }
        
        .submenu-item.active {
            color: #667eea;
            font-weight: 600;
            background: #e9ecef;
        }
        
        /* Nested Submenu */
        .submenu-parent {
            display: flex;
            align-items: center;
            justify-content: space-between;
            cursor: pointer;
            position: relative;
        }
        
        .submenu-parent .nested-arrow {
            font-size: 0.8rem;
            transition: transform 0.3s ease;
        }
        
        .submenu-parent.expanded .nested-arrow {
            transform: rotate(90deg);
        }
        
        .nested-submenu {
            list-style: none;
            padding: 0;
            margin: 0;
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease;
            background: #e9ecef;
        }
        
        .nested-submenu.show {
            max-height: 300px;
        }
        
        .nested-submenu-item {
            display: block;
            padding: 10px 20px 10px 68px;
            color: #6c757d;
            text-decoration: none;
            transition: all 0.3s ease;
            font-size: 0.85rem;
        }
        
        .nested-submenu-item:hover {
            background: #dee2e6;
            color: #667eea;
            padding-left: 72px;
        }
        
        .nested-submenu-item.active {
            color: #667eea;
            font-weight: 600;
            background: #dee2e6;
        }
        
        /* Main Content */
        .main-content {
            flex: 1;
            padding: 25px;
            overflow-x: hidden;
        }
        
        /* Scrollbar */
        .sidebar::-webkit-scrollbar {
            width: 6px;
        }
        
        .sidebar::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        
        .sidebar::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 3px;
        }
        
        .sidebar::-webkit-scrollbar-thumb:hover {
            background: #555;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                position: fixed;
                left: -280px;
                z-index: 999;
            }
            
            .sidebar.show {
                left: 0;
            }
            
            .main-content {
                margin-left: 0;
            }
        }
        
        /* Dropdown Menu Styles */
        .dropdown-menu {
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            border: none;
            margin-top: 8px;
        }
        
        .dropdown-item {
            padding: 10px 20px;
            transition: all 0.2s ease;
        }
        
        .dropdown-item:hover {
            background: #f8f9fa;
            color: #667eea;
            padding-left: 25px;
        }
        
        /* Toast Notification Styles */
        .toast-container {
            position: fixed;
            top: 80px;
            right: 20px;
            z-index: 9999;
            min-width: 320px;
            max-width: 400px;
        }
        
        .toast-notification {
            background: white;
            border-radius: 8px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
            margin-bottom: 10px;
            overflow: hidden;
            animation: slideInRight 0.3s ease-out;
            display: flex;
            align-items: stretch;
        }
        
        .toast-notification.hiding {
            animation: slideOutRight 0.3s ease-out;
        }
        
        @keyframes slideInRight {
            from {
                transform: translateX(400px);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        
        @keyframes slideOutRight {
            from {
                transform: translateX(0);
                opacity: 1;
            }
            to {
                transform: translateX(400px);
                opacity: 0;
            }
        }
        
        .toast-notification .toast-icon {
            width: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            color: white;
        }
        
        .toast-notification.success .toast-icon {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        }
        
        .toast-notification.error .toast-icon {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        }
        
        .toast-notification.info .toast-icon {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        }
        
        .toast-notification.warning .toast-icon {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        }
        
        .toast-notification .toast-content {
            flex: 1;
            padding: 15px 20px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        
        .toast-notification .toast-title {
            font-weight: 600;
            font-size: 0.95rem;
            margin-bottom: 3px;
            color: #1f2937;
        }
        
        .toast-notification .toast-message {
            font-size: 0.875rem;
            color: #6b7280;
            line-height: 1.4;
        }
        
        .toast-notification .toast-close {
            padding: 15px;
            background: none;
            border: none;
            color: #9ca3af;
            cursor: pointer;
            font-size: 18px;
            transition: color 0.2s ease;
            display: flex;
            align-items: center;
        }
        
        .toast-notification .toast-close:hover {
            color: #4b5563;
        }
        
        .toast-notification .toast-progress {
            position: absolute;
            bottom: 0;
            left: 0;
            height: 3px;
            background: rgba(0, 0, 0, 0.1);
            animation: progressBar 5s linear;
        }
        
        @keyframes progressBar {
            from {
                width: 100%;
            }
            to {
                width: 0%;
            }
        }
        
        @media (max-width: 768px) {
            .toast-container {
                right: 10px;
                left: 10px;
                min-width: auto;
                max-width: none;
            }
        }
    </style>
</head>
<body>
    <!-- Top Header -->
    <div class="top-header">
        <div class="brand">
            <i class="bi bi-qr-code-scan"></i>
            <span>UniSIRAJ Attendance System</span>
        </div>
        <div class="user-info">
            <div class="dropdown">
                <div class="user-profile" data-bs-toggle="dropdown" aria-expanded="false">
                    <div class="user-avatar">
                        <i class="bi bi-person-fill"></i>
                    </div>
                    <div>
                        <div style="font-size: 0.9rem; font-weight: 600;">
                            <?= e($_SESSION['user_name'] ?? 'Admin') ?>
                        </div>
                        <div style="font-size: 0.75rem; opacity: 0.9;">
                            Administrator
                        </div>
                    </div>
                    <i class="bi bi-chevron-down"></i>
                </div>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="<?= url('profile') ?>">
                        <i class="bi bi-person me-2"></i> My Profile
                    </a></li>
                    <li><a class="dropdown-item" href="<?= url('change-password') ?>">
                        <i class="bi bi-key me-2"></i> Change Password
                    </a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item text-danger" href="<?= url('logout') ?>">
                        <i class="bi bi-box-arrow-right me-2"></i> Logout
                    </a></li>
                </ul>
            </div>
        </div>
    </div>
    
    <!-- Main Container -->
    <div class="admin-container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <h5>Admin Panel</h5>
            </div>
            
            <ul class="sidebar-menu">
                <!-- Faculties -->
                <li>
                    <div class="menu-item" onclick="toggleSubmenu(this)">
                        <i class="bi bi-building"></i>
                        <span class="menu-label">Faculties</span>
                        <i class="bi bi-chevron-right menu-arrow"></i>
                    </div>
                    <ul class="submenu">
                        <li><a href="<?= url('admin/faculties/islamic-studies') ?>" class="submenu-item">
                            Faculty of Islamic Studies
                        </a></li>
                        <li><a href="<?= url('admin/faculties/islamic-transaction') ?>" class="submenu-item">
                            Faculty of Islamic Transaction and Finance
                        </a></li>
                        <li>
                            <div class="submenu-item submenu-parent" onclick="toggleNestedSubmenu(event, this)">
                                Faculty of Business and Management Science
                                <i class="bi bi-chevron-right ms-auto nested-arrow"></i>
                            </div>
                            <ul class="nested-submenu">
                                <li><a href="<?= url('admin/departments/computer-science') ?>" class="nested-submenu-item">
                                    <i class="bi bi-cpu me-2"></i> Computer Science
                                </a></li>
                                <li><a href="<?= url('admin/departments/information-technology') ?>" class="nested-submenu-item">
                                    <i class="bi bi-laptop me-2"></i> Information Technology
                                </a></li>
                                <li><a href="<?= url('admin/departments/business-administration') ?>" class="nested-submenu-item">
                                    <i class="bi bi-briefcase me-2"></i> Business Administration
                                </a></li>
                            </ul>
                        </li>
                        <li><a href="<?= url('admin/faculties/quran-sunnah') ?>" class="submenu-item">
                            Faculty of the Quran and Sunnah
                        </a></li>
                    </ul>
                </li>
                
                <!-- Manage Students -->
                <li>
                    <div class="menu-item" onclick="toggleSubmenu(this)">
                        <i class="bi bi-people-fill"></i>
                        <span class="menu-label">Manage Students</span>
                        <i class="bi bi-chevron-right menu-arrow"></i>
                    </div>
                    <ul class="submenu">
                        <li><a href="<?= url('admin/students') ?>" class="submenu-item">
                            <i class="bi bi-list-ul me-2"></i> View Students
                        </a></li>
                        <li><a href="<?= url('admin/students/attendance-report') ?>" class="submenu-item">
                            <i class="bi bi-file-earmark-bar-graph me-2"></i> General Attendance Report
                        </a></li>
                    </ul>
                </li>
                
                <!-- Lecturers -->
                <li>
                    <div class="menu-item" onclick="toggleSubmenu(this)">
                        <i class="bi bi-person-workspace"></i>
                        <span class="menu-label">Lecturers</span>
                        <i class="bi bi-chevron-right menu-arrow"></i>
                    </div>
                    <ul class="submenu">
                        <li><a href="<?= url('admin/lecturers/faculty/islamic-studies') ?>" class="submenu-item">
                            Faculty of Islamic Studies
                        </a></li>
                        <li><a href="<?= url('admin/lecturers/faculty/islamic-transaction') ?>" class="submenu-item">
                            Faculty of Islamic Transaction and Finance
                        </a></li>
                        <li><a href="<?= url('admin/lecturers/faculty/business-management') ?>" class="submenu-item">
                            Faculty of Business and Management Science
                        </a></li>
                        <li><a href="<?= url('admin/lecturers/faculty/quran-sunnah') ?>" class="submenu-item">
                            Faculty of the Quran and Sunnah
                        </a></li>
                    </ul>
                </li>
            </ul>
        </aside>
        
        <!-- Main Content -->
        <main class="main-content">
            <!-- Toast Container for Flash Messages -->
            <div class="toast-container" id="toastContainer"></div>
            
            <?= $content ?>
        </main>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JS -->
    <script>
        // Toast Notification System
        function showToast(message, type = 'success', duration = 5000) {
            const container = document.getElementById('toastContainer');
            
            // Create toast element
            const toast = document.createElement('div');
            toast.className = `toast-notification ${type}`;
            
            // Icon based on type
            const icons = {
                success: 'bi-check-circle-fill',
                error: 'bi-x-circle-fill',
                info: 'bi-info-circle-fill',
                warning: 'bi-exclamation-triangle-fill'
            };
            
            // Titles based on type
            const titles = {
                success: 'Success',
                error: 'Error',
                info: 'Information',
                warning: 'Warning'
            };
            
            toast.innerHTML = `
                <div class="toast-icon">
                    <i class="bi ${icons[type]}"></i>
                </div>
                <div class="toast-content">
                    <div class="toast-title">${titles[type]}</div>
                    <div class="toast-message">${message}</div>
                </div>
                <button class="toast-close" onclick="dismissToast(this)">
                    <i class="bi bi-x"></i>
                </button>
                <div class="toast-progress"></div>
            `;
            
            container.appendChild(toast);
            
            // Auto dismiss after duration
            setTimeout(() => {
                dismissToast(toast);
            }, duration);
        }
        
        function dismissToast(element) {
            const toast = element.classList ? element : element.parentElement;
            toast.classList.add('hiding');
            
            setTimeout(() => {
                toast.remove();
            }, 300);
        }
        
        // Show flash messages on page load
        document.addEventListener('DOMContentLoaded', function() {
            <?php if ($successMsg = flash('success')): ?>
                showToast(<?= json_encode($successMsg) ?>, 'success');
            <?php endif; ?>
            
            <?php if ($errorMsg = flash('error')): ?>
                showToast(<?= json_encode($errorMsg) ?>, 'error');
            <?php endif; ?>
            
            <?php if ($infoMsg = flash('info')): ?>
                showToast(<?= json_encode($infoMsg) ?>, 'info');
            <?php endif; ?>
            
            <?php if ($warningMsg = flash('warning')): ?>
                showToast(<?= json_encode($warningMsg) ?>, 'warning');
            <?php endif; ?>
        });
        
        // Toggle submenu
        function toggleSubmenu(element) {
            const submenu = element.nextElementSibling;
            const isExpanded = element.classList.contains('expanded');
            
            // Close all other submenus
            document.querySelectorAll('.menu-item.expanded').forEach(item => {
                if (item !== element) {
                    item.classList.remove('expanded');
                    item.nextElementSibling.classList.remove('show');
                }
            });
            
            // Toggle current submenu
            if (isExpanded) {
                element.classList.remove('expanded');
                submenu.classList.remove('show');
            } else {
                element.classList.add('expanded');
                submenu.classList.add('show');
            }
        }
        
        // Toggle nested submenu
        function toggleNestedSubmenu(event, element) {
            event.preventDefault();
            event.stopPropagation();
            
            const nestedSubmenu = element.nextElementSibling;
            const isExpanded = element.classList.contains('expanded');
            
            // Close all other nested submenus
            document.querySelectorAll('.submenu-parent.expanded').forEach(item => {
                if (item !== element) {
                    item.classList.remove('expanded');
                    item.nextElementSibling.classList.remove('show');
                }
            });
            
            // Toggle current nested submenu
            if (isExpanded) {
                element.classList.remove('expanded');
                nestedSubmenu.classList.remove('show');
            } else {
                element.classList.add('expanded');
                nestedSubmenu.classList.add('show');
            }
        }
        
        // Set active menu item based on current URL
        document.addEventListener('DOMContentLoaded', function() {
            const currentPath = window.location.pathname;
            const menuItems = document.querySelectorAll('.submenu-item, .nested-submenu-item');
            
            menuItems.forEach(item => {
                if (item.getAttribute('href') === currentPath) {
                    item.classList.add('active');
                    
                    // Expand parent menu
                    const parentLi = item.closest('li');
                    if (parentLi) {
                        const parentMenu = parentLi.parentElement.previousElementSibling;
                        if (parentMenu && parentMenu.classList.contains('menu-item')) {
                            parentMenu.click();
                        }
                        
                        // If nested, also expand nested parent
                        const nestedParent = item.closest('.nested-submenu')?.previousElementSibling;
                        if (nestedParent && nestedParent.classList.contains('submenu-parent')) {
                            nestedParent.click();
                        }
                    }
                }
            });
        });
    </script>
</body>
</html>
