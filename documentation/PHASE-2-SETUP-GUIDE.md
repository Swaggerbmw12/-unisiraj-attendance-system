# PHASE 2: PROJECT SETUP - COMPLETE GUIDE
# UniSIRAJ Automated Attendance System

**Status**: ✅ **COMPLETED**  
**Date**: Current  
**Student**: Ahmed Mohammed Alsadig Mohammed  
**Supervisor**: Dr. Fatimah Noni Muhamad

---

## ✅ PHASE 2 COMPLETED TASKS

### 1. Project Structure Created
```
Attendance System/
├── .gitignore
├── README.md
│
├── app/
│   ├── controllers/
│   │   ├── BaseController.php        ✅ Base controller with common methods
│   │   ├── HomeController.php        ✅ Homepage controller
│   │   └── ErrorController.php       ✅ Error pages controller
│   ├── models/
│   │   └── BaseModel.php             ✅ Base model with CRUD operations
│   └── views/
│       ├── layouts/
│       │   ├── header.php            ✅ HTML header & navbar
│       │   ├── footer.php            ✅ Footer with scripts
│       │   ├── navbar.php            ✅ Dynamic navigation menu
│       │   └── main.php              ✅ Main layout template
│       └── errors/
│           ├── 403.php               ✅ Forbidden page
│           ├── 404.php               ✅ Not found page
│           └── 500.php               ✅ Server error page
│
├── config/
│   ├── config.php                    ✅ Application configuration
│   └── database.php                  ✅ Database connection (PDO, Singleton)
│
├── public/
│   ├── index.php                     ✅ Front controller (entry point)
│   ├── .htaccess                     ✅ Apache rewrite rules
│   └── assets/
│       ├── css/
│       │   └── style.css             ✅ Custom styles
│       ├── js/
│       │   └── main.js               ✅ JavaScript utilities
│       └── images/
│
├── routes/
│   └── web.php                       ✅ All application routes
│
├── storage/
│   ├── logs/                         ✅ Error & application logs
│   ├── uploads/                      ✅ File uploads
│   └── qr-codes/                     ✅ Generated QR codes
│
├── database/
│   └── schema.sql                    ✅ Database schema (from Phase 1)
│
└── documentation/
    ├── PHASE-1-*.md                  ✅ Phase 1 documentation
    └── PHASE-2-SETUP-GUIDE.md        ✅ This file
```

---

## 🗂️ FILES CREATED IN PHASE 2

### Configuration Files (2)
1. ✅ `config/config.php` - Application settings, constants, helper functions
2. ✅ `config/database.php` - Database connection with PDO singleton pattern

### Controllers (3)
1. ✅ `app/controllers/BaseController.php` - Parent controller with common methods
2. ✅ `app/controllers/HomeController.php` - Homepage/landing page
3. ✅ `app/controllers/ErrorController.php` - Error page handling (403, 404, 500)

### Models (1)
1. ✅ `app/models/BaseModel.php` - Parent model with CRUD operations

### Views (7)
1. ✅ `app/views/layouts/header.php` - HTML header, meta tags, CSS includes
2. ✅ `app/views/layouts/footer.php` - Footer, JavaScript includes, flash messages
3. ✅ `app/views/layouts/navbar.php` - Dynamic navigation based on user role
4. ✅ `app/views/layouts/main.php` - Main layout wrapper
5. ✅ `app/views/errors/403.php` - Access forbidden page
6. ✅ `app/views/errors/404.php` - Page not found
7. ✅ `app/views/errors/500.php` - Server error page

### Routing (1)
1. ✅ `routes/web.php` - All application routes defined

### Front Controller (1)
1. ✅ `public/index.php` - Entry point, router implementation

### Assets (3)
1. ✅ `public/.htaccess` - Apache URL rewriting, security headers
2. ✅ `public/assets/css/style.css` - Custom styles (500+ lines)
3. ✅ `public/assets/js/main.js` - JavaScript utilities and AJAX

### Other (2)
1. ✅ `.gitignore` - Git ignore patterns
2. ✅ Storage directories with `.gitkeep` files

---

## 🎯 KEY FEATURES IMPLEMENTED

### 1. MVC Architecture ✅
- **Model**: BaseModel with full CRUD operations
- **View**: Layout system with reusable templates
- **Controller**: BaseController with authentication and validation
- **Router**: Clean URL routing system

### 2. Database Connection ✅
- **Singleton Pattern**: Single database connection instance
- **PDO with Prepared Statements**: SQL injection prevention
- **Error Handling**: Proper exception handling
- **Connection Testing**: Built-in connection test method
- **Transaction Support**: Begin, commit, rollback methods

### 3. Routing System ✅
- **Front Controller**: Single entry point (index.php)
- **Route Definitions**: Centralized in routes/web.php
- **Clean URLs**: Apache mod_rewrite configuration
- **Dynamic Loading**: Controllers loaded automatically
- **Error Handling**: 404 handling built-in

### 4. Security Features ✅
- **Session Management**: Secure session configuration
- **CSRF Protection**: Token generation and validation
- **Input Validation**: Built-in validation helper
- **XSS Prevention**: Output escaping helper (e() function)
- **Password Hashing**: Bcrypt implementation ready
- **Role-Based Access**: requireAuth() and requireRole() methods
- **Security Headers**: X-Content-Type, X-Frame-Options, etc.

### 5. Helper Functions ✅
- `url()` - Generate full URLs
- `asset()` - Generate asset URLs
- `redirect()` - Redirect to URL
- `flash()` - Flash message management
- `e()` - HTML escaping
- `isAuthenticated()` - Check if user logged in
- `currentUserId()` - Get current user ID
- `currentUserRole()` - Get current user role
- `hasRole()` - Check if user has specific role

### 6. Layout System ✅
- **Reusable Header**: Consistent HTML structure
- **Dynamic Navbar**: Changes based on user role
- **Flash Messages**: Success, error, warning, info alerts
- **Responsive Design**: Bootstrap 5 integration
- **Footer**: Project information and credits

### 7. BaseModel Features ✅
- `all()` - Get all records
- `find()` - Find by ID
- `findBy()` - Find by conditions
- `create()` - Insert new record
- `update()` - Update record
- `delete()` - Delete record
- `count()` - Count records
- `exists()` - Check if record exists
- `paginate()` - Pagination support
- `query()` - Custom SQL queries

### 8. BaseController Features ✅
- Session management
- View loading
- Layout loading
- JSON responses
- Authentication checking
- Role-based authorization
- CSRF protection
- Input validation
- Logout functionality

---

## 🔧 CONFIGURATION SETTINGS

### Application Settings
```php
APP_NAME: 'UniSIRAJ Attendance System'
APP_VERSION: '1.0.0'
APP_ENV: 'development'
BASE_URL: 'http://localhost:8000'
```

### Session Settings
```php
SESSION_LIFETIME: 1800 seconds (30 minutes)
SESSION_HTTPONLY: true
SESSION_SECURE: false (set true in production)
SESSION_SAMESITE: 'Strict'
```

### Security Settings
```php
PASSWORD_MIN_LENGTH: 8
BCRYPT_COST: 12
QR_DEFAULT_EXPIRY: 30 minutes
```

### Pagination
```php
ITEMS_PER_PAGE: 10
MAX_PAGINATION_LINKS: 5
```

---

## 🚀 HOW TO RUN THE PROJECT

### Option 1: PHP Built-in Server (Recommended for Development)

```bash
# Navigate to project root
cd "C:\Users\mohan\OneDrive\Desktop\Attendance System"

# Start PHP server
php -S localhost:8000 -t public
```

Access at: http://localhost:8000

### Option 2: Laragon

1. Install Laragon from https://laragon.org
2. Copy project to `C:\laragon\www\attendance`
3. Start Laragon
4. Access at: http://attendance.test

### Option 3: Apache/XAMPP

1. Copy project to `htdocs` folder
2. Update BASE_URL in `config/config.php`
3. Start Apache
4. Access at: http://localhost/attendance/public

---

## 💾 DATABASE SETUP

### Step 1: Create Database
```sql
CREATE DATABASE unisiraj_attendance 
CHARACTER SET utf8mb4 
COLLATE utf8mb4_unicode_ci;
```

### Step 2: Import Schema
```bash
# Method 1: MySQL Command Line
mysql -u root -p unisiraj_attendance < database/schema.sql

# Method 2: phpMyAdmin
1. Login to phpMyAdmin
2. Select 'unisiraj_attendance' database
3. Click 'Import' tab
4. Choose 'database/schema.sql' file
5. Click 'Go'
```

### Step 3: Verify Tables
```sql
USE unisiraj_attendance;
SHOW TABLES;

-- Should show 9 tables:
-- roles, users, students, lecturers, courses,
-- enrollments, attendance_sessions, 
-- attendance_records, audit_logs
```

### Step 4: Test with Sample Data
```sql
-- Check sample users
SELECT * FROM users;

-- Check roles
SELECT * FROM roles;

-- Check sample students
SELECT * FROM students;
```

---

## ✅ TESTING THE SETUP

### 1. Test Database Connection
```bash
php -r "
require 'config/config.php';
require 'config/database.php';
\$db = Database::getInstance();
echo \$db->testConnection() ? 'Connected!' : 'Failed!';
"
```

### 2. Test Web Server
- Start server: `php -S localhost:8000 -t public`
- Open browser: http://localhost:8000
- Should redirect to /login (not yet created)

### 3. Test Error Pages
- http://localhost:8000/404 - Should show 404 page
- http://localhost:8000/403 - Should show 403 page

### 4. Check File Permissions
```bash
# Make sure storage directories are writable
chmod 755 storage/logs
chmod 755 storage/uploads
chmod 755 storage/qr-codes
```

---

## 📊 ROUTING SYSTEM EXPLAINED

### How Routing Works
1. All requests go to `public/index.php` (via .htaccess)
2. Router class reads `routes/web.php`
3. Router matches URL to route definition
4. Router loads appropriate controller
5. Router calls specified method
6. Controller returns response

### Route Definition Format
```php
'/url' => [
    'controller' => 'ControllerName',
    'method' => 'methodName'
]
```

### Example Routes
```php
'/' => HomeController::index()
'/admin/students' => Admin\StudentController::index()
'/lecturer/sessions' => Lecturer\SessionController::index()
```

---

## 🔐 SECURITY IMPLEMENTATION

### Already Implemented
1. ✅ **Session Security**: Secure session configuration
2. ✅ **CSRF Tokens**: Generation and validation methods
3. ✅ **Input Validation**: Validation helper in BaseController
4. ✅ **XSS Prevention**: HTML escaping with e() function
5. ✅ **SQL Injection**: PDO prepared statements in BaseModel
6. ✅ **Security Headers**: Set in .htaccess
7. ✅ **Role-Based Access**: requireRole() method

### To Be Implemented (Next Phases)
- Password hashing on registration
- Authentication system
- Audit logging implementation
- File upload validation

---

## 🎨 FRONTEND FEATURES

### Bootstrap 5 Integration
- Responsive grid system
- Pre-built components
- Mobile-first design
- Icons (Bootstrap Icons)

### Custom CSS Features
- Custom color scheme
- Card styles with hover effects
- Dashboard stat cards
- Table styling
- Form styling
- Button styling
- Alert styling
- Responsive design

### JavaScript Features
- AJAX setup with loading spinner
- Alert system (success, error, warning, info)
- Form validation
- Table search and sort
- Copy to clipboard
- Session timeout warning
- Delete confirmation
- Date formatting utilities

---

## 📝 CODE QUALITY

### Standards Followed
- ✅ **MVC Pattern**: Clear separation of concerns
- ✅ **DRY Principle**: No code repetition
- ✅ **Single Responsibility**: Each class has one purpose
- ✅ **Clean Code**: Well-commented and documented
- ✅ **Security First**: Security measures built-in
- ✅ **Scalability**: Easy to extend and modify

### Documentation
- ✅ All files have header comments
- ✅ All functions have descriptions
- ✅ Complex logic explained inline
- ✅ Configuration well-commented

---

## 🎯 READY FOR PHASE 3

Phase 2 is now complete! The project structure is set up and ready for implementation.

### What We Have
- ✅ Complete MVC architecture
- ✅ Working routing system
- ✅ Database connection configured
- ✅ Security framework in place
- ✅ Layout system ready
- ✅ Helper functions available
- ✅ Asset pipeline configured

### What's Next (Phase 3: Authentication)
- Create User model
- Build login page
- Implement authentication logic
- Add session management
- Create role-based dashboards
- Add logout functionality

---

## 📞 TROUBLESHOOTING

### Issue: Database Connection Failed
**Solution**: 
1. Check MySQL is running
2. Verify credentials in `config/database.php`
3. Ensure database exists
4. Check PHP PDO extension is enabled

### Issue: 404 on All Pages
**Solution**:
1. Check Apache mod_rewrite is enabled
2. Verify .htaccess file exists in public/
3. Make sure server is started from project root
4. Check BASE_URL in config.php

### Issue: CSS/JS Not Loading
**Solution**:
1. Verify BASE_URL in config.php
2. Check files exist in public/assets/
3. Clear browser cache
4. Check browser console for errors

### Issue: Permission Denied on Storage
**Solution**:
```bash
chmod 755 storage/logs
chmod 755 storage/uploads
chmod 755 storage/qr-codes
```

---

## ✨ HIGHLIGHTS

### Production-Quality Code
- Industry-standard architecture
- Security best practices
- Clean, maintainable code
- Comprehensive error handling

### Scalability
- Easy to add new features
- Modular design
- Reusable components
- Clear structure

### Documentation
- Every file documented
- Clear comments
- Setup guide provided
- Troubleshooting included

---

**PHASE 2 STATUS**: ✅ **COMPLETED AND TESTED**

**Next Action**: Review and approve to proceed to Phase 3 (Authentication Module)

---

**Ahmed Mohammed Alsadig Mohammed**  
**Supervisor: Dr. Fatimah Noni Muhamad**  
**UniSIRAJ - Final Year Project**  
**Date**: <?= date('F d, Y') ?>
