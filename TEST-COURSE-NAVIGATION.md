# Test: Course Navigation Fix

## What Was Changed

### 1. Aggressive Cache Busting
- Added `Clear-Site-Data` header
- Added timestamp to URL: `&_t=<?= time() ?>`
- Force reload on every click

### 2. Visual Debug Info
Development mode now shows:
```
Debug Info: Course ID: 12 | Course Code: BOT4013 | Loaded at: 22:45:30
```

### 3. Course ID Badge
Course header now shows: `ID: 12` badge

### 4. JavaScript Force Reload
Every course link click now:
- Prevents default
- Adds timestamp to URL
- Forces fresh page load

## How to Test

### Step 1: Clear Browser Cache
1. Press `Ctrl + Shift + Delete`
2. Select "All time"
3. Check "Cached images and files"
4. Click "Clear data"

### Step 2: Hard Refresh
1. Go to http://localhost:8000
2. Press `Ctrl + Shift + R` (hard refresh)
3. Login as lecturer

### Step 3: Test Navigation
1. Click on **BOT4013/BTT3123** (Java course)
2. **Check:** Debug info shows correct Course ID
3. **Check:** Page header shows correct course code
4. **Check:** Course name matches
5. Click on **BOT4423/BTT3134** (Software course)
6. **Check:** Debug info updates to new Course ID
7. **Check:** Page header changes
8. **Check:** All data updates

### Step 4: Check URL
When you click a course, URL should look like:
```
http://localhost:8000/lecturer/courses/dashboard?id=12&_t=1785940990
```

The `_t` parameter is a timestamp that changes every time.

### Step 5: Check Server Logs
Look at the terminal running PHP server. You should see:
```
Loading course dashboard for course_id: 12, lecturer_id: 5
Course loaded: BOT4013/BTT3123 (ID: 12)
```

## Expected Results

✅ Debug alert shows correct Course ID
✅ Course code in header matches clicked course
✅ Course name matches clicked course  
✅ Statistics update for new course
✅ No cached/frozen data
✅ URL includes timestamp parameter

## If Still Not Working

### Test 1: Check Database
```sql
SELECT id, course_code, course_name FROM courses 
WHERE lecturer_id = 5 AND archived = 0
ORDER BY course_code;
```

Verify the course IDs match what you see in the sidebar.

### Test 2: Check Browser Console
1. Open DevTools (F12)
2. Go to Console tab
3. Look for any JavaScript errors
4. Navigate between courses
5. Check for error messages

### Test 3: Check Network Tab
1. Open DevTools (F12)
2. Go to Network tab
3. Click a course
4. Look for the request to `/lecturer/courses/dashboard?id=X&_t=...`
5. Check Response Preview
6. Verify HTML contains correct course data

### Test 4: Disable JavaScript
1. Open DevTools (F12)
2. Press `Ctrl + Shift + P`
3. Type "Disable JavaScript"
4. Select it
5. Try navigating courses
6. Check if it works without JavaScript

If it works without JavaScript, the problem is in the JS code.
If it doesn't work, the problem is server-side.

## Debug Checklist

- [ ] Cleared browser cache
- [ ] Hard refresh (Ctrl+Shift+R)
- [ ] Can see debug alert on page
- [ ] Course ID in debug matches clicked course
- [ ] URL has `&_t=` timestamp
- [ ] Server logs show correct course_id
- [ ] No JavaScript errors in console
- [ ] Network tab shows 200 response
- [ ] Response preview has correct HTML

## Server Status

Check these files were updated:
- [x] `app/controllers/LecturerCourseController.php`
- [x] `app/views/lecturer/course-dashboard.php`
- [x] `app/views/layouts/lecturer-sidebar.php`
- [x] `app/views/layouts/header.php`

## Quick Fix Commands

If you need to completely clear all caches:

### Clear PHP OpCache (if using)
```php
<?php opcache_reset(); ?>
```

### Restart PHP Server
```bash
# Stop current server (Ctrl+C in terminal)
# Start again:
php -S localhost:8000 -t public
```

### Clear All Browser Data
- Chrome: `chrome://settings/clearBrowserData`
- Firefox: `about:preferences#privacy`
- Edge: `edge://settings/clearBrowserData`

---

**Test Now:** Please try navigating between courses and let me know:
1. What Course ID shows in the debug alert?
2. What course code shows in the header?
3. What does the URL look like?

This will help identify if it's still a cache issue or something else!
