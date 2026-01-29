<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($pageTitle) ? htmlspecialchars($pageTitle) . ' - ' : '' ?><?= htmlspecialchars($config['site_name']) ?></title>
    <meta name="description" content="<?= isset($pageDescription) ? htmlspecialchars($pageDescription) : 'Plateforme de gestion de galerie photos' ?>">
    <meta name="keywords" content="<?= isset($pageKeywords) ? htmlspecialchars($pageKeywords) : 'galerie, photos, images, album' ?>">
    
    <!-- Open Graph Meta Tags -->
    <meta property="og:title" content="<?= isset($pageTitle) ? htmlspecialchars($pageTitle) : $config['site_name'] ?>">
    <meta property="og:description" content="<?= isset($pageDescription) ? htmlspecialchars($pageDescription) : 'Plateforme de gestion de galerie photos' ?>">
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?= $config['site_url'] . $_SERVER['REQUEST_URI'] ?>">
    <meta property="og:image" content="<?= $config['site_url'] ?>/assets/images/logo.png">
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?= BASE_URL ?>/favicon.ico">
    
    <!-- CSS -->
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/admin.css">
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- CSRF Token -->
    <meta name="csrf-token" content="<?= generateCSRFToken() ?>">
    
    <?php if (isset($customCSS)): ?>
        <style><?= $customCSS ?></style>
    <?php endif; ?>
</head>
<body>
    <!-- Flash Messages -->
    <?php if (hasFlashMessage('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= getFlashMessage('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    
    <?php if (hasFlashMessage('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= getFlashMessage('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    
    <?php if (hasFlashMessage('warning')): ?>
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <?= getFlashMessage('warning') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    
    <?php if (hasFlashMessage('info')): ?>
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            <?= getFlashMessage('info') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- Navigation -->
    <?php require_once __DIR__ . '/navbar.php'; ?>

    <!-- Main Content -->
    <main class="main-content">
