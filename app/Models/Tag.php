<?php

class Tag extends Model {
    protected $table = 'tags';
    
    public function __construct() {
        parent::__construct();
    }
    
    public function findByName($name) {
        $sql = "SELECT * FROM tags WHERE name = ?";
        return $this->db->fetch($sql, [$name]);
    }
    
    public function findBySlug($slug) {
        $sql = "SELECT * FROM tags WHERE slug = ?";
        return $this->db->fetch($sql, [$slug]);
    }
    
    public function findOrCreate($name) {
        $tag = $this->findByName($name);
        
        if ($tag) {
            return $tag;
        }
        
        // Create new tag
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name)));
        
        $data = [
            'name' => $name,
            'slug' => $slug
        ];
        
        $tagId = $this->create($data);
        
        return [
            'id' => $tagId,
            'name' => $name,
            'slug' => $slug
        ];
    }
    
    public function getPopularTags($limit = 20) {
        $sql = "SELECT t.*, COUNT(pt.photo_id) as photo_count
                FROM tags t 
                LEFT JOIN photo_tags pt ON t.id = pt.tag_id 
                LEFT JOIN photos p ON pt.photo_id = p.id AND p.is_public = 1
                GROUP BY t.id 
                HAVING photo_count > 0
                ORDER BY photo_count DESC, t.name ASC
                LIMIT $limit";
        
        return $this->db->fetchAll($sql);
    }
    
    public function getRecentTags($limit = 20) {
        $sql = "SELECT t.*, COUNT(pt.photo_id) as photo_count
                FROM tags t 
                LEFT JOIN photo_tags pt ON t.id = pt.tag_id 
                LEFT JOIN photos p ON pt.photo_id = p.id AND p.is_public = 1
                GROUP BY t.id 
                HAVING photo_count > 0
                ORDER BY t.created_at DESC, t.name ASC
                LIMIT $limit";
        
        return $this->db->fetchAll($sql);
    }
    
    public function getPhotoTags($photoId) {
        $sql = "SELECT t.* FROM tags t 
                JOIN photo_tags pt ON t.id = pt.tag_id 
                WHERE pt.photo_id = ? 
                ORDER BY t.name";
        
        return $this->db->fetchAll($sql, [$photoId]);
    }
    
    public function addTagToPhoto($photoId, $tagId) {
        // Check if already tagged
        $sql = "SELECT id FROM photo_tags WHERE photo_id = ? AND tag_id = ?";
        $existing = $this->db->fetch($sql, [$photoId, $tagId]);
        
        if ($existing) {
            return false; // Already tagged
        }
        
        $sql = "INSERT INTO photo_tags (photo_id, tag_id) VALUES (?, ?)";
        $this->db->query($sql, [$photoId, $tagId]);
        
        return true;
    }
    
    public function removeTagFromPhoto($photoId, $tagId) {
        $sql = "DELETE FROM photo_tags WHERE photo_id = ? AND tag_id = ?";
        $this->db->query($sql, [$photoId, $tagId]);
        
        return true;
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
    
    public function searchTags($query, $limit = 10) {
        $sql = "SELECT t.*, COUNT(pt.photo_id) as photo_count
                FROM tags t 
                LEFT JOIN photo_tags pt ON t.id = pt.tag_id 
                LEFT JOIN photos p ON pt.photo_id = p.id AND p.is_public = 1
                WHERE t.name LIKE ? 
                GROUP BY t.id 
                HAVING photo_count > 0
                ORDER BY photo_count DESC, t.name ASC
                LIMIT $limit";
        
        return $this->db->fetchAll($sql, ["%$query%"]);
    }
    
    public function getTagStats($tagId) {
        $sql = "SELECT 
                    COUNT(pt.photo_id) as photo_count,
                    COUNT(DISTINCT p.user_id) as photographer_count,
                    COUNT(DISTINCT p.album_id) as album_count,
                    MAX(p.created_at) as latest_photo
                FROM photo_tags pt 
                JOIN photos p ON pt.photo_id = p.id 
                WHERE pt.tag_id = ? AND p.is_public = 1";
        
        return $this->db->fetch($sql, [$tagId]);
    }
    
    public function getRelatedTags($tagId, $limit = 10) {
        $sql = "SELECT t.*, COUNT(DISTINCT pt2.photo_id) as shared_photos
                FROM photo_tags pt1 
                JOIN photo_tags pt2 ON pt1.photo_id = pt2.photo_id 
                JOIN tags t ON pt2.tag_id = t.id 
                WHERE pt1.tag_id = ? AND pt2.tag_id != ?
                AND pt1.photo_id IN (
                    SELECT photo_id FROM photo_tags WHERE tag_id = ?
                )
                GROUP BY t.id 
                ORDER BY shared_photos DESC, t.name ASC
                LIMIT $limit";
        
        return $this->db->fetchAll($sql, [$tagId, $tagId, $tagId]);
    }
    
    public function cleanupUnusedTags() {
        // Remove tags that are not associated with any photos
        $sql = "DELETE t FROM tags t 
                LEFT JOIN photo_tags pt ON t.id = pt.tag_id 
                WHERE pt.tag_id IS NULL";
        
        $this->db->query($sql);
    }
    
    public function mergeTags($sourceTagId, $targetTagId) {
        // Move all photo associations from source tag to target tag
        $sql = "UPDATE photo_tags SET tag_id = ? WHERE tag_id = ?";
        $this->db->query($sql, [$targetTagId, $sourceTagId]);
        
        // Delete the source tag
        $this->delete($sourceTagId);
    }
    
    public function renameTag($tagId, $newName) {
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $newName)));
        
        $sql = "UPDATE tags SET name = ?, slug = ? WHERE id = ?";
        $this->db->query($sql, [$newName, $slug, $tagId]);
        
        return true;
    }
}
