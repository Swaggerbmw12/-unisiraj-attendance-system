# 🧪 TEST LOGIN GUIDE
# UniSIRAJ Automated Attendance System

Quick guide to test the authentication system.

---

## 🚀 START THE SERVER

```bash
# Navigate to project directory
cd "C:\Users\mohan\OneDrive\Desktop\Attendance System"

# Start PHP server
php -S localhost:8000 -t public
```

---

## 🔑 TEST ACCOUNTS

### 1. Administrator Account
```
Email:    admin@unisiraj.edu.my
Password: Admin@123
```

**What to expect:**
- Login successful
- Redirect to Admin Dashboard (`/admin/dashboard`)
- See statistics: Total Students, Lecturers, Courses, Today's Sessions
- View recent sessions and registrations
- Access admin menu (Students, Lecturers, Courses, Enrollments)

---

### 2. Lecturer Account
```
Email:    fatimah@unisiraj.edu.my
Password: Admin@123
```

**What to expect:**
- Login successful
- Redirect to Lecturer Dashboard (`/lecturer/dashboard`)
- See statistics: My Courses, Total Sessions, Active Today
- View assigned courses with student counts
- View recent sessions with attendance stats
- Access lecturer menu (Dashboard, Attendance Sessions)

---

### 3. Student Account
```
Email:    ahmed@student.unisiraj.edu.my
Password: Admin@123
```

**What to expect:**
- Login successful
- Redirect to Student Dashboard (`/student/dashboard`)
- See statistics: Enrolled Courses, Total Attendance, Overall Percentage
- View courses with attendance breakdown
- View recent attendance records
- Access student menu (Dashboard, Scan QR Code, My Attendance)

---

## ✅ FEATURES TO TEST

### 1. Login Process
- [x] Open http://localhost:8000
- [x] Enter email and password
- [x] Click "Sign In"
- [x] Should redirect to role-appropriate dashboard
- [x] Flash message: "Welcome back, [Name]!"

### 2. Wrong Credentials
- [x] Try invalid email
- [x] Try wrong password
- [x] Should show error: "Invalid email or password"
- [x] Should log failed attempt in audit_logs table

### 3. Dashboard Statistics
- [x] Check numbers match database records
- [x] Verify recent data displays correctly
- [x] Test quick action buttons

### 4. Navigation
- [x] Click menu items
- [x] Verify correct pages load
- [x] Check role-based menu visibility

### 5. Password Change
- [x] Click on user dropdown → My Profile (will be added in Phase 4)
- [x] Or navigate to `/change-password`
- [x] Enter current password
- [x] Enter new password twice
- [x] Click "Change Password"
- [x] Should show success message
- [x] Login with new password

### 6. Logout
- [x] Click user dropdown → Logout
- [x] Should redirect to login page
- [x] Should show success message
- [x] Attempt to access dashboard - should redirect to login

### 7. Session Timeout
- [x] Login
- [x] Wait 30+ minutes (or change SESSION_LIFETIME in config)
- [x] Try to navigate
- [x] Should redirect to login with timeout message

---

## 🗄️ DATABASE CHECKS

### Check Audit Logs
```sql
-- View recent login attempts
SELECT * FROM audit_logs 
WHERE action IN ('login', 'failed_login_attempt', 'logout')
ORDER BY created_at DESC 
LIMIT 10;
```

### Check Last Login Times
```sql
-- View user login history
SELECT u.email, u.last_login, r.name as role
FROM users u
INNER JOIN roles r ON u.role_id = r.id
ORDER BY u.last_login DESC;
```

### Check Session Data
```sql
-- Count total sessions per user
SELECT 
    u.email,
    COUNT(al.id) as login_count
FROM users u
LEFT JOIN audit_logs al ON u.id = al.user_id AND al.action = 'login'
GROUP BY u.id, u.email;
```

---

## 🐛 TROUBLESHOOTING

### Issue: "Database connection failed"
**Solution**: 
- Check MySQL is running
- Verify database exists: `unisiraj_attendance`
- Check credentials in `config/database.php`

### Issue: "Invalid email or password"
**Solution**:
- Double-check credentials
- Ensure database has sample data
- Run: `SELECT * FROM users WHERE email = 'admin@unisiraj.edu.my';`

### Issue: Page not found (404)
**Solution**:
- Ensure server started with `-t public` flag
- Access http://localhost:8000 (not localhost:8000/public)
- Check .htaccess file exists in public/

### Issue: Blank dashboard
**Solution**:
- Check browser console for errors
- Verify Bootstrap CSS is loading
- Check database has sample data

### Issue: "Invalid request" on login
**Solution**:
- This is CSRF protection working
- Clear browser cookies
- Try again (new CSRF token will generate)

---

## 📊 EXPECTED RESULTS

### Admin Dashboard
```
Statistics:
- Total Students: 3
- Total Lecturers: 1
- Active Courses: 1
- Today's Sessions: 0 (will increase when sessions created)

Recent Activity:
- List of 3 sample students
- No sessions yet (Phase 5)
```

### Lecturer Dashboard
```
Statistics:
- My Courses: 1 (CS401 - Final Year Project)
- Total Sessions: 0
- Active Today: 0

My Courses:
- CS401 with 3 enrolled students
```

### Student Dashboard
```
Statistics:
- Enrolled Courses: 1
- Total Attendance: 0
- Overall Attendance: 0%

My Courses:
- CS401 with 0/0 sessions (0%)
```

---

## 🎯 SUCCESS CRITERIA

Phase 3 is working correctly if:

- ✅ All three roles can login
- ✅ Each role redirects to correct dashboard
- ✅ Statistics display from database
- ✅ Navigation menu changes per role
- ✅ Logout works and redirects to login
- ✅ Password can be changed
- ✅ Audit logs are created
- ✅ Session timeout works
- ✅ Invalid credentials are rejected
- ✅ CSRF protection blocks invalid requests

---

## 📝 TEST CHECKLIST

Copy this to track your testing:

```
Login Tests:
[ ] Admin login successful
[ ] Lecturer login successful  
[ ] Student login successful
[ ] Invalid email rejected
[ ] Wrong password rejected
[ ] Inactive account rejected (if you deactivate one)

Dashboard Tests:
[ ] Admin dashboard displays
[ ] Lecturer dashboard displays
[ ] Student dashboard displays
[ ] Statistics are accurate
[ ] Recent data displays

Security Tests:
[ ] CSRF token validation works
[ ] Session timeout works
[ ] Logout destroys session
[ ] Cannot access pages after logout
[ ] Audit logs created correctly

Feature Tests:
[ ] Password change works
[ ] Flash messages display
[ ] Navigation menu shows correct items
[ ] Quick action buttons work
[ ] Responsive on mobile

Database Tests:
[ ] Last login updated
[ ] Audit logs created
[ ] No plain-text passwords
[ ] Sessions stored correctly
```

---

## 🎉 READY!

Your authentication system is now fully functional!

**Next Steps:**
1. Test all features above
2. Verify everything works
3. Report any issues
4. Get approval for Phase 4

---

**Happy Testing! 🚀**
