# 🔧 FIX: Enable PHP PDO MySQL Extension

## Issue
```
Database Connection Error: could not find driver
```

This means the PDO MySQL extension is not enabled in PHP.

---

## ✅ QUICK FIX (3 Steps)

### Step 1: Open php.ini
```
Location: C:\laragon\bin\php\php-8.3.28-Win32-vs16-x64\php.ini
```

**How to open:**
1. Right-click on Laragon tray icon
2. Click "PHP" → "php.ini"
3. OR Open file directly in Notepad

### Step 2: Find and Uncomment
Search for this line (around line 813):
```ini
;extension=pdo_mysql
```

Change it to (remove the semicolon):
```ini
extension=pdo_mysql
```

### Step 3: Restart PHP Server
Stop the current PHP server (Ctrl+C in terminal) and restart:
```bash
php -S localhost:8000 -t public
```

---

## 🚀 ALTERNATIVE: Use Laragon's Built-in Feature

### Even Easier Method:

1. **Open Laragon**
2. **Right-click Laragon tray icon**
3. **Go to: PHP → Quick settings → Enable extension**
4. **Check: pdo_mysql**
5. **Laragon will auto-restart PHP**

---

## ✅ VERIFY IT WORKS

After enabling, run this command:
```bash
php -m | findstr pdo
```

You should see:
```
PDO
pdo_mysql
pdo_pgsql
```

---

## 🌐 TEST IN BROWSER

1. Restart PHP server:
   ```bash
   php -S localhost:8000 -t public
   ```

2. Open browser:
   ```
   http://localhost:8000
   ```

3. Should now show the login page without errors!

---

## 📝 WHAT THESE EXTENSIONS DO

- **PDO** - PHP Data Objects (database abstraction layer)
- **pdo_mysql** - MySQL driver for PDO
- **pdo_pgsql** - PostgreSQL driver (not needed, but OK to have)

We need `pdo_mysql` to connect to MySQL database.

---

## 🐛 IF STILL NOT WORKING

### Check if MySQL is Running

#### Using Laragon:
1. Open Laragon
2. Click "Start All"
3. MySQL should show green "Running" status

#### Using Services:
```powershell
Get-Service -Name *mysql* | Select-Object Name, Status
```

### Check Database Exists
```bash
# Open MySQL command line
C:\laragon\bin\mysql\mysql-8.0.30-winx64\bin\mysql -u root

# Check database
SHOW DATABASES LIKE 'unisiraj%';

# Should show: unisiraj_attendance
```

### Import Database if Missing
```bash
cd C:\laragon\bin\mysql\mysql-8.0.30-winx64\bin
mysql -u root unisiraj_attendance < "C:\Users\mohan\OneDrive\Desktop\Attendance System\database\schema.sql"
```

---

## 🎯 SUMMARY

**Problem:** PDO MySQL driver not enabled  
**Solution:** Uncomment `;extension=pdo_mysql` in php.ini  
**Restart:** PHP server  
**Result:** Database connection works! ✅

---

**After fixing, you'll see the beautiful login page without any errors!** 🚀
