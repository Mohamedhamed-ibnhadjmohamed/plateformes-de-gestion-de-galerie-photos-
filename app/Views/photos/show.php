<?php 
$pageTitle = $photo['title'] ?? 'Photo';
require_once __DIR__ . '/../partials/header.php'; 
?>

<div class="page-header">
    <div class="container">
        <h1 class="page-title"><?= htmlspecialchars($photo['title'] ?? 'Sans titre') ?></h1>
        <p class="page-description">
            par <a href="/users/<?= $photo['user_id'] ?>"><?= htmlspecialchars($photo['username']) ?></a>
            dans <a href="/albums/<?= $photo['album_id'] ?>"><?= htmlspecialchars($photo['album_title']) ?></a>
        </p>
    </div>
</div>

<div class="container">
    <div class="photo-show-container">
        <!-- Photo Display -->
        <div class="photo-main">
            <div class="photo-container">
                <img src="/uploads/albums/<?= htmlspecialchars($photo['filename']) ?>" 
                     alt="<?= htmlspecialchars($photo['title'] ?? 'Photo') ?>"
                     class="main-photo"
                     id="main-photo">
                
                <div class="photo-actions">
                    <button class="photo-action-btn favorite-btn <?= $photo['is_favorited'] ? 'favorited' : '' ?>" 
                            data-photo-id="<?= $photo['id'] ?>"
                            id="favorite-btn">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="<?= $photo['is_favorited'] ? 'currentColor' : 'none' ?>" stroke="currentColor" stroke-width="2">
                            <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                        </svg>
                        <span><?= $photo['is_favorited'] ? 'Retirer des favoris' : 'Ajouter aux favoris' ?></span>
                    </button>
                    
                    <button class="photo-action-btn" id="fullscreen-btn">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M8 3H5a2 2 0 00-2 2v3m18 0V5a2 2 0 00-2-2h-3m0 18h3a2 2 0 002-2v-3M3 16v3a2 2 0 002 2h3"/>
                        </svg>
                        <span>Plein écran</span>
                    </button>
                    
                    <button class="photo-action-btn" id="download-btn">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4M7 10l5 5 5-5M12 15V3"/>
                        </svg>
                        <span>Télécharger</span>
                    </button>
                    
                    <button class="photo-action-btn" id="share-btn">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="18" cy="5" r="3"/>
                            <circle cx="6" cy="12" r="3"/>
                            <circle cx="18" cy="19" r="3"/>
                            <path d="M8.59 13.51l6.83 3.98M15.41 6.51l-6.82 3.98"/>
                        </svg>
                        <span>Partager</span>
                    </button>
                    
                    <?php if (canEditPhoto($photo)): ?>
                        <a href="/photos/<?= $photo['id'] ?>/edit" class="photo-action-btn">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/>
                                <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>
                            </svg>
                            <span>Modifier</span>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <!-- Photo Info -->
        <div class="photo-info-panel">
            <div class="photo-details">
                <h2><?= htmlspecialchars($photo['title'] ?? 'Sans titre') ?></h2>
                
                <?php if ($photo['description']): ?>
                    <p class="photo-description"><?= htmlspecialchars($photo['description']) ?></p>
                <?php endif; ?>
                
                <div class="photo-meta">
                    <div class="meta-item">
                        <span class="meta-label">Photographe:</span>
                        <a href="/users/<?= $photo['user_id'] ?>" class="meta-value">
                            <img src="<?= getUserAvatar($photo, 24) ?>" alt="Avatar" class="meta-avatar">
                            <?= htmlspecialchars($photo['username']) ?>
                        </a>
                    </div>
                    
                    <div class="meta-item">
                        <span class="meta-label">Album:</span>
                        <a href="/albums/<?= $photo['album_id'] ?>" class="meta-value">
                            <?= htmlspecialchars($photo['album_title']) ?>
                        </a>
                    </div>
                    
                    <div class="meta-item">
                        <span class="meta-label">Date:</span>
                        <span class="meta-value"><?= date('d/m/Y à H:i', strtotime($photo['created_at'])) ?></span>
                    </div>
                    
                    <div class="meta-item">
                        <span class="meta-label">Dimensions:</span>
                        <span class="meta-value"><?= $photo['width'] ?> × <?= $photo['height'] ?> px</span>
                    </div>
                    
                    <div class="meta-item">
                        <span class="meta-label">Taille:</span>
                        <span class="meta-value"><?= formatFileSize($photo['file_size']) ?></span>
                    </div>
                </div>
                
                <div class="photo-stats">
                    <div class="stat-item">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                            <circle cx="12" cy="12" r="3"/>
                        </svg>
                        <span><?= $photo['views_count'] ?> vues</span>
                    </div>
                    
                    <div class="stat-item">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                        </svg>
                        <span id="favorite-count"><?= $photo['favorite_count'] ?> favoris</span>
                    </div>
                    
                    <div class="stat-item">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 00-2-2v-7"/>
                        </svg>
                        <span id="comment-count"><?= $photo['comment_count'] ?> commentaires</span>
                    </div>
                </div>
            </div>
            
            <!-- Tags -->
            <div class="photo-tags">
                <h3>Tags</h3>
                <div class="tags-list" id="photo-tags">
                    <?php
                    $photoTags = getPhotoTags($photo['id']);
                    foreach ($photoTags as $tag): ?>
                        <span class="tag">
                            <a href="/tags/<?= htmlspecialchars($tag['slug']) ?>">
                                <?= htmlspecialchars($tag['name']) ?>
                            </a>
                        </span>
                    <?php endforeach; ?>
                </div>
                
                <?php if (canEditPhoto($photo)): ?>
                    <button class="btn btn-outline btn-sm" id="add-tag-btn">Ajouter un tag</button>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <!-- Comments Section -->
    <div class="comments-section">
        <h3>Commentaires</h3>
        
        <?php if (isLoggedIn()): ?>
            <div class="comment-form">
                <form id="comment-form">
                    <textarea id="comment-input" 
                              placeholder="Ajoutez un commentaire..." 
                              rows="3"
                              maxlength="1000"
                              required></textarea>
                    <button type="submit" class="btn btn-primary">Commenter</button>
                </form>
            </div>
        <?php else: ?>
            <p><a href="/users/login">Connectez-vous</a> pour commenter.</p>
        <?php endif; ?>
        
        <div class="comments-list" id="comments-list">
            <?php
            $comments = getPhotoComments($photo['id']);
            foreach ($comments as $comment): ?>
                <div class="comment">
                    <div class="comment-avatar">
                        <img src="<?= getUserAvatar($comment, 40) ?>" alt="Avatar">
                    </div>
                    <div class="comment-content">
                        <div class="comment-header">
                            <span class="comment-author"><?= htmlspecialchars($comment['username']) ?></span>
                            <span class="comment-time"><?= formatRelativeTime($comment['created_at']) ?></span>
                        </div>
                        <p class="comment-text"><?= htmlspecialchars($comment['content']) ?></p>
                        
                        <?php if (canEditComment($comment['id'])): ?>
                            <div class="comment-actions">
                                <button class="btn btn-sm btn-outline edit-comment" data-comment-id="<?= $comment['id'] ?>">
                                    Modifier
                                </button>
                                <button class="btn btn-sm btn-danger delete-comment" data-comment-id="<?= $comment['id'] ?>">
                                    Supprimer
                                </button>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        
        <?php if (empty($comments)): ?>
            <p class="no-comments">Soyez le premier à commenter cette photo !</p>
        <?php endif; ?>
    </div>
    
    <!-- Related Photos -->
    <div class="related-photos">
        <h3>Photos similaires</h3>
        <div class="related-grid">
            <?php
            $relatedPhotos = getRelatedPhotos($photo['id'], 6);
            foreach ($relatedPhotos as $relatedPhoto): ?>
                <div class="related-photo">
                    <a href="/photos/<?= $relatedPhoto['id'] ?>">
                        <img src="/uploads/thumbs/<?= htmlspecialchars($relatedPhoto['filename']) ?>" 
                             alt="<?= htmlspecialchars($relatedPhoto['title'] ?? 'Photo') ?>"
                             loading="lazy">
                        <div class="related-overlay">
                            <span><?= htmlspecialchars($relatedPhoto['title'] ?? 'Sans titre') ?></span>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
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
    // Favorite functionality
    const favoriteBtn = document.getElementById("favorite-btn");
    const favoriteCount = document.getElementById("favorite-count");
    
    if (favoriteBtn) {
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
                    const svg = this.querySelector("svg");
                    const span = this.querySelector("span");
                    
                    if (data.action === "added") {
                        svg.setAttribute("fill", "currentColor");
                        this.classList.add("favorited");
                        span.textContent = "Retirer des favoris";
                    } else {
                        svg.setAttribute("fill", "none");
                        this.classList.remove("favorited");
                        span.textContent = "Ajouter aux favoris";
                    }
                    
                    favoriteCount.textContent = data.count + " favoris";
                    showNotification(data.message, "success");
                }
            })
            .catch(error => console.error("Error:", error));
        });
    }
    
    // Fullscreen
    const fullscreenBtn = document.getElementById("fullscreen-btn");
    const mainPhoto = document.getElementById("main-photo");
    
    if (fullscreenBtn) {
        fullscreenBtn.addEventListener("click", function() {
            if (!document.fullscreenElement) {
                mainPhoto.requestFullscreen();
            } else {
                document.exitFullscreen();
            }
        });
    }
    
    // Download
    const downloadBtn = document.getElementById("download-btn");
    if (downloadBtn) {
        downloadBtn.addEventListener("click", function() {
            const link = document.createElement("a");
            link.href = "/uploads/albums/' . htmlspecialchars($photo['filename']) . '";
            link.download = "' . htmlspecialchars($photo['filename']) . '";
            link.click();
        });
    }
    
    // Share modal
    const shareBtn = document.getElementById("share-btn");
    const shareModal = document.getElementById("share-modal");
    const shareUrl = document.getElementById("share-url");
    const copyLink = document.getElementById("copy-link");
    
    if (shareBtn) {
        shareBtn.addEventListener("click", function() {
            shareModal.style.display = "block";
        });
    }
    
    // Copy link
    if (copyLink) {
        copyLink.addEventListener("click", function() {
            shareUrl.select();
            document.execCommand("copy");
            showNotification("Lien copié dans le presse-papiers", "success");
        });
    }
    
    // Comment form
    const commentForm = document.getElementById("comment-form");
    const commentInput = document.getElementById("comment-input");
    const commentsList = document.getElementById("comments-list");
    
    if (commentForm) {
        commentForm.addEventListener("submit", function(e) {
            e.preventDefault();
            
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
                    
                    // Remove "no comments" message if exists
                    const noComments = document.querySelector(".no-comments");
                    if (noComments) {
                        noComments.remove();
                    }
                    
                    commentsList.appendChild(newComment);
                    commentInput.value = "";
                    
                    // Update comment count
                    const commentCount = document.getElementById("comment-count");
                    const currentCount = parseInt(commentCount.textContent);
                    commentCount.textContent = (currentCount + 1) + " commentaires";
                    
                    showNotification("Commentaire ajouté avec succès", "success");
                }
            });
        });
    }
    
    // Modal close handlers
    const modalClose = document.querySelector(".modal-close");
    const modalCancel = document.querySelector(".modal-cancel");
    
    if (modalClose) {
        modalClose.addEventListener("click", function() {
            shareModal.style.display = "none";
        });
    }
    
    if (modalCancel) {
        modalCancel.addEventListener("click", function() {
            shareModal.style.display = "none";
        });
    }
    
    window.addEventListener("click", function(event) {
        if (event.target === shareModal) {
            shareModal.style.display = "none";
        }
    });
    
    // Social share buttons
    const shareButtons = document.querySelectorAll(".share-btn");
    shareButtons.forEach(btn => {
        btn.addEventListener("click", function() {
            const platform = this.dataset.platform;
            const url = window.location.href;
            const title = document.title;
            
            let shareUrl = "";
            switch (platform) {
                case "facebook":
                    shareUrl = `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(url)}`;
                    break;
                case "twitter":
                    shareUrl = `https://twitter.com/intent/tweet?url=${encodeURIComponent(url)}&text=${encodeURIComponent(title)}`;
                    break;
                case "copy":
                    navigator.clipboard.writeText(url);
                    showNotification("Lien copié dans le presse-papiers", "success");
                    return;
            }
            
            if (shareUrl) {
                window.open(shareUrl, "_blank", "width=600,height=400");
            }
        });
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

function formatFileSize(bytes) {
    if (bytes === 0) return "0 Bytes";
    const k = 1024;
    const sizes = ["Bytes", "KB", "MB", "GB"];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + " " + sizes[i];
}
</script>

<style>
.photo-show-container {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 2rem;
    margin-bottom: 3rem;
}

.photo-main {
    position: relative;
}

.photo-container {
    position: relative;
    background: #000;
    border-radius: 12px;
    overflow: hidden;
}

.main-photo {
    width: 100%;
    height: auto;
    display: block;
}

.photo-actions {
    position: absolute;
    bottom: 1rem;
    left: 1rem;
    right: 1rem;
    display: flex;
    gap: 0.5rem;
    background: rgba(0,0,0,0.7);
    padding: 0.75rem;
    border-radius: 8px;
    backdrop-filter: blur(10px);
}

.photo-action-btn {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    background: rgba(255,255,255,0.9);
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-size: 0.875rem;
    transition: all 0.3s ease;
    color: #333;
}

.photo-action-btn:hover {
    background: rgba(255,255,255,1);
    transform: translateY(-2px);
}

.photo-action-btn.favorited {
    background: #dc3545;
    color: #fff;
}

.photo-action-btn a {
    color: inherit;
    text-decoration: none;
}

.photo-info-panel {
    background: #fff;
    border-radius: 12px;
    padding: 1.5rem;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
}

.photo-details h2 {
    margin-bottom: 1rem;
    color: #333;
}

.photo-description {
    margin-bottom: 1.5rem;
    line-height: 1.6;
    color: #666;
}

.photo-meta {
    margin-bottom: 1.5rem;
}

.meta-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.5rem 0;
    border-bottom: 1px solid #eee;
}

.meta-item:last-child {
    border-bottom: none;
}

.meta-label {
    font-weight: 500;
    color: #666;
}

.meta-value {
    color: #333;
    text-decoration: none;
}

.meta-avatar {
    width: 24px;
    height: 24px;
    border-radius: 50%;
    object-fit: cover;
    margin-right: 0.5rem;
}

.photo-stats {
    display: flex;
    gap: 1rem;
    margin-bottom: 1.5rem;
    padding: 1rem 0;
    border-top: 1px solid #eee;
    border-bottom: 1px solid #eee;
}

.stat-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: #666;
    font-size: 0.875rem;
}

.photo-tags h3 {
    margin-bottom: 0.5rem;
    color: #333;
}

.tags-list {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
    margin-bottom: 1rem;
}

.tag {
    background: #f8f9fa;
    color: #333;
    padding: 0.25rem 0.75rem;
    border-radius: 15px;
    font-size: 0.875rem;
}

.tag a {
    color: inherit;
    text-decoration: none;
}

.tag a:hover {
    color: #007bff;
}

.comments-section {
    background: #fff;
    border-radius: 12px;
    padding: 1.5rem;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    margin-bottom: 3rem;
}

.comments-section h3 {
    margin-bottom: 1.5rem;
    color: #333;
}

.comment-form {
    margin-bottom: 2rem;
}

.comment-form textarea {
    width: 100%;
    padding: 1rem;
    border: 1px solid #ddd;
    border-radius: 8px;
    resize: vertical;
    margin-bottom: 1rem;
}

.comment {
    display: flex;
    gap: 1rem;
    margin-bottom: 1.5rem;
}

.comment-avatar img {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    object-fit: cover;
}

.comment-content {
    flex: 1;
}

.comment-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 0.5rem;
}

.comment-author {
    font-weight: 600;
    color: #333;
}

.comment-time {
    font-size: 0.875rem;
    color: #666;
}

.comment-text {
    margin-bottom: 0.5rem;
    line-height: 1.6;
    color: #333;
}

.comment-actions {
    display: flex;
    gap: 0.5rem;
}

.no-comments {
    text-align: center;
    color: #666;
    padding: 2rem;
}

.related-photos {
    background: #fff;
    border-radius: 12px;
    padding: 1.5rem;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
}

.related-photos h3 {
    margin-bottom: 1.5rem;
    color: #333;
}

.related-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
}

.related-photo {
    position: relative;
    aspect-ratio: 16/9;
    border-radius: 8px;
    overflow: hidden;
}

.related-photo img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.related-photo:hover img {
    transform: scale(1.05);
}

.related-overlay {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    background: linear-gradient(to top, rgba(0,0,0,0.8), transparent);
    color: #fff;
    padding: 1rem;
    font-size: 0.875rem;
}

@media (max-width: 768px) {
    .photo-show-container {
        grid-template-columns: 1fr;
    }
    
    .photo-actions {
        flex-wrap: wrap;
        gap: 0.25rem;
    }
    
    .photo-action-btn {
        padding: 0.5rem;
        font-size: 0.75rem;
    }
    
    .photo-action-btn span {
        display: none;
    }
    
    .related-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}
</style>';
require_once __DIR__ . '/../partials/footer.php'; 

// Helper functions
function getPhotoTags($photoId) {
    // This would query the database for photo tags
    return [];
}

function getRelatedPhotos($photoId, $limit = 6) {
    // This would query for related photos
    return [];
}

function canEditComment($commentId) {
    // This would check if current user can edit the comment
    return false;
}
?>
