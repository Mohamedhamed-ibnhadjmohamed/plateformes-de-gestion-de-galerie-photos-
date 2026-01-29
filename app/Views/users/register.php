<?php 
$pageTitle = 'Inscription';
require_once __DIR__ . '/../partials/header.php'; 
?>

<div class="auth-container">
    <div class="auth-card">
        <div class="auth-header">
            <h1 class="auth-title">Inscription</h1>
            <p class="auth-subtitle">Créez votre compte pour commencer à partager vos photos</p>
        </div>
        
        <form action="/users/store" method="POST" class="auth-form">
            <div class="form-row">
                <div class="form-group">
                    <label for="first_name" class="form-label">Prénom</label>
                    <div class="input-group">
                        <div class="input-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/>
                                <circle cx="12" cy="7" r="4"/>
                            </svg>
                        </div>
                        <input type="text" 
                               id="first_name" 
                               name="first_name" 
                               class="form-control" 
                               value="<?= htmlspecialchars($data['first_name'] ?? '') ?>"
                               maxlength="50"
                               placeholder="Jean">
                    </div>
                    <?php if (isset($errors['first_name'])): ?>
                        <div class="form-error">
                            <?php foreach ($errors['first_name'] as $error): ?>
                                <span><?= htmlspecialchars($error) ?></span>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
                
                <div class="form-group">
                    <label for="last_name" class="form-label">Nom</label>
                    <div class="input-group">
                        <div class="input-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/>
                                <circle cx="12" cy="7" r="4"/>
                            </svg>
                        </div>
                        <input type="text" 
                               id="last_name" 
                               name="last_name" 
                               class="form-control" 
                               value="<?= htmlspecialchars($data['last_name'] ?? '') ?>"
                               maxlength="50"
                               placeholder="Dupont">
                    </div>
                    <?php if (isset($errors['last_name'])): ?>
                        <div class="form-error">
                            <?php foreach ($errors['last_name'] as $error): ?>
                                <span><?= htmlspecialchars($error) ?></span>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="form-group">
                <label for="username" class="form-label">Nom d'utilisateur *</label>
                <div class="input-group">
                    <div class="input-icon">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/>
                            <circle cx="12" cy="7" r="4"/>
                        </svg>
                    </div>
                    <input type="text" 
                           id="username" 
                           name="username" 
                           class="form-control" 
                           value="<?= htmlspecialchars($data['username'] ?? '') ?>"
                           required
                           minlength="3"
                           maxlength="50"
                           placeholder="jdupont"
                           autocomplete="username">
                </div>
                <?php if (isset($errors['username'])): ?>
                    <div class="form-error">
                        <?php foreach ($errors['username'] as $error): ?>
                            <span><?= htmlspecialchars($error) ?></span>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
                <div class="form-help">3-50 caractères, lettres, chiffres et underscores uniquement</div>
            </div>
            
            <div class="form-group">
                <label for="email" class="form-label">Email *</label>
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
                <label for="password" class="form-label">Mot de passe *</label>
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
                           minlength="8"
                           placeholder="••••••••"
                           autocomplete="new-password">
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
                <div class="form-help">Minimum 8 caractères</div>
            </div>
            
            <div class="form-group">
                <label for="password_confirm" class="form-label">Confirmer le mot de passe *</label>
                <div class="input-group">
                    <div class="input-icon">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                            <path d="M7 11V7a5 5 0 0110 0v4"/>
                        </svg>
                    </div>
                    <input type="password" 
                           id="password_confirm" 
                           name="password_confirm" 
                           class="form-control" 
                           required
                           placeholder="••••••••"
                           autocomplete="new-password">
                    <button type="button" class="password-toggle" id="password-confirm-toggle">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                            <circle cx="12" cy="12" r="3"/>
                        </svg>
                    </button>
                </div>
                <?php if (isset($errors['password_confirm'])): ?>
                    <div class="form-error">
                        <?php foreach ($errors['password_confirm'] as $error): ?>
                            <span><?= htmlspecialchars($error) ?></span>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="form-options">
                <div class="form-check">
                    <input type="checkbox" id="terms" name="terms" class="form-check-input" required>
                    <label for="terms" class="form-check-label">
                        J'accepte les <a href="/terms" target="_blank">conditions d'utilisation</a> et la <a href="/privacy" target="_blank">politique de confidentialité</a>
                    </label>
                </div>
                
                <div class="form-check">
                    <input type="checkbox" id="newsletter" name="newsletter" class="form-check-input" checked>
                    <label for="newsletter" class="form-check-label">
                        Je souhaite recevoir la newsletter
                    </label>
                </div>
            </div>
            
            <button type="submit" class="btn btn-primary btn-full">
                Créer mon compte
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
                S'inscrire avec Google
            </button>
            
            <button class="btn btn-outline btn-full social-btn" data-provider="facebook">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                </svg>
                S'inscrire avec Facebook
            </button>
        </div>
        
        <div class="auth-footer">
            <p>Déjà un compte ? <a href="/users/login" class="auth-link">Se connecter</a></p>
        </div>
    </div>
    
    <div class="auth-sidebar">
        <div class="sidebar-content">
            <h3>Rejoignez notre communauté</h3>
            <p>En créant un compte, vous aurez accès à :</p>
            <ul class="feature-list">
                <li>✓ Stockage illimité pour vos photos</li>
                <li>✓ Création d'albums personnalisés</li>
                <li>✓ Outils d'édition intégrés</li>
                <li>✓ Partage facile avec vos proches</li>
                <li>✓ Accès à la communauté de photographes</li>
                <li>✓ Statistiques détaillées sur vos photos</li>
            </ul>
            
            <div class="benefits">
                <div class="benefit-item">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 2L2 7l10 5 10-5-10-5z"/>
                        <path d="M2 17l10 5 10-5M2 12l10 5 10-5"/>
                    </svg>
                    <h4>Simple et Rapide</h4>
                    <p>Interface intuitive pour une prise en main immédiate</p>
                </div>
                
                <div class="benefit-item">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                    </svg>
                    <h4>Sécurisé</h4>
                    <p>Vos photos sont protégées et sauvegardées en toute sécurité</p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php 
$additionalScripts = '
<script>
document.addEventListener("DOMContentLoaded", function() {
    const passwordToggle = document.getElementById("password-toggle");
    const passwordConfirmToggle = document.getElementById("password-confirm-toggle");
    const passwordInput = document.getElementById("password");
    const passwordConfirmInput = document.getElementById("password_confirm");
    const socialBtns = document.querySelectorAll(".social-btn");
    
    // Password visibility toggles
    function setupPasswordToggle(toggleBtn, input) {
        toggleBtn.addEventListener("click", function() {
            const type = input.getAttribute("type") === "password" ? "text" : "password";
            input.setAttribute("type", type);
            
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
    }
    
    setupPasswordToggle(passwordToggle, passwordInput);
    setupPasswordToggle(passwordConfirmToggle, passwordConfirmInput);
    
    // Password confirmation validation
    passwordConfirmInput.addEventListener("input", function() {
        if (this.value !== passwordInput.value) {
            this.setCustomValidity("Les mots de passe ne correspondent pas");
        } else {
            this.setCustomValidity("");
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
    
    // Username validation
    const usernameInput = document.getElementById("username");
    usernameInput.addEventListener("input", function() {
        const value = this.value;
        const isValid = /^[a-zA-Z0-9_]{3,50}$/.test(value);
        
        if (!isValid && value.length > 0) {
            this.setCustomValidity("Le nom d'utilisateur ne peut contenir que des lettres, chiffres et underscores (3-50 caractères)");
        } else {
            this.setCustomValidity("");
        }
    });
});
</script>';
require_once __DIR__ . '/../partials/footer.php'; 
?>
