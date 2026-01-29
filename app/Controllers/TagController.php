<?php

class TagController extends Controller {
    private $tagModel;
    
    public function __construct() {
        parent::__construct();
        $this->tagModel = new Tag();
    }
    
    public function index() {
        // Get all tags with photo counts
        $sql = "SELECT t.*, COUNT(pt.photo_id) as photo_count
                FROM tags t 
                LEFT JOIN photo_tags pt ON t.id = pt.tag_id 
                LEFT JOIN photos p ON pt.photo_id = p.id AND p.is_public = 1
                GROUP BY t.id 
                HAVING photo_count > 0
                ORDER BY t.name";
        
        $tags = $this->tagModel->db->fetchAll($sql);
        
        $this->view('tags/index', ['tags' => $tags]);
    }
    
    public function show($name) {
        $page = $this->getQueryData('page', 1);
        $limit = 12;
        $offset = ($page - 1) * $limit;
        
        // Find tag by slug or name
        $tagSql = "SELECT * FROM tags WHERE slug = ? OR name = ? LIMIT 1";
        $tag = $this->tagModel->db->fetch($tagSql, [$name, $name]);
        
        if (!$tag) {
            http_response_code(404);
            echo 'Tag not found';
            return;
        }
        
        // Get photos with this tag
        $sql = "SELECT p.*, u.username, a.title as album_title,
                       (SELECT COUNT(*) FROM favorites f WHERE f.photo_id = p.id) as favorite_count,
                       (SELECT COUNT(*) FROM comments c WHERE c.photo_id = p.id) as comment_count
                FROM photo_tags pt 
                JOIN photos p ON pt.photo_id = p.id 
                JOIN users u ON p.user_id = u.id 
                JOIN albums a ON p.album_id = a.id 
                WHERE pt.tag_id = ? AND p.is_public = 1 
                ORDER BY p.created_at DESC 
                LIMIT $limit OFFSET $offset";
        
        $photos = $this->tagModel->db->fetchAll($sql, [$tag['id']]);
        
        // Get total count for pagination
        $countSql = "SELECT COUNT(*) as total 
                    FROM photo_tags pt 
                    JOIN photos p ON pt.photo_id = p.id 
                    WHERE pt.tag_id = ? AND p.is_public = 1";
        
        $total = $this->tagModel->db->fetch($countSql, [$tag['id']])['total'];
        
        $this->view('tags/show', [
            'tag' => $tag,
            'photos' => $photos,
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
        
        $data = $this->getPostData();
        
        // Validate input
        $errors = $this->validate($data, [
            'name' => ['required' => true, 'max' => 50]
        ]);
        
        if (!empty($errors)) {
            $this->json(['errors' => $errors], 400);
            return;
        }
        
        // Generate slug
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $data['name'])));
        
        // Check if tag already exists
        $existingSql = "SELECT id FROM tags WHERE name = ? OR slug = ?";
        $existing = $this->tagModel->db->fetch($existingSql, [$data['name'], $slug]);
        
        if ($existing) {
            $this->json(['error' => 'Tag already exists'], 400);
            return;
        }
        
        // Create tag
        $tagData = [
            'name' => $data['name'],
            'slug' => $slug
        ];
        
        $tagId = $this->tagModel->create($tagData);
        
        logActivity(getCurrentUser()['id'], 'create', 'tag', $tagId);
        
        $this->json([
            'success' => true,
            'tag' => [
                'id' => $tagId,
                'name' => $data['name'],
                'slug' => $slug
            ]
        ]);
    }
    
    public function addTagToPhoto($photoId) {
        $this->requireAuth();
        
        $data = $this->getPostData();
        
        // Validate input
        $errors = $this->validate($data, [
            'tag_name' => ['required' => true, 'max' => 50]
        ]);
        
        if (!empty($errors)) {
            $this->json(['errors' => $errors], 400);
            return;
        }
        
        // Check if user can edit this photo
        $photoSql = "SELECT * FROM photos WHERE id = ?";
        $photo = $this->tagModel->db->fetch($photoSql, [$photoId]);
        
        if (!$photo || !canEditPhoto($photo)) {
            $this->json(['error' => 'Photo not found or access denied'], 404);
            return;
        }
        
        // Find or create tag
        $tagSql = "SELECT * FROM tags WHERE name = ? LIMIT 1";
        $tag = $this->tagModel->db->fetch($tagSql, [$data['tag_name']]);
        
        if (!$tag) {
            // Create new tag
            $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $data['tag_name'])));
            $tagData = [
                'name' => $data['tag_name'],
                'slug' => $slug
            ];
            $tagId = $this->tagModel->create($tagData);
            $tag = ['id' => $tagId, 'name' => $data['tag_name'], 'slug' => $slug];
        }
        
        // Check if photo already has this tag
        $existingSql = "SELECT * FROM photo_tags WHERE photo_id = ? AND tag_id = ?";
        $existing = $this->tagModel->db->fetch($existingSql, [$photoId, $tag['id']]);
        
        if ($existing) {
            $this->json(['error' => 'Photo already has this tag'], 400);
            return;
        }
        
        // Add tag to photo
        $photoTagData = [
            'photo_id' => $photoId,
            'tag_id' => $tag['id']
        ];
        
        $this->tagModel->db->query(
            "INSERT INTO photo_tags (photo_id, tag_id) VALUES (?, ?)",
            [$photoId, $tag['id']]
        );
        
        logActivity(getCurrentUser()['id'], 'tag', 'photo', $photoId, $tag['name']);
        
        $this->json([
            'success' => true,
            'message' => 'Tag added to photo',
            'tag' => $tag
        ]);
    }
    
    public function removeTagFromPhoto($photoId, $tagId) {
        $this->requireAuth();
        
        // Check if user can edit this photo
        $photoSql = "SELECT * FROM photos WHERE id = ?";
        $photo = $this->tagModel->db->fetch($photoSql, [$photoId]);
        
        if (!$photo || !canEditPhoto($photo)) {
            $this->json(['error' => 'Photo not found or access denied'], 404);
            return;
        }
        
        // Remove tag from photo
        $deleteSql = "DELETE FROM photo_tags WHERE photo_id = ? AND tag_id = ?";
        $this->tagModel->db->query($deleteSql, [$photoId, $tagId]);
        
        logActivity(getCurrentUser()['id'], 'untag', 'photo', $photoId, $tagId);
        
        $this->json([
            'success' => true,
            'message' => 'Tag removed from photo'
        ]);
    }
    
    public function getPhotoTags($photoId) {
        $sql = "SELECT t.* FROM tags t 
                JOIN photo_tags pt ON t.id = pt.tag_id 
                WHERE pt.photo_id = ? 
                ORDER BY t.name";
        
        $tags = $this->tagModel->db->fetchAll($sql, [$photoId]);
        
        $this->json(['tags' => $tags]);
    }
    
    public function search() {
        $query = $this->getQueryData('q', '');
        
        if (empty($query)) {
            $this->json(['tags' => []]);
            return;
        }
        
        $sql = "SELECT t.*, COUNT(pt.photo_id) as photo_count
                FROM tags t 
                LEFT JOIN photo_tags pt ON t.id = pt.tag_id 
                LEFT JOIN photos p ON pt.photo_id = p.id AND p.is_public = 1
                WHERE t.name LIKE ? 
                GROUP BY t.id 
                HAVING photo_count > 0
                ORDER BY photo_count DESC, t.name
                LIMIT 10";
        
        $tags = $this->tagModel->db->fetchAll($sql, ["%$query%"]);
        
        $this->json(['tags' => $tags]);
    }
}
