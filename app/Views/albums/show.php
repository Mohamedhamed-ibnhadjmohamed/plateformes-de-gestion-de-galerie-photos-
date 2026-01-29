<?php 
$pageTitle = $album['title'];
require_once __DIR__ . '/../partials/header.php'; 
?>

<div class="page-header">
    <div class="container">
        <h1 class="page-title"><?= htmlspecialchars($album['title']) ?></h1>
        <p class="page-description">
            par <a href="/users/<?= $album['user_id'] ?>"><?= htmlspecialchars($album['username']) ?></a>
            • <?= $album['photo_count'] ?> photo<?= $album['photo_count'] > 1 ? 's' : '' ?>
        </p>
    </div>
</div>

<div class="container">
    <div class="album-header">
        <div class="album-info">
            <div class="album-cover">
                <?php if ($album['preview_photo']): ?>
                    <img src="/uploads/albums/<?= htmlspecialchars($album['preview_photo']) ?>" 
                         alt="<?= htmlspecialchars($album['title']) ?>"
                         class="cover-image">
                <?php else: ?>
                    <div class="album-placeholder">
                        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/>
                            <circle cx="8.5" cy="8.5" r="1.5"/>
                            <path d="m21 15-5-5L5 21"/>
                        </svg>
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="album-details">
                <h2><?= htmlspecialchars($album['title']) ?></h2>
                
                <?php if ($album['description']): ?>
                    <p class="album-description"><?= htmlspecialchars($album['description']) ?></p>
                <?php endif; ?>
                
                <div class="album-meta">
                    <div class="meta-item">
                        <span class="meta-label">Créé par:</span>
                        <a href="/users/<?= $album['user_id'] ?>" class="meta-value">
                            <img src="<?= getUserAvatar($album, 24) ?>" alt="Avatar" class="meta-avatar">
                            <?= htmlspecialchars($album['username']) ?>
                        </a>
                    </div>
                    
                    <div class="meta-item">
                        <span class="meta-label">Date de création:</span>
                        <span class="meta-value"><?= date('d/m/Y', strtotime($album['created_at'])) ?></span>
                    </div>
                    
                    <div class="meta-item">
                        <span class="meta-label">Visibilité:</span>
                        <span class="meta-value">
                            <span class="visibility-badge visibility-<?= $album['is_public'] ? 'public' : 'private' ?>">
                                <?= $album['is_public'] ? 'Public' : 'Privé' ?>
                            </span>
                        </span>
                    </div>
                    
                    <div class="meta-item">
                        <span class="meta-label">Photos:</span>
                        <span class="meta-value"><?= $album['photo_count'] ?> photo<?= $album['photo_count'] > 1 ? 's' : '' ?></span>
                    </div>
                </div>
                
                <div class="album-actions">
                    <?php if (canEditAlbum($album)): ?>
                        <a href="/albums/<?= $album['id'] ?>/edit" class="btn btn-outline">Modifier l'album</a>
                        <button class="btn btn-danger delete-album" data-album-id="<?= $album['id'] ?>">Supprimer l'album</button>
                    <?php endif; ?>
                    
                    <button class="btn btn-primary" id="share-album-btn">Partager l'album</button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Search and Filter -->
    <div class="search-filter-bar">
        <div class="search-box">
            <input type="text" id="photo-search" placeholder="Rechercher dans cet album...">
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
                <option value="oldest">Plus anciennes</option>
                <option value="popular">Plus populaires</option>
                <option value="name">Par nom</option>
            </select>
            
            <select id="view-mode" class="filter-select">
                <option value="grid">Grille</option>
                <option value="list">Liste</option>
            </select>
        </div>
    </div>
    
    <!-- Photos Grid/List -->
    <div class="photos-container">
        <?php if (!empty($photos)): ?>
            <div class="photos-grid" id="photos-grid">
                <?php foreach ($photos as $photo): ?>
                    <div class="photo-card">
                        <div class="photo-container">
                            <img src="/uploads/albums/<?= htmlspecialchars($photo['filename']) ?>" 
                                 alt="<?= htmlspecialchars($photo['title'] ?? 'Photo') ?>"
                                 loading="lazy"
                                 class="photo-image">
                            
                            <div class="photo-overlay">
                                <div class="photo-actions">
                                    <a href="/photos/<?= $photo['id'] ?>" class="photo-action-btn view-btn" title="Voir">
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8-11-8z"/>
                                            <circle cx="12" cy="12" r="3"/>
                                        </svg>
                                    </a>
                                    
                                    <a href="/photos/<?= $photo['id'] ?>/lightbox" 
                                       class="photo-action-btn lightbox-btn"
                                       title="Voir en plein écran">
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M8 3H5a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/>
                                            <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                        </svg>
                                    </a>
                                    
                                    <button class="photo-action-btn favorite-btn <?= $photo['is_favorited'] ? 'favorited' : '' ?>" 
                                            data-photo-id="<?= $photo['id'] ?>"
                                            title="<?= $photo['is_favorited'] ? 'Retirer des favoris' : 'Ajouter aux favoris' ?>">
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="<?= $photo['is_favorited'] ? 'currentColor' : 'none' ?>" stroke="currentColor" stroke-width="2">
                                            <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                                        </svg>
                                    </button>
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
                                <span class="photo-date"><?= date('d/m/Y', strtotime($photo['created_at'])) ?></span>
                            </p>
                            
                            <div class="photo-stats">
                                <span class="stat-item">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8-11-8z"/>
                                        <circle cx="12" cy="12" r="3"/>
                                    </svg>
                                    <?= $photo['views_count'] ?>
                                </span>
                                
                                <span class="stat-item">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                                    </svg>
                                    <?= $photo['favorite_count'] ?>
                                </span>
                                
                                <span class="stat-item">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 00-2-2v-7"/>
                                    </svg>
                                    <?= $photo['comment_count'] ?>
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
                        <a href="?page=<?= $pagination['current'] - 1 ?><?= isset($_GET['search']) ? '&search=' . urlencode($_GET['search']) : '' ?><?= isset($_GET['sort']) ? '&sort=' . urlencode($_GET['sort']) : '' ?>" class="pagination-link prev">Précédent</a>
                    <?php endif; ?>
                    
                    <?php for ($i = 1; $i <= $pagination['pages']; $i++): ?>
                        <?php if ($i == $pagination['current']): ?>
                            <span class="pagination-link current"><?= $i ?></span>
                        <?php elseif (abs($i - $pagination['current']) <= 2 || $i == 1 || $i == $pagination['pages']): ?>
                            <a href="?page=<?= $i ?><?= isset($_GET['search']) ? '&search=' . urlencode($_GET['search']) : '' ?><?= isset($_GET['sort']) ? '&sort=' . urlencode($_GET['sort']) : '' ?>" class="pagination-link"><?= $i ?></a>
                        <?php endif; ?>
                    <?php endfor; ?>
                    
                    <?php if ($pagination['current'] < $pagination['pages']): ?>
                        <a href="?page=<?= $pagination['current'] + 1 ?><?= isset($_GET['search']) ? '&search=' . urlencode($_GET['search']) : '' ?><?= isset($_GET['sort']) ? '&sort=' . urlencode($_GET['sort']) : '' ?>" class="pagination-link next">Suivant</a>
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
                <h3>Cet album ne contient aucune photo</h3>
                <p>Soyez le premier à ajouter une photo à cet album !</p>
                <?php if (canEditAlbum($album)): ?>
                    <a href="/photos/upload?album=<?= $album['id'] ?>" class="btn btn-primary">Ajouter une photo</a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Share Modal -->
<div id="share-modal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Partager l'Album</h3>
            <button class="modal-close">&times;</button>
        </div>
        <div class="modal-body">
            <div class="share-options">
                <button class="share-btn" data-platform="facebook">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                    </svg>
                    Facebook
                </button>
                
                <button class="share-btn" data-platform="twitter">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                    </svg>
                    Twitter
                </button>
                
                <button class="share-btn" data-platform="copy">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="9" y="9" width="13" height="13" rx="2" ry="2"/>
                        <path d="M5 15H4a2 2 0 01-2-2V4a2 2 0 012-2h9a2 2 0 012 2v1"/>
                    </svg>
                    Copier le lien
                </button>
            </div>
            
            <div class="share-link">
                <input type="text" id="share-url" value="<?= $_SERVER['REQUEST_URI'] ?>" readonly>
                <button class="btn btn-primary btn-sm" id="copy-link">Copier</button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="delete-modal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Confirmer la Suppression</h3>
            <button class="modal-close">&times;</button>
        </div>
        <div class="modal-body">
            <p>Êtes-vous sûr de vouloir supprimer cet album ?</p>
            <p><strong>Cette action est irréversible et supprimera toutes les photos contenues dans cet album.</strong></p>
            <p><strong>Album : <?= htmlspecialchars($album['title']) ?></strong></p>
        </div>
        <div class="modal-footer">
            <button class="btn btn-outline modal-cancel">Annuler</button>
            <form id="delete-form" method="POST">
                <button type="submit" class="btn btn-danger">Supprimer</button>
            </form>
        </div>
    </div>
</div>

<?php 
$additionalScripts = '
<script>
document.addEventListener("DOMContentLoaded", function() {
    // Search functionality
    const searchInput = document.getElementById("photo-search");
    const sortPhotos = document.getElementById("sort-photos");
    const viewMode = document.getElementById("view-mode");
    
    let searchTimeout;
    
    searchInput.addEventListener("input", function() {
        clearTimeout(searchTimeout);
        const query = this.value.trim();
        
        searchTimeout = setTimeout(() => {
            const url = new URL(window.location);
            if (query) {
                url.searchParams.set("search", query);
            } else {
                url.searchParams.delete("search");
            }
            window.location = url.toString();
        }, 300);
    });
    
    sortPhotos.addEventListener("change", function() {
        const url = new URL(window.location);
        if (this.value) {
            url.searchParams.set("sort", this.value);
        } else {
            url.searchParams.delete("sort");
        }
        window.location = url.toString();
    });
    
    viewMode.addEventListener("change", function() {
        const photosContainer = document.querySelector(".photos-container");
        if (this.value === "list") {
            photosContainer.classList.add("list-view");
        } else {
            photosContainer.classList.remove("list-view");
        }
    });
    
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
                    const statItem = this.closest(".photo-card").querySelector(".stat-item:nth-child(2) span");
                    
                    if (data.action === "added") {
                        svg.setAttribute("fill", "currentColor");
                        this.classList.add("favorited");
                    } else {
                        svg.setAttribute("fill", "none");
                        this.classList.remove("favorited");
                    }
                    
                    if (statItem) {
                        statItem.textContent = data.count;
                    }
                    
                    showNotification(data.message, "success");
                }
            })
            .catch(error => console.error("Error:", error));
        });
    });
    
    // Share modal
    const shareBtn = document.getElementById("share-album-btn");
    const shareModal = document.getElementById("share-modal");
    const shareUrl = document.getElementById("share-url");
    const copyLink = document.getElementById("copy-link");
    
    if (shareBtn) {
        shareBtn.addEventListener("click", function() {
            shareModal.style.display = "block";
        });
    }
    
    // Copy link
    if (copyLink) {
        copyLink.addEventListener("click", function() {
            shareUrl.select();
            document.execCommand("copy");
            showNotification("Lien copié dans le presse-papiers", "success");
        });
    }
    
    // Delete modal
    const deleteBtn = document.querySelector(".delete-album");
    const deleteModal = document.getElementById("delete-modal");
    const deleteForm = document.getElementById("delete-form");
    const modalClose = document.querySelector(".modal-close");
    const modalCancel = document.querySelector(".modal-cancel");
    
    if (deleteBtn) {
        deleteBtn.addEventListener("click", function() {
            deleteModal.style.display = "block";
        });
    }
    
    modalClose.addEventListener("click", function() {
        shareModal.style.display = "none";
        deleteModal.style.display = "none";
    });
    
    modalCancel.addEventListener("click", function() {
        shareModal.style.display = "none";
        deleteModal.style.display = "none";
    });
    
    window.addEventListener("click", function(event) {
        if (event.target === shareModal) {
            shareModal.style.display = "none";
        }
        if (event.target === deleteModal) {
            deleteModal.style.display = "none";
        }
    });
    
    deleteForm.addEventListener("submit", function(e) {
        e.preventDefault();
        this.submit();
    });
    
    // Social share buttons
    const shareButtons = document.querySelectorAll(".share-btn");
    shareButtons.forEach(btn => {
        btn.addEventListener("click", function() {
            const platform = this.dataset.platform;
            const url = window.location.href;
            const title = document.title;
            
            let shareUrl = "";
            switch (platform) {
                case "facebook":
                    shareUrl = `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(url)}`;
                    break;
                case "twitter":
                    shareUrl = `https://twitter.com/intent/tweet?url=${encodeURIComponent(url)}&text=${encodeURIComponent(title)}`;
                    break;
                case "copy":
                    navigator.clipboard.writeText(url);
                    showNotification("Lien copié dans le presse-papiers", "success");
                    return;
            }
            
            if (shareUrl) {
                window.open(shareUrl, "_blank", "width=600,height=400");
            }
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
.album-header {
    margin-bottom: 2rem;
}

.album-info {
    display: grid;
    grid-template-columns: 300px 1fr;
    gap: 2rem;
    background: #fff;
    padding: 2rem;
    border-radius: 12px;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
}

.album-cover {
    position: relative;
}

.cover-image {
    width: 100%;
    height: 200px;
    object-fit: cover;
    border-radius: 8px;
}

.album-placeholder {
    width: 100%;
    height: 200px;
    background: #f8f9fa;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 8px;
    color: #666;
}

.album-details h2 {
    margin-bottom: 1rem;
    color: #333;
}

.album-description {
    margin-bottom: 1.5rem;
    line-height: 1.6;
    color: #666;
}

.album-meta {
    margin-bottom: 1.5rem;
}

.meta-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.5rem 0;
    border-bottom: 1px solid #eee;
}

.meta-item:last-child {
    border-bottom: none;
}

.meta-label {
    font-weight: 500;
    color: #666;
}

.meta-value {
    color: #333;
    text-decoration: none;
}

.meta-avatar {
    width: 24px;
    height: 24px;
    border-radius: 50%;
    object-fit: cover;
    margin-right: 0.5rem;
}

.visibility-badge {
    padding: 0.25rem 0.75rem;
    border-radius: 12px;
    font-size: 0.75rem;
    font-weight: 500;
    color: #fff;
}

.visibility-public {
    background: #28a745;
}

.visibility-private {
    background: #6c757d;
}

.album-actions {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
}

.photos-container.list-view .photos-grid {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.photos-container.list-view .photo-card {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.photos-container.list-view .photo-container {
    width: 200px;
    height: 150px;
}

.photos-container.list-view .photo-info {
    flex: 1;
}

@media (max-width: 768px) {
    .album-info {
        grid-template-columns: 1fr;
        gap: 1rem;
    }
    
    .album-actions {
        flex-direction: column;
    }
    
    .photos-container.list-view .photo-card {
        flex-direction: column;
        align-items: stretch;
    }
    
    .photos-container.list-view .photo-container {
        width: 100%;
        height: auto;
    }
}
</style>';
require_once __DIR__ . '/../partials/footer.php'; 

// Helper function
function canEditAlbum($album) {
    // This would check if current user can edit the album
    return false;
}
?>
