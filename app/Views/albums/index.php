<?php require_once __DIR__ . '/../partials/header.php'; ?>

<div class="page-header">
    <div class="container">
        <h1 class="page-title">Albums Photos</h1>
        <p class="page-description">Découvrez les magnifiques galeries partagées par notre communauté</p>
        
        <?php if (isLoggedIn()): ?>
            <div class="page-actions">
                <a href="/albums/create" class="btn btn-primary">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 5v14M5 12h14"/>
                    </svg>
                    Nouvel Album
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>

<div class="container">
    <!-- Search and Filter -->
    <div class="search-filter-bar">
        <div class="search-box">
            <input type="text" id="album-search" placeholder="Rechercher un album...">
            <button type="button" class="search-btn">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="11" cy="11" r="8"/>
                    <path d="m21 21-4.35-4.35"/>
                </svg>
            </button>
        </div>
        
        <div class="filter-options">
            <select id="sort-albums" class="filter-select">
                <option value="recent">Plus récents</option>
                <option value="popular">Plus populaires</option>
                <option value="name">Par nom</option>
            </select>
        </div>
    </div>

    <!-- Albums Grid -->
    <?php if (!empty($albums)): ?>
        <div class="albums-grid">
            <?php foreach ($albums as $album): ?>
                <div class="album-card">
                    <div class="album-cover">
                        <?php if ($album['preview_photo']): ?>
                            <img src="/uploads/albums/<?= htmlspecialchars($album['preview_photo']) ?>" 
                                 alt="<?= htmlspecialchars($album['title']) ?>"
                                 loading="lazy">
                        <?php else: ?>
                            <div class="album-placeholder">
                                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/>
                                    <circle cx="8.5" cy="8.5" r="1.5"/>
                                    <path d="m21 15-5-5L5 21"/>
                                </svg>
                            </div>
                        <?php endif; ?>
                        
                        <div class="album-overlay">
                            <a href="/albums/<?= $album['id'] ?>" class="album-link">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                    <circle cx="12" cy="12" r="3"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                    
                    <div class="album-info">
                        <h3 class="album-title">
                            <a href="/albums/<?= $album['id'] ?>"><?= htmlspecialchars($album['title']) ?></a>
                        </h3>
                        
                        <p class="album-meta">
                            <span class="photo-count"><?= $album['photo_count'] ?> photo<?= $album['photo_count'] > 1 ? 's' : '' ?></span>
                            <span class="album-author">
                                par <a href="/users/<?= $album['user_id'] ?>"><?= htmlspecialchars($album['username']) ?></a>
                            </span>
                        </p>
                        
                        <?php if ($album['description']): ?>
                            <p class="album-description"><?= htmlspecialchars(substr($album['description'], 0, 100)) ?>...</p>
                        <?php endif; ?>
                        
                        <div class="album-stats">
                            <span class="stat-item">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                                </svg>
                                <!-- Favorite count would be added here -->
                            </span>
                            <span class="stat-item">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                    <circle cx="12" cy="12" r="3"/>
                                </svg>
                                <!-- View count would be added here -->
                            </span>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        
        <!-- Pagination -->
        <?php if ($pagination['pages'] > 1): ?>
            <div class="pagination">
                <?php if ($pagination['current'] > 1): ?>
                    <a href="?page=<?= $pagination['current'] - 1 ?>" class="pagination-link prev">Précédent</a>
                <?php endif; ?>
                
                <?php for ($i = 1; $i <= $pagination['pages']; $i++): ?>
                    <?php if ($i == $pagination['current']): ?>
                        <span class="pagination-link current"><?= $i ?></span>
                    <?php elseif (abs($i - $pagination['current']) <= 2 || $i == 1 || $i == $pagination['pages']): ?>
                        <a href="?page=<?= $i ?>" class="pagination-link"><?= $i ?></a>
                    <?php endif; ?>
                <?php endfor; ?>
                
                <?php if ($pagination['current'] < $pagination['pages']): ?>
                    <a href="?page=<?= $pagination['current'] + 1 ?>" class="pagination-link next">Suivant</a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
        
    <?php else: ?>
        <div class="empty-state">
            <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/>
                <circle cx="8.5" cy="8.5" r="1.5"/>
                <path d="m21 15-5-5L5 21"/>
            </svg>
            <h3>Aucun album trouvé</h3>
            <p>Soyez le premier à créer un album !</p>
            <?php if (isLoggedIn()): ?>
                <a href="/albums/create" class="btn btn-primary">Créer un Album</a>
            <?php else: ?>
                <a href="/users/register" class="btn btn-primary">S'inscrire</a>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>
