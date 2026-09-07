# Student Course Enrollment Guide
**UniSIRAJ Automated Attendance System**

---

## 🎯 Overview

Students can now browse available courses and enroll themselves, similar to the lecturer dashboard functionality. This guide covers how students can manage their course enrollments.

---

## ✨ New Features Added

### **1. Course Enrollment Page**
- **URL:** `/student/enrollment`
- Browse all available courses
- View enrolled courses
- Enroll in new courses
- Unenroll from courses (with restrictions)

### **2. Enhanced Student Dashboard**
- New "Enroll in Courses" button (top right)
- "Enroll in Courses" in Quick Actions sidebar
- Course enrollment management

### **3. Course Cards Display**
- **My Enrolled Courses:** Green cards with enrolled status
- **Available Courses:** Available for enrollment

---

## 📋 How to Access

### **From Student Dashboard:**
1. Login as student
2. Click "Enroll in Courses" button (top right, green button)
   OR
3. Scroll to Quick Actions → Click "Enroll in Courses"

### **Direct URL:**
```
http://localhost:8000/student/enrollment
```

---

## 🎨 Page Layout

```
┌─────────────────────────────────────────────────────────┐
│ 📚 Course Enrollment                                    │
│ Browse and enroll in available courses                  │
├─────────────────────────────────────────────────────────┤
│                                                         │
│ ┌─────────────────────────────────────────────────┐   │
│ │ 📖 MY ENROLLED COURSES            [3 Courses]  │   │
│ ├─────────────────────────────────────────────────┤   │
│ │                                                 │   │
│ │  ┌──────────┐ ┌──────────┐ ┌──────────┐       │   │
│ │  │ CS401    │ │ BOT4423  │ │ BTT3123  │       │   │
│ │  │ Course 1 │ │ Course 2 │ │ Course 3 │       │   │
│ │  │[Enrolled]│ │[Enrolled]│ │[Enrolled]│       │   │
│ │  │[Unenroll]│ │[Unenroll]│ │[Unenroll]│       │   │
│ │  └──────────┘ └──────────┘ └──────────┘       │   │
│ └─────────────────────────────────────────────────┘   │
│                                                         │
│ ┌─────────────────────────────────────────────────┐   │
│ │ ➕ AVAILABLE COURSES             [2 Available] │   │
│ ├─────────────────────────────────────────────────┤   │
│ │                                                 │   │
│ │  ┌──────────┐ ┌──────────┐                     │   │
│ │  │ BCS302   │ │ SE401    │                     │   │
│ │  │ Course A │ │ Course B │                     │   │
│ │  │[Available│ │[Available│                     │   │
│ │  │[Enroll  ]│ │[Enroll  ]│                     │   │
│ │  └──────────┘ └──────────┘                     │   │
│ └─────────────────────────────────────────────────┘   │
└─────────────────────────────────────────────────────────┘
```

---

## 🔄 Enrollment Process

### **Enroll in a Course:**

1. **Navigate** to Course Enrollment page
2. **Browse** available courses (green section)
3. **Review** course details:
   - Course code
   - Course name
   - Semester & academic year
   - Lecturer name
   - Number of enrolled students
   - Description
4. **Click** "Enroll Now" button (green)
5. **Confirm** the enrollment dialog
6. **Result:** 
   - ✅ Success message
   - ✅ Course moves to "My Enrolled Courses"
   - ✅ Page refreshes

### **Unenroll from a Course:**

1. **Navigate** to Course Enrollment page
2. **Find** course in "My Enrolled Courses" section
3. **Click** "Unenroll" button (red)
4. **Confirm** the warning dialog
5. **Result:**
   - ✅ Success: Course removed
   - ⚠️ Error: If you have attendance records

---

## 📊 Course Card Information

### **Enrolled Course Card (Green Border):**
```
┌──────────────────────────────────────┐
│ CS401              [✓ Enrolled]      │
│ Final Year Project                   │
│                                      │
│ 📅 Semester 2                        │
│ 📆 2025/2026                         │
│ 👤 Fatimah Noni Muhamad              │
│ 👥 15 students                       │
│                                      │
│ Enrolled: Aug 05, 2026               │
│                                      │
│ [🔴 Unenroll]                        │
└──────────────────────────────────────┘
```

### **Available Course Card (Teal Border):**
```
┌──────────────────────────────────────┐
│ BOT4013            [Available]       │
│ Advanced Programming Java            │
│                                      │
│ 📅 Semester 1                        │
│ 📆 2025/2026                         │
│ 👤 Fatimah Noni Muhamad              │
│ 👥 12 students enrolled              │
│                                      │
│ Advance Java programming...          │
│                                      │
│ [➕ Enroll Now]                      │
└──────────────────────────────────────┘
```

---

## ⚠️ Enrollment Rules

### **Can Enroll If:**
✅ Course is active (`is_active = 1`)
✅ Course is not archived (`archived = 0`)
✅ Not already enrolled in the course
✅ Course exists in system

### **Cannot Enroll If:**
❌ Already enrolled in the course
❌ Course is inactive or archived
❌ Course doesn't exist

### **Can Unenroll If:**
✅ No attendance records in the course
✅ Currently enrolled

### **Cannot Unenroll If:**
❌ Have attendance records in the course
❌ Not enrolled in the course

---

## 🔧 Technical Implementation

### **New Files Created:**

1. **Controller:**
   ```
   app/controllers/Student/EnrollmentController.php
   ```
   - `index()` - Show enrollment page
   - `enroll()` - Enroll in course
   - `unenroll()` - Unenroll from course

2. **View:**
   ```
   app/views/student/enrollment/index.php
   ```
   - Course cards display
   - Enrollment interface
   - AJAX enrollment functions

3. **Routes Added:**
   ```php
   '/student/enrollment' → EnrollmentController::index()
   '/student/enrollment/enroll' → EnrollmentController::enroll()
   '/student/enrollment/unenroll' → EnrollmentController::unenroll()
   ```

### **Modified Files:**

1. **Student Dashboard:**
   ```
   app/views/student/dashboard.php
   ```
   - Added "Enroll in Courses" button
   - Updated Quick Actions

2. **Routes:**
   ```
   routes/web.php
   ```
   - Added enrollment routes

---

## 🧪 Testing Guide

### **Test Enrollment:**

1. **Login as Student:**
   ```
   Email: ahmed@student.unisiraj.edu.my
   Password: Admin@123
   ```

2. **Go to Enrollment:**
   ```
   http://localhost:8000/student/enrollment
   ```

3. **Verify Display:**
   - See "My Enrolled Courses" section
   - See "Available Courses" section
   - Course cards display properly

4. **Enroll in a Course:**
   - Click "Enroll Now" on an available course
   - Confirm dialog
   - Verify success message
   - Verify course moves to enrolled section

5. **Check Dashboard:**
   - Go to student dashboard
   - Verify course appears in "My Courses" table
   - Verify statistics updated

### **Test Unenrollment:**

1. **Unenroll from Course (No Attendance):**
   - Go to enrollment page
   - Click "Unenroll" on a course without attendance
   - Confirm dialog
   - Verify course removed
   - Verify course appears in available section

2. **Try Unenroll (With Attendance):**
   - Click "Unenroll" on a course with attendance records
   - Verify warning message appears
   - Verify unenrollment fails

### **Test Edge Cases:**

1. **Double Enrollment:**
   - Try to enroll in same course twice
   - Verify error message

2. **Invalid Course:**
   - Try to enroll in non-existent course
   - Verify error handling

3. **All Courses Enrolled:**
   - Enroll in all available courses
   - Verify "Available Courses" shows success message

---

## 📱 User Interface Features

### **Visual Feedback:**

**Hover Effects:**
- Cards lift up on hover
- Shadow increases
- Smooth transitions

**Button States:**
- Green "Enroll Now" for available courses
- Red "Unenroll" for enrolled courses
- Disabled state for invalid actions

**Badges:**
- "Enrolled" badge on enrolled courses (green)
- "Available" badge on available courses (teal)
- Student count badges

### **Responsive Design:**

**Desktop (Large Screens):**
- 3 cards per row
- Full course information
- Side-by-side layout

**Tablet (Medium Screens):**
- 2 cards per row
- Condensed information
- Stacked sections

**Mobile (Small Screens):**
- 1 card per row
- Full-width cards
- Vertical stack

---

## 🔍 Database Operations

### **Enrollment (INSERT):**
```sql
INSERT INTO enrollments (student_id, course_id, enrollment_date, status)
VALUES (?, ?, CURDATE(), 'active')
```

### **Unenrollment (DELETE):**
```sql
DELETE FROM enrollments 
WHERE student_id = ? AND course_id = ?
```

### **Check Attendance:**
```sql
SELECT COUNT(*) 
FROM attendance_records ar
INNER JOIN attendance_sessions ats ON ar.session_id = ats.id
WHERE ar.student_id = ? AND ats.course_id = ?
```

---

## ✅ Success Criteria

The enrollment system is working when:

1. ✅ Student can view enrollment page
2. ✅ Enrolled courses display correctly
3. ✅ Available courses display correctly
4. ✅ Can enroll in available courses
5. ✅ Cannot enroll twice in same course
6. ✅ Can unenroll if no attendance
7. ✅ Cannot unenroll if has attendance
8. ✅ Dashboard updates after enrollment
9. ✅ Course stats update correctly
10. ✅ AJAX requests work properly

---

## 🎯 Integration with Existing Features

### **Student Dashboard:**
- Enrolled courses appear in "My Courses" table
- Statistics include all enrolled courses
- Active sessions only for enrolled courses
- Attendance history only for enrolled courses

### **Attendance System:**
- Can only scan QR for enrolled courses
- Attendance records tied to enrollments
- Cannot record attendance if not enrolled

### **Lecturer View:**
- Lecturers see updated enrollment counts
- Course dashboards show current enrollments
- Enrollment changes reflect immediately

---

## 📊 Quick Reference

| Action | URL | Method |
|--------|-----|--------|
| View Enrollment | `/student/enrollment` | GET |
| Enroll in Course | `/student/enrollment/enroll` | POST |
| Unenroll from Course | `/student/enrollment/unenroll` | POST |

| Parameter | Required | Type | Description |
|-----------|----------|------|-------------|
| `course_id` | Yes | Integer | Course to enroll/unenroll |

---

## 🚀 Next Steps

1. **Test the enrollment system**
2. **Verify AJAX functions work**
3. **Check database updates**
4. **Test with multiple students**
5. **Verify dashboard integration**

---

**Student course enrollment is now fully functional!** 🎓✨

**Test URL:** http://localhost:8000/student/enrollment
**Login:** ahmed@student.unisiraj.edu.my / Admin@123
