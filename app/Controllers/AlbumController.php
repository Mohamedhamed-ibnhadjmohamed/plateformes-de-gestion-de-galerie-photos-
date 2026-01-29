<?php

class AlbumController extends Controller {
    private $albumModel;
    private $photoModel;
    
    public function __construct() {
        parent::__construct();
        $this->albumModel = new Album();
        $this->photoModel = new Photo();
    }
    
    public function index() {
        $page = $this->getQueryData('page', 1);
        $limit = 12;
        $offset = ($page - 1) * $limit;
        
        // Get public albums
        $sql = "SELECT a.*, u.username, u.first_name, u.last_name, 
                       (SELECT COUNT(*) FROM photos p WHERE p.album_id = a.id) as photo_count,
                       (SELECT filename FROM photos p WHERE p.album_id = a.id LIMIT 1) as preview_photo
                FROM albums a 
                JOIN users u ON a.user_id = u.id 
                WHERE a.is_public = 1 
                ORDER BY a.created_at DESC 
                LIMIT $limit OFFSET $offset";
        
        $albums = $this->albumModel->db->fetchAll($sql);
        
        // Get total count for pagination
        $countSql = "SELECT COUNT(*) as total FROM albums WHERE is_public = 1";
        $total = $this->albumModel->db->fetch($countSql)['total'];
        
        $this->view('albums/index', [
            'albums' => $albums,
            'pagination' => [
                'current' => $page,
                'total' => ceil($total / $limit),
                'limit' => $limit,
                'count' => $total
            ]
        ]);
    }
    
    public function create() {
        $this->requireAuth();
        
        $this->view('albums/create');
    }
    
    public function store() {
        $this->requireAuth();
        
        $data = $this->getPostData();
        
        // Validate input
        $errors = $this->validate($data, [
            'title' => ['required' => true, 'max' => 100],
            'description' => ['max' => 1000]
        ]);
        
        if (!empty($errors)) {
            $this->setFlashMessage('error', 'Please fix the errors below');
            $this->view('albums/create', ['errors' => $errors, 'data' => $data]);
            return;
        }
        
        $user = getCurrentUser();
        
        $albumData = [
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'user_id' => $user['id'],
            'is_public' => isset($data['is_public']) ? 1 : 0
        ];
        
        $albumId = $this->albumModel->create($albumData);
        
        logActivity($user['id'], 'create', 'album', $albumId, json_encode($albumData));
        
        $this->setFlashMessage('success', 'Album created successfully!');
        $this->redirect("/albums/$albumId");
    }
    
    public function show($id) {
        $album = $this->albumModel->find($id);
        
        if (!$album || (!$album['is_public'] && !canEditAlbum($album))) {
            http_response_code(404);
            echo 'Album not found';
            return;
        }
        
        // Get album owner info
        $ownerSql = "SELECT username, first_name, last_name FROM users WHERE id = ?";
        $owner = $this->albumModel->db->fetch($ownerSql, [$album['user_id']]);
        
        // Get photos in album
        $page = $this->getQueryData('page', 1);
        $limit = 12;
        $offset = ($page - 1) * $limit;
        
        $photosSql = "SELECT p.*, u.username 
                     FROM photos p 
                     JOIN users u ON p.user_id = u.id 
                     WHERE p.album_id = ? AND p.is_public = 1 
                     ORDER BY p.created_at DESC 
                     LIMIT $limit OFFSET $offset";
        
        $photos = $this->albumModel->db->fetchAll($photosSql, [$id]);
        
        // Get total photo count
        $countSql = "SELECT COUNT(*) as total FROM photos WHERE album_id = ? AND is_public = 1";
        $total = $this->albumModel->db->fetch($countSql, [$id])['total'];
        
        // Increment view count
        $this->albumModel->update($id, ['views_count' => $album['views_count'] + 1]);
        
        $this->view('albums/show', [
            'album' => $album,
            'owner' => $owner,
            'photos' => $photos,
            'pagination' => [
                'current' => $page,
                'total' => ceil($total / $limit),
                'limit' => $limit,
                'count' => $total
            ],
            'canEdit' => canEditAlbum($album)
        ]);
    }
    
    public function edit($id) {
        $album = $this->albumModel->find($id);
        
        if (!$album || !canEditAlbum($album)) {
            http_response_code(404);
            echo 'Album not found';
            return;
        }
        
        $this->view('albums/edit', ['album' => $album]);
    }
    
    public function update($id) {
        $album = $this->albumModel->find($id);
        
        if (!$album || !canEditAlbum($album)) {
            http_response_code(404);
            echo 'Album not found';
            return;
        }
        
        $data = $this->getPostData();
        
        // Validate input
        $errors = $this->validate($data, [
            'title' => ['required' => true, 'max' => 100],
            'description' => ['max' => 1000]
        ]);
        
        if (!empty($errors)) {
            $this->setFlashMessage('error', 'Please fix the errors below');
            $this->view('albums/edit', ['errors' => $errors, 'album' => $album]);
            return;
        }
        
        $updateData = [
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'is_public' => isset($data['is_public']) ? 1 : 0
        ];
        
        $this->albumModel->update($id, $updateData);
        
        logActivity(getCurrentUser()['id'], 'update', 'album', $id, json_encode($updateData));
        
        $this->setFlashMessage('success', 'Album updated successfully!');
        $this->redirect("/albums/$id");
    }
    
    public function delete($id) {
        $album = $this->albumModel->find($id);
        
        if (!$album || !canEditAlbum($album)) {
            http_response_code(404);
            echo 'Album not found';
            return;
        }
        
        // Delete album (photos will be deleted due to foreign key constraint)
        $this->albumModel->delete($id);
        
        logActivity(getCurrentUser()['id'], 'delete', 'album', $id);
        
        $this->setFlashMessage('success', 'Album deleted successfully!');
        $this->redirect('/albums');
    }
}
