# Student Course Dashboard Feature

## Overview
Students can now view detailed attendance information for each enrolled course through a dedicated course dashboard. This provides comprehensive insights into their attendance patterns, session history, and performance metrics for individual courses.

## Features Implemented

### 1. Course Dashboard Access
**Route**: `/student/course/dashboard?id={course_id}`

**Access Points**:
- Click on course code in the main student dashboard courses table
- Click "View Details" button in the Actions column
- Direct navigation via URL with course ID parameter

### 2. Course Dashboard Components

#### A. Course Header
- **Breadcrumb navigation**: Student Dashboard → Course Code
- **Course information**:
  - Course code and name
  - Semester and academic year
  - Lecturer name and email (clickable mailto link)
- **Quick action button**: Scan QR Code (pulse animation)

#### B. Active Sessions Alert
- Prominent alert banner when there are active sessions available
- Shows count of active sessions
- Direct link to QR code scanner
- Dismissible alert

#### C. Statistics Cards (4 Cards)
1. **Total Sessions**
   - Count of all sessions created for the course
   - Info color scheme (blue gradient)

2. **Attended Sessions**
   - Count of sessions student marked attendance
   - Success color scheme (green gradient)

3. **Missed Sessions**
   - Count of sessions student was absent
   - Danger color (red) if > 0, secondary (gray) if 0

4. **Attendance Rate**
   - Percentage calculation: (Attended / Total) × 100
   - Dynamic color coding:
     - Green (≥75%): Excellent with smile emoji
     - Yellow (50-74%): Fair with neutral emoji
     - Red (<50%): Low with frown emoji

#### D. Session List (Main Content)

**Active Sessions Section** (if any):
- Highlighted in green
- Shows session name, date, time, and expiration
- "Mark Attendance" button with link to QR scanner
- Visible only when active sessions exist

**All Sessions Table**:
- Comprehensive list of all course sessions
- Columns:
  - Session name
  - Date and time (formatted)
  - Status badge (Present/Active/Missed/Upcoming)
  - Attendance time (if marked)
- Color-coded status badges:
  - **Present**: Green with checkmark
  - **Active**: Yellow with broadcast icon + "Scan Now"
  - **Missed**: Red with X icon
  - **Upcoming**: Gray with clock icon
- Empty state message if no sessions exist

#### E. Sidebar (Charts & Analytics)

**1. Attendance Summary (Donut Chart)**
- Visual breakdown of session statuses
- Legend with counts for:
  - Present (green)
  - Missed (red)
  - Active (yellow, if any)
  - Upcoming (gray, if any)
- Interactive tooltip on hover

**2. Monthly Attendance Trend (Line Chart)**
- Shows attendance rate percentage by month
- Displays last 6 months of data
- Filled area chart with smooth curves
- Tooltip shows: "X/Y sessions (Z%)"
- Empty if no monthly data

**3. Weekly Attendance Pattern (Bar Chart)**
- Shows attendance rate by day of week
- Helps identify which days student attends most/least
- Useful for understanding attendance habits
- Empty if no weekly pattern data

**4. Quick Actions Card**
- Scan QR Code button (primary action)
- Back to Dashboard button

### 3. Backend Logic

#### Controller: `StudentDashboardController::courseDashboard()`

**Validation**:
1. Checks student authentication
2. Validates course ID parameter
3. Verifies student enrollment in the course
4. Ensures enrollment status is "active"

**Data Fetching**:
- Course information with lecturer details
- All session records with attendance status
- Monthly attendance trend (last 6 months)
- Weekly attendance pattern

**Session Status Logic**:
```
IF attendance record exists → Status: "present"
ELSE IF session is active AND not expired → Status: "active"
ELSE IF session expired AND no attendance → Status: "missed"
ELSE → Status: "upcoming"
```

#### Database Queries

**1. Enrollment Verification Query**:
```sql
SELECT e.*, c.course_code, c.course_name, c.semester, c.academic_year,
       CONCAT(l.first_name, ' ', l.last_name) as lecturer_name,
       l.email as lecturer_email
FROM enrollments e
INNER JOIN courses c ON e.course_id = c.id
LEFT JOIN lecturers l ON c.lecturer_id = l.id
WHERE e.student_id = ? AND e.course_id = ? AND e.status = 'active'
```

**2. All Sessions Query**:
```sql
SELECT ats.id, ats.session_name, ats.session_date, ats.start_time, 
       ats.end_time, ats.expires_at, ats.is_active,
       ar.id as attendance_id, ar.attendance_time,
       CASE 
           WHEN ar.id IS NOT NULL THEN 'present'
           WHEN ats.is_active = 1 AND ats.expires_at > NOW() THEN 'active'
           WHEN ats.expires_at < NOW() THEN 'missed'
           ELSE 'upcoming'
       END as status
FROM attendance_sessions ats
LEFT JOIN attendance_records ar ON ats.id = ar.session_id AND ar.student_id = ?
WHERE ats.course_id = ?
ORDER BY ats.session_date DESC, ats.start_time DESC
```

**3. Monthly Trend Query**:
```sql
SELECT DATE_FORMAT(ats.session_date, '%Y-%m') as month,
       COUNT(DISTINCT ats.id) as total_sessions,
       COUNT(DISTINCT ar.id) as attended_sessions
FROM attendance_sessions ats
LEFT JOIN attendance_records ar ON ats.id = ar.session_id AND ar.student_id = ?
WHERE ats.course_id = ?
GROUP BY DATE_FORMAT(ats.session_date, '%Y-%m')
ORDER BY month DESC
LIMIT 6
```

**4. Weekly Pattern Query**:
```sql
SELECT DAYNAME(ats.session_date) as day_name,
       DAYOFWEEK(ats.session_date) as day_number,
       COUNT(DISTINCT ats.id) as total_sessions,
       COUNT(DISTINCT ar.id) as attended_sessions
FROM attendance_sessions ats
LEFT JOIN attendance_records ar ON ats.id = ar.session_id AND ar.student_id = ?
WHERE ats.course_id = ?
GROUP BY DAYNAME(ats.session_date), DAYOFWEEK(ats.session_date)
ORDER BY day_number
```

### 4. Frontend Components

#### Chart.js Integration
- **Donut Chart**: Session distribution
- **Line Chart**: Monthly attendance trend with filled area
- **Bar Chart**: Weekly attendance pattern

#### Responsive Design
- Bootstrap 5 grid system
- Mobile-friendly layout
- Collapsible sections on small screens
- Touch-friendly buttons and interactions

#### CSS Animations
- Hover effects on cards
- Pulse animation on primary action button
- Smooth transitions on charts
- Icon animations for active sessions

## File Changes

### New Files Created
1. **`app/views/student/course-dashboard.php`**
   - Complete course dashboard view with charts and tables
   - 545 lines of HTML, PHP, and JavaScript

2. **`public/css/student-dashboard.css`**
   - Custom styling for student dashboard and course dashboard
   - Stat cards, animations, responsive design

3. **`STUDENT-COURSE-DASHBOARD-FEATURE.md`**
   - This documentation file

### Modified Files
1. **`app/controllers/Student/DashboardController.php`**
   - Added `courseDashboard()` method (lines ~48-90)
   - Added `getCourseStats()` private method (lines ~92-200)

2. **`app/views/student/dashboard.php`**
   - Made course codes clickable (linked to course dashboard)
   - Added "Actions" column with "View Details" button
   - Updated table header

3. **`routes/web.php`**
   - Added route: `/student/course/dashboard` → `Student\DashboardController::courseDashboard`

## Security Features
- **Authentication check**: Requires student role
- **Enrollment verification**: Ensures student is enrolled in requested course
- **Active enrollment check**: Only shows data for active enrollments
- **SQL injection prevention**: Parameterized queries
- **XSS protection**: Output escaping with `e()` function

## User Experience Enhancements
1. **Breadcrumb navigation**: Easy navigation back to main dashboard
2. **Color-coded status**: Immediate visual understanding of attendance status
3. **Interactive charts**: Hover tooltips for detailed information
4. **Empty states**: Helpful messages when no data available
5. **Quick actions**: Always accessible scan button
6. **Responsive alerts**: Prominent active session notifications
7. **Smooth animations**: Professional and engaging UI

## Testing Checklist

### Functional Testing
- [ ] Navigate to course dashboard from main dashboard
- [ ] Verify course information displays correctly
- [ ] Check statistics cards show accurate counts
- [ ] Confirm attendance rate calculation is correct
- [ ] Test session status badges (Present/Active/Missed/Upcoming)
- [ ] Verify attendance time displays for present sessions
- [ ] Test active session alert appears when sessions are active
- [ ] Check charts render with correct data
- [ ] Test "Back to Dashboard" navigation
- [ ] Test "Scan QR Code" button links

### Security Testing
- [ ] Try accessing course dashboard without login (should redirect)
- [ ] Try accessing course with invalid ID (should show error)
- [ ] Try accessing course not enrolled in (should show error)
- [ ] Verify SQL injection attempts are prevented
- [ ] Check XSS attempts are escaped

### UI/UX Testing
- [ ] Test responsive design on mobile devices
- [ ] Verify charts scale properly on different screen sizes
- [ ] Check color contrast for accessibility
- [ ] Test hover effects on cards and buttons
- [ ] Verify pulse animation on scan button
- [ ] Check empty states display properly

### Browser Testing
- [ ] Test in Chrome
- [ ] Test in Firefox
- [ ] Test in Safari
- [ ] Test in Edge

## Benefits for Students
1. **Detailed insights**: See attendance patterns and trends
2. **Performance tracking**: Monitor attendance rate over time
3. **Session history**: Review all past and upcoming sessions
4. **Identify gaps**: See which sessions were missed
5. **Quick action**: Easy access to mark attendance for active sessions
6. **Visual feedback**: Charts make data easy to understand
7. **Lecturer contact**: Direct access to lecturer email

## Future Enhancements (Optional)
- Add "Download Report" button (PDF export)
- Add calendar view of sessions
- Add attendance goal setting
- Add email notifications for missed sessions
- Add comparison with class average
- Add attendance streak tracking
- Add prediction of final attendance rate

## Status
✅ **COMPLETED** - Student course dashboard feature fully implemented and ready for testing

## How to Use
1. **As a Student**:
   - Login to the system
   - Navigate to Student Dashboard
   - Click on any course code or "View Details" button
   - View detailed attendance information for that course
   - Use charts to understand attendance patterns
   - Click "Scan QR Code" to mark attendance for active sessions

2. **Testing with Sample Data**:
   - Ensure database has:
     - Active student enrollment
     - Course with sessions
     - Some attendance records marked
   - Navigate to: `/student/course/dashboard?id=1` (replace 1 with valid course ID)
