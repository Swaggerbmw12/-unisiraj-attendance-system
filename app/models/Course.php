<?php

/**
 * Course Model
 * Handles all course-related database operations
 */
class Course extends BaseModel
{
    /**
     * Get all courses with lecturer information
     * 
     * @param int $page Current page
     * @param int $perPage Records per page
     * @param string $search Search term
     * @return array Courses with pagination
     */
    public function getAll($page = 1, $perPage = 10, $search = '')
    {
        try {
            $offset = ($page - 1) * $perPage;
            
            $sql = "SELECT 
                        c.*,
                        COALESCE(c.lecturer_name, CONCAT(l.first_name, ' ', l.last_name)) as lecturer_name,
                        l.staff_id,
                        COUNT(DISTINCT e.id) as enrolled_students
                    FROM courses c
                    LEFT JOIN lecturers l ON c.lecturer_id = l.id
                    LEFT JOIN enrollments e ON c.id = e.course_id AND e.status = 'active'
                    WHERE 1=1";
            
            $params = [];
            
            if (!empty($search)) {
                $sql .= " AND (c.course_code LIKE :search OR c.course_name LIKE :search)";
                $params['search'] = "%$search%";
            }
            
            $sql .= " GROUP BY c.id, c.course_code, c.course_name, c.lecturer_id, c.lecturer_name, c.semester, 
                      c.academic_year, c.credits, c.description, c.is_active, c.created_at, c.updated_at,
                      l.first_name, l.last_name, l.staff_id
                      ORDER BY c.created_at DESC LIMIT :limit OFFSET :offset";
            
            $stmt = $this->db->getConnection()->prepare($sql);
            foreach ($params as $key => $value) {
                $stmt->bindValue(":$key", $value);
            }
            $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();
            
            $courses = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Get total count
            $countSql = "SELECT COUNT(DISTINCT c.id) FROM courses c WHERE 1=1";
            if (!empty($search)) {
                $countSql .= " AND (c.course_code LIKE :search OR c.course_name LIKE :search)";
            }
            
            $countStmt = $this->db->getConnection()->prepare($countSql);
            if (!empty($search)) {
                $countStmt->bindValue(':search', "%$search%");
            }
            $countStmt->execute();
            $total = $countStmt->fetchColumn();
            
            return [
                'data' => $courses,
                'total' => $total,
                'page' => $page,
                'perPage' => $perPage,
                'totalPages' => ceil($total / $perPage)
            ];
        } catch (PDOException $e) {
            error_log("Get all courses error: " . $e->getMessage());
            return ['data' => [], 'total' => 0, 'page' => 1, 'perPage' => $perPage, 'totalPages' => 0];
        }
    }
    
    /**
     * Get course by ID
     * 
     * @param int $id Course ID
     * @return array|null Course data
     */
    public function getById($id)
    {
        try {
            $sql = "SELECT c.*, 
                           CONCAT(l.first_name, ' ', l.last_name) as lecturer_name,
                           l.staff_id
                    FROM courses c
                    LEFT JOIN lecturers l ON c.lecturer_id = l.id
                    WHERE c.id = :id";
            
            $stmt = $this->db->getConnection()->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Get course by ID error: " . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Create new course
     * 
     * @param array $data Course data
     * @return int|false Course ID or false
     */
    public function create($data)
    {
        try {
            $sql = "INSERT INTO courses 
                    (course_code, course_name, lecturer_id, lecturer_name, semester, academic_year, credits, description, is_active) 
                    VALUES (:course_code, :course_name, :lecturer_id, :lecturer_name, :semester, :academic_year, :credits, :description, :is_active)";
            
            $stmt = $this->db->getConnection()->prepare($sql);
            $result = $stmt->execute([
                'course_code' => $data['course_code'],
                'course_name' => $data['course_name'],
                'lecturer_id' => $data['lecturer_id'] ?? null,
                'lecturer_name' => $data['lecturer_name'] ?? null,
                'semester' => $data['semester'] ?? null,
                'academic_year' => $data['academic_year'] ?? null,
                'credits' => $data['credits'] ?? null,
                'description' => $data['description'] ?? null,
                'is_active' => $data['is_active'] ?? 1
            ]);
            
            if ($result) {
                return $this->db->lastInsertId();
            }
            return false;
        } catch (PDOException $e) {
            error_log("Create course error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Update course
     * 
     * @param int $id Course ID
     * @param array $data Updated data
     * @return bool Success status
     */
    public function update($id, $data)
    {
        try {
            $sql = "UPDATE courses 
                    SET course_name = :course_name, lecturer_id = :lecturer_id, lecturer_name = :lecturer_name,
                        semester = :semester, academic_year = :academic_year,
                        credits = :credits, description = :description, is_active = :is_active
                    WHERE id = :id";
            
            $stmt = $this->db->getConnection()->prepare($sql);
            return $stmt->execute([
                'id' => $id,
                'course_name' => $data['course_name'],
                'lecturer_id' => $data['lecturer_id'] ?? null,
                'lecturer_name' => $data['lecturer_name'] ?? null,
                'semester' => $data['semester'] ?? null,
                'academic_year' => $data['academic_year'] ?? null,
                'credits' => $data['credits'] ?? null,
                'description' => $data['description'] ?? null,
                'is_active' => $data['is_active'] ?? 1
            ]);
        } catch (PDOException $e) {
            error_log("Update course error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Delete course
     * 
     * @param int $id Course ID
     * @return bool Success status
     */
    public function delete($id)
    {
        try {
            $sql = "DELETE FROM courses WHERE id = :id";
            $stmt = $this->db->getConnection()->prepare($sql);
            $stmt->execute(['id' => $id]);
            return true;
        } catch (PDOException $e) {
            error_log("Delete course error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Check if course code exists
     * 
     * @param string $courseCode Course code
     * @param int|null $excludeId Exclude this ID from check
     * @return bool
     */
    public function courseCodeExists($courseCode, $excludeId = null)
    {
        try {
            $sql = "SELECT COUNT(*) FROM courses WHERE course_code = :course_code";
            if ($excludeId) {
                $sql .= " AND id != :exclude_id";
            }
            
            $stmt = $this->db->getConnection()->prepare($sql);
            $stmt->bindValue(':course_code', $courseCode);
            if ($excludeId) {
                $stmt->bindValue(':exclude_id', $excludeId, PDO::PARAM_INT);
            }
            $stmt->execute();
            
            return $stmt->fetchColumn() > 0;
        } catch (PDOException $e) {
            error_log("Check course code exists error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Get enrolled students for a course
     * 
     * @param int $courseId Course ID
     * @return array
     */
    public function getEnrolledStudents($courseId)
    {
        try {
            $sql = "SELECT s.*, e.enrollment_date, e.status,
                           u.email
                    FROM enrollments e
                    INNER JOIN students s ON e.student_id = s.id
                    INNER JOIN users u ON s.user_id = u.id
                    WHERE e.course_id = :course_id
                    ORDER BY s.first_name, s.last_name";
            
            $stmt = $this->db->getConnection()->prepare($sql);
            $stmt->execute(['course_id' => $courseId]);
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Get enrolled students error: " . $e->getMessage());
            return [];
        }
    }
}
