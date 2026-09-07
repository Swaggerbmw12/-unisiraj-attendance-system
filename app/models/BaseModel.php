<?php
/**
 * Base Model
 * UniSIRAJ Automated Attendance System
 * 
 * Parent model that provides common database operations
 * All models should extend this class
 */

class BaseModel {
    
    /**
     * @var Database Database instance
     */
    protected $db;
    
    /**
     * @var string Table name
     */
    protected $table;
    
    /**
     * @var string Primary key column
     */
    protected $primaryKey = 'id';
    
    /**
     * Constructor
     */
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    /**
     * Get all records from table
     * @param array $conditions WHERE conditions
     * @param string $orderBy ORDER BY clause
     * @return array
     */
    public function all($conditions = [], $orderBy = null) {
        $sql = "SELECT * FROM {$this->table}";
        $params = [];
        
        if (!empty($conditions)) {
            $where = [];
            foreach ($conditions as $key => $value) {
                $where[] = "$key = ?";
                $params[] = $value;
            }
            $sql .= " WHERE " . implode(' AND ', $where);
        }
        
        if ($orderBy) {
            $sql .= " ORDER BY $orderBy";
        }
        
        return $this->db->query($sql, $params);
    }
    
    /**
     * Find record by ID
     * @param int $id Primary key value
     * @return array|false
     */
    public function find($id) {
        $sql = "SELECT * FROM {$this->table} WHERE {$this->primaryKey} = ? LIMIT 1";
        return $this->db->queryOne($sql, [$id]);
    }
    
    /**
     * Find record by conditions
     * @param array $conditions WHERE conditions
     * @return array|false
     */
    public function findBy($conditions) {
        $sql = "SELECT * FROM {$this->table}";
        $params = [];
        
        if (!empty($conditions)) {
            $where = [];
            foreach ($conditions as $key => $value) {
                $where[] = "$key = ?";
                $params[] = $value;
            }
            $sql .= " WHERE " . implode(' AND ', $where);
        }
        
        $sql .= " LIMIT 1";
        
        return $this->db->queryOne($sql, $params);
    }
    
    /**
     * Insert new record
     * @param array $data Data to insert
     * @return int Last insert ID
     */
    public function create($data) {
        // Add timestamps if columns exist
        if ($this->hasTimestamps()) {
            $data['created_at'] = date('Y-m-d H:i:s');
            $data['updated_at'] = date('Y-m-d H:i:s');
        }
        
        $columns = array_keys($data);
        $placeholders = array_fill(0, count($columns), '?');
        
        $sql = sprintf(
            "INSERT INTO %s (%s) VALUES (%s)",
            $this->table,
            implode(', ', $columns),
            implode(', ', $placeholders)
        );
        
        $this->db->execute($sql, array_values($data));
        
        return $this->db->lastInsertId();
    }
    
    /**
     * Update record by ID
     * @param int $id Primary key value
     * @param array $data Data to update
     * @return bool
     */
    public function update($id, $data) {
        // Add updated_at timestamp if column exists
        if ($this->hasTimestamps()) {
            $data['updated_at'] = date('Y-m-d H:i:s');
        }
        
        $set = [];
        $params = [];
        
        foreach ($data as $key => $value) {
            $set[] = "$key = ?";
            $params[] = $value;
        }
        
        $params[] = $id;
        
        $sql = sprintf(
            "UPDATE %s SET %s WHERE %s = ?",
            $this->table,
            implode(', ', $set),
            $this->primaryKey
        );
        
        return $this->db->execute($sql, $params);
    }
    
    /**
     * Delete record by ID
     * @param int $id Primary key value
     * @return bool
     */
    public function delete($id) {
        $sql = "DELETE FROM {$this->table} WHERE {$this->primaryKey} = ?";
        return $this->db->execute($sql, [$id]);
    }
    
    /**
     * Count records
     * @param array $conditions WHERE conditions
     * @return int
     */
    public function count($conditions = []) {
        $sql = "SELECT COUNT(*) as count FROM {$this->table}";
        $params = [];
        
        if (!empty($conditions)) {
            $where = [];
            foreach ($conditions as $key => $value) {
                $where[] = "$key = ?";
                $params[] = $value;
            }
            $sql .= " WHERE " . implode(' AND ', $where);
        }
        
        $result = $this->db->queryOne($sql, $params);
        return (int)$result['count'];
    }
    
    /**
     * Check if record exists
     * @param array $conditions WHERE conditions
     * @return bool
     */
    public function exists($conditions) {
        return $this->count($conditions) > 0;
    }
    
    /**
     * Paginate records
     * @param int $page Current page
     * @param int $perPage Items per page
     * @param array $conditions WHERE conditions
     * @param string $orderBy ORDER BY clause
     * @return array ['data' => [], 'total' => int, 'page' => int, 'perPage' => int]
     */
    public function paginate($page = 1, $perPage = ITEMS_PER_PAGE, $conditions = [], $orderBy = null) {
        $offset = ($page - 1) * $perPage;
        
        // Build query
        $sql = "SELECT * FROM {$this->table}";
        $params = [];
        
        if (!empty($conditions)) {
            $where = [];
            foreach ($conditions as $key => $value) {
                $where[] = "$key = ?";
                $params[] = $value;
            }
            $sql .= " WHERE " . implode(' AND ', $where);
        }
        
        if ($orderBy) {
            $sql .= " ORDER BY $orderBy";
        }
        
        $sql .= " LIMIT $perPage OFFSET $offset";
        
        // Get data
        $data = $this->db->query($sql, $params);
        
        // Get total count
        $total = $this->count($conditions);
        
        return [
            'data' => $data,
            'total' => $total,
            'page' => $page,
            'perPage' => $perPage,
            'totalPages' => ceil($total / $perPage)
        ];
    }
    
    /**
     * Execute raw SQL query
     * @param string $sql SQL query
     * @param array $params Parameters
     * @return array
     */
    public function query($sql, $params = []) {
        return $this->db->query($sql, $params);
    }
    
    /**
     * Execute raw SQL query and return single row
     * @param string $sql SQL query
     * @param array $params Parameters
     * @return array|false
     */
    public function queryOne($sql, $params = []) {
        return $this->db->queryOne($sql, $params);
    }
    
    /**
     * Check if table has timestamp columns
     * @return bool
     */
    protected function hasTimestamps() {
        // Override in child models if needed
        return true;
    }
}
