<?php

return [
    // Database configuration
    'db_host' => 'localhost',
    'db_name' => 'photo_gallery',
    'db_user' => 'root',
    'db_pass' => '',
    
    // Site configuration
    'site_name' => 'Photo Gallery Platform',
    'site_url' => 'http://localhost',
    'admin_email' => 'admin@example.com',
    
    // Upload configuration
    'upload_path' => __DIR__ . '/../public/uploads',
    'max_file_size' => 10 * 1024 * 1024, // 10MB
    'allowed_extensions' => ['jpg', 'jpeg', 'png', 'gif', 'webp'],
    
    // Thumbnail configuration
    'thumb_width' => 300,
    'thumb_height' => 200,
    'thumb_quality' => 85,
    
    // Pagination
    'items_per_page' => 12,
    
    // Security
    'session_lifetime' => 3600, // 1 hour
    'password_min_length' => 8,
    
    // Email configuration (for notifications)
    'mail_host' => 'smtp.example.com',
    'mail_port' => 587,
    'mail_username' => '',
    'mail_password' => '',
    'mail_from' => 'noreply@example.com',
    
    // Debug mode
    'debug' => true,
    'log_errors' => true,
    'log_path' => __DIR__ . '/../logs/activity.log',
];
