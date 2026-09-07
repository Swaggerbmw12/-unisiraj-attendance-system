# Lecturer Dashboard - Quick Reference Guide
**UniSIRAJ Automated Attendance System**

---

## 🎯 Quick Access

**URL**: `http://localhost:8000/lecturer/dashboard`  
**Required Role**: Lecturer  
**Login**: Use lecturer credentials from database

---

## 📊 Dashboard Layout

```
┌─────────────────────────────────────────────────────────────────┐
│  LECTURER DASHBOARD                        [Create New Session] │
│  Welcome back, [Name]! [Current Date]                           │
├─────────────────────────────────────────────────────────────────┤
│  ┌──────────┐  ┌──────────┐  ┌──────────┐  ┌──────────┐       │
│  │MY COURSES│  │  TOTAL   │  │  TOTAL   │  │  AVERAGE │       │
│  │    X     │  │ STUDENTS │  │ SESSIONS │  │ATTENDANCE│       │
│  │  Active  │  │    Y     │  │    Z     │  │   W%     │       │
│  └──────────┘  └──────────┘  └──────────┘  └──────────┘       │
├──────────────────────────────────┬──────────────────────────────┤
│                                  │                              │
│  UPCOMING SESSIONS THIS WEEK     │  QUICK ACTIONS               │
│  [Shows next 7 days]             │  • Create New Session        │
│                                  │  • View All Sessions         │
│  MY COURSES TABLE                │  • View Reports              │
│  [Course details & actions]      │  • Analytics                 │
│                                  │                              │
│                                  │  RECENT SESSIONS             │
│                                  │  [Last 5 sessions]           │
│                                  │                              │
│                                  │  WEEKLY TREND CHART          │
│                                  │  [Line graph - 7 days]       │
│                                  │                              │
└──────────────────────────────────┴──────────────────────────────┘
```

---

## 🔢 Statistics Cards (Top Row)

### 1️⃣ My Courses (Purple)
```
┌────────────────┐
│ My Courses     │
│                │
│      5         │
│  Active courses│
└────────────────┘
```
- **Shows**: Number of active courses assigned to you
- **Icon**: 📚 Book
- **Action**: None (informational)

### 2️⃣ Total Students (Blue)
```
┌────────────────┐
│ Total Students │
│                │
│     120        │
│ Enrolled stud..│
└────────────────┘
```
- **Shows**: Unique students across all your courses
- **Icon**: 👥 People
- **Note**: Students in multiple courses counted once

### 3️⃣ Total Sessions (Pink)
```
┌────────────────┐
│ Total Sessions │
│                │
│      47        │
│   All time     │
└────────────────┘
```
- **Shows**: All sessions you've created
- **Icon**: 📱 QR Code
- **Includes**: Both active and closed sessions

### 4️⃣ Average Attendance (Orange)
```
┌────────────────┐
│Avg Attendance  │
│                │
│    78.5%       │
│ [2 Active Tod.]│
└────────────────┘
```
- **Shows**: Overall attendance percentage
- **Icon**: 📈 Graph
- **Badge**: Shows active sessions today (if any)
- **Colors**: 
  - Green badge: Active sessions
  - Gray: No active sessions

---

## 📅 Upcoming Sessions

```
┌─────────────────────────────────────────────┐
│  UPCOMING SESSIONS THIS WEEK                │
├─────────────────────────────────────────────┤
│  [05]  Lecture 1 - Database Design          │
│  [Jun] CS401 - Final Year Project    [14:00]│
│                                             │
│  [07]  Tutorial 3 - SQL Queries             │
│  [Jun] CS302 - Database Systems      [10:00]│
└─────────────────────────────────────────────┘
```

**Shows**:
- Sessions scheduled in next 7 days
- Date (day + month)
- Session name
- Course code and name
- Start time

**When visible**: Only if sessions scheduled

---

## 📚 My Courses Table

```
┌──────────────────────────────────────────────────────────────────┐
│ Course Code │ Course Name          │ Semester  │Students│Sessions│
├──────────────────────────────────────────────────────────────────┤
│ CS401       │ Final Year Project   │ Sem 2     │  [45]  │2 active│
│             │                      │2025/2026  │        │[Create]│
├──────────────────────────────────────────────────────────────────┤
│ CS302       │ Database Systems     │ Sem 1     │  [38]  │5 total │
│             │                      │2025/2026  │        │[Create]│
└──────────────────────────────────────────────────────────────────┘
```

**Columns**:
1. **Course Code**: Identifier (blue text)
2. **Course Name**: Full name
3. **Semester**: Term and year
4. **Students**: Enrollment count (blue badge)
5. **Sessions**: 
   - Green badge if active sessions exist
   - Gray text for total sessions
6. **Action**: "New Session" button

**Features**:
- Click "New Session" → Pre-fills course in session creation form
- Hover effect on rows
- Responsive (scrollable on mobile)

---

## ⚡ Quick Actions Panel

```
┌─────────────────────────────────┐
│ QUICK ACTIONS                   │
├─────────────────────────────────┤
│  [Create New Session]  (Large)  │
│  [View All Sessions]            │
│  [View Reports]                 │
│  [Analytics]                    │
└─────────────────────────────────┘
```

**Buttons**:
1. **Create New Session** (Primary, Large)
   - Most common action
   - Direct to session creation

2. **View All Sessions** (Success)
   - See complete session history
   - Filter and search capabilities

3. **View Reports** (Info)
   - Generate attendance reports
   - Export to PDF/Excel

4. **Analytics** (Secondary)
   - Detailed charts and insights
   - Trend analysis

---

## 🕐 Recent Sessions

```
┌─────────────────────────────────────────────┐
│ RECENT SESSIONS                   [View All]│
├─────────────────────────────────────────────┤
│ CS401                        [🟢 Active]    │
│ Lecture 5 - Final Presentation              │
│ Jun 04, 2026       [38/45] (84%) [Green]    │
├─────────────────────────────────────────────┤
│ CS302                        [⚪ Closed]    │
│ Tutorial 4 - Advanced SQL                   │
│ Jun 03, 2026       [30/38] (79%) [Green]    │
└─────────────────────────────────────────────┘
```

**Shows**: Last 5 sessions created

**Information per session**:
- Course code (bold)
- Status badge:
  - 🟢 Green "Active" with pulse animation
  - ⚪ Gray "Closed"
- Session name
- Date
- Attendance: X/Y (Z%)
- Color-coded percentage badge:
  - 🟢 Green: ≥75%
  - 🟡 Yellow: 50-74%
  - 🔴 Red: <50%

**Actions**:
- Click anywhere → View session details
- Hover → Slides right slightly

---

## 📈 Weekly Attendance Trend

```
┌─────────────────────────────────────────────┐
│ WEEKLY ATTENDANCE TREND                     │
├─────────────────────────────────────────────┤
│    120┤                                     │
│       │              ╱╲                     │
│    80 │         ╱╲  ╱  ╲                    │
│       │    ╱╲  ╱  ╲╱    ╲                   │
│    40 │   ╱  ╲╱            ╲                │
│       │  ╱                  ╲               │
│     0 └──────────────────────────────       │
│        Mon Tue Wed Thu Fri Sat Sun          │
└─────────────────────────────────────────────┘
```

**Features**:
- Line chart with filled area
- Last 7 days of data
- Hover to see exact numbers
- Smooth curve interpolation
- Responsive to container width

**When visible**: Only if sessions exist in past 7 days

---

## 🎨 Color Coding Reference

### Stat Cards
| Color | Purpose | Gradient |
|-------|---------|----------|
| 🟣 Purple | My Courses | #667eea → #764ba2 |
| 🔵 Blue | Total Students | #4facfe → #00f2fe |
| 🩷 Pink | Total Sessions | #f093fb → #f5576c |
| 🟠 Orange | Avg Attendance | #fa709a → #fee140 |

### Status Badges
| Badge | Meaning | Color |
|-------|---------|-------|
| 🟢 Active | Session accepting attendance | Green |
| ⚪ Closed | Session ended | Gray |

### Attendance Rates
| Range | Badge Color | Meaning |
|-------|-------------|---------|
| ≥75% | 🟢 Green | Excellent attendance |
| 50-74% | 🟡 Yellow | Moderate attendance |
| <50% | 🔴 Red | Needs attention |

---

## ⌨️ Keyboard Shortcuts

Currently none implemented, but planned:
- `Ctrl+N` - Create new session
- `Ctrl+S` - View all sessions
- `Ctrl+R` - View reports
- `Esc` - Close modals

---

## 📱 Mobile View

### Layout Changes
- Stat cards stack vertically (1 per row)
- Tables become horizontally scrollable
- Sidebar moves below main content
- Buttons full-width
- Reduced font sizes
- Simplified chart

### Touch Interactions
- Tap to navigate
- Swipe to scroll tables
- Pull to refresh (if implemented)

---

## 🔄 Auto-Refresh

### What Refreshes
- Active session count (every 30 seconds)
- Chart data (on page reload)
- Statistics (on page reload)

### Manual Refresh
- Click browser refresh button
- Navigate away and back
- Click "View All" and return

---

## 🆘 Troubleshooting

### No Courses Showing
**Cause**: No courses assigned to your lecturer account  
**Solution**: Contact administrator to assign courses

### No Sessions Appearing
**Cause**: Haven't created any sessions yet  
**Solution**: Click "Create New Session" or "Create Your First Session"

### Chart Not Displaying
**Cause**: No sessions in past 7 days  
**Solution**: Chart will appear after creating sessions

### Statistics Show Zero
**Cause**: No data in database  
**Solution**: Ensure courses assigned and students enrolled

---

## 🎯 Common Tasks

### Create Attendance Session
1. Click "Create New Session" (large blue button)
2. OR click "New Session" next to specific course
3. Fill in session details
4. Generate QR code
5. Share with students

### Check Recent Attendance
1. Look at "Recent Sessions" panel
2. Check percentage badges
3. Click session for detailed view

### View Course Performance
1. Find course in "My Courses" table
2. Check enrollment count
3. Check active/total sessions
4. Click "New Session" for that course

### Export Reports
1. Click "View Reports" in Quick Actions
2. Select date range and course
3. Choose format (PDF/Excel)
4. Download

---

## 💡 Tips & Best Practices

### Daily Routine
✅ Check "Active Today" count in morning  
✅ Review "Upcoming Sessions" for week  
✅ Monitor recent session attendance rates  
✅ Create sessions at least 1 day in advance  

### Attendance Management
✅ Aim for 75%+ attendance (green badge)  
✅ Investigate sessions with <50% (red badge)  
✅ Create sessions with sufficient duration  
✅ Close sessions after class ends  

### Performance Monitoring
✅ Watch weekly trend for patterns  
✅ Compare courses for consistency  
✅ Review average attendance monthly  
✅ Export reports for records  

---

## 📖 Related Pages

- **Session Management**: `/lecturer/sessions`
- **Create Session**: `/lecturer/sessions/create`
- **View Session**: `/lecturer/sessions/view?id=X`
- **Reports**: `/reports`
- **Analytics**: `/analytics`

---

## 🔗 Quick Links

### Development Files
- Controller: `app/controllers/Lecturer/DashboardController.php`
- View: `app/views/lecturer/dashboard.php`
- CSS: `public/assets/css/lecturer-dashboard.css`
- JS: `public/assets/js/lecturer-dashboard.js`

### Documentation
- Full Docs: `documentation/LECTURER-DASHBOARD.md`
- Summary: `LECTURER-DASHBOARD-SUMMARY.md`
- This Guide: `documentation/LECTURER-DASHBOARD-QUICK-REFERENCE.md`

---

**Last Updated**: August 5, 2026  
**Version**: 1.0.0  
**For**: Lecturers using UniSIRAJ Attendance System

---

**Need Help?** Contact IT Support or refer to full documentation.
