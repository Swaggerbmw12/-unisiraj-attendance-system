<?php

/**
 * Student Model
 * Handles all student-related database operations
 */
class Student extends BaseModel
{
    /**
     * Get all students with user information
     * 
     * @param int $page Current page
     * @param int $perPage Records per page
     * @param string $search Search term
     * @return array Students with pagination
     */
    public function getAll($page = 1, $perPage = 10, $search = '')
    {
        try {
            $offset = ($page - 1) * $perPage;
            
            $sql = "SELECT 
                        s.id, s.student_id, s.first_name, s.last_name, 
                        s.phone, s.program, s.year_of_study,
                        u.email, u.is_active, u.last_login, s.created_at
                    FROM students s
                    INNER JOIN users u ON s.user_id = u.id
                    WHERE 1=1";
            
            $params = [];
            
            if (!empty($search)) {
                $sql .= " AND (s.student_id LIKE :search OR s.first_name LIKE :search 
                          OR s.last_name LIKE :search OR u.email LIKE :search)";
                $params['search'] = "%$search%";
            }
            
            $sql .= " ORDER BY s.created_at DESC LIMIT :limit OFFSET :offset";
            
            $stmt = $this->db->getConnection()->prepare($sql);
            foreach ($params as $key => $value) {
                $stmt->bindValue(":$key", $value);
            }
            $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();
            
            $students = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Get total count
            $countSql = "SELECT COUNT(*) FROM students s 
                         INNER JOIN users u ON s.user_id = u.id WHERE 1=1";
            if (!empty($search)) {
                $countSql .= " AND (s.student_id LIKE :search OR s.first_name LIKE :search 
                              OR s.last_name LIKE :search OR u.email LIKE :search)";
            }
            
            $countStmt = $this->db->getConnection()->prepare($countSql);
            if (!empty($search)) {
                $countStmt->bindValue(':search', "%$search%");
            }
            $countStmt->execute();
            $total = $countStmt->fetchColumn();
            
            return [
                'data' => $students,
                'total' => $total,
                'page' => $page,
                'perPage' => $perPage,
                'totalPages' => ceil($total / $perPage)
            ];
        } catch (PDOException $e) {
            error_log("Get all students error: " . $e->getMessage());
            return ['data' => [], 'total' => 0, 'page' => 1, 'perPage' => $perPage, 'totalPages' => 0];
        }
    }
    
    /**
     * Get student by ID
     * 
     * @param int $id Student ID
     * @return array|null Student data
     */
    public function getById($id)
    {
        try {
            $sql = "SELECT 
                        s.*, u.email, u.is_active, s.user_id
                    FROM students s
                    INNER JOIN users u ON s.user_id = u.id
                    WHERE s.id = :id";
            
            $stmt = $this->db->getConnection()->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Get student by ID error: " . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Create new student
     * 
     * @param array $data Student data
     * @return int|false Student ID or false
     */
    public function create($data)
    {
        try {
            $this->db->beginTransaction();
            
            // Create user account first
            $hashedPassword = password_hash($data['password'], PASSWORD_BCRYPT, ['cost' => 12]);
            
            $userSql = "INSERT INTO users (role_id, email, password, is_active) 
                        VALUES (3, :email, :password, 1)";
            $userStmt = $this->db->getConnection()->prepare($userSql);
            $userStmt->execute([
                'email' => $data['email'],
                'password' => $hashedPassword
            ]);
            
            $userId = $this->db->lastInsertId();
            
            if (!$userId) {
                error_log("Failed to create user account for student");
                $this->db->rollBack();
                return false;
            }
            
            // Create student profile
            $studentSql = "INSERT INTO students 
                          (user_id, student_id, first_name, last_name, phone, program, year_of_study) 
                          VALUES (:user_id, :student_id, :first_name, :last_name, :phone, :program, :year_of_study)";
            
            $studentStmt = $this->db->getConnection()->prepare($studentSql);
            $studentStmt->execute([
                'user_id' => $userId,
                'student_id' => $data['student_id'],
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'phone' => $data['phone'] ?? null,
                'program' => $data['program'] ?? null,
                'year_of_study' => $data['year_of_study'] ?? null
            ]);
            
            $studentId = $this->db->lastInsertId();
            
            if (!$studentId) {
                error_log("Failed to create student profile");
                $this->db->rollBack();
                return false;
            }
            
            $this->db->commit();
            return $studentId;
        } catch (PDOException $e) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            error_log("Create student error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Update student
     * 
     * @param int $id Student ID
     * @param array $data Updated data
     * @return bool Success status
     */
    public function update($id, $data)
    {
        try {
            $this->db->beginTransaction();
            
            // Get student first to ensure they exist
            $student = $this->getById($id);
            if (!$student) {
                error_log("Student not found for update: ID $id");
                $this->db->rollBack();
                return false;
            }
            
            // Update student profile
            $studentSql = "UPDATE students 
                          SET first_name = :first_name, last_name = :last_name, 
                              phone = :phone, program = :program, year_of_study = :year_of_study
                          WHERE id = :id";
            
            $studentStmt = $this->db->getConnection()->prepare($studentSql);
            $studentStmt->execute([
                'id' => $id,
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'phone' => $data['phone'] ?? null,
                'program' => $data['program'] ?? null,
                'year_of_study' => $data['year_of_study'] ?? null
            ]);
            
            // Update email if changed
            if (!empty($data['email'])) {
                $userSql = "UPDATE users SET email = :email WHERE id = :user_id";
                $userStmt = $this->db->getConnection()->prepare($userSql);
                $userStmt->execute([
                    'email' => $data['email'],
                    'user_id' => $student['user_id']
                ]);
            }
            
            $this->db->commit();
            return true;
        } catch (PDOException $e) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            error_log("Update student error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Delete student
     * 
     * @param int $id Student ID
     * @return bool Success status
     */
    public function delete($id)
    {
        try {
            $sql = "DELETE FROM students WHERE id = :id";
            $stmt = $this->db->getConnection()->prepare($sql);
            $stmt->execute(['id' => $id]);
            return true;
        } catch (PDOException $e) {
            error_log("Delete student error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Check if student ID exists
     * 
     * @param string $studentId Student ID
     * @param int|null $excludeId Exclude this ID from check
     * @return bool
     */
    public function studentIdExists($studentId, $excludeId = null)
    {
        try {
            $sql = "SELECT COUNT(*) FROM students WHERE student_id = :student_id";
            if ($excludeId) {
                $sql .= " AND id != :exclude_id";
            }
            
            $stmt = $this->db->getConnection()->prepare($sql);
            $stmt->bindValue(':student_id', $studentId);
            if ($excludeId) {
                $stmt->bindValue(':exclude_id', $excludeId, PDO::PARAM_INT);
            }
            $stmt->execute();
            
            return $stmt->fetchColumn() > 0;
        } catch (PDOException $e) {
            error_log("Check student ID exists error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Get student's enrolled courses
     * 
     * @param int $studentId Student ID
     * @return array
     */
    public function getEnrolledCourses($studentId)
    {
        try {
            $sql = "SELECT c.*, e.status, e.enrollment_date,
                           CONCAT(l.first_name, ' ', l.last_name) as lecturer_name
                    FROM enrollments e
                    INNER JOIN courses c ON e.course_id = c.id
                    LEFT JOIN lecturers l ON c.lecturer_id = l.id
                    WHERE e.student_id = :student_id
                    ORDER BY e.enrollment_date DESC";
            
            $stmt = $this->db->getConnection()->prepare($sql);
            $stmt->execute(['student_id' => $studentId]);
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Get enrolled courses error: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get student's attendance statistics
     * 
     * @param int $studentId Student ID
     * @return array
     */
    public function getAttendanceStats($studentId)
    {
        try {
            $sql = "SELECT 
                        c.id as course_id,
                        c.course_code,
                        c.course_name,
                        COUNT(DISTINCT ats.id) as total_sessions,
                        COUNT(DISTINCT ar.id) as attended_sessions,
                        ROUND((COUNT(DISTINCT ar.id) / NULLIF(COUNT(DISTINCT ats.id), 0) * 100), 2) as percentage
                    FROM enrollments e
                    INNER JOIN courses c ON e.course_id = c.id
                    LEFT JOIN attendance_sessions ats ON c.id = ats.course_id
                    LEFT JOIN attendance_records ar ON ats.id = ar.session_id AND ar.student_id = ?
                    WHERE e.student_id = ? AND e.status = 'active'
                    GROUP BY c.id, c.course_code, c.course_name";
            
            $stmt = $this->db->getConnection()->prepare($sql);
            $stmt->execute([$studentId, $studentId]);
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Get attendance stats error: " . $e->getMessage());
            return [];
        }
    }
}
