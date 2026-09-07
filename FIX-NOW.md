# Quick Fix - Add Courses Working Again

## The Problem
- Can't add courses now because the database needs a new column
- phpMyAdmin shows "Forbidden" error

## The Solution (2 Minutes)

### Step 1: Run the Auto-Fix Script

**Open your browser and go to:**
```
http://localhost:8000/add-lecturer-column.php
```

That's it! The script will:
- ✅ Add the lecturer_name column automatically
- ✅ Show you what it did
- ✅ Test that everything works
- ✅ Give you a button to go back to admin dashboard

### Step 2: Test Adding a Course

1. Go back to your intake dashboard
2. Click "Add Course"
3. Fill in the form:
   - Course Code: TEST101
   - Course Name: Test Course
   - Lecturer: John Doe (optional)
4. Click "Add Course"
5. **Should work now!** ✅

## If the Script Doesn't Work

### Alternative: Use MySQL Command Line

1. **Open Command Prompt**
2. **Connect to MySQL:**
   ```
   mysql -u root -p
   ```
3. **Enter your MySQL password** (usually empty for local development)
4. **Run these commands:**
   ```sql
   USE unisiraj_attendance;
   ALTER TABLE courses ADD COLUMN lecturer_name VARCHAR(255) NULL AFTER lecturer_id;
   exit;
   ```

## About phpMyAdmin "Forbidden" Error

This is a common Apache/XAMPP configuration issue. Here are quick fixes:

### If using XAMPP:

1. **Open:** `C:\xampp\apache\conf\extra\httpd-xampp.conf`
2. **Find this section:**
   ```apache
   <Directory "C:/xampp/phpMyAdmin">
       Require local
   </Directory>
   ```
3. **Change to:**
   ```apache
   <Directory "C:/xampp/phpMyAdmin">
       Require all granted
   </Directory>
   ```
4. **Restart Apache** in XAMPP Control Panel

### If using Laragon:

1. phpMyAdmin should work at: `http://localhost/phpmyadmin`
2. If not, click "Menu" → "phpMyAdmin" in Laragon

### Alternative Tools:

Instead of phpMyAdmin, you can use:
- **HeidiSQL** (comes with XAMPP)
- **MySQL Workbench**
- **Command Line** (mysql -u root -p)
- **VS Code MySQL Extension**

## What Changed?

### Before:
- Lecturer: stored only lecturer_id (number)
- Display: looked up from lecturers table

### After:
- Lecturer: stores lecturer_name (text) directly
- Display: shows the text you entered
- **Benefit:** Can enter any lecturer name without needing it in database

## Need Help?

If `add-lecturer-column.php` shows an error, copy the error message and we can fix it!

## Summary

**Just do this:**
1. Go to: `http://localhost:8000/add-lecturer-column.php`
2. Wait for success message
3. Go back to admin dashboard
4. Try adding a course
5. Done! ✅
