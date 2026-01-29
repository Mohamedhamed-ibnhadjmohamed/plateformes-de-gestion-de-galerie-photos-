<?php

function uploadFile($file, $destinationDir, $allowedExtensions = null) {
    $config = require __DIR__ . '/../../config/config.php';
    
    // Check if file was uploaded
    if (!isset($file['tmp_name']) || !is_uploaded_file($file['tmp_name'])) {
        throw new Exception('No file uploaded or upload error');
    }
    
    // Check file size
    if ($file['size'] > $config['max_file_size']) {
        throw new Exception('File size exceeds maximum limit');
    }
    
    // Get file extension
    $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    
    // Check allowed extensions
    $allowed = $allowedExtensions ?? $config['allowed_extensions'];
    if (!in_array($extension, $allowed)) {
        throw new Exception('File type not allowed');
    }
    
    // Generate unique filename
    $filename = generateUniqueFilename($file['name'], $extension);
    
    // Create destination directory if it doesn't exist
    if (!is_dir($destinationDir)) {
        mkdir($destinationDir, 0755, true);
    }
    
    // Move uploaded file
    $destination = $destinationDir . '/' . $filename;
    if (!move_uploaded_file($file['tmp_name'], $destination)) {
        throw new Exception('Failed to move uploaded file');
    }
    
    return [
        'filename' => $filename,
        'original_filename' => $file['name'],
        'file_size' => $file['size'],
        'mime_type' => $file['type'],
        'destination' => $destination
    ];
}

function generateUniqueFilename($originalName, $extension) {
    $basename = pathinfo($originalName, PATHINFO_FILENAME);
    $basename = preg_replace('/[^a-zA-Z0-9-_]/', '_', $basename);
    $basename = substr($basename, 0, 50); // Limit length
    
    $timestamp = date('YmdHis');
    $random = bin2hex(random_bytes(4));
    
    return "{$basename}_{$timestamp}_{$random}.{$extension}";
}

function deleteFile($filepath) {
    if (file_exists($filepath)) {
        return unlink($filepath);
    }
    return true; // File doesn't exist, consider it deleted
}

function getFileSize($bytes) {
    $units = ['B', 'KB', 'MB', 'GB'];
    $bytes = max($bytes, 0);
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
    $pow = min($pow, count($units) - 1);
    
    $bytes /= pow(1024, $pow);
    
    return round($bytes, 2) . ' ' . $units[$pow];
}

function validateImage($file) {
    $config = require __DIR__ . '/../../config/config.php';
    
    // Check if it's actually an image
    $imageInfo = getimagesize($file['tmp_name']);
    if (!$imageInfo) {
        throw new Exception('File is not a valid image');
    }
    
    // Check MIME type
    $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    if (!in_array($file['type'], $allowedMimeTypes)) {
        throw new Exception('Image type not allowed');
    }
    
    // Get image dimensions
    list($width, $height) = $imageInfo;
    
    return [
        'width' => $width,
        'height' => $height,
        'mime_type' => $imageInfo['mime']
    ];
}

function createUploadDirectories() {
    $config = require __DIR__ . '/../../config/config.php';
    
    $directories = [
        $config['upload_path'],
        $config['upload_path'] . '/albums',
        $config['upload_path'] . '/thumbs',
        $config['upload_path'] . '/avatars'
    ];
    
    foreach ($directories as $dir) {
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
    }
}

function isImageFile($filename) {
    $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    return in_array($extension, $imageExtensions);
}

function getUploadPath($type = 'albums') {
    $config = require __DIR__ . '/../../config/config.php';
    return $config['upload_path'] . '/' . $type;
}
