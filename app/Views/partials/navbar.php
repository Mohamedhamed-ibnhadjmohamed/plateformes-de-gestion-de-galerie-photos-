<?php
// This is a separate navbar partial that can be included in specific views
// It provides more control over navbar appearance
?>

<nav class="navbar <?= isset($navbarClass) ? $navbarClass : '' ?>">
    <div class="container">
        <div class="navbar-brand">
            <a href="/" class="brand-link">
                <img src="/assets/images/logo.png" alt="Photo Gallery" class="brand-logo">
                <span class="brand-text">Photo Gallery</span>
            </a>
        </div>
        
        <div class="navbar-menu">
            <ul class="nav-links">
                <li><a href="/" class="nav-link <?= $current_page === 'home' ? 'active' : '' ?>">Accueil</a></li>
                <li><a href="/albums" class="nav-link <?= $current_page === 'albums' ? 'active' : '' ?>">Albums</a></li>
                <li><a href="/photos" class="nav-link <?= $current_page === 'photos' ? 'active' : '' ?>">Photos</a></li>
                <li><a href="/tags" class="nav-link <?= $current_page === 'tags' ? 'active' : '' ?>">Tags</a></li>
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
    </div>
</nav>
