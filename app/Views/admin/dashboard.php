<?php 
$pageTitle = 'Tableau de Bord Administration';
require_once __DIR__ . '/../partials/header.php'; 
?>

<div class="page-header">
    <div class="container">
        <h1 class="page-title">Tableau de Bord</h1>
        <p class="page-description">Vue d'ensemble de la plateforme</p>
    </div>
</div>

<div class="container">
    <!-- Statistics Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M17 21v-2a4 4 0 00-4-4H5a2 2 0 00-2 2H3"/>
                    <path d="M21 16V8a2 2 0 00-2-2h-1m-1 4h-8a2 2 0 01-2-2V4a2 2 0 012-2h8a2 2 0 012 2v8a2 2 0 01-2 2h-8a2 2 0 01-2-2z"/>
                </svg>
            </div>
            <div class="stat-content">
                <h3><?= $stats['photos'] ?></h3>
                <p>Photos</p>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/>
                    <circle cx="8.5" cy="8.5" r="1.5"/>
                    <path d="m21 15-5-5L5 21"/>
                </svg>
            </div>
            <div class="stat-content">
                <h3><?= $stats['albums'] ?></h3>
                <p>Albums</p>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M20 21v-2a4 4 0 00-4-4H5a2 2 0 00-2 2v3"/>
                    <path d="M18 8A6 6 0 0012 20a6 6 0 006-6V2"/>
                </svg>
            </div>
            <div class="stat-content">
                <h3><?= $stats['users'] ?></h3>
                <p>Utilisateurs</p>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z"/>
                </svg>
            </div>
            <div class="stat-content">
                <h3><?= $stats['comments'] ?></h3>
                <p>Commentaires</p>
            </div>
        </div>
        
        <div class="stat-card warning">
            <div class="stat-icon">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13 7.5V4a1 1 0 00-1-1H4a1 1 0 00-1 1v3.5L2.752 7.5c-.23.333-.192 3-.732 3z"/>
                </svg>
            </div>
            <div class="stat-content">
                <h3><?= $stats['pending_comments'] ?></h3>
                <p>Commentaires en attente</p>
            </div>
        </div>
    </div>
    
    <!-- Charts Section -->
    <div class="dashboard-section">
        <h2>Activité des 30 derniers jours</h2>
        <div class="charts-grid">
            <div class="chart-card">
                <h3>Actions par type</h3>
                <div class="chart-placeholder">
                    <div class="chart-bar" style="height: 60%; background: #007bff;"></div>
                    <div class="chart-bar" style="height: 30%; background: #28a745;"></div>
                    <div class="chart-bar" style="height: 10%; background: #dc3545;"></div>
                </div>
                <div class="chart-legend">
                    <span class="legend-item"><span class="legend-color" style="background: #007bff;"></span>Créations</span>
                    <span class="legend-item"><span class="legend-color" style="background: #28a745;"></span>Modifications</span>
                    <span class="legend-item"><span class="legend-color" style="background: #dc3545;"></span>Suppressions</span>
                </div>
            </div>
            
            <div class="chart-card">
                <h3>Utilisateurs actifs</h3>
                <div class="chart-placeholder">
                    <div class="chart-bar" style="height: 80%; background: #17a2b8;"></div>
                    <div class="chart-bar" style="height: 60%; background: #17a2b8;"></div>
                    <div class="chart-bar" style="height: 40%; background: #17a2b8;"></div>
                    <div class="chart-bar" style="height: 20%; background: #17a2b8;"></div>
                </div>
                <div class="chart-legend">
                    <span class="legend-item"><span class="legend-color" style="background: #17a2b8;"></span>Top utilisateurs</span>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Recent Activity -->
    <div class="dashboard-section">
        <h2>Activité Récente</h2>
        <div class="activity-list">
            <?php foreach ($recentActivity as $activity): ?>
                <div class="activity-item">
                    <div class="activity-icon">
                        <?php
                        $action = $activity['action'];
                        if ($action === 'create' || $action === 'upload') {
                            echo '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12h14"/></svg>';
                        } elseif ($action === 'update' || $action === 'edit') {
                            echo '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>';
                        } elseif ($action === 'delete') {
                            echo '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18M8 6V4a2 2 0 012-2h4a2 2 0 012 2v2m3 0v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6h14zM10 11v6M14 11v6"/></svg>';
                        } elseif ($action === 'favoris') {
                            echo '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>';
                        } else {
                            echo '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>';
                        }
                        ?>
                    </div>
                    <div class="activity-content">
                        <p class="activity-text">
                            <?= getActivityDescription($activity) ?>
                        </p>
                        <span class="activity-time"><?= formatRelativeTime($activity['created_at']) ?></span>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    
    <!-- Popular Content -->
    <div class="dashboard-section">
        <h2>Contenu Populaire</h2>
        <div class="content-grid">
            <div class="content-section">
                <h3>Photos Populaires</h3>
                <div class="popular-items">
                    <?php foreach ($popularPhotos as $photo): ?>
                        <div class="popular-item">
                            <a href="/photos/<?= $photo['id'] ?>">
                                <img src="/uploads/thumbs/<?= htmlspecialchars($photo['filename']) ?>" 
                                     alt="<?= htmlspecialchars($photo['title'] ?? 'Photo') ?>"
                                     loading="lazy">
                                <div class="popular-overlay">
                                    <span class="popular-title"><?= htmlspecialchars($photo['title'] ?? 'Sans titre') ?></span>
                                    <span class="popular-stats">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                            <circle cx="12" cy="12" r="3"/>
                                        </svg>
                                        <?= $photo['views_count'] ?>
                                    </span>
                                </div>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            
            <div class="content-section">
                <h3>Albums Populaires</h3>
                <div class="popular-items">
                    <?php foreach ($popularAlbums as $album): ?>
                        <div class="popular-item">
                            <a href="/albums/<?= $album['id'] ?>">
                                <?php if ($album['preview_photo']): ?>
                                    <img src="/uploads/thumbs/<?= htmlspecialchars($album['preview_photo']) ?>" 
                                         alt="<?= htmlspecialchars($album['title']) ?>"
                                         loading="lazy">
                                <?php else: ?>
                                    <div class="popular-placeholder">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/>
                                            <circle cx="8.5" cy="8.5" r="1.5"/>
                                            <path d="m21 15-5-5L5 21"/>
                                        </svg>
                                    </div>
                                <?php endif; ?>
                                <div class="popular-overlay">
                                    <span class="popular-title"><?= htmlspecialchars($album['title']) ?></span>
                                    <span class="popular-stats">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/>
                                        </svg>
                                        <?= $album['photo_count'] ?> photos
                                    </span>
                                </div>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Quick Actions -->
    <div class="dashboard-section">
        <h2>Actions Rapides</h2>
        <div class="quick-actions">
            <a href="/admin/users" class="btn btn-outline">Gérer les utilisateurs</a>
            <a href="/admin/albums" class="btn btn-outline">Gérer les albums</a>
            <a href="/admin/photos" class="btn btn-outline">Gérer les photos</a>
            <a href="/admin/comments" class="btn btn-outline">Modérer les commentaires</a>
            <a href="/admin/settings" class="btn btn-primary">Paramètres</a>
        </div>
    </div>
</div>

<?php 
$additionalScripts = '
<script>
document.addEventListener("DOMContentLoaded", function() {
    // Auto-refresh dashboard stats every 30 seconds
    setInterval(function() {
        location.reload();
    }, 30000);
});

function getActivityDescription(activity) {
    const actions = {
        "create": "a créé",
        "upload": "a uploadé",
        "update": "a modifié",
        "edit": "a modifié",
        "delete": "a supprimé",
        "favoris": "a ajouté aux favoris",
        "unfavoris": "a retiré des favoris",
        "comment": "a commenté",
        "login": "s\'est connecté",
        "register": "s\'est inscrit"
    };
    
    const resources = {
        "photo": "une photo",
        "album": "un album",
        "user": "un utilisateur",
        "comment": "un commentaire"
    };
    
    return `${actions[activity.action] || "a effectué une action sur"} ${resources[activity.resource_type] || "une ressource"}`;
}

function formatRelativeTime(dateString) {
    const date = new Date(dateString);
    const now = new Date();
    const diff = Math.floor((now - date) / 1000); // seconds
    
    if (diff < 60) return "Il y a quelques secondes";
    if (diff < 3600) return `Il y a ${Math.floor(diff / 60)} minute${Math.floor(diff / 60) > 1 ? "s" : ""}`;
    if (diff < 86400) return `Il y a ${Math.floor(diff / 3600)} heure${Math.floor(diff / 3600) > 1 ? "s" : ""}`;
    if (diff < 2592000) return `Il y a ${Math.floor(diff / 86400)} jour${Math.floor(diff / 86400) > 1 ? "s" : ""}`;
    
    return date.toLocaleDateString("fr-FR");
}
</script>

<style>
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
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

.stat-card.warning {
    border-left: 4px solid #ffc107;
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
}

.stat-content p {
    color: #666;
    margin: 0;
}

.dashboard-section {
    margin-bottom: 3rem;
}

.dashboard-section h2 {
    font-size: 1.5rem;
    margin-bottom: 1.5rem;
    color: #333;
}

.charts-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
    gap: 2rem;
    margin-bottom: 2rem;
}

.chart-card {
    background: #fff;
    padding: 1.5rem;
    border-radius: 12px;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
}

.chart-placeholder {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
    margin: 1rem 0;
}

.chart-bar {
    border-radius: 4px;
    transition: all 0.3s ease;
}

.chart-legend {
    display: flex;
    flex-wrap: wrap;
    gap: 1rem;
    margin-top: 1rem;
}

.legend-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.875rem;
}

.legend-color {
    width: 12px;
    height: 12px;
    border-radius: 2px;
}

.activity-list {
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    overflow: hidden;
}

.activity-item {
    display: flex;
    align-items: center;
    padding: 1rem 1.5rem;
    border-bottom: 1px solid #eee;
    transition: background-color 0.3s ease;
}

.activity-item:hover {
    background-color: #f8f9fa;
}

.activity-item:last-child {
    border-bottom: none;
}

.activity-icon {
    width: 40px;
    height: 40px;
    background: #f8f9fa;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #666;
    margin-right: 1rem;
}

.activity-content {
    flex: 1;
}

.activity-text {
    margin-bottom: 0.25rem;
    color: #333;
}

.activity-time {
    font-size: 0.875rem;
    color: #666;
}

.content-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
    gap: 2rem;
}

.content-section h3 {
    font-size: 1.25rem;
    margin-bottom: 1rem;
    color: #333;
}

.popular-items {
    display: grid;
    gap: 1rem;
}

.popular-item {
    position: relative;
    border-radius: 8px;
    overflow: hidden;
    aspect-ratio: 16/9;
}

.popular-item a {
    display: block;
    position: relative;
    width: 100%;
    height: 100%;
}

.popular-item img,
.popular-placeholder {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.popular-placeholder {
    background: #f8f9fa;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #666;
}

.popular-overlay {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    background: linear-gradient(to top, rgba(0,0,0,0.8), transparent);
    color: #fff;
    padding: 1rem;
    display: flex;
    flex-direction: column;
    justify-content: flex-end;
}

.popular-title {
    font-weight: 600;
    margin-bottom: 0.25rem;
}

.popular-stats {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.875rem;
    opacity: 0.9;
}

.quick-actions {
    display: flex;
    flex-wrap: wrap;
    gap: 1rem;
}

@media (max-width: 768px) {
    .stats-grid {
        grid-template-columns: 1fr;
    }
    
    .charts-grid {
        grid-template-columns: 1fr;
    }
    
    .content-grid {
        grid-template-columns: 1fr;
    }
    
    .popular-items {
        grid-template-columns: repeat(2, 1fr);
    }
}
</style>';
require_once __DIR__ . '/../partials/footer.php'; 
?>
