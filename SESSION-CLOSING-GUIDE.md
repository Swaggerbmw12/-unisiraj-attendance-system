# Session Closing Guide
**UniSIRAJ Automated Attendance System**

---

## 🎯 Overview

This guide explains how to close attendance sessions manually or automatically, and what happens to the session when closed.

---

## 🔴 How to Close a Session

### **Method 1: Manual Close (Before Expiration)**

**When to use:** You want to close the session before it naturally expires

**Steps:**
1. Navigate to the session view page
2. Look for the **"Close Session Now"** button (red, large)
3. Click the button
4. Confirm the action in the popup dialog
5. Session is immediately closed

**Result:**
- ✅ `is_active` set to 0 in database
- ✅ `end_time` recorded
- ✅ Students can no longer scan QR code
- ✅ Attendance is locked
- ✅ Status badge changes to "CLOSED"

### **Method 2: Mark as Closed (After Expiration)**

**When to use:** Session has expired but is still marked active in database

**Steps:**
1. Navigate to the session view page
2. You'll see a warning: "Session has expired"
3. Click **"Mark as Closed"** button (gray)
4. Confirm the action
5. Session is marked as closed in database

**Result:**
- ✅ Same as Method 1
- ✅ Cleans up expired but unclosed sessions

### **Method 3: Automatic Expiration**

**When:** Session reaches its expiration time

**What happens:**
- QR code becomes invalid automatically
- Students cannot scan anymore
- `is_active` remains 1 (until manually closed)
- Session is "soft closed" (expired but not marked)

**Note:** You should manually close or mark as closed for proper record keeping

---

## 📊 Session States

### **1. Active (Before Expiration)**

**Conditions:**
- `is_active` = 1
- Current time < `expires_at`
- QR code is valid

**Appearance:**
- Status badge: 🟢 **ACTIVE** (green, pulsing)
- Button: "Close Session Now" (red)
- Auto-refresh: Every 5 seconds

**Student Access:**
- ✅ Can scan QR code
- ✅ Can record attendance
- ✅ Appears in real-time list

### **2. Expired (After expiration time)**

**Conditions:**
- `is_active` = 1 (still marked active)
- Current time > `expires_at`
- QR code is expired

**Appearance:**
- Status badge: ⚫ **CLOSED** (gray)
- Alert: "Session has expired"
- Button: "Mark as Closed" (gray)
- No auto-refresh

**Student Access:**
- ❌ Cannot scan QR code
- ❌ QR code shows "Session expired" error
- ❌ Cannot record attendance

### **3. Manually Closed**

**Conditions:**
- `is_active` = 0
- `end_time` is set
- Closed by lecturer

**Appearance:**
- Status badge: ⚫ **CLOSED** (gray)
- Alert: "Session is closed"
- No action buttons
- No auto-refresh

**Student Access:**
- ❌ Cannot scan QR code
- ❌ QR code shows "Session closed" error
- ❌ Cannot record attendance

---

## 🔧 What Was Fixed

### **Problem:**
The close button was **inactive/not showing** because:
1. Method signature mismatch (parameter vs query string)
2. Only showed before expiration
3. No option to close expired sessions

### **Solution:**

**1. Updated `close()` method:**
```php
// OLD: public function close($id)
// NEW: public function close()
//      Gets ID from query string: $_GET['id']
```

**2. Enhanced view page:**
```php
// Show different buttons based on state:
if (is_active && not_expired) {
    // Show "Close Session Now" (red)
} else if (is_active && expired) {
    // Show "Mark as Closed" (gray)
} else {
    // Show "Session is closed" (info)
}
```

**3. Better user feedback:**
- Expiration warning displayed
- Clear button states
- Appropriate messaging

---

## 🧪 Testing the Fix

### **Test Case 1: Close Active Session**

1. **Create a new session** with 5 minutes duration
2. **Navigate** to session view page
3. **Verify** you see:
   - Status: "ACTIVE" (green, pulsing)
   - Button: "Close Session Now" (red)
4. **Click** "Close Session Now"
5. **Confirm** the dialog
6. **Expected Result:**
   - Redirected back to same page
   - Status: "CLOSED" (gray)
   - Message: "Session closed successfully"
   - Button: No close button (shows "Session is closed")
   - No auto-refresh

### **Test Case 2: Mark Expired Session**

1. **Create a session** with 1 minute duration
2. **Wait** 2 minutes (session expires)
3. **Refresh** the session view page
4. **Verify** you see:
   - Status: "CLOSED" (gray)
   - Alert: "Session has expired"
   - Button: "Mark as Closed" (gray)
5. **Click** "Mark as Closed"
6. **Confirm** the dialog
7. **Expected Result:**
   - Status: "CLOSED"
   - Message: "Session closed successfully"
   - Alert: "Session is closed"

### **Test Case 3: Already Closed Session**

1. **Open** a closed session
2. **Verify** you see:
   - Status: "CLOSED" (gray)
   - Alert: "Session is closed"
   - No action buttons (except "Back to Sessions")

---

## 📱 User Interface

### **Active Session:**
```
┌─────────────────────────────────────────┐
│ [Session Name]      [🟢 ACTIVE Badge]  │
├─────────────────────────────────────────┤
│                                         │
│ [QR CODE IMAGE]                         │
│                                         │
│ ⏰ Expires at 12:15 PM                  │
│                                         │
│ [🔴 Close Session Now]  ← RED BUTTON   │
│ [← Back to Sessions]                    │
│                                         │
└─────────────────────────────────────────┘
```

### **Expired Session:**
```
┌─────────────────────────────────────────┐
│ [Session Name]      [⚫ CLOSED Badge]   │
├─────────────────────────────────────────┤
│                                         │
│ [QR CODE IMAGE]                         │
│                                         │
│ ⚠️ Session has expired                  │
│                                         │
│ [📥 Mark as Closed]  ← GRAY BUTTON      │
│ [← Back to Sessions]                    │
│                                         │
└─────────────────────────────────────────┘
```

### **Closed Session:**
```
┌─────────────────────────────────────────┐
│ [Session Name]      [⚫ CLOSED Badge]   │
├─────────────────────────────────────────┤
│                                         │
│ [QR CODE IMAGE]                         │
│                                         │
│ ℹ️ Session is closed                    │
│                                         │
│ [← Back to Sessions]  ← ONLY THIS      │
│                                         │
└─────────────────────────────────────────┘
```

---

## 🔍 Troubleshooting

### **Problem: Button still not showing**

**Check:**
1. Is session `is_active` = 1 in database?
   ```sql
   SELECT id, is_active, expires_at FROM attendance_sessions WHERE id = X;
   ```
2. Refresh the page (Ctrl+F5)
3. Clear browser cache
4. Check server logs for errors

**Fix:**
```sql
-- Manually set session as active if needed
UPDATE attendance_sessions SET is_active = 1 WHERE id = X;
```

### **Problem: Close button does nothing**

**Possible Causes:**
1. JavaScript disabled
2. Form submission blocked
3. Server error

**Check:**
1. Open DevTools (F12) → Console
2. Look for JavaScript errors
3. Check Network tab when clicking
4. View server logs

**Fix:**
- Enable JavaScript
- Check form action URL
- Verify session ID in URL

### **Problem: "Session not found" error**

**Cause:** Session doesn't belong to logged-in lecturer

**Check:**
```sql
SELECT lecturer_id FROM attendance_sessions WHERE id = X;
-- Should match your lecturer_id
```

**Fix:**
- Login with correct lecturer account
- Verify session ownership

### **Problem: Page shows wrong status**

**Cause:** Database state doesn't match display

**Check:**
```sql
SELECT id, is_active, expires_at, end_time 
FROM attendance_sessions 
WHERE id = X;
```

**Expected:**
- Active: `is_active = 1`, `expires_at > NOW()`
- Expired: `is_active = 1`, `expires_at < NOW()`
- Closed: `is_active = 0`, `end_time` set

---

## 📊 Database Changes

### **When Session is Closed:**

**Before:**
```sql
id: 1
is_active: 1
end_time: NULL
```

**After:**
```sql
id: 1
is_active: 0
end_time: 2026-08-07 01:23:45
```

### **SQL Query:**
```sql
UPDATE attendance_sessions 
SET is_active = 0, end_time = CURRENT_TIME 
WHERE id = ?
```

---

## ⚡ Quick Reference

| Condition | Status Badge | Button | Auto-Refresh | QR Valid? |
|-----------|--------------|--------|--------------|-----------|
| Active & Not Expired | 🟢 ACTIVE | Close Now (Red) | ✅ Yes | ✅ Yes |
| Active & Expired | ⚫ CLOSED | Mark Closed (Gray) | ❌ No | ❌ No |
| Manually Closed | ⚫ CLOSED | None | ❌ No | ❌ No |

---

## 🎯 Best Practices

### **When to Close Manually:**

✅ **Do close manually:**
- When all students have arrived
- When class ends early
- When you want final attendance count
- To prevent late submissions

❌ **Don't close if:**
- Expecting late arrivals
- Session duration is appropriate
- Want to allow full time window

### **Session Duration Recommendations:**

- **5-10 minutes:** Quick attendance at class start (close manually when done)
- **15-20 minutes:** Allow for late arrivals (close manually or let expire)
- **30-60 minutes:** Lab sessions (let expire naturally)
- **90-120 minutes:** Full class periods (let expire naturally)

---

## 📝 Files Modified

1. ✏️ `app/controllers/Lecturer/SessionController.php`
   - Updated `close()` method to get ID from query string
   - Added validation and error handling

2. ✏️ `app/views/lecturer/sessions/view.php`
   - Enhanced button logic
   - Added expired session handling
   - Improved user feedback

---

## ✅ Summary

**The close button is now functional!**

**Features:**
- ✅ Works before expiration ("Close Session Now")
- ✅ Works after expiration ("Mark as Closed")
- ✅ Shows appropriate message when already closed
- ✅ Proper confirmation dialogs
- ✅ Database state updated correctly
- ✅ Clear visual feedback

**Test it now:**
1. Create a new session
2. View the session
3. Click "Close Session Now"
4. Verify it closes successfully

---

**Ready to close sessions!** 🔴

**URL to test:** http://localhost:8000/lecturer/sessions/view?id=YOUR_SESSION_ID
