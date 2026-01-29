<?php 
$pageTitle = 'Mon Profil';
require_once __DIR__ . '/../partials/header.php'; 
?>

<div class="page-header">
    <div class="container">
        <h1 class="page-title">Mon Profil</h1>
        <p class="page-description">Gérez vos informations personnelles et vos préférences</p>
    </div>
</div>

<div class="container">
    <div class="profile-container">
        <div class="profile-sidebar">
            <div class="profile-card">
                <div class="profile-avatar">
                    <img src="<?= getUserAvatar($user, 120) ?>" alt="Avatar">
                    <button class="avatar-upload-btn" id="avatar-upload-btn">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M23 19a2 2 0 01-2 2H3a2 2 0 01-2-2V8a2 2 0 012-2h4l2-3h6l2 3h4a2 2 0 012 2z"/>
                            <circle cx="12" cy="13" r="4"/>
                        </svg>
                    </button>
                    <input type="file" id="avatar-input" accept="image/*" hidden>
                </div>
                
                <div class="profile-info">
                    <h2 class="profile-name"><?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?></h2>
                    <p class="profile-username">@<?= htmlspecialchars($user['username']) ?></p>
                    
                    <?php if ($user['bio']): ?>
                        <p class="profile-bio"><?= htmlspecialchars($user['bio']) ?></p>
                    <?php endif; ?>
                    
                    <div class="profile-stats">
                        <div class="stat-item">
                            <span class="stat-value"><?= $stats['album_count'] ?></span>
                            <span class="stat-label">Albums</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-value"><?= $stats['photo_count'] ?></span>
                            <span class="stat-label">Photos</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-value"><?= $stats['favorite_count'] ?></span>
                            <span class="stat-label">Favoris</span>
                        </div>
                    </div>
                </div>
                
                <div class="profile-actions">
                    <a href="/users/<?= $user['id'] ?>" class="btn btn-outline btn-block">Voir mon profil public</a>
                    <a href="/albums/create" class="btn btn-primary btn-block">Créer un album</a>
                </div>
            </div>
            
            <div class="quick-links">
                <h3>Liens Rapides</h3>
                <nav class="quick-nav">
                    <a href="/photos/upload" class="quick-link">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4M17 8l-5-5-5 5M12 3v12"/>
                        </svg>
                        Uploader une photo
                    </a>
                    <a href="/favorites" class="quick-link">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                        </svg>
                        Mes favoris
                    </a>
                    <a href="/albums" class="quick-link">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/>
                            <circle cx="8.5" cy="8.5" r="1.5"/>
                            <path d="m21 15-5-5L5 21"/>
                        </svg>
                        Mes albums
                    </a>
                    <?php if (isAdmin()): ?>
                        <a href="/admin/dashboard" class="quick-link">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/>
                                <path d="M9 22V12h6v10"/>
                            </svg>
                            Administration
                        </a>
                    <?php endif; ?>
                </nav>
            </div>
        </div>
        
        <div class="profile-main">
            <div class="tabs-container">
                <div class="tabs-header">
                    <button class="tab-btn active" data-tab="profile">Profil</button>
                    <button class="tab-btn" data-tab="security">Sécurité</button>
                    <button class="tab-btn" data-tab="activity">Activité</button>
                    <button class="tab-btn" data-tab="settings">Paramètres</button>
                </div>
                
                <div class="tabs-content">
                    <!-- Profile Tab -->
                    <div class="tab-pane active" id="profile-tab">
                        <form action="/users/update-profile" method="POST" class="profile-form">
                            <div class="form-section">
                                <h3>Informations Personnelles</h3>
                                
                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="first_name" class="form-label">Prénom</label>
                                        <input type="text" 
                                               id="first_name" 
                                               name="first_name" 
                                               class="form-control" 
                                               value="<?= htmlspecialchars($user['first_name'] ?? '') ?>"
                                               maxlength="50">
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="last_name" class="form-label">Nom</label>
                                        <input type="text" 
                                               id="last_name" 
                                               name="last_name" 
                                               class="form-control" 
                                               value="<?= htmlspecialchars($user['last_name'] ?? '') ?>"
                                               maxlength="50">
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label for="bio" class="form-label">Bio</label>
                                    <textarea id="bio" 
                                              name="bio" 
                                              class="form-control" 
                                              rows="4"
                                              maxlength="1000"
                                              placeholder="Parlez-nous de vous..."><?= htmlspecialchars($user['bio'] ?? '') ?></textarea>
                                    <div class="form-help">
                                        <span id="bio-count"><?= strlen($user['bio'] ?? '') ?></span>/1000 caractères
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-actions">
                                <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
                            </div>
                        </form>
                    </div>
                    
                    <!-- Security Tab -->
                    <div class="tab-pane" id="security-tab">
                        <form action="/users/change-password" method="POST" class="profile-form">
                            <div class="form-section">
                                <h3>Changer le mot de passe</h3>
                                
                                <div class="form-group">
                                    <label for="current_password" class="form-label">Mot de passe actuel</label>
                                    <input type="password" 
                                           id="current_password" 
                                           name="current_password" 
                                           class="form-control" 
                                           required>
                                </div>
                                
                                <div class="form-group">
                                    <label for="new_password" class="form-label">Nouveau mot de passe</label>
                                    <input type="password" 
                                           id="new_password" 
                                           name="new_password" 
                                           class="form-control" 
                                           required
                                           minlength="8">
                                    <div class="form-help">Minimum 8 caractères</div>
                                </div>
                                
                                <div class="form-group">
                                    <label for="confirm_password" class="form-label">Confirmer le nouveau mot de passe</label>
                                    <input type="password" 
                                           id="confirm_password" 
                                           name="confirm_password" 
                                           class="form-control" 
                                           required>
                                </div>
                            </div>
                            
                            <div class="form-actions">
                                <button type="submit" class="btn btn-primary">Changer le mot de passe</button>
                            </div>
                        </form>
                        
                        <div class="form-section">
                            <h3>Sessions Actives</h3>
                            <div class="sessions-list">
                                <div class="session-item current">
                                    <div class="session-info">
                                        <h4>Cette session</h4>
                                        <p><?= $_SERVER['HTTP_USER_AGENT'] ?></p>
                                        <small>IP: <?= $_SERVER['REMOTE_ADDR'] ?></small>
                                    </div>
                                    <span class="session-badge current">Actuelle</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Activity Tab -->
                    <div class="tab-pane" id="activity-tab">
                        <div class="activity-section">
                            <h3>Activité Récente</h3>
                            
                            <?php if (!empty($activities)): ?>
                                <div class="activity-list">
                                    <?php foreach ($activities as $activity): ?>
                                        <div class="activity-item">
                                            <div class="activity-icon">
                                                <?php
                                                $icon = '';
                                                switch ($activity['action']) {
                                                    case 'create':
                                                    case 'upload':
                                                        $icon = '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12h14"/></svg>';
                                                        break;
                                                    case 'update':
                                                    case 'edit':
                                                        $icon = '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>';
                                                        break;
                                                    case 'delete':
                                                        $icon = '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18M8 6V4a2 2 0 012-2h4a2 2 0 012 2v2m3 0v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6h14zM10 11v6M14 11v6"/></svg>';
                                                        break;
                                                    case 'favorite':
                                                        $icon = '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>';
                                                        break;
                                                    default:
                                                        $icon = '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>';
                                                }
                                                echo $icon;
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
                            <?php else: ?>
                                <p class="empty-state">Aucune activité récente</p>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <!-- Settings Tab -->
                    <div class="tab-pane" id="settings-tab">
                        <div class="form-section">
                            <h3>Préférences de Notification</h3>
                            
                            <div class="form-options">
                                <div class="form-check">
                                    <input type="checkbox" id="email_comments" name="email_comments" class="form-check-input" checked>
                                    <label for="email_comments" class="form-check-label">
                                        Notifications par email pour les nouveaux commentaires
                                    </label>
                                </div>
                                
                                <div class="form-check">
                                    <input type="checkbox" id="email_favorites" name="email_favorites" class="form-check-input" checked>
                                    <label for="email_favorites" class="form-check-label">
                                        Notifications par email pour les nouveaux favoris
                                    </label>
                                </div>
                                
                                <div class="form-check">
                                    <input type="checkbox" id="email_follows" name="email_follows" class="form-check-input">
                                    <label for="email_follows" class="form-check-label">
                                        Notifications par email pour les nouveaux abonnés
                                    </label>
                                </div>
                                
                                <div class="form-check">
                                    <input type="checkbox" id="email_newsletter" name="email_newsletter" class="form-check-input" checked>
                                    <label for="email_newsletter" class="form-check-label">
                                        Newsletter mensuelle
                                    </label>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-section">
                            <h3>Confidentialité</h3>
                            
                            <div class="form-options">
                                <div class="form-check">
                                    <input type="checkbox" id="public_profile" name="public_profile" class="form-check-input" checked>
                                    <label for="public_profile" class="form-check-label">
                                        Profil public visible par tous
                                    </label>
                                </div>
                                
                                <div class="form-check">
                                    <input type="checkbox" id="show_email" name="show_email" class="form-check-input">
                                    <label for="show_email" class="form-check-label">
                                        Afficher mon email publiquement
                                    </label>
                                </div>
                                
                                <div class="form-check">
                                    <input type="checkbox" id="allow_messages" name="allow_messages" class="form-check-input" checked>
                                    <label for="allow_messages" class="form-check-label">
                                        Autoriser les messages privés
                                    </label>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary">Enregistrer les préférences</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php 
$additionalScripts = '
<script>
document.addEventListener("DOMContentLoaded", function() {
    // Tab functionality
    const tabBtns = document.querySelectorAll(".tab-btn");
    const tabPanes = document.querySelectorAll(".tab-pane");
    
    tabBtns.forEach(btn => {
        btn.addEventListener("click", function() {
            const targetTab = this.dataset.tab;
            
            // Remove active class from all tabs and panes
            tabBtns.forEach(b => b.classList.remove("active"));
            tabPanes.forEach(p => p.classList.remove("active"));
            
            // Add active class to clicked tab and corresponding pane
            this.classList.add("active");
            document.getElementById(targetTab + "-tab").classList.add("active");
        });
    });
    
    // Avatar upload
    const avatarUploadBtn = document.getElementById("avatar-upload-btn");
    const avatarInput = document.getElementById("avatar-input");
    
    avatarUploadBtn.addEventListener("click", () => avatarInput.click());
    
    avatarInput.addEventListener("change", function(e) {
        const file = e.target.files[0];
        if (file && file.type.startsWith("image/")) {
            // Handle avatar upload
            const formData = new FormData();
            formData.append("avatar", file);
            
            fetch("/users/upload-avatar", {
                method: "POST",
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                }
            });
        }
    });
    
    // Bio character count
    const bioTextarea = document.getElementById("bio");
    const bioCount = document.getElementById("bio-count");
    
    bioTextarea.addEventListener("input", function() {
        bioCount.textContent = this.value.length;
    });
    
    // Password confirmation validation
    const newPassword = document.getElementById("new_password");
    const confirmPassword = document.getElementById("confirm_password");
    
    confirmPassword.addEventListener("input", function() {
        if (this.value !== newPassword.value) {
            this.setCustomValidity("Les mots de passe ne correspondent pas");
        } else {
            this.setCustomValidity("");
        }
    });
});

// Helper functions (would normally be in PHP helpers)
function getActivityDescription(activity) {
    const actions = {
        "create": "a créé",
        "upload": "a uploadé",
        "update": "a mis à jour",
        "edit": "a modifié",
        "delete": "a supprimé",
        "favorite": "a ajouté aux favoris",
        "unfavorite": "a retiré des favoris",
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
</script>';
require_once __DIR__ . '/../partials/footer.php'; 
?>
