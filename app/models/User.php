<?php
/**
 * User Model
 * UniSIRAJ Automated Attendance System
 * 
 * Handles user authentication and user-related database operations
 */

class User extends BaseModel {
    
    /**
     * @var string Table name
     */
    protected $table = 'users';
    
    /**
     * Authenticate user with email and password
     * @param string $email User email
     * @param string $password User password (plain text)
     * @return array|false User data if authenticated, false otherwise
     */
    public function authenticate($email, $password) {
        // Find user by email
        $user = $this->findByEmail($email);
        
        if (!$user) {
            return false;
        }
        
        // Check if account is active
        if (!$user['is_active']) {
            return false;
        }
        
        // Verify password
        if (!password_verify($password, $user['password'])) {
            return false;
        }
        
        // Update last login time
        $this->updateLastLogin($user['id']);
        
        // Don't return password in user data
        unset($user['password']);
        
        return $user;
    }
    
    /**
     * Find user by email
     * @param string $email User email
     * @return array|false User data or false
     */
    public function findByEmail($email) {
        $sql = "SELECT * FROM {$this->table} WHERE email = ? LIMIT 1";
        return $this->db->queryOne($sql, [$email]);
    }
    
    /**
     * Get user with role information
     * @param int $userId User ID
     * @return array|false User with role data
     */
    public function getUserWithRole($userId) {
        $sql = "SELECT u.*, r.name as role_name, r.description as role_description
                FROM {$this->table} u
                INNER JOIN roles r ON u.role_id = r.id
                WHERE u.id = ?
                LIMIT 1";
        return $this->db->queryOne($sql, [$userId]);
    }
    
    /**
     * Get user profile data (with student or lecturer info)
     * @param int $userId User ID
     * @return array|false Complete user profile
     */
    public function getUserProfile($userId) {
        $user = $this->getUserWithRole($userId);
        
        if (!$user) {
            return false;
        }
        
        // Get additional profile based on role
        if ($user['role_name'] === 'student') {
            $sql = "SELECT * FROM students WHERE user_id = ? LIMIT 1";
            $profile = $this->db->queryOne($sql, [$userId]);
            $user['profile'] = $profile;
        } elseif ($user['role_name'] === 'lecturer') {
            $sql = "SELECT * FROM lecturers WHERE user_id = ? LIMIT 1";
            $profile = $this->db->queryOne($sql, [$userId]);
            $user['profile'] = $profile;
        }
        
        // Remove password from response
        unset($user['password']);
        
        return $user;
    }
    
    /**
     * Update last login timestamp
     * @param int $userId User ID
     * @return bool Success status
     */
    public function updateLastLogin($userId) {
        $sql = "UPDATE {$this->table} SET last_login = NOW() WHERE id = ?";
        return $this->db->execute($sql, [$userId]);
    }
    
    /**
     * Create new user account
     * @param array $data User data
     * @return int|false Last insert ID or false
     */
    public function createUser($data) {
        // Hash password
        $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT, ['cost' => BCRYPT_COST]);
        
        // Set default values
        $data['is_active'] = $data['is_active'] ?? 1;
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');
        
        return $this->create($data);
    }
    
    /**
     * Update user password
     * @param int $userId User ID
     * @param string $newPassword New password (plain text)
     * @return bool Success status
     */
    public function updatePassword($userId, $newPassword) {
        $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT, ['cost' => BCRYPT_COST]);
        
        $sql = "UPDATE {$this->table} SET password = ?, updated_at = NOW() WHERE id = ?";
        return $this->db->execute($sql, [$hashedPassword, $userId]);
    }
    
    /**
     * Check if email already exists
     * @param string $email Email address
     * @param int|null $excludeUserId Exclude this user ID (for updates)
     * @return bool True if exists, false otherwise
     */
    public function emailExists($email, $excludeUserId = null) {
        $sql = "SELECT COUNT(*) as count FROM {$this->table} WHERE email = ?";
        $params = [$email];
        
        if ($excludeUserId) {
            $sql .= " AND id != ?";
            $params[] = $excludeUserId;
        }
        
        $result = $this->db->queryOne($sql, $params);
        return $result['count'] > 0;
    }
    
    /**
     * Activate user account
     * @param int $userId User ID
     * @return bool Success status
     */
    public function activate($userId) {
        return $this->update($userId, ['is_active' => 1]);
    }
    
    /**
     * Deactivate user account
     * @param int $userId User ID
     * @return bool Success status
     */
    public function deactivate($userId) {
        return $this->update($userId, ['is_active' => 0]);
    }
    
    /**
     * Get all users with role information
     * @param int $page Page number
     * @param int $perPage Items per page
     * @param string|null $roleFilter Filter by role name
     * @return array Paginated users
     */
    public function getAllWithRoles($page = 1, $perPage = ITEMS_PER_PAGE, $roleFilter = null) {
        $offset = ($page - 1) * $perPage;
        
        $sql = "SELECT u.id, u.email, u.is_active, u.last_login, u.created_at,
                       r.name as role_name, r.description as role_description
                FROM {$this->table} u
                INNER JOIN roles r ON u.role_id = r.id";
        
        $params = [];
        
        if ($roleFilter) {
            $sql .= " WHERE r.name = ?";
            $params[] = $roleFilter;
        }
        
        $sql .= " ORDER BY u.created_at DESC LIMIT $perPage OFFSET $offset";
        
        $data = $this->db->query($sql, $params);
        
        // Count total
        $countSql = "SELECT COUNT(*) as count FROM {$this->table} u
                     INNER JOIN roles r ON u.role_id = r.id";
        if ($roleFilter) {
            $countSql .= " WHERE r.name = ?";
        }
        $total = $this->db->queryOne($countSql, $params)['count'];
        
        return [
            'data' => $data,
            'total' => $total,
            'page' => $page,
            'perPage' => $perPage,
            'totalPages' => ceil($total / $perPage)
        ];
    }
    
    /**
     * Get role ID by role name
     * @param string $roleName Role name (admin, lecturer, student)
     * @return int|false Role ID or false
     */
    public function getRoleIdByName($roleName) {
        $sql = "SELECT id FROM roles WHERE name = ? LIMIT 1";
        $result = $this->db->queryOne($sql, [$roleName]);
        return $result ? $result['id'] : false;
    }
    
    /**
     * Change user role
     * @param int $userId User ID
     * @param int $newRoleId New role ID
     * @return bool Success status
     */
    public function changeRole($userId, $newRoleId) {
        return $this->update($userId, ['role_id' => $newRoleId]);
    }
}
