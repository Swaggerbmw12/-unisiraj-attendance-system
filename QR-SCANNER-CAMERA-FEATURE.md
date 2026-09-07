# QR Code Camera Scanner Feature

## Overview
Implemented live camera-based QR code scanning for student attendance using the html5-qrcode library. Students can now point their mobile device camera at the lecturer's QR code to automatically mark attendance.

## Implementation

### Technology Stack
- **Library:** html5-qrcode v2.3.8
- **Source:** https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js
- **API:** HTML5 getUserMedia API for camera access
- **Browser Support:** Modern browsers (Chrome, Firefox, Safari, Edge)

### Features Implemented

✅ **Live Camera Scanning**
- Real-time QR code detection
- Uses device's back camera by default (environment facing)
- Automatic focus and exposure adjustment

✅ **User-Friendly Interface**
- Clear "Start Camera" button
- Visual feedback when scanner is active
- "Stop Camera" button for manual control
- Smooth transitions and animations

✅ **Smart Token Extraction**
- Handles full URLs: `https://domain.com/student/attendance/scan?token=XXX`
- Handles query strings: `token=XXX`
- Handles raw tokens: `XXX`

✅ **Auto-Submission**
- Automatically submits attendance after QR detection
- Shows "Verifying..." status during submission
- Seamless user experience

✅ **Fallback Options**
- Manual token entry still available
- Works if camera access is denied
- Works if QR scanning fails

✅ **Security & Privacy**
- Camera only activates when user clicks "Start Camera"
- Camera stops immediately after successful scan
- Proper cleanup on page unload
- No video recording or storage

## User Flow

### 1. Initial State
```
┌─────────────────────────────────┐
│  📷 Camera Icon (100px)         │
│  "Click below to start camera"  │
│  [Start Camera] Button          │
└─────────────────────────────────┘
```

### 2. Scanner Active
```
┌─────────────────────────────────┐
│  📹 Live Camera Feed            │
│  Blue border with targeting box │
│  [Stop Camera] Button below     │
└─────────────────────────────────┘
```

### 3. QR Detected
```
┌─────────────────────────────────┐
│  ✓ Scanner stops automatically  │
│  Token filled in form           │
│  "Verifying..." message         │
│  Auto-submits to server         │
└─────────────────────────────────┘
```

## Code Structure

### JavaScript Functions

**`startScanner()`**
- Creates Html5Qrcode instance
- Configures scanner settings (FPS, QR box size)
- Requests camera permission
- Handles errors gracefully

**`stopScanner()`**
- Stops camera stream
- Cleans up Html5Qrcode instance
- Resets UI to initial state

**`onScanSuccess(decodedText, decodedResult)`**
- Extracts token from various formats
- Fills form field
- Auto-submits attendance
- Handles success/error cases

**`onScanError(errorMessage)`**
- Silently handles scanning errors
- Doesn't spam console (QR not in view is normal)

### Scanner Configuration

```javascript
const config = {
    fps: 10,                          // Frames per second
    qrbox: { width: 250, height: 250 }, // Targeting box size
    aspectRatio: 1.0                   // Square aspect ratio
};
```

### Camera Selection
```javascript
{ facingMode: "environment" }  // Back camera (preferred for scanning)
```

## Browser Permissions

### Permission Request
When user clicks "Start Camera", browser shows:
```
┌──────────────────────────────────────┐
│ https://domain.com wants to:         │
│ • Use your camera                    │
│                                      │
│ [Block] [Allow]                      │
└──────────────────────────────────────┘
```

### Permission States

**Granted** ✅
- Camera activates
- Scanner starts
- User can scan QR codes

**Denied** ❌
- Alert shown: "Unable to access camera..."
- Suggests manual entry
- Scanner returns to initial state

**Prompt** ⏳
- Browser asks user for permission
- User can allow or block
- System waits for response

## Mobile Browser Support

### iOS (Safari/Chrome)
✅ Works on iOS 11.3+
✅ Requires HTTPS (except localhost)
✅ Uses back camera by default
⚠️ May prompt for permission on each page load

### Android (Chrome/Firefox)
✅ Works on Android 5.0+
✅ Excellent QR detection performance
✅ Remembers permission choice
✅ Smooth camera switching

### Desktop Browsers
✅ Chrome - Full support
✅ Firefox - Full support
✅ Edge - Full support
✅ Safari - macOS 11+ required

## HTTPS Requirement

⚠️ **Important:** Camera access requires HTTPS in production

**Works:**
- ✅ `https://production-site.com`
- ✅ `https://tunnel.trycloudflare.com`
- ✅ `http://localhost:8000` (development exception)
- ✅ `http://127.0.0.1:8000` (development exception)

**Doesn't Work:**
- ❌ `http://production-site.com` (not secure)
- ❌ `http://192.168.x.x:8000` (not localhost)

## Testing Guide

### Desktop Testing
1. Open student scan page
2. Click "Start Camera"
3. Display QR code on another device/screen
4. Hold device camera toward QR code
5. Should auto-detect and submit

### Mobile Testing
1. Access via HTTPS URL (tunnel or production)
2. Login as student
3. Navigate to scan page
4. Tap "Start Camera"
5. Grant camera permission
6. Point at lecturer's QR code
7. Should detect and auto-submit
8. Verify attendance recorded

### Permission Testing
1. Block camera permission
2. Try starting scanner
3. Should show alert
4. Manual entry should still work

### Token Format Testing
Test with different QR code formats:
```
Format 1: https://domain.com/student/attendance/scan?token=abc123
Format 2: ?token=abc123
Format 3: abc123
```

All formats should work correctly.

## Error Handling

### Camera Not Available
```javascript
alert("Unable to access camera. Please check camera permissions or try manual entry.");
```

### Invalid QR Code
```javascript
alert("Invalid QR code. Please try again or use manual entry.");
// Scanner restarts automatically
```

### Permission Denied
- Shows alert message
- Returns to initial state
- Manual entry remains available

### No Back Camera
- Falls back to front camera
- Still functional for scanning
- May need to mirror display

## Performance Optimization

### FPS Setting
- Set to 10 FPS (good balance)
- Lower = better battery, slower detection
- Higher = faster detection, more battery usage

### QR Box Size
- 250x250px targeting box
- Good for mobile screens
- Large enough for most QR codes

### Cleanup
- Camera stops on successful scan
- Prevents battery drain
- Frees camera resource

## Security Considerations

✅ **No Video Recording**
- Camera is used only for QR detection
- No video or images are stored
- Stream stops after scan

✅ **No Data Transmission**
- QR data processed client-side
- Only token sent to server
- No camera feed transmitted

✅ **Permission Based**
- Requires explicit user permission
- Can be revoked anytime
- Browser enforced security

✅ **HTTPS Only (Production)**
- Ensures encrypted transmission
- Prevents man-in-the-middle attacks
- Browser requirement

## Future Enhancements

### Possible Improvements
- [ ] Torch/Flashlight toggle for low light
- [ ] Switch between front/back camera
- [ ] Zoom controls for distance scanning
- [ ] Scan history/cache
- [ ] Offline QR code storage
- [ ] Vibration feedback on successful scan
- [ ] Sound notification option
- [ ] QR code quality indicators

### Advanced Features
- [ ] Batch scanning (multiple codes)
- [ ] NFC support as alternative
- [ ] Bluetooth beacon detection
- [ ] GPS location verification
- [ ] Face recognition anti-spoofing
- [ ] Time-based one-time codes

## Troubleshooting

### Issue: Camera doesn't start
**Solutions:**
- Check browser permissions
- Ensure HTTPS connection (or localhost)
- Try different browser
- Check device camera works in other apps
- Clear browser cache and reload

### Issue: QR code not detected
**Solutions:**
- Ensure good lighting
- Hold device steady
- Move closer/farther from QR code
- Check QR code quality
- Try manual entry as fallback

### Issue: Wrong camera activates
**Solutions:**
- Check device has multiple cameras
- Modify `facingMode` setting
- Add camera selection UI
- Use manual camera picker

### Issue: Auto-submit doesn't work
**Solutions:**
- Check form ID matches
- Verify token extraction logic
- Check network connection
- Look for JavaScript errors in console

## Files Modified

1. `app/views/student/attendance/scan.php`
   - Added QR reader div
   - Added start/stop buttons
   - Removed "Coming Soon" placeholder
   - Added html5-qrcode library
   - Implemented scanner JavaScript
   - Added token extraction logic
   - Added auto-submission

## Dependencies

### External Libraries
```html
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
```

### Browser APIs
- `navigator.mediaDevices.getUserMedia()` - Camera access
- `URL` API - Token extraction from URLs
- `URLSearchParams` - Query string parsing

## Testing Checklist

- [ ] Camera activates on button click
- [ ] Permission prompt appears (first time)
- [ ] Back camera is used by default
- [ ] QR code is detected successfully
- [ ] Token is extracted correctly
- [ ] Form auto-fills with token
- [ ] Form auto-submits
- [ ] Attendance is recorded
- [ ] Scanner stops after successful scan
- [ ] Manual entry still works
- [ ] Stop button works
- [ ] Error messages are clear
- [ ] Works on mobile devices
- [ ] Works on desktop
- [ ] HTTPS requirement enforced
- [ ] Cleanup on page navigation

---

**Status:** ✅ Complete and Ready for Testing
**Date:** August 7, 2026
**Library:** html5-qrcode v2.3.8
**Test URL:** https://pas-facing-review-hundred.trycloudflare.com/student/attendance/scan
