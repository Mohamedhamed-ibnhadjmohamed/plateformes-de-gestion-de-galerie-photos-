<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
        <a class="navbar-brand" href="<?= BASE_URL ?>">
            <img src="<?= BASE_URL ?>/assets/images/logo.png" alt="<?= htmlspecialchars($config['site_name']) ?>" class="navbar-logo">
            <?= htmlspecialchars($config['site_name']) ?>
        </a>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link <?= $_SERVER['REQUEST_URI'] === '/' || $_SERVER['REQUEST_URI'] === '/home' ? 'active' : '' ?>" href="<?= BASE_URL ?>">
                        <i class="fas fa-home"></i> Accueil
                    </a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/photos') !== false ? 'active' : '' ?>" href="<?= BASE_URL ?>/photos">
                        <i class="fas fa-images"></i> Photos
                    </a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/albums') !== false ? 'active' : '' ?>" href="<?= BASE_URL ?>/albums">
                        <i class="fas fa-folder"></i> Albums
                    </a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/tags') !== false ? 'active' : '' ?>" href="<?= BASE_URL ?>/tags">
                        <i class="fas fa-tags"></i> Tags
                    </a>
                </li>
                
                <?php if (isLoggedIn()): ?>
                    <li class="nav-item">
                        <a class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/favorites') !== false ? 'active' : '' ?>" href="<?= BASE_URL ?>/favorites">
                            <i class="fas fa-heart"></i> Favoris
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
            
            <ul class="navbar-nav">
                <!-- Search -->
                <li class="nav-item">
                    <form class="d-flex me-2" action="<?= BASE_URL ?>/search" method="GET">
                        <input class="form-control form-control-sm" type="search" name="q" placeholder="Rechercher..." value="<?= htmlspecialchars($_GET['q'] ?? '') ?>">
                        <button class="btn btn-outline-light btn-sm" type="submit">
                            <i class="fas fa-search"></i>
                        </button>
                    </form>
                </li>
                
                <?php if (isLoggedIn()): ?>
                    <!-- User Menu -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                            <?= getUserAvatar(getCurrentUser(), 30) ?>
                            <?= htmlspecialchars(getCurrentUser()['username']) ?>
                        </a>
                        
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="<?= BASE_URL ?>/users/profile">
                                <i class="fas fa-user"></i> Mon Profil
                            </a></li>
                            <li><a class="dropdown-item" href="<?= BASE_URL ?>/users/<?= getCurrentUser()['id'] ?>">
                                <i class="fas fa-eye"></i> Ma Galerie
                            </a></li>
                            <li><a class="dropdown-item" href="<?= BASE_URL ?>/photos/upload">
                                <i class="fas fa-upload"></i> Upload Photo
                            </a></li>
                            <li><a class="dropdown-item" href="<?= BASE_URL ?>/albums/create">
                                <i class="fas fa-folder-plus"></i> Créer Album
                            </a></li>
                            
                            <li><hr class="dropdown-divider"></li>
                            
                            <?php if (isAdmin()): ?>
                                <li><a class="dropdown-item" href="<?= BASE_URL ?>/admin/dashboard">
                                    <i class="fas fa-cog"></i> Administration
                                </a></li>
                                <li><hr class="dropdown-divider"></li>
                            <?php endif; ?>
                            
                            <li><a class="dropdown-item" href="<?= BASE_URL ?>/users/logout">
                                <i class="fas fa-sign-out-alt"></i> Déconnexion
                            </a></li>
                        </ul>
                    </li>
                <?php else: ?>
                    <!-- Login/Register -->
                    <li class="nav-item">
                        <a class="nav-link" href="<?= BASE_URL ?>/users/login">
                            <i class="fas fa-sign-in-alt"></i> Connexion
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= BASE_URL ?>/users/register">
                            <i class="fas fa-user-plus"></i> Inscription
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<!-- Breadcrumb -->
<?php if (isset($breadcrumbs) && !empty($breadcrumbs)): ?>
<nav aria-label="breadcrumb" class="breadcrumb-container">
    <div class="container">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="<?= BASE_URL ?>"><i class="fas fa-home"></i></a>
            </li>
            <?php foreach ($breadcrumbs as $index => $breadcrumb): ?>
                <?php if ($index === count($breadcrumbs) - 1): ?>
                    <li class="breadcrumb-item active" aria-current="page">
                        <?= htmlspecialchars($breadcrumb['title']) ?>
                    </li>
                <?php else: ?>
                    <li class="breadcrumb-item">
                        <a href="<?= $breadcrumb['url'] ?>"><?= htmlspecialchars($breadcrumb['title']) ?></a>
                    </li>
                <?php endif; ?>
            <?php endforeach; ?>
        </ol>
    </div>
</nav>
<?php endif; ?>
