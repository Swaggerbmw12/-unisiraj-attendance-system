-- ============================================
-- Fix User Passwords
-- UniSIRAJ Automated Attendance System
-- ============================================
-- This script updates all user passwords to 'Admin@123'
-- All users should change their password after first login

USE unisiraj_attendance;

-- Update Admin password
UPDATE users SET password = '$2y$12$NJMIjC.25FGtd8z393Wp8eFxjjN5ikQ7JUJzYdQzdGgCFGMA/l.5C' 
WHERE email = 'admin@unisiraj.edu.my';

-- Update all other users (if they exist)
UPDATE users SET password = '$2y$12$NJMIjC.25FGtd8z393Wp8eFxjjN5ikQ7JUJzYdQzdGgCFGMA/l.5C';

-- Verify the update
SELECT id, email, role_id, is_active, 
       CASE WHEN password = '$2y$12$NJMIjC.25FGtd8z393Wp8eFxjjN5ikQ7JUJzYdQzdGgCFGMA/l.5C' 
            THEN '✅ Correct' 
            ELSE '❌ Wrong' 
       END as password_status
FROM users;
