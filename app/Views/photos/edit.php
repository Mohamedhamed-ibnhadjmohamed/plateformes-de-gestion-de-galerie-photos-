<?php 
$pageTitle = 'Modifier la Photo';
require_once __DIR__ . '/../partials/header.php'; 
?>

<div class="page-header">
    <div class="container">
        <h1 class="page-title">Modifier la Photo</h1>
        <p class="page-description">Modifiez les informations de votre photo</p>
    </div>
</div>

<div class="container">
    <div class="content-wrapper">
        <div class="form-container">
            <form action="/photos/update/<?= $photo['id'] ?>" method="POST" class="photo-edit-form" enctype="multipart/form-data">
                <div class="form-section">
                    <h2>Aperçu de la Photo</h2>
                    <div class="photo-preview">
                        <img src="/uploads/albums/<?= htmlspecialchars($photo['filename']) ?>" 
                             alt="<?= htmlspecialchars($photo['title'] ?? 'Photo') ?>"
                             class="preview-image">
                        <div class="photo-info">
                            <p><strong>Fichier:</strong> <?= htmlspecialchars($photo['filename']) ?></p>
                            <p><strong>Taille:</strong> <?= formatFileSize($photo['file_size']) ?></p>
                            <p><strong>Dimensions:</strong> <?= $photo['width'] ?> × <?= $photo['height'] ?> px</p>
                            <p><strong>Uploadé le:</strong> <?= date('d/m/Y à H:i', strtotime($photo['created_at'])) ?></p>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="replace_photo" class="form-label">Remplacer la photo (optionnel)</label>
                        <div class="file-upload">
                            <input type="file" 
                                   id="replace_photo" 
                                   name="photo" 
                                   accept="image/*"
                                   class="file-input">
                            <label for="replace_photo" class="file-label">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4M17 8l-5-5-5 5M12 3v12"/>
                                </svg>
                                Choisir une nouvelle photo
                            </label>
                            <span class="file-name">Aucun fichier sélectionné</span>
                        </div>
                        <div class="form-help">
                            Formats acceptés : JPG, PNG, GIF, WebP (max 10MB)
                        </div>
                    </div>
                </div>
                
                <div class="form-section">
                    <h2>Informations de la Photo</h2>
                    
                    <div class="form-group">
                        <label for="title" class="form-label">Titre</label>
                        <input type="text" 
                               id="title" 
                               name="title" 
                               class="form-control" 
                               value="<?= htmlspecialchars($photo['title'] ?? '') ?>"
                               maxlength="100"
                               placeholder="Donnez un titre à votre photo">
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
                                  placeholder="Décrivez votre photo..."><?= htmlspecialchars($photo['description'] ?? '') ?></textarea>
                        <div class="form-help">
                            <span id="description-count"><?= strlen($photo['description'] ?? '') ?></span>/1000 caractères
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="album_id" class="form-label">Album *</label>
                        <select id="album_id" name="album_id" class="form-control" required>
                            <?php foreach ($albums as $album): ?>
                                <option value="<?= $album['id'] ?>" <?= $album['id'] == $photo['album_id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($album['title']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <div class="form-help">
                            <a href="/albums/create">Créer un nouvel album</a>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <div class="form-check">
                            <input type="checkbox" 
                                   id="is_public" 
                                   name="is_public" 
                                   class="form-check-input"
                                   value="1"
                                   <?= $photo['is_public'] ? 'checked' : '' ?>>
                            <label for="is_public" class="form-check-label">
                                Photo publique
                                <small>Tout le monde pourra voir cette photo</small>
                            </label>
                        </div>
                    </div>
                </div>
                
                <div class="form-section">
                    <h2>Tags</h2>
                    
                    <div class="form-group">
                        <label for="tags" class="form-label">Tags</label>
                        <input type="text" 
                               id="tags" 
                               name="tags" 
                               class="form-control" 
                               value="<?= htmlspecialchars($photo['tags'] ?? '') ?>"
                               placeholder="Ajoutez des tags (séparés par des virgules)">
                        <div class="form-help">
                            Ex: nature, paysage, portrait, noir et blanc
                        </div>
                        <div class="current-tags" id="current-tags">
                            <?php
                            $currentTags = getPhotoTags($photo['id']);
                            foreach ($currentTags as $tag): ?>
                                <span class="tag-badge">
                                    <?= htmlspecialchars($tag['name']) ?>
                                    <button type="button" class="tag-remove" data-tag="<?= htmlspecialchars($tag['name']) ?>">
                                        &times;
                                    </button>
                                </span>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
                
                <div class="form-actions">
                    <a href="/photos/<?= $photo['id'] ?>" class="btn btn-outline">Annuler</a>
                    <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
                </div>
            </form>
        </div>
        
        <div class="sidebar">
            <div class="sidebar-card">
                <h3>Conseils d'Optimisation</h3>
                <ul class="tips-list">
                    <li>Utilisez un titre descriptif pour une meilleure découvrabilité</li>
                    <li>Ajoutez des tags pertinents pour aider les autres à trouver votre photo</li>
                    <li>Une bonne description aide à comprendre le contexte de votre photo</li>
                    <li>Les photos publiques sont visibles par tous les utilisateurs</li>
                    <li>Les photos privées ne sont visibles que par vous</li>
                </ul>
            </div>
            
            <div class="sidebar-card">
                <h3>Statistiques Actuelles</h3>
                <div class="stats-list">
                    <div class="stat-item">
                        <span class="stat-label">Vues:</span>
                        <span class="stat-value"><?= $photo['views_count'] ?? 0 ?></span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-label">Favoris:</span>
                        <span class="stat-value"><?= $photo['favorite_count'] ?? 0 ?></span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-label">Commentaires:</span>
                        <span class="stat-value"><?= $photo['comment_count'] ?? 0 ?></span>
                    </div>
                </div>
            </div>
            
            <div class="sidebar-card">
                <h3>Actions Rapides</h3>
                <div class="quick-actions">
                    <a href="/photos/<?= $photo['id'] ?>" class="btn btn-outline btn-block">Voir la photo</a>
                    <a href="/photos/<?= $photo['id'] ?>/lightbox" class="btn btn-outline btn-block">Voir en plein écran</a>
                    <button class="btn btn-danger btn-block delete-photo" data-photo-id="<?= $photo['id'] ?>">
                        Supprimer la photo
                    </button>
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
            <p>Êtes-vous sûr de vouloir supprimer cette photo ?</p>
            <p><strong>Cette action est irréversible et supprimera définitivement la photo.</strong></p>
            <p><strong>Photo : <?= htmlspecialchars($photo['title'] ?? 'Sans titre') ?></strong></p>
        </div>
        <div class="modal-footer">
            <button class="btn btn-outline modal-cancel">Annuler</button>
            <form id="delete-form" method="POST" action="/photos/delete/<?= $photo['id'] ?>">
                <button type="submit" class="btn btn-danger">Supprimer</button>
            </form>
        </div>
    </div>
</div>

<?php 
$additionalScripts = '
<script>
document.addEventListener("DOMContentLoaded", function() {
    // File upload preview
    const fileInput = document.getElementById("replace_photo");
    const fileLabel = document.querySelector(".file-label");
    const fileName = document.querySelector(".file-name");
    const previewImage = document.querySelector(".preview-image");
    
    fileInput.addEventListener("change", function(e) {
        const file = e.target.files[0];
        if (file && file.type.startsWith("image/")) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImage.src = e.target.result;
                fileName.textContent = file.name;
            };
            reader.readAsDataURL(file);
        }
    });
    
    // Description character count
    const descriptionTextarea = document.getElementById("description");
    const countSpan = document.getElementById("description-count");
    
    descriptionTextarea.addEventListener("input", function() {
        countSpan.textContent = this.value.length;
    });
    
    // Tags management
    const tagsInput = document.getElementById("tags");
    const currentTags = document.getElementById("current-tags");
    
    // Remove tag functionality
    currentTags.addEventListener("click", function(e) {
        if (e.target.classList.contains("tag-remove")) {
            e.preventDefault();
            const tagName = e.target.dataset.tag;
            const tagBadge = e.target.closest(".tag-badge");
            
            // Remove from current tags display
            tagBadge.remove();
            
            // Update input field
            const currentTagsArray = tagsInput.value.split(",").map(tag => tag.trim()).filter(tag => tag !== tagName);
            tagsInput.value = currentTagsArray.join(", ");
        }
    });
    
    // Add tags on input change
    tagsInput.addEventListener("input", function() {
        const tags = this.value.split(",").map(tag => tag.trim()).filter(tag => tag);
        updateCurrentTags(tags);
    });
    
    function updateCurrentTags(tags) {
        const existingTags = Array.from(currentTags.querySelectorAll(".tag-badge")).map(badge => 
            badge.querySelector("button").dataset.tag
        );
        
        // Add new tags
        tags.forEach(tag => {
            if (!existingTags.includes(tag)) {
                const tagBadge = document.createElement("span");
                tagBadge.className = "tag-badge";
                tagBadge.innerHTML = `
                    ${tag}
                    <button type="button" class="tag-remove" data-tag="${tag}">&times;</button>
                `;
                currentTags.appendChild(tagBadge);
            }
        });
        
        // Remove tags that are no longer in input
        existingTags.forEach(tag => {
            if (!tags.includes(tag)) {
                const badge = currentTags.querySelector(`[data-tag="${tag}"]`);
                if (badge) {
                    badge.closest(".tag-badge").remove();
                }
            }
        });
    }
    
    // Delete modal
    const deleteBtn = document.querySelector(".delete-photo");
    const deleteModal = document.getElementById("delete-modal");
    const deleteForm = document.getElementById("delete-form");
    const modalClose = document.querySelector(".modal-close");
    const modalCancel = document.querySelector(".modal-cancel");
    
    if (deleteBtn) {
        deleteBtn.addEventListener("click", function() {
            deleteModal.style.display = "block";
        });
    }
    
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

function formatFileSize(bytes) {
    if (bytes === 0) return "0 Bytes";
    const k = 1024;
    const sizes = ["Bytes", "KB", "MB", "GB"];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + " " + sizes[i];
}
</script>

<style>
.photo-preview {
    display: flex;
    gap: 2rem;
    margin-bottom: 2rem;
    padding: 1rem;
    background: #f8f9fa;
    border-radius: 12px;
}

.preview-image {
    width: 200px;
    height: 150px;
    object-fit: cover;
    border-radius: 8px;
}

.photo-info p {
    margin: 0.25rem 0;
    font-size: 0.875rem;
    color: #666;
}

.file-upload {
    position: relative;
}

.file-input {
    display: none;
}

.file-label {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.5rem;
    background: #007bff;
    color: #fff;
    border-radius: 6px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.file-label:hover {
    background: #0056b3;
}

.file-name {
    margin-left: 1rem;
    color: #666;
    font-size: 0.875rem;
}

.current-tags {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
    margin-top: 1rem;
}

.tag-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    background: #f8f9fa;
    color: #333;
    padding: 0.25rem 0.75rem;
    border-radius: 15px;
    font-size: 0.875rem;
}

.tag-remove {
    background: none;
    border: none;
    color: #666;
    cursor: pointer;
    font-size: 1rem;
    line-height: 1;
    padding: 0;
    width: 16px;
    height: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
}

.tag-remove:hover {
    background: #dc3545;
    color: #fff;
}

.tips-list li {
    margin-bottom: 0.5rem;
    padding-left: 1.5rem;
    position: relative;
}

.tips-list li::before {
    content: "✓";
    position: absolute;
    left: 0;
    color: #28a745;
}

.stats-list {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.stat-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.5rem 0;
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

.quick-actions {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

@media (max-width: 768px) {
    .photo-preview {
        flex-direction: column;
        gap: 1rem;
    }
    
    .preview-image {
        width: 100%;
        height: auto;
    }
}
</style>';
require_once __DIR__ . '/../partials/footer.php'; 

// Helper function
function getPhotoTags($photoId) {
    // This would query the database for photo tags
    return [];
}
?>
