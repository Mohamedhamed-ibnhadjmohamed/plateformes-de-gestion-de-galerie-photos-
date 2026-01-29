<?php 
$pageTitle = 'Tag: ' . htmlspecialchars($tag['name']);
require_once __DIR__ . '/../partials/header.php'; 
?>

<div class="page-header">
    <div class="container">
        <h1 class="page-title">Tag: <?= htmlspecialchars($tag['name']) ?></h1>
        <p class="page-description"><?= $pagination['count'] ?> photo<?= $pagination['count'] > 1 ? 's' : '' } avec ce tag</p>
        
        <div class="tag-actions">
            <a href="/tags" class="btn btn-outline">Retour aux tags</a>
            <?php if (isLoggedIn()): ?>
                <button class="btn btn-primary" id="add-photo-tag-btn">Ajouter ce tag à une photo</button>
            <?php endif; ?>
        </div>
    </div>
</div>

<div class="container">
    <!-- Search and Filter -->
    <div class="search-filter-bar">
        <div class="search-box">
            <input type="text" id="photo-search" placeholder="Rechercher dans ce tag...">
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
                                <button class="photo-action-btn favorite-btn <?= $photo['is_favorited'] ? 'favorited' : '' ?>" 
                                        data-photo-id="<?= $photo['id'] ?>"
                                        title="<?= $photo['is_favorited'] ? 'Retirer des favoris' : 'Ajouter aux favoris' ?>">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="<?= $photo['is_favorited'] ? 'currentColor' : 'none' ?>" stroke="currentColor" stroke-width="2">
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
                            
                            <div class="photo-tags">
                                <?php
                                // Get all tags for this photo
                                $photoTags = getPhotoTags($photo['id']);
                                foreach ($photoTags as $photoTag): ?>
                                    <span class="tag-badge">
                                        <a href="/tags/<?= htmlspecialchars($photoTag['slug']) ?>">
                                            <?= htmlspecialchars($photoTag['name']) ?>
                                        </a>
                                    </span>
                                <?php endforeach; ?>
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
                                <?= $photo['favorite_count'] ?>
                            </span>
                            <span class="stat-item">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z"/>
                                </svg>
                                <?= $photo['comment_count'] ?>
                            </span>
                            <span class="stat-item">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                    <circle cx="12" cy="12" r="3"/>
                                </svg>
                                <?= $photo['views_count'] ?>
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
                <path d="M20.59 13.41l-7.17 7.17a2 2 0 01-2.83 0L2 12V2h10l8.59 8.59a2 2 0 010 2.82z"/>
                <line x1="7" y1="7" x2="7.01" y2="7"/>
            </svg>
            <h3>Aucune photo avec ce tag</h3>
            <p>Aucune photo n'a été taggée avec "<?= htmlspecialchars($tag['name']) ?>" pour le moment.</p>
            <a href="/photos" class="btn btn-primary">Explorer les photos</a>
        </div>
    <?php endif; ?>
</div>

<!-- Add Tag Modal -->
<div id="add-tag-modal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Ajouter le tag "<?= htmlspecialchars($tag['name']) ?>" à une photo</h3>
            <button class="modal-close">&times;</button>
        </div>
        <div class="modal-body">
            <form id="add-tag-form">
                <div class="form-group">
                    <label for="photo-select" class="form-label">Sélectionnez une photo</label>
                    <select id="photo-select" name="photo_id" class="form-control" required>
                        <option value="">Choisissez une photo...</option>
                        <?php
                        // Get user's photos that don't have this tag
                        $userPhotos = getUserPhotosWithoutTag($tag['id'], getCurrentUser()['id']);
                        foreach ($userPhotos as $photo): ?>
                            <option value="<?= $photo['id'] ?>">
                                <?= htmlspecialchars($photo['title'] ?? 'Photo sans titre') ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-actions">
                    <button type="button" class="btn btn-outline modal-cancel">Annuler</button>
                    <button type="submit" class="btn btn-primary">Ajouter le tag</button>
                </div>
            </form>
        </div>
    </div>
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
                        svg.setAttribute("fill", "currentColor");
                        this.classList.add("favorited");
                        this.setAttribute("title", "Retirer des favoris");
                    } else {
                        svg.setAttribute("fill", "none");
                        this.classList.remove("favorited");
                        this.setAttribute("title", "Ajouter aux favoris");
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
                    
                    showNotification(data.message, "success");
                }
            })
            .catch(error => console.error("Error:", error));
        });
    });
    
    // Add tag modal
    const addTagBtn = document.getElementById("add-tag-tag-btn");
    const addTagModal = document.getElementById("add-tag-modal");
    const modalClose = document.querySelector(".modal-close");
    const modalCancel = document.querySelector(".modal-cancel");
    const addTagForm = document.getElementById("add-tag-form");
    
    if (addTagBtn) {
        addTagBtn.addEventListener("click", function() {
            addTagModal.style.display = "block";
        });
    }
    
    if (modalClose) {
        modalClose.addEventListener("click", function() {
            addTagModal.style.display = "none";
        });
    }
    
    if (modalCancel) {
        modalCancel.addEventListener("click", function() {
            addTagModal.style.display = "none";
        });
    }
    
    window.addEventListener("click", function(event) {
        if (event.target === addTagModal) {
            addTagModal.style.display = "none";
        }
    });
    
    if (addTagForm) {
        addTagForm.addEventListener("submit", function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const photoId = formData.get("photo_id");
            
            fetch(`/tags/add-tag-to-photo/${photoId}`, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-Requested-With": "XMLHttpRequest"
                },
                body: JSON.stringify({
                    tag_name: "' . htmlspecialchars($tag['name']) . '"
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification(data.message, "success");
                    addTagModal.style.display = "none";
                    // Reload page to show updated photo
                    setTimeout(() => location.reload(), 1000);
                } else {
                    showNotification(data.error || "Erreur lors de l\\'ajout du tag", "error");
                }
            })
            .catch(error => {
                console.error("Error:", error);
                showNotification("Erreur lors de l\\'ajout du tag", "error");
            });
        });
    }
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
.photo-tags {
    position: absolute;
    bottom: 1rem;
    left: 1rem;
    right: 1rem;
    display: flex;
    flex-wrap: wrap;
    gap: 0.25rem;
}

.tag-badge {
    background: rgba(255, 255, 255, 0.9);
    color: #333;
    padding: 0.25rem 0.5rem;
    border-radius: 12px;
    font-size: 0.75rem;
    font-weight: 500;
}

.tag-badge a {
    color: inherit;
    text-decoration: none;
}

.tag-badge a:hover {
    color: #007bff;
}

.tag-actions {
    display: flex;
    gap: 1rem;
    align-items: center;
}
</style>';
require_once __DIR__ . '/../partials/footer.php'; 

// Helper functions for the view
function getPhotoTags($photoId) {
    // This would typically query the database
    // For now, return empty array
    return [];
}

function getUserPhotosWithoutTag($tagId, $userId) {
    // This would typically query the database
    // For now, return empty array
    return [];
}
?>
