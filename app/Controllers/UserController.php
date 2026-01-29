<?php

class UserController extends Controller {
    private $userModel;
    
    public function __construct() {
        parent::__construct();
        $this->userModel = new User();
    }
    
    public function login() {
        $this->requireGuest();
        
        $this->view('users/login');
    }
    
    public function authenticate() {
        $this->requireGuest();
        
        $data = $this->getPostData();
        
        // Validate input
        $errors = $this->validate($data, [
            'email' => ['required' => true, 'email' => true],
            'password' => ['required' => true]
        ]);
        
        if (!empty($errors)) {
            $this->setFlashMessage('error', 'Please fix the errors below');
            $this->view('users/login', ['errors' => $errors, 'data' => $data]);
            return;
        }
        
        // Find user by email
        $user = $this->userModel->where('email', $data['email'])[0] ?? null;
        
        if (!$user || !verifyPassword($data['password'], $user['password_hash'])) {
            $this->setFlashMessage('error', 'Invalid email or password');
            $this->view('users/login', ['data' => $data]);
            return;
        }
        
        if (!$user['is_active']) {
            $this->setFlashMessage('error', 'Account is deactivated');
            $this->view('users/login', ['data' => $data]);
            return;
        }
        
        // Login user
        login($user);
        
        $this->setFlashMessage('success', 'Welcome back!');
        $this->redirect('/');
    }
    
    public function logout() {
        logout();
    }
    
    public function register() {
        $this->requireGuest();
        
        $this->view('users/register');
    }
    
    public function store() {
        $this->requireGuest();
        
        $data = $this->getPostData();
        
        // Validate input
        $errors = $this->validate($data, [
            'username' => ['required' => true, 'min' => 3, 'max' => 50],
            'email' => ['required' => true, 'email' => true],
            'password' => ['required' => true, 'min' => 8],
            'password_confirm' => ['required' => true],
            'first_name' => ['max' => 50],
            'last_name' => ['max' => 50]
        ]);
        
        // Check if passwords match
        if ($data['password'] !== $data['password_confirm']) {
            $errors['password_confirm'][] = 'Passwords do not match';
        }
        
        // Check if email already exists
        $existingUser = $this->userModel->where('email', $data['email']);
        if (!empty($existingUser)) {
            $errors['email'][] = 'Email already exists';
        }
        
        // Check if username already exists
        $existingUsername = $this->userModel->where('username', $data['username']);
        if (!empty($existingUsername)) {
            $errors['username'][] = 'Username already exists';
        }
        
        if (!empty($errors)) {
            $this->setFlashMessage('error', 'Please fix the errors below');
            $this->view('users/register', ['errors' => $errors, 'data' => $data]);
            return;
        }
        
        // Create user
        $userData = [
            'username' => $data['username'],
            'email' => $data['email'],
            'password_hash' => hashPassword($data['password']),
            'first_name' => $data['first_name'] ?? null,
            'last_name' => $data['last_name'] ?? null,
            'role' => 'user',
            'email_verified' => false
        ];
        
        $userId = $this->userModel->create($userData);
        
        logActivity($userId, 'register', 'user', $userId);
        
        $this->setFlashMessage('success', 'Registration successful! Please login.');
        $this->redirect('/users/login');
    }
    
    public function profile() {
        $this->requireAuth();
        
        $user = getCurrentUser();
        
        // Get user statistics
        $statsSql = "SELECT 
                        (SELECT COUNT(*) FROM albums WHERE user_id = ?) as album_count,
                        (SELECT COUNT(*) FROM photos WHERE user_id = ?) as photo_count,
                        (SELECT COUNT(*) FROM favorites WHERE user_id = ?) as favorite_count";
        
        $stats = $this->userModel->db->fetch($statsSql, [$user['id'], $user['id'], $user['id']]);
        
        // Get recent activity
        $activitySql = "SELECT * FROM activity_logs 
                        WHERE user_id = ? 
                        ORDER BY created_at DESC 
                        LIMIT 10";
        
        $activities = $this->userModel->db->fetchAll($activitySql, [$user['id']]);
        
        $this->view('users/profile', [
            'user' => $user,
            'stats' => $stats,
            'activities' => $activities
        ]);
    }
    
    public function show($id) {
        $user = $this->userModel->find($id);
        
        if (!$user) {
            http_response_code(404);
            echo 'User not found';
            return;
        }
        
        // Get user's public albums
        $albumsSql = "SELECT a.*, (SELECT COUNT(*) FROM photos p WHERE p.album_id = a.id) as photo_count
                      FROM albums a 
                      WHERE a.user_id = ? AND a.is_public = 1 
                      ORDER BY a.created_at DESC";
        
        $albums = $this->userModel->db->fetchAll($albumsSql, [$id]);
        
        // Get user's public photos
        $photosSql = "SELECT p.*, a.title as album_title
                      FROM photos p 
                      JOIN albums a ON p.album_id = a.id 
                      WHERE p.user_id = ? AND p.is_public = 1 
                      ORDER BY p.created_at DESC 
                      LIMIT 12";
        
        $photos = $this->userModel->db->fetchAll($photosSql, [$id]);
        
        // Get user statistics
        $statsSql = "SELECT 
                        (SELECT COUNT(*) FROM albums WHERE user_id = ? AND is_public = 1) as album_count,
                        (SELECT COUNT(*) FROM photos WHERE user_id = ? AND is_public = 1) as photo_count,
                        (SELECT COUNT(*) FROM favorites WHERE user_id = ?) as favorite_count";
        
        $stats = $this->userModel->db->fetch($statsSql, [$user['id'], $user['id'], $user['id']]);
        
        $this->view('users/show', [
            'user' => $user,
            'albums' => $albums,
            'photos' => $photos,
            'stats' => $stats,
            'isOwnProfile' => isLoggedIn() && getCurrentUser()['id'] == $user['id']
        ]);
    }
    
    public function updateProfile() {
        $this->requireAuth();
        
        $user = getCurrentUser();
        $data = $this->getPostData();
        
        // Validate input
        $errors = $this->validate($data, [
            'first_name' => ['max' => 50],
            'last_name' => ['max' => 50],
            'bio' => ['max' => 1000]
        ]);
        
        if (!empty($errors)) {
            $this->setFlashMessage('error', 'Please fix the errors below');
            $this->view('users/profile', ['errors' => $errors, 'user' => $user]);
            return;
        }
        
        $updateData = [
            'first_name' => $data['first_name'] ?? null,
            'last_name' => $data['last_name'] ?? null,
            'bio' => $data['bio'] ?? null
        ];
        
        $this->userModel->update($user['id'], $updateData);
        
        logActivity($user['id'], 'update', 'user', $user['id'], json_encode($updateData));
        
        $this->setFlashMessage('success', 'Profile updated successfully!');
        $this->redirect('/users/profile');
    }
    
    public function changePassword() {
        $this->requireAuth();
        
        $user = getCurrentUser();
        $data = $this->getPostData();
        
        // Validate input
        $errors = $this->validate($data, [
            'current_password' => ['required' => true],
            'new_password' => ['required' => true, 'min' => 8],
            'confirm_password' => ['required' => true]
        ]);
        
        // Check if new passwords match
        if ($data['new_password'] !== $data['confirm_password']) {
            $errors['confirm_password'][] = 'Passwords do not match';
        }
        
        // Verify current password
        if (!verifyPassword($data['current_password'], $user['password_hash'])) {
            $errors['current_password'][] = 'Current password is incorrect';
        }
        
        if (!empty($errors)) {
            $this->setFlashMessage('error', 'Please fix the errors below');
            $this->view('users/profile', ['errors' => $errors, 'user' => $user]);
            return;
        }
        
        // Update password
        $this->userModel->update($user['id'], [
            'password_hash' => hashPassword($data['new_password'])
        ]);
        
        logActivity($user['id'], 'change_password', 'user', $user['id']);
        
        $this->setFlashMessage('success', 'Password changed successfully!');
        $this->redirect('/users/profile');
    }
}
