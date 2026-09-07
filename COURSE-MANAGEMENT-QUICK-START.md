# 🚀 Course Management Feature - Quick Start Guide

## ✅ What's New

**Lecturers can now:**
1. ✨ Add their own courses
2. 📂 See all courses in a sidebar menu
3. 📊 View course-specific dashboards
4. 🔄 Courses automatically sync with admin dashboard

---

## 🎯 How to Use

### Step 1: Login as Lecturer
```
URL: http://localhost:8000/login
Email: fatimah@unisiraj.edu.my
Password: Admin@123
```

### Step 2: Add Your First Course

#### Option A: From Navbar
1. Click "My Courses" dropdown in navbar
2. Select "Add New Course"

#### Option B: From Sidebar
1. Look at the left sidebar
2. Click the green "Add New Course" button

### Step 3: Fill Course Details
```
Course Code:     CS202         (Required)
Course Name:     Data Structures   (Required)
Semester:        Semester 1    (Required)
Academic Year:   2026/2027     (Required)
Credits:         3             (Optional)
Description:     [Optional text]
```

### Step 4: Submit
- Click "Add Course" button
- Wait for confirmation
- Automatically redirected to course dashboard

---

## 📱 Interface Overview

### Sidebar (Left Side)
```
┌────────────────────┐
│  MY COURSES        │
├────────────────────┤
│ [+ Add New Course] │ ← Click to add
├────────────────────┤
│ CS401 (Active)     │ ← Your courses
│ Final Year Project │
│ 👥 45 students     │
├────────────────────┤
│ CS202              │
│ Data Structures    │
│ 👥 38 students     │
└────────────────────┘
```

### Course Dashboard (Main Area)
- **4 Stat Cards**: Students, Sessions, Active, Avg Attendance
- **Recent Sessions Table**: All sessions for this course
- **Student Attendance**: Individual student progress
- **Quick Actions**: Create session, view all, reports

---

## 🎨 Visual Guide

### Adding a Course
1. **Form appears** with all fields
2. **Fill in details** (red * = required)
3. **Click submit** → Loading animation
4. **Success!** → Course dashboard opens
5. **Sidebar updates** → Course appears in menu

### Viewing Course Dashboard
1. **Click course name** in sidebar
2. **Dashboard loads** with course stats
3. **Active course highlighted** in blue
4. **See all sessions** for that course
5. **Monitor students** attendance

### Switching Between Courses
1. **Click different course** in sidebar
2. **Dashboard updates** instantly
3. **Sidebar shows** active selection
4. **Stats change** to selected course

---

## 🔄 Admin Synchronization

### What Happens Behind the Scenes:
```
Lecturer adds course
    ↓
Saves to database with lecturer_id
    ↓
Admin dashboard automatically shows new course
    ↓
Admin can see: "Course by Fatimah Noni Muhamad"
```

### Admin Can:
- ✅ View all courses by all lecturers
- ✅ See which lecturer created each course
- ✅ Edit course details
- ✅ Assign courses to different lecturers
- ✅ Manage student enrollments

---

## 📊 Statistics Shown

### In Sidebar (Per Course)
- 👥 Number of enrolled students
- 🔴 Active sessions badge (if any)

### In Course Dashboard
1. **Students**: How many enrolled
2. **Sessions**: How many created
3. **Active Now**: Currently running
4. **Avg Attendance**: Overall percentage

---

## 📱 Mobile Version

### On Mobile Devices:
- Sidebar **hidden by default**
- **Blue button** bottom-left to toggle
- **Click button** → Sidebar slides in
- **Select course** → Sidebar auto-closes
- **Full dashboard** visible

---

## ⚡ Quick Actions

### From Course Dashboard:
1. **Create New Session** → Pre-filled with course
2. **View All Sessions** → Filtered by course
3. **Generate Report** → Course-specific data

### From Navbar:
1. **My Courses** → Add New / View All
2. **Attendance Sessions** → All sessions
3. **Reports** → Generate reports
4. **Analytics** → View trends

---

## 🎯 Common Tasks

### Task 1: Create Course and First Session
```
1. Add course (CS202)
2. Redirected to CS202 dashboard
3. Click "Create New Session"
4. Course already selected
5. Fill session details
6. Generate QR code
7. Share with students
```

### Task 2: Monitor Course Attendance
```
1. Click course in sidebar
2. View statistics cards
3. Scroll to "Student Attendance"
4. See each student's progress
5. Check attendance rates
```

### Task 3: Manage Multiple Courses
```
1. Sidebar shows all courses
2. Click CS101 → View CS101 data
3. Click CS202 → View CS202 data
4. Click CS301 → View CS301 data
5. Each has separate statistics
```

---

## 🔍 Finding Your Courses

### In Sidebar:
- **Always visible** on desktop
- **Ordered alphabetically** by course code
- **Shows student count** for quick reference
- **Highlights active** course in blue

### In Navbar:
- **My Courses dropdown** → View all
- **Dashboard** → Overview of all courses
- **Sessions** → Filter by course

---

## 💡 Tips & Tricks

### 1. Course Organization
- Use clear course codes (CS101, CS202)
- Descriptive course names
- Add detailed descriptions
- Set correct semester/year

### 2. Quick Navigation
- Use sidebar for fast switching
- Bookmark favorite courses
- Check active sessions badge
- Monitor student counts

### 3. Efficient Workflow
```
Morning: Check active sessions in sidebar
         Create new sessions as needed
         
During Class: Monitor live attendance
              Check student participation
              
After Class: Review attendance rates
            Identify absent students
            
Weekly: Generate course reports
        Analyze attendance trends
```

---

## ❓ Troubleshooting

### Problem: Sidebar not showing
**Solution**: Make sure you're logged in as lecturer and have courses added

### Problem: Can't add course
**Solution**: Check that course code is unique and all required fields filled

### Problem: Course dashboard empty
**Solution**: No sessions created yet - click "Create New Session"

### Problem: Mobile sidebar won't open
**Solution**: Click the blue circular button at bottom-left

---

## 📞 Need Help?

### For Course Issues:
- Check course details are correct
- Verify you're logged in as lecturer
- Contact IT support if persists

### For Admin Sync Issues:
- Courses appear immediately in admin
- Admin needs to refresh their page
- Check database connection

---

## ✨ Key Features

✅ **Easy Course Creation** - Simple 5-field form  
✅ **Sidebar Navigation** - Quick access to all courses  
✅ **Course Dashboards** - Detailed stats per course  
✅ **Auto Synchronization** - Admin sees everything  
✅ **Mobile Responsive** - Works on all devices  
✅ **Beautiful Design** - Modern, clean interface  

---

## 🎓 Next Steps

After adding courses:
1. ✅ Create attendance sessions
2. ✅ Enroll students (admin does this)
3. ✅ Generate QR codes for sessions
4. ✅ Students scan QR codes
5. ✅ Monitor attendance in real-time
6. ✅ Generate reports

---

**Ready to start?** Login and click "Add New Course"!

**Version**: 1.0.0  
**Date**: August 5, 2026  
**Status**: ✅ Live and Ready

---

_Happy Teaching! 🎓📚_
