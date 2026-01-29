<?php

class PhotoController extends Controller {
    private $photoModel;
    private $albumModel;
    private $favoriteModel;
    private $commentModel;
    
    public function __construct() {
        parent::__construct();
        $this->photoModel = new Photo();
        $this->albumModel = new Album();
        $this->favoriteModel = new Favorite();
        $this->commentModel = new Comment();
    }
    
    public function index() {
        $page = $this->getQueryData('page', 1);
        $limit = 12;
        $offset = ($page - 1) * $limit;
        
        // Get public photos
        $sql = "SELECT p.*, u.username, a.title as album_title,
                       (SELECT COUNT(*) FROM favorites f WHERE f.photo_id = p.id) as favorite_count,
                       (SELECT COUNT(*) FROM comments c WHERE c.photo_id = p.id) as comment_count
                FROM photos p 
                JOIN users u ON p.user_id = u.id 
                JOIN albums a ON p.album_id = a.id 
                WHERE p.is_public = 1 
                ORDER BY p.created_at DESC 
                LIMIT $limit OFFSET $offset";
        
        $photos = $this->photoModel->db->fetchAll($sql);
        
        // Get total count for pagination
        $countSql = "SELECT COUNT(*) as total FROM photos WHERE is_public = 1";
        $total = $this->photoModel->db->fetch($countSql)['total'];
        
        $this->view('photos/index', [
            'photos' => $photos,
            'pagination' => [
                'current' => $page,
                'total' => ceil($total / $limit),
                'limit' => $limit,
                'count' => $total
            ]
        ]);
    }
    
    public function upload() {
        $this->requireAuth();
        
        // Get user's albums for dropdown
        $user = getCurrentUser();
        $albums = $this->albumModel->where('user_id', $user['id']);
        
        $this->view('photos/upload', ['albums' => $albums]);
    }
    
    public function store() {
        $this->requireAuth();
        
        if (!isset($_FILES['photo'])) {
            $this->setFlashMessage('error', 'No photo uploaded');
            $this->redirect('/photos/upload');
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
            
            logActivity($user['id'], 'create', 'photo', $photoId, json_encode($photoData));
            
            $this->setFlashMessage('success', 'Photo uploaded successfully!');
            $this->redirect("/photos/$photoId");
            
        } catch (Exception $e) {
            $this->setFlashMessage('error', $e->getMessage());
            $this->redirect('/photos/upload');
        }
    }
    
    public function show($id) {
        $photo = $this->photoModel->find($id);
        
        if (!$photo || (!$photo['is_public'] && !canEditPhoto($photo))) {
            http_response_code(404);
            echo 'Photo not found';
            return;
        }
        
        // Get photo details with joins
        $sql = "SELECT p.*, u.username, u.first_name, u.last_name, a.title as album_title,
                       (SELECT COUNT(*) FROM favorites f WHERE f.photo_id = p.id) as favorite_count
                FROM photos p 
                JOIN users u ON p.user_id = u.id 
                JOIN albums a ON p.album_id = a.id 
                WHERE p.id = ?";
        
        $photo = $this->photoModel->db->fetch($sql, [$id]);
        
        // Get comments
        $commentsSql = "SELECT c.*, u.username, u.first_name, u.last_name 
                        FROM comments c 
                        JOIN users u ON c.user_id = u.id 
                        WHERE c.photo_id = ? AND c.is_approved = 1 
                        ORDER BY c.created_at ASC";
        
        $comments = $this->photoModel->db->fetchAll($commentsSql, [$id]);
        
        // Check if current user favorited this photo
        $isFavorited = false;
        if (isLoggedIn()) {
            $user = getCurrentUser();
            $favoriteSql = "SELECT id FROM favorites WHERE user_id = ? AND photo_id = ?";
            $favorite = $this->photoModel->db->fetch($favoriteSql, [$user['id'], $id]);
            $isFavorited = !empty($favorite);
        }
        
        // Increment view count
        $this->photoModel->update($id, ['views_count' => $photo['views_count'] + 1]);
        
        $this->view('photos/show', [
            'photo' => $photo,
            'comments' => $comments,
            'isFavorited' => $isFavorited,
            'canEdit' => canEditPhoto($photo)
        ]);
    }
    
    public function edit($id) {
        $photo = $this->photoModel->find($id);
        
        if (!$photo || !canEditPhoto($photo)) {
            http_response_code(404);
            echo 'Photo not found';
            return;
        }
        
        // Get user's albums for dropdown
        $user = getCurrentUser();
        $albums = $this->albumModel->where('user_id', $user['id']);
        
        $this->view('photos/edit', [
            'photo' => $photo,
            'albums' => $albums
        ]);
    }
    
    public function update($id) {
        $photo = $this->photoModel->find($id);
        
        if (!$photo || !canEditPhoto($photo)) {
            http_response_code(404);
            echo 'Photo not found';
            return;
        }
        
        $data = $this->getPostData();
        
        // Validate input
        $errors = $this->validate($data, [
            'title' => ['max' => 100],
            'description' => ['max' => 1000]
        ]);
        
        if (!empty($errors)) {
            $this->setFlashMessage('error', 'Please fix the errors below');
            $this->view('photos/edit', ['errors' => $errors, 'photo' => $photo]);
            return;
        }
        
        $updateData = [
            'title' => $data['title'] ?? null,
            'description' => $data['description'] ?? null,
            'album_id' => $data['album_id'],
            'is_public' => isset($data['is_public']) ? 1 : 0
        ];
        
        $this->photoModel->update($id, $updateData);
        
        logActivity(getCurrentUser()['id'], 'update', 'photo', $id, json_encode($updateData));
        
        $this->setFlashMessage('success', 'Photo updated successfully!');
        $this->redirect("/photos/$id");
    }
    
    public function delete($id) {
        $photo = $this->photoModel->find($id);
        
        if (!$photo || !canEditPhoto($photo)) {
            http_response_code(404);
            echo 'Photo not found';
            return;
        }
        
        // Delete files
        $uploadDir = getUploadPath('albums');
        $photoPath = $uploadDir . '/' . $photo['filename'];
        $thumbPath = getThumbnailPath($photo['filename']);
        
        deleteFile($photoPath);
        deleteFile($thumbPath);
        
        // Delete photo from database
        $this->photoModel->delete($id);
        
        logActivity(getCurrentUser()['id'], 'delete', 'photo', $id);
        
        $this->setFlashMessage('success', 'Photo deleted successfully!');
        $this->redirect('/albums/' . $photo['album_id']);
    }
    
    public function lightbox($id) {
        $photo = $this->photoModel->find($id);
        
        if (!$photo || (!$photo['is_public'] && !canEditPhoto($photo))) {
            http_response_code(404);
            echo 'Photo not found';
            return;
        }
        
        $this->view('photos/lightbox', ['photo' => $photo]);
    }
}
