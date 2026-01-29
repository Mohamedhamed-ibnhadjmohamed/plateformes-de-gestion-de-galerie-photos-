<?php

function createThumbnail($sourcePath, $destinationPath, $width = 300, $height = 200, $quality = 85) {
    // Get image info
    $imageInfo = getimagesize($sourcePath);
    if (!$imageInfo) {
        throw new Exception('Unable to get image information');
    }
    
    list($sourceWidth, $sourceHeight, $imageType) = $imageInfo;
    
    // Create image resource based on type
    switch ($imageType) {
        case IMAGETYPE_JPEG:
            $sourceImage = imagecreatefromjpeg($sourcePath);
            break;
        case IMAGETYPE_PNG:
            $sourceImage = imagecreatefrompng($sourcePath);
            break;
        case IMAGETYPE_GIF:
            $sourceImage = imagecreatefromgif($sourcePath);
            break;
        case IMAGETYPE_WEBP:
            $sourceImage = imagecreatefromwebp($sourcePath);
            break;
        default:
            throw new Exception('Unsupported image type');
    }
    
    // Calculate dimensions maintaining aspect ratio
    $dimensions = calculateDimensions($sourceWidth, $sourceHeight, $width, $height);
    $thumbWidth = $dimensions['width'];
    $thumbHeight = $dimensions['height'];
    
    // Create thumbnail
    $thumbImage = imagecreatetruecolor($thumbWidth, $thumbHeight);
    
    // Preserve transparency for PNG and GIF
    if ($imageType == IMAGETYPE_PNG || $imageType == IMAGETYPE_GIF) {
        imagealphablending($thumbImage, false);
        imagesavealpha($thumbImage, true);
        $transparent = imagecolorallocatealpha($thumbImage, 255, 255, 255, 127);
        imagefilledrectangle($thumbImage, 0, 0, $thumbWidth, $thumbHeight, $transparent);
    }
    
    // Resize image
    imagecopyresampled($thumbImage, $sourceImage, 0, 0, 0, 0, $thumbWidth, $thumbHeight, $sourceWidth, $sourceHeight);
    
    // Create destination directory if it doesn't exist
    $destinationDir = dirname($destinationPath);
    if (!is_dir($destinationDir)) {
        mkdir($destinationDir, 0755, true);
    }
    
    // Save thumbnail
    $result = false;
    switch ($imageType) {
        case IMAGETYPE_JPEG:
            $result = imagejpeg($thumbImage, $destinationPath, $quality);
            break;
        case IMAGETYPE_PNG:
            $result = imagepng($thumbImage, $destinationPath, round($quality / 11));
            break;
        case IMAGETYPE_GIF:
            $result = imagegif($thumbImage, $destinationPath);
            break;
        case IMAGETYPE_WEBP:
            $result = imagewebp($thumbImage, $destinationPath, $quality);
            break;
    }
    
    // Clean up
    imagedestroy($sourceImage);
    imagedestroy($thumbImage);
    
    if (!$result) {
        throw new Exception('Failed to save thumbnail');
    }
    
    return $destinationPath;
}

function calculateDimensions($sourceWidth, $sourceHeight, $maxWidth, $maxHeight) {
    $ratio = $sourceWidth / $sourceHeight;
    
    if ($maxWidth / $maxHeight > $ratio) {
        $width = $maxHeight * $ratio;
        $height = $maxHeight;
    } else {
        $width = $maxWidth;
        $height = $maxWidth / $ratio;
    }
    
    return [
        'width' => round($width),
        'height' => round($height)
    ];
}

function resizeImage($sourcePath, $destinationPath, $width, $height, $quality = 85) {
    $imageInfo = getimagesize($sourcePath);
    if (!$imageInfo) {
        throw new Exception('Unable to get image information');
    }
    
    list($sourceWidth, $sourceHeight, $imageType) = $imageInfo;
    
    // Create image resource
    switch ($imageType) {
        case IMAGETYPE_JPEG:
            $sourceImage = imagecreatefromjpeg($sourcePath);
            break;
        case IMAGETYPE_PNG:
            $sourceImage = imagecreatefrompng($sourcePath);
            break;
        case IMAGETYPE_GIF:
            $sourceImage = imagecreatefromgif($sourcePath);
            break;
        case IMAGETYPE_WEBP:
            $sourceImage = imagecreatefromwebp($sourcePath);
            break;
        default:
            throw new Exception('Unsupported image type');
    }
    
    // Create resized image
    $resizedImage = imagecreatetruecolor($width, $height);
    
    // Preserve transparency
    if ($imageType == IMAGETYPE_PNG || $imageType == IMAGETYPE_GIF) {
        imagealphablending($resizedImage, false);
        imagesavealpha($resizedImage, true);
        $transparent = imagecolorallocatealpha($resizedImage, 255, 255, 255, 127);
        imagefilledrectangle($resizedImage, 0, 0, $width, $height, $transparent);
    }
    
    imagecopyresampled($resizedImage, $sourceImage, 0, 0, 0, 0, $width, $height, $sourceWidth, $sourceHeight);
    
    // Save image
    $result = false;
    switch ($imageType) {
        case IMAGETYPE_JPEG:
            $result = imagejpeg($resizedImage, $destinationPath, $quality);
            break;
        case IMAGETYPE_PNG:
            $result = imagepng($resizedImage, $destinationPath, round($quality / 11));
            break;
        case IMAGETYPE_GIF:
            $result = imagegif($resizedImage, $destinationPath);
            break;
        case IMAGETYPE_WEBP:
            $result = imagewebp($resizedImage, $destinationPath, $quality);
            break;
    }
    
    imagedestroy($sourceImage);
    imagedestroy($resizedImage);
    
    if (!$result) {
        throw new Exception('Failed to save resized image');
    }
    
    return $destinationPath;
}

function cropImage($sourcePath, $destinationPath, $x, $y, $width, $height, $quality = 85) {
    $imageInfo = getimagesize($sourcePath);
    if (!$imageInfo) {
        throw new Exception('Unable to get image information');
    }
    
    list($sourceWidth, $sourceHeight, $imageType) = $imageInfo;
    
    // Create image resource
    switch ($imageType) {
        case IMAGETYPE_JPEG:
            $sourceImage = imagecreatefromjpeg($sourcePath);
            break;
        case IMAGETYPE_PNG:
            $sourceImage = imagecreatefrompng($sourcePath);
            break;
        case IMAGETYPE_GIF:
            $sourceImage = imagecreatefromgif($sourcePath);
            break;
        case IMAGETYPE_WEBP:
            $sourceImage = imagecreatefromwebp($sourcePath);
            break;
        default:
            throw new Exception('Unsupported image type');
    }
    
    // Create cropped image
    $croppedImage = imagecreatetruecolor($width, $height);
    
    // Preserve transparency
    if ($imageType == IMAGETYPE_PNG || $imageType == IMAGETYPE_GIF) {
        imagealphablending($croppedImage, false);
        imagesavealpha($croppedImage, true);
        $transparent = imagecolorallocatealpha($croppedImage, 255, 255, 255, 127);
        imagefilledrectangle($croppedImage, 0, 0, $width, $height, $transparent);
    }
    
    imagecopyresampled($croppedImage, $sourceImage, 0, 0, $x, $y, $width, $height, $width, $height);
    
    // Save image
    $result = false;
    switch ($imageType) {
        case IMAGETYPE_JPEG:
            $result = imagejpeg($croppedImage, $destinationPath, $quality);
            break;
        case IMAGETYPE_PNG:
            $result = imagepng($croppedImage, $destinationPath, round($quality / 11));
            break;
        case IMAGETYPE_GIF:
            $result = imagegif($croppedImage, $destinationPath);
            break;
        case IMAGETYPE_WEBP:
            $result = imagewebp($croppedImage, $destinationPath, $quality);
            break;
    }
    
    imagedestroy($sourceImage);
    imagedestroy($croppedImage);
    
    if (!$result) {
        throw new Exception('Failed to save cropped image');
    }
    
    return $destinationPath;
}

function getImageDimensions($imagePath) {
    $imageInfo = getimagesize($imagePath);
    if (!$imageInfo) {
        return null;
    }
    
    return [
        'width' => $imageInfo[0],
        'height' => $imageInfo[1],
        'type' => $imageInfo[2],
        'mime' => $imageInfo['mime']
    ];
}

function getThumbnailPath($photoFilename) {
    $config = require __DIR__ . '/../../config/config.php';
    $thumbDir = $config['upload_path'] . '/thumbs';
    $extension = pathinfo($photoFilename, PATHINFO_EXTENSION);
    $basename = pathinfo($photoFilename, PATHINFO_FILENAME);
    
    return $thumbDir . '/' . $basename . '_thumb.' . $extension;
}

function validateImage($file) {
    // Check if file is actually an image
    $imageInfo = getimagesize($file['tmp_name']);
    if (!$imageInfo) {
        return ['valid' => false, 'error' => 'Le fichier n\'est pas une image valide'];
    }
    
    // Check file size (max 5MB)
    $maxSize = 5 * 1024 * 1024; // 5MB
    if ($file['size'] > $maxSize) {
        return ['valid' => false, 'error' => 'L\'image est trop volumineuse (max 5MB)'];
    }
    
    // Check allowed types
    $allowedTypes = [IMAGETYPE_JPEG, IMAGETYPE_PNG, IMAGETYPE_GIF, IMAGETYPE_WEBP];
    if (!in_array($imageInfo[2], $allowedTypes)) {
        return ['valid' => false, 'error' => 'Type d\'image non supporté (JPEG, PNG, GIF, WebP uniquement)'];
    }
    
    // Check MIME type
    $allowedMimes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    if (!in_array($file['type'], $allowedMimes)) {
        return ['valid' => false, 'error' => 'Type MIME non autorisé'];
    }
    
    return ['valid' => true, 'info' => $imageInfo];
}

function generateUniqueFilename($originalName) {
    $extension = pathinfo($originalName, PATHINFO_EXTENSION);
    $basename = pathinfo($originalName, PATHINFO_FILENAME);
    $timestamp = time();
    $random = bin2hex(random_bytes(4));
    
    // Sanitize basename
    $basename = preg_replace('/[^a-zA-Z0-9_\-]/', '_', $basename);
    $basename = preg_replace('/_+/', '_', $basename);
    $basename = trim($basename, '_');
    
    return $basename . '_' . $timestamp . '_' . $random . '.' . $extension;
}

function createWatermark($sourcePath, $destinationPath, $watermarkText = '© Photo Gallery', $quality = 85) {
    $imageInfo = getimagesize($sourcePath);
    if (!$imageInfo) {
        throw new Exception('Unable to get image information');
    }
    
    list($sourceWidth, $sourceHeight, $imageType) = $imageInfo;
    
    // Create image resource
    switch ($imageType) {
        case IMAGETYPE_JPEG:
            $sourceImage = imagecreatefromjpeg($sourcePath);
            break;
        case IMAGETYPE_PNG:
            $sourceImage = imagecreatefrompng($sourcePath);
            break;
        case IMAGETYPE_GIF:
            $sourceImage = imagecreatefromgif($sourcePath);
            break;
        case IMAGETYPE_WEBP:
            $sourceImage = imagecreatefromwebp($sourcePath);
            break;
        default:
            throw new Exception('Unsupported image type');
    }
    
    // Create watermark
    $watermarkImage = imagecreatetruecolor($sourceWidth, $sourceHeight);
    
    // Copy original image
    imagecopy($watermarkImage, $sourceImage, 0, 0, 0, 0, $sourceWidth, $sourceHeight);
    
    // Add text watermark
    $fontSize = max(12, min($sourceWidth, $sourceHeight) / 30);
    $textColor = imagecolorallocatealpha($watermarkImage, 255, 255, 255, 80);
    
    // Calculate text position (bottom right)
    $textBoundingBox = imagettfbbox($fontSize, 0, __DIR__ . '/../../assets/fonts/arial.ttf', $watermarkText);
    $textWidth = $textBoundingBox[2] - $textBoundingBox[0];
    $textHeight = $textBoundingBox[3] - $textBoundingBox[5];
    
    $x = $sourceWidth - $textWidth - 20;
    $y = $sourceHeight - $textHeight - 20;
    
    // Add text
    imagettftext($watermarkImage, $fontSize, 0, $x, $y, $textColor, __DIR__ . '/../../assets/fonts/arial.ttf', $watermarkText);
    
    // Save image
    $result = false;
    switch ($imageType) {
        case IMAGETYPE_JPEG:
            $result = imagejpeg($watermarkImage, $destinationPath, $quality);
            break;
        case IMAGETYPE_PNG:
            $result = imagepng($watermarkImage, $destinationPath, round($quality / 11));
            break;
        case IMAGETYPE_GIF:
            $result = imagegif($watermarkImage, $destinationPath);
            break;
        case IMAGETYPE_WEBP:
            $result = imagewebp($watermarkImage, $destinationPath, $quality);
            break;
    }
    
    imagedestroy($sourceImage);
    imagedestroy($watermarkImage);
    
    if (!$result) {
        throw new Exception('Failed to save watermarked image');
    }
    
    return $destinationPath;
}

function rotateImage($sourcePath, $destinationPath, $degrees, $quality = 85) {
    $imageInfo = getimagesize($sourcePath);
    if (!$imageInfo) {
        throw new Exception('Unable to get image information');
    }
    
    list($sourceWidth, $sourceHeight, $imageType) = $imageInfo;
    
    // Create image resource
    switch ($imageType) {
        case IMAGETYPE_JPEG:
            $sourceImage = imagecreatefromjpeg($sourcePath);
            break;
        case IMAGETYPE_PNG:
            $sourceImage = imagecreatefrompng($sourcePath);
            break;
        case IMAGETYPE_GIF:
            $sourceImage = imagecreatefromgif($sourcePath);
            break;
        case IMAGETYPE_WEBP:
            $sourceImage = imagecreatefromwebp($sourcePath);
            break;
        default:
            throw new Exception('Unsupported image type');
    }
    
    // Rotate image
    $rotatedImage = imagerotate($sourceImage, $degrees, 0);
    
    // Preserve transparency for PNG and GIF
    if ($imageType == IMAGETYPE_PNG || $imageType == IMAGETYPE_GIF) {
        imagealphablending($rotatedImage, false);
        imagesavealpha($rotatedImage, true);
    }
    
    // Save image
    $result = false;
    switch ($imageType) {
        case IMAGETYPE_JPEG:
            $result = imagejpeg($rotatedImage, $destinationPath, $quality);
            break;
        case IMAGETYPE_PNG:
            $result = imagepng($rotatedImage, $destinationPath, round($quality / 11));
            break;
        case IMAGETYPE_GIF:
            $result = imagegif($rotatedImage, $destinationPath);
            break;
        case IMAGETYPE_WEBP:
            $result = imagewebp($rotatedImage, $destinationPath, $quality);
            break;
    }
    
    imagedestroy($sourceImage);
    imagedestroy($rotatedImage);
    
    if (!$result) {
        throw new Exception('Failed to save rotated image');
    }
    
    return $destinationPath;
}

function convertFormat($sourcePath, $destinationPath, $targetFormat, $quality = 85) {
    $imageInfo = getimagesize($sourcePath);
    if (!$imageInfo) {
        throw new Exception('Unable to get image information');
    }
    
    list($sourceWidth, $sourceHeight, $imageType) = $imageInfo;
    
    // Create image resource
    switch ($imageType) {
        case IMAGETYPE_JPEG:
            $sourceImage = imagecreatefromjpeg($sourcePath);
            break;
        case IMAGETYPE_PNG:
            $sourceImage = imagecreatefrompng($sourcePath);
            break;
        case IMAGETYPE_GIF:
            $sourceImage = imagecreatefromgif($sourcePath);
            break;
        case IMAGETYPE_WEBP:
            $sourceImage = imagecreatefromwebp($sourcePath);
            break;
        default:
            throw new Exception('Unsupported image type');
    }
    
    // Save in target format
    $result = false;
    switch (strtolower($targetFormat)) {
        case 'jpeg':
        case 'jpg':
            $result = imagejpeg($sourceImage, $destinationPath, $quality);
            break;
        case 'png':
            $result = imagepng($sourceImage, $destinationPath, round($quality / 11));
            break;
        case 'gif':
            $result = imagegif($sourceImage, $destinationPath);
            break;
        case 'webp':
            $result = imagewebp($sourceImage, $destinationPath, $quality);
            break;
        default:
            throw new Exception('Unsupported target format');
    }
    
    imagedestroy($sourceImage);
    
    if (!$result) {
        throw new Exception('Failed to convert image format');
    }
    
    return $destinationPath;
}

function getImageExif($imagePath) {
    if (!function_exists('exif_read_data')) {
        return null;
    }
    
    $exif = @exif_read_data($imagePath);
    if (!$exif) {
        return null;
    }
    
    return [
        'make' => $exif['Make'] ?? null,
        'model' => $exif['Model'] ?? null,
        'datetime' => $exif['DateTime'] ?? null,
        'exposure_time' => $exif['ExposureTime'] ?? null,
        'focal_length' => $exif['FocalLength'] ?? null,
        'iso' => $exif['ISOSpeedRatings'] ?? null,
        'flash' => $exif['Flash'] ?? null,
        'width' => $exif['ExifImageWidth'] ?? null,
        'height' => $exif['ExifImageLength'] ?? null,
        'orientation' => $exif['Orientation'] ?? null
    ];
}

function optimizeImage($imagePath, $quality = 85) {
    $imageInfo = getimagesize($imagePath);
    if (!$imageInfo) {
        throw new Exception('Unable to get image information');
    }
    
    list($width, $height, $imageType) = $imageInfo;
    
    // Create image resource
    switch ($imageType) {
        case IMAGETYPE_JPEG:
            $image = imagecreatefromjpeg($imagePath);
            // Optimize JPEG
            imagejpeg($image, $imagePath, $quality);
            break;
        case IMAGETYPE_PNG:
            $image = imagecreatefrompng($imagePath);
            // Optimize PNG
            imagepng($image, $imagePath, round($quality / 11));
            break;
        case IMAGETYPE_WEBP:
            $image = imagecreatefromwebp($imagePath);
            // Optimize WebP
            imagewebp($image, $imagePath, $quality);
            break;
        default:
            return false; // Skip optimization for unsupported formats
    }
    
    imagedestroy($image);
    return true;
}
