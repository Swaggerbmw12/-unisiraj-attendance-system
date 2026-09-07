<?php

/**
 * AttendanceSession Model
 * Handles QR code attendance sessions
 */
class AttendanceSession extends BaseModel
{
    /**
     * Create new attendance session
     * 
     * @param array $data Session data
     * @return int|false Session ID or false
     */
    public function create($data)
    {
        try {
            // Generate unique token
            $token = bin2hex(random_bytes(32));
            
            $sql = "INSERT INTO attendance_sessions 
                    (course_id, lecturer_id, session_name, session_date, start_time, end_time, 
                     token, expires_at, is_active, qr_code_path) 
                    VALUES (:course_id, :lecturer_id, :session_name, :session_date, :start_time, :end_time,
                            :token, :expires_at, 1, :qr_code_path)";
            
            $stmt = $this->db->getConnection()->prepare($sql);
            $stmt->execute([
                'course_id' => $data['course_id'],
                'lecturer_id' => $data['lecturer_id'],
                'session_name' => $data['session_name'] ?? null,
                'session_date' => $data['session_date'],
                'start_time' => $data['start_time'],
                'end_time' => $data['end_time'] ?? null,
                'token' => $token,
                'expires_at' => $data['expires_at'],
                'qr_code_path' => $data['qr_code_path'] ?? null
            ]);
            
            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            error_log("Create attendance session error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Get session by ID
     * 
     * @param int $id Session ID
     * @return array|null Session data
     */
    public function getById($id)
    {
        try {
            $sql = "SELECT ats.*, 
                           c.course_code, c.course_name,
                           CONCAT(l.first_name, ' ', l.last_name) as lecturer_name,
                           COUNT(DISTINCT ar.id) as total_attendees,
                           (SELECT COUNT(*) FROM enrollments WHERE course_id = ats.course_id AND status = 'active') as total_enrolled
                    FROM attendance_sessions ats
                    INNER JOIN courses c ON ats.course_id = c.id
                    INNER JOIN lecturers l ON ats.lecturer_id = l.id
                    LEFT JOIN attendance_records ar ON ats.id = ar.session_id
                    WHERE ats.id = :id
                    GROUP BY ats.id";
            
            $stmt = $this->db->getConnection()->prepare($sql);
            $stmt->execute(['id' => $id]);
            
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Get session by ID error: " . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Get session by token
     * 
     * @param string $token Session token
     * @return array|null Session data
     */
    public function getByToken($token)
    {
        try {
            $sql = "SELECT ats.*, 
                           c.course_code, c.course_name,
                           CONCAT(l.first_name, ' ', l.last_name) as lecturer_name
                    FROM attendance_sessions ats
                    INNER JOIN courses c ON ats.course_id = c.id
                    INNER JOIN lecturers l ON ats.lecturer_id = l.id
                    WHERE ats.token = :token";
            
            $stmt = $this->db->getConnection()->prepare($sql);
            $stmt->execute(['token' => $token]);
            
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Get session by token error: " . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Get all sessions for a course
     * 
     * @param int $courseId Course ID
     * @param int $page Current page
     * @param int $perPage Records per page
     * @return array
     */
    public function getByCourse($courseId, $page = 1, $perPage = 10)
    {
        try {
            $offset = ($page - 1) * $perPage;
            
            $sql = "SELECT ats.*,
                           COUNT(DISTINCT ar.id) as total_attendees,
                           (SELECT COUNT(*) FROM enrollments WHERE course_id = ats.course_id AND status = 'active') as total_enrolled
                    FROM attendance_sessions ats
                    LEFT JOIN attendance_records ar ON ats.id = ar.session_id
                    WHERE ats.course_id = :course_id
                    GROUP BY ats.id
                    ORDER BY ats.session_date DESC, ats.start_time DESC
                    LIMIT :limit OFFSET :offset";
            
            $stmt = $this->db->getConnection()->prepare($sql);
            $stmt->bindValue(':course_id', $courseId, PDO::PARAM_INT);
            $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Get sessions by course error: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get lecturer's sessions
     * 
     * @param int $lecturerId Lecturer ID
     * @param int $limit Limit results
     * @return array
     */
    public function getByLecturer($lecturerId, $limit = 10)
    {
        try {
            $sql = "SELECT ats.*,
                           c.course_code, c.course_name,
                           COUNT(DISTINCT ar.id) as total_attendees,
                           (SELECT COUNT(*) FROM enrollments WHERE course_id = ats.course_id AND status = 'active') as total_enrolled
                    FROM attendance_sessions ats
                    INNER JOIN courses c ON ats.course_id = c.id
                    LEFT JOIN attendance_records ar ON ats.id = ar.session_id
                    WHERE ats.lecturer_id = :lecturer_id
                    GROUP BY ats.id
                    ORDER BY ats.session_date DESC, ats.start_time DESC
                    LIMIT :limit";
            
            $stmt = $this->db->getConnection()->prepare($sql);
            $stmt->bindValue(':lecturer_id', $lecturerId, PDO::PARAM_INT);
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Get sessions by lecturer error: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get active sessions for today
     * 
     * @param int|null $lecturerId Filter by lecturer
     * @return array
     */
    public function getActiveSessions($lecturerId = null)
    {
        try {
            $sql = "SELECT ats.*,
                           c.course_code, c.course_name,
                           COUNT(DISTINCT ar.id) as total_attendees
                    FROM attendance_sessions ats
                    INNER JOIN courses c ON ats.course_id = c.id
                    LEFT JOIN attendance_records ar ON ats.id = ar.session_id
                    WHERE ats.is_active = 1 
                    AND ats.session_date = CURDATE()
                    AND ats.expires_at > NOW()";
            
            if ($lecturerId) {
                $sql .= " AND ats.lecturer_id = :lecturer_id";
            }
            
            $sql .= " GROUP BY ats.id ORDER BY ats.start_time DESC";
            
            $stmt = $this->db->getConnection()->prepare($sql);
            if ($lecturerId) {
                $stmt->bindValue(':lecturer_id', $lecturerId, PDO::PARAM_INT);
            }
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Get active sessions error: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Close session (make inactive)
     * 
     * @param int $id Session ID
     * @return bool Success status
     */
    public function closeSession($id)
    {
        try {
            $sql = "UPDATE attendance_sessions SET is_active = 0, end_time = CURRENT_TIME WHERE id = :id";
            $stmt = $this->db->getConnection()->prepare($sql);
            $stmt->execute(['id' => $id]);
            return true;
        } catch (PDOException $e) {
            error_log("Close session error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Check if session is valid and active
     * 
     * @param string $token Session token
     * @return bool
     */
    public function isValidSession($token)
    {
        try {
            $sql = "SELECT COUNT(*) FROM attendance_sessions 
                    WHERE token = :token 
                    AND is_active = 1 
                    AND expires_at > NOW()";
            
            $stmt = $this->db->getConnection()->prepare($sql);
            $stmt->execute(['token' => $token]);
            
            return $stmt->fetchColumn() > 0;
        } catch (PDOException $e) {
            error_log("Check valid session error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Update QR code path
     * 
     * @param int $id Session ID
     * @param string $path QR code file path
     * @return bool Success status
     */
    public function updateQrCodePath($id, $path)
    {
        try {
            $sql = "UPDATE attendance_sessions SET qr_code_path = :path WHERE id = :id";
            $stmt = $this->db->getConnection()->prepare($sql);
            $stmt->execute(['id' => $id, 'path' => $path]);
            return true;
        } catch (PDOException $e) {
            error_log("Update QR code path error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Get attendance records for session
     * 
     * @param int $sessionId Session ID
     * @return array
     */
    public function getAttendanceRecords($sessionId)
    {
        try {
            $sql = "SELECT ar.*,
                           s.student_id, s.first_name, s.last_name,
                           u.email
                    FROM attendance_records ar
                    INNER JOIN students s ON ar.student_id = s.id
                    INNER JOIN users u ON s.user_id = u.id
                    WHERE ar.session_id = :session_id
                    ORDER BY ar.attendance_time ASC";
            
            $stmt = $this->db->getConnection()->prepare($sql);
            $stmt->execute(['session_id' => $sessionId]);
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Get attendance records error: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Delete session and all related attendance records
     * 
     * @param int $id Session ID
     * @return bool Success status
     */
    public function delete($id)
    {
        try {
            // Start transaction
            $this->db->getConnection()->beginTransaction();
            
            // Delete attendance records first (foreign key constraint)
            $sql = "DELETE FROM attendance_records WHERE session_id = :id";
            $stmt = $this->db->getConnection()->prepare($sql);
            $stmt->execute(['id' => $id]);
            
            // Delete the session
            $sql = "DELETE FROM attendance_sessions WHERE id = :id";
            $stmt = $this->db->getConnection()->prepare($sql);
            $stmt->execute(['id' => $id]);
            
            // Commit transaction
            $this->db->getConnection()->commit();
            return true;
        } catch (PDOException $e) {
            // Rollback on error
            $this->db->getConnection()->rollBack();
            error_log("Delete session error: " . $e->getMessage());
            return false;
        }
    }
}
