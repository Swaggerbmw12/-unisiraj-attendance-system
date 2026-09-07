# Quick Start Guide - Testing Intake Dashboard Fixes

## 🚀 Quick Test (5 minutes)

### Step 1: Start Server
```bash
cd "c:\Users\mohan\OneDrive\Desktop\Attendance System"
php -S localhost:8000 -t public
```

### Step 2: Run Database Test
Open browser: `http://localhost:8000/test-database-operations.php`

**Expected:** All tests show ✓ (green checkmarks)

### Step 3: Test Add Course
1. Login as admin → `http://localhost:8000/login`
2. Navigate: Admin Dashboard → Computer Science → Intake February 2023
3. Click **"Add Course"** (blue button)
4. Fill form:
   - Course Code: `TEST101`
   - Course Name: `Test Course`
   - Credits: `3`
5. Click **"Add Course"**

**Expected:** ✓ Success message + course appears in table

### Step 4: Test Add Student
1. On same page, click **"Add Student"** (green button)
2. Fill form:
   - Name: `Test Student`
   - Matric No: `TEST001`
   - Email: `test@student.edu.my`
   - Password: `test123`
3. Click **"Add Student"**

**Expected:** ✓ Success message + student appears in table

### Step 5: Test Edit Student
1. Click pencil icon next to test student
2. Change name to `Test Student Updated`
3. Click **"Update Student"**

**Expected:** ✓ Name updates in table

### Step 6: Test Delete Student
1. Click trash icon next to test student
2. Confirm deletion
3. Click **"Delete"**

**Expected:** ✓ Student removed from table

## ✅ All Working?
Great! The fixes are successful. You can now:
- Delete test files (`test-*.php`)
- Remove test data
- Use the system normally

## ❌ Something Failed?
1. Check browser console (F12)
2. Check PHP error logs
3. Review `TESTING-GUIDE.md` for detailed debugging
4. Provide error details for further assistance

## 📁 Files Changed
- `app/controllers/Admin/DepartmentController.php` - Enhanced error handling
- `app/models/Course.php` - Improved create method
- `app/models/Student.php` - Improved create/update methods

## 📋 Documentation Files
- `FIXES-APPLIED.md` - Detailed list of all changes
- `TESTING-GUIDE.md` - Comprehensive testing instructions
- `QUICK-START.md` - This file (quick reference)
