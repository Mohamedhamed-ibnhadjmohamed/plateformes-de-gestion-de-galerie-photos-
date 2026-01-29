</main>

    <!-- Footer -->
    <footer class="footer bg-dark text-light">
        <div class="container">
            <div class="row">
                <!-- About Section -->
                <div class="col-md-4 mb-4">
                    <h5><?= htmlspecialchars($config['site_name']) ?></h5>
                    <p>Plateforme moderne de gestion de galerie photos avec fonctionnalités avancées d'organisation, de partage et de collaboration.</p>
                    <div class="social-links">
                        <a href="#" class="text-light me-3"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="text-light me-3"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="text-light me-3"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="text-light"><i class="fab fa-github"></i></a>
                    </div>
                </div>
                
                <!-- Quick Links -->
                <div class="col-md-4 mb-4">
                    <h5>Liens Rapides</h5>
                    <ul class="list-unstyled">
                        <li><a href="<?= BASE_URL ?>" class="text-light text-decoration-none">Accueil</a></li>
                        <li><a href="<?= BASE_URL ?>/photos" class="text-light text-decoration-none">Photos</a></li>
                        <li><a href="<?= BASE_URL ?>/albums" class="text-light text-decoration-none">Albums</a></li>
                        <li><a href="<?= BASE_URL ?>/tags" class="text-light text-decoration-none">Tags</a></li>
                        <?php if (isLoggedIn()): ?>
                            <li><a href="<?= BASE_URL ?>/favorites" class="text-light text-decoration-none">Favoris</a></li>
                        <?php endif; ?>
                        <?php if (isAdmin()): ?>
                            <li><a href="<?= BASE_URL ?>/admin/dashboard" class="text-light text-decoration-none">Administration</a></li>
                        <?php endif; ?>
                    </ul>
                </div>
                
                <!-- Contact & Legal -->
                <div class="col-md-4 mb-4">
                    <h5>Contact & Légal</h5>
                    <ul class="list-unstyled">
                        <li><i class="fas fa-envelope me-2"></i><?= htmlspecialchars($config['admin_email']) ?></li>
                        <li><a href="<?= BASE_URL ?>/privacy" class="text-light text-decoration-none">Politique de confidentialité</a></li>
                        <li><a href="<?= BASE_URL ?>/terms" class="text-light text-decoration-none">Conditions d'utilisation</a></li>
                        <li><a href="<?= BASE_URL ?>/help" class="text-light text-decoration-none">Aide</a></li>
                    </ul>
                </div>
            </div>
            
            <hr class="bg-light">
            
            <div class="row">
                <div class="col-md-6">
                    <p class="mb-0">&copy; <?= date('Y') ?> <?= htmlspecialchars($config['site_name']) ?>. Tous droits réservés.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p class="mb-0">
                        <small>
                            Fait avec <i class="fas fa-heart text-danger"></i> utilisant PHP 8+ et MySQL
                        </small>
                    </p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Back to Top Button -->
    <button id="backToTop" class="btn btn-primary back-to-top" aria-label="Back to top">
        <i class="fas fa-arrow-up"></i>
    </button>

    <!-- Loading Overlay -->
    <div id="loadingOverlay" class="loading-overlay d-none">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Chargement...</span>
        </div>
    </div>

    <!-- JavaScript -->
    <script src="<?= BASE_URL ?>/assets/js/bootstrap.bundle.min.js"></script>
    <script src="<?= BASE_URL ?>/assets/js/main.js"></script>
    
    <?php if (isset($customJS)): ?>
        <script><?= $customJS ?></script>
    <?php endif; ?>
    
    <!-- Analytics (if configured) -->
    <?php if (!empty($config['google_analytics_id'])): ?>
        <script async src="https://www.googletagmanager.com/gtag/js?id=<?= $config['google_analytics_id'] ?>"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());
            gtag('config', '<?= $config['google_analytics_id'] ?>');
        </script>
    <?php endif; ?>
</body>
</html>
