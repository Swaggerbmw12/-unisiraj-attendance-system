<?php

/**
 * Enrollment Model
 * Handles student course enrollments
 */
class Enrollment extends BaseModel
{
    /**
     * Get all enrollments
     * 
     * @param int $page Current page
     * @param int $perPage Records per page
     * @param array $filters Filters (course_id, student_id, status)
     * @return array Enrollments with pagination
     */
    public function getAll($page = 1, $perPage = 10, $filters = [])
    {
        try {
            $offset = ($page - 1) * $perPage;
            
            $sql = "SELECT 
                        e.*,
                        s.student_id, s.first_name as student_first_name, s.last_name as student_last_name,
                        c.course_code, c.course_name
                    FROM enrollments e
                    INNER JOIN students s ON e.student_id = s.id
                    INNER JOIN courses c ON e.course_id = c.id
                    WHERE 1=1";
            
            $params = [];
            
            if (!empty($filters['course_id'])) {
                $sql .= " AND e.course_id = :course_id";
                $params['course_id'] = $filters['course_id'];
            }
            
            if (!empty($filters['student_id'])) {
                $sql .= " AND e.student_id = :student_id";
                $params['student_id'] = $filters['student_id'];
            }
            
            if (!empty($filters['status'])) {
                $sql .= " AND e.status = :status";
                $params['status'] = $filters['status'];
            }
            
            $sql .= " ORDER BY e.created_at DESC LIMIT :limit OFFSET :offset";
            
            $stmt = $this->db->getConnection()->prepare($sql);
            foreach ($params as $key => $value) {
                $stmt->bindValue(":$key", $value);
            }
            $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();
            
            $enrollments = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Get total count
            $countSql = "SELECT COUNT(*) FROM enrollments e WHERE 1=1";
            $countParams = [];
            
            if (!empty($filters['course_id'])) {
                $countSql .= " AND e.course_id = :course_id";
                $countParams['course_id'] = $filters['course_id'];
            }
            if (!empty($filters['student_id'])) {
                $countSql .= " AND e.student_id = :student_id";
                $countParams['student_id'] = $filters['student_id'];
            }
            if (!empty($filters['status'])) {
                $countSql .= " AND e.status = :status";
                $countParams['status'] = $filters['status'];
            }
            
            $countStmt = $this->db->getConnection()->prepare($countSql);
            foreach ($countParams as $key => $value) {
                $countStmt->bindValue(":$key", $value);
            }
            $countStmt->execute();
            $total = $countStmt->fetchColumn();
            
            return [
                'data' => $enrollments,
                'total' => $total,
                'page' => $page,
                'perPage' => $perPage,
                'totalPages' => ceil($total / $perPage)
            ];
        } catch (PDOException $e) {
            error_log("Get all enrollments error: " . $e->getMessage());
            return ['data' => [], 'total' => 0, 'page' => 1, 'perPage' => $perPage, 'totalPages' => 0];
        }
    }
    
    /**
     * Enroll student in course
     * 
     * @param int $studentId Student ID
     * @param int $courseId Course ID
     * @return bool Success status
     */
    public function enroll($studentId, $courseId)
    {
        try {
            $sql = "INSERT INTO enrollments (student_id, course_id, enrollment_date, status) 
                    VALUES (:student_id, :course_id, CURDATE(), 'active')";
            
            $stmt = $this->db->getConnection()->prepare($sql);
            $stmt->execute([
                'student_id' => $studentId,
                'course_id' => $courseId
            ]);
            
            return true;
        } catch (PDOException $e) {
            error_log("Enroll student error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Check if student is already enrolled
     * 
     * @param int $studentId Student ID
     * @param int $courseId Course ID
     * @return bool
     */
    public function isEnrolled($studentId, $courseId)
    {
        try {
            $sql = "SELECT COUNT(*) FROM enrollments 
                    WHERE student_id = :student_id AND course_id = :course_id";
            
            $stmt = $this->db->getConnection()->prepare($sql);
            $stmt->execute([
                'student_id' => $studentId,
                'course_id' => $courseId
            ]);
            
            return $stmt->fetchColumn() > 0;
        } catch (PDOException $e) {
            error_log("Check enrollment error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Update enrollment status
     * 
     * @param int $id Enrollment ID
     * @param string $status New status
     * @return bool Success status
     */
    public function updateStatus($id, $status)
    {
        try {
            $sql = "UPDATE enrollments SET status = :status WHERE id = :id";
            $stmt = $this->db->getConnection()->prepare($sql);
            $stmt->execute(['id' => $id, 'status' => $status]);
            return true;
        } catch (PDOException $e) {
            error_log("Update enrollment status error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Delete enrollment
     * 
     * @param int $id Enrollment ID
     * @return bool Success status
     */
    public function delete($id)
    {
        try {
            $sql = "DELETE FROM enrollments WHERE id = :id";
            $stmt = $this->db->getConnection()->prepare($sql);
            $stmt->execute(['id' => $id]);
            return true;
        } catch (PDOException $e) {
            error_log("Delete enrollment error: " . $e->getMessage());
            return false;
        }
    }
}
