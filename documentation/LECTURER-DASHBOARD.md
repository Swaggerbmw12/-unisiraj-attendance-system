# Lecturer Dashboard Documentation
**UniSIRAJ Automated Attendance System**

## Overview
The Lecturer Dashboard is the central hub for lecturers to manage attendance sessions, view course statistics, and monitor student attendance patterns.

---

## Features

### 1. Statistics Overview
Four key metrics displayed at the top of the dashboard:

#### My Courses
- **Description**: Total number of active courses assigned to the lecturer
- **Color**: Purple gradient
- **Icon**: Book icon
- **Details**: Shows only active courses

#### Total Students
- **Description**: Total unique students enrolled across all lecturer's courses
- **Color**: Blue gradient  
- **Icon**: People icon
- **Details**: Counts distinct students (students enrolled in multiple courses counted once)

#### Total Sessions
- **Description**: Total attendance sessions created by the lecturer
- **Color**: Pink gradient
- **Icon**: QR code icon
- **Details**: Includes both active and closed sessions from all time

#### Average Attendance
- **Description**: Average attendance rate across all sessions
- **Color**: Yellow/Orange gradient
- **Icon**: Graph icon
- **Details**: 
  - Calculated as average percentage of students present
  - Shows badge with count of active sessions today
  - Updates based on completed sessions

---

### 2. Upcoming Sessions This Week
Displays sessions scheduled for the next 7 days.

**Features**:
- **Calendar-style date display**: Shows day and month
- **Session details**: Name, course code, course name
- **Time badge**: Start time in HH:MM format
- **Chronological order**: Sorted by date and time

**When visible**: Only shown when there are upcoming sessions

---

### 3. My Courses Table
Comprehensive view of all assigned courses.

**Columns**:
- **Course Code**: Unique identifier (displayed in blue/primary color)
- **Course Name**: Full course name
- **Semester**: Current semester with academic year
- **Students**: Number of actively enrolled students (info badge)
- **Sessions**: 
  - Shows active sessions count (green badge) if any are active
  - Shows total sessions count if no active sessions
- **Action**: "New Session" button to create attendance session

**Features**:
- **Hover effects**: Row highlights on hover
- **Quick session creation**: Pre-fills course when clicking "New Session"
- **Responsive design**: Scrollable on mobile devices

**Empty State**: Shows helpful message when no courses assigned

---

### 4. Quick Actions Sidebar
Fast access to common tasks.

**Actions**:
1. **Create New Session** (Primary/Large button)
   - Direct link to session creation page
   - Prominent placement for frequent action

2. **View All Sessions** (Success outline)
   - Navigate to complete session list
   - Shows all sessions with filters

3. **View Reports** (Info outline)
   - Access attendance reports
   - Generate PDF/Excel exports

4. **Analytics** (Secondary outline)
   - View detailed analytics dashboard
   - Charts and trends

---

### 5. Recent Sessions
Shows last 5 sessions created by the lecturer.

**Information Displayed**:
- **Course code**: Bold heading
- **Status badge**: 
  - Green "Active" with broadcast icon (animated pulse)
  - Gray "Closed" for ended sessions
- **Session name**: Description/title
- **Date**: Formatted as "Mon DD, YYYY"
- **Attendance statistics**:
  - Ratio: X/Y students
  - Percentage: (X%)
  - Color-coded badge:
    - Green: ≥75% attendance
    - Yellow: 50-74% attendance
    - Red: <50% attendance

**Features**:
- **Clickable**: Each item links to session detail page
- **Hover animation**: Slides right slightly on hover
- **Empty state**: Encourages creating first session

---

### 6. Weekly Attendance Trend (Chart)
Visual representation of attendance patterns over the last 7 days.

**Chart Type**: Line chart with filled area

**Data Shown**:
- X-axis: Dates (Mon DD format)
- Y-axis: Total attendance count
- Line: Smooth curve showing trend

**Features**:
- **Interactive tooltips**: Shows exact numbers on hover
- **Responsive**: Adjusts to card width
- **Auto-refresh**: Updates when page loads
- **Only shown when data exists**: Hidden if no sessions in past 7 days

**Technology**: Chart.js 3.9.1

---

## Technical Implementation

### Controller: `LecturerDashboardController.php`
Location: `app/controllers/Lecturer/DashboardController.php`

#### Main Method: `index()`
- **Authentication**: Requires 'lecturer' role
- **Session validation**: Checks for valid profile_id
- **Data retrieval**: Calls `getDashboardStats()`
- **Rendering**: Loads view with statistics

#### Statistics Method: `getDashboardStats($lecturerId)`
Queries database for:
- Course count (active only)
- Total sessions count
- Active sessions today
- Total enrolled students (distinct)
- Average attendance rate
- Course list with enrollment data
- Recent sessions (last 5)
- Upcoming sessions (next 7 days)
- Weekly attendance trend

**Error Handling**: Returns empty arrays on exception, logs error

---

### View: `lecturer/dashboard.php`
Location: `app/views/lecturer/dashboard.php`

**Structure**:
1. Page header with welcome message and date
2. Statistics cards row (4 columns)
3. Main content row:
   - Left column (8/12): Upcoming sessions + Courses table
   - Right column (4/12): Quick actions + Recent sessions + Chart

**Responsive Breakpoints**:
- Large screens (≥992px): 4 stat cards side-by-side
- Medium screens (768-991px): 2 stat cards per row
- Small screens (<768px): Stacked vertically

---

### Styling: `lecturer-dashboard.css`
Location: `public/assets/css/lecturer-dashboard.css`

**Key Features**:
- **Gradient stat cards**: Vibrant, modern color schemes
- **Smooth transitions**: 0.3s ease on hover effects
- **Card animations**: Fade-in with stagger effect
- **Responsive typography**: Scales for mobile
- **Print-friendly**: Hides buttons, adjusts layout
- **Pulse animation**: For active session indicators

**Color Scheme**:
- Primary: Purple (#667eea to #764ba2)
- Success: Pink (#f093fb to #f5576c)
- Info: Blue (#4facfe to #00f2fe)
- Warning: Orange/Yellow (#fa709a to #fee140)

---

### JavaScript: `lecturer-dashboard.js`
Location: `public/assets/js/lecturer-dashboard.js`

**Functions**:

1. **initTooltips()**: Initialize Bootstrap tooltips
2. **initAutoRefresh()**: Auto-refresh active sessions every 30s
3. **refreshActiveSessions()**: Fetch updated session data via AJAX
4. **updateActiveSessionCount()**: Animate count updates
5. **animateValue()**: Smooth number animations
6. **initCardAnimations()**: Intersection Observer for scroll animations
7. **displayWelcomeMessage()**: Time-based greeting (morning/afternoon/evening)
8. **confirmAction()**: Confirmation dialogs
9. **copyToClipboard()**: Copy text with notification
10. **showNotification()**: Toast notifications

**Global Object**: `window.LecturerDashboard` for external access

---

## Database Queries

### Courses Count
```sql
SELECT COUNT(*) as count 
FROM courses 
WHERE lecturer_id = ? AND is_active = 1
```

### Total Students (Distinct)
```sql
SELECT COUNT(DISTINCT e.student_id) as count
FROM enrollments e
INNER JOIN courses c ON e.course_id = c.id
WHERE c.lecturer_id = ? AND e.status = 'active'
```

### Average Attendance Rate
```sql
SELECT COALESCE(AVG(attendance_rate), 0) as avg_rate
FROM (
    SELECT 
        ats.id,
        (COUNT(DISTINCT ar.student_id) / NULLIF(
            (SELECT COUNT(*) FROM enrollments 
             WHERE course_id = ats.course_id AND status = 'active'), 
        0)) * 100 as attendance_rate
    FROM attendance_sessions ats
    LEFT JOIN attendance_records ar ON ats.id = ar.session_id
    WHERE ats.lecturer_id = ?
    GROUP BY ats.id
) as rates
```

### Weekly Trend
```sql
SELECT 
    DATE(ats.session_date) as date,
    COUNT(DISTINCT ats.id) as sessions,
    COUNT(DISTINCT ar.student_id) as total_attendance
FROM attendance_sessions ats
LEFT JOIN attendance_records ar ON ats.id = ar.session_id
WHERE ats.lecturer_id = ?
AND ats.session_date >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
GROUP BY DATE(ats.session_date)
ORDER BY date
```

---

## User Flow

### Typical Workflow:
1. **Login** → Lecturer dashboard loads
2. **View statistics** → Quick overview of courses and attendance
3. **Check upcoming sessions** → See what's scheduled
4. **Create new session** → Click "Create New Session" or course-specific button
5. **Monitor recent sessions** → Check attendance rates
6. **View trends** → Analyze weekly chart

### Action Paths:
- **Create Session**: Dashboard → "Create New Session" → Session Form
- **View Session Details**: Dashboard → Recent Session → Session View Page
- **Manage Course**: Dashboard → Courses Table → Session Creation
- **Reports**: Dashboard → Quick Actions → Reports Page
- **Analytics**: Dashboard → Quick Actions → Analytics Page

---

## Security Considerations

1. **Authentication**: `requireAuth(['lecturer'])` checks role
2. **Authorization**: Only shows lecturer's own data (filtered by `lecturer_id`)
3. **SQL Injection Prevention**: Parameterized queries with `?` placeholders
4. **XSS Prevention**: Output escaping with `e()` function
5. **Session Validation**: Checks `$_SESSION['profile_id']` exists

---

## Performance Optimization

1. **Single Page Load**: All data fetched in one controller action
2. **Indexed Queries**: Database indexes on `lecturer_id`, `course_id`, etc.
3. **Limit Results**: Recent sessions limited to 5
4. **Chart Data**: Only last 7 days to reduce payload
5. **CSS/JS Minification**: Consider minifying in production
6. **Lazy Loading**: Cards animate in as they appear (Intersection Observer)

---

## Future Enhancements

### Planned Features:
- [ ] Real-time session monitoring with WebSocket
- [ ] Export dashboard as PDF report
- [ ] Customizable dashboard widgets
- [ ] Attendance prediction based on trends
- [ ] Notification center for session reminders
- [ ] Dark mode toggle
- [ ] Course comparison analytics
- [ ] Student engagement metrics
- [ ] Mobile app integration
- [ ] Voice commands for session creation

### Technical Improvements:
- [ ] API endpoint for AJAX refresh (`/api/lecturer/active-sessions`)
- [ ] Caching layer for statistics (Redis/Memcached)
- [ ] Progressive Web App (PWA) support
- [ ] Real-time updates with Server-Sent Events (SSE)
- [ ] GraphQL API for flexible data fetching

---

## Troubleshooting

### Common Issues:

#### Statistics showing zero
- **Cause**: No data in database or lecturer has no assigned courses
- **Solution**: 
  1. Check database connection
  2. Verify courses assigned to lecturer
  3. Check `lecturer_id` in session matches database

#### Chart not displaying
- **Cause**: No sessions in past 7 days or Chart.js failed to load
- **Solution**:
  1. Check network tab for CDN errors
  2. Verify sessions exist in date range
  3. Check console for JavaScript errors

#### CSS not loading
- **Cause**: Asset path incorrect or file missing
- **Solution**:
  1. Verify file exists at `public/assets/css/lecturer-dashboard.css`
  2. Check `asset()` helper function returns correct URL
  3. Clear browser cache

#### Permissions error
- **Cause**: User not logged in as lecturer or session expired
- **Solution**:
  1. Verify user has 'lecturer' role in database
  2. Check session is active
  3. Re-login if session expired

---

## Related Documentation

- [Session Management](SESSION-MANAGEMENT.md)
- [Attendance Tracking](ATTENDANCE-TRACKING.md)
- [Reports and Analytics](REPORTS-ANALYTICS.md)
- [User Authentication](AUTHENTICATION.md)
- [Database Schema](../database/schema.sql)

---

## Contact & Support

For questions or issues with the Lecturer Dashboard:
- **Email**: support@unisiraj.edu.my
- **Documentation**: `/documentation`
- **Issue Tracker**: [Internal Support System]

---

**Last Updated**: August 5, 2026  
**Version**: 1.0.0  
**Author**: Development Team
