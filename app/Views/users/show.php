<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="user-profile-container">
    <!-- User Header -->
    <div class="user-header">
        <div class="user-avatar-section">
            <div class="user-avatar-large">
                <?php if ($user['avatar']): ?>
                    <img src="<?= BASE_URL ?>/assets/images/avatars/<?= htmlspecialchars($user['avatar']) ?>" alt="<?= htmlspecialchars($user['username']) ?>">
                <?php else: ?>
                    <div class="avatar-placeholder">
                        <?= strtoupper(substr($user['username'], 0, 2)) ?>
                    </div>
                <?php endif; ?>
            </div>
            <div class="user-info">
                <h1><?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?></h1>
                <p class="username">@<?= htmlspecialchars($user['username']) ?></p>
                <p class="user-role"><?= ucfirst(htmlspecialchars($user['role'])) ?></p>
                <?php if ($user['bio']): ?>
                    <p class="user-bio"><?= htmlspecialchars($user['bio']) ?></p>
                <?php endif; ?>
                <div class="user-stats">
                    <div class="stat">
                        <span class="stat-number"><?= $userStats['photos_count'] ?></span>
                        <span class="stat-label">Photos</span>
                    </div>
                    <div class="stat">
                        <span class="stat-number"><?= $userStats['albums_count'] ?></span>
                        <span class="stat-label">Albums</span>
                    </div>
                    <div class="stat">
                        <span class="stat-number"><?= $userStats['comments_count'] ?></span>
                        <span class="stat-label">Commentaires</span>
                    </div>
                    <div class="stat">
                        <span class="stat-number"><?= $userStats['favorites_count'] ?></span>
                        <span class="stat-label">Favoris</span>
                    </div>
                </div>
            </div>
        </div>
        
        <?php if (isLoggedIn() && getCurrentUserId() == $user['id']): ?>
            <div class="user-actions">
                <a href="<?= BASE_URL ?>/users/edit" class="btn btn-primary">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/>
                        <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>
                    </svg>
                    Modifier le profil
                </a>
            </div>
        <?php endif; ?>
    </div>

    <!-- Tabs Navigation -->
    <div class="tabs-navigation">
        <button class="tab-btn active" data-tab="photos">Photos</button>
        <button class="tab-btn" data-tab="albums">Albums</button>
        <button class="tab-btn" data-tab="comments">Commentaires</button>
        <button class="tab-btn" data-tab="favorites">Favoris</button>
        <?php if (isLoggedIn() && getCurrentUserId() == $user['id']): ?>
            <button class="tab-btn" data-tab="activity">Activité</button>
        <?php endif; ?>
    </div>

    <!-- Tab Content -->
    <div class="tabs-content">
        <!-- Photos Tab -->
        <div class="tab-content active" id="photos-tab">
            <div class="section-header">
                <h2>Photos de <?= htmlspecialchars($user['first_name']) ?></h2>
                <div class="view-options">
                    <button class="view-btn active" data-view="grid">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="3" width="7" height="7"/>
                            <rect x="14" y="3" width="7" height="7"/>
                            <rect x="14" y="14" width="7" height="7"/>
                            <rect x="3" y="14" width="7" height="7"/>
                        </svg>
                    </button>
                    <button class="view-btn" data-view="list">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="8" y1="6" x2="21" y2="6"/>
                            <line x1="8" y1="12" x2="21" y2="12"/>
                            <line x1="8" y1="18" x2="21" y2="18"/>
                            <line x1="3" y1="6" x2="3.01" y2="6"/>
                            <line x1="3" y1="12" x2="3.01" y2="12"/>
                            <line x1="3" y1="18" x2="3.01" y2="18"/>
                        </svg>
                    </button>
                </div>
            </div>
            
            <?php if (!empty($photos)): ?>
                <div class="photos-grid" id="photos-grid">
                    <?php foreach ($photos as $photo): ?>
                        <div class="photo-item">
                            <div class="photo-container">
                                <img src="<?= BASE_URL ?>/assets/images/photos/<?= htmlspecialchars($photo['filename']) ?>" 
                                     alt="<?= htmlspecialchars($photo['title']) ?>"
                                     loading="lazy">
                                <div class="photo-overlay">
                                    <div class="photo-actions">
                                        <a href="<?= BASE_URL ?>/photos/show/<?= $photo['id'] ?>" class="btn btn-sm btn-primary">
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                                <circle cx="12" cy="12" r="3"/>
                                            </svg>
                                        </a>
                                        <?php if (isLoggedIn() && getCurrentUserId() == $user['id']): ?>
                                            <a href="<?= BASE_URL ?>/photos/edit/<?= $photo['id'] ?>" class="btn btn-sm btn-secondary">
                                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/>
                                                    <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                                </svg>
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                    <div class="photo-info">
                                        <h3><?= htmlspecialchars($photo['title']) ?></h3>
                                        <p><?= formatDate($photo['created_at']) ?></p>
                                        <div class="photo-stats">
                                            <span><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg> <?= $photo['views_count'] ?></span>
                                            <span><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg> <?= $photo['favorites_count'] ?></span>
                                            <span><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z"/></svg> <?= $photo['comments_count'] ?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                
                <?php if ($pagination['total_pages'] > 1): ?>
                    <div class="pagination">
                        <?php if ($pagination['current_page'] > 1): ?>
                            <a href="?page=<?= $pagination['current_page'] - 1 ?>" class="pagination-link">Précédent</a>
                        <?php endif; ?>
                        
                        <?php for ($i = 1; $i <= $pagination['total_pages']; $i++): ?>
                            <?php if ($i == $pagination['current_page']): ?>
                                <span class="pagination-link active"><?= $i ?></span>
                            <?php else: ?>
                                <a href="?page=<?= $i ?>" class="pagination-link"><?= $i ?></a>
                            <?php endif; ?>
                        <?php endfor; ?>
                        
                        <?php if ($pagination['current_page'] < $pagination['total_pages']): ?>
                            <a href="?page=<?= $pagination['current_page'] + 1 ?>" class="pagination-link">Suivant</a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            <?php else: ?>
                <div class="empty-state">
                    <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/>
                        <circle cx="8.5" cy="8.5" r="1.5"/>
                        <polyline points="21 15 16 10 5 21"/>
                    </svg>
                    <h3>Aucune photo</h3>
                    <p><?= htmlspecialchars($user['first_name']) ?> n'a pas encore publié de photo.</p>
                </div>
            <?php endif; ?>
        </div>

        <!-- Albums Tab -->
        <div class="tab-content" id="albums-tab">
            <div class="section-header">
                <h2>Albums de <?= htmlspecialchars($user['first_name']) ?></h2>
            </div>
            
            <?php if (!empty($albums)): ?>
                <div class="albums-grid">
                    <?php foreach ($albums as $album): ?>
                        <div class="album-item">
                            <div class="album-cover">
                                <?php if ($album['cover_photo']): ?>
                                    <img src="<?= BASE_URL ?>/assets/images/photos/<?= htmlspecialchars($album['cover_photo']) ?>" 
                                         alt="<?= htmlspecialchars($album['title']) ?>">
                                <?php else: ?>
                                    <div class="album-placeholder">
                                        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/>
                                            <circle cx="8.5" cy="8.5" r="1.5"/>
                                            <polyline points="21 15 16 10 5 21"/>
                                        </svg>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="album-info">
                                <h3><?= htmlspecialchars($album['title']) ?></h3>
                                <p><?= $album['photos_count'] ?> photo(s)</p>
                                <p><?= formatDate($album['created_at']) ?></p>
                                <a href="<?= BASE_URL ?>/albums/show/<?= $album['id'] ?>" class="btn btn-sm btn-primary">Voir</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="empty-state">
                    <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z"/>
                        <polyline points="17 21 17 13 7 13 7 21"/>
                        <polyline points="7 3 7 8 15 8"/>
                    </svg>
                    <h3>Aucun album</h3>
                    <p><?= htmlspecialchars($user['first_name']) ?> n'a pas encore créé d'album.</p>
                </div>
            <?php endif; ?>
        </div>

        <!-- Comments Tab -->
        <div class="tab-content" id="comments-tab">
            <div class="section-header">
                <h2>Commentaires de <?= htmlspecialchars($user['first_name']) ?></h2>
            </div>
            
            <?php if (!empty($comments)): ?>
                <div class="comments-list">
                    <?php foreach ($comments as $comment): ?>
                        <div class="comment-item">
                            <div class="comment-photo">
                                <img src="<?= BASE_URL ?>/assets/images/photos/<?= htmlspecialchars($comment['filename']) ?>" 
                                     alt="<?= htmlspecialchars($comment['photo_title']) ?>">
                            </div>
                            <div class="comment-content">
                                <p><?= htmlspecialchars($comment['content']) ?></p>
                                <div class="comment-meta">
                                    <span>Sur <a href="<?= BASE_URL ?>/photos/show/<?= $comment['photo_id'] ?>"><?= htmlspecialchars($comment['photo_title']) ?></a></span>
                                    <span><?= formatRelativeTime($comment['created_at']) ?></span>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="empty-state">
                    <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z"/>
                    </svg>
                    <h3>Aucun commentaire</h3>
                    <p><?= htmlspecialchars($user['first_name']) ?> n'a pas encore laissé de commentaire.</p>
                </div>
            <?php endif; ?>
        </div>

        <!-- Favorites Tab -->
        <div class="tab-content" id="favorites-tab">
            <div class="section-header">
                <h2>Favoris de <?= htmlspecialchars($user['first_name']) ?></h2>
            </div>
            
            <?php if (!empty($favorites)): ?>
                <div class="photos-grid">
                    <?php foreach ($favorites as $favorite): ?>
                        <div class="photo-item">
                            <div class="photo-container">
                                <img src="<?= BASE_URL ?>/assets/images/photos/<?= htmlspecialchars($favorite['filename']) ?>" 
                                     alt="<?= htmlspecialchars($favorite['title']) ?>"
                                     loading="lazy">
                                <div class="photo-overlay">
                                    <div class="photo-actions">
                                        <a href="<?= BASE_URL ?>/photos/show/<?= $favorite['id'] ?>" class="btn btn-sm btn-primary">
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                                <circle cx="12" cy="12" r="3"/>
                                            </svg>
                                        </a>
                                    </div>
                                    <div class="photo-info">
                                        <h3><?= htmlspecialchars($favorite['title']) ?></h3>
                                        <p>par <?= htmlspecialchars($favorite['username']) ?></p>
                                        <div class="photo-stats">
                                            <span><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg> <?= $favorite['views_count'] ?></span>
                                            <span><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg> <?= $favorite['favorites_count'] ?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="empty-state">
                    <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                    </svg>
                    <h3>Aucun favori</h3>
                    <p><?= htmlspecialchars($user['first_name']) ?> n'a pas encore ajouté de photo en favori.</p>
                </div>
            <?php endif; ?>
        </div>

        <!-- Activity Tab (only for owner) -->
        <?php if (isLoggedIn() && getCurrentUserId() == $user['id']): ?>
            <div class="tab-content" id="activity-tab">
                <div class="section-header">
                    <h2>Votre activité récente</h2>
                </div>
                
                <?php if (!empty($activity)): ?>
                    <div class="activity-list">
                        <?php foreach ($activity as $item): ?>
                            <div class="activity-item">
                                <div class="activity-icon">
                                    <?php
                                    $action = $item['action'];
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
                                    <p class="activity-text"><?= getActivityDescription($item) ?></p>
                                    <span class="activity-time"><?= formatRelativeTime($item['created_at']) ?></span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="empty-state">
                        <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"/>
                            <polyline points="12 6 12 12 16 14"/>
                        </svg>
                        <h3>Aucune activité</h3>
                        <p>Vous n'avez pas encore d'activité enregistrée.</p>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Tab switching
    const tabBtns = document.querySelectorAll('.tab-btn');
    const tabContents = document.querySelectorAll('.tab-content');
    
    tabBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const targetTab = this.getAttribute('data-tab');
            
            // Remove active class from all tabs and contents
            tabBtns.forEach(b => b.classList.remove('active'));
            tabContents.forEach(c => c.classList.remove('active'));
            
            // Add active class to clicked tab and corresponding content
            this.classList.add('active');
            document.getElementById(targetTab + '-tab').classList.add('active');
        });
    });
    
    // View switching for photos
    const viewBtns = document.querySelectorAll('.view-btn');
    const photosGrid = document.getElementById('photos-grid');
    
    viewBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const view = this.getAttribute('data-view');
            
            viewBtns.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            
            if (view === 'list') {
                photosGrid.classList.add('list-view');
            } else {
                photosGrid.classList.remove('list-view');
            }
        });
    });
});
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
