<?php 
$pageTitle = 'Modifier l\'Utilisateur';
require_once __DIR__ . '/../partials/header.php'; 
?>

<div class="page-header">
    <div class="container">
        <h1 class="page-title">Modifier l'Utilisateur</h1>
        <p class="page-description">Modifiez les informations de l'utilisateur</p>
    </div>
</div>

<div class="container">
    <div class="content-wrapper">
        <div class="form-container">
            <form action="/admin/update-user/<?= $user['id'] ?>" method="POST" class="user-edit-form">
                <div class="form-section">
                    <h2>Informations Personnelles</h2>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="first_name" class="form-label">Prénom</label>
                            <input type="text" 
                                   id="first_name" 
                                   name="first_name" 
                                   class="form-control" 
                                   value="<?= htmlspecialchars($user['first_name'] ?? '') ?>"
                                   maxlength="50"
                                   placeholder="Jean">
                        </div>
                        
                        <div class="form-group">
                            <label for="last_name" class="form-label">Nom</label>
                            <input type="text" 
                                   id="last_name" 
                                   name="last_name" 
                                   class="form-control" 
                                   value="<?= htmlspecialchars($user['last_name'] ?? '') ?>"
                                   maxlength="50"
                                   placeholder="Dupont">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="username" class="form-label">Nom d'utilisateur *</label>
                        <input type="text" 
                               id="username" 
                               name="username" 
                               class="form-control" 
                               value="<?= htmlspecialchars($user['username'] ?? '') ?>"
                               required
                               minlength="3"
                               maxlength="50"
                               placeholder="jdupont"
                               autocomplete="username">
                        <div class="form-help">3-50 caractères, lettres, chiffres et underscores uniquement</div>
                    </div>
                    
                    <div class="form-group">
                        <label for="email" class="form-label">Email *</label>
                        <input type="email" 
                               id="email" 
                               name="email" 
                               class="form-control" 
                               value="<?= htmlspecialchars($user['email'] ?? '') ?>"
                               required
                               placeholder="votre@email.com"
                               autocomplete="email">
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
                
                <div class="form-section">
                    <h2>Rôle et Permissions</h2>
                    
                    <div class="form-group">
                        <label for="role" class="form-label">Rôle</label>
                        <select id="role" name="role" class="form-control" required>
                            <option value="user" <?= ($user['role'] ?? 'user') === 'user' ? 'selected' : '' ?>>Utilisateur</option>
                            <option value="admin" <?= ($user['role'] ?? 'user') === 'admin' ? 'selected' : '' ?>>Administrateur</option>
                        </select>
                        <div class="form-help">
                            <strong>Administrateur:</strong> Accès complet à l'administration<br>
                            <strong>Utilisateur:</strong> Accès limité à ses propres contenus
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <div class="form-check">
                            <input type="checkbox" 
                                   id="is_active" 
                                   name="is_active" 
                                   class="form-check-input"
                                   value="1"
                                   <?= ($user['is_active'] ?? 1) ? 'checked' : '' ?>>
                            <label for="is_active" class="form-check-label">
                                Compte actif
                                <small>L'utilisateur peut se connecter et utiliser la plateforme</small>
                            </label>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <div class="form-check">
                            <input type="checkbox" 
                                   id="email_verified" 
                                   name="email_verified" 
                                   class="form-check-input"
                                   value="1"
                                   <?= ($user['email_verified'] ?? 0) ? 'checked' : '' ?>>
                            <label for="email_verified" class="form-check-label">
                                Email vérifié
                                <small>L'utilisateur a vérifié son adresse email</small>
                            </label>
                        </div>
                    </div>
                </div>
                
                <div class="form-section">
                    <h2>Statistiques de l'Utilisateur</h2>
                    
                    <div class="user-stats">
                        <div class="stat-item">
                            <span class="stat-label">Albums créés:</span>
                            <span class="stat-value"><?= $userStats['album_count'] ?? 0 ?></span>
                        </div>
                        
                        <div class="stat-item">
                            <span class="stat-label">Photos uploadées:</span>
                            <span class="stat-value"><?= $userStats['photo_count'] ?? 0 ?></span>
                        </div>
                        
                        <div class="stat-item">
                            <span class="stat-label">Favoris:</span>
                            <span class="stat-value"><?= $userStats['favorite_count'] ?? 0 ?></span>
                        </div>
                        
                        <div class="stat-item">
                            <span class="stat-label">Commentaires:</span>
                            <span class="stat-value"><?= $userStats['comment_count'] ?? 0 ?></span>
                        </div>
                        
                        <div class="stat-item">
                            <span class="stat-label">Dernière connexion:</span>
                            <span class="stat-value"><?= $user['last_login'] ? date('d/m/Y à H:i', strtotime($user['last_login'])) : 'Jamais' ?></span>
                        </div>
                        
                        <div class="stat-item">
                            <span class="stat-label">Date d'inscription:</span>
                            <span class="stat-value"><?= date('d/m/Y', strtotime($user['created_at'])) ?></span>
                        </div>
                    </div>
                </div>
                
                <div class="form-section">
                    <h2>Actions de Sécurité</h2>
                    
                    <div class="security-actions">
                        <div class="action-item">
                            <h4>Réinitialiser le mot de passe</h4>
                            <p>Envoie un email à l'utilisateur avec un lien pour réinitialiser son mot de passe</p>
                            <button type="button" class="btn btn-warning btn-sm" id="reset-password-btn">
                                Réinitialiser le mot de passe
                            </button>
                        </div>
                        
                        <div class="action-item">
                            <h4>Basculer l'utilisateur</h4>
                            <p>Empêche l'utilisateur de se connecter à son compte</p>
                            <button type="button" class="btn btn-outline btn-sm" id="ban-user-btn">
                                <?= ($user['is_active'] ? 'Bannir' : 'Débannir') ?>
                            </button>
                        </div>
                        
                        <div class="action-item danger">
                            <h4>Supprimer le compte</h4>
                            <p>Supprime définitivement le compte et toutes les données associées</p>
                            <button type="button" class="btn btn-danger btn-sm" id="delete-user-btn">
                                Supprimer le compte
                            </button>
                        </div>
                    </div>
                </div>
                
                <div class="form-actions">
                    <a href="/admin/users" class="btn btn-outline">Retour à la liste</a>
                    <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
                </div>
            </form>
        </div>
        
        <div class="sidebar">
            <div class="sidebar-card">
                <h3>Avatar Actuel</h3>
                <div class="avatar-preview">
                    <img src="<?= getUserAvatar($user, 120) ?>" alt="Avatar" class="current-avatar">
                    <button class="btn btn-outline btn-sm" id="change-avatar-btn">Changer l'avatar</button>
                    <input type="file" id="avatar-input" accept="image/*" hidden>
                </div>
            </div>
            
            <div class="sidebar-card">
                <h3>Historique Récent</h3>
                <div class="recent-activity">
                    <?php
                    $recentActivity = getUserRecentActivity($user['id'], 10);
                    foreach ($recentActivity as $activity): ?>
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
                                        $icon = '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18M8 6V4a2 2 0 012-2h4a2 2 0 012 2v2m3 0v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6h14zM10 11v6M14 11v6M10 11v6"/>
                                </svg>';
                                        break;
                                    case 'login':
                                        $icon = '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 16l-4-4m0 0l4 4m-4-4l4 4m4-4v12m-4-8h8m-4 8H3m3 4h14M3 4h14"/>
                                </svg>';
                                        break;
                                    default:
                                        $icon = '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2 2 4m0-6v6l4 2 2 4"/>
                                </svg>';
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
            </div>
            
            <div class="sidebar-card">
                <h3>Actions Rapides</h3>
                <div class="quick-actions">
                    <a href="/users/<?= $user['id'] ?>" class="btn btn-outline btn-block">Voir le profil public</a>
                    <a href="/admin/users/<?= $user['id'] ?>/edit" class="btn btn-primary btn-block">Modifier</a>
                    <a href="/admin/users/<?= $user['id'] ?>/delete" class="btn btn-danger btn-block">Supprimer</a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Password Reset Modal -->
<div id="password-reset-modal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Réinitialiser le mot de passe</h3>
            <button class="modal-close">&times;</button>
        </div>
        <div class="modal-body">
            <p>Êtes-vous sûr de vouloir réinitialiser le mot de passe de cet utilisateur ?</p>
            <p><strong>Un email avec un lien de réinitialisation sera envoyé à <?= htmlspecialchars($user['email']) ?></strong></p>
            <p><strong>Utilisateur : <?= htmlspecialchars($user['username']) ?></strong></p>
        </div>
        <div class="modal-footer">
            <button class="btn btn-outline modal-cancel">Annuler</button>
            <form id="password-reset-form" method="POST">
                <button type="submit" class="btn btn-warning">Réinitialiser</button>
            </form>
        </div>
    </div>
</div>

<!-- User Ban Modal -->
<div id="user-ban-modal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3><?= ($user['is_active'] ? 'Bannir' : 'Débannir') ?> l\'Utilisateur</h3>
            <button class="modal-close">&times;</button>
        </div>
        <div class="modal-body">
            <p>Êtes-vous sûr de vouloir <?= $user['is_active'] ? 'bannir' : 'débannir' ?> cet utilisateur ?</p>
            <p><strong><?= $user['is_active'] ? 'L\'utilisateur ne pourra plus se connecter' : 'L\'utilisateur pourra à nouveau se connecter' ?></strong></p>
            <p><strong>Utilisateur : <?= htmlspecialchars($user['username']) ?></strong></p>
        </div>
        <div class="modal-footer">
            <button class="btn btn-outline modal-cancel">Annuler</button>
            <form id="user-ban-form" method="POST">
                <button type="submit" class="btn btn-<?= $user['is_active'] ? 'warning' : 'success' ?>"><?= ($user['is_active'] ? 'Bannir' : 'Débannir') ?></button>
            </form>
        </div>
    </div>
</div>

<!-- Delete User Modal -->
<div id="delete-user-modal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Supprimer l'Utilisateur</h3>
            <button class="modal-close">&times;</button>
        </div>
        <div class="modal-body">
            <p>⚠️ **ATTENTION : Cette action est irréversible**</p>
            <p>La suppression de cet utilisateur entraînera la suppression définitive de :</p>
            <ul>
                <li>Tous ses albums et photos</li>
                <li>Tous ses commentaires et favoris</li>
                <li>Son historique d'activité</li>
                <li>Son profil et toutes ses données personnelles</li>
            </ul>
            <p><strong>Utilisateur : <?= htmlspecialchars($user['username']) ?></strong></p>
        </div>
        <div class="modal-footer">
            <button class="btn btn-outline modal-cancel">Annuler</button>
            <form id="delete-user-form" method="POST">
                <button type="submit" class="btn btn-danger">Supprimer définitivement</button>
            </form>
        </div>
    </div>
</div>

<?php 
$additionalScripts = '
<script>
document.addEventListener("DOMContentLoaded", function() {
    // Character count for bio
    const bioTextarea = document.getElementById("bio");
    const bioCount = document.getElementById("bio-count");
    
    if (bioTextarea && bioCount) {
        bioTextarea.addEventListener("input", function() {
            bioCount.textContent = this.value.length;
        });
    }
    
    // Avatar change
    const changeAvatarBtn = document.getElementById("change-avatar-btn");
    const avatarInput = document.getElementById("avatar-input");
    const currentAvatar = document.querySelector(".current-avatar");
    
    if (changeAvatarBtn && avatarInput && currentAvatar) {
        changeAvatarBtn.addEventListener("click", () => avatarInput.click());
        
        avatarInput.addEventListener("change", function(e) {
            const file = e.target.files[0];
            if (file && file.type.startsWith("image/")) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    currentAvatar.src = e.target.result;
                    
                    // Auto-submit form to update avatar
                    const formData = new FormData();
                    formData.append("avatar", file);
                    
                    fetch("/admin/update-avatar/' . $user['id'] . ', {
                        method: "POST",
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            showNotification("Avatar mis à jour avec succès", "success");
                        } else {
                            showNotification(data.message || "Erreur lors de la mise à jour de l\'avatar", "error");
                            currentAvatar.src = "' . getUserAvatar($user, 120) . '"; // Reset on error
                        }
                    })
                    .catch(error => {
                        console.error("Error:", error);
                        showNotification("Erreur lors de la mise à jour de l\'avatar", "error");
                    });
                };
                reader.readAsDataURL(file);
            }
        });
    }
    
    // Password reset modal
    const passwordResetBtn = document.getElementById("reset-password-btn");
    const passwordResetModal = document.getElementById("password-reset-modal");
    const passwordResetForm = document.getElementById("password-reset-form");
    
    if (passwordResetBtn) {
        passwordResetBtn.addEventListener("click", function() {
            passwordResetModal.style.display = "block";
        });
    }
    
    // User ban modal
    const banUserBtn = document.getElementById("ban-user-btn");
    const userBanModal = document.getElementById("user-ban-modal");
    const userBanForm = document.getElementById("user-ban-form");
    
    if (banUserBtn) {
        banUserBtn.addEventListener("click", function() {
            userBanModal.style.display = "block";
        });
    }
    
    // Delete user modal
    const deleteUserBtn = document.getElementById("delete-user-btn");
    const deleteUserModal = document.getElementById("delete-user-modal");
    const deleteUserForm = document.getElementById("delete-user-form");
    
    if (deleteUserBtn) {
        deleteUserBtn.addEventListener("click", function() {
            deleteUserModal.style.display = "block";
        });
    }
    
    // Modal close handlers
    const modalClose = document.querySelectorAll(".modal-close");
    const modalCancel = document.querySelectorAll(".modal-cancel");
    
    modalClose.forEach(btn => {
        btn.addEventListener("click", function() {
            this.closest(".modal").style.display = "none";
        });
    });
    
    modalCancel.forEach(btn => {
        btn.addEventListener("click", function() {
            this.closest(".modal").style.display = "none";
        });
    });
    
    window.addEventListener("click", function(event) {
        if (event.target.classList.contains("modal")) {
            event.target.style.display = "none";
        }
    });
    
    // Form submissions
    if (passwordResetForm) {
        passwordResetForm.addEventListener("submit", function(e) {
            e.preventDefault();
            this.submit();
        });
    }
    
    if (userBanForm) {
        userBanForm.addEventListener("submit", function(e) {
            e.preventDefault();
            this.submit();
        });
    }
    
    if (deleteUserForm) {
        deleteUserForm.addEventListener("submit", function(e) {
            e.preventDefault();
            this.submit();
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

function getActivityDescription(activity) {
    const actions = {
        "create": "a créé",
        "upload": "a uploadé",
        "update": "a modifié",
        "edit": "a modifié",
        "delete": "a supprimé",
        "login": "s\'est connecté",
        "register": "s\'est inscrit",
        "logout": "s\'est déconnecté",
        "comment": "a commenté",
        "favoris": "a ajouté aux favoris",
        "unfavoris": "a retiré des favoris",
        "approve": "a approuvé",
        "unapprove": "a désapprouvé",
        "view": "a visualisé"
    };
    
    const resources = {
        "photo": "une photo",
        "album": "un album",
        "user": "un utilisateur",
        "comment": "un commentaire",
        "tag": "un tag",
        "favorite": "un favori"
    };
    
    return `${actions[activity['action'] || "a effectué une action sur"} ${resources[activity['resource_type'] || "une ressource"}`;
}
</script>

<style>
.avatar-preview {
    text-align: center;
    margin-bottom: 1.5rem;
}

.current-avatar {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    object-fit: cover;
    margin-bottom: 1rem;
}

.change-avatar-btn {
    display: block;
    width: 100%;
}

.user-stats {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
}

.stat-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.75rem 0;
    border-bottom: 1px solid #eee;
}

.stat-item:last-child {
    border-bottom: none;
}

.stat-label {
    font-weight: 500;
    color: #666;
}

.stat-value {
    font-weight: 600;
    color: #333;
}

.security-actions {
    background: #fff;
    border-radius: 12px;
    padding: 1.5rem;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    margin-bottom: 2rem;
}

.action-item {
    border-bottom: 1px solid #eee;
    padding: 1rem 0;
}

.action-item:last-child {
    border-bottom: none;
}

.action-item h4 {
    margin-bottom: 0.5rem;
    color: #333;
}

.action-item p {
    margin-bottom: 1rem;
    color: #666;
    line-height: 1.6;
}

.action-item ul {
    margin: 0;
    padding-left: 1.5rem;
    color: #dc3545;
}

.action-item ul li {
    margin-bottom: 0.25rem;
}

.action-item.danger {
    border-left: 4px solid #dc3545;
}

.recent-activity {
    max-height: 400px;
    overflow-y: auto;
}

.activity-item {
    display: flex;
    gap: 1rem;
    padding: 1rem 0;
    border-bottom: 1px solid #eee;
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

.quick-actions {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

@media (max-width: 768px) {
    .content-wrapper {
        grid-template-columns: 1fr;
    }
    
    .form-row {
        grid-template-columns: 1fr;
    }
    
    .security-actions {
        margin-bottom: 2rem;
    }
    
    .action-item ul {
        padding-left: 1rem;
    }
}
</style>';
require_once __DIR__ . '/../partials/footer.php'; 

// Helper functions
function getUserRecentActivity($userId, $limit = 10) {
    // This would query the database for user activity
    return [];
}
?>
