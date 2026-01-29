<?php 
$pageTitle = 'Logs d\'Activité';
require_once __DIR__ . '/../partials/header.php'; 
?>

<div class="page-header">
    <div class="container">
        <h1 class="page-title">Logs d'Activité</h1>
        <p class="page-description">Consultez l'historique des actions sur la plateforme</p>
    </div>
</div>

<div class="container">
    <!-- Search and Filter -->
    <div class="search-filter-bar">
        <div class="search-box">
            <input type="text" id="log-search" placeholder="Rechercher dans les logs...">
            <button type="button" class="search-btn">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="11" cy="11" r="8"/>
                    <path d="m21 21-4.35-4.35"/>
                </svg>
            </button>
        </div>
        
        <div class="filter-options">
            <select id="filter-action" class="filter-select">
                <option value="">Toutes les actions</option>
                <option value="create">Créations</option>
                <option value="update">Modifications</option>
                <option value="delete">Suppressions</option>
                <option value="upload">Uploads</option>
                <option value="login">Connexions</option>
                <option value="register">Inscriptions</option>
                <option value="comment">Commentaires</option>
                <option value="favoris">Favoris</option>
            </select>
            
            <select id="filter-resource" class="filter-select">
                <option value="">Toutes les ressources</option>
                <option value="photo">Photos</option>
                <option value="album">Albums</option>
                <option value="user">Utilisateurs</option>
                <option value="comment">Commentaires</option>
            </select>
            
            <select id="filter-user" class="filter-select">
                <option value="">Tous les utilisateurs</option>
                <!-- Users would be populated here -->
            </select>
            
            <select id="filter-date" class="filter-select">
                <option value="">Toutes les dates</option>
                <option value="today">Aujourd'hui</option>
                <option value="week">Cette semaine</option>
                <option value="month">Ce mois</option>
                <option value="year">Cette année</option>
            </select>
            
            <select id="sort-logs" class="filter-select">
                <option value="recent">Plus récents</option>
                <option value="oldest">Plus anciens</option>
            </select>
        </div>
    </div>
    
    <!-- Quick Stats -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M12 20V10M12 20l-4-4m4 4l-4-4m4-4V10"/>
                </svg>
            </div>
            <div class="stat-content">
                <h3><?= $stats['total_logs'] ?? 0 ?></h3>
                <p>Total des logs</p>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 0 0 7z"/>
                </svg>
            </div>
            <div class="stat-content">
                <h3><?= $stats['today_logs'] ?? 0 ?></h3>
                <p>Aujourd'hui</p>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M16 21v-2a4 4 0 00-4H5a2 2 0 01-2-2V5a2 2 0 012-2h6l5 5v11z"/>
                </svg>
            </div>
            <div class="stat-content">
                <h3><?= $stats['week_logs'] ?? 0 ?></h3>
                <p>Cette semaine</p>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="stat-content">
                <h3><?= $stats['active_users'] ?? 0 ?></h3>
                <p>Utilisateurs actifs</p>
            </div>
        </div>
    </div>
    
    <!-- Logs Table -->
    <div class="table-container">
        <table class="logs-table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Utilisateur</th>
                    <th>Action</th>
                    <th>Ressource</th>
                    <th>Détails</th>
                    <th>IP</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($logs as $log): ?>
                    <tr>
                        <td>
                            <span class="log-date"><?= date('d/m/Y H:i:s', strtotime($log['created_at'])) ?></span>
                            <span class="log-relative"><?= formatRelativeTime($log['created_at']) ?></span>
                        </td>
                        <td>
                            <div class="user-info">
                                <img src="<?= getUserAvatar(['username' => $log['username']], 24) ?>" alt="Avatar" class="user-avatar">
                                <span class="user-name"><?= htmlspecialchars($log['username']) ?></span>
                            </div>
                        </td>
                        <td>
                            <span class="action-badge action-<?= $log['action'] ?>">
                                <?= getActionLabel($log['action']) ?>
                            </span>
                        </td>
                        <td>
                            <span class="resource-badge resource-<?= $log['resource_type'] ?>">
                                <?= getResourceLabel($log['resource_type']) ?>
                            </span>
                            <?php if ($log['resource_title']): ?>
                                <span class="resource-title"><?= htmlspecialchars($log['resource_title']) ?></span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="log-details">
                                <p><?= htmlspecialchars($log['description'] ?? '') ?></p>
                                <?php if ($log['details']): ?>
                                    <pre class="log-details-json"><?= htmlspecialchars(json_encode($log['details'], JSON_PRETTY_PRINT)) ?></pre>
                                <?php endif; ?>
                            </div>
                        </td>
                        <td>
                            <span class="ip-address"><?= htmlspecialchars($log['ip_address']) ?></span>
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
                <a href="?page=<?= $pagination['current'] - 1 ?><?= isset($_GET['action']) ? '&action=' . urlencode($_GET['action']) : '' ?><?= isset($_GET['resource']) ? '&resource=' . urlencode($_GET['resource']) : '' ?><?= isset($_GET['user']) ? '&user=' . urlencode($_GET['user']) : '' ?><?= isset($_GET['date']) ? '&date=' . urlencode($_GET['date']) : '' ?><?= isset($_GET['sort']) ? '&sort=' . urlencode($_GET['sort']) : '' ?>" class="pagination-link prev">Précédent</a>
            <?php endif; ?>
            
            <?php for ($i = 1; $i <= $pagination['pages']; $i++): ?>
                <?php if ($i == $pagination['current']): ?>
                    <span class="pagination-link current"><?= $i ?></span>
                <?php elseif (abs($i - $pagination['current']) <= 2 || $i == 1 || $i == $pagination['pages']): ?>
                    <a href="?page=<?= $i ?><?= isset($_GET['action']) ? '&action=' . urlencode($_GET['action']) : '' ?><?= isset($_GET['resource']) ? '&resource=' . urlencode($_GET['resource']) : '' ?><?= isset($_GET['user']) ? '&user=' . urlencode($_GET['user']) : '' ?><?= isset($_GET['date']) ? '&date=' . urlencode($_GET['date']) : '' ?><?= isset($_GET['sort']) ? '&sort=' . urlencode($_GET['sort']) : '' ?>" class="pagination-link"><?= $i ?></a>
                <?php endif; ?>
            <?php endfor; ?>
            
            <?php if ($pagination['current'] < $pagination['pages']): ?>
                <a href="?page=<?= $pagination['current'] + 1 ?><?= isset($_GET['action']) ? '&action=' . urlencode($_GET['action']) : '' ?><?= isset($_GET['resource']) ? '&resource=' . urlencode($_GET['resource']) : '' ?><?= isset($_GET['user']) ? '&user=' . urlencode($_GET['user']) : '' ?><?= isset($_GET['date']) ? '&date=' . urlencode($_GET['date']) : '' ?><?= isset($_GET['sort']) ? '&sort=' . urlencode($_GET['sort']) : '' ?>" class="pagination-link next">Suivant</a>
            <?php endif; ?>
        </div>
    <?php endif; ?>
    
    <!-- Actions -->
    <div class="log-actions">
        <button class="btn btn-danger" id="cleanup-logs">Nettoyer les anciens logs</button>
        <button class="btn btn-outline" id="export-logs">Exporter les logs</button>
        <button class="btn btn-outline" id="refresh-logs">Rafraîchir</button>
    </div>
</div>

<?php 
$additionalScripts = '
<script>
document.addEventListener("DOMContentLoaded", function() {
    // Search functionality
    const searchInput = document.getElementById("log-search");
    const filterAction = document.getElementById("filter-action");
    const filterResource = document.getElementById("filter-resource");
    const filterUser = document.getElementById("filter-user");
    const filterDate = document.getElementById("filter-date");
    const sortLogs = document.getElementById("sort-logs");
    
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
    
    // Filter handlers
    [filterAction, filterResource, filterUser, filterDate, sortLogs].forEach(select => {
        select.addEventListener("change", function() {
            const url = new URL(window.location);
            if (this.value) {
                url.searchParams.set(this.id.replace("filter-", ""), this.value);
            } else {
                url.searchParams.delete(this.id.replace("filter-", ""));
            }
            window.location = url.toString();
        });
    });
    
    // Cleanup logs
    const cleanupBtn = document.getElementById("cleanup-logs");
    if (cleanupBtn) {
        cleanupBtn.addEventListener("click", function() {
            if (confirm("Êtes-vous sûr de vouloir nettoyer les logs anciens ? Cette action est irréversible.")) {
                fetch("/admin/cleanup-logs", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-Requested-With": "XMLHttpRequest"
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showNotification("Logs nettoyés avec succès", "success");
                        setTimeout(() => location.reload(), 1000);
                    } else {
                        showNotification(data.message || "Erreur lors du nettoyage des logs", "error");
                    }
                })
                .catch(error => {
                    console.error("Error:", error);
                    showNotification("Erreur lors du nettoyage des logs", "error");
                });
            }
        });
    }
    
    // Export logs
    const exportBtn = document.getElementById("export-logs");
    if (exportBtn) {
        exportBtn.addEventListener("click", function() {
            const url = new URL(window.location);
            url.searchParams.set("export", "1");
            window.open(url.toString(), "_blank");
        });
    }
    
    // Refresh logs
    const refreshBtn = document.getElementById("refresh-logs");
    if (refreshBtn) {
        refreshBtn.addEventListener("click", function() {
            location.reload();
        });
    }
    
    // Auto-refresh every 30 seconds
    setInterval(() => {
        if (!document.hidden) {
            location.reload();
        }
    }, 30000);
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

function formatRelativeTime(dateString) {
    const date = new Date(dateString);
    const now = new Date();
    const diff = Math.floor((now - date) / 1000);
    
    if (diff < 60) return "Il y a quelques secondes";
    if (diff < 3600) return `Il y a ${Math.floor(diff / 60)} minute${Math.floor(diff / 60) > 1 ? "s" : ""}`;
    if (diff < 86400) return `Il y a ${Math.floor(diff / 3600)} heure${Math.floor(diff / 3600) > 1 ? "s" : ""}`;
    if (diff < 2592000) return `Il y a ${Math.floor(diff / 86400)} jour${Math.floor(diff / 86400) > 1 ? "s" : ""}`;
    
    return date.toLocaleDateString("fr-FR");
}

function getActionLabel(action) {
    const labels = {
        "create": "Création",
        "update": "Modification",
        "delete": "Suppression",
        "upload": "Upload",
        "login": "Connexion",
        "register": "Inscription",
        "logout": "Déconnexion",
        "comment": "Commentaire",
        "favoris": "Favoris",
        "unfavoris": "Retrait favoris",
        "approve": "Approbation",
        "unapprove": "Désapprobation",
        "view": "Visualisation"
    };
    
    return labels[action] || action;
}

function getResourceLabel(resource) {
    const labels = {
        "photo": "Photo",
        "album": "Album",
        "user": "Utilisateur",
        "comment": "Commentaire",
        "tag": "Tag",
        "favorite": "Favori"
    };
    
    return labels[resource] || resource;
}
</script>

<style>
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.stat-card {
    background: #fff;
    padding: 1.5rem;
    border-radius: 12px;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    display: flex;
    align-items: center;
    gap: 1rem;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}

.stat-icon {
    width: 48px;
    height: 48px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
}

.stat-content h3 {
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: 0.25rem;
    color: #333;
}

.stat-content p {
    color: #666;
    margin: 0;
}

.table-container {
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    overflow: hidden;
    margin-bottom: 2rem;
}

.logs-table {
    width: 100%;
    border-collapse: collapse;
}

.logs-table th,
.logs-table td {
    padding: 1rem;
    text-align: left;
    border-bottom: 1px solid #eee;
}

.logs-table th {
    background: #f8f9fa;
    font-weight: 600;
    color: #333;
    position: sticky;
    top: 0;
    z-index: 1;
}

.logs-table tr:hover {
    background-color: #f8f9fa;
}

.log-date {
    display: block;
    font-weight: 500;
    color: #333;
}

.log-relative {
    display: block;
    font-size: 0.875rem;
    color: #666;
    margin-top: 0.25rem;
}

.user-info {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.user-avatar {
    width: 24px;
    height: 24px;
    border-radius: 50%;
    object-fit: cover;
}

.user-name {
    font-weight: 500;
    color: #333;
}

.action-badge,
.resource-badge {
    padding: 0.25rem 0.75rem;
    border-radius: 12px;
    font-size: 0.75rem;
    font-weight: 500;
    color: #fff;
}

.action-create { background: #28a745; }
.action-update { background: #007bff; }
.action-delete { background: #dc3545; }
.action-upload { background: #17a2b8; }
.action-login { background: #6c757d; }
.action-register { background: #6f42c1; }
.action-comment { background: #fd7e14; }
.action-favoris { background: #e83e8c; }
.action-unfavoris { background: #6c757d; }

.resource-photo { background: #007bff; }
.resource-album { background: #28a745; }
.resource-user { background: #6c757d; }
.resource-comment { background: #fd7e14; }
.resource-tag { background: #6f42c1; }
.resource-favorite { background: #e83e8c; }

.resource-title {
    display: block;
    font-size: 0.875rem;
    color: #666;
    margin-top: 0.25rem;
}

.log-details {
    max-width: 300px;
}

.log-details p {
    margin: 0 0 0.5rem 0;
    line-height: 1.4;
    color: #333;
}

.log-details-json {
    background: #f8f9fa;
    padding: 0.5rem;
    border-radius: 4px;
    font-size: 0.75rem;
    color: #666;
    max-height: 100px;
    overflow-y: auto;
    margin: 0;
}

.ip-address {
    font-family: monospace;
    font-size: 0.875rem;
    color: #666;
}

.log-actions {
    display: flex;
    gap: 1rem;
    margin-top: 2rem;
    padding: 1.5rem;
    background: #f8f9fa;
    border-radius: 12px;
}

@media (max-width: 768px) {
    .stats-grid {
        grid-template-columns: 1fr;
    }
    
    .table-container {
        overflow-x: auto;
    }
    
    .logs-table {
        min-width: 800px;
    }
    
    .log-details {
        max-width: 200px;
    }
    
    .log-actions {
        flex-direction: column;
    }
}
</style>';
require_once __DIR__ . '/../partials/footer.php'; 

// Helper functions
function getActionLabel($action) {
    $labels = [
        'create' => 'Création',
        'update' => 'Modification',
        'delete' => 'Suppression',
        'upload' => 'Upload',
        'login' => 'Connexion',
        'register' => 'Inscription',
        'logout' => 'Déconnexion',
        'comment' => 'Commentaire',
        'favoris' => 'Favoris',
        'unfavoris' => 'Retrait favoris',
        'approve' => 'Approbation',
        'unapprove' => 'Désapprobation',
        'view' => 'Visualisation'
    ];
    
    return $labels[$action] ?? $action;
}

function getResourceLabel($resource) {
    $labels = [
        'photo' => 'Photo',
        'album' => 'Album',
        'user' => 'Utilisateur',
        'comment' => 'Commentaire',
        'tag' => 'Tag',
        'favorite' => 'Favori'
    ];
    
    return $labels[$resource] ?? $resource;
}
?>
