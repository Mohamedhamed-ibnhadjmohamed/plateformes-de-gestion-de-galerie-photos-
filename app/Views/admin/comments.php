<?php 
$pageTitle = 'Modération des Commentaires';
require_once __DIR__ . '/../partials/header.php'; 
?>

<div class="page-header">
    <div class="container">
        <h1 class="page-title">Modération des Commentaires</h1>
        <p class="page-description">Gérez et modérez tous les commentaires de la plateforme</p>
    </div>
</div>

<div class="container">
    <!-- Filter Tabs -->
    <div class="filter-tabs">
        <button class="tab-btn active" data-status="all">Tous les commentaires</button>
        <button class="tab-btn" data-status="pending">En attente</button>
        <button class="tab-btn" data-status="approved">Approuvés</button>
        <button class="tab-btn" data-status="rejected">Rejetés</button>
    </div>

    <!-- Search and Filter -->
    <div class="search-filter-bar">
        <div class="search-box">
            <input type="text" id="comment-search" placeholder="Rechercher un commentaire...">
            <button type="button" class="search-btn">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="11" cy="11" r="8"/>
                    <path d="m21 21-4.35-4.35"/>
                </svg>
            </button>
        </div>
        
        <div class="filter-options">
            <select id="filter-date" class="filter-select">
                <option value="">Toutes les dates</option>
                <option value="today">Aujourd'hui</option>
                <option value="week">Cette semaine</option>
                <option value="month">Ce mois</option>
                <option value="year">Cette année</option>
            </select>
            
            <select id="sort-comments" class="filter-select">
                <option value="recent">Plus récents</option>
                <option value="oldest">Plus anciens</option>
                <option value="pending">En attente en premier</option>
            </select>
        </div>
    </div>

    <!-- Comments List -->
    <div class="comments-list">
        <?php foreach ($comments as $comment): ?>
            <div class="comment-item status-<?= $comment['is_approved'] ? 'approved' : 'pending' ?>">
                <div class="comment-header">
                    <div class="comment-user">
                        <img src="<?= getUserAvatar($comment, 40) ?>" alt="Avatar" class="user-avatar">
                        <div class="user-info">
                            <h4 class="user-name"><?= htmlspecialchars($comment['username']) ?></h4>
                            <span class="user-email"><?= htmlspecialchars($comment['email']) ?></span>
                        </div>
                    </div>
                    
                    <div class="comment-meta">
                        <span class="comment-date"><?= date('d/m/Y à H:i', strtotime($comment['created_at'])) ?></span>
                        <span class="status-badge status-<?= $comment['is_approved'] ? 'approved' : 'pending' ?>">
                            <?= $comment['is_approved'] ? 'Approuvé' : 'En attente' ?>
                        </span>
                    </div>
                </div>
                
                <div class="comment-content">
                    <p class="comment-text"><?= htmlspecialchars($comment['content']) ?></p>
                    
                    <div class="comment-context">
                        <span class="context-info">
                            Sur la photo 
                            <a href="/photos/<?= $comment['photo_id'] ?>" class="photo-link">
                                <?= htmlspecialchars($comment['photo_title'] ?? 'Photo') ?>
                            </a>
                            dans l'album 
                            <a href="/albums/<?= $comment['album_id'] ?>" class="album-link">
                                <?= htmlspecialchars($comment['album_title']) ?>
                            </a>
                        </span>
                    </div>
                </div>
                
                <div class="comment-actions">
                    <a href="/photos/<?= $comment['photo_id'] ?>" class="btn btn-sm btn-outline">Voir la photo</a>
                    
                    <?php if (!$comment['is_approved']): ?>
                        <button class="btn btn-sm btn-success approve-btn" 
                                data-comment-id="<?= $comment['id'] ?>">
                            Approuver
                        </button>
                    <?php endif; ?>
                    
                    <?php if ($comment['is_approved']): ?>
                        <button class="btn btn-sm btn-warning unapprove-btn" 
                                data-comment-id="<?= $comment['id'] ?>">
                            Désapprouver
                        </button>
                    <?php endif; ?>
                    
                    <button class="btn btn-sm btn-danger delete-btn" 
                            data-comment-id="<?= $comment['id'] ?>"
                            data-comment-text="<?= htmlspecialchars(substr($comment['content'], 0, 50)) ?>">
                        Supprimer
                    </button>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    
    <!-- Pagination -->
    <?php if ($pagination['pages'] > 1): ?>
        <div class="pagination">
            <?php if ($pagination['current'] > 1): ?>
                <a href="?page=<?= $pagination['current'] - 1 ?><?= isset($_GET['status']) ? '&status=' . urlencode($_GET['status']) : '' ?><?= isset($_GET['search']) ? '&search=' . urlencode($_GET['search']) : '' ?><?= isset($_GET['date']) ? '&date=' . urlencode($_GET['date']) : '' ?><?= isset($_GET['sort']) ? '&sort=' . urlencode($_GET['sort']) : '' ?>" class="pagination-link prev">Précédent</a>
            <?php endif; ?>
            
            <?php for ($i = 1; $i <= $pagination['pages']; $i++): ?>
                <?php if ($i == $pagination['current']): ?>
                    <span class="pagination-link current"><?= $i ?></span>
                <?php elseif (abs($i - $pagination['current']) <= 2 || $i == 1 || $i == $pagination['pages']): ?>
                    <a href="?page=<?= $i ?><?= isset($_GET['status']) ? '&status=' . urlencode($_GET['status']) : '' ?><?= isset($_GET['search']) ? '&search=' . urlencode($_GET['search']) : '' ?><?= isset($_GET['date']) ? '&date=' . urlencode($_GET['date']) : '' ?><?= isset($_GET['sort']) ? '&sort=' . urlencode($_GET['sort']) : '' ?>" class="pagination-link"><?= $i ?></a>
                <?php endif; ?>
            <?php endfor; ?>
            
            <?php if ($pagination['current'] < $pagination['pages']): ?>
                <a href="?page=<?= $pagination['current'] + 1 ?><?= isset($_GET['status']) ? '&status=' . urlencode($_GET['status']) : '' ?><?= isset($_GET['search']) ? '&search=' . urlencode($_GET['search']) : '' ?><?= isset($_GET['date']) ? '&date=' . urlencode($_GET['date']) : '' ?><?= isset($_GET['sort']) ? '&sort=' . urlencode($_GET['sort']) : '' ?>" class="pagination-link next">Suivant</a>
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
            <p>Êtes-vous sûr de vouloir supprimer ce commentaire ?</p>
            <p><strong>Cette action est irréversible.</strong></p>
            <p><strong>Commentaire : <span id="delete-comment-text"></span></strong></p>
        </div>
        <div class="modal-footer">
            <button class="btn btn-outline modal-cancel">Annuler</button>
            <form id="delete-form" method="POST">
                <button type="submit" class="btn btn-danger">Supprimer</button>
            </form>
        </div>
    </div>
</div>

<!-- Bulk Actions -->
<div class="bulk-actions">
    <div class="bulk-select">
        <input type="checkbox" id="select-all">
        <label for="select-all">Tout sélectionner</label>
    </div>
    
    <div class="bulk-buttons">
        <button class="btn btn-success bulk-approve" disabled>
            Approuver la sélection
        </button>
        <button class="btn btn-warning bulk-unapprove" disabled>
            Désapprouver la sélection
        </button>
        <button class="btn btn-danger bulk-delete" disabled>
            Supprimer la sélection
        </button>
    </div>
</div>

<?php 
$additionalScripts = '
<script>
document.addEventListener("DOMContentLoaded", function() {
    // Filter tabs
    const tabButtons = document.querySelectorAll(".tab-btn");
    tabButtons.forEach(btn => {
        btn.addEventListener("click", function() {
            const status = this.dataset.status;
            const url = new URL(window.location);
            if (status === "all") {
                url.searchParams.delete("status");
            } else {
                url.searchParams.set("status", status);
            }
            window.location = url.toString();
        });
    });
    
    // Search functionality
    const searchInput = document.getElementById("comment-search");
    const filterDate = document.getElementById("filter-date");
    const sortComments = document.getElementById("sort-comments");
    
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
    
    filterDate.addEventListener("change", function() {
        const url = new URL(window.location);
        if (this.value) {
            url.searchParams.set("date", this.value);
        } else {
            url.searchParams.delete("date");
        }
        window.location = url.toString();
    });
    
    sortComments.addEventListener("change", function() {
        const url = new URL(window.location);
        if (this.value) {
            url.searchParams.set("sort", this.value);
        } else {
            url.searchParams.delete("sort");
        }
        window.location = url.toString();
    });
    
    // Comment actions
    const approveButtons = document.querySelectorAll(".approve-btn");
    const unapproveButtons = document.querySelectorAll(".unapprove-btn");
    const deleteButtons = document.querySelectorAll(".delete-btn");
    
    approveButtons.forEach(btn => {
        btn.addEventListener("click", function() {
            const commentId = this.dataset.commentId;
            
            fetch(`/admin/approve-comment/${commentId}`, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-Requested-With": "XMLHttpRequest"
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert(data.message || "Erreur lors de l\'approbation");
                }
            });
        });
    });
    
    unapproveButtons.forEach(btn => {
        btn.addEventListener("click", function() {
            const commentId = this.dataset.commentId;
            
            fetch(`/admin/unapprove-comment/${commentId}`, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-Requested-With": "XMLHttpRequest"
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert(data.message || "Erreur lors de la désapprobation");
                }
            });
        });
    });
    
    // Delete modal
    const deleteModal = document.getElementById("delete-modal");
    const deleteForm = document.getElementById("delete-form");
    const modalClose = document.querySelector(".modal-close");
    const modalCancel = document.querySelector(".modal-cancel");
    
    deleteButtons.forEach(btn => {
        btn.addEventListener("click", function() {
            const commentId = this.dataset.commentId;
            const commentText = this.dataset.commentText;
            
            document.getElementById("delete-comment-text").textContent = commentText;
            deleteForm.action = `/admin/delete-comment/${commentId}`;
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
    
    // Bulk actions
    const selectAll = document.getElementById("select-all");
    const commentCheckboxes = document.querySelectorAll(".comment-checkbox");
    const bulkButtons = document.querySelectorAll(".bulk-buttons button");
    
    selectAll.addEventListener("change", function() {
        commentCheckboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
        updateBulkButtons();
    });
    
    commentCheckboxes.forEach(checkbox => {
        checkbox.addEventListener("change", updateBulkButtons);
    });
    
    function updateBulkButtons() {
        const checkedBoxes = document.querySelectorAll(".comment-checkbox:checked");
        bulkButtons.forEach(btn => {
            btn.disabled = checkedBoxes.length === 0;
        });
    }
});
</script>

<style>
.filter-tabs {
    display: flex;
    gap: 1rem;
    margin-bottom: 2rem;
    border-bottom: 2px solid #eee;
}

.tab-btn {
    padding: 0.75rem 1.5rem;
    background: none;
    border: none;
    border-bottom: 2px solid transparent;
    cursor: pointer;
    font-weight: 500;
    color: #666;
    transition: all 0.3s ease;
}

.tab-btn.active {
    color: #007bff;
    border-bottom-color: #007bff;
}

.tab-btn:hover {
    color: #007bff;
}

.comments-list {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.comment-item {
    background: #fff;
    border-radius: 12px;
    padding: 1.5rem;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    border-left: 4px solid #ddd;
    transition: border-color 0.3s ease;
}

.comment-item.status-pending {
    border-left-color: #ffc107;
}

.comment-item.status-approved {
    border-left-color: #28a745;
}

.comment-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
}

.comment-user {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.user-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    object-fit: cover;
}

.user-name {
    margin: 0;
    color: #333;
}

.user-email {
    font-size: 0.875rem;
    color: #666;
}

.comment-meta {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    gap: 0.25rem;
}

.comment-date {
    font-size: 0.875rem;
    color: #666;
}

.status-badge {
    padding: 0.25rem 0.75rem;
    border-radius: 12px;
    font-size: 0.75rem;
    font-weight: 500;
    color: #fff;
}

.status-approved {
    background: #28a745;
}

.status-pending {
    background: #ffc107;
    color: #333;
}

.comment-content {
    margin-bottom: 1rem;
}

.comment-text {
    margin-bottom: 0.5rem;
    line-height: 1.6;
    color: #333;
}

.comment-context {
    font-size: 0.875rem;
    color: #666;
}

.photo-link,
.album-link {
    color: #007bff;
    text-decoration: none;
}

.photo-link:hover,
.album-link:hover {
    text-decoration: underline;
}

.comment-actions {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
}

.bulk-actions {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem;
    background: #f8f9fa;
    border-radius: 8px;
    margin-top: 2rem;
}

.bulk-select {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.bulk-buttons {
    display: flex;
    gap: 0.5rem;
}

@media (max-width: 768px) {
    .comment-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 1rem;
    }
    
    .comment-meta {
        align-items: flex-start;
    }
    
    .comment-actions {
        flex-direction: column;
    }
    
    .bulk-actions {
        flex-direction: column;
        gap: 1rem;
    }
}
</style>';
require_once __DIR__ . '/../partials/footer.php'; 
?>
