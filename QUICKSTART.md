# 🚀 QUICK START GUIDE
# UniSIRAJ Automated Attendance System

Get up and running in **5 minutes**!

---

## 📋 PREREQUISITES

- PHP 8.0 or higher
- MySQL 8.0 or higher
- Web browser (Chrome, Firefox, Safari, or Edge)

---

## ⚡ QUICK SETUP (3 Steps)

### STEP 1: Setup Database (2 minutes)

```bash
# 1. Open MySQL
mysql -u root -p

# 2. Create database
CREATE DATABASE unisiraj_attendance CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

# 3. Exit MySQL
exit;

# 4. Import schema
mysql -u root -p unisiraj_attendance < database/schema.sql
```

### STEP 2: Configure Application (30 seconds)

Open `config/database.php` and update if needed:
```php
private $host = 'localhost';
private $dbname = 'unisiraj_attendance';
private $username = 'root';
private $password = '';  // Your MySQL password
```

### STEP 3: Start Server (30 seconds)

```bash
# Navigate to project directory
cd "C:\Users\mohan\OneDrive\Desktop\Attendance System"

# Start PHP built-in server
php -S localhost:8000 -t public
```

### DONE! 🎉

Open your browser and go to: **http://localhost:8000**

---

## 🔑 DEFAULT LOGIN CREDENTIALS

### Administrator
- **Email**: admin@unisiraj.edu.my
- **Password**: Admin@123

### Lecturer (Sample)
- **Email**: fatimah@unisiraj.edu.my
- **Password**: Admin@123

### Student (Sample)
- **Email**: ahmed@student.unisiraj.edu.my
- **Password**: Admin@123

⚠️ **Change these passwords after first login!**

---

## 📁 PROJECT STRUCTURE

```
Attendance System/
├── app/               # Application logic
│   ├── controllers/   # Handle requests
│   ├── models/        # Database operations
│   └── views/         # HTML templates
├── config/            # Configuration files
├── database/          # Database schema
├── public/            # Web root (entry point)
│   ├── assets/        # CSS, JS, images
│   └── index.php      # Front controller
├── routes/            # URL routing
├── storage/           # Logs, uploads, QR codes
└── documentation/     # Project docs
```

---

## 🧪 VERIFY INSTALLATION

### Test 1: Database Connection
```bash
php -r "require 'config/config.php'; require 'config/database.php'; echo Database::getInstance()->testConnection() ? 'OK' : 'Failed';"
```

### Test 2: Web Access
- Open: http://localhost:8000
- Should redirect to login page

### Test 3: Sample Data
```sql
USE unisiraj_attendance;
SELECT * FROM users;
-- Should show 5 users (1 admin, 1 lecturer, 3 students)
```

---

## 🎯 CURRENT STATUS

- ✅ Phase 1: System Analysis - **COMPLETE**
- ✅ Phase 2: Project Setup - **COMPLETE**
- ⏳ Phase 3: Authentication - **NEXT**

---

## 📚 DOCUMENTATION

- **Full Setup Guide**: `documentation/PHASE-2-SETUP-GUIDE.md`
- **System Analysis**: `documentation/PHASE-1-SYSTEM-ANALYSIS.md`
- **Database Design**: `documentation/ERD-DIAGRAM.md`
- **Project Roadmap**: `documentation/PROJECT-ROADMAP.md`

---

## 🆘 COMMON ISSUES

### Database Connection Failed
**Fix**: Check MySQL is running and credentials are correct

### Page Not Found (404)
**Fix**: Make sure you're accessing through `http://localhost:8000`

### CSS Not Loading
**Fix**: Clear browser cache and verify BASE_URL in `config/config.php`

### Permission Denied
**Fix**: 
```bash
chmod 755 storage/logs
chmod 755 storage/uploads
chmod 755 storage/qr-codes
```

---

## 🔥 WHAT'S WORKING NOW

- ✅ Database structure (9 tables)
- ✅ Sample data (5 users, 1 course)
- ✅ MVC architecture
- ✅ Routing system
- ✅ Layout templates
- ✅ Security framework
- ✅ Error pages

---

## 📞 NEXT STEPS

1. **Review Phase 2 deliverables**
2. **Test the setup**
3. **Get approval**
4. **Start Phase 3: Authentication**

---

## 💡 TIPS

- Use **Chrome DevTools** to debug (F12)
- Check **error logs** in `storage/logs/`
- **Read comments** in code files
- Follow the **project roadmap**

---

**Ready to develop! 🚀**

For detailed information, see `documentation/PHASE-2-SETUP-GUIDE.md`
