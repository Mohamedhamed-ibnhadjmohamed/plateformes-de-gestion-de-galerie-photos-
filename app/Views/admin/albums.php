<?php 
$pageTitle = 'Gestion des Albums';
require_once __DIR__ . '/../partials/header.php'; 
?>

<div class="page-header">
    <div class="container">
        <h1 class="page-title">Gestion des Albums</h1>
        <p class="page-description">Gérez tous les albums de la plateforme</p>
    </div>
</div>

<div class="container">
    <!-- Search and Filter -->
    <div class="search-filter-bar">
        <div class="search-box">
            <input type="text" id="album-search" placeholder="Recherchercher un album...">
            <button type="button" class="search-btn">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="11" cy="11" r="8"/>
                    <path d="m21 21-4.35-4.35"/>
                </svg>
            </button>
        </div>
        
        <div class="filter-options">
            <select id="filter-visibility" class="filter-select">
                <option value="">Tous les albums</option>
                <option value="1">Publics</option>
                <option value="0">Privés</option>
            </select>
            
            <select id="sort-albums" class="filter-select">
                <option value="recent">Plus récents</option>
                <option value="popular">Plus populaires</option>
                <option value="name">Par nom</option>
                <option value="photos">Par nombre de photos</option>
            </select>
        </div>
    </div>

    <!-- Albums Grid -->
    <div class="albums-grid">
        <?php foreach ($albums as $album): ?>
            <div class="album-card admin-album-card">
                <div class="album-cover">
                    <?php if ($album['preview_photo']): ?>
                        <img src="/uploads/albums/<?= htmlspecialchars($album['preview_photo']) ?>" 
                             alt="<?= htmlspecialchars($album['title']) ?>"
                             loading="lazy"
                             class="album-image">
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
                        <div class="album-actions">
                            <a href="/albums/<?= $album['id'] ?>" class="album-action-btn" title="Voir">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8-11-8z"/>
                                    <circle cx="12" cy="12" r="3"/>
                                </svg>
                            </a>
                            
                            <a href="/albums/<?= $album['id'] ?>/edit" class="album-action-btn" title="Modifier">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/>
                                    <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                </svg>
                            </a>
                            
                            <button class="album-action-btn delete-btn" 
                                    data-album-id="<?= $album['id'] ?>"
                                    data-album-title="<?= htmlspecialchars($album['title']) ?>"
                                    title="Supprimer">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M3 6h18M8 6V4a2 2 0 012-2h4a2 2 0 012 2v2m3 0v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6h14zM10 11v6M14 11v6M10 11v6"/>
                                </svg>
                            </button>
                        </div>
                        
                        <div class="album-info">
                            <span class="visibility-badge visibility-<?= $album['is_public'] ? 'public' : 'private' ?>">
                                <?= $album['is_public'] ? 'Public' : 'Privé' ?>
                            </span>
                        </div>
                    </div>
                </div>
                
                <div class="album-info">
                    <h3 class="album-title">
                        <a href="/albums/<?= $album['id'] ?>">
                            <?= htmlspecialchars($album['title']) ?>
                        </a>
                    </h3>
                    
                    <p class="album-meta">
                        <span class="album-author">
                            par <a href="/users/<?= $album['user_id'] ?>"><?= htmlspecialchars($album['username']) ?></a>
                        </span>
                        <span class="album-date">
                            <?= date('d/m/Y', strtotime($album['created_at'])) ?>
                        </span>
                    </p>
                    
                    <?php if ($album['description']): ?>
                        <p class="album-description">
                            <?= htmlspecialchars(substr($album['description'], 0, 150)) ?>...
                        </p>
                    <?php endif; ?>
                    
                    <div class="album-stats">
                        <span class="stat-item">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/>
                            </svg>
                            <?= $album['photo_count'] ?> photo<?= $album['photo_count'] > 1 ? 's' : '' ?>
                        </span>
                        
                        <span class="stat-item">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8-11-8z"/>
                                <circle cx="12" cy="12" r="3"/>
                            </svg>
                            <?= $album['views_count'] ?? 0 ?>
                        </span>
                    </div>
                    
                    <div class="album-owner">
                        <img src="<?= getUserAvatar($album, 24) ?>" alt="Avatar" class="owner-avatar">
                        <div class="owner-info">
                            <span class="owner-name"><?= htmlspecialchars($album['first_name'] . ' ' . $album['last_name']) ?></span>
                            <span class="owner-username">@<?= htmlspecialchars($album['username']) ?></span>
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
                <a href="?page=<?= $pagination['current'] - 1 ?><?= isset($_GET['search']) ? '&search=' . urlencode($_GET['search']) : '' ?><?= isset($_GET['visibility']) ? '&visibility=' . urlencode($_GET['visibility']) : '' ?><?= isset($_GET['sort']) ? '&sort=' . urlencode($_GET['sort']) : '' ?>" class="pagination-link prev">Précédent</a>
            <?php endif; ?>
            
            <?php for ($i = 1; $i <= $pagination['pages']; $i++): ?>
                <?php if ($i == $pagination['current']): ?>
                    <span class="pagination-link current"><?= $i ?></span>
                <?php elseif (abs($i - $pagination['current']) <= 2 || $i == 1 || $i == $pagination['pages']): ?>
                    <a href="?page=<?= $i ?><?= isset($_GET['search']) ? '&search=' . urlencode($_GET['search']) : '' ?><?= isset($_GET['visibility']) ? '&visibility=' . urlencode($_GET['visibility']) : '' ?><?= isset($_GET['sort']) ? '&sort=' . urlencode($_GET['sort']) : '' ?>" class="pagination-link"><?= $i ?></a>
                <?php endif; ?>
            <?php endfor; ?>
            
            <?php if ($pagination['current'] < $pagination['pages']): ?>
                <a href="?page=<?= $pagination['current'] + 1 ?><?= isset($_GET['search']) ? '&search=' . urlencode($_GET['search']) : '' ?><?= isset($_GET['visibility']) ? '&visibility=' . urlencode($_GET['visibility']) : '' ?><?= isset($_GET['sort']) ? '&sort=' . urlencode($_GET['sort']) : '' ?>" class="pagination-link next">Suivant</a>
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
            <p>Êtes-vous sûr de vouloir supprimer cet album ?</p>
            <p><strong>Cette action est irréversible et supprimera toutes les photos contenues dans cet album.</strong></p>
            <p><strong>Album : <span id="delete-album-title"></span></strong></p>
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
    const searchInput = document.getElementById("album-search");
    const filterVisibility = document.getElementById("filter-visibility");
    const sortAlbums = document.getElementById("sort-albums");
    
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
    
    sortAlbums.addEventListener("change", function() {
        const url = new URL(window.location);
        if (this.value) {
            url.searchParams.set("sort", this.value);
        } else {
            url.searchParams.delete("sort");
        }
        window.location = url.toString();
    });
    
    // Delete album
    const deleteButtons = document.querySelectorAll(".delete-btn");
    const deleteModal = document.getElementById("delete-modal");
    const deleteForm = document.getElementById("delete-form");
    const modalClose = document.querySelector(".modal-close");
    const modalCancel = document.querySelector(".modal-cancel");
    
    deleteButtons.forEach(btn => {
        btn.addEventListener("click", function() {
            const albumId = this.dataset.albumId;
            const albumTitle = this.dataset.albumTitle;
            
            document.getElementById("delete-album-title").textContent = albumTitle;
            deleteForm.action = `/admin/albums/${albumId}/delete`;
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
});
</script>

<style>
.admin-album-card {
    position: relative;
}

.album-overlay {
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

.album-card:hover .album-overlay {
    opacity: 1;
}

.album-actions {
    display: flex;
    gap: 0.5rem;
}

.album-action-btn {
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

.album-action-btn:hover {
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

.album-owner {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    margin-top: 1rem;
    padding-top: 1rem;
    border-top: 1px solid #eee;
}

.owner-avatar {
    width: 24px;
    height: 24px;
    border-radius: 50%;
    object-fit: cover;
}

.owner-info {
    display: flex;
    flex-direction: column;
}

.owner-name {
    font-weight: 600;
    color: #333;
    font-size: 0.875rem;
}

.owner-username {
    color: #666;
    font-size: 0.75rem;
}

@media (max-width: 768px) {
    .album-actions {
        flex-direction: column;
        gap: 0.25rem;
    }
}
</style>';
require_once __DIR__ . '/../partials/footer.php'; 
?>
