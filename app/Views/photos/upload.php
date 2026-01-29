<?php 
$pageTitle = 'Uploader une Photo';
require_once __DIR__ . '/../partials/header.php'; 
?>

<div class="page-header">
    <div class="container">
        <h1 class="page-title">Uploader une Photo</h1>
        <p class="page-description">Partagez vos plus belles photos avec la communauté</p>
    </div>
</div>

<div class="container">
    <div class="content-wrapper">
        <div class="form-container">
            <form action="/photos/store" method="POST" enctype="multipart/form-data" class="upload-form">
                <div class="form-section">
                    <h2>Sélectionner une Photo</h2>
                    
                    <div class="upload-area" id="upload-area">
                        <div class="upload-content">
                            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4M17 8l-5-5-5 5M12 3v12"/>
                            </svg>
                            <h3>Glissez-déposez votre photo ici</h3>
                            <p>ou</p>
                            <button type="button" class="btn btn-outline" id="browse-btn">Parcourir</button>
                            <input type="file" id="photo-input" name="photo" accept="image/*" required hidden>
                            <p class="upload-info">
                                Formats acceptés : JPG, PNG, GIF, WebP<br>
                                Taille maximale : 10MB
                            </p>
                        </div>
                        
                        <div class="upload-preview" id="upload-preview" style="display: none;">
                            <img id="preview-image" src="" alt="Preview">
                            <div class="preview-info">
                                <p class="preview-name" id="preview-name"></p>
                                <p class="preview-size" id="preview-size"></p>
                                <button type="button" class="btn btn-outline btn-sm" id="remove-photo">Retirer</button>
                            </div>
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
                                  placeholder="Décrivez votre photo..."></textarea>
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
                        <label for="album_id" class="form-label">Album *</label>
                        <select id="album_id" name="album_id" class="form-control" required>
                            <option value="">Sélectionnez un album</option>
                            <?php foreach ($albums as $album): ?>
                                <option value="<?= $album['id'] ?>"><?= htmlspecialchars($album['title']) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <?php if (isset($errors['album_id'])): ?>
                            <div class="form-error">
                                <?php foreach ($errors['album_id'] as $error): ?>
                                    <span><?= htmlspecialchars($error) ?></span>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
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
                                   checked>
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
                               placeholder="Ajoutez des tags (séparés par des virgules)">
                        <div class="form-help">
                            Ex: nature, paysage, portrait, noir et blanc
                        </div>
                        <div class="tags-suggestions" id="tags-suggestions">
                            <!-- Popular tags would be suggested here -->
                        </div>
                    </div>
                </div>
                
                <div class="form-actions">
                    <a href="/photos" class="btn btn-outline">Annuler</a>
                    <button type="submit" class="btn btn-primary" id="upload-btn" disabled>
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4M17 8l-5-5-5 5M12 3v12"/>
                        </svg>
                        Uploader la Photo
                    </button>
                </div>
            </form>
        </div>
        
        <div class="sidebar">
            <div class="sidebar-card">
                <h3>Conseils de Upload</h3>
                <ul class="tips-list">
                    <li>Choisissez une photo de haute qualité</li>
                    <li>Préférez les formats JPG ou PNG</li>
                    <li>La taille maximale est de 10MB</li>
                    <li>Ajoutez des tags pour améliorer la découvrabilité</li>
                    <li>Une bonne description aide les autres à comprendre votre photo</li>
                </ul>
            </div>
            
            <div class="sidebar-card">
                <h3>Recommandations</h3>
                <div class="recommendations">
                    <div class="rec-item">
                        <h4>Résolution</h4>
                        <p>Minimum 1920x1080 pixels pour un meilleur affichage</p>
                    </div>
                    <div class="rec-item">
                        <h4>Format</h4>
                        <p>JPG pour les photos, PNG pour les graphiques</p>
                    </div>
                    <div class="rec-item">
                        <h4>Taille</h4>
                        <p>Entre 1MB et 10MB pour un bon équilibre qualité/poids</p>
                    </div>
                </div>
            </div>
            
            <div class="sidebar-card">
                <h3>Vos Albums Récents</h3>
                <div class="recent-albums">
                    <?php if (!empty($albums)): ?>
                        <?php foreach (array_slice($albums, 0, 5) as $album): ?>
                            <div class="recent-album">
                                <span class="album-name"><?= htmlspecialchars($album['title']) ?></span>
                                <span class="album-count"><?= $album['photo_count'] ?? 0 ?> photos</span>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>Vous n'avez pas encore d'album.</p>
                        <a href="/albums/create" class="btn btn-primary btn-sm">Créer un album</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php 
$additionalScripts = '
<script>
document.addEventListener("DOMContentLoaded", function() {
    const uploadArea = document.getElementById("upload-area");
    const photoInput = document.getElementById("photo-input");
    const browseBtn = document.getElementById("browse-btn");
    const uploadPreview = document.getElementById("upload-preview");
    const previewImage = document.getElementById("preview-image");
    const previewName = document.getElementById("preview-name");
    const previewSize = document.getElementById("preview-size");
    const removePhotoBtn = document.getElementById("remove-photo");
    const uploadBtn = document.getElementById("upload-btn");
    const descriptionTextarea = document.getElementById("description");
    const countSpan = document.getElementById("description-count");
    const tagsInput = document.getElementById("tags");
    
    // File selection
    browseBtn.addEventListener("click", () => photoInput.click());
    
    photoInput.addEventListener("change", function(e) {
        handleFileSelect(e.target.files[0]);
    });
    
    // Drag and drop
    uploadArea.addEventListener("dragover", function(e) {
        e.preventDefault();
        this.classList.add("drag-over");
    });
    
    uploadArea.addEventListener("dragleave", function(e) {
        e.preventDefault();
        this.classList.remove("drag-over");
    });
    
    uploadArea.addEventListener("drop", function(e) {
        e.preventDefault();
        this.classList.remove("drag-over");
        
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            handleFileSelect(files[0]);
        }
    });
    
    function handleFileSelect(file) {
        if (!file || !file.type.startsWith("image/")) {
            alert("Veuillez sélectionner une image valide.");
            return;
        }
        
        if (file.size > 10 * 1024 * 1024) {
            alert("La taille du fichier ne doit pas dépasser 10MB.");
            return;
        }
        
        // Show preview
        const reader = new FileReader();
        reader.onload = function(e) {
            previewImage.src = e.target.result;
            previewName.textContent = file.name;
            previewSize.textContent = formatFileSize(file.size);
            
            uploadArea.querySelector(".upload-content").style.display = "none";
            uploadPreview.style.display = "flex";
            uploadBtn.disabled = false;
        };
        reader.readAsDataURL(file);
    }
    
    // Remove photo
    removePhotoBtn.addEventListener("click", function() {
        photoInput.value = "";
        uploadArea.querySelector(".upload-content").style.display = "block";
        uploadPreview.style.display = "none";
        uploadBtn.disabled = true;
    });
    
    // Description character count
    descriptionTextarea.addEventListener("input", function() {
        countSpan.textContent = this.value.length;
    });
    
    // Tags input
    tagsInput.addEventListener("input", function() {
        // Auto-complete functionality would be implemented here
    });
    
    function formatFileSize(bytes) {
        if (bytes === 0) return "0 Bytes";
        const k = 1024;
        const sizes = ["Bytes", "KB", "MB", "GB"];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + " " + sizes[i];
    }
});
</script>';
require_once __DIR__ . '/../partials/footer.php'; 
?>
