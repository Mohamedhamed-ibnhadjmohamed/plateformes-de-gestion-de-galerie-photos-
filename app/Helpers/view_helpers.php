<?php

/**
 * Format date in a readable format
 */
function formatDate($date) {
    $timestamp = is_string($date) ? strtotime($date) : $date;
    return date('d/m/Y H:i', $timestamp);
}

/**
 * Format relative time (e.g., "2 hours ago")
 */
function formatRelativeTime($date) {
    $timestamp = is_string($date) ? strtotime($date) : $date;
    $diff = time() - $timestamp;
    
    if ($diff < 60) {
        return "à l'instant";
    } elseif ($diff < 3600) {
        $minutes = floor($diff / 60);
        return "il y a $minutes minute" . ($minutes > 1 ? "s" : "");
    } elseif ($diff < 86400) {
        $hours = floor($diff / 3600);
        return "il y a $hours heure" . ($hours > 1 ? "s" : "");
    } elseif ($diff < 2592000) {
        $days = floor($diff / 86400);
        return "il y a $days jour" . ($days > 1 ? "s" : "");
    } else {
        return formatDate($date);
    }
}

/**
 * Get activity description based on activity data
 */
function getActivityDescription($activity) {
    $username = $activity['username'] ?? 'Un utilisateur';
    $action = $activity['action'];
    $resourceType = $activity['resource_type'];
    $resourceTitle = $activity['resource_title'] ?? '';
    
    $actionText = [
        'create' => 'a créé',
        'upload' => 'a uploadé',
        'update' => 'a mis à jour',
        'edit' => 'a modifié',
        'delete' => 'a supprimé',
        'favoris' => 'a ajouté en favori',
        'favorite' => 'a ajouté en favori',
        'comment' => 'a commenté',
        'login' => 's\'est connecté',
        'logout' => 's\'est déconnecté',
        'register' => 's\'est inscrit'
    ];
    
    $resourceText = [
        'photo' => 'la photo',
        'album' => 'l\'album',
        'user' => 'l\'utilisateur',
        'comment' => 'le commentaire'
    ];
    
    $verb = $actionText[$action] ?? 'a effectué une action sur';
    $resource = $resourceText[$resourceType] ?? $resourceType;
    
    $description = "$username $verb $resource";
    
    if ($resourceTitle) {
        $description .= " \"$resourceTitle\"";
    }
    
    return $description;
}

/**
 * Check if user is logged in
 */
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

/**
 * Get current user ID
 */
function getCurrentUserId() {
    return $_SESSION['user_id'] ?? null;
}

/**
 * Get current user data
 */
function getCurrentUser() {
    return $_SESSION['user'] ?? null;
}

/**
 * Check if current user is admin
 */
function isAdmin() {
    return isset($_SESSION['user']['role']) && $_SESSION['user']['role'] === 'admin';
}

/**
 * Get user avatar HTML
 */
function getUserAvatar($user, $size = 'small') {
    $avatarClass = $size === 'large' ? 'user-avatar-large' : 'user-avatar';
    
    if (!empty($user['avatar'])) {
        return '<img src="' . BASE_URL . '/assets/images/avatars/' . htmlspecialchars($user['avatar']) . '" alt="' . htmlspecialchars($user['username']) . '" class="' . $avatarClass . '">';
    } else {
        $initials = strtoupper(substr($user['username'], 0, 2));
        return '<div class="' . $avatarClass . ' avatar-placeholder">' . $initials . '</div>';
    }
}

/**
 * Truncate text
 */
function truncateText($text, $length = 100) {
    if (strlen($text) <= $length) {
        return $text;
    }
    return substr($text, 0, $length) . '...';
}

/**
 * Generate pagination HTML
 */
function generatePagination($currentPage, $totalPages, $baseUrl) {
    if ($totalPages <= 1) {
        return '';
    }
    
    $html = '<div class="pagination">';
    
    // Previous button
    if ($currentPage > 1) {
        $html .= '<a href="' . $baseUrl . '?page=' . ($currentPage - 1) . '" class="pagination-link">Précédent</a>';
    }
    
    // Page numbers
    $start = max(1, $currentPage - 2);
    $end = min($totalPages, $currentPage + 2);
    
    if ($start > 1) {
        $html .= '<a href="' . $baseUrl . '?page=1" class="pagination-link">1</a>';
        if ($start > 2) {
            $html .= '<span class="pagination-ellipsis">...</span>';
        }
    }
    
    for ($i = $start; $i <= $end; $i++) {
        if ($i == $currentPage) {
            $html .= '<span class="pagination-link active">' . $i . '</span>';
        } else {
            $html .= '<a href="' . $baseUrl . '?page=' . $i . '" class="pagination-link">' . $i . '</a>';
        }
    }
    
    if ($end < $totalPages) {
        if ($end < $totalPages - 1) {
            $html .= '<span class="pagination-ellipsis">...</span>';
        }
        $html .= '<a href="' . $baseUrl . '?page=' . $totalPages . '" class="pagination-link">' . $totalPages . '</a>';
    }
    
    // Next button
    if ($currentPage < $totalPages) {
        $html .= '<a href="' . $baseUrl . '?page=' . ($currentPage + 1) . '" class="pagination-link">Suivant</a>';
    }
    
    $html .= '</div>';
    return $html;
}

/**
 * Format file size
 */
function formatFileSize($bytes) {
    $units = ['B', 'KB', 'MB', 'GB'];
    $bytes = max($bytes, 0);
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
    $pow = min($pow, count($units) - 1);
    
    $bytes /= (1 << (10 * $pow));
    
    return round($bytes, 2) . ' ' . $units[$pow];
}

/**
 * Get MIME type icon
 */
function getMimeTypeIcon($mimeType) {
    $iconMap = [
        'image/jpeg' => '📷',
        'image/png' => '🖼️',
        'image/gif' => '🎬',
        'image/webp' => '🌐',
        'application/pdf' => '📄',
        'text/plain' => '📝',
        'application/zip' => '📦',
        'video/mp4' => '🎥',
        'audio/mpeg' => '🎵'
    ];
    
    return $iconMap[$mimeType] ?? '📄';
}

/**
 * Sanitize filename
 */
function sanitizeFilename($filename) {
    // Remove any character that is not alphanumeric, space, or underscore
    $filename = preg_replace('/[^a-zA-Z0-9_\-\.]/', '_', $filename);
    
    // Remove multiple underscores
    $filename = preg_replace('/_+/', '_', $filename);
    
    // Trim underscores from start and end
    $filename = trim($filename, '_');
    
    return $filename;
}

/**
 * Generate CSRF token
 */
function generateCSRFToken() {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Validate CSRF token
 */
function validateCSRFToken($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Get base URL
 */
function getBaseUrl() {
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https" : "http";
    $host = $_SERVER['HTTP_HOST'];
    $path = dirname($_SERVER['PHP_SELF']);
    
    return "$protocol://$host$path";
}

/**
 * Redirect to URL
 */
function redirect($url) {
    header("Location: $url");
    exit;
}

/**
 * Flash message helper
 */
function setFlashMessage($type, $message) {
    $_SESSION['flash'][$type] = $message;
}

function getFlashMessage($type) {
    $message = $_SESSION['flash'][$type] ?? '';
    unset($_SESSION['flash'][$type]);
    return $message;
}

function hasFlashMessage($type) {
    return isset($_SESSION['flash'][$type]);
}

/**
 * Validation helpers
 */
function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

function validateUsername($username) {
    return preg_match('/^[a-zA-Z0-9_]{3,20}$/', $username);
}

function validatePassword($password) {
    return strlen($password) >= 8;
}

/**
 * Security helpers
 */
function escape($string) {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}

function cleanInput($input) {
    return trim(strip_tags($input));
}

/**
 * File upload helpers
 */
function isImageFile($file) {
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    return in_array($file['type'], $allowedTypes);
}

function getMaxFileSize() {
    return 5 * 1024 * 1024; // 5MB
}

/**
 * Generate unique filename
 */
function generateUniqueFilename($originalName) {
    $extension = pathinfo($originalName, PATHINFO_EXTENSION);
    $basename = pathinfo($originalName, PATHINFO_FILENAME);
    $timestamp = time();
    $random = bin2hex(random_bytes(4));
    
    return sanitizeFilename($basename) . '_' . $timestamp . '_' . $random . '.' . $extension;
}
