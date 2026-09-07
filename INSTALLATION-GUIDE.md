# Installation Guide - UniSIRAJ Automated Attendance System

**Version**: 1.0  
**Last Updated**: August 7, 2026  
**Target Audience**: System Administrators, IT Staff  

---

## 📋 Table of Contents

1. [System Requirements](#system-requirements)
2. [Pre-Installation Checklist](#pre-installation-checklist)
3. [Installation Steps](#installation-steps)
4. [Database Setup](#database-setup)
5. [Configuration](#configuration)
6. [Testing Installation](#testing-installation)
7. [Troubleshooting](#troubleshooting)
8. [Post-Installation](#post-installation)

---

## 🖥️ System Requirements

### Server Requirements

**Minimum Requirements:**
- **Operating System**: Windows 10/11, Linux (Ubuntu 20.04+), or macOS 10.15+
- **Web Server**: Apache 2.4+ or Nginx 1.18+ (or PHP built-in server for development)
- **PHP**: Version 8.0 or higher
- **Database**: MySQL 8.0+ or MariaDB 10.5+
- **RAM**: 2 GB minimum (4 GB recommended)
- **Storage**: 500 MB minimum (1 GB recommended)
- **Internet**: Required for initial setup and CDN resources

**Recommended Requirements:**
- **PHP**: Version 8.2
- **MySQL**: Version 8.0+
- **RAM**: 8 GB
- **Storage**: 5 GB (allows for growth)
- **SSL Certificate**: For HTTPS (required for production)

### PHP Extensions Required

```
php-pdo
php-pdo_mysql
php-gd (for QR code generation)
php-mbstring
php-openssl
php-json
php-session
```

### Browser Requirements (Client Side)

**Supported Browsers:**
- Google Chrome 90+ (Recommended)
- Mozilla Firefox 88+
- Microsoft Edge 90+
- Safari 14+ (macOS/iOS)

**Mobile Support:**
- Android 8.0+ with Chrome
- iOS 13+ with Safari

**Camera Access Required:**
- For QR code scanning feature

---

## ✅ Pre-Installation Checklist

Before beginning installation, ensure you have:

- [ ] Server access (SSH or physical access)
- [ ] Root/Administrator privileges
- [ ] Database credentials
- [ ] Domain name or IP address
- [ ] SSL certificate (for production)
- [ ] Backup of any existing data
- [ ] Installation files downloaded
- [ ] 30-60 minutes of time

---

## 📦 Installation Steps

### Step 1: Prepare the Server

#### On Windows:

**Option A: Using XAMPP (Recommended for Windows)**

1. Download and install XAMPP from https://www.apachefriends.org/
2. Install XAMPP to `C:\xampp`
3. Start Apache and MySQL from XAMPP Control Panel

**Option B: Using Laragon**

1. Download Laragon from https://laragon.org/
2. Install Laragon
3. Start services (Apache, MySQL)

#### On Linux (Ubuntu/Debian):

```bash
# Update package list
sudo apt update

# Install Apache
sudo apt install apache2 -y

# Install PHP and required extensions
sudo apt install php8.2 php8.2-mysql php8.2-gd php8.2-mbstring php8.2-xml -y

# Install MySQL
sudo apt install mysql-server -y

# Enable Apache modules
sudo a2enmod rewrite
sudo systemctl restart apache2
```

#### On macOS:

```bash
# Install Homebrew (if not installed)
/bin/bash -c "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/HEAD/install.sh)"

# Install PHP
brew install php@8.2

# Install MySQL
brew install mysql

# Start services
brew services start mysql
brew services start php@8.2
```

---

### Step 2: Download and Extract Files

**Option A: From Git Repository**

```bash
# Clone repository
git clone https://github.com/yourusername/attendance-system.git

# Navigate to directory
cd attendance-system
```

**Option B: From ZIP Archive**

1. Download `attendance-system.zip`
2. Extract to web server directory:
   - **Windows (XAMPP)**: `C:\xampp\htdocs\attendance-system`
   - **Linux**: `/var/www/html/attendance-system`
   - **macOS**: `/Applications/XAMPP/htdocs/attendance-system`

---

### Step 3: Set File Permissions

#### On Linux/macOS:

```bash
# Navigate to project directory
cd /var/www/html/attendance-system

# Set ownership
sudo chown -R www-data:www-data .

# Set directory permissions
sudo find . -type d -exec chmod 755 {} \;

# Set file permissions
sudo find . -type f -exec chmod 644 {} \;

# Make specific directories writable
sudo chmod -R 775 public/assets/qr-codes
sudo chmod -R 775 storage
```

#### On Windows:

No special permissions needed for development. For production, ensure IIS has write access to `public/assets/qr-codes` folder.

---

## 🗄️ Database Setup

### Step 1: Create Database

#### Using MySQL Command Line:

```bash
# Login to MySQL
mysql -u root -p

# Create database
CREATE DATABASE attendance_system CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

# Create database user (recommended for production)
CREATE USER 'attendance_user'@'localhost' IDENTIFIED BY 'your_secure_password';

# Grant privileges
GRANT ALL PRIVILEGES ON attendance_system.* TO 'attendance_user'@'localhost';

# Flush privileges
FLUSH PRIVILEGES;

# Exit MySQL
EXIT;
```

#### Using phpMyAdmin:

1. Open phpMyAdmin (usually at `http://localhost/phpmyadmin`)
2. Click "New" in left sidebar
3. Enter database name: `attendance_system`
4. Select collation: `utf8mb4_unicode_ci`
5. Click "Create"

---

### Step 2: Import Database Schema

#### Method 1: Using MySQL Command Line

```bash
# Navigate to project directory
cd /path/to/attendance-system

# Import schema
mysql -u root -p attendance_system < database/schema.sql

# Verify import
mysql -u root -p attendance_system -e "SHOW TABLES;"
```

#### Method 2: Using phpMyAdmin

1. Open phpMyAdmin
2. Select `attendance_system` database
3. Click "Import" tab
4. Choose file: `database/schema.sql`
5. Click "Go"
6. Wait for import to complete

---

### Step 3: Verify Database Tables

After import, you should have these tables:

```
- roles
- users
- students
- lecturers
- courses
- enrollments
- attendance_sessions
- attendance_records
```

**Verify with SQL:**

```sql
SHOW TABLES;

-- Check sample data
SELECT * FROM roles;
```

Expected roles:
- admin
- lecturer
- student

---

## ⚙️ Configuration

### Step 1: Database Configuration

Edit `config/database.php`:

```php
<?php
return [
    'host' => 'localhost',
    'database' => 'attendance_system',
    'username' => 'attendance_user',  // Change this
    'password' => 'your_secure_password',  // Change this
    'charset' => 'utf8mb4',
    'port' => 3306
];
```

**Security Note**: Use strong, unique passwords for production!

---

### Step 2: Application Configuration

Edit `config/config.php`:

**For Development (Localhost):**

```php
<?php
define('BASE_URL', 'http://localhost:8000');
define('SITE_NAME', 'UniSIRAJ Attendance System');
define('DEBUG_MODE', true);  // Set to false in production
```

**For Production:**

```php
<?php
define('BASE_URL', 'https://yourdomain.com');
define('SITE_NAME', 'UniSIRAJ Attendance System');
define('DEBUG_MODE', false);  // IMPORTANT: Set to false
```

---

### Step 3: Create QR Code Directory

```bash
# Linux/macOS
mkdir -p public/assets/qr-codes
chmod 775 public/assets/qr-codes

# Windows (Command Prompt)
mkdir public\assets\qr-codes
```

---

### Step 4: Create Default Admin User

Run the setup script or manually insert:

```sql
-- Insert admin user (password: Admin@123)
INSERT INTO users (role_id, email, password, is_active) 
VALUES (
    (SELECT id FROM roles WHERE role_name = 'admin'),
    'admin@unisiraj.edu.my',
    '$2y$12$LQv3c1ydemgSu5Sr.F5kO.N4oc1FcJKZIvU.RwpY2l/ZJL0sVl9Oi',
    1
);
```

**Default Login Credentials:**
- Email: `admin@unisiraj.edu.my`
- Password: `Admin@123`

⚠️ **IMPORTANT**: Change this password immediately after first login!

---

## 🧪 Testing Installation

### Step 1: Start Web Server

#### Using PHP Built-in Server (Development):

```bash
# Navigate to project directory
cd /path/to/attendance-system

# Start server
php -S localhost:8000 -t public
```

Server will be available at: `http://localhost:8000`

#### Using XAMPP:

1. Ensure Apache and MySQL are running
2. Access via: `http://localhost/attendance-system`

#### Using Apache (Production):

Configure virtual host (see Advanced Configuration section)

---

### Step 2: Access Application

1. Open browser
2. Navigate to: `http://localhost:8000` (or configured URL)
3. You should see the login page

**Expected Result:**
- Login page displays correctly
- No error messages
- Bootstrap styling loads
- No console errors

---

### Step 3: Test Login

1. Enter default credentials:
   - Email: `admin@unisiraj.edu.my`
   - Password: `Admin@123`
2. Click "Login"
3. Should redirect to admin dashboard

**Expected Result:**
- Successful login
- Redirect to `/admin/dashboard`
- Dashboard displays statistics
- No errors in logs

---

### Step 4: Verify Database Connection

**Check Error Logs:**

```bash
# View PHP error log
# Location varies by system:
# - XAMPP: C:\xampp\apache\logs\error.log
# - Linux: /var/log/apache2/error.log
# - macOS: /usr/local/var/log/httpd/error_log

# Should see:
# "Database connection established successfully"
```

---

### Step 5: Test Core Features

**Quick Feature Test Checklist:**

- [ ] Login/Logout works
- [ ] Admin dashboard displays
- [ ] Can create new student
- [ ] Can create new lecturer
- [ ] Can create new course
- [ ] Can create attendance session
- [ ] QR code generates
- [ ] Student can scan QR (test with phone)
- [ ] Reports generate
- [ ] Analytics display

---

## 🔧 Troubleshooting

### Issue 1: "Database Connection Failed"

**Cause**: Incorrect database credentials or MySQL not running

**Solutions:**
1. Verify MySQL is running:
   ```bash
   # Linux
   sudo systemctl status mysql
   
   # Windows (XAMPP)
   # Check XAMPP Control Panel - MySQL should be green
   ```

2. Check credentials in `config/database.php`
3. Test connection manually:
   ```bash
   mysql -u attendance_user -p attendance_system
   ```

---

### Issue 2: "404 Not Found" or "Page Not Found"

**Cause**: URL rewriting not enabled or incorrect BASE_URL

**Solutions:**

1. **Check .htaccess** (Apache):
   Ensure `.htaccess` exists in `public/` folder

2. **Enable mod_rewrite** (Linux):
   ```bash
   sudo a2enmod rewrite
   sudo systemctl restart apache2
   ```

3. **Verify BASE_URL** in `config/config.php`

---

### Issue 3: "Permission Denied" Writing QR Codes

**Cause**: Insufficient write permissions

**Solutions:**

**Linux/macOS:**
```bash
sudo chmod -R 775 public/assets/qr-codes
sudo chown -R www-data:www-data public/assets/qr-codes
```

**Windows:**
- Right-click `qr-codes` folder → Properties → Security
- Give "Everyone" write permissions (development only)

---

### Issue 4: Blank White Page

**Cause**: PHP errors with error reporting disabled

**Solutions:**

1. Enable error display (development only):
   ```php
   // Add to public/index.php (top)
   error_reporting(E_ALL);
   ini_set('display_errors', 1);
   ```

2. Check PHP error logs

3. Verify all files uploaded correctly

---

### Issue 5: "Call to undefined function password_hash()"

**Cause**: PHP version too old

**Solutions:**
1. Check PHP version:
   ```bash
   php -v
   ```
2. Upgrade to PHP 8.0+
3. Restart web server

---

### Issue 6: QR Code Not Scanning

**Cause**: Camera permissions, HTTPS required, or QR quality

**Solutions:**
1. Use HTTPS for production (required for camera access)
2. Allow camera permissions in browser
3. Ensure QR code image is clear
4. Test with multiple QR code scanner apps

---

### Issue 7: Session Issues / Can't Stay Logged In

**Cause**: Session configuration issues

**Solutions:**

1. Check `php.ini`:
   ```ini
   session.save_path = "/tmp"
   session.gc_maxlifetime = 1440
   ```

2. Ensure session directory writable:
   ```bash
   # Linux
   sudo chmod 1733 /tmp
   ```

3. Clear browser cookies and cache

---

## 📝 Post-Installation

### Security Checklist

- [ ] Change default admin password
- [ ] Set DEBUG_MODE to false in production
- [ ] Use strong database passwords
- [ ] Enable HTTPS (SSL certificate)
- [ ] Restrict database user privileges
- [ ] Set proper file permissions
- [ ] Disable directory listing
- [ ] Keep PHP and MySQL updated
- [ ] Configure firewall rules
- [ ] Enable backup system

---

### Performance Optimization

**PHP Configuration (`php.ini`):**

```ini
# Increase memory limit
memory_limit = 256M

# Increase upload limit (if needed)
upload_max_filesize = 10M
post_max_size = 10M

# Enable OPcache (production)
opcache.enable=1
opcache.memory_consumption=128
```

**MySQL Optimization:**

```sql
-- Add indexes for performance
ALTER TABLE attendance_records ADD INDEX idx_session_student (session_id, student_id);
ALTER TABLE enrollments ADD INDEX idx_course_student (course_id, student_id);
```

---

### Backup Configuration

**Database Backup Script:**

```bash
#!/bin/bash
# backup-database.sh

TIMESTAMP=$(date +%Y%m%d_%H%M%S)
BACKUP_DIR="/path/to/backups"
DB_NAME="attendance_system"
DB_USER="attendance_user"
DB_PASS="your_password"

mysqldump -u $DB_USER -p$DB_PASS $DB_NAME > $BACKUP_DIR/backup_$TIMESTAMP.sql
gzip $BACKUP_DIR/backup_$TIMESTAMP.sql

# Keep only last 7 days
find $BACKUP_DIR -name "backup_*.sql.gz" -mtime +7 -delete
```

**Schedule with cron (Linux):**

```bash
# Run daily at 2 AM
0 2 * * * /path/to/backup-database.sh
```

---

### Monitoring

**Setup Log Monitoring:**

1. **Error Logs**: Check regularly for PHP errors
2. **Access Logs**: Monitor suspicious activity
3. **Database Logs**: Watch for slow queries
4. **Disk Space**: Ensure adequate storage

**Log Locations:**
- PHP errors: Check `php.ini` for `error_log` location
- Apache: `/var/log/apache2/`
- MySQL: `/var/log/mysql/`

---

### User Setup

**Create Initial Users:**

1. Login as admin
2. Navigate to Admin Dashboard
3. Create lecturer accounts
4. Create student accounts
5. Enroll students in courses
6. Assign courses to lecturers

**User Training:**
- Provide user guides (see USER-MANUAL.md)
- Conduct training sessions
- Create quick reference cards
- Setup support channel

---

## 🚀 Production Deployment

### Apache Virtual Host Configuration

**Create virtual host file:**

```apache
# /etc/apache2/sites-available/attendance.conf

<VirtualHost *:80>
    ServerName yourdomain.com
    ServerAlias www.yourdomain.com
    DocumentRoot /var/www/html/attendance-system/public
    
    <Directory /var/www/html/attendance-system/public>
        Options -Indexes +FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>
    
    ErrorLog ${APACHE_LOG_DIR}/attendance_error.log
    CustomLog ${APACHE_LOG_DIR}/attendance_access.log combined
</VirtualHost>
```

**Enable site:**

```bash
sudo a2ensite attendance
sudo systemctl reload apache2
```

### SSL Configuration (HTTPS)

**Using Let's Encrypt:**

```bash
# Install Certbot
sudo apt install certbot python3-certbot-apache

# Obtain certificate
sudo certbot --apache -d yourdomain.com -d www.yourdomain.com

# Auto-renewal
sudo certbot renew --dry-run
```

---

## 📞 Support

### Getting Help

**Documentation:**
- Installation Guide (this file)
- User Manual: `USER-MANUAL.md`
- Admin Guide: `ADMIN-GUIDE.md`
- API Documentation: `API-DOCUMENTATION.md`

**Common Issues:**
- Check Troubleshooting section above
- Review error logs
- Verify configuration files

**Contact:**
- Project Repository: [GitHub URL]
- Email: support@unisiraj.edu.my
- Documentation: [Wiki URL]

---

## ✅ Installation Complete!

If all tests passed, your installation is complete!

**Next Steps:**
1. Change default admin password
2. Create user accounts
3. Configure courses
4. Test end-to-end workflow
5. Train users
6. Go live!

---

**UniSIRAJ Automated Attendance System**  
**Installation Guide v1.0**  
**Last Updated**: August 7, 2026

**Thank you for installing the UniSIRAJ Automated Attendance System!** 🎉
