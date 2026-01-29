<?php 
$pageTitle = 'Créer un Album';
require_once __DIR__ . '/../partials/header.php'; 
?>

<div class="page-header">
    <div class="container">
        <h1 class="page-title">Créer un Nouvel Album</h1>
        <p class="page-description">Organisez vos photos dans un magnifique album</p>
    </div>
</div>

<div class="container">
    <div class="content-wrapper">
        <div class="form-container">
            <form action="/albums/store" method="POST" class="album-form">
                <div class="form-section">
                    <h2>Informations de l'Album</h2>
                    
                    <div class="form-group">
                        <label for="title" class="form-label">Titre de l'album *</label>
                        <input type="text" 
                               id="title" 
                               name="title" 
                               class="form-control" 
                               value="<?= htmlspecialchars($data['title'] ?? '') ?>"
                               required
                               maxlength="100"
                               placeholder="Donnez un nom à votre album">
                        <?php if (isset($errors['title'])): ?>
                            <div class="form-error">
                                <?php foreach ($errors['title'] as $error): ?>
                                    <span><?= htmlspecialchars($error) ?></span>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="form-group">
                        <label for="description" class="form-label">Description</label>
                        <textarea id="description" 
                                  name="description" 
                                  class="form-control" 
                                  rows="4"
                                  maxlength="1000"
                                  placeholder="Décrivez votre album..."><?= htmlspecialchars($data['description'] ?? '') ?></textarea>
                        <?php if (isset($errors['description'])): ?>
                            <div class="form-error">
                                <?php foreach ($errors['description'] as $error): ?>
                                    <span><?= htmlspecialchars($error) ?></span>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                        <div class="form-help">
                            <span id="description-count">0</span>/1000 caractères
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <div class="form-check">
                            <input type="checkbox" 
                                   id="is_public" 
                                   name="is_public" 
                                   class="form-check-input"
                                   value="1"
                                   <?= isset($data['is_public']) ? 'checked' : '' ?>>
                            <label for="is_public" class="form-check-label">
                                Album public
                                <small>Tout le monde pourra voir cet album</small>
                            </label>
                        </div>
                    </div>
                </div>
                
                <div class="form-section">
                    <h2>Paramètres Avancés</h2>
                    
                    <div class="form-group">
                        <label class="form-label">Options de partage</label>
                        <div class="form-options">
                            <div class="form-check">
                                <input type="checkbox" id="allow_comments" name="allow_comments" class="form-check-input" value="1" checked>
                                <label for="allow_comments" class="form-check-label">Autoriser les commentaires</label>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" id="allow_favorites" name="allow_favorites" class="form-check-input" value="1" checked>
                                <label for="allow_favorites" class="form-check-label">Autoriser les favoris</label>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" id="show_exif" name="show_exif" class="form-check-input" value="1">
                                <label for="show_exif" class="form-check-label">Afficher les informations EXIF</label>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="form-actions">
                    <a href="/albums" class="btn btn-outline">Annuler</a>
                    <button type="submit" class="btn btn-primary">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M12 5v14M5 12h14"/>
                        </svg>
                        Créer l'Album
                    </button>
                </div>
            </form>
        </div>
        
        <div class="sidebar">
            <div class="sidebar-card">
                <h3>Conseils</h3>
                <ul class="tips-list">
                    <li>Choisissez un titre descriptif pour votre album</li>
                    <li>Ajoutez une description pour donner du contexte</li>
                    <li>Les albums publics sont visibles par tous les utilisateurs</li>
                    <li>Vous pourrez modifier ces paramètres plus tard</li>
                </ul>
            </div>
            
            <div class="sidebar-card">
                <h3>Prochaines Étapes</h3>
                <ol class="steps-list">
                    <li>Créez votre album</li>
                    <li>Ajoutez des photos</li>
                    <li>Organisez-les avec des tags</li>
                    <li>Partagez votre album</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<?php 
$additionalScripts = '
<script>
document.addEventListener("DOMContentLoaded", function() {
    const descriptionTextarea = document.getElementById("description");
    const countSpan = document.getElementById("description-count");
    
    descriptionTextarea.addEventListener("input", function() {
        countSpan.textContent = this.value.length;
    });
    
    // Initialize count
    countSpan.textContent = descriptionTextarea.value.length;
});
</script>';
require_once __DIR__ . '/../partials/footer.php'; 
?>
