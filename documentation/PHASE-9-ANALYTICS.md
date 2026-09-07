# Phase 9: Analytics Dashboard - Documentation

## Overview
Phase 9 implements an intelligent analytics dashboard with ML-inspired features for predictive insights and data-driven decision making in attendance management.

## Features Implemented

### 1. **Intelligent Analytics Dashboard**
- Multi-factor risk scoring system for student identification
- Attendance trend analysis over time
- Performance distribution visualization
- Session analysis (best performance times)
- Engagement metrics tracking
- Predictive insights generation

### 2. **ML-Inspired Risk Detection**
The system uses a sophisticated multi-factor scoring algorithm to identify at-risk students:

#### Risk Score Calculation (0-100 scale)
- **Attendance Rate (40% weight)**: Base risk from overall attendance percentage
- **Declining Trend (30% weight)**: Detects deteriorating attendance patterns
- **Recent Absences (30% weight)**: Weights recent behavior more heavily

#### Risk Levels
- **Critical (70-100)**: Immediate intervention required
- **High (50-69)**: Schedule meeting to discuss concerns
- **Medium (30-49)**: Monitor closely
- **Low (0-29)**: On track

### 3. **Analytics Components**

#### Overall Statistics
- Total sessions conducted
- Total enrolled students
- Average attendance rate
- Active students count

#### Attendance Trends
- Line chart showing attendance rates over time
- Session-by-session breakdown
- Date-based trend analysis

#### Performance Distribution
- Doughnut chart visualization
- Categories:
  - Excellent (90-100%)
  - Good (75-89%)
  - Fair (50-74%)
  - Poor (<50%)

#### At-Risk Students Table
- Student identification with risk scores
- Visual risk level indicators
- AI-generated recommendations
- Action buttons for interventions

#### Engagement Metrics
- Punctuality rate (check-in within 5 minutes)
- Average check-in delay
- Active participants tracking

#### Session Analysis
- Best performance times by day and hour
- Session count statistics
- Average attendance by time slot

### 4. **Interactive Features**
- Course selector dropdown
- Real-time chart rendering with Chart.js
- Recommendations modal with AI insights
- Print-friendly layouts
- Responsive design

## Files Created/Modified

### New Files
1. **app/controllers/AnalyticsController.php**
   - Main analytics controller
   - 10+ analytical methods
   - ML-inspired algorithms

2. **app/views/analytics/index.php**
   - Complete analytics dashboard UI
   - Chart.js integration
   - Interactive components

### Modified Files
1. **routes/web.php**
   - Added `/analytics` route
   - Added `/analytics/data` API endpoint

2. **app/views/layouts/lecturer-sidebar.php**
   - Added "Analytics Dashboard" button
   - Quick access from sidebar

## User Access

### Routes
- **Main Dashboard**: `/analytics`
- **Data API**: `/analytics/data?course_id={id}` (AJAX endpoint)

### Access Control
- **Lecturers**: Can view analytics for their assigned courses only
- **Admins**: Can view analytics for all courses

### Navigation
1. **From Lecturer Sidebar**: Click "Analytics Dashboard" button
2. **From Lecturer Dashboard**: Click "Analytics" in Quick Actions
3. **Direct URL**: Navigate to `/analytics`

## How to Use

### For Lecturers

1. **Access Analytics**
   - Click "Analytics Dashboard" from sidebar
   - Or use Quick Actions card

2. **Select Course**
   - Use dropdown to select course
   - Analytics load automatically

3. **View Insights**
   - Check AI-generated insights at top
   - Review overall statistics cards
   - Analyze attendance trends chart

4. **Identify At-Risk Students**
   - Review at-risk students table
   - Check risk scores and levels
   - Click lightbulb icon for recommendations

5. **Monitor Engagement**
   - Check punctuality rates
   - Review average check-in delays
   - Analyze active participation

6. **Optimize Scheduling**
   - Review "Best Performance Times"
   - Identify optimal days/times
   - Plan future sessions accordingly

## Technical Implementation

### Controller Methods

#### `index()`
Main dashboard page with course selection

#### `data()`
AJAX endpoint for real-time data updates

#### `generateAnalytics($courseId)`
Master method orchestrating all analytics:
- Calls 7 specialized analysis methods
- Combines results into comprehensive dataset
- Returns structured analytics array

#### `getOverallStatistics($courseId)`
Calculates aggregate statistics

#### `getAttendanceTrends($courseId)`
Session-by-session trend analysis

#### `identifyAtRiskStudents($courseId)`
ML-inspired risk detection algorithm

#### `calculateRiskScore($student, $courseId)`
Multi-factor risk scoring (0-100)

#### `getStudentTrend($studentId, $courseId)`
Individual student trend analysis

#### `getRecentAbsenceCount($studentId, $courseId, $sessionCount)`
Recent absence tracking

#### `getRiskLevel($score)`
Converts numeric score to risk category

#### `getRecommendations($student)`
Generates AI-driven intervention recommendations

#### `getPerformanceDistribution($courseId)`
Student performance bucketing

#### `getSessionAnalysis($courseId)`
Time-based performance analysis

#### `calculateEngagementMetrics($courseId)`
Punctuality and engagement calculations

#### `generateInsights($courseId, $stats, $atRiskStudents, $trends)`
Contextual insight generation

### Database Queries
All queries use positional parameters (`?`) to avoid PDO binding issues with duplicate parameters.

### Chart.js Integration
- **Version**: 4.4.0 (CDN)
- **Chart Types**: Line, Doughnut
- **Features**: Responsive, interactive, tooltips

## AI/ML Features (Without Actual ML)

### 1. Risk Scoring Algorithm
Uses weighted multi-factor analysis to simulate ML predictions:
```php
score = (100 - attendance_rate) * 0.4  // Base attendance
      + trend_penalty * 0.3             // Declining pattern
      + recent_absences * 10 (max 30)   // Recent behavior
```

### 2. Trend Detection
Analyzes recent session patterns:
- Compares first half vs second half of recent sessions
- Detects "declining", "stable_low", or "stable" trends
- Requires minimum 3 sessions for accuracy

### 3. Predictive Insights
Context-aware recommendations based on:
- Overall attendance patterns
- At-risk student counts
- Trend directions
- Historical performance

### 4. Automated Recommendations
Rule-based expert system:
- Attendance < 50%: Immediate intervention
- Attendance < 75%: Schedule meeting
- Risk > 70: Academic advising referral
- Check for personal/health issues

## Security Features

1. **Role-Based Access Control**
   - Lecturers see only their courses
   - Admins see all courses
   - Authorization checks in controller

2. **SQL Injection Prevention**
   - All queries use prepared statements
   - Positional parameters throughout

3. **XSS Protection**
   - All output escaped with `e()` helper
   - JSON encoding for JavaScript data

## Performance Considerations

1. **Efficient Queries**
   - Minimal database calls
   - Optimized JOINs and aggregations
   - Index-friendly WHERE clauses

2. **Client-Side Rendering**
   - Charts rendered by Chart.js
   - Reduces server load
   - Smooth animations

3. **AJAX API**
   - Separate endpoint for data updates
   - Allows dynamic course switching
   - JSON response format

## Future Enhancements

### Potential Additions
1. **Export Features**
   - PDF analytics reports
   - Excel data exports
   - CSV downloads

2. **Advanced ML**
   - Actual machine learning models
   - Predictive absence forecasting
   - Automated intervention triggers

3. **Email Alerts**
   - Automatic notifications for critical risk students
   - Weekly analytics digests
   - Attendance threshold alerts

4. **Comparative Analytics**
   - Course-to-course comparisons
   - Semester-over-semester trends
   - Department-wide analytics

5. **Student Self-Analytics**
   - Personal attendance dashboards
   - Peer comparisons
   - Goal setting features

## Testing Checklist

- [ ] Access analytics as lecturer
- [ ] Access analytics as admin
- [ ] Select different courses
- [ ] Verify attendance trends chart renders
- [ ] Verify performance distribution chart renders
- [ ] Check at-risk students table (if any)
- [ ] Click recommendations button
- [ ] Verify engagement metrics accuracy
- [ ] Check session analysis table
- [ ] Test responsive design on mobile
- [ ] Verify course selector functionality
- [ ] Check AJAX data endpoint
- [ ] Verify authorization (lecturer can't see other courses)

## Known Limitations

1. **Trend Analysis**: Requires minimum 3 sessions for accurate trend detection
2. **Risk Scoring**: Simplified algorithm, not true machine learning
3. **Real-time Updates**: Manual course selection required (no auto-refresh)
4. **Historical Data**: Limited to existing sessions only

## Conclusion

Phase 9 successfully implements a comprehensive analytics dashboard with ML-inspired features that provide actionable insights for improving student attendance and identifying at-risk students early. The system uses intelligent algorithms to simulate machine learning predictions while maintaining performance and security.

---

**Phase Status**: ✅ COMPLETE
**Date Completed**: August 7, 2026
**Next Phase**: Phase 10 (TBD - User feedback required)
