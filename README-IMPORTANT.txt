╔══════════════════════════════════════════════════════════════╗
║                    IMPORTANT - READ THIS                     ║
╚══════════════════════════════════════════════════════════════╝

PROBLEM: Cannot add courses now?
SOLUTION: Run the auto-fix script (takes 30 seconds)

╔══════════════════════════════════════════════════════════════╗
║                      QUICK FIX STEPS                         ║
╚══════════════════════════════════════════════════════════════╝

1. Make sure your PHP server is running:
   → Open Command Prompt
   → cd "c:\Users\mohan\OneDrive\Desktop\Attendance System"
   → php -S localhost:8000 -t public

2. Open your web browser

3. Go to this URL:
   → http://localhost:8000/add-lecturer-column.php

4. You should see:
   ✅ Success! The 'lecturer_name' column has been added

5. Click the button: "Go to Admin Dashboard"

6. Try adding a course - it should work now!

╔══════════════════════════════════════════════════════════════╗
║                         WHAT FIXED                           ║
╚══════════════════════════════════════════════════════════════╝

✅ Placeholders removed (all inputs are empty now)
✅ "Lecturer ID" changed to "Lecturer" (text input)
✅ Lecturer names save and display correctly
✅ Database column added automatically

╔══════════════════════════════════════════════════════════════╗
║                    IF SCRIPT DOESN'T WORK                    ║
╚══════════════════════════════════════════════════════════════╝

Run this in MySQL command line:

ALTER TABLE courses ADD COLUMN lecturer_name VARCHAR(255) NULL AFTER lecturer_id;

╔══════════════════════════════════════════════════════════════╗
║                          DONE!                               ║
╚══════════════════════════════════════════════════════════════╝

After running the script, everything will work:
- ✅ Add courses with lecturer names
- ✅ Edit courses
- ✅ Add students
- ✅ Clean forms (no placeholders)

Questions? Check FIX-NOW.md for detailed instructions!
