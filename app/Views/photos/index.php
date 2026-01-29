<?php require_once __DIR__ . '/../partials/header.php'; ?>

<div class="page-header">
    <div class="container">
        <h1 class="page-title">Photos</h1>
        <p class="page-description">Explorez les magnifiques photos partagées par notre communauté</p>
        
        <?php if (isLoggedIn()): ?>
            <div class="page-actions">
                <a href="/photos/upload" class="btn btn-primary">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4M17 8l-5-5-5 5M12 3v12"/>
                    </svg>
                    Uploader une Photo
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>

<div class="container">
    <!-- Search and Filter -->
    <div class="search-filter-bar">
        <div class="search-box">
            <input type="text" id="photo-search" placeholder="Rechercher une photo...">
            <button type="button" class="search-btn">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="11" cy="11" r="8"/>
                    <path d="m21 21-4.35-4.35"/>
                </svg>
            </button>
        </div>
        
        <div class="filter-options">
            <select id="sort-photos" class="filter-select">
                <option value="recent">Plus récentes</option>
                <option value="popular">Plus populaires</option>
                <option value="favorites">Plus de favoris</option>
                <option value="comments">Plus commentées</option>
            </select>
            
            <select id="filter-album" class="filter-select">
                <option value="">Tous les albums</option>
                <!-- Albums would be populated here -->
            </select>
            
            <select id="filter-tags" class="filter-select">
                <option value="">Tous les tags</option>
                <!-- Tags would be populated here -->
            </select>
        </div>
    </div>

    <!-- Photos Grid -->
    <?php if (!empty($photos)): ?>
        <div class="photos-grid">
            <?php foreach ($photos as $photo): ?>
                <div class="photo-card">
                    <div class="photo-container">
                        <img src="/uploads/albums/<?= htmlspecialchars($photo['filename']) ?>" 
                             alt="<?= htmlspecialchars($photo['title'] ?? 'Photo') ?>"
                             loading="lazy"
                             class="photo-image">
                        
                        <div class="photo-overlay">
                            <div class="photo-actions">
                                <button class="photo-action-btn favorite-btn" 
                                        data-photo-id="<?= $photo['id'] ?>"
                                        title="Ajouter aux favoris">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                                    </svg>
                                </button>
                                
                                <a href="/photos/<?= $photo['id'] ?>/lightbox" 
                                   class="photo-action-btn lightbox-btn"
                                   title="Voir en plein écran">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M8 3H5a2 2 0 00-2 2v3m18 0V5a2 2 0 00-2-2h-3m0 18h3a2 2 0 002-2v-3M3 16v3a2 2 0 002 2h3"/>
                                    </svg>
                                </a>
                                
                                <?php if (canEditPhoto($photo)): ?>
                                    <a href="/photos/<?= $photo['id'] ?>/edit" 
                                       class="photo-action-btn edit-btn"
                                       title="Modifier">
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/>
                                            <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                        </svg>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="photo-info">
                        <h3 class="photo-title">
                            <a href="/photos/<?= $photo['id'] ?>">
                                <?= htmlspecialchars($photo['title'] ?? 'Sans titre') ?>
                            </a>
                        </h3>
                        
                        <p class="photo-meta">
                            <span class="photo-author">
                                par <a href="/users/<?= $photo['user_id'] ?>"><?= htmlspecialchars($photo['username']) ?></a>
                            </span>
                            <span class="photo-album">
                                dans <a href="/albums/<?= $photo['album_id'] ?>"><?= htmlspecialchars($photo['album_title']) ?></a>
                            </span>
                        </p>
                        
                        <div class="photo-stats">
                            <span class="stat-item">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                                </svg>
                                <?= $photo['favorite_count'] ?? 0 ?>
                            </span>
                            <span class="stat-item">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z"/>
                                </svg>
                                <?= $photo['comment_count'] ?? 0 ?>
                            </span>
                            <span class="stat-item">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                    <circle cx="12" cy="12" r="3"/>
                                </svg>
                                <?= $photo['views_count'] ?? 0 ?>
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
            <h3>Aucune photo trouvée</h3>
            <p>Soyez le premier à partager une photo !</p>
            <?php if (isLoggedIn()): ?>
                <a href="/photos/upload" class="btn btn-primary">Uploader une Photo</a>
            <?php else: ?>
                <a href="/users/register" class="btn btn-primary">S'inscrire</a>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>

<?php 
$additionalScripts = '
<script>
document.addEventListener("DOMContentLoaded", function() {
    // Favorite functionality
    const favoriteBtns = document.querySelectorAll(".favorite-btn");
    
    favoriteBtns.forEach(btn => {
        btn.addEventListener("click", function(e) {
            e.preventDefault();
            const photoId = this.dataset.photoId;
            
            fetch(`/favorites/toggle/${photoId}`, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-Requested-With": "XMLHttpRequest"
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const svg = this.querySelector("svg");
                    if (data.action === "added") {
                        svg.style.fill = "currentColor";
                        this.classList.add("favorited");
                    } else {
                        svg.style.fill = "none";
                        this.classList.remove("favorited");
                    }
                    
                    // Update favorite count
                    const statItem = this.closest(".photo-card").querySelector(".stat-item:first-child");
                    if (statItem) {
                        statItem.innerHTML = `
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                            </svg>
                            ${data.count}
                        `;
                    }
                }
            })
            .catch(error => console.error("Error:", error));
        });
    });
});
</script>';
require_once __DIR__ . '/../partials/footer.php'; 
?>
