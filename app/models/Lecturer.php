<?php

/**
 * Lecturer Model
 * Handles all lecturer-related database operations
 */
class Lecturer extends BaseModel
{
    /**
     * Get lecturers by faculty
     * 
     * @param string $faculty Faculty slug
     * @param int $page Current page
     * @param int $perPage Records per page
     * @param string $search Search term
     * @return array Lecturers with pagination
     */
    public function getByFaculty($faculty, $page = 1, $perPage = 10, $search = '')
    {
        try {
            $offset = ($page - 1) * $perPage;
            
            $sql = "SELECT 
                        l.id, l.staff_id, l.first_name, l.last_name, 
                        l.phone, l.department, l.faculty,
                        u.email, u.is_active, u.last_login, l.created_at
                    FROM lecturers l
                    INNER JOIN users u ON l.user_id = u.id
                    WHERE l.faculty = :faculty";
            
            $params = ['faculty' => $faculty];
            
            if (!empty($search)) {
                $sql .= " AND (l.staff_id LIKE :search OR l.first_name LIKE :search 
                          OR l.last_name LIKE :search OR u.email LIKE :search)";
                $params['search'] = "%$search%";
            }
            
            $sql .= " ORDER BY l.created_at DESC LIMIT :limit OFFSET :offset";
            
            $stmt = $this->db->getConnection()->prepare($sql);
            foreach ($params as $key => $value) {
                $stmt->bindValue(":$key", $value);
            }
            $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();
            
            $lecturers = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Get total count
            $countSql = "SELECT COUNT(*) FROM lecturers l 
                         INNER JOIN users u ON l.user_id = u.id 
                         WHERE l.faculty = :faculty";
            $countParams = ['faculty' => $faculty];
            
            if (!empty($search)) {
                $countSql .= " AND (l.staff_id LIKE :search OR l.first_name LIKE :search 
                              OR l.last_name LIKE :search OR u.email LIKE :search)";
                $countParams['search'] = "%$search%";
            }
            
            $countStmt = $this->db->getConnection()->prepare($countSql);
            foreach ($countParams as $key => $value) {
                $countStmt->bindValue(":$key", $value);
            }
            $countStmt->execute();
            $total = $countStmt->fetchColumn();
            
            return [
                'data' => $lecturers,
                'total' => $total,
                'page' => $page,
                'perPage' => $perPage,
                'totalPages' => ceil($total / $perPage)
            ];
        } catch (PDOException $e) {
            error_log("Get lecturers by faculty error: " . $e->getMessage());
            return ['data' => [], 'total' => 0, 'page' => 1, 'perPage' => $perPage, 'totalPages' => 0];
        }
    }
    
    /**
     * Get all lecturers with user information
     * 
     * @param int $page Current page
     * @param int $perPage Records per page
     * @param string $search Search term
     * @return array Lecturers with pagination
     */
    public function getAll($page = 1, $perPage = 10, $search = '')
    {
        try {
            $offset = ($page - 1) * $perPage;
            
            $sql = "SELECT 
                        l.id, l.staff_id, l.first_name, l.last_name, 
                        l.phone, l.department,
                        u.email, u.is_active, u.last_login, l.created_at
                    FROM lecturers l
                    INNER JOIN users u ON l.user_id = u.id
                    WHERE 1=1";
            
            $params = [];
            
            if (!empty($search)) {
                $sql .= " AND (l.staff_id LIKE :search OR l.first_name LIKE :search 
                          OR l.last_name LIKE :search OR u.email LIKE :search OR l.department LIKE :search)";
                $params['search'] = "%$search%";
            }
            
            $sql .= " ORDER BY l.created_at DESC LIMIT :limit OFFSET :offset";
            
            $stmt = $this->db->getConnection()->prepare($sql);
            foreach ($params as $key => $value) {
                $stmt->bindValue(":$key", $value);
            }
            $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();
            
            $lecturers = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Get total count
            $countSql = "SELECT COUNT(*) FROM lecturers l 
                         INNER JOIN users u ON l.user_id = u.id WHERE 1=1";
            if (!empty($search)) {
                $countSql .= " AND (l.staff_id LIKE :search OR l.first_name LIKE :search 
                              OR l.last_name LIKE :search OR u.email LIKE :search OR l.department LIKE :search)";
            }
            
            $countStmt = $this->db->getConnection()->prepare($countSql);
            if (!empty($search)) {
                $countStmt->bindValue(':search', "%$search%");
            }
            $countStmt->execute();
            $total = $countStmt->fetchColumn();
            
            return [
                'data' => $lecturers,
                'total' => $total,
                'page' => $page,
                'perPage' => $perPage,
                'totalPages' => ceil($total / $perPage)
            ];
        } catch (PDOException $e) {
            error_log("Get all lecturers error: " . $e->getMessage());
            return ['data' => [], 'total' => 0, 'page' => 1, 'perPage' => $perPage, 'totalPages' => 0];
        }
    }
    
    /**
     * Get lecturer by ID
     * 
     * @param int $id Lecturer ID
     * @return array|null Lecturer data
     */
    public function getById($id)
    {
        try {
            $sql = "SELECT 
                        l.*, u.email, u.is_active, u.user_id
                    FROM lecturers l
                    INNER JOIN users u ON l.user_id = u.id
                    WHERE l.id = :id";
            
            $stmt = $this->db->getConnection()->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Get lecturer by ID error: " . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Create new lecturer
     * 
     * @param array $data Lecturer data
     * @return int|false Lecturer ID or false
     */
    public function create($data)
    {
        try {
            $this->db->beginTransaction();
            
            // Create user account first
            $hashedPassword = password_hash($data['password'], PASSWORD_BCRYPT, ['cost' => 12]);
            
            $userSql = "INSERT INTO users (role_id, email, password, is_active) 
                        VALUES (2, :email, :password, 1)";
            $userStmt = $this->db->getConnection()->prepare($userSql);
            $userStmt->execute([
                'email' => $data['email'],
                'password' => $hashedPassword
            ]);
            
            $userId = $this->db->lastInsertId();
            
            // Create lecturer profile
            $lecturerSql = "INSERT INTO lecturers 
                           (user_id, staff_id, first_name, last_name, phone, faculty) 
                           VALUES (:user_id, :staff_id, :first_name, :last_name, :phone, :faculty)";
            
            $lecturerStmt = $this->db->getConnection()->prepare($lecturerSql);
            $lecturerStmt->execute([
                'user_id' => $userId,
                'staff_id' => $data['staff_id'],
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'phone' => $data['phone'] ?? null,
                'faculty' => $data['faculty'] ?? null
            ]);
            
            $lecturerId = $this->db->lastInsertId();
            
            $this->db->commit();
            return $lecturerId;
        } catch (PDOException $e) {
            $this->db->rollBack();
            error_log("Create lecturer error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Update lecturer
     * 
     * @param int $id Lecturer ID
     * @param array $data Updated data
     * @return bool Success status
     */
    public function update($id, $data)
    {
        try {
            $this->db->beginTransaction();
            
            // Update lecturer profile
            $lecturerSql = "UPDATE lecturers 
                           SET first_name = :first_name, last_name = :last_name, 
                               phone = :phone, faculty = :faculty
                           WHERE id = :id";
            
            $lecturerStmt = $this->db->getConnection()->prepare($lecturerSql);
            $lecturerStmt->execute([
                'id' => $id,
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'phone' => $data['phone'] ?? null,
                'faculty' => $data['faculty'] ?? null
            ]);
            
            // Update email if changed
            if (!empty($data['email'])) {
                $lecturer = $this->getById($id);
                $userSql = "UPDATE users SET email = :email WHERE id = :user_id";
                $userStmt = $this->db->getConnection()->prepare($userSql);
                $userStmt->execute([
                    'email' => $data['email'],
                    'user_id' => $lecturer['user_id']
                ]);
            }
            
            $this->db->commit();
            return true;
        } catch (PDOException $e) {
            $this->db->rollBack();
            error_log("Update lecturer error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Delete lecturer
     * 
     * @param int $id Lecturer ID
     * @return bool Success status
     */
    public function delete($id)
    {
        try {
            $sql = "DELETE FROM lecturers WHERE id = :id";
            $stmt = $this->db->getConnection()->prepare($sql);
            $stmt->execute(['id' => $id]);
            return true;
        } catch (PDOException $e) {
            error_log("Delete lecturer error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Check if staff ID exists
     * 
     * @param string $staffId Staff ID
     * @param int|null $excludeId Exclude this ID from check
     * @return bool
     */
    public function staffIdExists($staffId, $excludeId = null)
    {
        try {
            $sql = "SELECT COUNT(*) FROM lecturers WHERE staff_id = :staff_id";
            if ($excludeId) {
                $sql .= " AND id != :exclude_id";
            }
            
            $stmt = $this->db->getConnection()->prepare($sql);
            $stmt->bindValue(':staff_id', $staffId);
            if ($excludeId) {
                $stmt->bindValue(':exclude_id', $excludeId, PDO::PARAM_INT);
            }
            $stmt->execute();
            
            return $stmt->fetchColumn() > 0;
        } catch (PDOException $e) {
            error_log("Check staff ID exists error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Get lecturer's assigned courses
     * 
     * @param int $lecturerId Lecturer ID
     * @return array
     */
    public function getAssignedCourses($lecturerId)
    {
        try {
            $sql = "SELECT c.*,
                           COUNT(DISTINCT e.id) as enrolled_students
                    FROM courses c
                    LEFT JOIN enrollments e ON c.id = e.course_id AND e.status = 'active'
                    WHERE c.lecturer_id = :lecturer_id
                    GROUP BY c.id
                    ORDER BY c.created_at DESC";
            
            $stmt = $this->db->getConnection()->prepare($sql);
            $stmt->execute(['lecturer_id' => $lecturerId]);
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Get assigned courses error: " . $e->getMessage());
            return [];
        }
    }
}
