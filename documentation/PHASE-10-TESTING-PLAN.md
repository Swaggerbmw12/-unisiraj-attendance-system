# Phase 10: Testing & Quality Assurance Plan

## 🎯 Testing Objectives

1. **Functional Testing**: Verify all features work as intended
2. **Security Testing**: Ensure system is protected against common vulnerabilities
3. **Performance Testing**: Validate system performs efficiently
4. **Usability Testing**: Confirm user-friendly experience
5. **Compatibility Testing**: Ensure cross-browser/device support
6. **Integration Testing**: Verify modules work together seamlessly

---

## 📋 Testing Categories

### 1. Authentication & Authorization Testing
### 2. Admin Module Testing
### 3. Lecturer Module Testing
### 4. Student Module Testing
### 5. QR Attendance System Testing
### 6. Reporting Module Testing
### 7. Analytics Module Testing
### 8. Security Testing
### 9. Performance Testing
### 10. Cross-Browser Testing

---

## 🧪 Test Cases

### CATEGORY 1: Authentication & Authorization

#### TC-001: Login Functionality
**Priority**: Critical  
**Prerequisites**: Valid user accounts exist

| Test Step | Expected Result | Status |
|-----------|----------------|--------|
| 1. Navigate to /login | Login page displays | ⏳ |
| 2. Enter valid admin credentials | No errors | ⏳ |
| 3. Submit form | Redirect to /admin/dashboard | ⏳ |
| 4. Check session | User logged in, role = admin | ⏳ |

#### TC-002: Login with Invalid Credentials
| Test Step | Expected Result | Status |
|-----------|----------------|--------|
| 1. Enter invalid username | Error message displayed | ⏳ |
| 2. Enter valid username, wrong password | Error message displayed | ⏳ |
| 3. Leave fields empty | Validation errors shown | ⏳ |

#### TC-003: Role-Based Redirection
| Test Step | Expected Result | Status |
|-----------|----------------|--------|
| 1. Login as admin | Redirect to /admin/dashboard | ⏳ |
| 2. Logout, login as lecturer | Redirect to /lecturer/dashboard | ⏳ |
| 3. Logout, login as student | Redirect to /student/dashboard | ⏳ |

#### TC-004: Session Management
| Test Step | Expected Result | Status |
|-----------|----------------|--------|
| 1. Login successfully | Session created | ⏳ |
| 2. Close browser, reopen | Still logged in (if remember me) | ⏳ |
| 3. Click logout | Session destroyed, redirect to login | ⏳ |
| 4. Try accessing protected page | Redirect to login | ⏳ |

#### TC-005: Authorization Checks
| Test Step | Expected Result | Status |
|-----------|----------------|--------|
| 1. Login as student | Can't access /admin routes | ⏳ |
| 2. Login as lecturer | Can't access /admin routes | ⏳ |
| 3. Login as admin | Can access all admin routes | ⏳ |

---

### CATEGORY 2: Admin Module Testing

#### TC-006: Student Management - Create
| Test Step | Expected Result | Status |
|-----------|----------------|--------|
| 1. Navigate to /admin/students | Students list displayed | ⏳ |
| 2. Click "Add Student" | Create form displayed | ⏳ |
| 3. Fill valid data | No validation errors | ⏳ |
| 4. Submit form | Student created, success message | ⏳ |
| 5. Check database | Student record exists | ⏳ |
| 6. Check users table | User account created | ⏳ |

#### TC-007: Student Management - Validation
| Test Step | Expected Result | Status |
|-----------|----------------|--------|
| 1. Leave required fields empty | Validation errors shown | ⏳ |
| 2. Enter invalid email format | Email validation error | ⏳ |
| 3. Use duplicate student_id | Duplicate error message | ⏳ |
| 4. Enter invalid phone format | Phone validation error | ⏳ |

#### TC-008: Student Management - Edit
| Test Step | Expected Result | Status |
|-----------|----------------|--------|
| 1. Click edit on student | Edit form pre-filled | ⏳ |
| 2. Modify data | Changes saved | ⏳ |
| 3. Submit form | Success message, data updated | ⏳ |
| 4. Verify in database | Changes persisted | ⏳ |

#### TC-009: Student Management - Delete
| Test Step | Expected Result | Status |
|-----------|----------------|--------|
| 1. Click delete on student | Confirmation dialog | ⏳ |
| 2. Confirm deletion | Student deleted, success message | ⏳ |
| 3. Check database | Student record removed | ⏳ |
| 4. Check related data | Enrollments handled properly | ⏳ |

#### TC-010: Lecturer Management - CRUD
| Test Step | Expected Result | Status |
|-----------|----------------|--------|
| 1. Create new lecturer | Lecturer created successfully | ⏳ |
| 2. View lecturer list | All lecturers displayed | ⏳ |
| 3. Edit lecturer | Changes saved | ⏳ |
| 4. Delete lecturer | Lecturer removed | ⏳ |
| 5. Check user account | User created/updated correctly | ⏳ |

#### TC-011: Course Management - CRUD
| Test Step | Expected Result | Status |
|-----------|----------------|--------|
| 1. Create new course | Course created successfully | ⏳ |
| 2. Assign lecturer | Lecturer assigned correctly | ⏳ |
| 3. Edit course details | Changes saved | ⏳ |
| 4. Archive course | Course archived, not deleted | ⏳ |
| 5. Delete course | Course deleted or archived | ⏳ |

#### TC-012: Enrollment Management
| Test Step | Expected Result | Status |
|-----------|----------------|--------|
| 1. Enroll student in course | Enrollment created | ⏳ |
| 2. Check duplicate enrollment | Error message, prevented | ⏳ |
| 3. Update enrollment status | Status changed | ⏳ |
| 4. Remove enrollment | Enrollment deleted | ⏳ |

---

### CATEGORY 3: Lecturer Module Testing

#### TC-013: Lecturer Dashboard
| Test Step | Expected Result | Status |
|-----------|----------------|--------|
| 1. Login as lecturer | Dashboard displays | ⏳ |
| 2. Check statistics cards | Correct counts displayed | ⏳ |
| 3. View my courses | Only assigned courses shown | ⏳ |
| 4. Check recent sessions | Recent sessions listed | ⏳ |
| 5. View upcoming sessions | Future sessions displayed | ⏳ |

#### TC-014: Course Dashboard
| Test Step | Expected Result | Status |
|-----------|----------------|--------|
| 1. Click on a course | Course dashboard loads | ⏳ |
| 2. Check enrolled students | Student list displayed | ⏳ |
| 3. View course statistics | Correct stats shown | ⏳ |
| 4. Check sessions list | Course sessions displayed | ⏳ |

#### TC-015: Create Attendance Session
| Test Step | Expected Result | Status |
|-----------|----------------|--------|
| 1. Navigate to create session | Form displayed | ⏳ |
| 2. Select course | Course selected | ⏳ |
| 3. Fill session details | Form validates | ⏳ |
| 4. Submit form | Session created | ⏳ |
| 5. Check QR code | QR code generated and displayed | ⏳ |
| 6. Check token | Unique token created | ⏳ |
| 7. Verify database | Session record created | ⏳ |

#### TC-016: Session Management
| Test Step | Expected Result | Status |
|-----------|----------------|--------|
| 1. View active sessions | Active sessions listed | ⏳ |
| 2. View session details | Attendees list shown | ⏳ |
| 3. Monitor real-time attendance | Live updates working | ⏳ |
| 4. Close session manually | Session closed, QR expired | ⏳ |
| 5. Delete session | Session and records deleted | ⏳ |

---

### CATEGORY 4: Student Module Testing

#### TC-017: Student Dashboard
| Test Step | Expected Result | Status |
|-----------|----------------|--------|
| 1. Login as student | Dashboard displays | ⏳ |
| 2. Check enrolled courses | My courses listed | ⏳ |
| 3. View attendance stats | Correct percentages shown | ⏳ |
| 4. Check recent attendance | Recent records displayed | ⏳ |

#### TC-018: Course Enrollment
| Test Step | Expected Result | Status |
|-----------|----------------|--------|
| 1. View available courses | Courses listed | ⏳ |
| 2. Enroll in course | Enrollment successful | ⏳ |
| 3. Try duplicate enrollment | Error prevented | ⏳ |
| 4. Unenroll from course | Enrollment removed | ⏳ |

#### TC-019: QR Code Scanning
| Test Step | Expected Result | Status |
|-----------|----------------|--------|
| 1. Navigate to scan page | Camera permission requested | ⏳ |
| 2. Allow camera access | Camera feed displays | ⏳ |
| 3. Scan valid QR code | Token extracted | ⏳ |
| 4. Submit attendance | Attendance recorded | ⏳ |
| 5. Check success message | Confirmation shown | ⏳ |

#### TC-020: Attendance Verification
| Test Step | Expected Result | Status |
|-----------|----------------|--------|
| 1. Scan QR for active session | Attendance recorded | ⏳ |
| 2. Try scanning again | Duplicate prevented | ⏳ |
| 3. Scan expired QR | Error: session expired | ⏳ |
| 4. Scan invalid token | Error: invalid token | ⏳ |
| 5. Scan for unenrolled course | Error: not enrolled | ⏳ |

#### TC-021: Attendance History
| Test Step | Expected Result | Status |
|-----------|----------------|--------|
| 1. View attendance history | All records displayed | ⏳ |
| 2. Filter by course | Course-specific records | ⏳ |
| 3. Check timestamps | Correct dates/times shown | ⏳ |
| 4. View attendance percentage | Correct calculation | ⏳ |

---

### CATEGORY 5: QR Attendance System Testing

#### TC-022: QR Code Generation
| Test Step | Expected Result | Status |
|-----------|----------------|--------|
| 1. Create session | QR code generated | ⏳ |
| 2. Check QR contains token | Token embedded correctly | ⏳ |
| 3. Verify token uniqueness | Each session has unique token | ⏳ |
| 4. Check QR image quality | Clear and scannable | ⏳ |

#### TC-023: Token Validation
| Test Step | Expected Result | Status |
|-----------|----------------|--------|
| 1. Scan valid, active token | Accepted | ⏳ |
| 2. Scan expired token | Rejected with message | ⏳ |
| 3. Scan invalid token | Rejected with error | ⏳ |
| 4. Scan token for closed session | Rejected | ⏳ |

#### TC-024: Duplicate Prevention
| Test Step | Expected Result | Status |
|-----------|----------------|--------|
| 1. Student scans QR | Attendance recorded | ⏳ |
| 2. Same student scans again | Prevented, message shown | ⏳ |
| 3. Check database | Only one record exists | ⏳ |

#### TC-025: Session Expiration
| Test Step | Expected Result | Status |
|-----------|----------------|--------|
| 1. Create session with end time | Session active | ⏳ |
| 2. Wait until end time passes | Session auto-expired | ⏳ |
| 3. Try scanning | Rejected: session expired | ⏳ |
| 4. Manual close before end | Session closed immediately | ⏳ |

---

### CATEGORY 6: Reporting Module Testing

#### TC-026: Course Summary Report
| Test Step | Expected Result | Status |
|-----------|----------------|--------|
| 1. Select "Course Summary" | Form displayed | ⏳ |
| 2. Select course and dates | Parameters accepted | ⏳ |
| 3. Generate report | Report displays correctly | ⏳ |
| 4. Check session breakdown | All sessions listed | ⏳ |
| 5. Check student performance | Correct percentages | ⏳ |

#### TC-027: Student Details Report
| Test Step | Expected Result | Status |
|-----------|----------------|--------|
| 1. Select "Student Details" | Form displayed | ⏳ |
| 2. Select student and course | Parameters accepted | ⏳ |
| 3. Generate report | Individual report shown | ⏳ |
| 4. Check attendance records | All records listed | ⏳ |
| 5. Verify timestamps | Correct dates/times | ⏳ |

#### TC-028: Session Details Report
| Test Step | Expected Result | Status |
|-----------|----------------|--------|
| 1. Select "Session Details" | Form displayed | ⏳ |
| 2. Select session | Parameters accepted | ⏳ |
| 3. Generate report | Session report shown | ⏳ |
| 4. Check attendee list | All attendees listed | ⏳ |
| 5. Check absentee list | Non-attendees shown | ⏳ |

#### TC-029: Report Filtering
| Test Step | Expected Result | Status |
|-----------|----------------|--------|
| 1. Set date range | Filter applied | ⏳ |
| 2. Generate report | Only filtered data shown | ⏳ |
| 3. Clear filters | All data shown | ⏳ |
| 4. Use multiple filters | Filters work together | ⏳ |

#### TC-030: Report Authorization
| Test Step | Expected Result | Status |
|-----------|----------------|--------|
| 1. Lecturer views own course report | Allowed | ⏳ |
| 2. Lecturer views other course | Denied | ⏳ |
| 3. Admin views any course | Allowed | ⏳ |
| 4. Student tries accessing reports | Denied | ⏳ |

---

### CATEGORY 7: Analytics Module Testing

#### TC-031: Analytics Dashboard Access
| Test Step | Expected Result | Status |
|-----------|----------------|--------|
| 1. Navigate to /analytics | Dashboard loads | ⏳ |
| 2. Check course selector | Dropdown shows courses | ⏳ |
| 3. Select course | Analytics load | ⏳ |
| 4. Check statistics cards | Correct numbers displayed | ⏳ |

#### TC-032: Attendance Trends Chart
| Test Step | Expected Result | Status |
|-----------|----------------|--------|
| 1. View trends chart | Line chart renders | ⏳ |
| 2. Check data points | All sessions plotted | ⏳ |
| 3. Hover over points | Tooltips display | ⏳ |
| 4. Verify accuracy | Data matches database | ⏳ |

#### TC-033: Performance Distribution
| Test Step | Expected Result | Status |
|-----------|----------------|--------|
| 1. View distribution chart | Doughnut chart renders | ⏳ |
| 2. Check categories | 4 categories shown | ⏳ |
| 3. Verify percentages | Adds up to 100% | ⏳ |
| 4. Check student counts | Numbers correct | ⏳ |

#### TC-034: At-Risk Student Detection
| Test Step | Expected Result | Status |
|-----------|----------------|--------|
| 1. View at-risk table | Students listed | ⏳ |
| 2. Check risk scores | Scores 0-100 range | ⏳ |
| 3. Verify risk levels | Correct categorization | ⏳ |
| 4. Click recommendations | Modal opens | ⏳ |
| 5. Check recommendations | Relevant suggestions shown | ⏳ |

#### TC-035: Risk Score Calculation
| Test Step | Expected Result | Status |
|-----------|----------------|--------|
| 1. Student with 40% attendance | High risk score | ⏳ |
| 2. Student with declining trend | Score increased | ⏳ |
| 3. Student with recent absences | Score increased | ⏳ |
| 4. Student with 95% attendance | Low risk score | ⏳ |

#### TC-036: Engagement Metrics
| Test Step | Expected Result | Status |
|-----------|----------------|--------|
| 1. Check punctuality rate | Percentage displayed | ⏳ |
| 2. Check avg check-in delay | Time in minutes | ⏳ |
| 3. Check active participants | Count displayed | ⏳ |
| 4. Verify calculations | Numbers accurate | ⏳ |

#### TC-037: Session Analysis
| Test Step | Expected Result | Status |
|-----------|----------------|--------|
| 1. View best performance times | Table displayed | ⏳ |
| 2. Check day analysis | Days listed | ⏳ |
| 3. Check hour analysis | Hours listed | ⏳ |
| 4. Verify sorting | Sorted by attendance | ⏳ |

#### TC-038: AI Insights
| Test Step | Expected Result | Status |
|-----------|----------------|--------|
| 1. View insights alerts | Insights displayed | ⏳ |
| 2. Check insight relevance | Context-appropriate | ⏳ |
| 3. Verify color coding | Correct alert types | ⏳ |
| 4. Dismiss alerts | Alerts can be dismissed | ⏳ |

---

### CATEGORY 8: Security Testing

#### TC-039: SQL Injection Prevention
| Test Step | Expected Result | Status |
|-----------|----------------|--------|
| 1. Input: `' OR '1'='1` in login | Blocked, no access | ⏳ |
| 2. Input: `1; DROP TABLE users--` | Query fails safely | ⏳ |
| 3. Check all input fields | All use prepared statements | ⏳ |

#### TC-040: XSS Protection
| Test Step | Expected Result | Status |
|-----------|----------------|--------|
| 1. Input: `<script>alert('XSS')</script>` | Escaped, not executed | ⏳ |
| 2. Check output escaping | All outputs escaped | ⏳ |
| 3. Test in all form fields | XSS prevented everywhere | ⏳ |

#### TC-041: CSRF Protection
| Test Step | Expected Result | Status |
|-----------|----------------|--------|
| 1. Submit form without token | Request rejected | ⏳ |
| 2. Submit with invalid token | Request rejected | ⏳ |
| 3. Check all POST forms | All have CSRF tokens | ⏳ |

#### TC-042: Session Security
| Test Step | Expected Result | Status |
|-----------|----------------|--------|
| 1. Check session timeout | Inactive sessions expire | ⏳ |
| 2. Test session hijacking | Prevented | ⏳ |
| 3. Check session regeneration | ID regenerates on login | ⏳ |

#### TC-043: Password Security
| Test Step | Expected Result | Status |
|-----------|----------------|--------|
| 1. Check database | Passwords hashed | ⏳ |
| 2. Try default passwords | Rejected or flagged | ⏳ |
| 3. Password change | Old password required | ⏳ |

#### TC-044: File Upload Security (if applicable)
| Test Step | Expected Result | Status |
|-----------|----------------|--------|
| 1. Upload executable file | Rejected | ⏳ |
| 2. Check file type validation | Only allowed types | ⏳ |
| 3. Check file size limits | Large files rejected | ⏳ |

---

### CATEGORY 9: Performance Testing

#### TC-045: Page Load Times
| Test Step | Expected Result | Status |
|-----------|----------------|--------|
| 1. Load home page | < 2 seconds | ⏳ |
| 2. Load dashboard (any role) | < 2 seconds | ⏳ |
| 3. Load analytics | < 3 seconds | ⏳ |
| 4. Generate report | < 3 seconds | ⏳ |

#### TC-046: Database Query Performance
| Test Step | Expected Result | Status |
|-----------|----------------|--------|
| 1. Check query execution times | < 100ms average | ⏳ |
| 2. Test with large dataset | No significant slowdown | ⏳ |
| 3. Check for N+1 queries | None found | ⏳ |

#### TC-047: Chart Rendering Performance
| Test Step | Expected Result | Status |
|-----------|----------------|--------|
| 1. Load Chart.js charts | < 1 second render | ⏳ |
| 2. Test with many data points | Smooth animation | ⏳ |
| 3. Check browser memory | No memory leaks | ⏳ |

---

### CATEGORY 10: Cross-Browser Testing

#### TC-048: Chrome Compatibility
| Test Step | Expected Result | Status |
|-----------|----------------|--------|
| 1. Test all features in Chrome | Everything works | ⏳ |
| 2. Check camera access | Camera works | ⏳ |
| 3. Check charts | Charts render | ⏳ |

#### TC-049: Firefox Compatibility
| Test Step | Expected Result | Status |
|-----------|----------------|--------|
| 1. Test all features in Firefox | Everything works | ⏳ |
| 2. Check camera access | Camera works | ⏳ |
| 3. Check charts | Charts render | ⏳ |

#### TC-050: Edge Compatibility
| Test Step | Expected Result | Status |
|-----------|----------------|--------|
| 1. Test all features in Edge | Everything works | ⏳ |
| 2. Check camera access | Camera works | ⏳ |
| 3. Check charts | Charts render | ⏳ |

#### TC-051: Mobile Responsiveness
| Test Step | Expected Result | Status |
|-----------|----------------|--------|
| 1. Test on mobile viewport | Responsive layout | ⏳ |
| 2. Test navigation | Mobile menu works | ⏳ |
| 3. Test QR scanning | Camera works on mobile | ⏳ |
| 4. Test forms | Forms usable on mobile | ⏳ |

---

## 📊 Testing Progress Tracking

### Summary
- **Total Test Cases**: 51
- **Passed**: 0
- **Failed**: 0
- **In Progress**: 51
- **Blocked**: 0

### Categories Progress
- Authentication & Authorization: 0/5 (0%)
- Admin Module: 0/7 (0%)
- Lecturer Module: 0/4 (0%)
- Student Module: 0/5 (0%)
- QR Attendance System: 0/4 (0%)
- Reporting Module: 0/5 (0%)
- Analytics Module: 0/8 (0%)
- Security: 0/6 (0%)
- Performance: 0/3 (0%)
- Cross-Browser: 0/4 (0%)

---

## 🐛 Bug Tracking Template

### Bug Report Format
```
Bug ID: BUG-XXX
Title: [Short description]
Severity: Critical / High / Medium / Low
Priority: High / Medium / Low
Test Case: TC-XXX
Steps to Reproduce:
1. Step 1
2. Step 2
3. Step 3
Expected Result: [What should happen]
Actual Result: [What actually happened]
Environment: [Browser, OS, etc.]
Status: Open / In Progress / Fixed / Closed
Assigned To: [Developer name]
```

---

## ✅ Testing Completion Criteria

### Exit Criteria
- [ ] All critical test cases passed
- [ ] All high-priority bugs fixed
- [ ] All medium-priority bugs documented
- [ ] Performance benchmarks met
- [ ] Security audit passed
- [ ] Cross-browser compatibility confirmed
- [ ] Mobile responsiveness verified
- [ ] Test report completed

---

**Phase 10: Testing & Quality Assurance**  
**Status**: 🚧 IN PROGRESS  
**Start Date**: August 7, 2026
