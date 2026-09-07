# User Manual - UniSIRAJ Automated Attendance System

**Version**: 1.0  
**Last Updated**: August 7, 2026  
**Target Audience**: All Users (Admin, Lecturers, Students)

---

## 📋 Table of Contents

1. [Getting Started](#getting-started)
2. [Admin User Guide](#admin-user-guide)
3. [Lecturer User Guide](#lecturer-user-guide)
4. [Student User Guide](#student-user-guide)
5. [Common Tasks](#common-tasks)
6. [FAQs](#faqs)
7. [Tips & Best Practices](#tips--best-practices)
8. [Troubleshooting](#troubleshooting)

---

## 🚀 Getting Started

### Accessing the System

1. **Open your web browser** (Chrome, Firefox, or Edge recommended)
2. **Navigate to the system URL**:
   - Local: `http://localhost:8000`
   - Production: `https://attendance.unisiraj.edu.my` (example)
3. **You will see the login page**

### First Time Login

1. Enter your email address
2. Enter your temporary password
3. Click "Login"
4. You will be prompted to change your password (first time only)

### Changing Your Password

1. Click your name in the top-right corner
2. Select "Change Password"
3. Enter current password
4. Enter new password
5. Confirm new password
6. Click "Update Password"

**Password Requirements:**
- Minimum 8 characters
- At least one uppercase letter
- At least one lowercase letter
- At least one number
- At least one special character

---

## 👨‍💼 Admin User Guide

### Admin Dashboard Overview

After logging in as admin, you'll see:
- **Statistics Cards**: Students, Lecturers, Courses, Active Sessions
- **Quick Actions**: Create new records
- **Recent Activity**: Latest system activities

### Managing Students

#### Creating a New Student

1. Navigate to **Admin Dashboard**
2. Click **"Students"** in sidebar or click **"Add Student"**
3. Fill in the form:
   - **Student ID**: Unique identifier (e.g., STU001)
   - **First Name**: Student's first name
   - **Last Name**: Student's last name
   - **Email**: Student's email address
   - **Phone**: Contact number
   - **Date of Birth**: Student's DOB
   - **Gender**: Male/Female/Other
   - **Department**: Select department
   - **Intake**: Select intake/batch
4. Click **"Create Student"**
5. System will automatically create user account

**Notes:**
- Student ID must be unique
- Email will be used for login
- Temporary password will be generated

#### Editing a Student

1. Go to **Students** list
2. Find the student
3. Click **"Edit"** button
4. Modify information
5. Click **"Update"**

#### Deleting a Student

1. Go to **Students** list
2. Find the student
3. Click **"Delete"** button
4. Confirm deletion
5. **Warning**: This will also delete:
   - Student's attendance records
   - Course enrollments
   - User account

---

### Managing Lecturers

#### Creating a New Lecturer

1. Navigate to **Lecturers** section
2. Click **"Add Lecturer"**
3. Fill in the form:
   - **Lecturer ID**: Unique identifier
   - **First Name**: Lecturer's first name
   - **Last Name**: Lecturer's last name
   - **Email**: Lecturer's email
   - **Phone**: Contact number
   - **Department/Faculty**: Select faculty
   - **Specialization**: Area of expertise
4. Click **"Create Lecturer"**

#### Assigning Courses to Lecturers

**Method 1: During Course Creation**
- When creating a course, select the lecturer from dropdown

**Method 2: Editing Existing Course**
1. Go to **Courses**
2. Find the course
3. Click **"Edit"**
4. Change **"Lecturer"** dropdown
5. Click **"Update"**

---

### Managing Courses

#### Creating a New Course

1. Navigate to **Courses**
2. Click **"Add Course"**
3. Fill in course details:
   - **Course Code**: e.g., CS101
   - **Course Name**: e.g., Introduction to Programming
   - **Lecturer**: Select from dropdown
   - **Semester**: e.g., Semester 1
   - **Academic Year**: e.g., 2025/2026
   - **Credits**: Number of credits
   - **Description**: Course description
4. Click **"Create Course"**

#### Archiving a Course

1. Go to **Courses** list
2. Find the course
3. Click **"Archive"**
4. Archived courses don't appear in active lists but data is preserved

#### Deleting a Course

⚠️ **Warning**: Only delete if course has no attendance sessions
1. Click **"Delete"** on course
2. Confirm deletion
3. If course has sessions, it will be archived instead

---

### Managing Enrollments

#### Enrolling Students in Courses

1. Navigate to **Enrollments**
2. Click **"New Enrollment"**
3. Select **Student**
4. Select **Course**
5. Set **Status**: Active/Inactive
6. Click **"Enroll"**

**Bulk Enrollment:**
1. Navigate to **Enrollments**
2. Click **"Bulk Enroll"**
3. Select **Course**
4. Select multiple **Students** (hold Ctrl/Cmd)
5. Click **"Enroll Selected"**

#### Managing Enrollment Status

**Active**: Student can attend and record attendance  
**Inactive**: Student cannot attend (but records preserved)  
**Withdrawn**: Student withdrew from course

To change status:
1. Find enrollment in list
2. Click **"Change Status"**
3. Select new status
4. Click **"Update"**

---

### Viewing Reports

1. Navigate to **Reports**
2. Select report type:
   - **Course Summary**: Overview of course attendance
   - **Student Details**: Individual student report
   - **Session Details**: Specific session report
3. Set filters (date range, course, etc.)
4. Click **"Generate Report"**
5. View report on screen
6. Click **"Print"** to print report

---

### Viewing Analytics

1. Navigate to **Analytics**
2. Select course from dropdown
3. View:
   - **Overall Statistics**: Key metrics
   - **Attendance Trends**: Line chart showing trends
   - **Performance Distribution**: Student performance breakdown
   - **At-Risk Students**: Students needing intervention
   - **Engagement Metrics**: Punctuality and participation
   - **Best Performance Times**: Optimal session times
4. Click lightbulb icon (💡) on at-risk students for recommendations

---

## 👨‍🏫 Lecturer User Guide

### Lecturer Dashboard

After logging in as lecturer, you'll see:
- **My Courses**: Courses assigned to you
- **Statistics**: Course count, students, sessions
- **Recent Sessions**: Latest attendance sessions
- **Quick Actions**: Create new session

### Managing Your Courses

#### Viewing Course Details

1. Click on a course card in sidebar OR
2. Click **"View"** on course in dashboard
3. You'll see:
   - Enrolled students
   - Course statistics
   - Session history
   - Attendance overview

---

### Creating Attendance Sessions

#### Step 1: Navigate to Create Session

**Method 1**: From dashboard
- Click **"Create New Session"** button

**Method 2**: From course dashboard
- Click on course
- Click **"Create Session"**

#### Step 2: Fill Session Details

1. **Select Course**: Choose course (if not pre-selected)
2. **Session Name**: e.g., "Week 1 - Introduction"
3. **Session Date**: Select date
4. **Start Time**: When session starts
5. **End Time**: When session ends
6. **Location**: e.g., "Room 301"
7. **Description**: Optional notes

#### Step 3: Generate QR Code

1. Click **"Create Session"**
2. System generates unique QR code
3. QR code is displayed on screen

#### Step 4: Display QR Code

**Option 1: Project on Screen**
- Display QR code page on projector
- Students scan with their phones

**Option 2: Print**
- Click **"Print QR Code"**
- Print and show to students

**Option 3: Share Link**
- Copy session link
- Share via WhatsApp/Email
- Students can access and scan

---

### Managing Active Sessions

#### Viewing Live Attendance

1. Go to **Sessions** → **Active Sessions**
2. Click on session
3. You'll see:
   - QR code (still scannable)
   - List of students who checked in
   - Check-in times
   - Live updates (auto-refreshes)

#### Closing a Session

**Automatic**: Session closes at end time

**Manual** (close early):
1. Go to active session
2. Click **"Close Session"**
3. Confirm
4. QR code stops working immediately

---

### Viewing Session History

1. Navigate to **Sessions** → **All Sessions**
2. You'll see list of all sessions
3. Click **"View"** to see details:
   - Attendance list
   - Absentee list
   - Statistics
   - Check-in times

### Deleting a Session

⚠️ **Warning**: This deletes all attendance records for that session!

1. Go to **Sessions** list
2. Find session to delete
3. Click **"Delete"** button
4. Confirm deletion
5. Session and all attendance records removed

---

### Viewing Course Reports

1. From course dashboard, click **"Reports"**
2. Select report type
3. Set date range
4. Click **"Generate"**
5. View or print report

### Using Analytics

1. Click **"Analytics"** in sidebar or quick actions
2. Select your course
3. Review:
   - **At-Risk Students**: Students with low attendance
   - **Trends**: Is attendance improving or declining?
   - **Best Times**: When do students attend most?
4. Take action:
   - Contact at-risk students
   - Schedule important sessions at best times
   - Implement recommendations

---

## 🎓 Student User Guide

### Student Dashboard

After logging in as student, you'll see:
- **My Courses**: Courses you're enrolled in
- **Attendance Statistics**: Your attendance percentage
- **Recent Attendance**: Latest check-ins
- **Active Sessions**: Sessions you can join now

### Enrolling in Courses

#### Method 1: Self-Enrollment (if enabled)

1. Navigate to **"Available Courses"**
2. Browse courses
3. Click **"Enroll"** on desired course
4. Confirm enrollment

#### Method 2: Admin Enrollment

- Your admin will enroll you in courses
- Enrolled courses appear automatically on dashboard

---

### Marking Attendance via QR Code

#### Step 1: Access Session

**When lecturer displays QR code:**

1. Click **"Scan QR"** from dashboard OR
2. Navigate to **"Attendance"** → **"Scan QR"**

#### Step 2: Allow Camera Access

1. Browser will ask for camera permission
2. Click **"Allow"**
3. Camera feed appears

#### Step 3: Scan QR Code

1. Point your phone camera at QR code
2. System automatically detects and extracts token
3. Wait for detection (usually 1-2 seconds)

#### Step 4: Confirm Attendance

1. Token displays in text box
2. Click **"Mark Attendance"**
3. You'll see success message
4. Attendance recorded!

**Alternative**: Manual Token Entry
- If QR scan doesn't work
- Type token manually (if lecturer provides it)
- Click **"Mark Attendance"**

---

### Viewing Your Attendance History

1. Navigate to **"Attendance"** → **"History"**
2. Select course (or view all)
3. You'll see:
   - All sessions
   - Which ones you attended (✅)
   - Which ones you missed (❌)
   - Check-in times
   - Attendance percentage

### Viewing Course Details

1. Click on course card
2. You'll see:
   - **Course Information**: Lecturer, schedule, credits
   - **Your Statistics**: Attendance percentage
   - **Session List**: All sessions
   - **Upcoming Sessions**: Future sessions

---

## 🔧 Common Tasks

### Resetting a Password

**For Admins** (resetting other users):
1. Navigate to user management
2. Find user
3. Click **"Reset Password"**
4. Temporary password generated
5. Provide to user

**For Users** (self-reset):
1. Click **"Forgot Password"** on login page
2. Enter email
3. Follow reset instructions
4. Check email for reset link

### Updating Profile Information

1. Click your name in top-right
2. Select **"Profile"**
3. Edit information
4. Click **"Update"**

### Downloading Reports

1. Generate report
2. Click **"Export"** or **"Download"**
3. Select format: PDF or Excel (if available)
4. File downloads to your computer

---

## ❓ FAQs

### General Questions

**Q: What browsers are supported?**  
A: Chrome, Firefox, Edge (latest versions). Chrome recommended.

**Q: Can I access from my phone?**  
A: Yes! The system is mobile-responsive.

**Q: Is internet required?**  
A: Yes, an active internet connection is required.

**Q: How secure is my data?**  
A: Very secure. We use industry-standard encryption and security practices.

---

### Student FAQs

**Q: Why can't I scan the QR code?**  
A: 
- Ensure camera permissions allowed
- Use HTTPS (not HTTP) for camera access
- Try manual token entry
- Ensure QR code is clear and well-lit

**Q: Can I mark attendance after session ends?**  
A: No. Attendance must be marked during active session time.

**Q: I forgot to mark attendance. What do I do?**  
A: Contact your lecturer. They may be able to manually add you.

**Q: Can I mark attendance for someone else?**  
A: No. Each QR scan is linked to your account. This would be considered fraud.

**Q: Why does it say "Already marked"?**  
A: You've already marked attendance for this session. Duplicate entries not allowed.

---

### Lecturer FAQs

**Q: Can I edit attendance after session closes?**  
A: Yes, admins can edit. Contact admin if correction needed.

**Q: How long should I display QR code?**  
A: Recommended 5-10 minutes at session start.

**Q: Can I create sessions in advance?**  
A: Yes! You can schedule future sessions.

**Q: What if no students appear in my course?**  
A: Check that students are enrolled. Contact admin for enrollments.

**Q: Can I download attendance data?**  
A: Yes, via Reports section. Export to Excel (if enabled).

---

### Admin FAQs

**Q: How do I bulk import students?**  
A: Use bulk enrollment feature or import via database (advanced).

**Q: Can I recover deleted data?**  
A: Only if database backups exist. Be careful with deletions!

**Q: How often should I backup data?**  
A: Daily recommended, especially during active academic periods.

**Q: Can I customize the system?**  
A: Yes, but requires technical knowledge. See developer documentation.

---

## 💡 Tips & Best Practices

### For Lecturers

**✅ DO:**
- Create sessions 5 minutes before class
- Display QR code prominently
- Close sessions after attendance window
- Review analytics weekly
- Contact at-risk students early
- Use best performance times for important sessions

**❌ DON'T:**
- Leave sessions open indefinitely
- Share QR codes outside class
- Delete sessions unless absolutely necessary
- Ignore at-risk student notifications

---

### For Students

**✅ DO:**
- Mark attendance at start of session
- Bring charged device with camera
- Allow camera permissions
- Check attendance history regularly
- Contact lecturer if you notice errors

**❌ DON'T:**
- Share QR codes with absent students
- Try to mark attendance remotely (off-campus)
- Wait until last minute to scan
- Miss sessions without valid reason

---

### For Admins

**✅ DO:**
- Regular database backups
- Monitor system logs
- Keep software updated
- Train users properly
- Review analytics for system health
- Archive old data periodically

**❌ DON'T:**
- Delete data without backups
- Share admin credentials
- Ignore security warnings
- Skip updates

---

## 🔍 Troubleshooting

### Login Issues

**Problem**: "Invalid username or password"  
**Solutions**:
- Check email spelling
- Verify password (check Caps Lock)
- Try password reset
- Contact admin

**Problem**: "Account locked"  
**Solutions**:
- Wait 15 minutes
- Contact admin to unlock

---

### QR Code Scanning Issues

**Problem**: Camera not working  
**Solutions**:
- Allow camera permission in browser
- Use HTTPS (not HTTP)
- Try different browser
- Use manual token entry

**Problem**: "Invalid token"  
**Solutions**:
- Ensure token copied correctly
- Check session is still active
- Verify you're enrolled in course
- Try scanning again

**Problem**: QR code blurry  
**Solutions**:
- Increase screen brightness
- Move closer/further from screen
- Clean phone camera lens
- Reduce screen glare

---

### Performance Issues

**Problem**: Pages loading slowly  
**Solutions**:
- Check internet connection
- Clear browser cache
- Try different browser
- Report to admin if persistent

**Problem**: Charts not displaying  
**Solutions**:
- Ensure JavaScript enabled
- Check internet (Chart.js from CDN)
- Clear cache and refresh
- Try different browser

---

### Data Issues

**Problem**: Attendance not recorded  
**Solutions**:
- Check you're enrolled in course
- Verify session was active
- Contact lecturer
- Check attendance history

**Problem**: Wrong statistics  
**Solutions**:
- Refresh page
- Check date filters
- Clear browser cache
- Report to admin

---

## 📞 Getting Help

### Support Channels

**For Technical Issues:**
- Email: support@unisiraj.edu.my
- Help Desk: [Location]
- Phone: [Number]

**For Academic Issues:**
- Contact your lecturer
- Department office
- Academic advisor

**Documentation:**
- User Manual (this document)
- Video Tutorials: [Link]
- FAQ Page: [Link]

---

## 📱 Mobile App Guide

### Downloading the App (if available)

**Android:**
1. Open Google Play Store
2. Search "UniSIRAJ Attendance"
3. Click "Install"

**iOS:**
1. Open App Store
2. Search "UniSIRAJ Attendance"
3. Click "Get"

### Using Mobile App

**Advantages:**
- Faster QR scanning
- Push notifications
- Offline mode (limited)
- Better camera integration

**Features:**
- Same functionality as web
- Optimized for mobile
- Native camera access

---

## 🎯 Quick Reference Cards

### Student Quick Reference

```
┌─────────────────────────────────────┐
│    MARKING ATTENDANCE (QUICK)       │
├─────────────────────────────────────┤
│ 1. Click "Scan QR" on dashboard     │
│ 2. Allow camera access              │
│ 3. Point at QR code                 │
│ 4. Wait for auto-detection          │
│ 5. Click "Mark Attendance"          │
│ 6. See success message ✓            │
└─────────────────────────────────────┘
```

### Lecturer Quick Reference

```
┌─────────────────────────────────────┐
│    CREATING SESSION (QUICK)         │
├─────────────────────────────────────┤
│ 1. Click "Create New Session"       │
│ 2. Select course                    │
│ 3. Fill session details             │
│ 4. Click "Create Session"           │
│ 5. Display QR code on screen        │
│ 6. Monitor live attendance          │
│ 7. Close session when done          │
└─────────────────────────────────────┘
```

---

## 🏆 System Tips

### Keyboard Shortcuts

- `Ctrl + K`: Quick search
- `Ctrl + H`: Go to home/dashboard
- `Esc`: Close modal
- `Enter`: Submit form (when focused)

### Browser Tips

- Bookmark dashboard for quick access
- Save password in browser (secure devices only)
- Enable notifications for updates
- Use latest browser version

---

## 📊 Understanding Your Statistics

### Attendance Percentage

```
Percentage = (Sessions Attended / Total Sessions) × 100
```

**Grading Guide:**
- 90-100%: Excellent ⭐⭐⭐⭐⭐
- 75-89%: Good ⭐⭐⭐⭐
- 50-74%: Fair ⭐⭐⭐
- Below 50%: Poor ⭐

### Risk Levels (Analytics)

**Critical (Red)**: Immediate attention needed  
**High (Orange)**: Schedule meeting  
**Medium (Blue)**: Monitor closely  
**Low (Gray)**: On track

---

## ✅ Best Practices Summary

### General

- Keep login credentials secure
- Log out after use (public computers)
- Update profile information
- Check system regularly
- Report issues promptly

### For Attendance

- Mark attendance on time
- Verify attendance recorded
- Check history periodically
- Maintain good attendance rate
- Communicate absences

---

**UniSIRAJ Automated Attendance System**  
**User Manual v1.0**  
**Last Updated**: August 7, 2026

**For additional help, contact your system administrator or visit the help center.** 📞
