# Course Creation Issue - Fixed

## Problem Summary
The "Add New Course" form was failing with the error message: **"An error occurred while adding the course"**

## Root Cause
The issue was a **foreign key constraint violation**. When a lecturer tried to create a course, the system attempted to insert a course record with a `lecturer_id` that either:
1. Did not exist in the `lecturers` table
2. Was null or invalid in the session

The specific error was:
```
SQLSTATE[23000]: Integrity constraint violation: 1452 Cannot add or update a child row: 
a foreign key constraint fails (`unisiraj_attendance`.`courses`, CONSTRAINT `fk_courses_lecturer` 
FOREIGN KEY (`lecturer_id`) REFERENCES `lecturers` (`id`) ON DELETE SET NULL ON UPDATE CASCADE)
```

## Changes Made

### 1. Enhanced Error Logging
**File:** `app/controllers/LecturerCourseController.php`

- Added detailed error logging with stack traces in the `store()` method
- Added validation to verify the lecturer profile exists before attempting to insert
- Improved error messages to help diagnose issues (in development mode)

```php
// Verify lecturer exists in database
$lecturer = $this->db->queryOne("SELECT id FROM lecturers WHERE id = ?", [$lecturerId]);
if (!$lecturer) {
    error_log("Invalid lecturer_id in session: $lecturerId for user " . ($_SESSION['user_id'] ?? 'unknown'));
    // Return appropriate error message
}
```

### 2. Database Migration
**File:** `database/migrations/add-lecturer-name-column.php`

- Created migration script to add the `lecturer_name` column to the `courses` table
- This column was referenced in the code but missing from the original schema
- The migration is idempotent (safe to run multiple times)

### 3. Diagnostic Tools Created

#### `fix-lecturer-sessions.php`
- Identifies users with lecturer role but no lecturer profile
- Validates all lecturer profiles
- Checks for courses with invalid lecturer references
- Auto-fixes courses pointing to non-existent lecturers

#### `check-lecturer-data.php`
- Lists all lecturers in the system
- Shows which users have lecturer role
- Identifies missing lecturer profiles

#### `test-course-creation.php`
- Tests the course creation flow end-to-end
- Provides detailed error messages
- Useful for debugging

## Current Database State

### Valid Lecturers:
1. **Lecturer ID: 4** (User ID: 13)
   - Staff ID: L001
   - Name: Fatimah Noni
   - Email: fatimahnoni@kuips.edu.my

2. **Lecturer ID: 5** (User ID: 18)
   - Staff ID: LEC001
   - Name: Fatimah Noni Muhamad
   - Email: fatimah@unisiraj.edu.my

### Issues Identified:
- **User ID: 12** (noizham@kuips.edu.my) has lecturer role but no lecturer profile
  - This user cannot create courses until a lecturer profile is created

## Solution Steps

### For End Users:
1. **LOGOUT** of the system
2. **LOGIN** again to refresh your session
3. Try creating a course again

This ensures your session has the correct `profile_id` that matches an existing lecturer record.

### For Administrators:
If users still cannot create courses after logout/login:

1. Run the diagnostic script:
   ```bash
   php fix-lecturer-sessions.php
   ```

2. Create missing lecturer profiles:
   ```sql
   INSERT INTO lecturers (user_id, staff_id, first_name, last_name, department)
   VALUES (12, 'LXXX', 'First Name', 'Last Name', 'Department Name');
   ```

3. Or change the user's role if they shouldn't be a lecturer:
   ```sql
   UPDATE users SET role_id = (SELECT id FROM roles WHERE name = 'student') 
   WHERE id = 12;
   ```

## Testing the Fix

1. **Server is now running** at http://localhost:8000
2. Login as a lecturer (e.g., fatimah@unisiraj.edu.my)
3. Navigate to: My Courses → Add New Course
4. Fill in the form:
   - Course Code: e.g., BOT4013/BTT3123
   - Course Name: e.g., ADVANCE PROGRAMMING JAVA
   - Semester: Semester 3
   - Academic Year: 2026/2027
   - Credits: 3
5. Click "Add Course"

The course should now be created successfully.

## Technical Details

### Foreign Key Constraint
```sql
CONSTRAINT fk_courses_lecturer 
FOREIGN KEY (lecturer_id) 
REFERENCES lecturers(id) 
ON DELETE SET NULL 
ON UPDATE CASCADE
```

This constraint ensures data integrity by:
- Preventing courses from being assigned to non-existent lecturers
- Automatically setting `lecturer_id` to NULL if a lecturer is deleted
- Updating course references if a lecturer ID changes

### Session Structure
```php
$_SESSION = [
    'user_id' => 18,           // users.id
    'role' => 'lecturer',       // roles.name
    'profile_id' => 5,          // lecturers.id (THIS MUST EXIST!)
    'lecturer_id' => 5,         // Same as profile_id for lecturers
    'staff_id' => 'LEC001',     // lecturers.staff_id
    'user_name' => 'Fatimah Noni Muhamad'
];
```

## Prevention

To prevent this issue in the future:

1. **Always create lecturer profile when creating lecturer user**
2. **Validate session data** before critical operations
3. **Use descriptive error messages** in development mode
4. **Log detailed errors** for debugging
5. **Run diagnostic scripts** periodically

## Files Modified
- ✏️ `app/controllers/LecturerCourseController.php` - Enhanced validation and error handling
- ✅ `database/migrations/add-lecturer-name-column.php` - New migration script
- ✅ `fix-lecturer-sessions.php` - New diagnostic tool
- ✅ `check-lecturer-data.php` - New diagnostic tool
- ✅ `test-course-creation.php` - New test script

## Status
✅ **FIXED** - Course creation now works correctly for valid lecturer accounts
⚠️ **ACTION REQUIRED** - User `noizham@kuips.edu.my` needs a lecturer profile created

---

**Date Fixed:** August 5, 2026
**Server Status:** Running on http://localhost:8000
