<?php

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function getCurrentUser() {
    if (!isLoggedIn()) {
        return null;
    }
    
    static $user = null;
    if ($user === null) {
        $userModel = new User();
        $user = $userModel->find($_SESSION['user_id']);
    }
    
    return $user;
}

function isAdmin() {
    $user = getCurrentUser();
    return $user && $user['role'] === 'admin';
}

function requireAuth() {
    if (!isLoggedIn()) {
        header('Location: /users/login');
        exit;
    }
}

function requireAdmin() {
    requireAuth();
    if (!isAdmin()) {
        http_response_code(403);
        echo 'Access denied';
        exit;
    }
}

function hashPassword($password) {
    return password_hash($password, PASSWORD_DEFAULT);
}

function verifyPassword($password, $hash) {
    return password_verify($password, $hash);
}

function generateToken() {
    return bin2hex(random_bytes(32));
}

function validateToken($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

function login($user) {
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['role'] = $user['role'];
    
    // Update last login
    $userModel = new User();
    $userModel->update($user['id'], ['last_login' => date('Y-m-d H:i:s')]);
    
    // Log activity
    logActivity($user['id'], 'login', 'user', $user['id']);
}

function logout() {
    if (isLoggedIn()) {
        logActivity($_SESSION['user_id'], 'logout', 'user', $_SESSION['user_id']);
    }
    
    session_destroy();
    header('Location: /');
    exit;
}

function logActivity($userId, $action, $resourceType, $resourceId, $details = null) {
    $activityLog = new ActivityLog();
    
    $data = [
        'user_id' => $userId,
        'action' => $action,
        'resource_type' => $resourceType,
        'resource_id' => $resourceId,
        'details' => $details,
        'ip_address' => $_SERVER['REMOTE_ADDR'] ?? null,
        'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? null
    ];
    
    $activityLog->create($data);
}

function getUserAvatar($user, $size = 50) {
    if ($user['avatar']) {
        return "/uploads/avatars/{$user['avatar']}";
    }
    
    // Generate Gravatar
    $hash = md5(strtolower(trim($user['email'])));
    return "https://www.gravatar.com/avatar/$hash?s=$size&d=identicon";
}

function canEditPhoto($photo) {
    $user = getCurrentUser();
    return $user && ($user['id'] == $photo['user_id'] || $user['role'] === 'admin');
}

function canEditAlbum($album) {
    $user = getCurrentUser();
    return $user && ($user['id'] == $album['user_id'] || $user['role'] === 'admin');
}

function canDeleteComment($comment) {
    $user = getCurrentUser();
    return $user && ($user['id'] == $comment['user_id'] || $user['role'] === 'admin');
}

function canEditUser($targetUser) {
    $user = getCurrentUser();
    return $user && ($user['id'] == $targetUser['id'] || $user['role'] === 'admin');
}

function canDeleteUser($targetUser) {
    $user = getCurrentUser();
    return $user && $user['role'] === 'admin' && $user['id'] != $targetUser['id'];
}

function canViewPrivateContent($content) {
    $user = getCurrentUser();
    return $user && ($user['id'] == $content['user_id'] || $user['role'] === 'admin');
}

function generatePasswordResetToken($email) {
    $token = bin2hex(random_bytes(32));
    $expiry = date('Y-m-d H:i:s', strtotime('+1 hour'));
    
    // Store token in database (you'll need to implement this)
    $_SESSION['reset_token'] = $token;
    $_SESSION['reset_email'] = $email;
    $_SESSION['reset_expiry'] = $expiry;
    
    return $token;
}

function validatePasswordResetToken($token, $email) {
    return isset($_SESSION['reset_token']) && 
           hash_equals($_SESSION['reset_token'], $token) &&
           $_SESSION['reset_email'] === $email &&
           strtotime($_SESSION['reset_expiry']) > time();
}

function clearPasswordResetToken() {
    unset($_SESSION['reset_token'], $_SESSION['reset_email'], $_SESSION['reset_expiry']);
}

function isEmailVerified($userId) {
    $userModel = new User();
    return $userModel->isEmailVerified($userId);
}

function sendVerificationEmail($user) {
    // Implementation would depend on your email service
    // This is a placeholder for email verification
    $token = bin2hex(random_bytes(32));
    $expiry = date('Y-m-d H:i:s', strtotime('+24 hours'));
    
    // Store verification token
    $_SESSION['verification_token'] = $token;
    $_SESSION['verification_user_id'] = $user['id'];
    $_SESSION['verification_expiry'] = $expiry;
    
    // In a real implementation, you would send an email here
    return true;
}

function verifyEmail($token) {
    if (!isset($_SESSION['verification_token']) || 
        !hash_equals($_SESSION['verification_token'], $token) ||
        strtotime($_SESSION['verification_expiry']) <= time()) {
        return false;
    }
    
    $userId = $_SESSION['verification_user_id'];
    $userModel = new User();
    $userModel->verifyEmail($userId);
    
    // Clear verification session
    unset($_SESSION['verification_token'], $_SESSION['verification_user_id'], $_SESSION['verification_expiry']);
    
    return true;
}

function checkRateLimit($action, $limit = 5, $window = 300) {
    $key = $action . '_' . ($_SERVER['REMOTE_ADDR'] ?? 'unknown');
    
    if (!isset($_SESSION['rate_limit'][$key])) {
        $_SESSION['rate_limit'][$key] = ['count' => 0, 'start' => time()];
    }
    
    $data = $_SESSION['rate_limit'][$key];
    
    // Reset window if expired
    if (time() - $data['start'] > $window) {
        $_SESSION['rate_limit'][$key] = ['count' => 1, 'start' => time()];
        return true;
    }
    
    // Check limit
    if ($data['count'] >= $limit) {
        return false;
    }
    
    // Increment count
    $_SESSION['rate_limit'][$key]['count']++;
    return true;
}

function isAccountLocked($userId) {
    // Check if account is locked due to failed attempts
    $key = 'login_attempts_' . $userId;
    
    if (!isset($_SESSION[$key])) {
        return false;
    }
    
    $attempts = $_SESSION[$key];
    
    // Lock account after 5 failed attempts for 30 minutes
    if ($attempts['count'] >= 5 && (time() - $attempts['last_attempt']) < 1800) {
        return true;
    }
    
    // Reset if lock period expired
    if (time() - $attempts['last_attempt'] >= 1800) {
        unset($_SESSION[$key]);
        return false;
    }
    
    return false;
}

function recordFailedLogin($userId) {
    $key = 'login_attempts_' . $userId;
    
    if (!isset($_SESSION[$key])) {
        $_SESSION[$key] = ['count' => 1, 'last_attempt' => time()];
    } else {
        $_SESSION[$key]['count']++;
        $_SESSION[$key]['last_attempt'] = time();
    }
}

function clearFailedLogins($userId) {
    unset($_SESSION['login_attempts_' . $userId]);
}

function hasPermission($permission) {
    $user = getCurrentUser();
    
    if (!$user) {
        return false;
    }
    
    // Admin has all permissions
    if ($user['role'] === 'admin') {
        return true;
    }
    
    // Define user permissions
    $userPermissions = [
        'upload_photos' => true,
        'edit_own_photos' => true,
        'delete_own_photos' => true,
        'create_albums' => true,
        'edit_own_albums' => true,
        'delete_own_albums' => true,
        'comment' => true,
        'favorite' => true
    ];
    
    return $userPermissions[$permission] ?? false;
}

function requirePermission($permission) {
    if (!hasPermission($permission)) {
        http_response_code(403);
        echo 'Permission denied';
        exit;
    }
}

function isTwoFactorEnabled($userId) {
    // Implementation would depend on your 2FA system
    return false; // Placeholder
}

function generateTwoFactorSecret() {
    // Implementation would generate a 2FA secret
    return 'JBSWY3DPEHPK3PXP'; // Placeholder
}

function validateTwoFactorCode($secret, $code) {
    // Implementation would validate 2FA code
    return false; // Placeholder
}

function getSessionTimeout() {
    return 30 * 60; // 30 minutes
}

function isSessionExpired() {
    if (!isset($_SESSION['last_activity'])) {
        return false;
    }
    
    return (time() - $_SESSION['last_activity']) > getSessionTimeout();
}

function refreshSession() {
    $_SESSION['last_activity'] = time();
    
    // Regenerate session ID to prevent session fixation
    session_regenerate_id(true);
}

function secureHeaders() {
    header('X-Content-Type-Options: nosniff');
    header('X-Frame-Options: DENY');
    header('X-XSS-Protection: 1; mode=block');
    header('Strict-Transport-Security: max-age=31536000; includeSubDomains');
    header('Content-Security-Policy: default-src \'self\'');
}

function validateSession() {
    // Check if session is expired
    if (isSessionExpired()) {
        logout();
    }
    
    // Refresh session activity
    refreshSession();
}
