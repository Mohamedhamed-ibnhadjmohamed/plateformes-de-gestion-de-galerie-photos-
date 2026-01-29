<?php 
$pageTitle = 'Connexion';
require_once __DIR__ . '/../partials/header.php'; 
?>

<div class="auth-container">
    <div class="auth-card">
        <div class="auth-header">
            <h1 class="auth-title">Connexion</h1>
            <p class="auth-subtitle">Connectez-vous pour accéder à votre galerie</p>
        </div>
        
        <form action="/users/authenticate" method="POST" class="auth-form">
            <div class="form-group">
                <label for="email" class="form-label">Email</label>
                <div class="input-group">
                    <div class="input-icon">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                            <path d="M22 6l-10 7L2 6"/>
                        </svg>
                    </div>
                    <input type="email" 
                           id="email" 
                           name="email" 
                           class="form-control" 
                           value="<?= htmlspecialchars($data['email'] ?? '') ?>"
                           required
                           placeholder="votre@email.com"
                           autocomplete="email">
                </div>
                <?php if (isset($errors['email'])): ?>
                    <div class="form-error">
                        <?php foreach ($errors['email'] as $error): ?>
                            <span><?= htmlspecialchars($error) ?></span>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="form-group">
                <label for="password" class="form-label">Mot de passe</label>
                <div class="input-group">
                    <div class="input-icon">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                            <path d="M7 11V7a5 5 0 0110 0v4"/>
                        </svg>
                    </div>
                    <input type="password" 
                           id="password" 
                           name="password" 
                           class="form-control" 
                           required
                           placeholder="••••••••"
                           autocomplete="current-password">
                    <button type="button" class="password-toggle" id="password-toggle">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                            <circle cx="12" cy="12" r="3"/>
                        </svg>
                    </button>
                </div>
                <?php if (isset($errors['password'])): ?>
                    <div class="form-error">
                        <?php foreach ($errors['password'] as $error): ?>
                            <span><?= htmlspecialchars($error) ?></span>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="form-options">
                <div class="form-check">
                    <input type="checkbox" id="remember" name="remember" class="form-check-input">
                    <label for="remember" class="form-check-label">Se souvenir de moi</label>
                </div>
                
                <a href="/forgot-password" class="forgot-password">Mot de passe oublié ?</a>
            </div>
            
            <button type="submit" class="btn btn-primary btn-full">
                Se connecter
            </button>
        </form>
        
        <div class="auth-divider">
            <span>ou</span>
        </div>
        
        <div class="social-auth">
            <button class="btn btn-outline btn-full social-btn" data-provider="google">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                    <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                    <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                    <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                </svg>
                Continuer avec Google
            </button>
            
            <button class="btn btn-outline btn-full social-btn" data-provider="facebook">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                </svg>
                Continuer avec Facebook
            </button>
        </div>
        
        <div class="auth-footer">
            <p>Pas encore de compte ? <a href="/users/register" class="auth-link">S'inscrire</a></p>
        </div>
    </div>
    
    <div class="auth-sidebar">
        <div class="sidebar-content">
            <h3>Bienvenue sur Photo Gallery</h3>
            <p>Connectez-vous pour :</p>
            <ul class="feature-list">
                <li>Créer et gérer vos albums photos</li>
                <li>Uploader et organiser vos photos</li>
                <li>Commenter et favoriser les photos</li>
                <li>Suivre vos photographes préférés</li>
                <li>Accéder à des fonctionnalités exclusives</li>
            </ul>
            
            <div class="testimonial">
                <blockquote>
                    "Photo Gallery a transformé la façon dont je partage mes photos. Simple, rapide et magnifique !"
                </blockquote>
                <cite>- Marie P., photographe amateur</cite>
            </div>
        </div>
    </div>
</div>

<?php 
$additionalScripts = '
<script>
document.addEventListener("DOMContentLoaded", function() {
    const passwordToggle = document.getElementById("password-toggle");
    const passwordInput = document.getElementById("password");
    const socialBtns = document.querySelectorAll(".social-btn");
    
    // Password visibility toggle
    passwordToggle.addEventListener("click", function() {
        const type = passwordInput.getAttribute("type") === "password" ? "text" : "password";
        passwordInput.setAttribute("type", type);
        
        const svg = this.querySelector("svg");
        if (type === "text") {
            svg.innerHTML = `
                <path d="M17.94 17.94A10.07 10.07 0 0112 20c-7 0-11-8-11-8a18.45 18.45 0 015.06-5.94M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 8 11 8a18.5 18.5 0 01-2.16 3.19m-6.72-1.07a3 3 0 11-4.24-4.24"/>
                <line x1="1" y1="1" x2="23" y2="23"/>
            `;
        } else {
            svg.innerHTML = `
                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                <circle cx="12" cy="12" r="3"/>
            `;
        }
    });
    
    // Social authentication
    socialBtns.forEach(btn => {
        btn.addEventListener("click", function() {
            const provider = this.dataset.provider;
            // Redirect to OAuth endpoint
            window.location.href = `/auth/${provider}`;
        });
    });
});
</script>';
require_once __DIR__ . '/../partials/footer.php'; 
?>
