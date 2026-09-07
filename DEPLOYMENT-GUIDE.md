# Deployment Guide - UniSIRAJ Automated Attendance System

**Version**: 1.0  
**Last Updated**: August 7, 2026  
**Target Audience**: System Administrators, DevOps Engineers

---

## 📋 Table of Contents

1. [Pre-Deployment Checklist](#pre-deployment-checklist)
2. [Production Environment Setup](#production-environment-setup)
3. [Security Hardening](#security-hardening)
4. [Deployment Steps](#deployment-steps)
5. [SSL/HTTPS Configuration](#sslhttps-configuration)
6. [Performance Optimization](#performance-optimization)
7. [Backup Strategy](#backup-strategy)
8. [Monitoring & Maintenance](#monitoring--maintenance)
9. [Rollback Procedures](#rollback-procedures)
10. [Post-Deployment Verification](#post-deployment-verification)

---

## ✅ Pre-Deployment Checklist

### Infrastructure Requirements

- [ ] Production server provisioned
- [ ] Database server setup (or managed database service)
- [ ] Domain name registered and configured
- [ ] SSL certificate obtained
- [ ] Backup storage configured
- [ ] Monitoring tools setup
- [ ] Email service configured (for notifications)
- [ ] Firewall rules defined

### Application Requirements

- [ ] All tests passed (Phase 10)
- [ ] Security audit completed
- [ ] Performance benchmarks met
- [ ] Documentation complete
- [ ] User training completed
- [ ] Support procedures defined

### Data Requirements

- [ ] Database backup created
- [ ] Migration plan documented
- [ ] Data validation procedures defined
- [ ] Rollback plan prepared

---

## 🖥️ Production Environment Setup

### Server Specifications (Recommended)

**Web Server:**
- OS: Ubuntu 22.04 LTS or Windows Server 2022
- CPU: 4 cores minimum
- RAM: 8 GB minimum (16 GB recommended)
- Storage: 50 GB SSD minimum
- Network: 100 Mbps minimum

**Database Server:**
- OS: Ubuntu 22.04 LTS or Windows Server 2022
- CPU: 4 cores minimum
- RAM: 16 GB minimum (32 GB recommended)
- Storage: 100 GB SSD minimum (RAID 10 recommended)
- Network: 1 Gbps recommended

**Alternative: Managed Services**
- AWS RDS for MySQL
- Azure Database for MySQL
- Google Cloud SQL

---

### Software Installation (Ubuntu)

```bash
# Update system
sudo apt update && sudo apt upgrade -y

# Install Apache
sudo apt install apache2 -y

# Install PHP 8.2 and extensions
sudo apt install software-properties-common -y
sudo add-apt-repository ppa:ondrej/php -y
sudo apt update
sudo apt install php8.2 php8.2-fpm php8.2-mysql php8.2-gd php8.2-mbstring php8.2-xml php8.2-curl php8.2-zip -y

# Install MySQL
sudo apt install mysql-server -y

# Secure MySQL installation
sudo mysql_secure_installation

# Enable required Apache modules
sudo a2enmod rewrite ssl headers proxy_fcgi setenvif
sudo a2enconf php8.2-fpm
sudo systemctl restart apache2
```

---

## 🔒 Security Hardening

### 1. Firewall Configuration

```bash
# Ubuntu UFW
sudo ufw default deny incoming
sudo ufw default allow outgoing
sudo ufw allow ssh
sudo ufw allow http
sudo ufw allow https
sudo ufw enable
```

### 2. MySQL Security

```sql
-- Create dedicated database user
CREATE DATABASE attendance_system CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'attendance_prod'@'localhost' IDENTIFIED BY 'STRONG_RANDOM_PASSWORD';

-- Grant minimal required privileges
GRANT SELECT, INSERT, UPDATE, DELETE, CREATE, INDEX, ALTER ON attendance_system.* TO 'attendance_prod'@'localhost';
FLUSH PRIVILEGES;

-- Disable remote root login
DELETE FROM mysql.user WHERE User='root' AND Host NOT IN ('localhost', '127.0.0.1', '::1');
FLUSH PRIVILEGES;
```

### 3. File Permissions

```bash
# Set proper ownership
sudo chown -R www-data:www-data /var/www/html/attendance-system

# Set directory permissions
sudo find /var/www/html/attendance-system -type d -exec chmod 755 {} \;

# Set file permissions
sudo find /var/www/html/attendance-system -type f -exec chmod 644 {} \;

# Set writable directories
sudo chmod -R 775 /var/www/html/attendance-system/public/assets/qr-codes
sudo chmod -R 775 /var/www/html/attendance-system/storage

# Protect sensitive files
sudo chmod 600 /var/www/html/attendance-system/config/database.php
sudo chmod 600 /var/www/html/attendance-system/config/config.php
```

### 4. Disable Directory Listing

Add to Apache configuration:

```apache
<Directory /var/www/html/attendance-system>
    Options -Indexes +FollowSymLinks
    AllowOverride All
    Require all granted
</Directory>
```

### 5. PHP Security Settings

Edit `/etc/php/8.2/apache2/php.ini`:

```ini
# Disable dangerous functions
disable_functions = exec,passthru,shell_exec,system,proc_open,popen,curl_exec,curl_multi_exec,parse_ini_file,show_source

# Hide PHP version
expose_php = Off

# Set error logging (don't display errors)
display_errors = Off
log_errors = On
error_log = /var/log/php_errors.log

# Set session security
session.cookie_httponly = On
session.cookie_secure = On
session.use_strict_mode = On

# File upload limits
upload_max_filesize = 5M
post_max_size = 8M
```

### 6. Application Security Configuration

Edit `config/config.php`:

```php
<?php
// PRODUCTION CONFIGURATION

// Base URL (HTTPS only)
define('BASE_URL', 'https://attendance.unisiraj.edu.my');

// IMPORTANT: Disable debug mode
define('DEBUG_MODE', false);

// Site configuration
define('SITE_NAME', 'UniSIRAJ Attendance System');

// Security settings
define('SESSION_LIFETIME', 3600); // 1 hour
define('MAX_LOGIN_ATTEMPTS', 5);
define('LOGIN_TIMEOUT', 900); // 15 minutes

// Force HTTPS
if (!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] !== 'on') {
    if (php_sapi_name() !== 'cli') {
        header('Location: https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
        exit;
    }
}
```

---

## 🚀 Deployment Steps

### Step 1: Prepare Deployment Package

```bash
# On development machine
cd /path/to/attendance-system

# Create deployment package
tar -czf attendance-system-v1.0.tar.gz \
    --exclude='.git' \
    --exclude='node_modules' \
    --exclude='*.log' \
    --exclude='.env.local' \
    .

# Transfer to server
scp attendance-system-v1.0.tar.gz user@server:/tmp/
```

### Step 2: Deploy Application

```bash
# On production server
cd /var/www/html

# Extract files
sudo tar -xzf /tmp/attendance-system-v1.0.tar.gz -C /var/www/html/
sudo mv attendance-system attendance-system-v1.0

# Create symlink for easy updates
sudo ln -s /var/www/html/attendance-system-v1.0 /var/www/html/attendance-system

# Set permissions
sudo chown -R www-data:www-data /var/www/html/attendance-system-v1.0
```

### Step 3: Configure Database

```bash
# Import database schema
mysql -u attendance_prod -p attendance_system < /var/www/html/attendance-system/database/schema.sql

# Verify tables created
mysql -u attendance_prod -p attendance_system -e "SHOW TABLES;"
```

### Step 4: Configure Application

```bash
# Copy production config (prepared separately)
sudo cp /secure/path/database.php /var/www/html/attendance-system/config/
sudo cp /secure/path/config.php /var/www/html/attendance-system/config/

# Set proper permissions
sudo chmod 600 /var/www/html/attendance-system/config/database.php
sudo chmod 600 /var/www/html/attendance-system/config/config.php
```

### Step 5: Configure Apache Virtual Host

Create `/etc/apache2/sites-available/attendance.conf`:

```apache
<VirtualHost *:80>
    ServerName attendance.unisiraj.edu.my
    ServerAlias www.attendance.unisiraj.edu.my
    
    # Redirect all HTTP to HTTPS
    RewriteEngine On
    RewriteCond %{HTTPS} off
    RewriteRule ^(.*)$ https://%{HTTP_HOST}$1 [R=301,L]
</VirtualHost>

<VirtualHost *:443>
    ServerName attendance.unisiraj.edu.my
    ServerAlias www.attendance.unisiraj.edu.my
    
    DocumentRoot /var/www/html/attendance-system/public
    
    # SSL Configuration
    SSLEngine on
    SSLCertificateFile /etc/ssl/certs/attendance.crt
    SSLCertificateKeyFile /etc/ssl/private/attendance.key
    SSLCertificateChainFile /etc/ssl/certs/attendance-chain.crt
    
    # Security Headers
    Header always set X-Frame-Options "SAMEORIGIN"
    Header always set X-Content-Type-Options "nosniff"
    Header always set X-XSS-Protection "1; mode=block"
    Header always set Referrer-Policy "strict-origin-when-cross-origin"
    Header always set Permissions-Policy "camera=(self), microphone=(), geolocation=()"
    
    # Directory Configuration
    <Directory /var/www/html/attendance-system/public>
        Options -Indexes +FollowSymLinks -MultiViews
        AllowOverride All
        Require all granted
        
        # PHP Configuration
        <FilesMatch \.php$>
            SetHandler "proxy:unix:/var/run/php/php8.2-fpm.sock|fcgi://localhost"
        </FilesMatch>
    </Directory>
    
    # Logging
    ErrorLog ${APACHE_LOG_DIR}/attendance_error.log
    CustomLog ${APACHE_LOG_DIR}/attendance_access.log combined
    
    # Compression
    <IfModule mod_deflate.c>
        AddOutputFilterByType DEFLATE text/html text/plain text/xml text/css text/javascript application/javascript
    </IfModule>
    
    # Caching
    <IfModule mod_expires.c>
        ExpiresActive On
        ExpiresByType image/jpg "access plus 1 year"
        ExpiresByType image/jpeg "access plus 1 year"
        ExpiresByType image/gif "access plus 1 year"
        ExpiresByType image/png "access plus 1 year"
        ExpiresByType text/css "access plus 1 month"
        ExpiresByType application/javascript "access plus 1 month"
    </IfModule>
</VirtualHost>
```

Enable site:

```bash
sudo a2ensite attendance
sudo a2dissite 000-default
sudo systemctl reload apache2
```

---

## 🔐 SSL/HTTPS Configuration

### Using Let's Encrypt (Free)

```bash
# Install Certbot
sudo apt install certbot python3-certbot-apache -y

# Obtain certificate
sudo certbot --apache -d attendance.unisiraj.edu.my -d www.attendance.unisiraj.edu.my

# Verify auto-renewal
sudo certbot renew --dry-run

# Setup auto-renewal cron
sudo crontab -e
# Add: 0 3 * * * certbot renew --quiet
```

### Using Commercial SSL Certificate

1. Purchase SSL certificate from provider
2. Generate CSR:
   ```bash
   sudo openssl req -new -newkey rsa:2048 -nodes \
     -keyout /etc/ssl/private/attendance.key \
     -out /etc/ssl/csr/attendance.csr
   ```
3. Submit CSR to certificate authority
4. Receive and install certificate:
   ```bash
   sudo cp attendance.crt /etc/ssl/certs/
   sudo cp attendance-chain.crt /etc/ssl/certs/
   ```
5. Update Apache configuration (see above)

---

## ⚡ Performance Optimization

### 1. PHP OPcache Configuration

Edit `/etc/php/8.2/apache2/php.ini`:

```ini
opcache.enable=1
opcache.memory_consumption=256
opcache.interned_strings_buffer=16
opcache.max_accelerated_files=10000
opcache.revalidate_freq=2
opcache.fast_shutdown=1
opcache.enable_cli=1
```

### 2. MySQL Optimization

Edit `/etc/mysql/mysql.conf.d/mysqld.cnf`:

```ini
[mysqld]
# Connection settings
max_connections = 200
connect_timeout = 10
wait_timeout = 600

# Buffer settings
innodb_buffer_pool_size = 4G
innodb_log_file_size = 512M
innodb_flush_log_at_trx_commit = 2

# Query cache (MySQL 5.7 only)
query_cache_type = 1
query_cache_size = 256M
query_cache_limit = 2M

# Slow query log
slow_query_log = 1
slow_query_log_file = /var/log/mysql/slow-query.log
long_query_time = 2
```

### 3. Database Indexing

```sql
-- Add performance indexes
USE attendance_system;

ALTER TABLE attendance_records 
ADD INDEX idx_session_student (session_id, student_id);

ALTER TABLE attendance_records 
ADD INDEX idx_student_session (student_id, session_id);

ALTER TABLE enrollments 
ADD INDEX idx_course_student (course_id, student_id);

ALTER TABLE attendance_sessions 
ADD INDEX idx_course_date (course_id, session_date);

ALTER TABLE attendance_sessions 
ADD INDEX idx_active (is_active);

-- Analyze tables
ANALYZE TABLE attendance_records;
ANALYZE TABLE attendance_sessions;
ANALYZE TABLE enrollments;
```

### 4. Apache Performance Tuning

Edit `/etc/apache2/mods-enabled/mpm_prefork.conf`:

```apache
<IfModule mpm_prefork_module>
    StartServers             5
    MinSpareServers          5
    MaxSpareServers          10
    MaxRequestWorkers        150
    MaxConnectionsPerChild   3000
</IfModule>
```

---

## 💾 Backup Strategy

### 1. Automated Database Backup

Create `/usr/local/bin/backup-attendance-db.sh`:

```bash
#!/bin/bash

# Configuration
TIMESTAMP=$(date +%Y%m%d_%H%M%S)
BACKUP_DIR="/var/backups/attendance"
DB_NAME="attendance_system"
DB_USER="attendance_prod"
DB_PASS="YOUR_PASSWORD"
RETENTION_DAYS=30

# Create backup directory
mkdir -p $BACKUP_DIR

# Backup database
mysqldump -u $DB_USER -p$DB_PASS \
  --single-transaction \
  --routines \
  --triggers \
  --databases $DB_NAME \
  | gzip > $BACKUP_DIR/db_backup_$TIMESTAMP.sql.gz

# Backup QR codes
tar -czf $BACKUP_DIR/qr_backup_$TIMESTAMP.tar.gz \
  /var/www/html/attendance-system/public/assets/qr-codes/

# Remove old backups
find $BACKUP_DIR -name "db_backup_*.sql.gz" -mtime +$RETENTION_DAYS -delete
find $BACKUP_DIR -name "qr_backup_*.tar.gz" -mtime +$RETENTION_DAYS -delete

# Log backup
echo "Backup completed: $TIMESTAMP" >> /var/log/attendance-backup.log
```

Make executable:

```bash
sudo chmod +x /usr/local/bin/backup-attendance-db.sh
```

Schedule with cron:

```bash
sudo crontab -e
# Add: 0 2 * * * /usr/local/bin/backup-attendance-db.sh
```

### 2. Off-site Backup

Sync backups to remote storage:

```bash
# Using rsync to remote server
rsync -avz /var/backups/attendance/ \
  user@backup-server:/backups/attendance/

# Or using AWS S3
aws s3 sync /var/backups/attendance/ \
  s3://your-bucket/attendance-backups/

# Or using Google Cloud Storage
gsutil -m rsync -r /var/backups/attendance/ \
  gs://your-bucket/attendance-backups/
```

---

## 📊 Monitoring & Maintenance

### 1. Log Monitoring

**Setup Log Rotation:**

Create `/etc/logrotate.d/attendance`:

```
/var/log/attendance*.log {
    daily
    rotate 30
    compress
    delaycompress
    notifempty
    create 0640 www-data adm
    sharedscripts
    postrotate
        systemctl reload apache2 > /dev/null 2>&1
    endscript
}
```

### 2. System Monitoring

**Install monitoring tools:**

```bash
# Install htop for system monitoring
sudo apt install htop -y

# Install mytop for MySQL monitoring
sudo apt install mytop -y

# Install Apache monitoring
sudo a2enmod status
```

### 3. Application Monitoring

Create `/usr/local/bin/check-attendance-health.sh`:

```bash
#!/bin/bash

# Check web server
if ! systemctl is-active --quiet apache2; then
    echo "Apache is down!" | mail -s "Alert: Apache Down" admin@unisiraj.edu.my
fi

# Check MySQL
if ! systemctl is-active --quiet mysql; then
    echo "MySQL is down!" | mail -s "Alert: MySQL Down" admin@unisiraj.edu.my
fi

# Check website response
if ! curl -f -s -o /dev/null https://attendance.unisiraj.edu.my; then
    echo "Website not responding!" | mail -s "Alert: Website Down" admin@unisiraj.edu.my
fi

# Check disk space
DISK_USAGE=$(df -h / | awk 'NR==2 {print $5}' | sed 's/%//')
if [ $DISK_USAGE -gt 80 ]; then
    echo "Disk usage is at ${DISK_USAGE}%" | mail -s "Alert: High Disk Usage" admin@unisiraj.edu.my
fi
```

Schedule monitoring:

```bash
sudo crontab -e
# Add: */5 * * * * /usr/local/bin/check-attendance-health.sh
```

### 4. Performance Monitoring

```bash
# Monitor slow queries
sudo tail -f /var/log/mysql/slow-query.log

# Monitor Apache access
sudo tail -f /var/log/apache2/attendance_access.log

# Monitor PHP errors
sudo tail -f /var/log/php_errors.log
```

---

## 🔄 Rollback Procedures

### Quick Rollback (Symlink Method)

```bash
# List versions
ls -la /var/www/html/

# Rollback to previous version
sudo rm /var/www/html/attendance-system
sudo ln -s /var/www/html/attendance-system-v0.9 /var/www/html/attendance-system

# Reload Apache
sudo systemctl reload apache2
```

### Database Rollback

```bash
# Restore from backup
gunzip < /var/backups/attendance/db_backup_TIMESTAMP.sql.gz | \
  mysql -u attendance_prod -p attendance_system

# Or restore specific backup
mysql -u attendance_prod -p attendance_system < backup.sql
```

---

## ✅ Post-Deployment Verification

### Verification Checklist

- [ ] Application loads correctly
- [ ] HTTPS working (certificate valid)
- [ ] Login functionality works
- [ ] Database connections stable
- [ ] QR code generation works
- [ ] File uploads working
- [ ] Reports generate correctly
- [ ] Analytics display properly
- [ ] Mobile access works
- [ ] Camera permissions work
- [ ] Email notifications sent (if configured)
- [ ] Logs writing correctly
- [ ] Backups running
- [ ] Monitoring active

### Smoke Tests

```bash
# Test web server response
curl -I https://attendance.unisiraj.edu.my

# Test database connection
mysql -u attendance_prod -p attendance_system -e "SELECT COUNT(*) FROM users;"

# Test PHP
php -v

# Test SSL certificate
openssl s_client -connect attendance.unisiraj.edu.my:443 -servername attendance.unisiraj.edu.my
```

---

## 📞 Support & Maintenance

### Regular Maintenance Tasks

**Daily:**
- Check error logs
- Monitor system resources
- Verify backups completed

**Weekly:**
- Review slow queries
- Check disk space
- Update content (if needed)

**Monthly:**
- Apply security updates
- Analyze performance metrics
- Review user feedback
- Archive old data

**Quarterly:**
- Full security audit
- Performance optimization
- Disaster recovery test
- Documentation update

---

## 📋 Deployment Checklist Summary

- [ ] Pre-deployment requirements met
- [ ] Production environment configured
- [ ] Security hardening applied
- [ ] Application deployed
- [ ] SSL/HTTPS configured
- [ ] Performance optimized
- [ ] Backup strategy implemented
- [ ] Monitoring setup
- [ ] Rollback procedures tested
- [ ] Post-deployment verification passed
- [ ] Documentation updated
- [ ] Team trained
- [ ] Support procedures defined

---

**UniSIRAJ Automated Attendance System**  
**Deployment Guide v1.0**  
**Last Updated**: August 7, 2026

**System is now ready for production use!** 🚀
