<?php

class CommentController extends Controller {
    private $commentModel;
    
    public function __construct() {
        parent::__construct();
        $this->commentModel = new Comment();
    }
    
    public function store() {
        $this->requireAuth();
        
        $data = $this->getPostData();
        
        // Validate input
        $errors = $this->validate($data, [
            'photo_id' => ['required' => true],
            'content' => ['required' => true, 'max' => 1000]
        ]);
        
        if (!empty($errors)) {
            $this->json(['errors' => $errors], 400);
            return;
        }
        
        // Check if photo exists and is public
        $photoSql = "SELECT id FROM photos WHERE id = ? AND is_public = 1";
        $photo = $this->commentModel->db->fetch($photoSql, [$data['photo_id']]);
        
        if (!$photo) {
            $this->json(['error' => 'Photo not found'], 404);
            return;
        }
        
        $user = getCurrentUser();
        
        // Create comment
        $commentData = [
            'photo_id' => $data['photo_id'],
            'user_id' => $user['id'],
            'content' => $data['content'],
            'is_approved' => true // Auto-approve for now
        ];
        
        $commentId = $this->commentModel->create($commentData);
        
        logActivity($user['id'], 'comment', 'photo', $data['photo_id']);
        
        // Get the created comment with user info
        $commentSql = "SELECT c.*, u.username, u.first_name, u.last_name 
                       FROM comments c 
                       JOIN users u ON c.user_id = u.id 
                       WHERE c.id = ?";
        
        $comment = $this->commentModel->db->fetch($commentSql, [$commentId]);
        
        // Format comment for response
        $comment['created_at'] = date('M j, Y \a\t g:i A', strtotime($comment['created_at']));
        $comment['display_name'] = $comment['first_name'] ? 
            $comment['first_name'] . ' ' . $comment['last_name'] : 
            $comment['username'];
        
        $this->json([
            'success' => true,
            'message' => 'Comment added successfully',
            'comment' => $comment
        ]);
    }
    
    public function delete($id) {
        $this->requireAuth();
        
        // Get comment
        $sql = "SELECT c.*, p.user_id as photo_owner_id 
                FROM comments c 
                JOIN photos p ON c.photo_id = p.id 
                WHERE c.id = ?";
        
        $comment = $this->commentModel->db->fetch($sql, [$id]);
        
        if (!$comment) {
            $this->json(['error' => 'Comment not found'], 404);
            return;
        }
        
        $user = getCurrentUser();
        
        // Check if user can delete comment (own comment or photo owner or admin)
        if ($comment['user_id'] !== $user['id'] && 
            $comment['photo_owner_id'] !== $user['id'] && 
            $user['role'] !== 'admin') {
            $this->json(['error' => 'Access denied'], 403);
            return;
        }
        
        // Delete comment
        $this->commentModel->delete($id);
        
        logActivity($user['id'], 'delete_comment', 'comment', $id);
        
        $this->json([
            'success' => true,
            'message' => 'Comment deleted successfully'
        ]);
    }
    
    public function getPhotoComments($photoId) {
        $page = $this->getQueryData('page', 1);
        $limit = 10;
        $offset = ($page - 1) * $limit;
        
        // Check if photo exists and is public
        $photoSql = "SELECT id FROM photos WHERE id = ? AND is_public = 1";
        $photo = $this->commentModel->db->fetch($photoSql, [$photoId]);
        
        if (!$photo) {
            $this->json(['error' => 'Photo not found'], 404);
            return;
        }
        
        // Get comments
        $sql = "SELECT c.*, u.username, u.first_name, u.last_name 
                FROM comments c 
                JOIN users u ON c.user_id = u.id 
                WHERE c.photo_id = ? AND c.is_approved = 1 
                ORDER BY c.created_at ASC 
                LIMIT $limit OFFSET $offset";
        
        $comments = $this->commentModel->db->fetchAll($sql, [$photoId]);
        
        // Format comments
        foreach ($comments as &$comment) {
            $comment['created_at'] = date('M j, Y \a\t g:i A', strtotime($comment['created_at']));
            $comment['display_name'] = $comment['first_name'] ? 
                $comment['first_name'] . ' ' . $comment['last_name'] : 
                $comment['username'];
            $comment['can_delete'] = isLoggedIn() && (
                getCurrentUser()['id'] == $comment['user_id'] || 
                getCurrentUser()['role'] === 'admin'
            );
        }
        
        // Get total count
        $countSql = "SELECT COUNT(*) as total FROM comments WHERE photo_id = ? AND is_approved = 1";
        $total = $this->commentModel->db->fetch($countSql, [$photoId])['total'];
        
        $this->json([
            'comments' => $comments,
            'pagination' => [
                'current' => $page,
                'total' => ceil($total / $limit),
                'limit' => $limit,
                'count' => $total
            ]
        ]);
    }
    
    public function update($id) {
        $this->requireAuth();
        
        $data = $this->getPostData();
        
        // Validate input
        $errors = $this->validate($data, [
            'content' => ['required' => true, 'max' => 1000]
        ]);
        
        if (!empty($errors)) {
            $this->json(['errors' => $errors], 400);
            return;
        }
        
        // Get comment
        $sql = "SELECT * FROM comments WHERE id = ?";
        $comment = $this->commentModel->db->fetch($sql, [$id]);
        
        if (!$comment) {
            $this->json(['error' => 'Comment not found'], 404);
            return;
        }
        
        $user = getCurrentUser();
        
        // Check if user can edit comment (own comment only)
        if ($comment['user_id'] !== $user['id']) {
            $this->json(['error' => 'Access denied'], 403);
            return;
        }
        
        // Update comment
        $this->commentModel->update($id, [
            'content' => $data['content']
        ]);
        
        logActivity($user['id'], 'update_comment', 'comment', $id);
        
        $this->json([
            'success' => true,
            'message' => 'Comment updated successfully'
        ]);
    }
    
    public function approve($id) {
        $this->requireAdmin();
        
        // Get comment
        $sql = "SELECT * FROM comments WHERE id = ?";
        $comment = $this->commentModel->db->fetch($sql, [$id]);
        
        if (!$comment) {
            $this->json(['error' => 'Comment not found'], 404);
            return;
        }
        
        // Approve comment
        $this->commentModel->update($id, ['is_approved' => true]);
        
        logActivity(getCurrentUser()['id'], 'approve_comment', 'comment', $id);
        
        $this->json([
            'success' => true,
            'message' => 'Comment approved successfully'
        ]);
    }
    
    public function unapprove($id) {
        $this->requireAdmin();
        
        // Get comment
        $sql = "SELECT * FROM comments WHERE id = ?";
        $comment = $this->commentModel->db->fetch($sql, [$id]);
        
        if (!$comment) {
            $this->json(['error' => 'Comment not found'], 404);
            return;
        }
        
        // Unapprove comment
        $this->commentModel->update($id, ['is_approved' => false]);
        
        logActivity(getCurrentUser()['id'], 'unapprove_comment', 'comment', $id);
        
        $this->json([
            'success' => true,
            'message' => 'Comment unapproved successfully'
        ]);
    }
}
