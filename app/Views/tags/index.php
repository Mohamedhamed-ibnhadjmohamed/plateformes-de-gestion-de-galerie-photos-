<?php require_once __DIR__ . '/../partials/header.php'; ?>

<div class="page-header">
    <div class="container">
        <h1 class="page-title">Tags</h1>
        <p class="page-description">Découvrez les photos organisées par tags</p>
    </div>
</div>

<div class="container">
    <!-- Search Tags -->
    <div class="search-filter-bar">
        <div class="search-box">
            <input type="text" id="tag-search" placeholder="Rechercher un tag...">
            <button type="button" class="search-btn">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="11" cy="11" r="8"/>
                    <path d="m21 21-4.35-4.35"/>
                </svg>
            </button>
        </div>
        
        <div class="filter-options">
            <select id="sort-tags" class="filter-select">
                <option value="popular">Plus populaires</option>
                <option value="name">Par nom</option>
                <option value="recent">Plus récents</option>
            </select>
        </div>
    </div>

    <!-- Tags Cloud -->
    <?php if (!empty($tags)): ?>
        <div class="tags-cloud">
            <?php foreach ($tags as $tag): ?>
                <a href="/tags/<?= htmlspecialchars($tag['slug']) ?>" 
                   class="tag-cloud-item" 
                   style="font-size: <?= calculateTagSize($tag['photo_count']) ?>px"
                   title="<?= $tag['photo_count'] ?> photo<?= $tag['photo_count'] > 1 ? 's' : '' ?>">
                    <?= htmlspecialchars($tag['name']) ?>
                    <span class="tag-count">(<?= $tag['photo_count'] ?>)</span>
                </a>
            <?php endforeach; ?>
        </div>
        
        <!-- Tags Grid Alternative View -->
        <div class="tags-grid">
            <?php foreach ($tags as $tag): ?>
                <div class="tag-card">
                    <div class="tag-header">
                        <h3 class="tag-name">
                            <a href="/tags/<?= htmlspecialchars($tag['slug']) ?>">
                                <?= htmlspecialchars($tag['name']) ?>
                            </a>
                        </h3>
                        <span class="tag-count"><?= $tag['photo_count'] ?> photo<?= $tag['photo_count'] > 1 ? 's' : '' ?></span>
                    </div>
                    
                    <div class="tag-preview">
                        <?php
                        // Get some preview photos for this tag
                        $previewPhotos = getTagPreviewPhotos($tag['id'], 4);
                        foreach ($previewPhotos as $photo): ?>
                            <a href="/photos/<?= $photo['id'] ?>" class="preview-photo">
                                <img src="/uploads/thumbs/<?= htmlspecialchars($photo['filename']) ?>" 
                                     alt="<?= htmlspecialchars($photo['title'] ?? 'Photo') ?>"
                                     loading="lazy">
                            </a>
                        <?php endforeach; ?>
                    </div>
                    
                    <div class="tag-actions">
                        <a href="/tags/<?= htmlspecialchars($tag['slug']) ?>" class="btn btn-outline btn-sm">
                            Voir toutes les photos
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        
    <?php else: ?>
        <div class="empty-state">
            <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M20.59 13.41l-7.17 7.17a2 2 0 01-2.83 0L2 12V2h10l8.59 8.59a2 2 0 010 2.82z"/>
                <line x1="7" y1="7" x2="7.01" y2="7"/>
            </svg>
            <h3>Aucun tag trouvé</h3>
            <p>Aucun tag n'a été créé pour le moment.</p>
            <a href="/photos" class="btn btn-primary">Explorer les photos</a>
        </div>
    <?php endif; ?>
</div>

<?php 
$additionalScripts = '
<script>
document.addEventListener("DOMContentLoaded", function() {
    const tagSearch = document.getElementById("tag-search");
    const sortTags = document.getElementById("sort-tags");
    const tagsContainer = document.querySelector(".tags-cloud");
    const tagsGrid = document.querySelector(".tags-grid");
    
    // Search functionality
    tagSearch.addEventListener("input", function() {
        const query = this.value.trim().toLowerCase();
        const tagItems = document.querySelectorAll(".tag-cloud-item");
        
        tagItems.forEach(item => {
            const tagName = item.textContent.toLowerCase();
            if (tagName.includes(query)) {
                item.style.display = "inline-block";
            } else {
                item.style.display = "none";
            }
        });
    });
    
    // Sort functionality
    sortTags.addEventListener("change", function() {
        const sortBy = this.value;
        sortTags(sortBy);
    });
    
    function sortTags(sortBy) {
        const tagItems = Array.from(document.querySelectorAll(".tag-cloud-item"));
        
        tagItems.sort((a, b) => {
            const aCount = parseInt(a.querySelector(".tag-count").textContent);
            const bCount = parseInt(b.querySelector(".tag-count").textContent);
            const aName = a.textContent.replace(/\(\d+\)/, "").trim();
            const bName = b.textContent.replace(/\(\d+\)/, "").trim();
            
            switch(sortBy) {
                case "popular":
                    return bCount - aCount;
                case "name":
                    return aName.localeCompare(bName);
                case "recent":
                    // Would need date data for proper sorting
                    return bCount - aCount; // Fallback to popularity
                default:
                    return 0;
            }
        });
        
        // Re-append sorted items
        tagsContainer.innerHTML = "";
        tagItems.forEach(item => tagsContainer.appendChild(item));
    }
});

// Helper function to calculate tag size based on photo count
function calculateTagSize(count) {
    const minSize = 14;
    const maxSize = 32;
    const maxCount = Math.max(...Array.from(document.querySelectorAll(".tag-count")).map(el => parseInt(el.textContent)));
    
    if (maxCount === 0) return minSize;
    
    const ratio = count / maxCount;
    return Math.round(minSize + (maxSize - minSize) * ratio);
}

// Helper function to get preview photos for a tag
function getTagPreviewPhotos(tagId, limit = 4) {
    // This would normally be an AJAX call or passed from controller
    // For now, return empty array as placeholder
    return [];
}
</script>

<style>
.tags-cloud {
    text-align: center;
    padding: 2rem 0;
    margin-bottom: 3rem;
}

.tag-cloud-item {
    display: inline-block;
    margin: 0.5rem;
    padding: 0.5rem 1rem;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    text-decoration: none;
    border-radius: 25px;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.tag-cloud-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
}

.tag-count {
    opacity: 0.8;
    font-size: 0.875em;
}

.tags-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 2rem;
    margin-top: 2rem;
}

.tag-card {
    background: #fff;
    border-radius: 12px;
    padding: 1.5rem;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.tag-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}

.tag-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
}

.tag-name a {
    color: #333;
    text-decoration: none;
    font-size: 1.25rem;
    font-weight: 600;
    transition: color 0.3s ease;
}

.tag-name a:hover {
    color: #007bff;
}

.tag-count {
    background: #f8f9fa;
    color: #666;
    padding: 0.25rem 0.75rem;
    border-radius: 15px;
    font-size: 0.875rem;
    font-weight: 500;
}

.tag-preview {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 0.5rem;
    margin-bottom: 1rem;
    aspect-ratio: 1;
}

.preview-photo {
    border-radius: 8px;
    overflow: hidden;
    position: relative;
}

.preview-photo img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.preview-photo:hover img {
    transform: scale(1.05);
}

.tag-actions {
    text-align: center;
}

@media (max-width: 768px) {
    .tags-grid {
        grid-template-columns: 1fr;
    }
    
    .tag-preview {
        grid-template-columns: repeat(4, 1fr);
    }
}
</style>';
require_once __DIR__ . '/../partials/footer.php'; 

// Helper functions for the view
function calculateTagSize($count) {
    $minSize = 14;
    $maxSize = 32;
    $maxCount = 50; // Adjust based on your data
    
    if ($maxCount === 0) return $minSize;
    
    $ratio = min($count / $maxCount, 1);
    return round($minSize + ($maxSize - $minSize) * $ratio);
}

function getTagPreviewPhotos($tagId, $limit = 4) {
    // This would typically query the database
    // For now, return empty array
    return [];
}
?>
