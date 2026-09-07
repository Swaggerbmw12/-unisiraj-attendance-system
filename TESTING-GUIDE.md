# Testing Guide - Course Creation Fix

## Quick Test (For Users)

### Prerequisites
✅ Server is running at http://localhost:8000

### Test Steps

1. **Open Browser**
   - Navigate to: http://localhost:8000

2. **Login as Lecturer**
   - Email: `fatimah@unisiraj.edu.my`
   - Password: `Admin@123` (default password from schema)

3. **Navigate to Add Course**
   - Click "My Courses" in the navigation
   - Click "Add New Course" button (or use the sidebar button)

4. **Fill in Course Details**
   ```
   Course Code: TEST001
   Course Name: Test Course Creation
   Semester: Semester 3
   Academic Year: 2026/2027
   Credit Hours: 3
   Description: This is a test course to verify the fix
   ```

5. **Submit Form**
   - Click "Add Course" button
   - Watch for success message

### Expected Results
✅ Success message: "Course added successfully!"
✅ Redirected to course dashboard
✅ New course appears in "My Courses" list
✅ No error messages in the alert popup

### If It Still Fails

#### Step 1: Check Your Session
```
Your lecturer account might have an old session. Follow these steps:
1. Click "Logout" in the top right
2. Close your browser completely
3. Reopen browser and login again
4. Try creating a course again
```

#### Step 2: Run Diagnostics
Open command prompt in the project folder and run:
```bash
php fix-lecturer-sessions.php
```

This will show you:
- Your lecturer profile status
- Any database issues
- Recommended fixes

#### Step 3: Check Server Logs
After clicking "Add Course", look at the server terminal for error messages.
They will start with `[Wed Aug 05 22:XX:XX 2026]`

## Detailed Testing (For Developers)

### Test 1: Verify Database Structure
```bash
php check-lecturer-data.php
```

**Expected Output:**
- Shows 2 valid lecturers (ID: 4 and 5)
- Lists 1 orphaned user (noizham@kuips.edu.my)
- No database errors

### Test 2: Verify Session Data
After logging in, check the session by viewing the lecturer dashboard source.
Look for the lecturer ID in the sidebar course list.

**What to Check:**
- Sidebar shows "Add New Course" button
- Existing courses are listed
- Profile name shows at top: "Fatimah Noni Muhamad"

### Test 3: Test Course Creation Directly
```bash
php test-course-creation.php
```

**Expected Output:**
```
✓ Database connection established
✓ Lecturer found: Fatimah Noni Muhamad
  Lecturer ID: 5
✓ No duplicate found
✓ SUCCESS! Course created with ID: XXX
✓ Test course deleted
```

**If it fails:**
- Check the error message
- Most likely cause: Session has wrong profile_id
- Solution: Logout and login again

### Test 4: Test with Different Lecturers

#### Lecturer 1: fatimahnoni@kuips.edu.my
- Profile ID: 4
- Should work ✅

#### Lecturer 2: fatimah@unisiraj.edu.my
- Profile ID: 5
- Should work ✅

#### Lecturer 3: noizham@kuips.edu.my
- Profile ID: NONE ❌
- Will fail until profile is created

### Test 5: Verify Error Handling

Try to create a course with:
1. **Duplicate course code** - Should show: "Course code already exists"
2. **Empty required fields** - Should show validation errors
3. **Invalid session** - Should redirect to login

## API Testing (Advanced)

### Test AJAX Endpoint Directly
```javascript
// Open browser console on http://localhost:8000
// After logging in as lecturer

fetch('/lecturer/courses/store', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/x-www-form-urlencoded',
        'X-Requested-With': 'XMLHttpRequest'
    },
    body: new URLSearchParams({
        course_code: 'TEST999',
        course_name: 'Test Course via API',
        semester: 'Semester 1',
        academic_year: '2026/2027',
        credits: 3,
        description: 'Testing direct API call'
    })
})
.then(r => r.json())
.then(data => console.log(data))
.catch(err => console.error(err));
```

**Expected Response:**
```json
{
    "success": true,
    "message": "Course added successfully",
    "course_id": 123
}
```

**Error Response (if session invalid):**
```json
{
    "success": false,
    "message": "Invalid lecturer profile. Please logout and login again."
}
```

## Common Issues & Solutions

### Issue 1: "Lecturer profile not found"
**Cause:** No profile_id in session
**Solution:** Logout and login again

### Issue 2: "Invalid lecturer profile"
**Cause:** profile_id in session points to non-existent lecturer
**Solution:** 
1. Run `php fix-lecturer-sessions.php`
2. Logout and login again
3. If still fails, contact admin to create lecturer profile

### Issue 3: "Course code already exists"
**Cause:** Trying to create course with duplicate code
**Solution:** Use a different course code

### Issue 4: "An error occurred while adding the course"
**Cause:** Generic database error
**Solution:**
1. Check server logs for detailed error
2. Run `php fix-lecturer-sessions.php`
3. Verify lecturer profile exists
4. Check database connection

### Issue 5: Form submits but shows no message
**Cause:** JavaScript error or network issue
**Solution:**
1. Open browser console (F12)
2. Check for JavaScript errors
3. Check Network tab for failed requests
4. Try submitting again

## Verification Checklist

After testing, verify:

- [ ] Course appears in "My Courses" list
- [ ] Course has correct details
- [ ] Course has correct lecturer assigned
- [ ] Can view course dashboard
- [ ] Can create attendance sessions for the course
- [ ] Course appears in admin course list
- [ ] No duplicate courses created
- [ ] Error handling works correctly

## Performance Testing

### Test Load Time
```bash
# Measure page load time
curl -w "@-" -o /dev/null -s "http://localhost:8000/lecturer/courses/create" <<'EOF'
    time_namelookup:  %{time_namelookup}\n
       time_connect:  %{time_connect}\n
    time_appconnect:  %{time_appconnect}\n
      time_redirect:  %{time_redirect}\n
   time_pretransfer:  %{time_pretransfer}\n
 time_starttransfer:  %{time_starttransfer}\n
                    ----------\n
         time_total:  %{time_total}\n
EOF
```

**Expected:** < 500ms for form load

### Test Database Query Performance
Check the server logs for query times. Add this to your course creation:
```
[timestamp] Database query executed in 0.XXX seconds
```

## Security Testing

### Test CSRF Protection
Try submitting the form without a valid CSRF token:
```javascript
fetch('/lecturer/courses/store', {
    method: 'POST',
    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
    body: 'course_code=HACK001&course_name=Hacked'
})
```

**Expected:** Should fail with CSRF error or redirect

### Test Authentication
Try accessing the endpoint without logging in:
```bash
curl http://localhost:8000/lecturer/courses/store
```

**Expected:** Should redirect to login page

### Test Authorization
Login as a student, then try:
```
http://localhost:8000/lecturer/courses/create
```

**Expected:** Should show 403 Forbidden error

## Regression Testing

After this fix, verify these features still work:

- [ ] Admin can create courses
- [ ] Admin can view all courses
- [ ] Students can view enrolled courses
- [ ] Attendance sessions can be created
- [ ] Course enrollment works
- [ ] Course editing works (if implemented)
- [ ] Course deletion works (if implemented)

## Browser Compatibility

Test in multiple browsers:
- [ ] Chrome/Edge (Chromium)
- [ ] Firefox
- [ ] Safari (if available)

## Mobile Testing

Test responsive design:
- [ ] Form displays correctly on mobile
- [ ] All fields are accessible
- [ ] Buttons are clickable
- [ ] Success/error messages visible

---

**Test Status:** Ready for Testing
**Last Updated:** August 5, 2026
**Server:** http://localhost:8000
