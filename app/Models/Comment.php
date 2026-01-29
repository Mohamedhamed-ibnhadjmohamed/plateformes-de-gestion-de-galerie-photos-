<?php

class Comment extends Model {
    protected $table = 'comments';
    
    public function __construct() {
        parent::__construct();
    }
    
    public function getPhotoComments($photoId, $limit = 10, $offset = 0) {
        $sql = "SELECT c.*, u.username, u.first_name, u.last_name, u.avatar
                FROM comments c 
                JOIN users u ON c.user_id = u.id 
                WHERE c.photo_id = ? AND c.is_approved = 1 
                ORDER BY c.created_at ASC 
                LIMIT $limit OFFSET $offset";
        
        return $this->db->fetchAll($sql, [$photoId]);
    }
    
    public function getPhotoCommentsCount($photoId) {
        $sql = "SELECT COUNT(*) as count 
                FROM comments 
                WHERE photo_id = ? AND is_approved = 1";
        
        $result = $this->db->fetch($sql, [$photoId]);
        return $result['count'];
    }
    
    public function getAllPhotoComments($photoId, $limit = 10, $offset = 0) {
        $sql = "SELECT c.*, u.username, u.first_name, u.last_name, u.avatar
                FROM comments c 
                JOIN users u ON c.user_id = u.id 
                WHERE c.photo_id = ? 
                ORDER BY c.created_at ASC 
                LIMIT $limit OFFSET $offset";
        
        return $this->db->fetchAll($sql, [$photoId]);
    }
    
    public function getAllPhotoCommentsCount($photoId) {
        $sql = "SELECT COUNT(*) as count FROM comments WHERE photo_id = ?";
        $result = $this->db->fetch($sql, [$photoId]);
        return $result['count'];
    }
    
    public function getUserComments($userId, $limit = 10, $offset = 0) {
        $sql = "SELECT c.*, p.title as photo_title, p.filename, a.title as album_title
                FROM comments c 
                JOIN photos p ON c.photo_id = p.id 
                JOIN albums a ON p.album_id = a.id 
                WHERE c.user_id = ? AND c.is_approved = 1 
                ORDER BY c.created_at DESC 
                LIMIT $limit OFFSET $offset";
        
        return $this->db->fetchAll($sql, [$userId]);
    }
    
    public function getUserCommentsCount($userId) {
        $sql = "SELECT COUNT(*) as count 
                FROM comments 
                WHERE user_id = ? AND is_approved = 1";
        
        $result = $this->db->fetch($sql, [$userId]);
        return $result['count'];
    }
    
    public function getPendingComments($limit = 20, $offset = 0) {
        $sql = "SELECT c.*, u.username, u.first_name, u.last_name,
                       p.title as photo_title, p.filename
                FROM comments c 
                JOIN users u ON c.user_id = u.id 
                JOIN photos p ON c.photo_id = p.id 
                WHERE c.is_approved = 0 
                ORDER BY c.created_at DESC 
                LIMIT $limit OFFSET $offset";
        
        return $this->db->fetchAll($sql);
    }
    
    public function getPendingCommentsCount() {
        $sql = "SELECT COUNT(*) as count FROM comments WHERE is_approved = 0";
        $result = $this->db->fetch($sql);
        return $result['count'];
    }
    
    public function approve($id) {
        $sql = "UPDATE comments SET is_approved = 1 WHERE id = ?";
        $this->db->query($sql, [$id]);
        
        return true;
    }
    
    public function unapprove($id) {
        $sql = "UPDATE comments SET is_approved = 0 WHERE id = ?";
        $this->db->query($sql, [$id]);
        
        return true;
    }
    
    public function canEdit($commentId, $userId) {
        $sql = "SELECT user_id FROM comments WHERE id = ?";
        $result = $this->db->fetch($sql, [$commentId]);
        
        return $result && $result['user_id'] == $userId;
    }
    
    public function canDelete($commentId, $userId, $userRole) {
        $sql = "SELECT c.user_id, p.user_id as photo_owner_id 
                FROM comments c 
                JOIN photos p ON c.photo_id = p.id 
                WHERE c.id = ?";
        
        $result = $this->db->fetch($sql, [$commentId]);
        
        if (!$result) {
            return false;
        }
        
        return $result['user_id'] == $userId || 
               $result['photo_owner_id'] == $userId || 
               $userRole === 'admin';
    }
    
    public function getRecentComments($limit = 10) {
        $sql = "SELECT c.*, u.username, u.first_name, u.last_name, u.avatar,
                       p.title as photo_title, p.filename
                FROM comments c 
                JOIN users u ON c.user_id = u.id 
                JOIN photos p ON c.photo_id = p.id 
                WHERE c.is_approved = 1 
                ORDER BY c.created_at DESC 
                LIMIT $limit";
        
        return $this->db->fetchAll($sql);
    }
    
    public function getCommentStats($photoId) {
        $sql = "SELECT 
                    COUNT(*) as total_comments,
                    COUNT(CASE WHEN is_approved = 1 THEN 1 END) as approved_comments,
                    COUNT(CASE WHEN is_approved = 0 THEN 1 END) as pending_comments,
                    COUNT(DISTINCT user_id) as unique_commenters
                FROM comments 
                WHERE photo_id = ?";
        
        return $this->db->fetch($sql, [$photoId]);
    }
    
    public function getUserCommentStats($userId) {
        $sql = "SELECT 
                    COUNT(*) as total_comments,
                    COUNT(CASE WHEN is_approved = 1 THEN 1 END) as approved_comments,
                    COUNT(CASE WHEN is_approved = 0 THEN 1 END) as pending_comments,
                    COUNT(DISTINCT photo_id) as photos_commented
                FROM comments 
                WHERE user_id = ?";
        
        return $this->db->fetch($sql, [$userId]);
    }
    
    public function searchComments($query, $limit = 20, $offset = 0) {
        $sql = "SELECT c.*, u.username, u.first_name, u.last_name,
                       p.title as photo_title, p.filename
                FROM comments c 
                JOIN users u ON c.user_id = u.id 
                JOIN photos p ON c.photo_id = p.id 
                WHERE c.is_approved = 1 
                AND c.content LIKE ? 
                ORDER BY c.created_at DESC 
                LIMIT $limit OFFSET $offset";
        
        return $this->db->fetchAll($sql, ["%$query%"]);
    }
    
    public function searchCommentsCount($query) {
        $sql = "SELECT COUNT(*) as count 
                FROM comments c 
                WHERE c.is_approved = 1 
                AND c.content LIKE ?";
        
        $result = $this->db->fetch($sql, ["%$query%"]);
        return $result['count'];
    }
    
    public function cleanupOrphanedComments() {
        // Remove comments for photos that no longer exist
        $sql = "DELETE c FROM comments c 
                LEFT JOIN photos p ON c.photo_id = p.id 
                WHERE p.id IS NULL";
        
        $this->db->query($sql);
        
        // Remove comments from users that no longer exist
        $sql = "DELETE c FROM comments c 
                LEFT JOIN users u ON c.user_id = u.id 
                WHERE u.id IS NULL";
        
        $this->db->query($sql);
    }
    
    public function getTotalCommentsCount() {
        $sql = "SELECT COUNT(*) as count FROM comments";
        $result = $this->db->fetch($sql);
        return $result['count'];
    }
    
    public function getAllComments($limit = 20, $offset = 0) {
        $sql = "SELECT c.*, u.username, u.first_name, u.last_name, p.title as photo_title,
                       a.title as album_title
                FROM comments c 
                JOIN users u ON c.user_id = u.id 
                JOIN photos p ON c.photo_id = p.id 
                JOIN albums a ON p.album_id = a.id 
                ORDER BY c.created_at DESC 
                LIMIT $limit OFFSET $offset";
        
        return $this->db->fetchAll($sql);
    }
    
    public function getAllCommentsCount() {
        $sql = "SELECT COUNT(*) as count FROM comments";
        $result = $this->db->fetch($sql);
        return $result['count'];
    }
}
