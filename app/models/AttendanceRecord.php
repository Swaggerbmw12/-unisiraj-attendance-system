<?php

/**
 * AttendanceRecord Model
 * Handles student attendance records
 */
class AttendanceRecord extends BaseModel
{
    /**
     * Record attendance
     * 
     * @param int $sessionId Session ID
     * @param int $studentId Student ID
     * @param string $ipAddress IP address
     * @param string $userAgent User agent
     * @return bool Success status
     */
    public function recordAttendance($sessionId, $studentId, $ipAddress = null, $userAgent = null)
    {
        try {
            $sql = "INSERT INTO attendance_records 
                    (session_id, student_id, ip_address, user_agent, attendance_time) 
                    VALUES (:session_id, :student_id, :ip_address, :user_agent, NOW())";
            
            $stmt = $this->db->getConnection()->prepare($sql);
            $stmt->execute([
                'session_id' => $sessionId,
                'student_id' => $studentId,
                'ip_address' => $ipAddress,
                'user_agent' => $userAgent
            ]);
            
            return true;
        } catch (PDOException $e) {
            // Check if it's a duplicate entry error
            if ($e->getCode() == 23000) {
                return false; // Already recorded
            }
            error_log("Record attendance error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Check if student has already recorded attendance
     * 
     * @param int $sessionId Session ID
     * @param int $studentId Student ID
     * @return bool
     */
    public function hasAttended($sessionId, $studentId)
    {
        try {
            $sql = "SELECT COUNT(*) FROM attendance_records 
                    WHERE session_id = :session_id AND student_id = :student_id";
            
            $stmt = $this->db->getConnection()->prepare($sql);
            $stmt->execute([
                'session_id' => $sessionId,
                'student_id' => $studentId
            ]);
            
            return $stmt->fetchColumn() > 0;
        } catch (PDOException $e) {
            error_log("Check attendance error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Get student's attendance history
     * 
     * @param int $studentId Student ID
     * @param int $limit Limit results
     * @return array
     */
    public function getStudentHistory($studentId, $limit = 20)
    {
        try {
            $sql = "SELECT ar.*,
                           ats.session_name, ats.session_date, ats.start_time,
                           c.course_code, c.course_name
                    FROM attendance_records ar
                    INNER JOIN attendance_sessions ats ON ar.session_id = ats.id
                    INNER JOIN courses c ON ats.course_id = c.id
                    WHERE ar.student_id = :student_id
                    ORDER BY ar.attendance_time DESC
                    LIMIT :limit";
            
            $stmt = $this->db->getConnection()->prepare($sql);
            $stmt->bindValue(':student_id', $studentId, PDO::PARAM_INT);
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Get student history error: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get attendance count for a session
     * 
     * @param int $sessionId Session ID
     * @return int
     */
    public function getSessionAttendanceCount($sessionId)
    {
        try {
            $sql = "SELECT COUNT(*) FROM attendance_records WHERE session_id = :session_id";
            $stmt = $this->db->getConnection()->prepare($sql);
            $stmt->execute(['session_id' => $sessionId]);
            
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log("Get session attendance count error: " . $e->getMessage());
            return 0;
        }
    }
    
    /**
     * Get attendance statistics for a student in a course
     * 
     * @param int $studentId Student ID
     * @param int $courseId Course ID
     * @return array
     */
    public function getCourseAttendanceStats($studentId, $courseId)
    {
        try {
            $sql = "SELECT 
                        COUNT(DISTINCT ats.id) as total_sessions,
                        COUNT(DISTINCT ar.id) as attended_sessions,
                        ROUND((COUNT(DISTINCT ar.id) / NULLIF(COUNT(DISTINCT ats.id), 0) * 100), 2) as percentage
                    FROM attendance_sessions ats
                    LEFT JOIN attendance_records ar ON ats.id = ar.session_id AND ar.student_id = :student_id
                    WHERE ats.course_id = :course_id";
            
            $stmt = $this->db->getConnection()->prepare($sql);
            $stmt->execute([
                'student_id' => $studentId,
                'course_id' => $courseId
            ]);
            
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Get course attendance stats error: " . $e->getMessage());
            return ['total_sessions' => 0, 'attended_sessions' => 0, 'percentage' => 0];
        }
    }
}
