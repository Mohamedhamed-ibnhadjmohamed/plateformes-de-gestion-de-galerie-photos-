<?php

class AdminController extends Controller {
    private $userModel;
    private $albumModel;
    private $photoModel;
    private $commentModel;
    private $activityLogModel;
    
    public function __construct() {
        parent::__construct();
        $this->requireAdmin();
        
        $this->userModel = new User();
        $this->albumModel = new Album();
        $this->photoModel = new Photo();
        $this->commentModel = new Comment();
        $this->activityLogModel = new ActivityLog();
    }
    
    public function dashboard() {
        // Get dashboard statistics
        $stats = [
            'users' => $this->userModel->getUsersCount(),
            'albums' => $this->albumModel->getPublicAlbumsCount(),
            'photos' => $this->photoModel->getPublicPhotosCount(),
            'comments' => $this->commentModel->getTotalCommentsCount(),
            'pending_comments' => $this->commentModel->getPendingCommentsCount()
        ];
        
        // Get recent activity
        $recentActivity = $this->activityLogModel->getRecentActivity(10);
        
        // Get popular content
        $popularPhotos = $this->photoModel->getPopularPhotos(5);
        $popularAlbums = $this->albumModel->getPopularAlbums(5);
        
        // Get user statistics
        $userStats = $this->activityLogModel->getActivityStats(30);
        
        $this->view('admin/dashboard', [
            'stats' => $stats,
            'recentActivity' => $recentActivity,
            'popularPhotos' => $popularPhotos,
            'popularAlbums' => $popularAlbums,
            'userStats' => $userStats
        ]);
    }
    
    public function users() {
        $page = $this->getQueryData('page', 1);
        $limit = 20;
        $offset = ($page - 1) * $limit;
        
        $users = $this->userModel->getAllUsers($limit, $offset);
        $total = $this->userModel->getUsersCount();
        
        $pagination = [
            'current' => $page,
            'total' => ceil($total / $limit),
            'limit' => $limit,
            'count' => $total
        ];
        
        $this->view('admin/users', [
            'users' => $users,
            'pagination' => $pagination
        ]);
    }
    
    public function albums() {
        $page = $this->getQueryData('page', 1);
        $limit = 12;
        $offset = ($page - 1) * $limit;
        
        $albums = $this->albumModel->getAllAlbums($limit, $offset);
        $total = $this->albumModel->getAllAlbumsCount();
        
        $pagination = [
            'current' => $page,
            'total' => ceil($total / $limit),
            'limit' => $limit,
            'count' => $total
        ];
        
        $this->view('admin/albums', [
            'albums' => $albums,
            'pagination' => $pagination
        ]);
    }
    
    public function photos() {
        $page = $this->getQueryData('page', 1);
        $limit = 20;
        $offset = ($page - 1) * $limit;
        
        $photos = $this->photoModel->getAllPhotos($limit, $offset);
        $total = $this->photoModel->getAllPhotosCount();
        
        $pagination = [
            'current' => $page,
            'total' => ceil($total / $limit),
            'limit' => $limit,
            'count' => $total
        ];
        
        $this->view('admin/photos', [
            'photos' => $photos,
            'pagination' => $pagination
        ]);
    }
    
    public function comments() {
        $page = $this->getQueryData('page', 1);
        $limit = 20;
        $offset = ($page - 1) * $limit;
        
        $comments = $this->commentModel->getAllComments($limit, $offset);
        $total = $this->commentModel->getAllCommentsCount();
        
        $pagination = [
            'current' => $page,
            'total' => ceil($total / $limit),
            'limit' => $limit,
            'count' => $total
        ];
        
        $this->view('admin/comments', [
            'comments' => $comments,
            'pagination' => $pagination
        ]);
    }
    
    public function editUser($id) {
        $user = $this->userModel->find($id);
        
        if (!$user) {
            http_response_code(404);
            echo 'User not found';
            return;
        }
        
        if ($this->getPostData()) {
            $data = $this->getPostData();
            
            $updateData = [
                'username' => $data['username'],
                'email' => $data['email'],
                'first_name' => $data['first_name'] ?? null,
                'last_name' => $data['last_name'] ?? null,
                'role' => $data['role'],
                'is_active' => isset($data['is_active']) ? 1 : 0
            ];
            
            $this->userModel->update($id, $updateData);
            
            logActivity(getCurrentUser()['id'], 'update', 'user', $id);
            
            $this->setFlashMessage('success', 'User updated successfully');
            $this->redirect('/admin/users');
        }
        
        $this->view('admin/edit_user', ['user' => $user]);
    }
    
    public function deleteUser($id) {
        $user = $this->userModel->find($id);
        
        if (!$user) {
            http_response_code(404);
            echo 'User not found';
            return;
        }
        
        if ($user['id'] == getCurrentUser()['id']) {
            $this->setFlashMessage('error', 'You cannot delete your own account');
            $this->redirect('/admin/users');
            return;
        }
        
        $this->userModel->delete($id);
        
        logActivity(getCurrentUser()['id'], 'delete', 'user', $id);
        
        $this->setFlashMessage('success', 'User deleted successfully');
        $this->redirect('/admin/users');
    }
    
    public function toggleUserStatus($id) {
        $user = $this->userModel->find($id);
        
        if (!$user) {
            $this->json(['error' => 'User not found'], 404);
            return;
        }
        
        $newStatus = $user['is_active'] ? 0 : 1;
        $this->userModel->update($id, ['is_active' => $newStatus]);
        
        logActivity(getCurrentUser()['id'], $newStatus ? 'activate' : 'deactivate', 'user', $id);
        
        $this->json([
            'success' => true,
            'message' => $newStatus ? 'User activated successfully' : 'User deactivated successfully',
            'status' => $newStatus
        ]);
    }
    
    public function approveComment($id) {
        $this->commentModel->approve($id);
        
        logActivity(getCurrentUser()['id'], 'approve_comment', 'comment', $id);
        
        $this->json([
            'success' => true,
            'message' => 'Comment approved successfully'
        ]);
    }
    
    public function deleteComment($id) {
        $comment = $this->commentModel->find($id);
        
        if (!$comment) {
            $this->json(['error' => 'Comment not found'], 404);
            return;
        }
        
        $this->commentModel->delete($id);
        
        logActivity(getCurrentUser()['id'], 'delete_comment', 'comment', $id);
        
        $this->json([
            'success' => true,
            'message' => 'Comment deleted successfully'
        ]);
    }
    
    public function settings() {
        if ($this->getPostData()) {
            $data = $this->getPostData();
            
            // Update settings in config file
            $configFile = __DIR__ . '/../../config/config.php';
            $config = require $configFile;
            
            // Update allowed settings
            $config['site_name'] = $data['site_name'];
            $config['admin_email'] = $data['admin_email'];
            $config['max_file_size'] = $data['max_file_size'];
            $config['items_per_page'] = $data['items_per_page'];
            $config['debug'] = isset($data['debug']) ? true : false;
            
            // Write back to config file
            file_put_contents($configFile, '<?php return ' . var_export($config, true) . ';');
            
            $this->setFlashMessage('success', 'Settings updated successfully');
            $this->redirect('/admin/settings');
        }
        
        $config = require __DIR__ . '/../../config/config.php';
        
        $this->view('admin/settings', ['config' => $config]);
    }
    
    public function logs() {
        $page = $this->getQueryData('page', 1);
        $limit = 50;
        $offset = ($page - 1) * $limit;
        
        $logs = $this->activityLogModel->getAllActivity($limit, $offset);
        $total = $this->activityLogModel->getAllActivityCount();
        
        $pagination = [
            'current' => $page,
            'total' => ceil($total / $limit),
            'limit' => $limit,
            'count' => $total
        ];
        
        $this->view('admin/logs', [
            'logs' => $logs,
            'pagination' => $pagination
        ]);
    }
    
    public function cleanup() {
        $this->activityLogModel->cleanupOldLogs(90); // Clean logs older than 90 days
        
        $this->json([
            'success' => true,
            'message' => 'Old logs cleaned up successfully'
        ]);
    }
}
