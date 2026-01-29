<?php require_once __DIR__ . '/../partials/header.php'; ?>

<div class="page-header">
    <div class="container">
        <h1 class="page-title">Mes Favoris</h1>
        <p class="page-description">Retrouvez toutes les photos que vous avez ajoutées à vos favoris</p>
    </div>
</div>

<div class="container">
    <?php if (!empty($favorites)): ?>
        <div class="photos-grid">
            <?php foreach ($favorites as $favorite): ?>
                <div class="photo-card">
                    <div class="photo-container">
                        <img src="/uploads/albums/<?= htmlspecialchars($favorite['filename']) ?>" 
                             alt="<?= htmlspecialchars($favorite['title'] ?? 'Photo') ?>"
                             loading="lazy"
                             class="photo-image">
                        
                        <div class="photo-overlay">
                            <div class="photo-actions">
                                <button class="photo-action-btn favorite-btn favorited" 
                                        data-photo-id="<?= $favorite['id'] ?>"
                                        title="Retirer des favoris">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor" stroke="currentColor" stroke-width="2">
                                        <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                                    </svg>
                                </button>
                                
                                <a href="/photos/<?= $favorite['id'] ?>/lightbox" 
                                   class="photo-action-btn lightbox-btn"
                                   title="Voir en plein écran">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M8 3H5a2 2 0 00-2 2v3m18 0V5a2 2 0 00-2-2h-3m0 18h3a2 2 0 002-2v-3M3 16v3a2 2 0 002 2h3"/>
                                    </svg>
                                </a>
                                
                                <a href="/photos/<?= $favorite['id'] ?>" 
                                   class="photo-action-btn view-btn"
                                   title="Voir les détails">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                        <circle cx="12" cy="12" r="3"/>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="photo-info">
                        <h3 class="photo-title">
                            <a href="/photos/<?= $favorite['id'] ?>">
                                <?= htmlspecialchars($favorite['title'] ?? 'Sans titre') ?>
                            </a>
                        </h3>
                        
                        <p class="photo-meta">
                            <span class="photo-author">
                                par <a href="/users/<?= $favorite['user_id'] ?>"><?= htmlspecialchars($favorite['username']) ?></a>
                            </span>
                            <span class="photo-album">
                                dans <a href="/albums/<?= $favorite['album_id'] ?>"><?= htmlspecialchars($favorite['album_title']) ?></a>
                            </span>
                        </p>
                        
                        <p class="photo-date">
                            Ajouté aux favoris le <?= date('d/m/Y à H:i', strtotime($favorite['favorited_at'])) ?>
                        </p>
                        
                        <div class="photo-stats">
                            <span class="stat-item">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                    <circle cx="12" cy="12" r="3"/>
                                </svg>
                                <?= $favorite['views_count'] ?? 0 ?>
                            </span>
                            <span class="stat-item">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z"/>
                                </svg>
                                <?= $favorite['comment_count'] ?? 0 ?>
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
                <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
            </svg>
            <h3>Aucun favori</h3>
            <p>Vous n'avez pas encore ajouté de photos à vos favoris.</p>
            <a href="/photos" class="btn btn-primary">Explorer les photos</a>
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
                    if (data.action === "removed") {
                        // Remove the photo card from favorites
                        const photoCard = this.closest(".photo-card");
                        photoCard.style.animation = "fadeOut 0.3s ease";
                        setTimeout(() => {
                            photoCard.remove();
                            
                            // Check if no more favorites
                            const remainingCards = document.querySelectorAll(".photo-card");
                            if (remainingCards.length === 0) {
                                location.reload(); // Reload to show empty state
                            }
                        }, 300);
                    }
                    
                    showNotification(data.message, data.action === "added" ? "success" : "info");
                }
            })
            .catch(error => console.error("Error:", error));
        });
    });
});

function showNotification(message, type = "info") {
    const notification = document.createElement("div");
    notification.className = `flash-message flash-${type}`;
    notification.innerHTML = `
        ${message}
        <button class="flash-close">&times;</button>
    `;
    
    let container = document.querySelector(".flash-messages");
    if (!container) {
        container = document.createElement("div");
        container.className = "flash-messages";
        container.style.position = "fixed";
        container.style.top = "80px";
        container.style.right = "20px";
        container.style.zIndex = "1002";
        container.style.maxWidth = "400px";
        document.body.appendChild(container);
    }
    
    container.appendChild(notification);
    
    setTimeout(() => {
        notification.style.animation = "slideOut 0.3s ease";
        setTimeout(() => notification.remove(), 300);
    }, 5000);
    
    notification.querySelector(".flash-close").addEventListener("click", () => {
        notification.style.animation = "slideOut 0.3s ease";
        setTimeout(() => notification.remove(), 300);
    });
}
</script>

<style>
@keyframes fadeOut {
    to {
        opacity: 0;
        transform: scale(0.9);
    }
}

.photo-date {
    color: #666;
    font-size: 0.875rem;
    margin-top: 0.5rem;
}
</style>';
require_once __DIR__ . '/../partials/footer.php'; 
?>
