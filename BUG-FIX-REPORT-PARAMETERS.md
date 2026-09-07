# Bug Fix: Report Generation Parameter Binding Error

## Issue
Reports failed to generate with error:
```
Exception: SQLSTATE[HY093]: Invalid parameter number
File: ReportController.php, Line: 140
```

## Root Cause
Same issue as the student stats bug - SQL queries used named parameters (`:course_id`, `:date_from`, `:date_to`) multiple times in the same query, but PDO couldn't bind them correctly when passed as an associative array.

### Example of Problematic Code
```php
// Query had multiple :course_id placeholders
$sql = "SELECT ... WHERE course_id = :course_id 
        AND (SELECT COUNT(*) FROM table WHERE course_id = :course_id)";

// Only one value passed
$params = ['course_id' => $courseId];
$stmt->execute($params); // FAILS!
```

## Solution
Changed all report generation methods from **named parameters** to **positional parameters**.

### Before (Broken)
```php
$params = ['course_id' => $courseId];
if ($dateFrom && $dateTo) {
    $params['date_from'] = $dateFrom;
    $params['date_to'] = $dateTo;
}

$sql = "WHERE course_id = :course_id 
        AND session_date BETWEEN :date_from AND :date_to";
$stmt->execute($params);
```

### After (Fixed)
```php
$params = [$courseId];
if ($dateFrom && $dateTo) {
    $params[] = $dateFrom;
    $params[] = $dateTo;
}

$sql = "WHERE course_id = ? 
        AND session_date BETWEEN ? AND ?";
$stmt->execute($params);
```

## Files Modified

### `app/controllers/ReportController.php`

**1. `generateCourseSummaryReport()` method**
- Fixed summary query (2 `:course_id` → `?`)
- Fixed sessions query (3 `:course_id` → `?`)
- Fixed students query (4 `:course_id` → `?`)
- Properly built parameter arrays for each query

**2. `generateStudentDetailsReport()` method**
- Fixed query with multiple `:course_id` placeholders
- Changed to positional parameters
- Correctly ordered parameter array

**3. `generateSessionDetailsReport()` method**
- Already mostly correct (simpler queries)
- Updated for consistency with positional parameters

## Technical Details

### Why Named Parameters Failed
When using named parameters with PDO:
- Each named placeholder in SQL (`:name`) expects a corresponding key in the params array
- If `:name` appears multiple times, PDO gets confused
- Some PDO implementations require special handling for duplicate named parameters

### Why Positional Parameters Work
With positional parameters (`?`):
- Order-based, not name-based
- Can repeat the same value easily
- Just pass values in order: `[$value1, $value2, $value1]`
- More reliable for complex queries with subqueries

### Parameter Array Building Pattern

**For simple queries:**
```php
$params = [$courseId];
if ($dateFrom && $dateTo) {
    $params[] = $dateFrom;
    $params[] = $dateTo;
}
```

**For complex queries with subqueries:**
```php
// Build params in the same order as ? placeholders in SQL
$params = [
    $courseId,           // First ?
    $courseId,           // Second ? (in subquery)
    $courseId            // Third ? (in WHERE)
];

if ($dateFrom && $dateTo) {
    $params[] = $dateFrom;  // Fourth ?
    $params[] = $dateTo;    // Fifth ?
}
```

## Testing

### Test Cases
1. **Course Summary Report**
   - ✅ Without date range
   - ✅ With date range
   - ✅ Empty results
   - ✅ Multiple sessions
   - ✅ Multiple students

2. **Student Details Report**
   - ✅ Without date range
   - ✅ With date range
   - ✅ Students with no attendance
   - ✅ Students with full attendance

3. **Session Details Report**
   - ✅ Without date range
   - ✅ With date range
   - ✅ Sessions with no attendees
   - ✅ Sessions with many attendees

### Verification Steps
1. Login as lecturer
2. Navigate to Reports
3. Select "Course Summary"
4. Choose a course
5. Set date range (optional)
6. Click "Generate Report"
7. Report should display without errors

## Impact

### Before Fix
- ❌ All reports failed to generate
- ❌ Error shown to user
- ❌ Feature completely unusable

### After Fix
- ✅ All reports generate successfully
- ✅ Correct data displayed
- ✅ Date filtering works
- ✅ Feature fully functional

## Lessons Learned

### Best Practices
1. **Use positional parameters for complex queries**
   - Especially with subqueries
   - When same parameter used multiple times

2. **Test with actual data early**
   - Don't just verify syntax
   - Run with real queries

3. **Watch for duplicate parameter names**
   - Easy to miss in long SQL queries
   - PDO error messages can be cryptic

4. **Consider query refactoring**
   - Break complex queries into simpler ones
   - Use CTEs (Common Table Expressions) if supported
   - Store intermediate results

## Prevention

### Code Review Checklist
When writing PDO queries:
- [ ] Count `?` placeholders in SQL
- [ ] Count values in parameter array
- [ ] Ensure they match exactly
- [ ] Test with actual database
- [ ] Check error logs
- [ ] Verify output data

### SQL Query Tips
```php
// ✅ GOOD - Simple positional
$sql = "SELECT * FROM table WHERE id = ? AND status = ?";
$stmt->execute([$id, $status]);

// ✅ GOOD - Named for single use
$sql = "SELECT * FROM table WHERE id = :id";
$stmt->execute(['id' => $id]);

// ❌ BAD - Duplicate named without proper binding
$sql = "SELECT * FROM table WHERE col1 = :id AND col2 = :id";
$stmt->execute(['id' => $id]); // FAILS!

// ✅ GOOD - Positional for duplicates
$sql = "SELECT * FROM table WHERE col1 = ? AND col2 = ?";
$stmt->execute([$id, $id]); // Works!
```

## Related Bugs
This is the **second occurrence** of this parameter binding issue:
1. **First:** Student attendance stats (fixed earlier)
2. **Second:** Report generation (fixed now)

### Pattern Recognition
Both bugs involved:
- Complex queries with multiple JOINs
- Subqueries in SELECT clause
- Same parameter used multiple times
- Named parameters with associative arrays

### Going Forward
**Recommendation:** Use positional parameters (`?`) as the default for all new queries, especially complex ones.

## Status

**Fixed:** ✅ Complete
**Tested:** ✅ All report types working
**Severity:** Critical (blocking feature)
**Priority:** High (user-facing)
**Resolution Time:** ~15 minutes

---

**Date Fixed:** August 7, 2026
**Bug Type:** Parameter binding error (PDO)
**Files Changed:** 1 (ReportController.php)
**Lines Changed:** ~150 lines (3 methods refactored)
