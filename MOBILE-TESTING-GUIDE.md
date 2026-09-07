# Mobile Testing Guide - E2E QR Code Attendance

## 🌐 Current Setup

### Backend Server
- **Status:** ✅ Running
- **Local URL:** http://localhost:8000
- **Process:** PHP Development Server

### Cloudflare Tunnel
- **Status:** ✅ Active
- **Public URL:** https://pas-facing-review-hundred.trycloudflare.com
- **Process:** Cloudflared tunnel

## 📱 E2E Testing Flow

### Phase 1: Lecturer Creates Session (Desktop/Laptop)

1. **Open Browser on Computer**
   ```
   https://pas-facing-review-hundred.trycloudflare.com
   ```

2. **Login as Lecturer**
   - Email: `fatimah@unisiraj.edu.my`
   - Password: `Admin@123`

3. **Navigate to Sessions**
   - Click "Attendance Sessions" in sidebar
   - OR go directly: `https://pas-facing-review-hundred.trycloudflare.com/lecturer/sessions`

4. **Create New Session**
   - Click "Create New Session" button
   - Select a course (e.g., BOT4423/BTT4134 - Computer Systems)
   - Session name: "Mobile Testing Session"
   - Session date: Today's date
   - Start time: Current time
   - Duration: 30 minutes
   - Click "Create Session"

5. **View Session & QR Code**
   - You'll be redirected to the session view page
   - QR code will be displayed
   - **Important:** The QR code should now contain the tunnel URL, not localhost

6. **Verify QR Code URL** (Optional)
   - Right-click the QR code image
   - Copy image address
   - It should look like:
   ```
   https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=https%3A%2F%2Fpas-facing-review-hundred.trycloudflare.com%2Fstudent%2Fattendance%2Fscan%3Ftoken%3D...
   ```
   - Notice the encoded URL contains the tunnel domain, not localhost

### Phase 2: Student Scans QR Code (Mobile Phone)

7. **Open Camera or QR Scanner on Phone**
   - Use built-in camera app (iOS/Android)
   - OR use any QR code scanner app

8. **Scan the QR Code from Computer Screen**
   - Point phone camera at the QR code
   - Wait for notification/link to appear

9. **Tap the Link**
   - Should open in mobile browser
   - URL should be: `https://pas-facing-review-hundred.trycloudflare.com/student/attendance/scan?token=...`

10. **Login as Student (if not logged in)**
    - Email: `ahmed@student.unisiraj.edu.my`
    - Password: `Admin@123`
    - OR use any other student credentials:
      - `ali@student.unisiraj.edu.my`
      - `fatima@student.unisiraj.edu.my`
      - `hassan@student.unisiraj.edu.my`
      - `zainab@student.unisiraj.edu.my`

11. **Verify Session Info**
    - Should see course name
    - Should see session name
    - Should see lecturer name
    - Should see "Mark Attendance" button

12. **Mark Attendance**
    - Click "Mark Attendance" button
    - Should see success message
    - Should see confirmation that attendance was recorded

### Phase 3: Lecturer Monitors Attendance (Desktop)

13. **Refresh Session Page on Computer**
    - Go back to the session view page
    - Refresh the page (F5 or Ctrl+R)

14. **Verify Attendance Record**
    - Should see the student's name in attendance list
    - Should see attendance time
    - Counter should show: `1 / [total enrolled]`
    - Percentage should update

15. **Test Multiple Students** (Optional)
    - Repeat steps 7-12 with different student accounts
    - Each student should be able to mark attendance
    - Attendance list should update with each new record

## ✅ Success Criteria

### Must Work:
- [ ] Login page loads via tunnel URL
- [ ] Lecturer can access dashboard
- [ ] Lecturer can create attendance session
- [ ] QR code is generated and displayed
- [ ] QR code contains tunnel URL (not localhost)
- [ ] Mobile device can scan QR code
- [ ] QR code redirects to tunnel URL
- [ ] Student can login from mobile
- [ ] Student can see session details
- [ ] Student can mark attendance
- [ ] Attendance is recorded in database
- [ ] Lecturer can see attendance records
- [ ] Attendance count updates correctly

### Should Work:
- [ ] All navigation links use tunnel URL
- [ ] All assets (CSS/JS/images) load correctly
- [ ] Forms submit to tunnel URL
- [ ] Redirects maintain tunnel URL
- [ ] Session cookies work across tunnel
- [ ] Live attendance updates (if implemented)

## 🐛 Troubleshooting

### Issue: QR Code Still Shows Localhost
**Solution:**
1. Clear browser cache
2. Create a new session
3. Check config/config.php was updated
4. Verify BASE_URL is using dynamic detection

### Issue: Mobile Cannot Access Tunnel URL
**Solution:**
1. Check if cloudflared is still running
2. Verify tunnel URL is still active
3. Try accessing tunnel URL directly from mobile browser first
4. Check mobile has internet connection

### Issue: Student Already Marked Attendance
**Solution:**
1. Use a different student account
2. OR manually delete the attendance record from database
3. OR create a new session

### Issue: Session Expired
**Solution:**
1. Create new session with longer duration
2. OR extend session duration in database

### Issue: Can't Login as Student
**Solution:**
1. Verify student account exists
2. Check password is `Admin@123`
3. Try resetting password from setup-test-users.php

## 📊 Test Results Template

```
Date: [Date]
Time: [Time]
Tester: [Name]

✅ Desktop - Lecturer Session Creation
✅ QR Code Generation with Tunnel URL
✅ Mobile - QR Code Scanning
✅ Mobile - Student Login
✅ Mobile - Attendance Marking
✅ Desktop - Attendance Verification

Issues Found:
- [List any issues]

Notes:
- [Additional observations]
```

## 🔗 Quick Links

### Desktop (Lecturer)
- Login: https://pas-facing-review-hundred.trycloudflare.com/login
- Dashboard: https://pas-facing-review-hundred.trycloudflare.com/lecturer/dashboard
- Sessions: https://pas-facing-review-hundred.trycloudflare.com/lecturer/sessions
- Create Session: https://pas-facing-review-hundred.trycloudflare.com/lecturer/sessions/create

### Mobile (Student)
- Login: https://pas-facing-review-hundred.trycloudflare.com/login
- Dashboard: https://pas-facing-review-hundred.trycloudflare.com/student/dashboard
- Enrollment: https://pas-facing-review-hundred.trycloudflare.com/student/enrollment

## 📝 Test Credentials

### Lecturer
```
Email: fatimah@unisiraj.edu.my
Password: Admin@123
```

### Students
```
ahmed@student.unisiraj.edu.my / Admin@123
ali@student.unisiraj.edu.my / Admin@123
fatima@student.unisiraj.edu.my / Admin@123
hassan@student.unisiraj.edu.my / Admin@123
zainab@student.unisiraj.edu.my / Admin@123
```

### Admin (if needed)
```
Email: admin@unisiraj.edu.my
Password: Admin@123
```

---

**Ready for mobile testing!** 📱✨

**Current Tunnel:** https://pas-facing-review-hundred.trycloudflare.com
**Tunnel Status:** Active until manually stopped
**Server Status:** Running
