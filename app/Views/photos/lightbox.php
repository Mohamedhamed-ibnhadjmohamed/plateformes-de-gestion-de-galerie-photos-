<?php 
$pageTitle = $photo['title'] ?? 'Photo';
require_once __DIR__ . '/../partials/header.php'; 
?>

<div class="lightbox-container" id="lightbox">
    <div class="lightbox-header">
        <div class="lightbox-info">
            <h2 class="lightbox-title"><?= htmlspecialchars($photo['title'] ?? 'Sans titre') ?></h2>
            <p class="lightbox-meta">
                par <a href="/users/<?= $photo['user_id'] ?>"><?= htmlspecialchars($photo['username']) ?></a>
                dans <a href="/albums/<?= $photo['album_id'] ?>"><?= htmlspecialchars($photo['album_title']) ?></a>
            </p>
        </div>
        
        <div class="lightbox-actions">
            <button class="lightbox-btn" id="fullscreen-btn" title="Plein écran">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M8 3H5a2 2 0 00-2 2v3m18 0V5a2 2 0 00-2-2h-3m0 18h3a2 2 0 002-2v-3M3 16v3a2 2 0 002 2h3"/>
                </svg>
            </button>
            
            <button class="lightbox-btn" id="download-btn" title="Télécharger">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4M7 10l5 5 5-5M12 15V3"/>
                </svg>
            </button>
            
            <button class="lightbox-btn" id="share-btn" title="Partager">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="18" cy="5" r="3"/>
                    <circle cx="6" cy="12" r="3"/>
                    <circle cx="18" cy="19" r="3"/>
                    <path d="M8.59 13.51l6.83 3.98M15.41 6.51l-6.82 3.98"/>
                </svg>
            </button>
            
            <button class="lightbox-btn" id="close-btn" title="Fermer">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M18 6L6 18M6 6l12 12"/>
                </svg>
            </button>
        </div>
    </div>
    
    <div class="lightbox-content">
        <div class="photo-container">
            <img src="/uploads/albums/<?= htmlspecialchars($photo['filename']) ?>" 
                 alt="<?= htmlspecialchars($photo['title'] ?? 'Photo') ?>"
                 class="lightbox-image"
                 id="lightbox-image">
            
            <div class="photo-navigation">
                <button class="nav-btn prev-btn" id="prev-btn" title="Photo précédente">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M15 18l-6-6 6-6"/>
                    </svg>
                </button>
                
                <button class="nav-btn next-btn" id="next-btn" title="Photo suivante">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M9 18l6-6-6-6"/>
                    </svg>
                </button>
            </div>
            
            <div class="loading-spinner" id="loading" style="display: none;">
                <div class="spinner"></div>
            </div>
        </div>
        
        <div class="lightbox-sidebar">
            <div class="photo-details">
                <h3>Détails de la Photo</h3>
                
                <?php if ($photo['description']): ?>
                    <div class="detail-section">
                        <h4>Description</h4>
                        <p><?= htmlspecialchars($photo['description']) ?></p>
                    </div>
                <?php endif; ?>
                
                <div class="detail-section">
                    <h4>Informations</h4>
                    <dl class="detail-list">
                        <dt>Taille</dt>
                        <dd><?= $photo['width'] ?> × <?= $photo['height'] ?> px</dd>
                        
                        <dt>Poids</dt>
                        <dd><?= formatFileSize($photo['file_size']) ?></dd>
                        
                        <dt>Format</dt>
                        <dd><?= strtoupper(pathinfo($photo['filename'], PATHINFO_EXTENSION)) ?></dd>
                        
                        <dt>Date d'upload</dt>
                        <dd><?= date('d/m/Y à H:i', strtotime($photo['created_at'])) ?></dd>
                        
                        <dt>Vues</dt>
                        <dd><?= $photo['views_count'] ?? 0 ?></dd>
                    </dl>
                </div>
                
                <div class="detail-section">
                    <h4>Tags</h4>
                    <div class="photo-tags" id="photo-tags">
                        <!-- Tags would be loaded here -->
                        <span class="tag">nature</span>
                        <span class="tag">paysage</span>
                        <span class="tag">sunset</span>
                    </div>
                    <?php if (canEditPhoto($photo)): ?>
                        <button class="btn btn-outline btn-sm" id="add-tag-btn">Ajouter un tag</button>
                    <?php endif; ?>
                </div>
                
                <div class="detail-section">
                    <h4>Actions</h4>
                    <div class="action-buttons">
                        <button class="btn btn-primary favorite-btn" 
                                data-photo-id="<?= $photo['id'] ?>"
                                id="favorite-btn">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                            </svg>
                            Ajouter aux favoris
                        </button>
                        
                        <?php if (canEditPhoto($photo)): ?>
                            <a href="/photos/<?= $photo['id'] ?>/edit" class="btn btn-outline">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/>
                                    <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                </svg>
                                Modifier
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <div class="comments-section">
                <h3>Commentaires</h3>
                
                <?php if (isLoggedIn()): ?>
                    <div class="comment-form">
                        <textarea id="comment-input" 
                                  placeholder="Ajoutez un commentaire..." 
                                  rows="3"
                                  maxlength="1000"></textarea>
                        <button class="btn btn-primary btn-sm" id="submit-comment">Commenter</button>
                    </div>
                <?php else: ?>
                    <p><a href="/users/login">Connectez-vous</a> pour commenter.</p>
                <?php endif; ?>
                
                <div class="comments-list" id="comments-list">
                    <!-- Comments would be loaded here -->
                    <div class="comment">
                        <div class="comment-avatar">
                            <img src="/assets/images/default-avatar.png" alt="Avatar">
                        </div>
                        <div class="comment-content">
                            <div class="comment-header">
                                <span class="comment-author">John Doe</span>
                                <span class="comment-time">Il y a 2 heures</span>
                            </div>
                            <p class="comment-text">Superbe photo ! J'adore les couleurs.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="lightbox-footer">
        <div class="photo-counter">
            <span id="current-index">1</span> / <span id="total-photos">10</span>
        </div>
        
        <div class="thumbnail-strip" id="thumbnail-strip">
            <!-- Thumbnails would be loaded here -->
        </div>
    </div>
</div>

<!-- Share Modal -->
<div id="share-modal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Partager cette Photo</h3>
            <button class="modal-close">&times;</button>
        </div>
        <div class="modal-body">
            <div class="share-options">
                <button class="share-btn" data-platform="facebook">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                    </svg>
                    Facebook
                </button>
                
                <button class="share-btn" data-platform="twitter">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                    </svg>
                    Twitter
                </button>
                
                <button class="share-btn" data-platform="copy">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="9" y="9" width="13" height="13" rx="2" ry="2"/>
                        <path d="M5 15H4a2 2 0 01-2-2V4a2 2 0 012-2h9a2 2 0 012 2v1"/>
                    </svg>
                    Copier le lien
                </button>
            </div>
            
            <div class="share-link">
                <input type="text" id="share-url" value="<?= $_SERVER['REQUEST_URI'] ?>" readonly>
                <button class="btn btn-primary btn-sm" id="copy-link">Copier</button>
            </div>
        </div>
    </div>
</div>

<?php 
$additionalScripts = '
<script>
document.addEventListener("DOMContentLoaded", function() {
    const lightbox = document.getElementById("lightbox");
    const closeBtn = document.getElementById("close-btn");
    const fullscreenBtn = document.getElementById("fullscreen-btn");
    const downloadBtn = document.getElementById("download-btn");
    const shareBtn = document.getElementById("share-btn");
    const shareModal = document.getElementById("share-modal");
    const favoriteBtn = document.getElementById("favorite-btn");
    const commentInput = document.getElementById("comment-input");
    const submitCommentBtn = document.getElementById("submit-comment");
    const lightboxImage = document.getElementById("lightbox-image");
    
    // Close lightbox
    closeBtn.addEventListener("click", function() {
        window.close();
        // Or redirect back to photo page
        window.location.href = "/photos/' . $photo['id'] . '";
    });
    
    // Fullscreen
    fullscreenBtn.addEventListener("click", function() {
        if (!document.fullscreenElement) {
            lightbox.requestFullscreen();
        } else {
            document.exitFullscreen();
        }
    });
    
    // Download
    downloadBtn.addEventListener("click", function() {
        const link = document.createElement("a");
        link.href = "/uploads/albums/' . htmlspecialchars($photo['filename']) . '";
        link.download = "' . htmlspecialchars($photo['filename']) . '";
        link.click();
    });
    
    // Share modal
    shareBtn.addEventListener("click", function() {
        shareModal.style.display = "block";
    });
    
    // Favorite functionality
    favoriteBtn.addEventListener("click", function() {
        const photoId = ' . $photo['id'] . ';
        
        fetch(`/favorites/toggle/${photoId}`, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-Requested-With": "XMLHttpRequest"
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                if (data.action === "added") {
                    this.innerHTML = `
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" stroke="currentColor" stroke-width="2">
                            <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                        </svg>
                        Retirer des favoris
                    `;
                } else {
                    this.innerHTML = `
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                        </svg>
                        Ajouter aux favoris
                    `;
                }
            }
        });
    });
    
    // Comment submission
    submitCommentBtn.addEventListener("click", function() {
        const content = commentInput.value.trim();
        if (!content) return;
        
        const photoId = ' . $photo['id'] . ';
        
        fetch("/comments/store", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-Requested-With": "XMLHttpRequest"
            },
            body: JSON.stringify({
                photo_id: photoId,
                content: content
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Add comment to list
                const commentsList = document.getElementById("comments-list");
                const newComment = document.createElement("div");
                newComment.className = "comment";
                newComment.innerHTML = `
                    <div class="comment-avatar">
                        <img src="${data.comment.avatar || "/assets/images/default-avatar.png"}" alt="Avatar">
                    </div>
                    <div class="comment-content">
                        <div class="comment-header">
                            <span class="comment-author">${data.comment.display_name}</span>
                            <span class="comment-time">${data.comment.created_at}</span>
                        </div>
                        <p class="comment-text">${data.comment.content}</p>
                    </div>
                `;
                commentsList.appendChild(newComment);
                
                // Clear input
                commentInput.value = "";
            }
        });
    });
    
    // Keyboard navigation
    document.addEventListener("keydown", function(e) {
        if (e.key === "Escape") {
            window.close();
        }
    });
});

function formatFileSize(bytes) {
    if (bytes === 0) return "0 Bytes";
    const k = 1024;
    const sizes = ["Bytes", "KB", "MB", "GB"];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + " " + sizes[i];
}
</script>';
require_once __DIR__ . '/../partials/footer.php'; 
?>
