<?php
/**
 * Database Configuration - EXAMPLE FILE
 * UniSIRAJ Automated Attendance System
 * 
 * INSTRUCTIONS:
 * 1. Copy this file and rename it to 'database.php'
 * 2. Update the credentials below with your database information
 * 3. Never commit the actual database.php file to version control
 */

// Prevent direct access
if (!defined('ROOT_PATH')) {
    exit('No direct script access allowed');
}

/**
 * Database Connection Class
 * Uses PDO with prepared statements for security
 */
class Database {
    
    // Database credentials - UPDATE THESE WITH YOUR VALUES
    private $host = 'localhost';           // Database host
    private $dbname = 'unisiraj_attendance'; // Database name
    private $username = 'root';            // Database username
    private $password = '';                // Database password
    private $charset = 'utf8mb4';
    
    // PDO instance (singleton)
    private static $instance = null;
    private $pdo;
    
    /**
     * Private constructor to prevent direct instantiation
     * Establishes database connection with PDO
     */
    private function __construct() {
        $dsn = "mysql:host={$this->host};dbname={$this->dbname};charset={$this->charset}";
        
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
            PDO::ATTR_PERSISTENT => false,
            PDO::ATTR_TIMEOUT => 5,
        ];
        
        try {
            $this->pdo = new PDO($dsn, $this->username, $this->password, $options);
            
            if (APP_ENV === 'development') {
                error_log('[' . date('Y-m-d H:i:s') . '] Database connection established successfully');
            }
            
        } catch (PDOException $e) {
            error_log('[' . date('Y-m-d H:i:s') . '] Database Connection Error: ' . $e->getMessage());
            
            if (APP_ENV === 'development') {
                die('Database Connection Error: ' . $e->getMessage());
            } else {
                die('Database connection failed. Please contact the system administrator.');
            }
        }
    }
    
    private function __clone() {}
    
    public function __wakeup() {
        throw new Exception("Cannot unserialize singleton");
    }
    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    public function getConnection() {
        return $this->pdo;
    }
    
    public function query($sql, $params = []) {
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            $this->logError($e, $sql);
            throw $e;
        }
    }
    
    public function queryOne($sql, $params = []) {
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetch();
        } catch (PDOException $e) {
            $this->logError($e, $sql);
            throw $e;
        }
    }
    
    public function execute($sql, $params = []) {
        try {
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute($params);
        } catch (PDOException $e) {
            $this->logError($e, $sql);
            throw $e;
        }
    }
    
    public function lastInsertId() {
        return $this->pdo->lastInsertId();
    }
    
    public function beginTransaction() {
        return $this->pdo->beginTransaction();
    }
    
    public function commit() {
        return $this->pdo->commit();
    }
    
    public function rollback() {
        return $this->pdo->rollBack();
    }
    
    public function inTransaction() {
        return $this->pdo->inTransaction();
    }
    
    private function logError($e, $sql) {
        $errorMessage = sprintf(
            "[%s] Database Error: %s\nSQL: %s\nFile: %s\nLine: %s\n",
            date('Y-m-d H:i:s'),
            $e->getMessage(),
            $sql,
            $e->getFile(),
            $e->getLine()
        );
        error_log($errorMessage);
    }
    
    public function testConnection() {
        try {
            $this->pdo->query('SELECT 1');
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }
}

function db() {
    return Database::getInstance();
}

function pdo() {
    return Database::getInstance()->getConnection();
}
