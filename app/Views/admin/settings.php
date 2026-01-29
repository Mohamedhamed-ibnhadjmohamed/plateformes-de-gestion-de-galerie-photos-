<?php 
$pageTitle = 'Paramètres de l\'Application';
require_once __DIR__ . '/../partials/header.php'; 
?>

<div class="page-header">
    <div class="container">
        <h1 class="page-title">Paramètres de l'Application</h1>
        <p class="page-description">Configurez les paramètres généraux de la plateforme</p>
    </div>
</div>

<div class="container">
    <div class="settings-container">
        <div class="settings-sidebar">
            <nav class="settings-nav">
                <a href="#general" class="nav-link active">Général</a>
                <a href="#upload" class="nav-link">Upload</a>
                <a href="#security" class="nav-link">Sécurité</a>
                <a href="#email" class="nav-link">Email</a>
                <a href="#appearance" class="nav-link">Apparence</a>
                <a href="#maintenance" class="nav-link">Maintenance</a>
            </nav>
        </div>
        
        <div class="settings-content">
            <form action="/admin/settings" method="POST" class="settings-form">
                <!-- General Settings -->
                <section id="general" class="settings-section active">
                    <h2>Paramètres Généraux</h2>
                    
                    <div class="form-group">
                        <label for="site_name" class="form-label">Nom du site</label>
                        <input type="text" 
                               id="site_name" 
                               name="site_name" 
                               class="form-control" 
                               value="<?= htmlspecialchars($config['site_name'] ?? 'Photo Gallery') ?>"
                               required>
                        <div class="form-help">Le nom qui apparaît dans l'en-tête du site</div>
                    </div>
                    
                    <div class="form-group">
                        <label for="site_description" class="form-label">Description du site</label>
                        <textarea id="site_description" 
                                  name="site_description" 
                                  class="form-control" 
                                  rows="3"
                                  placeholder="Description du site pour les moteurs de recherche"><?= htmlspecialchars($config['site_description'] ?? '') ?></textarea>
                        <div class="form-help">Description utilisée pour le SEO</div>
                    </div>
                    
                    <div class="form-group">
                        <label for="admin_email" class="form-label">Email administrateur</label>
                        <input type="email" 
                               id="admin_email" 
                               name="admin_email" 
                               class="form-control" 
                               value="<?= htmlspecialchars($config['admin_email'] ?? 'admin@example.com') ?>"
                               required>
                        <div class="form-help">Email utilisé pour les notifications système</div>
                    </div>
                    
                    <div class="form-group">
                        <label for="items_per_page" class="form-label">Éléments par page</label>
                        <input type="number" 
                               id="items_per_page" 
                               name="items_per_page" 
                               class="form-control" 
                               value="<?= $config['items_per_page'] ?? 12 ?>"
                               min="5"
                               max="100"
                               required>
                        <div class="form-help">Nombre d'éléments affichés par page (albums, photos, etc.)</div>
                    </div>
                    
                    <div class="form-group">
                        <div class="form-check">
                            <input type="checkbox" 
                                   id="debug" 
                                   name="debug" 
                                   class="form-check-input"
                                   value="1"
                                   <?= ($config['debug'] ?? false) ? 'checked' : '' ?>>
                            <label for="debug" class="form-check-label">
                                Mode debug
                                <small>Affiche les erreurs détaillées et les messages de débogage</small>
                            </label>
                        </div>
                    </div>
                </section>
                
                <!-- Upload Settings -->
                <section id="upload" class="settings-section">
                    <h2>Paramètres d'Upload</h2>
                    
                    <div class="form-group">
                        <label for="max_file_size" class="form-label">Taille maximale des fichiers (MB)</label>
                        <input type="number" 
                               id="max_file_size" 
                               name="max_file_size" 
                               class="form-control" 
                               value="<?= $config['max_file_size'] ?? 10 ?>"
                               min="1"
                               max="100"
                               required>
                        <div class="form-help">Taille maximale autorisée pour les fichiers uploadés</div>
                    </div>
                    
                    <div class="form-group">
                        <label for="allowed_extensions" class="form-label">Extensions autorisées</label>
                        <input type="text" 
                               id="allowed_extensions" 
                               name="allowed_extensions" 
                               class="form-control" 
                               value="<?= htmlspecialchars($config['allowed_extensions'] ?? 'jpg,jpeg,png,gif,webp') ?>"
                               required>
                        <div class="form-help">Extensions de fichiers autorisées, séparées par des virgules</div>
                    </div>
                    
                    <div class="form-group">
                        <label for="thumb_width" class="form-label">Largeur des miniatures (px)</label>
                        <input type="number" 
                               id="thumb_width" 
                               name="thumb_width" 
                               class="form-control" 
                               value="<?= $config['thumb_width'] ?? 300 ?>"
                               min="100"
                               max="1000"
                               required>
                        <div class="form-help">Largeur des miniatures générées automatiquement</div>
                    </div>
                    
                    <div class="form-group">
                        <label for="thumb_height" class="form-label">Hauteur des miniatures (px)</label>
                        <input type="number" 
                               id="thumb_height" 
                               name="thumb_height" 
                               class="form-control" 
                               value="<?= $config['thumb_height'] ?? 200 ?>"
                               min="100"
                               max="1000"
                               required>
                        <div class="form-help">Hauteur des miniatures générées automatiquement</div>
                    </div>
                    
                    <div class="form-group">
                        <label for="thumb_quality" class="form-label">Qualité des miniatures (%)</label>
                        <input type="number" 
                               id="thumb_quality" 
                               name="thumb_quality" 
                               class="form-control" 
                               value="<?= $config['thumb_quality'] ?? 85 ?>"
                               min="10"
                               max="100"
                               required>
                        <div class="form-help">Qualité de compression des miniatures (10-100)</div>
                    </div>
                </section>
                
                <!-- Security Settings -->
                <section id="security" class="settings-section">
                    <h2>Paramètres de Sécurité</h2>
                    
                    <div class="form-group">
                        <label for="session_timeout" class="form-label">Durée de session (minutes)</label>
                        <input type="number" 
                               id="session_timeout" 
                               name="session_timeout" 
                               class="form-control" 
                               value="<?= $config['session_timeout'] ?? 1440 ?>"
                               min="15"
                               max="10080"
                               required>
                        <div class="form-help">Durée avant expiration de la session utilisateur</div>
                    </div>
                    
                    <div class="form-group">
                        <label for="max_login_attempts" class="form-label">Tentatives de connexion max</label>
                        <input type="number" 
                               id="max_login_attempts" 
                               name="max_login_attempts" 
                               class="form-control" 
                               value="<?= $config['max_login_attempts'] ?? 5 ?>"
                               min="3"
                               max="20"
                               required>
                        <div class="form-help">Nombre maximum de tentatives avant blocage</div>
                    </div>
                    
                    <div class="form-group">
                        <label for="lockout_duration" class="form-label">Durée de blocage (minutes)</label>
                        <input type="number" 
                               id="lockout_duration" 
                               name="lockout_duration" 
                               class="form-control" 
                               value="<?= $config['lockout_duration'] ?? 30 ?>"
                               min="5"
                               max="1440"
                               required>
                        <div class="form-help">Durée de blocage après tentatives échouées</div>
                    </div>
                    
                    <div class="form-group">
                        <div class="form-check">
                            <input type="checkbox" 
                                   id="require_email_verification" 
                                   name="require_email_verification" 
                                   class="form-check-input"
                                   value="1"
                                   <?= ($config['require_email_verification'] ?? false) ? 'checked' : '' ?>>
                            <label for="require_email_verification" class="form-check-label">
                                Vérification email requise
                                <small>Les utilisateurs doivent vérifier leur email avant de pouvoir se connecter</small>
                            </label>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <div class="form-check">
                            <input type="checkbox" 
                                   id="enable_captcha" 
                                   name="enable_captcha" 
                                   class="form-check-input"
                                   value="1"
                                   <?= ($config['enable_captcha'] ?? false) ? 'checked' : '' ?>>
                            <label for="enable_captcha" class="form-check-label">
                                Activer CAPTCHA
                                <small>Ajoute un CAPTCHA pour les formulaires publics</small>
                            </label>
                        </div>
                    </div>
                </section>
                
                <!-- Email Settings -->
                <section id="email" class="settings-section">
                    <h2>Paramètres Email</h2>
                    
                    <div class="form-group">
                        <label for="smtp_host" class="form-label">Serveur SMTP</label>
                        <input type="text" 
                               id="smtp_host" 
                               name="smtp_host" 
                               class="form-control" 
                               value="<?= htmlspecialchars($config['smtp_host'] ?? 'localhost') ?>"
                               placeholder="smtp.example.com">
                        <div class="form-help">Serveur SMTP pour l'envoi d'emails</div>
                    </div>
                    
                    <div class="form-group">
                        <label for="smtp_port" class="form-label">Port SMTP</label>
                        <input type="number" 
                               id="smtp_port" 
                               name="smtp_port" 
                               class="form-control" 
                               value="<?= $config['smtp_port'] ?? 587 ?>"
                               placeholder="587">
                        <div class="form-help">Port du serveur SMTP (généralement 587 pour TLS, 465 pour SSL)</div>
                    </div>
                    
                    <div class="form-group">
                        <label for="smtp_username" class="form-label">Nom d'utilisateur SMTP</label>
                        <input type="text" 
                               id="smtp_username" 
                               name="smtp_username" 
                               class="form-control" 
                               value="<?= htmlspecialchars($config['smtp_username'] ?? '') ?>"
                               placeholder="email@example.com">
                        <div class="form-help">Nom d'utilisateur pour l'authentification SMTP</div>
                    </div>
                    
                    <div class="form-group">
                        <label for="smtp_password" class="form-label">Mot de passe SMTP</label>
                        <input type="password" 
                               id="smtp_password" 
                               name="smtp_password" 
                               class="form-control" 
                               value="<?= htmlspecialchars($config['smtp_password'] ?? '') ?>"
                               placeholder="••••••••••">
                        <div class="form-help">Mot de passe pour l'authentification SMTP</div>
                    </div>
                    
                    <div class="form-group">
                        <label for="smtp_encryption" class="form-label">Chiffrement SMTP</label>
                        <select id="smtp_encryption" name="smtp_encryption" class="form-control">
                            <option value="tls" <?= ($config['smtp_encryption'] ?? 'tls') === 'tls' ? 'selected' : '' ?>>TLS</option>
                            <option value="ssl" <?= ($config['smtp_encryption'] ?? 'tls') === 'ssl' ? 'selected' : '' ?>>SSL</option>
                            <option value="none" <?= ($config['smtp_encryption'] ?? 'tls') === 'none' ? 'selected' : '' ?>>Aucun</option>
                        </select>
                        <div class="form-help">Type de chiffrement utilisé pour la connexion SMTP</div>
                    </div>
                    
                    <div class="form-group">
                        <label for="from_email" class="form-label">Email d'envoi</label>
                        <input type="email" 
                               id="from_email" 
                               name="from_email" 
                               class="form-control" 
                               value="<?= htmlspecialchars($config['from_email'] ?? 'noreply@example.com') ?>"
                               required>
                        <div class="form-help">Email utilisé comme expéditeur pour les emails système</div>
                    </div>
                    
                    <div class="form-group">
                        <label for="from_name" class="form-label">Nom d'envoi</label>
                        <input type="text" 
                               id="from_name" 
                               name="from_name" 
                               class="form-control" 
                               value="<?= htmlspecialchars($config['from_name'] ?? 'Photo Gallery') ?>"
                               required>
                        <div class="form-help">Nom utilisé comme expéditeur pour les emails système</div>
                    </div>
                </section>
                
                <!-- Appearance Settings -->
                <section id="appearance" class="settings-section">
                    <h2>Apparence</h2>
                    
                    <div class="form-group">
                        <label for="theme" class="form-label">Thème</label>
                        <select id="theme" name="theme" class="form-control">
                            <option value="light" <?= ($config['theme'] ?? 'light') === 'light' ? 'selected' : '' ?>>Clair</option>
                            <option value="dark" <?= ($config['theme'] ?? 'light') === 'dark' ? 'selected' : '' ?>>Sombre</option>
                            <option value="auto" <?= ($config['theme'] ?? 'light') === 'auto' ? 'selected' : '' ?>>Automatique</option>
                        </select>
                        <div class="form-help">Thème de l'interface utilisateur</div>
                    </div>
                    
                    <div class="form-group">
                        <label for="primary_color" class="form-label">Couleur principale</label>
                        <input type="color" 
                               id="primary_color" 
                               name="primary_color" 
                               class="form-control" 
                               value="<?= $config['primary_color'] ?? '#007bff' ?>">
                        <div class="form-help">Couleur principale utilisée dans l'interface</div>
                    </div>
                    
                    <div class="form-group">
                        <label for="logo_path" class="form-label">Chemin du logo</label>
                        <input type="text" 
                               id="logo_path" 
                               name="logo_path" 
                               class="form-control" 
                               value="<?= htmlspecialchars($config['logo_path'] ?? '/assets/images/logo.png') ?>"
                               placeholder="/assets/images/logo.png">
                        <div class="form-help">Chemin vers le fichier logo du site</div>
                    </div>
                </section>
                
                <!-- Maintenance Settings -->
                <section id="maintenance" class="settings-section">
                    <h2>Maintenance</h2>
                    
                    <div class="form-group">
                        <label for="cleanup_days" class="form-label">Nettoyer les logs après (jours)</label>
                        <input type="number" 
                               id="cleanup_days" 
                               name="cleanup_days" 
                               class="form-control" 
                               value="<?= $config['cleanup_days'] ?? 90 ?>"
                               min="7"
                               max="365"
                               required>
                        <div class="form-help">Supprimer automatiquement les logs plus anciens que ce nombre de jours</div>
                    </div>
                    
                    <div class="form-group">
                        <div class="form-check">
                            <input type="checkbox" 
                                   id="enable_maintenance_mode" 
                                   name="enable_maintenance_mode" 
                                   class="form-check-input"
                                   value="1"
                                   <?= ($config['enable_maintenance_mode'] ?? false) ? 'checked' : '' ?>>
                            <label for="enable_maintenance_mode" class="form-check-label">
                                Mode maintenance
                                <small>Affiche une page de maintenance aux utilisateurs non-administrateurs</small>
                            </label>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="maintenance_message" class="form-label">Message de maintenance</label>
                        <textarea id="maintenance_message" 
                                  name="maintenance_message" 
                                  class="form-control" 
                                  rows="3"
                                  placeholder="Le site est actuellement en maintenance..."><?= htmlspecialchars($config['maintenance_message'] ?? 'Le site est actuellement en maintenance. Nous serons de retour très prochainement.') ?></textarea>
                        <div class="form-help">Message affiché aux utilisateurs pendant la maintenance</div>
                    </div>
                    
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">Enregistrer les paramètres</button>
                        <button type="button" class="btn btn-outline" id="test-email">Tester l'envoi d'email</button>
                        <button type="button" class="btn btn-outline" id="cleanup-logs">Nettoyer les logs</button>
                    </div>
                </section>
            </form>
        </div>
    </div>
</div>

<?php 
$additionalScripts = '
<script>
document.addEventListener("DOMContentLoaded", function() {
    // Navigation
    const navLinks = document.querySelectorAll(".settings-nav a");
    const sections = document.querySelectorAll(".settings-section");
    
    navLinks.forEach(link => {
        link.addEventListener("click", function(e) {
            e.preventDefault();
            const targetId = this.getAttribute("href").substring(1);
            
            // Hide all sections
            sections.forEach(section => section.classList.remove("active"));
            
            // Show target section
            const targetSection = document.getElementById(targetId);
            if (targetSection) {
                targetSection.classList.add("active");
            }
            
            // Update active nav link
            navLinks.forEach(navLink => navLink.classList.remove("active"));
            this.classList.add("active");
        });
    });
    
    // Test email functionality
    const testEmailBtn = document.getElementById("test-email");
    if (testEmailBtn) {
        testEmailBtn.addEventListener("click", function() {
            fetch("/admin/test-email", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-Requested-With": "XMLHttpRequest"
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification("Email de test envoyé avec succès", "success");
                } else {
                    showNotification(data.message || "Erreur lors de l\'envoi de l\'email de test", "error");
                }
            })
            .catch(error => {
                console.error("Error:", error);
                showNotification("Erreur lors de l\'envoi de l\'email de test", "error");
            });
        });
    }
    
    // Cleanup logs functionality
    const cleanupLogsBtn = document.getElementById("cleanup-logs");
    if (cleanupLogsBtn) {
        cleanupLogsBtn.addEventListener("click", function() {
            if (confirm("Êtes-vous sûr de vouloir nettoyer les anciens logs ?")) {
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
    
    // Form validation
    const form = document.querySelector(".settings-form");
    form.addEventListener("submit", function(e) {
        // Basic validation
        const requiredFields = form.querySelectorAll("[required]");
        let isValid = true;
        
        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                isValid = false;
                field.classList.add("error");
            } else {
                field.classList.remove("error");
            }
        });
        
        if (!isValid) {
            e.preventDefault();
            showNotification("Veuillez remplir tous les champs obligatoires", "error");
        }
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
.settings-container {
    display: grid;
    grid-template-columns: 250px 1fr;
    gap: 2rem;
}

.settings-sidebar {
    background: #fff;
    border-radius: 12px;
    padding: 1.5rem;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    height: fit-content;
    position: sticky;
    top: 2rem;
}

.settings-nav {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.nav-link {
    display: block;
    padding: 0.75rem 1rem;
    color: #666;
    text-decoration: none;
    border-radius: 6px;
    transition: all 0.3s ease;
}

.nav-link:hover,
.nav-link.active {
    background: #f8f9fa;
    color: #007bff;
}

.settings-content {
    background: #fff;
    border-radius: 12px;
    padding: 2rem;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
}

.settings-section {
    display: none;
}

.settings-section.active {
    display: block;
}

.settings-section h2 {
    margin-bottom: 2rem;
    color: #333;
    border-bottom: 2px solid #eee;
    padding-bottom: 0.5rem;
}

.form-group {
    margin-bottom: 1.5rem;
}

.form-label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: 500;
    color: #333;
}

.form-control {
    width: 100%;
    padding: 0.75rem;
    border: 1px solid #ddd;
    border-radius: 6px;
    font-size: 1rem;
    transition: border-color 0.3s ease;
}

.form-control:focus {
    outline: none;
    border-color: #007bff;
    box-shadow: 0 0 0 3px rgba(0,123,255,0.1);
}

.form-help {
    font-size: 0.875rem;
    color: #666;
    margin-top: 0.25rem;
}

.form-check {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.form-check-input {
    width: auto;
}

.form-check-label {
    color: #333;
    cursor: pointer;
}

.form-check-label small {
    display: block;
    color: #666;
    font-size: 0.875rem;
    margin-top: 0.25rem;
}

.form-actions {
    display: flex;
    gap: 1rem;
    margin-top: 2rem;
    padding-top: 2rem;
    border-top: 1px solid #eee;
}

@media (max-width: 768px) {
    .settings-container {
        grid-template-columns: 1fr;
    }
    
    .settings-sidebar {
        position: static;
        margin-bottom: 2rem;
    }
    
    .settings-nav {
        flex-direction: row;
        flex-wrap: wrap;
        gap: 0.25rem;
    }
    
    .nav-link {
        padding: 0.5rem 1rem;
        font-size: 0.875rem;
    }
    
    .form-actions {
        flex-direction: column;
    }
}
</style>';
require_once __DIR__ . '/../partials/footer.php'; 
?>
