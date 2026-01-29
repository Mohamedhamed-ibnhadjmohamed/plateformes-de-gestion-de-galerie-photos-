<?php

class Album extends Model {
    protected $table = 'albums';
    
    public function __construct() {
        parent::__construct();
    }
    
    public function getWithDetails($id) {
        $sql = "SELECT a.*, u.username, u.first_name, u.last_name,
                       (SELECT COUNT(*) FROM photos p WHERE p.album_id = a.id) as photo_count,
                       (SELECT filename FROM photos p WHERE p.album_id = a.id LIMIT 1) as preview_photo
                FROM albums a 
                JOIN users u ON a.user_id = u.id 
                WHERE a.id = ?";
        
        return $this->db->fetch($sql, [$id]);
    }
    
    public function getPhotos($albumId, $limit = null, $offset = 0) {
        $limitClause = $limit ? "LIMIT $limit OFFSET $offset" : '';
        
        $sql = "SELECT p.*, u.username 
                FROM photos p 
                JOIN users u ON p.user_id = u.id 
                WHERE p.album_id = ? 
                ORDER BY p.created_at DESC 
                $limitClause";
        
        return $this->db->fetchAll($sql, [$albumId]);
    }
    
    public function getPhotoCount($albumId) {
        $sql = "SELECT COUNT(*) as count FROM photos WHERE album_id = ?";
        $result = $this->db->fetch($sql, [$albumId]);
        return $result['count'];
    }
    
    public function getUserAlbums($userId, $includePrivate = false) {
        $privacyClause = $includePrivate ? "" : "AND is_public = 1";
        
        $sql = "SELECT a.*, (SELECT COUNT(*) FROM photos p WHERE p.album_id = a.id) as photo_count
                FROM albums a 
                WHERE a.user_id = ? $privacyClause 
                ORDER BY a.created_at DESC";
        
        return $this->db->fetchAll($sql, [$userId]);
    }
    
    public function getPublicAlbums($limit = 12, $offset = 0) {
        $sql = "SELECT a.*, u.username, u.first_name, u.last_name,
                       (SELECT COUNT(*) FROM photos p WHERE p.album_id = a.id) as photo_count,
                       (SELECT filename FROM photos p WHERE p.album_id = a.id LIMIT 1) as preview_photo
                FROM albums a 
                JOIN users u ON a.user_id = u.id 
                WHERE a.is_public = 1 
                ORDER BY a.created_at DESC 
                LIMIT $limit OFFSET $offset";
        
        return $this->db->fetchAll($sql);
    }
    
    public function getPublicAlbumsCount() {
        $sql = "SELECT COUNT(*) as count FROM albums WHERE is_public = 1";
        $result = $this->db->fetch($sql);
        return $result['count'];
    }
    
    public function updateCoverPhoto($albumId, $photoId) {
        $sql = "UPDATE albums SET cover_photo_id = ? WHERE id = ?";
        $this->db->query($sql, [$photoId, $albumId]);
    }
    
    public function search($query, $limit = 12, $offset = 0) {
        $sql = "SELECT a.*, u.username, u.first_name, u.last_name,
                       (SELECT COUNT(*) FROM photos p WHERE p.album_id = a.id) as photo_count
                FROM albums a 
                JOIN users u ON a.user_id = u.id 
                WHERE a.is_public = 1 
                AND (a.title LIKE ? OR a.description LIKE ?)
                ORDER BY a.created_at DESC 
                LIMIT $limit OFFSET $offset";
        
        $searchTerm = "%$query%";
        return $this->db->fetchAll($sql, [$searchTerm, $searchTerm]);
    }
    
    public function searchCount($query) {
        $sql = "SELECT COUNT(*) as count 
                FROM albums a 
                WHERE a.is_public = 1 
                AND (a.title LIKE ? OR a.description LIKE ?)";
        
        $searchTerm = "%$query%";
        $result = $this->db->fetch($sql, [$searchTerm, $searchTerm]);
        return $result['count'];
    }
    
    public function getPopularAlbums($limit = 10) {
        $sql = "SELECT a.*, u.username, u.first_name, u.last_name,
                       (SELECT COUNT(*) FROM photos p WHERE p.album_id = a.id) as photo_count,
                       (SELECT SUM(views_count) FROM photos p WHERE p.album_id = a.id) as total_views
                FROM albums a 
                JOIN users u ON a.user_id = u.id 
                WHERE a.is_public = 1 
                ORDER BY total_views DESC 
                LIMIT $limit";
        
        return $this->db->fetchAll($sql);
    }
    
    public function getRecentAlbums($limit = 10) {
        $sql = "SELECT a.*, u.username, u.first_name, u.last_name,
                       (SELECT COUNT(*) FROM photos p WHERE p.album_id = a.id) as photo_count
                FROM albums a 
                JOIN users u ON a.user_id = u.id 
                WHERE a.is_public = 1 
                ORDER BY a.created_at DESC 
                LIMIT $limit";
        
        return $this->db->fetchAll($sql);
    }
    
    public function getAllAlbums($limit = 12, $offset = 0) {
        $sql = "SELECT a.*, u.username, u.first_name, u.last_name,
                       (SELECT COUNT(*) FROM photos p WHERE p.album_id = a.id) as photo_count
                FROM albums a 
                JOIN users u ON a.user_id = u.id 
                ORDER BY a.created_at DESC 
                LIMIT $limit OFFSET $offset";
        
        return $this->db->fetchAll($sql);
    }
    
    public function getAllAlbumsCount() {
        $sql = "SELECT COUNT(*) as count FROM albums";
        $result = $this->db->fetch($sql);
        return $result['count'];
    }
}
