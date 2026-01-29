<?php

class Photo extends Model {
    protected $table = 'photos';
    
    public function __construct() {
        parent::__construct();
    }
    
    public function getWithDetails($id) {
        $sql = "SELECT p.*, u.username, u.first_name, u.last_name, a.title as album_title,
                       (SELECT COUNT(*) FROM favorites f WHERE f.photo_id = p.id) as favorite_count,
                       (SELECT COUNT(*) FROM comments c WHERE c.photo_id = p.id AND c.is_approved = 1) as comment_count
                FROM photos p 
                JOIN users u ON p.user_id = u.id 
                JOIN albums a ON p.album_id = a.id 
                WHERE p.id = ?";
        
        return $this->db->fetch($sql, [$id]);
    }
    
    public function getPublicPhotos($limit = 12, $offset = 0) {
        $sql = "SELECT p.*, u.username, a.title as album_title,
                       (SELECT COUNT(*) FROM favorites f WHERE f.photo_id = p.id) as favorite_count,
                       (SELECT COUNT(*) FROM comments c WHERE c.photo_id = p.id AND c.is_approved = 1) as comment_count
                FROM photos p 
                JOIN users u ON p.user_id = u.id 
                JOIN albums a ON p.album_id = a.id 
                WHERE p.is_public = 1 
                ORDER BY p.created_at DESC 
                LIMIT $limit OFFSET $offset";
        
        return $this->db->fetchAll($sql);
    }
    
    public function getPublicPhotosCount() {
        $sql = "SELECT COUNT(*) as count FROM photos WHERE is_public = 1";
        $result = $this->db->fetch($sql);
        return $result['count'];
    }
    
    public function getUserPhotos($userId, $includePrivate = false) {
        $privacyClause = $includePrivate ? "" : "AND is_public = 1";
        
        $sql = "SELECT p.*, a.title as album_title
                FROM photos p 
                JOIN albums a ON p.album_id = a.id 
                WHERE p.user_id = ? $privacyClause 
                ORDER BY p.created_at DESC";
        
        return $this->db->fetchAll($sql, [$userId]);
    }
    
    public function getAlbumPhotos($albumId, $includePrivate = false) {
        $privacyClause = $includePrivate ? "" : "AND is_public = 1";
        
        $sql = "SELECT p.*, u.username 
                FROM photos p 
                JOIN users u ON p.user_id = u.id 
                WHERE p.album_id = ? $privacyClause 
                ORDER BY p.created_at DESC";
        
        return $this->db->fetchAll($sql, [$albumId]);
    }
    
    public function getFavoritePhotos($userId, $limit = 12, $offset = 0) {
        $sql = "SELECT p.*, u.username, a.title as album_title,
                       f.created_at as favorited_at
                FROM favorites f 
                JOIN photos p ON f.photo_id = p.id 
                JOIN users u ON p.user_id = u.id 
                JOIN albums a ON p.album_id = a.id 
                WHERE f.user_id = ? AND p.is_public = 1 
                ORDER BY f.created_at DESC 
                LIMIT $limit OFFSET $offset";
        
        return $this->db->fetchAll($sql, [$userId]);
    }
    
    public function getFavoritePhotosCount($userId) {
        $sql = "SELECT COUNT(*) as count 
                FROM favorites f 
                JOIN photos p ON f.photo_id = p.id 
                WHERE f.user_id = ? AND p.is_public = 1";
        
        $result = $this->db->fetch($sql, [$userId]);
        return $result['count'];
    }
    
    public function search($query, $limit = 12, $offset = 0) {
        $sql = "SELECT p.*, u.username, a.title as album_title,
                       (SELECT COUNT(*) FROM favorites f WHERE f.photo_id = p.id) as favorite_count
                FROM photos p 
                JOIN users u ON p.user_id = u.id 
                JOIN albums a ON p.album_id = a.id 
                WHERE p.is_public = 1 
                AND (p.title LIKE ? OR p.description LIKE ?)
                ORDER BY p.created_at DESC 
                LIMIT $limit OFFSET $offset";
        
        $searchTerm = "%$query%";
        return $this->db->fetchAll($sql, [$searchTerm, $searchTerm]);
    }
    
    public function searchCount($query) {
        $sql = "SELECT COUNT(*) as count 
                FROM photos p 
                WHERE p.is_public = 1 
                AND (p.title LIKE ? OR p.description LIKE ?)";
        
        $searchTerm = "%$query%";
        $result = $this->db->fetch($sql, [$searchTerm, $searchTerm]);
        return $result['count'];
    }
    
    public function getPopularPhotos($limit = 10) {
        $sql = "SELECT p.*, u.username, a.title as album_title,
                       (SELECT COUNT(*) FROM favorites f WHERE f.photo_id = p.id) as favorite_count
                FROM photos p 
                JOIN users u ON p.user_id = u.id 
                JOIN albums a ON p.album_id = a.id 
                WHERE p.is_public = 1 
                ORDER BY p.views_count DESC, p.created_at DESC 
                LIMIT $limit";
        
        return $this->db->fetchAll($sql);
    }
    
    public function getRecentPhotos($limit = 10) {
        $sql = "SELECT p.*, u.username, a.title as album_title,
                       (SELECT COUNT(*) FROM favorites f WHERE f.photo_id = p.id) as favorite_count
                FROM photos p 
                JOIN users u ON p.user_id = u.id 
                JOIN albums a ON p.album_id = a.id 
                WHERE p.is_public = 1 
                ORDER BY p.created_at DESC 
                LIMIT $limit";
        
        return $this->db->fetchAll($sql);
    }
    
    public function incrementViews($id) {
        $sql = "UPDATE photos SET views_count = views_count + 1 WHERE id = ?";
        $this->db->query($sql, [$id]);
    }
    
    public function getPhotosByTag($tagId, $limit = 12, $offset = 0) {
        $sql = "SELECT p.*, u.username, a.title as album_title,
                       (SELECT COUNT(*) FROM favorites f WHERE f.photo_id = p.id) as favorite_count
                FROM photo_tags pt 
                JOIN photos p ON pt.photo_id = p.id 
                JOIN users u ON p.user_id = u.id 
                JOIN albums a ON p.album_id = a.id 
                WHERE pt.tag_id = ? AND p.is_public = 1 
                ORDER BY p.created_at DESC 
                LIMIT $limit OFFSET $offset";
        
        return $this->db->fetchAll($sql, [$tagId]);
    }
    
    public function getPhotosByTagCount($tagId) {
        $sql = "SELECT COUNT(*) as count 
                FROM photo_tags pt 
                JOIN photos p ON pt.photo_id = p.id 
                WHERE pt.tag_id = ? AND p.is_public = 1";
        
        $result = $this->db->fetch($sql, [$tagId]);
        return $result['count'];
    }
    
    public function getPhotosWithTags($photoIds) {
        if (empty($photoIds)) {
            return [];
        }
        
        $placeholders = str_repeat('?,', count($photoIds) - 1) . '?';
        
        $sql = "SELECT p.*, GROUP_CONCAT(t.name) as tags
                FROM photos p 
                LEFT JOIN photo_tags pt ON p.id = pt.photo_id 
                LEFT JOIN tags t ON pt.tag_id = t.id 
                WHERE p.id IN ($placeholders)
                GROUP BY p.id";
        
        return $this->db->fetchAll($sql, $photoIds);
    }
    
    public function getAllPhotos($limit = 12, $offset = 0) {
        $sql = "SELECT p.*, u.username, a.title as album_title,
                       (SELECT COUNT(*) FROM favorites f WHERE f.photo_id = p.id) as favorite_count,
                       (SELECT COUNT(*) FROM comments c WHERE c.photo_id = p.id AND c.is_approved = 1) as comment_count
                FROM photos p 
                JOIN users u ON p.user_id = u.id 
                JOIN albums a ON p.album_id = a.id 
                ORDER BY p.created_at DESC 
                LIMIT $limit OFFSET $offset";
        
        return $this->db->fetchAll($sql);
    }
    
    public function getAllPhotosCount() {
        $sql = "SELECT COUNT(*) as count FROM photos";
        $result = $this->db->fetch($sql);
        return $result['count'];
    }
}
