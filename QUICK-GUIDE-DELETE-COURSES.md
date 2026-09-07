# Quick Guide: How to Delete Courses

## Method 1: From Course Dashboard

1. **Open the course** you want to delete
2. Click the **dropdown arrow** next to "Create Session" button (top right)
3. Select **"Delete Course"** (red text with trash icon)
4. **Confirm** the deletion
5. You'll be redirected to your dashboard

## Method 2: From Course List

1. Go to **Lecturer Dashboard**
2. Scroll to **"My Courses"** section
3. Find the course you want to delete
4. Click the **dropdown arrow** on the "View" button
5. Select **"Delete"** (red text)
6. **Confirm** the deletion
7. Page will reload with course removed

## What Happens When You Delete?

### Scenario 1: Course with NO attendance sessions
- ✅ Course is **permanently deleted** from database
- ✅ All enrollments are removed
- ✅ Course disappears from everywhere

### Scenario 2: Course WITH attendance sessions
- ✅ Course is **archived** (not deleted)
- ✅ Attendance records are preserved
- ✅ Course is hidden from your dashboard
- ✅ Can be restored by admin if needed

## Warning Message

When you click delete, you'll see:
```
⚠️ WARNING: Are you sure you want to delete "COURSE_CODE"?

This action cannot be undone!

Note: If the course has attendance sessions, 
it will be archived instead of deleted.
```

## Safety Features

1. **Confirmation Required** - Must click OK to proceed
2. **Ownership Check** - Can only delete your own courses
3. **Smart Logic** - Preserves data when needed
4. **AJAX Request** - No page navigation until confirmed
5. **Success Message** - Clear feedback on what happened

## Tips

- ✅ Test courses with no sessions can be deleted permanently
- ✅ Old semester courses are better archived than deleted
- ✅ Always check if you have the right course before deleting
- ✅ If unsure, ask admin to help restore if needed

## Troubleshooting

**Problem:** Delete button doesn't work
**Solution:** Make sure JavaScript is enabled, refresh the page

**Problem:** "Access denied" error
**Solution:** You can only delete courses you own

**Problem:** Want to restore deleted course
**Solution:** 
- If permanently deleted: Cannot restore
- If archived: Contact admin to unarchive

---

**Server:** http://localhost:8000  
**Need Help?** Contact system administrator
