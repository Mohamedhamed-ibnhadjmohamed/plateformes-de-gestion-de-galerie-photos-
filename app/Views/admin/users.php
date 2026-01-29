<?php 
$pageTitle = 'Gestion des Utilisateurs';
require_once __DIR__ . '/../partials/header.php'; 
?>

<div class="page-header">
    <div class="container">
        <h1 class="page-title">Gestion des Utilisateurs</h1>
        <p class="page-description">Gérez tous les utilisateurs de la plateforme</p>
    </div>
</div>

<div class="container">
    <!-- Search and Filter -->
    <div class="search-filter-bar">
        <div class="search-box">
            <input type="text" id="user-search" placeholder="Recherchercher un utilisateur...">
            <button type="button" class="search-btn">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="11" cy="11" r="8"/>
                    <path d="m21 21-4.35-4.35"/>
                </svg>
            </button>
        </div>
        
        <div class="filter-options">
            <select id="filter-role" class="filter-select">
                <option value="">Tous les rôles</option>
                <option value="admin">Administrateurs</option>
                <option value="user">Utilisateurs</option>
            </select>
            
            <select id="filter-status" class="filter-select">
                <option value="">Tous les statuts</option>
                <option value="1">Actifs</option>
                <option value="0">Inactifs</option>
            </select>
        </div>
    </div>

    <!-- Users Table -->
    <div class="table-container">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Utilisateur</th>
                    <th>Email</th>
                    <th>Rôle</th>
                    <th>Statut</th>
                    <th>Inscription</th>
                    <th>Dernière connexion</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td>
                            <div class="user-info">
                                <img src="<?= getUserAvatar($user, 32) ?>" alt="Avatar" class="user-avatar">
                                <div>
                                    <span class="user-name"><?= htmlspecialchars($user['username']) ?></span>
                                    <span class="user-email"><?= htmlspecialchars($user['email']) ?></span>
                                </div>
                            </div>
                        </td>
                        <td><?= htmlspecialchars($user['email']) ?></td>
                        <td>
                            <span class="badge badge-<?= $user['role'] ?>">
                                <?= $user['role'] === 'admin' ? 'Admin' : 'User' ?>
                            </span>
                        </td>
                        <td>
                            <span class="status status-<?= $user['is_active'] ? 'active' : 'inactive' ?>">
                                <?= $user['is_active'] ? 'Actif' : 'Inactif' ?>
                            </span>
                        </td>
                        <td><?= date('d/m/Y', strtotime($user['created_at'])) ?></td>
                        <td><?= $user['last_login'] ? date('d/m/Y H:i', strtotime($user['last_login'])) : 'Jamais' ?></td>
                        <td>
                            <div class="action-buttons">
                                <a href="/admin/users/<?= $user['id'] ?>/edit" class="btn btn-sm btn-outline">Modifier</a>
                                <button class="btn btn-sm btn-<?= $user['is_active'] ? 'warning' : 'success' ?> toggle-status" 
                                        data-user-id="<?= $user['id'] ?>"
                                        data-status="<?= $user['is_active'] ?>">
                                    <?= $user['is_active'] ? 'Désactiver' : 'Activer' ?>
                                </button>
                                <button class="btn btn-sm btn-danger delete-user" 
                                        data-user-id="<?= $user['id'] ?>"
                                        data-username="<?= htmlspecialchars($user['username']) ?>">
                                    Supprimer
                                </button>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    <?php if ($pagination['pages'] > 1): ?>
        <div class="pagination">
            <?php if ($pagination['current'] > 1): ?>
                <a href="?page=<?= $pagination['current'] - 1 ?><?= isset($_GET['search']) ? '&search=' . urlencode($_GET['search']) : '' ?><?= isset($_GET['role']) ? '&role=' . urlencode($_GET['role']) : '' ?><?= isset($_GET['status']) ? '&status=' . urlencode($_GET['status']) : '' ?>" class="pagination-link prev">Précédent</a>
            <?php endif; ?>
            
            <?php for ($i = 1; $i <= $pagination['pages']; $i++): ?>
                <?php if ($i == $pagination['current']): ?>
                    <span class="pagination-link current"><?= $i ?></span>
                <?php elseif (abs($i - $pagination['current']) <= 2 || $i == 1 || $i == $pagination['pages']): ?>
                    <a href="?page=<?= $i ?><?= isset($_GET['search']) ? '&search=' . urlencode($_GET['search']) : '' ?><?= isset($_GET['role']) ? '&role=' . urlencode($_GET['role']) : '' ?><?= isset($_GET['status']) ? '&status=' . urlencode($_GET['status']) : '' ?>" class="pagination-link"><?= $i ?></a>
                <?php endif; ?>
            <?php endfor; ?>
            
            <?php if ($pagination['current'] < $pagination['pages']): ?>
                <a href="?page=<?= $pagination['current'] + 1 ?><?= isset($_GET['search']) ? '&search=' . urlencode($_GET['search']) : '' ?><?= isset($_GET['role']) ? '&role=' . urlencode($_GET['role']) : '' ?><?= isset($_GET['status']) ? '&status=' . urlencode($_GET['status']) : '' ?>" class="pagination-link next">Suivant</a>
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
            <p>Êtes-vous sûr de vouloir supprimer cet utilisateur ?</p>
            <p><strong>Cette action est irréversible et supprimera toutes les données associées (albums, photos, commentaires).</strong></p>
            <p><strong>Utilisateur : <span id="delete-username"></span></strong></p>
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
    const searchInput = document.getElementById("user-search");
    const filterRole = document.getElementById("filter-role");
    const filterStatus = document.getElementById("filter-status");
    
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
    
    filterRole.addEventListener("change", function() {
        const url = new URL(window.location);
        if (this.value) {
            url.searchParams.set("role", this.value);
        } else {
            url.searchParams.delete("role");
        }
        window.location = url.toString();
    });
    
    filterStatus.addEventListener("change", function() {
        const url = new URL(window.location);
        if (this.value) {
            url.searchParams.set("status", this.value);
        } else {
            url.searchParams.delete("status");
        }
        window.location = url.toString();
    });
    
    // Toggle user status
    const toggleButtons = document.querySelectorAll(".toggle-status");
    toggleButtons.forEach(btn => {
        btn.addEventListener("click", function() {
            const userId = this.dataset.userId;
            const currentStatus = this.dataset.status;
            const newStatus = currentStatus === "1" ? "0" : "1";
            
            fetch(`/admin/toggle-user-status/${userId}`, {
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
                    alert(data.message || "Erreur lors du changement de statut");
                }
            })
            .catch(error => {
                console.error("Error:", error);
                alert("Erreur lors du changement de statut");
            });
        });
    });
    
    // Delete user
    const deleteButtons = document.querySelectorAll(".delete-user");
    const deleteModal = document.getElementById("delete-modal");
    const deleteForm = document.getElementById("delete-form");
    const modalClose = document.querySelector(".modal-close");
    const modalCancel = document.querySelector(".modal-cancel");
    
    deleteButtons.forEach(btn => {
        btn.addEventListener("click", function() {
            const userId = this.dataset.userId;
            const username = this.dataset.username;
            
            document.getElementById("delete-username").textContent = username;
            deleteForm.action = `/admin/users/${userId}/delete`;
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
.user-info {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.user-avatar {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    object-fit: cover;
}

.user-name {
    font-weight: 600;
    color: #333;
    display: block;
}

.user-email {
    font-size: 0.875rem;
    color: #666;
}

.badge {
    padding: 0.25rem 0.75rem;
    border-radius: 12px;
    font-size: 0.75rem;
    font-weight: 500;
}

.badge-admin {
    background: #dc3545;
    color: #fff;
}

.badge-user {
    background: #6c757d;
    color: #fff;
}

.status {
    padding: 0.25rem 0.75rem;
    border-radius: 12px;
    font-size: 0.75rem;
    font-weight: 500;
}

.status-active {
    background: #28a745;
    color: #fff;
}

.status-inactive {
    background: #6c757d;
    color: #fff;
}

.action-buttons {
    display: flex;
    gap: 0.5rem;
}

.table-container {
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    overflow: hidden;
}

.admin-table {
    width: 100%;
    border-collapse: collapse;
}

.admin-table th,
.admin-table td {
    padding: 1rem;
    text-align: left;
    border-bottom: 1px solid #eee;
}

.admin-table th {
    background: #f8f9fa;
    font-weight: 600;
    color: #333;
}

.admin-table tr:hover {
    background-color: #f8f9fa;
}

@media (max-width: 768px) {
    .table-container {
        overflow-x: auto;
    }
    
    .action-buttons {
        flex-direction: column;
        gap: 0.25rem;
    }
    
    .action-buttons .btn {
        width: 100%;
    }
}
</style>';
require_once __DIR__ . '/../partials/footer.php'; 
?>
