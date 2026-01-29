<?php

class ActivityLog extends Model {
    protected $table = 'activity_logs';
    
    public function __construct() {
        parent::__construct();
    }
    
    public function logActivity($userId, $action, $resourceType, $resourceId, $details = null) {
        $data = [
            'user_id' => $userId,
            'action' => $action,
            'resource_type' => $resourceType,
            'resource_id' => $resourceId,
            'details' => $details,
            'ip_address' => $_SERVER['REMOTE_ADDR'] ?? null,
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? null
        ];
        
        return $this->create($data);
    }
    
    public function getUserActivity($userId, $limit = 20, $offset = 0) {
        $sql = "SELECT al.*, 
                       CASE 
                           WHEN al.resource_type = 'photo' THEN (SELECT title FROM photos WHERE id = al.resource_id)
                           WHEN al.resource_type = 'album' THEN (SELECT title FROM albums WHERE id = al.resource_id)
                           WHEN al.resource_type = 'user' THEN (SELECT username FROM users WHERE id = al.resource_id)
                           ELSE NULL
                       END as resource_title
                FROM activity_logs al 
                WHERE al.user_id = ? 
                ORDER BY al.created_at DESC 
                LIMIT $limit OFFSET $offset";
        
        return $this->db->fetchAll($sql, [$userId]);
    }
    
    public function getUserActivityCount($userId) {
        $sql = "SELECT COUNT(*) as count FROM activity_logs WHERE user_id = ?";
        $result = $this->db->fetch($sql, [$userId]);
        return $result['count'];
    }
    
    public function getAllActivity($limit = 50, $offset = 0) {
        $sql = "SELECT al.*, u.username, u.first_name, u.last_name,
                       CASE 
                           WHEN al.resource_type = 'photo' THEN (SELECT title FROM photos WHERE id = al.resource_id)
                           WHEN al.resource_type = 'album' THEN (SELECT title FROM albums WHERE id = al.resource_id)
                           WHEN al.resource_type = 'user' THEN (SELECT username FROM users WHERE id = al.resource_id)
                           ELSE NULL
                       END as resource_title
                FROM activity_logs al 
                LEFT JOIN users u ON al.user_id = u.id 
                ORDER BY al.created_at DESC 
                LIMIT $limit OFFSET $offset";
        
        return $this->db->fetchAll($sql);
    }
    
    public function getAllActivityCount() {
        $sql = "SELECT COUNT(*) as count FROM activity_logs";
        $result = $this->db->fetch($sql);
        return $result['count'];
    }
    
    public function getActivityByType($action, $limit = 20, $offset = 0) {
        $sql = "SELECT al.*, u.username, u.first_name, u.last_name,
                       CASE 
                           WHEN al.resource_type = 'photo' THEN (SELECT title FROM photos WHERE id = al.resource_id)
                           WHEN al.resource_type = 'album' THEN (SELECT title FROM albums WHERE id = al.resource_id)
                           WHEN al.resource_type = 'user' THEN (SELECT username FROM users WHERE id = al.resource_id)
                           ELSE NULL
                       END as resource_title
                FROM activity_logs al 
                LEFT JOIN users u ON al.user_id = u.id 
                WHERE al.action = ? 
                ORDER BY al.created_at DESC 
                LIMIT $limit OFFSET $offset";
        
        return $this->db->fetchAll($sql, [$action]);
    }
    
    public function getActivityByResource($resourceType, $resourceId, $limit = 20, $offset = 0) {
        $sql = "SELECT al.*, u.username, u.first_name, u.last_name
                FROM activity_logs al 
                LEFT JOIN users u ON al.user_id = u.id 
                WHERE al.resource_type = ? AND al.resource_id = ? 
                ORDER BY al.created_at DESC 
                LIMIT $limit OFFSET $offset";
        
        return $this->db->fetchAll($sql, [$resourceType, $resourceId]);
    }
    
    public function getRecentActivity($limit = 10) {
        $sql = "SELECT al.*, u.username, u.first_name, u.last_name,
                       CASE 
                           WHEN al.resource_type = 'photo' THEN (SELECT title FROM photos WHERE id = al.resource_id)
                           WHEN al.resource_type = 'album' THEN (SELECT title FROM albums WHERE id = al.resource_id)
                           WHEN al.resource_type = 'user' THEN (SELECT username FROM users WHERE id = al.resource_id)
                           ELSE NULL
                       END as resource_title
                FROM activity_logs al 
                LEFT JOIN users u ON al.user_id = u.id 
                ORDER BY al.created_at DESC 
                LIMIT $limit";
        
        return $this->db->fetchAll($sql);
    }
    
    public function getActivityStats($days = 30) {
        $sql = "SELECT 
                    COUNT(*) as total_activities,
                    COUNT(DISTINCT user_id) as unique_users,
                    COUNT(DISTINCT resource_type) as resource_types,
                    COUNT(CASE WHEN action IN ('create', 'upload') THEN 1 END) as creations,
                    COUNT(CASE WHEN action IN ('update', 'edit') THEN 1 END) as updates,
                    COUNT(CASE WHEN action = 'delete' THEN 1 END) as deletions
                FROM activity_logs 
                WHERE created_at >= DATE_SUB(NOW(), INTERVAL ? DAY)";
        
        return $this->db->fetch($sql, [$days]);
    }
    
    public function getActionStats($days = 30) {
        $sql = "SELECT action, COUNT(*) as count
                FROM activity_logs 
                WHERE created_at >= DATE_SUB(NOW(), INTERVAL ? DAY)
                GROUP BY action 
                ORDER BY count DESC";
        
        return $this->db->fetchAll($sql, [$days]);
    }
    
    public function getResourceTypeStats($days = 30) {
        $sql = "SELECT resource_type, COUNT(*) as count
                FROM activity_logs 
                WHERE created_at >= DATE_SUB(NOW(), INTERVAL ? DAY)
                GROUP BY resource_type 
                ORDER BY count DESC";
        
        return $this->db->fetchAll($sql, [$days]);
    }
    
    public function getDailyActivity($days = 30) {
        $sql = "SELECT DATE(created_at) as date, COUNT(*) as count
                FROM activity_logs 
                WHERE created_at >= DATE_SUB(NOW(), INTERVAL ? DAY)
                GROUP BY DATE(created_at) 
                ORDER BY date DESC";
        
        return $this->db->fetchAll($sql, [$days]);
    }
    
    public function getTopUsers($days = 30, $limit = 10) {
        $sql = "SELECT u.username, u.first_name, u.last_name, COUNT(*) as activity_count
                FROM activity_logs al 
                JOIN users u ON al.user_id = u.id 
                WHERE al.created_at >= DATE_SUB(NOW(), INTERVAL ? DAY)
                GROUP BY al.user_id 
                ORDER BY activity_count DESC 
                LIMIT $limit";
        
        return $this->db->fetchAll($sql, [$days]);
    }
    
    public function cleanupOldLogs($days = 365) {
        $sql = "DELETE FROM activity_logs WHERE created_at < DATE_SUB(NOW(), INTERVAL ? DAY)";
        $this->db->query($sql, [$days]);
        
        return true;
    }
    
    public function searchActivity($query, $limit = 20, $offset = 0) {
        $sql = "SELECT al.*, u.username, u.first_name, u.last_name,
                       CASE 
                           WHEN al.resource_type = 'photo' THEN (SELECT title FROM photos WHERE id = al.resource_id)
                           WHEN al.resource_type = 'album' THEN (SELECT title FROM albums WHERE id = al.resource_id)
                           WHEN al.resource_type = 'user' THEN (SELECT username FROM users WHERE id = al.resource_id)
                           ELSE NULL
                       END as resource_title
                FROM activity_logs al 
                LEFT JOIN users u ON al.user_id = u.id 
                WHERE al.action LIKE ? OR al.details LIKE ? OR u.username LIKE ?
                ORDER BY al.created_at DESC 
                LIMIT $limit OFFSET $offset";
        
        $searchTerm = "%$query%";
        return $this->db->fetchAll($sql, [$searchTerm, $searchTerm, $searchTerm]);
    }
    
    public function searchActivityCount($query) {
        $sql = "SELECT COUNT(*) as count 
                FROM activity_logs al 
                LEFT JOIN users u ON al.user_id = u.id 
                WHERE al.action LIKE ? OR al.details LIKE ? OR u.username LIKE ?";
        
        $searchTerm = "%$query%";
        $result = $this->db->fetch($sql, [$searchTerm, $searchTerm, $searchTerm]);
        return $result['count'];
    }
}
