# Session Creation & QR Code Generation Guide
**UniSIRAJ Automated Attendance System**

---

## 🎯 Overview

This guide covers how to create attendance sessions and generate QR codes for students to scan and record their attendance.

---

## 📋 Prerequisites

Before creating a session, ensure:
- ✅ You're logged in as a lecturer
- ✅ You have at least one active course assigned
- ✅ Students are enrolled in your course
- ✅ Server is running (http://localhost:8000)

---

## 🚀 How to Create an Attendance Session

### **Method 1: From Lecturer Dashboard**

1. **Navigate** to http://localhost:8000/lecturer/dashboard
2. **Click** the "Create New Session" button (top right or quick actions)
3. **Fill in** the session form
4. **Click** "Create Session & Generate QR Code"

### **Method 2: From Course Dashboard**

1. **Go to** a specific course dashboard
2. **Click** "Create Session" button
3. **Course is pre-selected** - fill in remaining details
4. **Submit** the form

### **Method 3: From Sessions List**

1. **Navigate** to http://localhost:8000/lecturer/sessions
2. **Click** "Create New Session" button
3. **Fill in** the form
4. **Submit**

---

## 📝 Session Creation Form Fields

### **1. Course** (Required)
- Select the course for this attendance session
- Shows: Course Code - Course Name (# of students)
- Example: `BOT4423/BTT3134 - Software Engineering (6 students)`

### **2. Session Name** (Optional)
- Descriptive name for this session
- Examples:
  - `Week 5 - Introduction to PHP`
  - `Midterm Review Session`
  - `Lab 3 - Database Design`
  - `Final Lecture - Project Presentation`

### **3. Session Date** (Required)
- Date when the session will occur
- Default: Today's date
- Can select future dates for scheduled sessions

### **4. Start Time** (Required)
- When the session starts
- Default: Current time
- Format: HH:MM (24-hour)

### **5. Duration** (Required)
- How long the QR code remains valid
- Options:
  - 5 minutes *(Quick attendance at start of class)*
  - 10 minutes
  - **15 minutes** *(Default - recommended)*
  - 20 minutes
  - 30 minutes
  - 45 minutes
  - 60 minutes *(Full hour)*
  - 90 minutes *(1.5 hours)*
  - 120 minutes *(2 hours)*

**Note:** The QR code expires at: **Start Time + Duration**

---

## 🎨 QR Code Generation

### **Automatic Generation**

When you create a session, the system automatically:

1. ✅ Generates a unique secure token (64-character hex)
2. ✅ Creates a URL: `http://localhost:8000/student/attendance/scan?token=XXXXX`
3. ✅ Generates QR code image via API
4. ✅ Stores QR code URL in database
5. ✅ Redirects you to session view page
6. ✅ Displays QR code for students to scan

### **QR Code Details**

**Size:** 300x300 pixels
**Format:** PNG image
**URL Encoded:** Student attendance URL with unique token
**Valid:** Until session expires or is manually closed

---

## 📱 Session View Page

After creating a session, you'll see:

### **Left Side: QR Code Display**
- Large, scannable QR code image
- Border and shadow for better visibility
- Expiration time countdown (if active)
- Status badge (ACTIVE or CLOSED)

### **Right Side: Session Information**
- **Date:** Full date (e.g., Wednesday, August 5, 2026)
- **Start Time:** Session start (e.g., 02:30 PM)
- **Expires At:** When QR code becomes invalid
- **Lecturer:** Your name
- **Actions:** 
  - Close Session Now (if active)
  - Back to Sessions

### **Statistics Cards** (Real-time)
1. **Total Enrolled** - Students enrolled in course
2. **Present** - Students who scanned QR code
3. **Absent** - Enrolled but didn't scan
4. **Attendance Rate** - Percentage present

### **Attendance Records Table**
- #, Student ID, Name, Email, Time Recorded, Status
- **Auto-refreshes** every 5 seconds while session is active
- Shows students as they scan in real-time

---

## 🔴 Live Attendance Monitoring

### **Auto-Refresh Feature**

When a session is **ACTIVE**:
- Page automatically updates every **5 seconds**
- No need to manually refresh
- See students join in real-time
- Statistics update automatically

### **Manual Close**

You can close a session at any time:
- Click "Close Session Now" button
- Confirm the action
- Session becomes inactive immediately
- Students can no longer scan QR code
- Final attendance is locked

---

## ⚙️ Technical Details

### **Session Token Generation**
```php
$token = bin2hex(random_bytes(32));
// Generates: 64-character hexadecimal string
// Example: a3f5c8d2e4b6...
```

### **QR Code API**
```
https://api.qrserver.com/v1/create-qr-code/
  ?size=300x300
  &data=http://localhost:8000/student/attendance/scan?token=XXXXX
```

### **Expiration Calculation**
```php
$expiresAt = date('Y-m-d H:i:s', 
    strtotime($session_date . ' ' . $start_time . ' +' . $duration . ' minutes')
);
```

### **Session Status**
A session is **ACTIVE** when:
- `is_active` = 1 (database)
- `expires_at` > Current time
- Not manually closed

A session is **CLOSED** when:
- `is_active` = 0 (database)
- OR `expires_at` < Current time
- OR manually closed by lecturer

---

## 🧪 Testing the Feature

### **Step-by-Step Test**

1. **Create a Session**
   ```
   - Login as: fatimah@unisiraj.edu.my / Admin@123
   - Go to: http://localhost:8000/lecturer/sessions/create
   - Select course: Any available course
   - Session name: "Test Session"
   - Date: Today
   - Time: Current time
   - Duration: 15 minutes
   - Submit
   ```

2. **Verify QR Code Generated**
   - Check you're redirected to session view
   - QR code image is displayed
   - Status badge shows "ACTIVE"
   - Expiration time is shown

3. **Check Database**
   ```sql
   SELECT * FROM attendance_sessions ORDER BY id DESC LIMIT 1;
   -- Should show your newly created session
   -- Check token field has 64-character value
   -- Check qr_code_path has URL
   ```

4. **Test QR Code URL**
   - Right-click QR code image
   - Copy image URL
   - Paste in browser
   - Should see QR code image

5. **Test Student Scanning** (Next Phase)
   - Open session view page
   - Get QR code URL (hover over QR to see URL)
   - Navigate to that URL as a student
   - Record attendance
   - See it appear in attendance list (auto-refresh)

---

## 📊 Session States

### **1. Scheduled** (Future session)
- Session date is in the future
- QR code generated but not yet active
- Students cannot scan yet

### **2. Active** (Current session)
- Session date is today
- Current time < Expiration time
- `is_active` = 1
- QR code is scannable
- Students can record attendance
- Real-time monitoring enabled

### **3. Expired** (Past expiration)
- Current time > Expiration time
- QR code no longer valid
- Students cannot scan
- Session still marked active in DB (auto-expired)

### **4. Closed** (Manually closed)
- `is_active` = 0
- QR code disabled
- Students cannot scan
- Final attendance locked

---

## 🎯 Best Practices

### **Timing**
- ✅ Create sessions **5-10 minutes before** class starts
- ✅ Set duration to **10-15 minutes** for quick attendance
- ✅ For full-class attendance, use **60-90 minutes**
- ✅ Close manually when all students have arrived

### **Session Names**
- ✅ Use descriptive names: `Week 5 - Arrays and Loops`
- ✅ Include week/topic: `Lecture 10 - Database Normalization`
- ✅ For labs: `Lab 3 - PHP Form Handling`
- ❌ Avoid: `Session 1`, `Test`, `Attendance`

### **Duration Selection**
- **5 minutes:** Very strict timing, attendance at start only
- **10-15 minutes:** Standard lecture attendance (recommended)
- **30-45 minutes:** Allow late arrivals
- **60+ minutes:** Lab sessions, practical work
- **120 minutes:** Full double-period classes

### **Managing Sessions**
- ✅ Check attendance rate during session
- ✅ Close session when all students present
- ✅ Remind students to scan when attendance is low
- ✅ Keep QR code visible on projector/screen
- ❌ Don't share QR code URL outside class
- ❌ Don't leave sessions open unnecessarily

---

## 🔍 Troubleshooting

### **Problem: QR Code Not Displaying**

**Causes:**
1. QR code API is down
2. Network connection issue
3. QR code path not saved to database

**Solutions:**
```php
// Check session in database
SELECT id, qr_code_path FROM attendance_sessions WHERE id = YOUR_SESSION_ID;

// If qr_code_path is NULL, regenerate:
// Contact administrator or check logs
```

### **Problem: Students Can't Scan QR Code**

**Causes:**
1. Session expired
2. Session manually closed
3. QR code URL is incorrect
4. Network issue

**Check:**
```
1. Is session status "ACTIVE"?
2. Is current time < expires_at?
3. Is is_active = 1 in database?
4. Can you access the URL in QR code?
```

### **Problem: Auto-Refresh Not Working**

**Causes:**
1. JavaScript disabled in browser
2. Browser console has errors
3. Session is not active

**Fix:**
- Open DevTools (F12) → Console
- Look for JavaScript errors
- Manually refresh page
- Check session is marked ACTIVE

### **Problem: Attendance Not Recording**

**This is a student-side issue** - covered in Student Attendance Guide

---

## 📱 QR Code URL Format

### **Standard Format**
```
http://localhost:8000/student/attendance/scan?token=UNIQUE_64_CHAR_TOKEN
```

### **Example**
```
http://localhost:8000/student/attendance/scan?token=a3f5c8d2e4b6f1a8c7d9e2b5f4a6c8d1e3b7f2a9c4d6e1b8f3a5c7d2e4b9f1a6
```

### **Token Security**
- **64 characters** long
- **Hexadecimal** (0-9, a-f)
- **Cryptographically secure** random generation
- **Unique** per session
- **Cannot be guessed** or predicted
- **One-time use** per student

---

## 🎓 Usage Scenarios

### **Scenario 1: Regular Lecture**
```
Session Name: Week 8 - Object-Oriented Programming
Date: Today
Start Time: 10:00 AM
Duration: 15 minutes
Result: QR code valid from 10:00-10:15 AM
```

### **Scenario 2: Lab Session**
```
Session Name: Lab 4 - Database CRUD Operations
Date: Today
Start Time: 2:00 PM
Duration: 120 minutes
Result: QR code valid entire 2-hour lab (students can arrive anytime)
```

### **Scenario 3: Quick Attendance Check**
```
Session Name: Attendance Check
Date: Today
Start Time: Current time
Duration: 5 minutes
Result: Very strict - students must scan within 5 minutes
```

### **Scenario 4: Scheduled Future Session**
```
Session Name: Final Exam
Date: 2026-08-15 (future)
Start Time: 9:00 AM
Duration: 180 minutes
Result: QR code generated now, active on exam day
```

---

## 🔗 Related Routes

| Action | URL | Method |
|--------|-----|--------|
| Sessions List | `/lecturer/sessions` | GET |
| Create Form | `/lecturer/sessions/create` | GET |
| Store Session | `/lecturer/sessions/store` | POST |
| View Session | `/lecturer/sessions/view/{id}` | GET |
| Close Session | `/lecturer/sessions/close?id={id}` | POST |
| Live Data | `/lecturer/sessions/live?id={id}` | GET (AJAX) |

---

## 📁 Related Files

### **Controllers**
- `app/controllers/Lecturer/SessionController.php` - Session management logic

### **Models**
- `app/models/AttendanceSession.php` - Database operations

### **Views**
- `app/views/lecturer/sessions/create.php` - Creation form
- `app/views/lecturer/sessions/view.php` - Session display & QR code
- `app/views/lecturer/sessions/index.php` - Sessions list

### **Database**
- `attendance_sessions` table - Session records
- `attendance_records` table - Student attendance records

---

## 🎉 Summary

The session creation and QR code generation system is **fully functional** and ready to use!

**Key Features:**
- ✅ Easy session creation
- ✅ Automatic QR code generation
- ✅ Real-time attendance monitoring
- ✅ Auto-refresh every 5 seconds
- ✅ Flexible duration options
- ✅ Manual session closing
- ✅ Secure token-based system
- ✅ Beautiful, responsive UI

**Next Steps:**
1. Test creating a session
2. Verify QR code displays
3. Test student scanning (next phase)
4. Review attendance reports

---

**Ready to create your first attendance session!** 🚀

**URL to Start:** http://localhost:8000/lecturer/sessions/create
**Login:** fatimah@unisiraj.edu.my / Admin@123
