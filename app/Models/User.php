<?php

class User extends Model {
    protected $table = 'users';
    
    public function __construct() {
        parent::__construct();
    }
    
    public function findByEmail($email) {
        $sql = "SELECT * FROM users WHERE email = ?";
        return $this->db->fetch($sql, [$email]);
    }
    
    public function findByUsername($username) {
        $sql = "SELECT * FROM users WHERE username = ?";
        return $this->db->fetch($sql, [$username]);
    }
    
    public function getWithStats($id) {
        $sql = "SELECT u.*, 
                       (SELECT COUNT(*) FROM albums WHERE user_id = ?) as album_count,
                       (SELECT COUNT(*) FROM photos WHERE user_id = ?) as photo_count,
                       (SELECT COUNT(*) FROM favorites WHERE user_id = ?) as favorite_count
                FROM users u 
                WHERE u.id = ?";
        
        return $this->db->fetch($sql, [$id, $id, $id, $id]);
    }
    
    public function getPublicProfile($id) {
        $sql = "SELECT id, username, first_name, last_name, avatar, bio, created_at 
                FROM users 
                WHERE id = ? AND is_active = 1";
        
        return $this->db->fetch($sql, [$id]);
    }
    
    public function updateLastLogin($id) {
        $sql = "UPDATE users SET last_login = NOW() WHERE id = ?";
        $this->db->query($sql, [$id]);
    }
    
    public function updatePassword($id, $passwordHash) {
        $sql = "UPDATE users SET password_hash = ? WHERE id = ?";
        $this->db->query($sql, [$passwordHash, $id]);
    }
    
    public function updateProfile($id, $data) {
        $allowedFields = ['first_name', 'last_name', 'bio', 'avatar'];
        $updateData = [];
        
        foreach ($allowedFields as $field) {
            if (isset($data[$field])) {
                $updateData[$field] = $data[$field];
            }
        }
        
        if (empty($updateData)) {
            return false;
        }
        
        return $this->update($id, $updateData);
    }
    
    public function activate($id) {
        $sql = "UPDATE users SET is_active = 1, email_verified = 1 WHERE id = ?";
        $this->db->query($sql, [$id]);
    }
    
    public function deactivate($id) {
        $sql = "UPDATE users SET is_active = 0 WHERE id = ?";
        $this->db->query($sql, [$id]);
    }
    
    public function getAllUsers($limit = 20, $offset = 0) {
        $sql = "SELECT u.*, 
                       (SELECT COUNT(*) FROM albums WHERE user_id = u.id) as album_count,
                       (SELECT COUNT(*) FROM photos WHERE user_id = u.id) as photo_count
                FROM users u 
                ORDER BY u.created_at DESC 
                LIMIT $limit OFFSET $offset";
        
        return $this->db->fetchAll($sql);
    }
    
    public function getUsersCount() {
        $sql = "SELECT COUNT(*) as count FROM users";
        $result = $this->db->fetch($sql);
        return $result['count'];
    }
    
    public function searchUsers($query, $limit = 20, $offset = 0) {
        $sql = "SELECT u.id, u.username, u.first_name, u.last_name, u.avatar,
                       (SELECT COUNT(*) FROM albums WHERE user_id = u.id AND is_public = 1) as public_album_count
                FROM users u 
                WHERE u.is_active = 1 
                AND (u.username LIKE ? OR u.first_name LIKE ? OR u.last_name LIKE ?)
                ORDER BY u.username ASC 
                LIMIT $limit OFFSET $offset";
        
        $searchTerm = "%$query%";
        return $this->db->fetchAll($sql, [$searchTerm, $searchTerm, $searchTerm]);
    }
    
    public function searchUsersCount($query) {
        $sql = "SELECT COUNT(*) as count 
                FROM users u 
                WHERE u.is_active = 1 
                AND (u.username LIKE ? OR u.first_name LIKE ? OR u.last_name LIKE ?)";
        
        $searchTerm = "%$query%";
        $result = $this->db->fetch($sql, [$searchTerm, $searchTerm, $searchTerm]);
        return $result['count'];
    }
    
    public function getRecentUsers($limit = 10) {
        $sql = "SELECT id, username, first_name, last_name, avatar, created_at 
                FROM users 
                WHERE is_active = 1 
                ORDER BY created_at DESC 
                LIMIT $limit";
        
        return $this->db->fetchAll($sql);
    }
    
    public function getActiveUsers($limit = 10) {
        $sql = "SELECT u.id, u.username, u.first_name, u.last_name, u.avatar, u.last_login,
                       (SELECT COUNT(*) FROM photos WHERE user_id = u.id AND created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)) as recent_photos
                FROM users u 
                WHERE u.is_active = 1 
                AND u.last_login >= DATE_SUB(NOW(), INTERVAL 30 DAY)
                ORDER BY u.last_login DESC 
                LIMIT $limit";
        
        return $this->db->fetchAll($sql);
    }
    
    public function changeRole($id, $role) {
        if (!in_array($role, ['admin', 'user'])) {
            return false;
        }
        
        $sql = "UPDATE users SET role = ? WHERE id = ?";
        $this->db->query($sql, [$role, $id]);
        
        return true;
    }
    
    public function verifyEmail($id) {
        $sql = "UPDATE users SET email_verified = 1 WHERE id = ?";
        $this->db->query($sql, [$id]);
    }
    
    public function isEmailVerified($id) {
        $sql = "SELECT email_verified FROM users WHERE id = ?";
        $result = $this->db->fetch($sql, [$id]);
        return $result['email_verified'] ?? false;
    }
    
    public function getUserStats($userId) {
        $sql = "SELECT 
                    (SELECT COUNT(*) FROM photos WHERE user_id = ?) as photos_count,
                    (SELECT COUNT(*) FROM albums WHERE user_id = ?) as albums_count,
                    (SELECT COUNT(*) FROM comments WHERE user_id = ? AND is_approved = 1) as comments_count,
                    (SELECT COUNT(*) FROM favorites WHERE user_id = ?) as favorites_count";
        
        $result = $this->db->fetch($sql, [$userId, $userId, $userId, $userId]);
        return $result;
    }
    
    public function getUserWithStats($id) {
        $sql = "SELECT u.*, 
                       (SELECT COUNT(*) FROM photos WHERE user_id = u.id) as photos_count,
                       (SELECT COUNT(*) FROM albums WHERE user_id = u.id) as albums_count,
                       (SELECT COUNT(*) FROM comments WHERE user_id = u.id AND is_approved = 1) as comments_count,
                       (SELECT COUNT(*) FROM favorites WHERE user_id = u.id) as favorites_count
                FROM users u 
                WHERE u.id = ?";
        
        return $this->db->fetch($sql, [$id]);
    }
    
    public function ban($id) {
        $sql = "UPDATE users SET is_banned = 1 WHERE id = ?";
        $this->db->query($sql, [$id]);
        return true;
    }
    
    public function unban($id) {
        $sql = "UPDATE users SET is_banned = 0 WHERE id = ?";
        $this->db->query($sql, [$id]);
        return true;
    }
    
    public function isBanned($id) {
        $sql = "SELECT is_banned FROM users WHERE id = ?";
        $result = $this->db->fetch($sql, [$id]);
        return $result['is_banned'] ?? false;
    }
    
    public function resetPassword($id, $newPassword) {
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $sql = "UPDATE users SET password = ? WHERE id = ?";
        $this->db->query($sql, [$hashedPassword, $id]);
        return true;
    }
    
    public function deleteUser($id) {
        // Delete user's photos
        $sql = "DELETE FROM photos WHERE user_id = ?";
        $this->db->query($sql, [$id]);
        
        // Delete user's albums
        $sql = "DELETE FROM albums WHERE user_id = ?";
        $this->db->query($sql, [$id]);
        
        // Delete user's comments
        $sql = "DELETE FROM comments WHERE user_id = ?";
        $this->db->query($sql, [$id]);
        
        // Delete user's favorites
        $sql = "DELETE FROM favorites WHERE user_id = ?";
        $this->db->query($sql, [$id]);
        
        // Delete user's activity logs
        $sql = "DELETE FROM activity_logs WHERE user_id = ?";
        $this->db->query($sql, [$id]);
        
        // Delete the user
        $sql = "DELETE FROM users WHERE id = ?";
        $this->db->query($sql, [$id]);
        
        return true;
    }
}
