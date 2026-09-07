# Apply Lecturer Name Column Update

## Overview
To make the lecturer name display correctly in the intake dashboard, you need to add a new column to the database.

## Steps to Apply

### Option 1: Using phpMyAdmin (Easiest)

1. **Open phpMyAdmin:**
   - Go to `http://localhost/phpmyadmin`
   - Or use your MySQL management tool

2. **Select Database:**
   - Click on `unisiraj_attendance` database

3. **Run SQL:**
   - Click on "SQL" tab at the top
   - Copy and paste this SQL:

```sql
USE unisiraj_attendance;

ALTER TABLE courses 
ADD COLUMN lecturer_name VARCHAR(255) NULL AFTER lecturer_id;

UPDATE courses c
LEFT JOIN lecturers l ON c.lecturer_id = l.id
SET c.lecturer_name = CONCAT(l.first_name, ' ', l.last_name)
WHERE c.lecturer_id IS NOT NULL;
```

4. **Click "Go" button**

5. **Verify:**
   - Click on "Structure" tab
   - Confirm you see the new `lecturer_name` column in the courses table

### Option 2: Using MySQL Command Line

1. **Open Command Prompt/Terminal**

2. **Connect to MySQL:**
```bash
mysql -u root -p
```

3. **Run the SQL file:**
```bash
source "c:\Users\mohan\OneDrive\Desktop\Attendance System\database\add-lecturer-name-column.sql"
```

Or copy-paste the SQL directly:
```sql
USE unisiraj_attendance;

ALTER TABLE courses 
ADD COLUMN lecturer_name VARCHAR(255) NULL AFTER lecturer_id;

UPDATE courses c
LEFT JOIN lecturers l ON c.lecturer_id = l.id
SET c.lecturer_name = CONCAT(l.first_name, ' ', l.last_name)
WHERE c.lecturer_id IS NOT NULL;
```

4. **Exit MySQL:**
```sql
exit;
```

### Option 3: Direct File Execution

1. **Open the SQL file:**
   - Navigate to: `c:\Users\mohan\OneDrive\Desktop\Attendance System\database\`
   - Open `add-lecturer-name-column.sql`

2. **Copy the contents**

3. **Execute in your MySQL client**

## What This Does

1. **Adds a new column** `lecturer_name` to the `courses` table
   - Type: VARCHAR(255)
   - Nullable: YES
   - Position: After `lecturer_id` column

2. **Migrates existing data:**
   - Copies lecturer names from the `lecturers` table
   - Updates all existing courses that have a `lecturer_id`
   - Concatenates first_name and last_name

## After Applying

Once the database is updated:

1. **Test Add Course:**
   - Go to intake dashboard
   - Click "Add Course"
   - Enter lecturer name in the "Lecturer" field
   - Submit the form
   - **Expected:** Lecturer name shows in the table (not "Not Assigned")

2. **Test Edit Course:**
   - Click edit on an existing course
   - Modify the lecturer name
   - Save
   - **Expected:** Updated name displays correctly

## Troubleshooting

### Error: "Column already exists"
This means the column was already added. You can skip this step.

### Error: "Access denied"
You need appropriate MySQL privileges. Try logging in as root:
```bash
mysql -u root -p
```

### Verification Query
To check if the column exists:
```sql
USE unisiraj_attendance;
DESCRIBE courses;
```

Look for `lecturer_name` in the list of columns.

### Check Existing Data
To see courses with lecturer names:
```sql
SELECT id, course_code, course_name, lecturer_id, lecturer_name 
FROM courses 
LIMIT 10;
```

## Summary of All Changes

### Database:
- ✅ Added `lecturer_name` column to `courses` table

### Code:
- ✅ Updated Course model create() method
- ✅ Updated Course model update() method
- ✅ Updated Course model getAll() method (uses COALESCE)
- ✅ Updated DepartmentController addCourse() method
- ✅ Updated DepartmentController updateCourse() method
- ✅ Updated intake detail view (removed all placeholders)
- ✅ Changed "Lecturer ID" to "Lecturer" in forms

## Before vs After

### Before:
- Lecturer ID: (number input)
- Saves to: lecturer_id (foreign key)
- Displays: "Not Assigned" (if no lecturer_id)

### After:
- Lecturer: (text input)
- Saves to: lecturer_name (text field)
- Displays: Actual lecturer name entered by user
