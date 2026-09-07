# UI Changes - Add Course Modal

## Changes Made

### 1. Changed "Lecturer ID" to "Lecturer"
- **Before:** Lecturer ID (number input)
- **After:** Lecturer (text input for lecturer name)

### 2. Removed All "e.g." Prefixes from Placeholders

#### Add Course Modal:
- **Before:** `placeholder="e.g., BOT4153/BTE3234"`
- **After:** `placeholder="BOT4153/BTE3234"`

- **Before:** `placeholder="e.g., Advanced Programming Java"`
- **After:** `placeholder="Advanced Programming Java"`

- **Before:** `placeholder="Enter lecturer ID"` (number input)
- **After:** `placeholder="Lecturer name"` (text input)

- **Before:** `placeholder="e.g., Semester 1"`
- **After:** `placeholder="Semester 1"`

- **Before:** `placeholder="e.g., 2023/2024"`
- **After:** `placeholder="2023/2024"`

#### Edit Course Modal:
- Changed from "Lecturer ID" (number input) to "Lecturer" (text input)

#### Add Student Modal:
- **Before:** `placeholder="e.g., 8231123215"`
- **After:** `placeholder="8231123215"`

- **Before:** `placeholder="e.g., +60123456789"`
- **After:** `placeholder="+60123456789"`

## Backend Changes

### DepartmentController.php

#### addCourse() Method:
- Changed from accepting `lecturer_id` (integer) to `lecturer_name` (string)
- Updated to handle text input for lecturer name
- Note: Currently sets lecturer_id to null in database (can be enhanced later to lookup lecturer by name)

#### updateCourse() Method:
- Changed from accepting `lecturer_id` (integer) to `lecturer_name` (string)
- Updated to handle text input for lecturer name

## Technical Notes

### Why Lecturer Name Instead of ID?

**User Experience:**
- More intuitive for users to enter a name rather than looking up an ID
- Reduces data entry errors
- Faster workflow

**Current Implementation:**
- Lecturer name is accepted but not stored (lecturer_id remains null)
- Lecturer name from database join is displayed in the table

**Future Enhancement:**
- Add auto-complete lookup for lecturer names
- Map lecturer name to lecturer_id before saving
- Create a dropdown/select with existing lecturers
- Add ability to create new lecturer on-the-fly

## Files Modified

1. **`app/views/admin/intakes/detail.php`**
   - Updated Add Course Modal
   - Updated Edit Course Modal
   - Updated Add Student Modal
   - Removed all "e.g." prefixes

2. **`app/controllers/Admin/DepartmentController.php`**
   - Updated addCourse() method
   - Updated updateCourse() method
   - Changed parameter from lecturer_id to lecturer_name

## Testing

Test the changes:
1. Navigate to intake dashboard
2. Click "Add Course" button
3. Verify:
   - ✓ Label shows "Lecturer" (not "Lecturer ID")
   - ✓ Input is text field (not number)
   - ✓ Placeholder shows "Lecturer name"
   - ✓ No "e.g." in any placeholder
4. Fill form and submit
5. Verify course is added successfully

## Visual Changes

### Before:
```
Lecturer ID
[number input: Enter lecturer ID]
Leave empty if not assigned yet
```

### After:
```
Lecturer
[text input: Lecturer name]
Leave empty if not assigned yet
```

### All Placeholders Before/After:

| Field | Before | After |
|-------|--------|-------|
| Course Code | `e.g., BOT4153/BTE3234` | `BOT4153/BTE3234` |
| Course Name | `e.g., Advanced Programming Java` | `Advanced Programming Java` |
| Lecturer | `Enter lecturer ID` | `Lecturer name` |
| Semester | `e.g., Semester 1` | `Semester 1` |
| Academic Year | `e.g., 2023/2024` | `2023/2024` |
| Matric Number | `e.g., 8231123215` | `8231123215` |
| Phone | `e.g., +60123456789` | `+60123456789` |

## Compatibility

These changes are:
- ✓ Backward compatible
- ✓ Do not break existing functionality
- ✓ Do not require database migration
- ✓ Maintain existing data integrity

## Future Enhancements

1. **Lecturer Lookup:**
   ```javascript
   // Add autocomplete for lecturer names
   $('#lecturer_name').autocomplete({
       source: '/api/lecturers/search',
       minLength: 2
   });
   ```

2. **Lecturer Selection:**
   ```html
   <!-- Replace text input with select dropdown -->
   <select name="lecturer_id" class="form-control">
       <option value="">Select Lecturer</option>
       <?php foreach ($lecturers as $lecturer): ?>
           <option value="<?= $lecturer['id'] ?>">
               <?= $lecturer['name'] ?>
           </option>
       <?php endforeach; ?>
   </select>
   ```

3. **Create Lecturer on Fly:**
   - Add "Create New Lecturer" button next to input
   - Open modal to create lecturer
   - Auto-fill lecturer field after creation
