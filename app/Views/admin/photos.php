<?php 
$pageTitle = 'Gestion des Photos';
require_once __DIR__ . '/../partials/header.php'; 
?>

<div class="page-header">
    <div class="container">
        <h1 class="page-title">Gestion des Photos</h1>
        <p class="page-description">Gérez toutes les photos de la plateforme</p>
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
            <select id="filter-visibility" class="filter-select">
                <option value="">Toutes les photos</option>
                <option value="1">Publics</option>
                <option value="0">Privées</option>
            </select>
            
            <select id="filter-user" class="filter-select">
                <option value="">Tous les utilisateurs</option>
                <!-- Users would be populated here -->
            </select>
            
            <select id="sort-photos" class="filter-select">
                <option value="recent">Plus récentes</option>
                <option value="popular">Plus populaires</option>
                <option value="oldest">Plus anciennes</option>
                <option value="name">Par nom</option>
                <option value="size">Par taille</option>
            </select>
        </div>
    </div>

    <!-- Photos Grid -->
    <div class="photos-grid">
        <?php foreach ($photos as $photo): ?>
            <div class="photo-card admin-photo-card">
                <div class="photo-container">
                    <img src="/uploads/albums/<?= htmlspecialchars($photo['filename']) ?>" 
                         alt="<?= htmlspecialchars($photo['title'] ?? 'Photo') ?>"
                         loading="lazy"
                         class="photo-image">
                    
                    <div class="photo-overlay">
                        <div class="photo-actions">
                            <a href="/photos/<?= $photo['id'] ?>" class="photo-action-btn view-btn" title="Voir">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M8 3H5a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/>
                                    <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                </svg>
                            </a>
                            
                            <a href="/photos/<?= $photo['id'] ?>/lightbox" 
                               class="photo-action-btn lightbox-btn"
                               title="Voir en plein écran">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M8 3H5a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/>
                                    <path d="M18.5 2.5a2.2 2.2 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                </svg>
                            </a>
                            
                            <a href="/photos/<?= $photo['id'] ?>/edit" 
                               class="photo-action-btn edit-btn"
                               title="Modifier">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/>
                                    <path d="M18.5 2.5a2.2 2.2 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                </svg>
                            </a>
                            
                            <button class="photo-action-btn delete-btn" 
                                    data-photo-id="<?= $photo['id'] ?>"
                                    data-photo-title="<?= htmlspecialchars($photo['title'] ?? 'Photo') ?>"
                                    title="Supprimer">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M3 6h18M8 6V4a2 2 0 012-2h4a2 2 0 012 2v2m3 0v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6h14zM10 11v6M14 11v6M10 11v6"/>
                                </svg>
                            </button>
                        </div>
                        
                        <div class="photo-info">
                            <span class="visibility-badge visibility-<?= $photo['is_public'] ? 'public' : 'private' ?>">
                                <?= $photo['is_public'] ? 'Public' : 'Privé' ?>
                            </span>
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
                    
                    <p class="photo-date">
                        Uploadé le <?= date('d/m/Y à H:i', strtotime($photo['created_at']) ?>
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
                                <path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 00-2-2v-7"/>
                                <path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 00-2-2v-7"/>
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
                    
                    <div class="photo-details">
                        <div class="photo-dimensions">
                            <span><?= $photo['width'] ?> × <?= $photo['height'] ?> px</span>
                            <span><?= formatFileSize($photo['file_size']) ?></span>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    
    <!-- Pagination -->
    <?php if ($pagination['pages'] > 1): ?>
        <div class="pagination">
            <?php if ($pagination['current'] > 1): ?>
                <a href="?page=<?= $pagination['current'] - 1 ?><?= isset($_GET['search']) ? '&search=' . urlencode($_GET['search']) : '' ?><?= isset($_GET['visibility']) ? '&visibility=' . urlencode($_GET['visibility']) : '' ?><?= isset($_GET['user']) ? '&user=' . urlencode($_GET['user']) : '' ?><?= isset($_GET['sort']) ? '&sort=' . urlencode($_GET['sort']) : '' ?>" class="pagination-link prev">Précédent</a>
            <?php endif; ?>
            
            <?php for ($i = 1; $i <= $pagination['pages']; $i++): ?>
                <?php if ($i == $pagination['current']): ?>
                    <span class="pagination-link current"><?= $i ?></span>
                <?php elseif (abs($i - $pagination['current']) <= 2 || $i == 1 || $i == $pagination['pages']): ?>
                    <a href="?page=<?= $i ?><?= isset($_GET['search']) ? '&search=' . urlencode($_GET['search']) : '' ?><?= isset($_GET['visibility']) ? '&visibility=' . urlencode($_GET['visibility']) : '' ?><?= isset($_GET['user']) ? '&user=' . urlencode($_GET['user']) : '' ?><?= isset($_GET['sort']) ? '&sort=' . urlencode($_GET['sort']) : '' ?>" class="pagination-link"><?= $i ?></a>
                <?php endif; ?>
            <?php endfor; ?>
            
            <?php if ($pagination['current'] < $pagination['pages']): ?>
                <a href="?page=<?= $pagination['current'] + 1 ?><?= isset($_GET['search']) ? '&search=' . urlencode($_GET['search']) : '' ?><?= isset($_GET['visibility']) ? '&visibility=' . urlencode($_GET['visibility']) : '' ?><?= isset($_GET['user']) ? '&user=' . urlencode($_GET['user']) : '' ?><?= isset($_GET['sort']) ? '&sort=' . urlencode($_GET['sort']) : '' ?>" class="pagination-link next">Suivant</a>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>

<!-- Delete Confirmation Modal -->
<div id="delete-modal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Confirmer la Suppression</h3>
            <button class="modal-close">&times;</button>
        </div>
        <div class="modal-body">
            <p>Êtes-vous sûr de vouloir supprimer cette photo ?</p>
            <p><strong>Cette action est irréversible.</strong></p>
            <p><strong>Photo : <span id="delete-photo-title"></span></strong></p>
        </div>
        <div class="modal-footer">
            <button class="btn btn-outline modal-cancel">Annuler</button>
            <form id="delete-form" method="POST">
                <button type="submit" class="btn btn-danger">Supprimer</button>
            </form>
        </div>
    </div>
</div>

<!-- Photo Details Modal -->
<div id="photo-details-modal" class="modal">
    <div class="modal-content modal-large">
        <div class="modal-header">
            <h3>Détails de la photo</h3>
            <button class="modal-close">&times;</button>
        </div>
        <div class="modal-body">
            <div id="photo-details-content">
                <!-- Photo details will be loaded here -->
            </div>
        </div>
    </div>
</div>

<?php 
$additionalScripts = '
<script>
document.addEventListener("DOMContentLoaded", function() {
    // Search functionality
    const searchInput = document.getElementById("photo-search");
    const filterVisibility = document.getElementById("filter-visibility");
    const filterUser = document.getElementById("filter-user");
    const sortPhotos = document.getElementById("sort-photos");
    
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
    
    filterVisibility.addEventListener("change", function() {
        const url = new URL(window.location);
        if (this.value) {
            url.searchParams.set("visibility", this.value);
        } else {
            url.searchParams.delete("visibility");
        }
        window.location = url.toString();
    });
    
    filterUser.addEventListener("change", function() {
        const url = new URL(window.location);
        if (this.value) {
            url.searchParams.set("user", this.value);
        } else {
            url.searchParams.delete("user");
        }
        window.location = url.toString();
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
    
    // Delete photo
    const deleteButtons = document.querySelectorAll(".delete-btn");
    const deleteModal = document.getElementById("delete-modal");
    const deleteForm = document.getElementById("delete-form");
    const modalClose = document.querySelector(".modal-close");
    const modalCancel = document.querySelector(".modal-cancel");
    
    deleteButtons.forEach(btn => {
        btn.addEventListener("click", function() {
            const photoId = this.dataset.photoId;
            const photoTitle = this.dataset.photoTitle;
            
            document.getElementById("delete-photo-title").textContent = photoTitle;
            deleteForm.action = `/admin/photos/${photoId}/delete`;
            deleteModal.style.display = "block";
        });
    });
    
    modalClose.addEventListener("click", function() {
        deleteModal.style.display = "none";
    });
    
    modalCancel.addEventListener("click", function() {
        deleteModal.style.display = "none";
    });
    
    window.addEventListener("click", function(event) {
        if (event.target === deleteModal) {
            deleteModal.style.display = "none";
        }
    });
    
    deleteForm.addEventListener("submit", function(e) {
        e.preventDefault();
        this.submit();
    });
    
    // Photo details modal
    const photoDetailsButtons = document.querySelectorAll(".view-btn");
    const photoDetailsModal = document.getElementById("photo-details-modal");
    const photoDetailsContent = document.getElementById("photo-details-content");
    const modalClose = photoDetailsModal.querySelector(".modal-close");
    
    photoDetailsButtons.forEach(btn => {
        btn.addEventListener("click", function(e) {
            e.preventDefault();
            const photoId = this.closest('.photo-card').dataset.photoId;
            
            fetch(`/api/photo/${photoId}`, {
                headers: {
                    "Content-Type": "application/json",
                    "X-Requested-With": "XMLHttpRequest"
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    photoDetailsContent.innerHTML = `
                        <div class="photo-details-grid">
                            <div class="photo-preview">
                                <img src="/uploads/albums/${data.data.filename}" alt="${data.data.title}" style="max-width: 100%; max-height: 400px;">
                            </div>
                            <div class="photo-info">
                                <h4>${data.data.title || 'Sans titre'}</h4>
                                <p>Par ${data.data.username}</p>
                                <p>Dans : ${data.album_title}</p>
                                <p>Uploadé le ${data.created_at}</p>
                                <p>Taille : ${data.width}x${data.height}</p>
                                <p>Poids : ${formatFileSize(data.file_size)}</p>
                            </div>
                        </div>
                    `;
                    
                    photoDetailsModal.style.display = "block";
                }
            });
        });
    });
    
    modalClose.addEventListener("click", function() {
        photoDetailsModal.style.display = "none";
    });
});

function formatFileSize(bytes) {
    if (bytes === 0) return "0 Bytes";
    const k = 1024;
    const sizes = ["Bytes", "KB", "MB", "GB"];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' ' + sizes[i];
}
</script>

<style>
.admin-photo-card {
    position: relative;
}

.photo-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0,0,0,0.5);
    opacity: 0;
    transition: opacity 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
}

.photo-card:hover .photo-overlay {
    opacity: 1;
}

.photo-actions {
    display: flex;
    gap: 0.5rem;
}

.photo-action-btn {
    background: rgba(255,255,255,0.9);
    border: none;
    border-radius: 50%;
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.3s ease;
    color: #333;
}

.photo-action-btn:hover {
    background: rgba(255,255,255,1);
    transform: scale(1.1);
}

.visibility-badge {
    position: absolute;
    top: 1rem;
    right: 1rem;
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

.photo-dimensions {
    display: flex;
    gap: 0.5rem;
    font-size: 0.875rem;
    color: #666;
}

.photo-details-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 2rem;
}

.photo-preview img {
    width: 100%;
    border-radius: 8px;
    object-fit: cover;
}

.photo-info h4 {
    margin-bottom: 0.25rem;
    color: #333;
}

.photo-info p {
    margin-bottom: 0.5rem;
    color: #666;
}

@media (max-width: 768px) {
    .photo-actions {
        flex-direction: column;
        gap: 0.25rem;
    }
}
</style>';
require_once __DIR__ . '/../partials/footer.php'; 
?>
