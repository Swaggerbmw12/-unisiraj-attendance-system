-- ============================================
-- Add lecturer_name column to courses table
-- This allows storing lecturer name as text
-- without requiring a foreign key relationship
-- ============================================

USE unisiraj_attendance;

-- Add lecturer_name column to courses table
ALTER TABLE courses 
ADD COLUMN lecturer_name VARCHAR(255) NULL AFTER lecturer_id;

-- Update existing records to populate lecturer_name from lecturers table
UPDATE courses c
LEFT JOIN lecturers l ON c.lecturer_id = l.id
SET c.lecturer_name = CONCAT(l.first_name, ' ', l.last_name)
WHERE c.lecturer_id IS NOT NULL;
