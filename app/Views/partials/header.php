<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($pageTitle) ? $pageTitle . ' - ' : '' ?>Photo Gallery</title>
    <meta name="description" content="<?= isset($pageDescription) ? $pageDescription : 'Plateforme de gestion de galerie photos' ?>">
    
    <!-- CSS -->
    <link rel="stylesheet" href="/assets/css/style.css">
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="/favicon.ico">
    
    <!-- CSRF Token -->
    <meta name="csrf-token" content="<?= $_SESSION['csrf_token'] ?? '' ?>">
</head>
<body>
    <header class="header">
        <div class="container">
            <nav class="navbar">
                <div class="navbar-brand">
                    <a href="/" class="brand-link">
                        <img src="/assets/images/logo.png" alt="Photo Gallery" class="brand-logo">
                        <span class="brand-text">Photo Gallery</span>
                    </a>
                </div>
                
                <div class="navbar-menu">
                    <ul class="nav-links">
                        <li><a href="/" class="nav-link">Accueil</a></li>
                        <li><a href="/albums" class="nav-link">Albums</a></li>
                        <li><a href="/photos" class="nav-link">Photos</a></li>
                        <li><a href="/tags" class="nav-link">Tags</a></li>
                    </ul>
                </div>
                
                <div class="navbar-actions">
                    <?php if (isLoggedIn()): ?>
                        <div class="user-menu">
                            <div class="user-avatar">
                                <img src="<?= getUserAvatar(getCurrentUser(), 32) ?>" alt="Avatar">
                            </div>
                            <div class="user-dropdown">
                                <span class="user-name"><?= getCurrentUser()['username'] ?></span>
                                <div class="dropdown-menu">
                                    <a href="/users/profile" class="dropdown-item">Mon Profil</a>
                                    <a href="/favorites" class="dropdown-item">Mes Favoris</a>
                                    <?php if (isAdmin()): ?>
                                        <a href="/admin/dashboard" class="dropdown-item">Administration</a>
                                    <?php endif; ?>
                                    <a href="/albums/create" class="dropdown-item">Créer un Album</a>
                                    <a href="/photos/upload" class="dropdown-item">Uploader une Photo</a>
                                    <div class="dropdown-divider"></div>
                                    <a href="/users/logout" class="dropdown-item">Déconnexion</a>
                                </div>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="auth-links">
                            <a href="/users/login" class="btn btn-outline">Connexion</a>
                            <a href="/users/register" class="btn btn-primary">Inscription</a>
                        </div>
                    <?php endif; ?>
                </div>
                
                <button class="mobile-menu-toggle">
                    <span></span>
                    <span></span>
                    <span></span>
                </button>
            </nav>
        </div>
    </header>

    <!-- Flash Messages -->
    <?php if (isset($_SESSION['flash'])): ?>
        <div class="flash-messages">
            <?php foreach ($_SESSION['flash'] as $type => $message): ?>
                <div class="flash-message flash-<?= $type ?>">
                    <?= htmlspecialchars($message) ?>
                    <button class="flash-close">&times;</button>
                </div>
            <?php endforeach; ?>
            <?php unset($_SESSION['flash']); ?>
        </div>
    <?php endif; ?>

    <main class="main-content">
