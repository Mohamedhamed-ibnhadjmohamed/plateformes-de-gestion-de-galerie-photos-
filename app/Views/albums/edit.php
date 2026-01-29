<?php 
$pageTitle = 'Modifier l\'Album';
require_once __DIR__ . '/../partials/header.php'; 
?>

<div class="page-header">
    <div class="container">
        <h1 class="page-title">Modifier l'Album</h1>
        <p class="page-description">Mettez à jour les informations de votre album</p>
    </div>
</div>

<div class="container">
    <div class="content-wrapper">
        <div class="form-container">
            <form action="/albums/<?= $album['id'] ?>/update" method="POST" class="album-form">
                <div class="form-section">
                    <h2>Informations de l'Album</h2>
                    
                    <div class="form-group">
                        <label for="title" class="form-label">Titre de l'album *</label>
                        <input type="text" 
                               id="title" 
                               name="title" 
                               class="form-control" 
                               value="<?= htmlspecialchars($album['title']) ?>"
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
                                  placeholder="Décrivez votre album..."><?= htmlspecialchars($album['description'] ?? '') ?></textarea>
                        <?php if (isset($errors['description'])): ?>
                            <div class="form-error">
                                <?php foreach ($errors['description'] as $error): ?>
                                    <span><?= htmlspecialchars($error) ?></span>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                        <div class="form-help">
                            <span id="description-count"><?= strlen($album['description'] ?? '') ?></span>/1000 caractères
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <div class="form-check">
                            <input type="checkbox" 
                                   id="is_public" 
                                   name="is_public" 
                                   class="form-check-input"
                                   value="1"
                                   <?= $album['is_public'] ? 'checked' : '' ?>>
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
                
                <div class="form-section">
                    <h2>Actions</h2>
                    <div class="danger-zone">
                        <h3>Zone de Danger</h3>
                        <p>Attention : ces actions sont irréversibles.</p>
                        <button type="button" class="btn btn-danger" id="delete-album-btn">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M3 6h18M8 6V4a2 2 0 012-2h4a2 2 0 012 2v2m3 0v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6h14zM10 11v6M14 11v6"/>
                            </svg>
                            Supprimer l'Album
                        </button>
                    </div>
                </div>
                
                <div class="form-actions">
                    <a href="/albums/<?= $album['id'] ?>" class="btn btn-outline">Annuler</a>
                    <button type="submit" class="btn btn-primary">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/>
                            <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>
                        </svg>
                        Enregistrer les Modifications
                    </button>
                </div>
            </form>
        </div>
        
        <div class="sidebar">
            <div class="sidebar-card">
                <h3>Statistiques de l'Album</h3>
                <div class="album-stats">
                    <div class="stat-item">
                        <span class="stat-label">Photos</span>
                        <span class="stat-value"><?= $album['photo_count'] ?? 0 ?></span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-label">Vues</span>
                        <span class="stat-value"><?= $album['views_count'] ?? 0 ?></span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-label">Créé le</span>
                        <span class="stat-value"><?= date('d/m/Y', strtotime($album['created_at'])) ?></span>
                    </div>
                </div>
            </div>
            
            <div class="sidebar-card">
                <h3>Actions Rapides</h3>
                <div class="quick-actions">
                    <a href="/albums/<?= $album['id'] ?>" class="btn btn-outline btn-block">Voir l'Album</a>
                    <a href="/photos/upload?album=<?= $album['id'] ?>" class="btn btn-primary btn-block">Ajouter des Photos</a>
                </div>
            </div>
        </div>
    </div>
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
            <p><strong>Cette action est irréversible et supprimera également toutes les photos contenues dans cet album.</strong></p>
        </div>
        <div class="modal-footer">
            <button class="btn btn-outline modal-cancel">Annuler</button>
            <form action="/albums/<?= $album['id'] ?>/delete" method="POST" style="display: inline;">
                <button type="submit" class="btn btn-danger">Supprimer</button>
            </form>
        </div>
    </div>
</div>

<?php 
$additionalScripts = '
<script>
document.addEventListener("DOMContentLoaded", function() {
    const descriptionTextarea = document.getElementById("description");
    const countSpan = document.getElementById("description-count");
    const deleteBtn = document.getElementById("delete-album-btn");
    const deleteModal = document.getElementById("delete-modal");
    const modalClose = document.querySelector(".modal-close");
    const modalCancel = document.querySelector(".modal-cancel");
    
    descriptionTextarea.addEventListener("input", function() {
        countSpan.textContent = this.value.length;
    });
    
    deleteBtn.addEventListener("click", function() {
        deleteModal.style.display = "block";
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
});
</script>';
require_once __DIR__ . '/../partials/footer.php'; 
?>
