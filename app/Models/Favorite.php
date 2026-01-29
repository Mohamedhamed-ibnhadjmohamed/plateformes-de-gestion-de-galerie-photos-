<?php

class Favorite extends Model {
    protected $table = 'favorites';
    
    public function __construct() {
        parent::__construct();
    }
    
    public function isFavorited($userId, $photoId) {
        $sql = "SELECT id FROM favorites WHERE user_id = ? AND photo_id = ?";
        $result = $this->db->fetch($sql, [$userId, $photoId]);
        return !empty($result);
    }
    
    public function addFavorite($userId, $photoId) {
        if ($this->isFavorited($userId, $photoId)) {
            return false; // Already favorited
        }
        
        $data = [
            'user_id' => $userId,
            'photo_id' => $photoId
        ];
        
        return $this->create($data);
    }
    
    public function removeFavorite($userId, $photoId) {
        $sql = "DELETE FROM favorites WHERE user_id = ? AND photo_id = ?";
        $this->db->query($sql, [$userId, $photoId]);
        
        return true;
    }
    
    public function toggleFavorite($userId, $photoId) {
        if ($this->isFavorited($userId, $photoId)) {
            $this->removeFavorite($userId, $photoId);
            return false; // Removed
        } else {
            $this->addFavorite($userId, $photoId);
            return true; // Added
        }
    }
    
    public function getUserFavorites($userId, $limit = 12, $offset = 0) {
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
    
    public function getUserFavoritesCount($userId) {
        $sql = "SELECT COUNT(*) as count 
                FROM favorites f 
                JOIN photos p ON f.photo_id = p.id 
                WHERE f.user_id = ? AND p.is_public = 1";
        
        $result = $this->db->fetch($sql, [$userId]);
        return $result['count'];
    }
    
    public function getPhotoFavorites($photoId, $limit = 10, $offset = 0) {
        $sql = "SELECT u.id, u.username, u.first_name, u.last_name, u.avatar,
                       f.created_at as favorited_at
                FROM favorites f 
                JOIN users u ON f.user_id = u.id 
                WHERE f.photo_id = ? 
                ORDER BY f.created_at DESC 
                LIMIT $limit OFFSET $offset";
        
        return $this->db->fetchAll($sql, [$photoId]);
    }
    
    public function getPhotoFavoritesCount($photoId) {
        $sql = "SELECT COUNT(*) as count FROM favorites WHERE photo_id = ?";
        $result = $this->db->fetch($sql, [$photoId]);
        return $result['count'];
    }
    
    public function getPopularPhotos($limit = 10) {
        $sql = "SELECT p.*, u.username, a.title as album_title,
                       COUNT(f.id) as favorite_count
                FROM favorites f 
                JOIN photos p ON f.photo_id = p.id 
                JOIN users u ON p.user_id = u.id 
                JOIN albums a ON p.album_id = a.id 
                WHERE p.is_public = 1 
                GROUP BY p.id 
                ORDER BY favorite_count DESC, p.created_at DESC 
                LIMIT $limit";
        
        return $this->db->fetchAll($sql);
    }
    
    public function getRecentFavorites($limit = 10) {
        $sql = "SELECT p.*, u.username, a.title as album_title,
                       f.user_id as favorited_by_id,
                       fu.username as favorited_by_username
                FROM favorites f 
                JOIN photos p ON f.photo_id = p.id 
                JOIN users u ON p.user_id = u.id 
                JOIN albums a ON p.album_id = a.id 
                JOIN users fu ON f.user_id = fu.id 
                WHERE p.is_public = 1 
                ORDER BY f.created_at DESC 
                LIMIT $limit";
        
        return $this->db->fetchAll($sql);
    }
    
    public function getUserFavoriteStats($userId) {
        $sql = "SELECT 
                    COUNT(*) as total_favorites,
                    COUNT(DISTINCT p.user_id) as unique_photographers,
                    COUNT(DISTINCT p.album_id) as unique_albums
                FROM favorites f 
                JOIN photos p ON f.photo_id = p.id 
                WHERE f.user_id = ? AND p.is_public = 1";
        
        return $this->db->fetch($sql, [$userId]);
    }
    
    public function getPhotoFavoriteUsers($photoId) {
        $sql = "SELECT u.id, u.username, u.first_name, u.last_name, u.avatar,
                       f.created_at
                FROM favorites f 
                JOIN users u ON f.user_id = u.id 
                WHERE f.photo_id = ? 
                ORDER BY f.created_at DESC";
        
        return $this->db->fetchAll($sql, [$photoId]);
    }
    
    public function cleanupOrphanedFavorites() {
        // Remove favorites for photos that no longer exist
        $sql = "DELETE f FROM favorites f 
                LEFT JOIN photos p ON f.photo_id = p.id 
                WHERE p.id IS NULL";
        
        $this->db->query($sql);
        
        // Remove favorites from users that no longer exist
        $sql = "DELETE f FROM favorites f 
                LEFT JOIN users u ON f.user_id = u.id 
                WHERE u.id IS NULL";
        
        $this->db->query($sql);
    }
}
