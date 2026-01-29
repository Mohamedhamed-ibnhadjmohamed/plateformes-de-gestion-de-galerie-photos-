<?php

class FavoriteController extends Controller {
    private $favoriteModel;
    
    public function __construct() {
        parent::__construct();
        $this->favoriteModel = new Favorite();
    }
    
    public function index() {
        $this->requireAuth();
        
        $user = getCurrentUser();
        $page = $this->getQueryData('page', 1);
        $limit = 12;
        $offset = ($page - 1) * $limit;
        
        // Get user's favorite photos
        $sql = "SELECT p.*, u.username, a.title as album_title,
                       f.created_at as favorited_at
                FROM favorites f 
                JOIN photos p ON f.photo_id = p.id 
                JOIN users u ON p.user_id = u.id 
                JOIN albums a ON p.album_id = a.id 
                WHERE f.user_id = ? AND p.is_public = 1 
                ORDER BY f.created_at DESC 
                LIMIT $limit OFFSET $offset";
        
        $favorites = $this->favoriteModel->db->fetchAll($sql, [$user['id']]);
        
        // Get total count for pagination
        $countSql = "SELECT COUNT(*) as total 
                    FROM favorites f 
                    JOIN photos p ON f.photo_id = p.id 
                    WHERE f.user_id = ? AND p.is_public = 1";
        
        $total = $this->favoriteModel->db->fetch($countSql, [$user['id']])['total'];
        
        $this->view('favorites/index', [
            'favorites' => $favorites,
            'pagination' => [
                'current' => $page,
                'total' => ceil($total / $limit),
                'limit' => $limit,
                'count' => $total
            ]
        ]);
    }
    
    public function add($photoId) {
        $this->requireAuth();
        
        // Check if photo exists and is public
        $photoSql = "SELECT id FROM photos WHERE id = ? AND is_public = 1";
        $photo = $this->favoriteModel->db->fetch($photoSql, [$photoId]);
        
        if (!$photo) {
            $this->json(['error' => 'Photo not found'], 404);
            return;
        }
        
        $user = getCurrentUser();
        
        // Check if already favorited
        $existingSql = "SELECT id FROM favorites WHERE user_id = ? AND photo_id = ?";
        $existing = $this->favoriteModel->db->fetch($existingSql, [$user['id'], $photoId]);
        
        if ($existing) {
            $this->json(['error' => 'Already favorited'], 400);
            return;
        }
        
        // Add favorite
        $favoriteData = [
            'user_id' => $user['id'],
            'photo_id' => $photoId
        ];
        
        $this->favoriteModel->create($favoriteData);
        
        logActivity($user['id'], 'favorite', 'photo', $photoId);
        
        // Get updated favorite count
        $countSql = "SELECT COUNT(*) as count FROM favorites WHERE photo_id = ?";
        $count = $this->favoriteModel->db->fetch($countSql, [$photoId])['count'];
        
        $this->json([
            'success' => true,
            'message' => 'Photo added to favorites',
            'count' => $count
        ]);
    }
    
    public function remove($photoId) {
        $this->requireAuth();
        
        $user = getCurrentUser();
        
        // Check if favorite exists
        $favoriteSql = "SELECT id FROM favorites WHERE user_id = ? AND photo_id = ?";
        $favorite = $this->favoriteModel->db->fetch($favoriteSql, [$user['id'], $photoId]);
        
        if (!$favorite) {
            $this->json(['error' => 'Favorite not found'], 404);
            return;
        }
        
        // Remove favorite
        $deleteSql = "DELETE FROM favorites WHERE user_id = ? AND photo_id = ?";
        $this->favoriteModel->db->query($deleteSql, [$user['id'], $photoId]);
        
        logActivity($user['id'], 'unfavorite', 'photo', $photoId);
        
        // Get updated favorite count
        $countSql = "SELECT COUNT(*) as count FROM favorites WHERE photo_id = ?";
        $count = $this->favoriteModel->db->fetch($countSql, [$photoId])['count'];
        
        $this->json([
            'success' => true,
            'message' => 'Photo removed from favorites',
            'count' => $count
        ]);
    }
    
    public function toggle($photoId) {
        $this->requireAuth();
        
        $user = getCurrentUser();
        
        // Check if photo exists and is public
        $photoSql = "SELECT id FROM photos WHERE id = ? AND is_public = 1";
        $photo = $this->favoriteModel->db->fetch($photoSql, [$photoId]);
        
        if (!$photo) {
            $this->json(['error' => 'Photo not found'], 404);
            return;
        }
        
        // Check if already favorited
        $existingSql = "SELECT id FROM favorites WHERE user_id = ? AND photo_id = ?";
        $existing = $this->favoriteModel->db->fetch($existingSql, [$user['id'], $photoId]);
        
        if ($existing) {
            // Remove favorite
            $deleteSql = "DELETE FROM favorites WHERE user_id = ? AND photo_id = ?";
            $this->favoriteModel->db->query($deleteSql, [$user['id'], $photoId]);
            
            logActivity($user['id'], 'unfavorite', 'photo', $photoId);
            
            $action = 'removed';
            $message = 'Photo removed from favorites';
        } else {
            // Add favorite
            $favoriteData = [
                'user_id' => $user['id'],
                'photo_id' => $photoId
            ];
            
            $this->favoriteModel->create($favoriteData);
            
            logActivity($user['id'], 'favorite', 'photo', $photoId);
            
            $action = 'added';
            $message = 'Photo added to favorites';
        }
        
        // Get updated favorite count
        $countSql = "SELECT COUNT(*) as count FROM favorites WHERE photo_id = ?";
        $count = $this->favoriteModel->db->fetch($countSql, [$photoId])['count'];
        
        $this->json([
            'success' => true,
            'action' => $action,
            'message' => $message,
            'count' => $count
        ]);
    }
}
