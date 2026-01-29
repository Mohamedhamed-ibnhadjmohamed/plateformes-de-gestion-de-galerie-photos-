<?php

class ApiController extends Controller {
    private $photoModel;
    private $albumModel;
    private $tagModel;
    private $userModel;
    
    public function __construct() {
        parent::__construct();
        
        $this->photoModel = new Photo();
        $this->albumModel = new Album();
        $this->tagModel = new Tag();
        $this->userModel = new User();
    }
    
    public function photos() {
        $page = $this->getQueryData('page', 1);
        $limit = $this->getQueryData('limit', 12);
        $offset = ($page - 1) * $limit;
        
        $photos = $this->photoModel->getPublicPhotos($limit, $offset);
        $total = $this->photoModel->getPublicPhotosCount();
        
        $this->json([
            'success' => true,
            'data' => $photos,
            'pagination' => [
                'current' => $page,
                'limit' => $limit,
                'total' => $total,
                'pages' => ceil($total / $limit)
            ]
        ]);
    }
    
    public function albums() {
        $page = $this->getQueryData('page', 1);
        $limit = $this->getQueryData('limit', 12);
        $offset = ($page - 1) * $limit;
        
        $albums = $this->albumModel->getPublicAlbums($limit, $offset);
        $total = $this->albumModel->getPublicAlbumsCount();
        
        $this->json([
            'success' => true,
            'data' => $albums,
            'pagination' => [
                'current' => $page,
                'limit' => $limit,
                'total' => $total,
                'pages' => ceil($total / $limit)
            ]
        ]);
    }
    
    public function search() {
        $query = $this->getQueryData('q', '');
        $type = $this->getQueryData('type', 'all');
        $page = $this->getQueryData('page', 1);
        $limit = $this->getQueryData('limit', 12);
        $offset = ($page - 1) * $limit;
        
        $results = [];
        
        if (empty($query)) {
            $this->json([
                'success' => false,
                'message' => 'Search query is required'
            ], 400);
            return;
        }
        
        switch ($type) {
            case 'photos':
                $results = $this->photoModel->search($query, $limit, $offset);
                $total = $this->photoModel->searchCount($query);
                break;
                
            case 'albums':
                $results = $this->albumModel->search($query, $limit, $offset);
                $total = $this->albumModel->searchCount($query);
                break;
                
            case 'tags':
                $results = $this->tagModel->searchTags($query, $limit);
                $total = count($results); // Tags search doesn't support pagination yet
                break;
                
            case 'users':
                $results = $this->userModel->searchUsers($query, $limit, $offset);
                $total = $this->userModel->searchUsersCount($query);
                break;
                
            default: // 'all'
                $photoResults = $this->photoModel->search($query, 5, 0);
                $albumResults = $this->albumModel->search($query, 5, 0);
                $tagResults = $this->tagModel->searchTags($query, 5);
                $userResults = $this->userModel->searchUsers($query, 5, 0);
                
                $results = [
                    'photos' => $photoResults,
                    'albums' => $albumResults,
                    'tags' => $tagResults,
                    'users' => $userResults
                ];
                $total = [
                    'photos' => $this->photoModel->searchCount($query),
                    'albums' => $this->albumModel->searchCount($query),
                    'tags' => count($tagResults),
                    'users' => $this->userModel->searchUsersCount($query)
                ];
                break;
        }
        
        $this->json([
            'success' => true,
            'data' => $results,
            'query' => $query,
            'type' => $type,
            'pagination' => is_array($total) ? $total : [
                'current' => $page,
                'limit' => $limit,
                'total' => $total,
                'pages' => ceil($total / $limit)
            ]
        ]);
    }
    
    public function photo($id) {
        $photo = $this->photoModel->getWithDetails($id);
        
        if (!$photo || !$photo['is_public']) {
            $this->json([
                'success' => false,
                'message' => 'Photo not found'
            ], 404);
            return;
        }
        
        // Get additional data
        $photo['tags'] = $this->tagModel->getPhotoTags($id);
        $photo['comments'] = $this->photoModel->db->fetchAll(
            "SELECT c.*, u.username, u.first_name, u.last_name 
             FROM comments c 
             JOIN users u ON c.user_id = u.id 
             WHERE c.photo_id = ? AND c.is_approved = 1 
             ORDER BY c.created_at ASC 
             LIMIT 10",
            [$id]
        );
        
        $this->json([
            'success' => true,
            'data' => $photo
        ]);
    }
    
    public function album($id) {
        $album = $this->albumModel->getWithDetails($id);
        
        if (!$album || !$album['is_public']) {
            $this->json([
                'success' => false,
                'message' => 'Album not found'
            ], 404);
            return;
        }
        
        $page = $this->getQueryData('page', 1);
        $limit = $this->getQueryData('limit', 12);
        $offset = ($page - 1) * $limit;
        
        $album['photos'] = $this->albumModel->getPhotos($id, $limit, $offset);
        $album['photo_count'] = $this->albumModel->getPhotoCount($id);
        
        $this->json([
            'success' => true,
            'data' => $album
        ]);
    }
    
    public function user($id) {
        $user = $this->userModel->getPublicProfile($id);
        
        if (!$user) {
            $this->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
            return;
        }
        
        $page = $this->getQueryData('page', 1);
        $limit = $this->getQueryData('limit', 12);
        $offset = ($page - 1) * $limit;
        
        $user['albums'] = $this->albumModel->getUserAlbums($id, true);
        $user['photos'] = $this->photoModel->getUserPhotos($id, true);
        $user['stats'] = $this->userModel->getWithStats($id);
        
        $this->json([
            'success' => true,
            'data' => $user
        ]);
    }
    
    public function tags() {
        $limit = $this->getQueryData('limit', 50);
        
        $tags = $this->tagModel->getPopularTags($limit);
        
        $this->json([
            'success' => true,
            'data' => $tags
        ]);
    }
    
    public function tag($id) {
        $tag = $this->tagModel->find($id);
        
        if (!$tag) {
            $this->json([
                'success' => false,
                'message' => 'Tag not found'
            ], 404);
            return;
        }
        
        $page = $this->getQueryData('page', 1);
        $limit = $this->getQueryData('limit', 12);
        $offset = ($page - 1) * $limit;
        
        $tag['photos'] = $this->tagModel->getPhotosByTag($id, $limit, $offset);
        $tag['photo_count'] = $this->tagModel->getPhotosByTagCount($id);
        $tag['stats'] = $this->tagModel->getTagStats($id);
        
        $this->json([
            'success' => true,
            'data' => $tag
        ]);
    }
    
    public function stats() {
        $stats = [
            'total_photos' => $this->photoModel->getPublicPhotosCount(),
            'total_albums' => $this->albumModel->getPublicAlbumsCount(),
            'total_users' => $this->userModel->getUsersCount(),
            'total_tags' => count($this->tagModel->getPopularTags(1000)),
            'popular_photos' => $this->photoModel->getPopularPhotos(5),
            'popular_albums' => $this->albumModel->getPopularAlbums(5),
            'popular_tags' => $this->tagModel->getPopularTags(10),
            'recent_photos' => $this->photoModel->getRecentPhotos(5),
            'recent_albums' => $this->albumModel->getRecentAlbums(5)
        ];
        
        $this->json([
            'success' => true,
            'data' => $stats
        ]);
    }
    
    public function upload() {
        $this->requireAuth();
        
        if (!isset($_FILES['photo'])) {
            $this->json([
                'success' => false,
                'message' => 'No photo uploaded'
            ], 400);
            return;
        }
        
        $file = $_FILES['photo'];
        $data = $this->getPostData();
        
        try {
            // Validate and upload file
            $imageInfo = validateImage($file);
            $uploadDir = getUploadPath('albums');
            $uploadResult = uploadFile($file, $uploadDir);
            
            // Create thumbnail
            $config = require __DIR__ . '/../../config/config.php';
            $thumbPath = getThumbnailPath($uploadResult['filename']);
            createThumbnail(
                $uploadResult['destination'], 
                $thumbPath, 
                $config['thumb_width'], 
                $config['thumb_height'], 
                $config['thumb_quality']
            );
            
            $user = getCurrentUser();
            
            $photoData = [
                'title' => $data['title'] ?? null,
                'description' => $data['description'] ?? null,
                'filename' => $uploadResult['filename'],
                'original_filename' => $uploadResult['original_filename'],
                'file_size' => $uploadResult['file_size'],
                'mime_type' => $uploadResult['mime_type'],
                'width' => $imageInfo['width'],
                'height' => $imageInfo['height'],
                'album_id' => $data['album_id'],
                'user_id' => $user['id'],
                'is_public' => isset($data['is_public']) ? 1 : 0
            ];
            
            $photoId = $this->photoModel->create($photoData);
            
            logActivity($user['id'], 'create', 'photo', $photoId);
            
            $photo = $this->photoModel->getWithDetails($photoId);
            
            $this->json([
                'success' => true,
                'message' => 'Photo uploaded successfully',
                'data' => $photo
            ]);
            
        } catch (Exception $e) {
            $this->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }
    
    public function favorite($photoId) {
        $this->requireAuth();
        
        $user = getCurrentUser();
        
        // Check if photo exists and is public
        $photo = $this->photoModel->find($photoId);
        
        if (!$photo || !$photo['is_public']) {
            $this->json([
                'success' => false,
                'message' => 'Photo not found'
            ], 404);
            return;
        }
        
        $favoriteModel = new Favorite();
        $isFavorited = $favoriteModel->toggleFavorite($user['id'], $photoId);
        
        $count = $favoriteModel->getPhotoFavoritesCount($photoId);
        
        $this->json([
            'success' => true,
            'action' => $isFavorited ? 'added' : 'removed',
            'count' => $count,
            'message' => $isFavorited ? 'Photo added to favorites' : 'Photo removed from favorites'
        ]);
    }
    
    public function comment() {
        $this->requireAuth();
        
        $data = $this->getPostData();
        
        // Validate input
        if (empty($data['photo_id']) || empty($data['content'])) {
            $this->json([
                'success' => false,
                'message' => 'Photo ID and content are required'
            ], 400);
            return;
        }
        
        // Check if photo exists and is public
        $photo = $this->photoModel->find($data['photo_id']);
        
        if (!$photo || !$photo['is_public']) {
            $this->json([
                'success' => false,
                'message' => 'Photo not found'
            ], 404);
            return;
        }
        
        $user = getCurrentUser();
        
        $commentData = [
            'photo_id' => $data['photo_id'],
            'user_id' => $user['id'],
            'content' => $data['content'],
            'is_approved' => true
        ];
        
        $commentModel = new Comment();
        $commentId = $commentModel->create($commentData);
        
        logActivity($user['id'], 'comment', 'photo', $data['photo_id']);
        
        // Get the created comment with user info
        $comment = $commentModel->db->fetch(
            "SELECT c.*, u.username, u.first_name, u.last_name 
             FROM comments c 
             JOIN users u ON c.user_id = u.id 
             WHERE c.id = ?",
            [$commentId]
        );
        
        $this->json([
            'success' => true,
            'message' => 'Comment added successfully',
            'data' => $comment
        ]);
    }
}
